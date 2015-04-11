Ext.onReady(function () {

    Ext.QuickTips.init();
    Ext.form.Field.prototype.msgTarget = 'side';

    Ext.ns('Ext.gest_asig');
    Ext.gest_asig.stAsig = new Ext.data.Store({
        url: 'notifcobranza/cargarnotifcobranza',
        autoLoad: true,
        reader: new Ext.data.JsonReader({
            root: "data",
            totalProperty: "count",
            id: "id"
        }, [
            {name: 'id'},
            {name: 'nombre'},
            {name: 'correo'}
        ])
    });

    Ext.gest_asig.fpItems = [
        {
            fieldLabel: 'Nombre',
            allowBlank: true,
            name: 'nombre',
            emptyText: 'Este campo esta vacio!'
        },
        {
            fieldLabel: 'Email',
            allowBlank: false,
            name: 'correo',
            vtype:'email',
            emptyText: 'Este campo esta vacio!'
        },
        {
            fieldLabel: 'id',
            allowBlank: true,
            name: 'id',
            hidden: true
        }
    ];

    Ext.gest_asig.fp = new Ext.form.FormPanel({
        frame: true,
        bodyStyle: 'padding: 6px',
        labelWidth: 60,
        defaultType: 'textfield',
        defaults: {
            msgTarget: 'side',
            anchor: '-20'
        },
        items: Ext.gest_asig.fpItems
    });

    Ext.gest_asig.myBtnHandler = function (btn) {
        if (btn.text == 'Adicionar') {
            if (!Ext.gest_asig.win) {
                var title = 'Adicionar usuario a notificar';
                Ext.gest_asig.win = new Ext.Window({
                    closeAction: 'hide',
                    title: title,
                    height: 140,
                    width: 300,
                    constrain: true,
                    items: [Ext.gest_asig.fp],
                    buttons: [
                        {
                            text: 'Aceptar',
                            handler: function (btn) {
                                if (Ext.gest_asig.fp.getForm().isValid()) {
                                    Ext.gest_asig.fp.el.mask('Por favor espere..', 'x-mask-loading');
                                    Ext.gest_asig.fp.getForm().submit({
                                        url: 'notifcobranza/create',
                                        success: function (form, action) {
                                            Ext.gest_asig.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_asig.win.hide();
                                                Ext.gest_asig.stAsig.load();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        },
                                        failure: function (form, action) {
                                            Ext.gest_asig.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_asig.win.hide();
                                                Ext.gest_asig.stAsig.load();
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
                                Ext.gest_asig.win.hide();
                            }
                        }
                    ]
                });
            } else {
                Ext.gest_asig.fp.getForm().reset();
            }
            
            Ext.gest_asig.win.add(Ext.gest_asig.fp);
            Ext.gest_asig.win.doLayout();
            Ext.gest_asig.win.show();
        }

        if (btn.text == 'Modificar') {

            if (!Ext.gest_asig.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento para modificar');
                return false;
            }

            if (!Ext.gest_asig.winmod) {
                var title = 'Modificar usuario a notificar';
                Ext.gest_asig.winmod = new Ext.Window({
                    closeAction: 'hide',
                    title: title,
                    height: 140,
                    width: 300,
                    constrain: true,
                    items: [Ext.gest_asig.fp],
                    buttons: [
                        {
                            text: 'Aceptar',
                            handler: function (btn) {
                                if (Ext.gest_asig.fp.getForm().isValid()) {
                                    Ext.gest_asig.fp.el.mask('Por favor espere..', 'x-mask-loading');
                                    Ext.gest_asig.fp.getForm().submit({
                                        url: 'notifcobranza/update',
                                        success: function (form, action) {
                                            Ext.gest_asig.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_asig.winmod.hide();
                                                Ext.gest_asig.stAsig.load();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        },
                                        failure: function (form, action) {
                                            Ext.gest_asig.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_asig.winmod.hide();
                                                Ext.gest_asig.stAsig.load();
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
                                Ext.gest_asig.winmod.hide();
                            }
                        }
                    ]
                });
            } else {
                Ext.gest_asig.fp.getForm().reset();
            }
            Ext.gest_asig.fp.getForm().reset();
            Ext.gest_asig.winmod.add(Ext.gest_asig.fp);
            Ext.gest_asig.winmod.doLayout();
            Ext.gest_asig.winmod.show();
            Ext.gest_asig.fp.getForm().loadRecord(Ext.gest_asig.sm.getSelected());
        }

        if (btn.text == 'Eliminar') {
            if (!Ext.gest_asig.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento para eliminar');
                return false;
            }

            Ext.MessageBox.show({
                title: 'Mensaje de confirmación',
                msg: '¿ Usted está seguro que desea eliminar la notificación ?',
                buttons: Ext.MessageBox.YESNOCANCEL,
                fn: function (btn) {
                    if (btn == 'yes') {
                        Ext.Ajax.request({
                            url: 'notifcobranza/delete',
                            method: 'POST',
                            params: {id: Ext.gest_asig.sm.getSelected().get("id")},
                            callback: function (options, success, response) {
                                responseData = Ext.decode(response.responseText);
                                if (responseData.success == true) {
                                    Ext.MessageBox.alert('Información', 'La asignatura se eliminó correctamente.'); 
                                    Ext.gest_asig.stAsig.load();
                                }
                            }
                        });
                    }
                },
                icon: Ext.MessageBox.QUESTION
            });

        }
    }

    Ext.gest_asig.addBtn = new Ext.Button({
        text: 'Adicionar',
        handler: Ext.gest_asig.myBtnHandler,
        icon: '../../images/add.png'
    });

    Ext.gest_asig.editBtn = new Ext.Button({
        text: 'Modificar',
        handler: Ext.gest_asig.myBtnHandler,
        icon: '../../images/bogus.png'
    });

    Ext.gest_asig.editDel = new Ext.Button({
        text: 'Eliminar',
        handler: Ext.gest_asig.myBtnHandler,
        icon: '../../images/delete.gif'
    });

    Ext.gest_asig.tbFill = new Ext.Toolbar.Fill();

    Ext.gest_asig.sm = new Ext.grid.RowSelectionModel({});

    Ext.gest_asig.gpAsig = new Ext.grid.GridPanel({
        frame: true,
        iconCls: 'icon-grid',
        autoExpandColumn: 'expandir',
        loadMask: 'Cargando...',
        width: 450,
        store: Ext.gest_asig.stAsig,
        clicksToEdit: 1,
        sm: Ext.gest_asig.sm,
        stripeRows: true,
        floating: false,
        columns: [
            new Ext.grid.RowNumberer(),
            {hidden: true, hideable: false, dataIndex: 'id'},
            {header: 'Nombre', width: 100, dataIndex: 'nombre', id: 'expandir'},
            {header: 'Email', width: 300, dataIndex: 'correo'}
        ],
        tbar: [
            Ext.gest_asig.addBtn, '-',
            Ext.gest_asig.editBtn, '-',
            Ext.gest_asig.editDel
        ],
        bbar: new Ext.PagingToolbar({
            pageSize: 12,
            store: Ext.gest_asig.stAsig,
            displayInfo: true,
            displayMsg: 'Resultados {0} - {1} de {2}',
            emptyMsg: 'Ning&uacute;n resultado para mostrar.'
        })
    });

    new Ext.Viewport({
        layout: 'fit',
        items: [
            Ext.gest_asig.gpAsig
        ]
    });


});