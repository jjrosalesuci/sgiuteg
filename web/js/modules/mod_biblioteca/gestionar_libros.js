Ext.onReady(function () {

    Ext.QuickTips.init();
    Ext.form.Field.prototype.msgTarget = 'side';

    Ext.ns('Ext.gest_libros');
    Ext.gest_libros.stLibros = new Ext.data.Store({
        url: 'libros/cargar',
        listeners: {'beforeload': function (store, objeto) {
            store.baseParams.query = Ext.getCmp('buscar_usuario').getRawValue();
        }},
        autoLoad: true,
        reader: new Ext.data.JsonReader({
            root: "data",
            totalProperty: "count",
            id: "ficha_no"
        }, [
            {name: 'ficha_no'},
            {name: 'titulo'},
            {name: 'autor'},
            {name: 'clasificacion'},
            {name: 'isbn'},
            {name: 'num_adqui'},
            {name: 'biblioteca'},
            {name: 'ejemplar'}
        ])
    });

    Ext.gest_libros.fp = new Ext.form.FormPanel({
        labelAlign: 'top',
        frame:true,
       // title: 'Multi Column, Nested Layouts and Anchoring',
        bodyStyle:'padding:5px 5px 0',
        width: 650,
        anchor:'100%',
        items: [{
            layout:'column',
            items:[
            {
                columnWidth:.5,
                layout: 'form',
                items: [
                {
                    xtype:'textfield',
                    fieldLabel: 'Titulo',
                    allowBlank: false,
                    name: 'titulo',
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    fieldLabel: 'Autor',
                    allowBlank: true,
                    name: 'autor',
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    fieldLabel: 'Clasificacion',
                    allowBlank: true,
                    name: 'clasificacion',
                    anchor:'90%'
                }
                ]
            },
            {
                    fieldLabel: 'id',
                    allowBlank: true,
                    name: 'ficha_no',
                    hidden: true
            },
            {
                columnWidth:.5,
                layout: 'form',
                items: [
                {
                    xtype:'textfield',
                    fieldLabel: 'ISBN',
                    allowBlank: true,
                    name: 'isbn',
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    fieldLabel: 'Num_Adqui',
                    allowBlank: true,
                    name: 'num_adqui',
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    fieldLabel: 'Biblioteca',
                    allowBlank: true,
                    name: 'biblioteca',
                    anchor:'90%'
                }
                ]
            },
            {
                columnWidth:.5,
                layout: 'form',
                items: [
                {
                    xtype:'textfield',
                    fieldLabel: 'Ejemplares',
                    allowBlank: true,
                    name: 'ejemplar',
                    anchor:'90%'
                }
                ]
            }
            ]
        }]
    });

    Ext.gest_libros.myBtnHandler = function (btn) {
        if (btn.text == 'Adicionar') {
            if (!Ext.gest_libros.win) {
                var title = 'Adicionar un Libro';
                Ext.gest_libros.win = new Ext.Window({
                    closeAction: 'hide',
                    title: title,
                    height: 283,
                    width: 640,
                    constrain: true,
                    items: [Ext.gest_libros.fp],
                    buttons: [
                        {
                            text: 'Aceptar',
                            handler: function (btn) {
                                if (Ext.gest_libros.fp.getForm().isValid()) {
                                    Ext.gest_libros.fp.el.mask('Por favor espere..', 'x-mask-loading');
                                    Ext.gest_libros.fp.getForm().submit({
                                        url: 'libros/create',
                                        success: function (form, action) {
                                            Ext.gest_libros.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_libros.win.hide();
                                                Ext.gest_libros.stLibros.load();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        },
                                        failure: function (form, action) {
                                            Ext.gest_libros.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_libros.win.hide();
                                                Ext.gest_libros.stLibros.load();
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
                                Ext.gest_libros.win.hide();
                            }
                        }
                    ]
                });
            } else {
                Ext.gest_libros.fp.getForm().reset();
            }
            
            Ext.gest_libros.win.add(Ext.gest_libros.fp);
            Ext.gest_libros.win.doLayout();
            Ext.gest_libros.win.show();
        }

        if (btn.text == 'Modificar') {

            if (!Ext.gest_libros.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento para modificar');
                return false;
            }

            if (!Ext.gest_libros.winmod) {
                var title = 'Modificar el libro';
                Ext.gest_libros.winmod = new Ext.Window({
                    closeAction: 'hide',
                    title: title,
                    height: 283,
                    width: 640,
                    constrain: true,
                    items: [Ext.gest_libros.fp],
                    buttons: [
                        {
                            text: 'Aceptar',
                            handler: function (btn) {
                                if (Ext.gest_libros.fp.getForm().isValid()) {
                                    Ext.gest_libros.fp.el.mask('Por favor espere..', 'x-mask-loading');
                                    Ext.gest_libros.fp.getForm().submit({
                                        url: 'libros/update',
                                        params: {ficha_no: Ext.gest_libros.sm.getSelected().get("ficha_no")},
                                        success: function (form, action) {
                                            Ext.gest_libros.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_libros.winmod.hide();
                                                Ext.gest_libros.stLibros.load();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        },
                                        failure: function (form, action) {
                                            Ext.gest_libros.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_libros.winmod.hide();
                                                Ext.gest_libros.stLibros.load();
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
                                Ext.gest_libros.winmod.hide();
                            }
                        }
                    ]
                });
            } else {
                Ext.gest_libros.fp.getForm().reset();
            }
            Ext.gest_libros.fp.getForm().reset();
            Ext.gest_libros.winmod.add(Ext.gest_libros.fp);
            Ext.gest_libros.winmod.doLayout();
            Ext.gest_libros.winmod.show();
            Ext.gest_libros.fp.getForm().loadRecord(Ext.gest_libros.sm.getSelected());
        }

        if (btn.text == 'Eliminar') {
            if (!Ext.gest_libros.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento para eliminar');
                return false;
            }

            Ext.MessageBox.show({
                title: 'Mensaje de confirmación',
                msg: '¿ Usted está seguro que desea eliminar el libro ?',
                buttons: Ext.MessageBox.YESNOCANCEL,
                fn: function (btn) {
                    if (btn == 'yes') {
                        Ext.Ajax.request({
                            url: 'libros/delete',
                            method: 'POST',
                            params: {ficha_no: Ext.gest_libros.sm.getSelected().get("ficha_no")},
                            callback: function (options, success, response) {
                                responseData = Ext.decode(response.responseText);
                                if (responseData.success == true) {
                                    Ext.MessageBox.alert('Información', 'El libro se eliminó correctamente.'); 
                                    Ext.gest_libros.stLibros.load();
                                }
                            }
                        });
                    }
                },
                icon: Ext.MessageBox.QUESTION
            });

        }
        if (btn.text == 'Buscar') {
            Ext.gest_libros.stLibros.load({params: {start: 0,limit: 14}});
        }
    }

    Ext.gest_libros.addBtn = new Ext.Button({
        text: 'Adicionar',
        handler: Ext.gest_libros.myBtnHandler,
        icon: '../../images/add.png'
    });

    Ext.gest_libros.editBtn = new Ext.Button({
        text: 'Modificar',
        handler: Ext.gest_libros.myBtnHandler,
        icon: '../../images/bogus.png'
    });

    Ext.gest_libros.editDel = new Ext.Button({
        text: 'Eliminar',
        handler: Ext.gest_libros.myBtnHandler,
        icon: '../../images/delete.gif'
    });
    Ext.gest_libros.buscar  = new Ext.Button({
        text: 'Buscar',
        handler: Ext.gest_libros.myBtnHandler,
        icon: '../../images/lupa.png'
    });

    Ext.gest_libros.tbFill = new Ext.Toolbar.Fill();

    Ext.gest_libros.sm = new Ext.grid.RowSelectionModel({});

    Ext.gest_libros.gpLibros = new Ext.grid.GridPanel({
        frame: true,
        iconCls: 'icon-grid',
        //autoExpandColumn: 'expandir',
        loadMask: 'Cargando...',
        width: 450,
        store: Ext.gest_libros.stLibros,
        clicksToEdit: 1,
        sm: Ext.gest_libros.sm,
        stripeRows: true,
        floating: false,
        columns: [
            new Ext.grid.RowNumberer(),
            {hidden: true, hideable: false, dataIndex: 'ficha_no'},
            {header: 'Título', width: 330, dataIndex: 'titulo'},
            {header: 'Autor', width: 150, dataIndex: 'autor'},
            {header: 'Clasificacion', width: 120, dataIndex: 'clasificacion'},
            {header: 'ISBN', width: 100, dataIndex: 'isbn'},
            {header: 'Biblioteca', width: 60, dataIndex: 'biblioteca'},
            {header: 'Ejemplares', width: 80, dataIndex: 'ejemplar'},
        ],
        tbar: [
            Ext.gest_libros.addBtn,'-',
            Ext.gest_libros.editBtn,'-',
            Ext.gest_libros.editDel,
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
                                         Ext.gest_libros.stLibros.load({params: {start: 0,limit: 14}});
                                    }
                         }
                        }      
            },
            Ext.gest_libros.buscar
        ],
        bbar: new Ext.PagingToolbar({
            pageSize: 14,
            store: Ext.gest_libros.stLibros,
            displayInfo: true,
            displayMsg: 'Resultados {0} - {1} de {2}',
            emptyMsg: 'Ning&uacute;n resultado para mostrar.'
        })
    });

    new Ext.Viewport({
        layout: 'fit',
        items: [
            Ext.gest_libros.gpLibros
        ]
    });


});