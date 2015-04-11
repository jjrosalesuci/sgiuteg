Ext.onReady(function () {

    Ext.QuickTips.init();
    Ext.form.Field.prototype.msgTarget = 'side';

    Ext.ns('Ext.gest_roles');
    Ext.gest_roles.stRoles = new Ext.data.Store({
        url: 'roles/cargarroles',
        autoLoad: true,
        reader: new Ext.data.JsonReader({
            root: "data",
            totalProperty: "count",
            id: "id"
        }, [
            {name: 'id_rol'},
            {name: 'nombre'}
        ])
    });

    Ext.gest_roles.fpItems = [
        {
            fieldLabel: 'Nombre',
            allowBlank: false,
            name: 'nombre',
            emptyText: 'Este campo esta vacio!'
        },
        {
            fieldLabel: 'id_rol',
            allowBlank: true,
            name: 'id_rol',
            hidden: true
        }
    ];

    Ext.gest_roles.fp = new Ext.form.FormPanel({
        frame: true,
        bodyStyle: 'padding: 6px',
        labelWidth: 60,
        defaultType: 'textfield',
        defaults: {
            msgTarget: 'side',
            anchor: '-20'
        },
        items: Ext.gest_roles.fpItems
    });

    Ext.gest_roles.myBtnHandler = function (btn) {
        if (btn.text == 'Adicionar') {
            if (!Ext.gest_roles.win) {
                var title = 'Adicionar un nuevo rol';
                Ext.gest_roles.win = new Ext.Window({
                    closeAction: 'hide',
                    title: title,
                    height: 110,
                    width: 300,
                    constrain: true,
                    items: [Ext.gest_roles.fp],
                    buttons: [
                        {
                            text: 'Aceptar',
                            handler: function (btn) {
                                if (Ext.gest_roles.fp.getForm().isValid()) {
                                    Ext.gest_roles.fp.el.mask('Por favor espere..', 'x-mask-loading');
                                    Ext.gest_roles.fp.getForm().submit({
                                        url: 'roles/create',
                                        success: function (form, action) {
                                            Ext.gest_roles.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_roles.win.hide();
                                                Ext.gest_roles.stRoles.load();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        },
                                        failure: function (form, action) {
                                            Ext.gest_roles.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_roles.win.hide();
                                                Ext.gest_roles.stRoles.load();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        }
                                    });
                                }
                            }
                        },
                        {
                            text: 'Cancelar',
                            handler: function (btn) {
                                Ext.gest_roles.win.hide();
                            }
                        }
                    ]
                });
            } else {
                Ext.gest_roles.fp.getForm().reset();
            }
            
            Ext.gest_roles.win.add(Ext.gest_roles.fp);
            Ext.gest_roles.win.doLayout();
            Ext.gest_roles.win.show();
        }

        if (btn.text == 'Modificar') {

            if (!Ext.gest_roles.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento para modificar');
                return false;
            }

            if (!Ext.gest_roles.winmod) {
                var title = 'Modificar el rol';
                Ext.gest_roles.winmod = new Ext.Window({
                    closeAction: 'hide',
                    title: title,
                    height: 110,
                    width: 300,
                    constrain: true,
                    items: [Ext.gest_roles.fp],
                    buttons: [
                        {
                            text: 'Aceptar',
                            handler: function (btn) {
                                if (Ext.gest_roles.fp.getForm().isValid()) {
                                    Ext.gest_roles.fp.el.mask('Por favor espere..', 'x-mask-loading');
                                    Ext.gest_roles.fp.getForm().submit({
                                        url: 'roles/update',
                                        success: function (form, action) {
                                            Ext.gest_roles.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_roles.winmod.hide();
                                                Ext.gest_roles.stRoles.load();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        },
                                        failure: function (form, action) {
                                            Ext.gest_roles.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_roles.winmod.hide();
                                                Ext.gest_roles.stRoles.load();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        }
                                    });
                                }
                            }
                        },
                        {
                            text: 'Cancelar',
                            handler: function (btn) {
                                Ext.gest_roles.winmod.hide();
                            }
                        }
                    ]
                });
            } else {
                Ext.gest_roles.fp.getForm().reset();
            }
            Ext.gest_roles.fp.getForm().reset();
            Ext.gest_roles.winmod.add(Ext.gest_roles.fp);
            Ext.gest_roles.winmod.doLayout();
            Ext.gest_roles.winmod.show();
            Ext.gest_roles.fp.getForm().loadRecord(Ext.gest_roles.sm.getSelected());
        }

        if (btn.text == 'Eliminar') {
            if (!Ext.gest_roles.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento para eliminar');
                return false;
            }

            Ext.MessageBox.show({
                title: 'Mensaje de confirmacion',
                msg: '¿ Usted esta seguro que desea eliminar el rol ?',
                buttons: Ext.MessageBox.YESNOCANCEL,
                fn: function (btn) {
                    if (btn == 'yes') {
                        Ext.Ajax.request({
                            url: 'roles/delete',
                            method: 'POST',
                            params: {id_rol: Ext.gest_roles.sm.getSelected().get("id_rol")},
                            callback: function (options, success, response) {
                                responseData = Ext.decode(response.responseText);
                                if (responseData.success == true) {
                                    Ext.MessageBox.alert('Informacion', 'Seleccione elimino correctamente.'); 
                                    Ext.gest_roles.stRoles.load();
                                }
                            }
                        });
                    }
                },
                icon: Ext.MessageBox.QUESTION
            });

        }
    }

    Ext.gest_roles.addBtn = new Ext.Button({
        text: 'Adicionar',
        handler: Ext.gest_roles.myBtnHandler,
        icon: '../../images/add.png'
    });

    Ext.gest_roles.editBtn = new Ext.Button({
        text: 'Modificar',
        handler: Ext.gest_roles.myBtnHandler,
        icon: '../../images/bogus.png'
    });

    Ext.gest_roles.editDel = new Ext.Button({
        text: 'Eliminar',
        handler: Ext.gest_roles.myBtnHandler,
        icon: '../../images/delete.gif'
    });

    Ext.gest_roles.tbFill = new Ext.Toolbar.Fill();

    Ext.gest_roles.sm = new Ext.grid.RowSelectionModel({});

    Ext.gest_roles.gpRoles = new Ext.grid.GridPanel({
        frame: true,
        iconCls: 'icon-grid',
        autoExpandColumn: 'expandir',
        loadMask: 'Cargando...',
        width: 450,
        store: Ext.gest_roles.stRoles,
        clicksToEdit: 1,
        sm: Ext.gest_roles.sm,
        stripeRows: true,
        floating: false,
        columns: [
            new Ext.grid.RowNumberer(),
            {hidden: true, hideable: false, dataIndex: 'id_rol'},
            {header: 'Nombre', width: 200, dataIndex: 'nombre', id: 'expandir'}
        ],
        tbar: [
            Ext.gest_roles.addBtn, '-',
            Ext.gest_roles.editBtn, '-',
            Ext.gest_roles.editDel
        ],
        bbar: new Ext.PagingToolbar({
            pageSize: 12,
            store: Ext.gest_roles.stRoles,
            displayInfo: true,
            displayMsg: 'Resultados {0} - {1} de {2}',
            emptyMsg: 'Ning&uacute;n resultado para mostrar.'
        })
    });

    new Ext.Viewport({
        layout: 'fit',
        items: [
            Ext.gest_roles.gpRoles
        ]
    });


});