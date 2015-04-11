Ext.onReady(function () {

    Ext.QuickTips.init();
    Ext.form.Field.prototype.msgTarget = 'side';

    Ext.ns('Ext.gest_periodo');
    Ext.gest_periodo.stPeriodo = new Ext.data.Store({
        url: 'semestres/cargarsemestres',
        autoLoad: true,
        reader: new Ext.data.JsonReader({
            root: "data",
            totalProperty: "count",
            id: "id"
        }, [
            {name: 'id'},
            {name: 'nombre'}
        ])
    });

    Ext.gest_periodo.fpItems = [
        {
            fieldLabel: 'Nombre',
            allowBlank: false,
            name: 'nombre',
            emptyText: 'Este campo esta vacio!'
        },
        {
            fieldLabel: 'id',
            allowBlank: true,
            name: 'id',
            hidden: true
        }
    ];

    Ext.gest_periodo.fp = new Ext.form.FormPanel({
        frame: true,
        bodyStyle: 'padding: 6px',
        labelWidth: 60,
        defaultType: 'textfield',
        defaults: {
            msgTarget: 'side',
            anchor: '-20'
        },
        items: Ext.gest_periodo.fpItems
    });

    Ext.gest_periodo.myBtnHandler = function (btn) {
        if (btn.text == 'Adicionar') {
            if (!Ext.gest_periodo.win) {
                var title = 'Adicionar un semestre';
                Ext.gest_periodo.win = new Ext.Window({
                    closeAction: 'hide',
                    title: title,
                    height: 110,
                    width: 300,
                    constrain: true,
                    items: [Ext.gest_periodo.fp],
                    buttons: [
                        {
                            text: 'Aceptar',
                            handler: function (btn) {
                                if (Ext.gest_periodo.fp.getForm().isValid()) {
                                    Ext.gest_periodo.fp.el.mask('Por favor espere..', 'x-mask-loading');
                                    Ext.gest_periodo.fp.getForm().submit({
                                        url: 'semestres/create',
                                        success: function (form, action) {
                                            Ext.gest_periodo.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_periodo.win.hide();
                                                Ext.gest_periodo.stPeriodo.load();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        },
                                        failure: function (form, action) {
                                            Ext.gest_periodo.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_periodo.win.hide();
                                                Ext.gest_periodo.stPeriodo.load();
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
                                Ext.gest_periodo.win.hide();
                            }
                        }
                    ]
                });
            } else {
                Ext.gest_periodo.fp.getForm().reset();
            }
            
            Ext.gest_periodo.win.add(Ext.gest_periodo.fp);
            Ext.gest_periodo.win.doLayout();
            Ext.gest_periodo.win.show();
        }

        if (btn.text == 'Modificar') {

            if (!Ext.gest_periodo.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento para modificar');
                return false;
            }

            if (!Ext.gest_periodo.winmod) {
                var title = 'Modificar un semestre';
                Ext.gest_periodo.winmod = new Ext.Window({
                    closeAction: 'hide',
                    title: title,
                    height: 110,
                    width: 300,
                    constrain: true,
                    items: [Ext.gest_periodo.fp],
                    buttons: [
                        {
                            text: 'Aceptar',
                            handler: function (btn) {
                                if (Ext.gest_periodo.fp.getForm().isValid()) {
                                    Ext.gest_periodo.fp.el.mask('Por favor espere..', 'x-mask-loading');
                                    Ext.gest_periodo.fp.getForm().submit({
                                        url: 'semestres/update',
                                        success: function (form, action) {
                                            Ext.gest_periodo.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_periodo.winmod.hide();
                                                Ext.gest_periodo.stPeriodo.load();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        },
                                        failure: function (form, action) {
                                            Ext.gest_periodo.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_periodo.winmod.hide();
                                                Ext.gest_periodo.stPeriodo.load();
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
                                Ext.gest_periodo.winmod.hide();
                            }
                        }
                    ]
                });
            } else {
                Ext.gest_periodo.fp.getForm().reset();
            }
            Ext.gest_periodo.fp.getForm().reset();
            Ext.gest_periodo.winmod.add(Ext.gest_periodo.fp);
            Ext.gest_periodo.winmod.doLayout();
            Ext.gest_periodo.winmod.show();
            Ext.gest_periodo.fp.getForm().loadRecord(Ext.gest_periodo.sm.getSelected());
        }

        if (btn.text == 'Eliminar') {
            if (!Ext.gest_periodo.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento para eliminar');
                return false;
            }

            Ext.MessageBox.show({
                title: 'Mensaje de confirmación',
                msg: '¿ Usted está seguro que desea eliminar el semestre ?',
                buttons: Ext.MessageBox.YESNOCANCEL,
                fn: function (btn) {
                    if (btn == 'yes') {
                        Ext.Ajax.request({
                            url: 'semestres/delete',
                            method: 'POST',
                            params: {id: Ext.gest_periodo.sm.getSelected().get("id")},
                            callback: function (options, success, response) {
                                responseData = Ext.decode(response.responseText);
                                if (responseData.success == true) {
                                    Ext.MessageBox.alert('Información', 'EL semestre se eliminó correctamente.'); 
                                    Ext.gest_periodo.stPeriodo.load();
                                }
                            }
                        });
                    }
                },
                icon: Ext.MessageBox.QUESTION
            });

        }
    }

    Ext.gest_periodo.addBtn = new Ext.Button({
        text: 'Adicionar',
        handler: Ext.gest_periodo.myBtnHandler,
        icon: '../../images/add.png'
    });

    Ext.gest_periodo.editBtn = new Ext.Button({
        text: 'Modificar',
        handler: Ext.gest_periodo.myBtnHandler,
        icon: '../../images/bogus.png'
    });

    Ext.gest_periodo.editDel = new Ext.Button({
        text: 'Eliminar',
        handler: Ext.gest_periodo.myBtnHandler,
        icon: '../../images/delete.gif'
    });

    Ext.gest_periodo.tbFill = new Ext.Toolbar.Fill();

    Ext.gest_periodo.sm = new Ext.grid.RowSelectionModel({});

    Ext.gest_periodo.gpPeriodo = new Ext.grid.GridPanel({
        frame: true,
        iconCls: 'icon-grid',
        autoExpandColumn: 'expandir',
        loadMask: 'Cargando...',
        width: 450,
        store: Ext.gest_periodo.stPeriodo,
        clicksToEdit: 1,
        sm: Ext.gest_periodo.sm,
        stripeRows: true,
        floating: false,
        columns: [
            new Ext.grid.RowNumberer(),
            {hidden: true, hideable: false, dataIndex: 'id'},
            {header: 'Nombre', width: 200, dataIndex: 'nombre', id: 'expandir'}
        ],
        /*tbar: [
            Ext.gest_periodo.addBtn, '-',
            Ext.gest_periodo.editBtn, '-',
            Ext.gest_periodo.editDel
        ],*/
        bbar: new Ext.PagingToolbar({
            pageSize: 12,
            store: Ext.gest_periodo.stPeriodo,
            displayInfo: true,
            displayMsg: 'Resultados {0} - {1} de {2}',
            emptyMsg: 'Ning&uacute;n resultado para mostrar.'
        })
    });

    new Ext.Viewport({
        layout: 'fit',
        items: [
            Ext.gest_periodo.gpPeriodo
        ]
    });


});