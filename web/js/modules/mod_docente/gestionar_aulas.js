Ext.onReady(function () {

    Ext.QuickTips.init();
    Ext.form.Field.prototype.msgTarget = 'side';

    Ext.ns('Ext.gest_aulas');
    Ext.gest_aulas.stAulas = new Ext.data.Store({
        url: 'aulas/cargar',
        listeners: {'beforeload': function (store, objeto) {
            store.baseParams.query = Ext.getCmp('buscar_usuario').getRawValue();
        }},
        autoLoad: true,
        reader: new Ext.data.JsonReader({
            root: "data",
            totalProperty: "count",
            id: "id"
        }, [
            {name: 'id'},
            {name: 'nombre'},
            {name: 'edificio'}
        ])
    });

    Ext.gest_aulas.fpItems = [
        {
            fieldLabel: 'Nombre',
            allowBlank: false,
            name: 'nombre',
            emptyText: 'Este campo esta vacio!'
        },
        {
            fieldLabel: 'Edificio',
            allowBlank: false,
            name: 'edificio',
            emptyText: 'Este campo esta vacio!'
        },
        {
            fieldLabel: 'id',
            allowBlank: true,
            name: 'id',
            hidden: true
        }
    ];

    Ext.gest_aulas.fp = new Ext.form.FormPanel({
        frame: true,
        bodyStyle: 'padding: 6px',
        labelWidth: 60,
        defaultType: 'textfield',
        defaults: {
            msgTarget: 'side',
            anchor: '-20'
        },
        items: Ext.gest_aulas.fpItems
    });

    Ext.gest_aulas.myBtnHandler = function (btn) {
        if (btn.text == 'Adicionar') {
            if (!Ext.gest_aulas.win) {
                var title = 'Adicionar';
                Ext.gest_aulas.win = new Ext.Window({
                    closeAction: 'hide',
                    title: title,
                    height: 141,
                    width: 300,
                    constrain: true,
                    items: [Ext.gest_aulas.fp],
                    buttons: [
                        {
                            text: 'Aceptar',
                            handler: function (btn) {
                                if (Ext.gest_aulas.fp.getForm().isValid()) {
                                    Ext.gest_aulas.fp.el.mask('Por favor espere..', 'x-mask-loading');
                                    Ext.gest_aulas.fp.getForm().submit({
                                        url: 'aulas/create',
                                        success: function (form, action) {
                                            Ext.gest_aulas.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_aulas.win.hide();
                                                Ext.gest_aulas.stAulas.load();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        },
                                        failure: function (form, action) {
                                            Ext.gest_aulas.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_aulas.win.hide();
                                                Ext.gest_aulas.stAulas.load();
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
                                Ext.gest_aulas.win.hide();
                            }
                        }
                    ]
                });
            } else {
                Ext.gest_aulas.fp.getForm().reset();
            }
            
            Ext.gest_aulas.win.add(Ext.gest_aulas.fp);
            Ext.gest_aulas.win.doLayout();
            Ext.gest_aulas.win.show();
        }

        if (btn.text == 'Modificar') {

            if (!Ext.gest_aulas.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento para modificar');
                return false;
            }

            if (!Ext.gest_aulas.winmod) {
                var title = 'Modificar';
                Ext.gest_aulas.winmod = new Ext.Window({
                    closeAction: 'hide',
                    title: title,
                    height: 141,
                    width: 300,
                    constrain: true,
                    items: [Ext.gest_aulas.fp],
                    buttons: [
                        {
                            text: 'Aceptar',
                            handler: function (btn) {
                                if (Ext.gest_aulas.fp.getForm().isValid()) {
                                    Ext.gest_aulas.fp.el.mask('Por favor espere..', 'x-mask-loading');
                                    Ext.gest_aulas.fp.getForm().submit({
                                        url: 'aulas/update',
                                        params: {id: Ext.gest_aulas.sm.getSelected().get("id")},
                                        success: function (form, action) {
                                            Ext.gest_aulas.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_aulas.winmod.hide();
                                                Ext.gest_aulas.stAulas.load();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        },
                                        failure: function (form, action) {
                                            Ext.gest_aulas.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_aulas.winmod.hide();
                                                Ext.gest_aulas.stAulas.load();
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
                                Ext.gest_aulas.winmod.hide();
                            }
                        }
                    ]
                });
            } else {
                Ext.gest_aulas.fp.getForm().reset();
            }
            Ext.gest_aulas.fp.getForm().reset();
            Ext.gest_aulas.winmod.add(Ext.gest_aulas.fp);
            Ext.gest_aulas.winmod.doLayout();
            Ext.gest_aulas.winmod.show();
            Ext.gest_aulas.fp.getForm().loadRecord(Ext.gest_aulas.sm.getSelected());
        }

        if (btn.text == 'Eliminar') {
            if (!Ext.gest_aulas.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento para eliminar');
                return false;
            }

            Ext.MessageBox.show({
                title: 'Mensaje de confirmación',
                msg: '¿ Usted está seguro que desea eliminar este elemento ?',
                buttons: Ext.MessageBox.YESNOCANCEL,
                fn: function (btn) {
                    if (btn == 'yes') {
                        Ext.Ajax.request({
                            url: 'aulas/delete',
                            method: 'POST',
                            params: {id: Ext.gest_aulas.sm.getSelected().get("id")},
                            callback: function (options, success, response) {
                                responseData = Ext.decode(response.responseText);
                                if (responseData.success == true) {
                                    Ext.MessageBox.alert('Información', 'Se eliminó correctamente.'); 
                                    Ext.gest_aulas.stAulas.load();
                                }
                            }
                        });
                    }
                },
                icon: Ext.MessageBox.QUESTION
            });

        }
        if (btn.text == 'Buscar') {
            Ext.gest_aulas.stAulas.load({params: {start: 0,limit: 14}});
        }
    }

    Ext.gest_aulas.addBtn = new Ext.Button({
        text: 'Adicionar',
        handler: Ext.gest_aulas.myBtnHandler,
        icon: '../../images/add.png'
    });

    Ext.gest_aulas.editBtn = new Ext.Button({
        text: 'Modificar',
        handler: Ext.gest_aulas.myBtnHandler,
        icon: '../../images/bogus.png'
    });

    Ext.gest_aulas.editDel = new Ext.Button({
        text: 'Eliminar',
        handler: Ext.gest_aulas.myBtnHandler,
        icon: '../../images/delete.gif'
    });
    Ext.gest_aulas.buscar  = new Ext.Button({
        text: 'Buscar',
        handler: Ext.gest_aulas.myBtnHandler,
        icon: '../../images/lupa.png'
    });

    Ext.gest_aulas.tbFill = new Ext.Toolbar.Fill();

    Ext.gest_aulas.sm = new Ext.grid.RowSelectionModel({});

    Ext.gest_aulas.gpAulas = new Ext.grid.GridPanel({
        frame: true,
        iconCls: 'icon-grid',
        //autoExpandColumn: 'expandir',
        loadMask: 'Cargando...',
        width: 450,
        store: Ext.gest_aulas.stAulas,
        clicksToEdit: 1,
        sm: Ext.gest_aulas.sm,
        stripeRows: true,
        floating: false,
        columns: [
            new Ext.grid.RowNumberer(),
            {hidden: true, hideable: false, dataIndex: 'id'},
            {header: 'Nombre', width: 400, dataIndex: 'nombre'},
            {header: 'Edificio', width: 400, dataIndex: 'edificio'}
        ],
        tbar: [
            Ext.gest_aulas.addBtn,'-',
            Ext.gest_aulas.editBtn,'-',
            Ext.gest_aulas.editDel,
            '->',
            {
              xtype:'textfield',
              fieldLabel: 'Nombre(s)',
              allowBlank: true,
              name: 'nombres',
              id:'buscar_usuario',
              enableKeyEvents:true,
              listeners:{
                         'keyup':function(textField, eventoObject){
                                    if(eventoObject.getCharCode() == 13){
                                         Ext.gest_aulas.stAulas.load({params: {start: 0,limit: 14}});
                                    }
                         }
                        }      
            },
            Ext.gest_aulas.buscar
        ],
        bbar: new Ext.PagingToolbar({
            pageSize: 14,
            store: Ext.gest_aulas.stAulas,
            displayInfo: true,
            displayMsg: 'Resultados {0} - {1} de {2}',
            emptyMsg: 'Ning&uacute;n resultado para mostrar.'
        })
    });

    new Ext.Viewport({
        layout: 'fit',
        items: [
            Ext.gest_aulas.gpAulas
        ]
    });


});