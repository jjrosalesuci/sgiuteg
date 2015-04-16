Ext.onReady(function () {

    Ext.QuickTips.init();
    Ext.form.Field.prototype.msgTarget = 'side';

    Ext.ns('Ext.gest_carreras');
    Ext.gest_carreras.stCarreras = new Ext.data.Store({
        url: 'carreras/cargarcarreras',
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
            {name: 'cod_legal'},
            {name: 'descripcion'},
            {name: 'estatus'},
            {name: 'fecha_cierre'},
            {name: 'modalidad'},
            {name: 'tipo_modalidad'},
            {name: 'id_unidad'}

        ])
    });

    Ext.gest_carreras.fpItems = [
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

    Ext.gest_carreras.fp = new Ext.form.FormPanel({
        frame: true,
        bodyStyle: 'padding: 6px',
        labelWidth: 60,
        defaultType: 'textfield',
        defaults: {
            msgTarget: 'side',
            anchor: '-20'
        },
        items: Ext.gest_carreras.fpItems
    });

    Ext.gest_carreras.myBtnHandler = function (btn) {
        if (btn.text == 'Adicionar') {
            if (!Ext.gest_carreras.win) {
                var title = 'Adicionar una carrera';
                Ext.gest_carreras.win = new Ext.Window({
                    closeAction: 'hide',
                    title: title,
                    height: 110,
                    width: 300,
                    constrain: true,
                    items: [Ext.gest_carreras.fp],
                    buttons: [
                        {
                            text: 'Aceptar',
                            handler: function (btn) {
                                if (Ext.gest_carreras.fp.getForm().isValid()) {
                                    Ext.gest_carreras.fp.el.mask('Por favor espere..', 'x-mask-loading');
                                    Ext.gest_carreras.fp.getForm().submit({
                                        url: 'carreras/create',
                                        success: function (form, action) {
                                            Ext.gest_carreras.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_carreras.win.hide();
                                                Ext.gest_carreras.stCarreras.load();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        },
                                        failure: function (form, action) {
                                            Ext.gest_carreras.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_carreras.win.hide();
                                                Ext.gest_carreras.stCarreras.load();
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
                                Ext.gest_carreras.win.hide();
                            }
                        }
                    ]
                });
            } else {
                Ext.gest_carreras.fp.getForm().reset();
            }
            
            Ext.gest_carreras.win.add(Ext.gest_carreras.fp);
            Ext.gest_carreras.win.doLayout();
            Ext.gest_carreras.win.show();
        }

        if (btn.text == 'Modificar') {

            if (!Ext.gest_carreras.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento para modificar');
                return false;
            }

            if (!Ext.gest_carreras.winmod) {
                var title = 'Modificar la carrera';
                Ext.gest_carreras.winmod = new Ext.Window({
                    closeAction: 'hide',
                    title: title,
                    height: 110,
                    width: 300,
                    constrain: true,
                    items: [Ext.gest_carreras.fp],
                    buttons: [
                        {
                            text: 'Aceptar',
                            handler: function (btn) {
                                if (Ext.gest_carreras.fp.getForm().isValid()) {
                                    Ext.gest_carreras.fp.el.mask('Por favor espere..', 'x-mask-loading');
                                    Ext.gest_carreras.fp.getForm().submit({
                                        url: 'carreras/update',
                                        success: function (form, action) {
                                            Ext.gest_carreras.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_carreras.winmod.hide();
                                                Ext.gest_carreras.stCarreras.load();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        },
                                        failure: function (form, action) {
                                            Ext.gest_carreras.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_carreras.winmod.hide();
                                                Ext.gest_carreras.stCarreras.load();
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
                                Ext.gest_carreras.winmod.hide();
                            }
                        }
                    ]
                });
            } else {
                Ext.gest_carreras.fp.getForm().reset();
            }
            Ext.gest_carreras.fp.getForm().reset();
            Ext.gest_carreras.winmod.add(Ext.gest_carreras.fp);
            Ext.gest_carreras.winmod.doLayout();
            Ext.gest_carreras.winmod.show();
            Ext.gest_carreras.fp.getForm().loadRecord(Ext.gest_carreras.sm.getSelected());
        }

        if (btn.text == 'Eliminar') {
            if (!Ext.gest_carreras.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento para eliminar');
                return false;
            }

            Ext.MessageBox.show({
                title: 'Mensaje de confirmación',
                msg: '¿ Usted está seguro que desea eliminar la carrera ?',
                buttons: Ext.MessageBox.YESNOCANCEL,
                fn: function (btn) {
                    if (btn == 'yes') {
                        Ext.Ajax.request({
                            url: 'carreras/delete',
                            method: 'POST',
                            params: {id: Ext.gest_carreras.sm.getSelected().get("id")},
                            callback: function (options, success, response) {
                                responseData = Ext.decode(response.responseText);
                                if (responseData.success == true) {
                                    Ext.MessageBox.alert('Información', 'La carrera se eliminó correctamente.'); 
                                    Ext.gest_carreras.stCarreras.load();
                                }
                            }
                        });
                    }
                },
                icon: Ext.MessageBox.QUESTION
            });

        }
        if (btn.text == 'Buscar') {
            Ext.gest_carreras.stCarreras.load({params: {start: 0,limit: 12}});
        }
    }

    Ext.gest_carreras.addBtn = new Ext.Button({
        text: 'Adicionar',
        handler: Ext.gest_carreras.myBtnHandler,
        icon: '../../images/add.png'
    });

    Ext.gest_carreras.editBtn = new Ext.Button({
        text: 'Modificar',
        handler: Ext.gest_carreras.myBtnHandler,
        icon: '../../images/bogus.png'
    });

    Ext.gest_carreras.editDel = new Ext.Button({
        text: 'Eliminar',
        handler: Ext.gest_carreras.myBtnHandler,
        icon: '../../images/delete.gif'
    });
    Ext.gest_carreras.buscar  = new Ext.Button({
        text: 'Buscar',
        handler: Ext.gest_carreras.myBtnHandler,
        icon: '../../images/lupa.png'
    });

    Ext.gest_carreras.tbFill = new Ext.Toolbar.Fill();

    Ext.gest_carreras.sm = new Ext.grid.RowSelectionModel({});

    Ext.gest_carreras.gpCarreras = new Ext.grid.GridPanel({
        frame: true,
        iconCls: 'icon-grid',
       // autoExpandColumn: 'expandir',
        loadMask: 'Cargando...',
        width: 450,
        store: Ext.gest_carreras.stCarreras,
        clicksToEdit: 1,
        sm: Ext.gest_carreras.sm,
        stripeRows: true,
        floating: false,
        columns: [
            new Ext.grid.RowNumberer(),
            {hidden: true, hideable: false, dataIndex: 'id'},
            {header: 'Nombre', width: 500, dataIndex: 'nombre'},
            {header: 'Codigo legal', width: 100, dataIndex: 'cod_legal'},
            {header: 'Descripcion', width: 200, dataIndex: 'descripcion'},
            {header: 'Estatus', width: 50, dataIndex: 'estatus'},
            {header: 'Fecha cierre', width: 200, dataIndex: 'fecha_cierre'},
            {header: 'Modalidad', width: 100, dataIndex: 'modalidad'},
            {header: 'Tipo modalidad', width: 100, dataIndex: 'tipo_modalidad'},
            //{header: 'id_unidad ', width: 200, dataIndex: 'nombre'}

        ],
        tbar: [
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
                                         Ext.gest_carreras.stCarreras.load({params: {start: 0,limit: 12}});
                                    }
                         }
                        }      
            },
            Ext.gest_carreras.buscar
          //  Ext.gest_carreras.addBtn, '-',
            //Ext.gest_carreras.editBtn, '-',
           // Ext.gest_carreras.editDel
        ],
        bbar: new Ext.PagingToolbar({
            pageSize: 12,
            store: Ext.gest_carreras.stCarreras,
            displayInfo: true,
            displayMsg: 'Resultados {0} - {1} de {2}',
            emptyMsg: 'Ning&uacute;n resultado para mostrar.'
        })
    });

    new Ext.Viewport({
        layout: 'fit',
        items: [
            Ext.gest_carreras.gpCarreras
        ]
    });


});