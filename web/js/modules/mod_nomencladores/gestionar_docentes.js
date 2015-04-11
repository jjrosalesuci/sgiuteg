Ext.onReady(function () {

    Ext.QuickTips.init();
    Ext.form.Field.prototype.msgTarget = 'side';

    Ext.ns('Ext.gest_docentes');
    Ext.gest_docentes.stDocentes = new Ext.data.Store({
        url: 'docentes/cargardocentes',
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
            {name: 'cedula'},
            {name: 'ruc'},
            {name: 'segundo_nombre'},
            {name: 'apellido'},
            {name: 'apellido_materno'},
            {name: 'direccion_domicilio'},
            {name: 'telefono_domicilio'},
            {name: 'direccion_trabajo'},
            {name: 'telefono_trabajo'},
            {name: 'telefono_celular'},
            {name: 'email'},
            {name: 'titulo_tn'},
            {name: 'titulo_cn'},
            {name: 'universidad_titulo_cn'},
            {name: 'nivel_titulo_cn'},
            {name: 'pais_titulo_cn'}
        ])
    });

    Ext.gest_docentes.fpItems = [
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
        },
        {
            fieldLabel: 'Cedula',
            allowBlank: false,
            name: 'cedula',
            emptyText: 'Este campo esta vacio!'
        }
    ];

    Ext.gest_docentes.fp = new Ext.form.FormPanel({
        frame: true,
        bodyStyle: 'padding: 6px',
        labelWidth: 60,
        defaultType: 'textfield',
        defaults: {
            msgTarget: 'side',
            anchor: '-20'
        },
        items: Ext.gest_docentes.fpItems
    });

    Ext.gest_docentes.myBtnHandler = function (btn) {
        if (btn.text == 'Adicionar') {
            if (!Ext.gest_docentes.win) {
                var title = 'Adicionar un docente';
                Ext.gest_docentes.win = new Ext.Window({
                    closeAction: 'hide',
                    title: title,
                    height: 110,
                    width: 300,
                    constrain: true,
                    items: [Ext.gest_docentes.fp],
                    buttons: [
                        {
                            text: 'Aceptar',
                            handler: function (btn) {
                                if (Ext.gest_docentes.fp.getForm().isValid()) {
                                    Ext.gest_docentes.fp.el.mask('Por favor espere..', 'x-mask-loading');
                                    Ext.gest_docentes.fp.getForm().submit({
                                        url: 'docentes/create',
                                        success: function (form, action) {
                                            Ext.gest_docentes.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_docentes.win.hide();
                                                Ext.gest_docentes.stDocentes.load();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        },
                                        failure: function (form, action) {
                                            Ext.gest_docentes.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_docentes.win.hide();
                                                Ext.gest_docentes.stDocentes.load();
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
                                Ext.gest_docentes.win.hide();
                            }
                        }
                    ]
                });
            } else {
                Ext.gest_docentes.fp.getForm().reset();
            }
            
            Ext.gest_docentes.win.add(Ext.gest_docentes.fp);
            Ext.gest_docentes.win.doLayout();
            Ext.gest_docentes.win.show();
        }

        if (btn.text == 'Modificar') {

            if (!Ext.gest_docentes.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento para modificar');
                return false;
            }

            if (!Ext.gest_docentes.winmod) {
                var title = 'Modificar docente';
                Ext.gest_docentes.winmod = new Ext.Window({
                    closeAction: 'hide',
                    title: title,
                    height: 110,
                    width: 300,
                    constrain: true,
                    items: [Ext.gest_docentes.fp],
                    buttons: [
                        {
                            text: 'Aceptar',
                            handler: function (btn) {
                                if (Ext.gest_docentes.fp.getForm().isValid()) {
                                    Ext.gest_docentes.fp.el.mask('Por favor espere..', 'x-mask-loading');
                                    Ext.gest_docentes.fp.getForm().submit({
                                        url: 'docentes/update',
                                        success: function (form, action) {
                                            Ext.gest_docentes.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_docentes.winmod.hide();
                                                Ext.gest_docentes.stDocentes.load();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        },
                                        failure: function (form, action) {
                                            Ext.gest_docentes.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_docentes.winmod.hide();
                                                Ext.gest_docentes.stDocentes.load();
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
                                Ext.gest_docentes.winmod.hide();
                            }
                        }
                    ]
                });
            } else {
                Ext.gest_docentes.fp.getForm().reset();
            }
            Ext.gest_docentes.fp.getForm().reset();
            Ext.gest_docentes.winmod.add(Ext.gest_docentes.fp);
            Ext.gest_docentes.winmod.doLayout();
            Ext.gest_docentes.winmod.show();
            Ext.gest_docentes.fp.getForm().loadRecord(Ext.gest_docentes.sm.getSelected());
        }

        if (btn.text == 'Eliminar') {
            if (!Ext.gest_docentes.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento para eliminar');
                return false;
            }

            Ext.MessageBox.show({
                title: 'Mensaje de confirmación',
                msg: '¿ Usted está seguro que desea eliminar el docente ?',
                buttons: Ext.MessageBox.YESNOCANCEL,
                fn: function (btn) {
                    if (btn == 'yes') {
                        Ext.Ajax.request({
                            url: 'docentes/delete',
                            method: 'POST',
                            params: {id: Ext.gest_docentes.sm.getSelected().get("id")},
                            callback: function (options, success, response) {
                                responseData = Ext.decode(response.responseText);
                                if (responseData.success == true) {
                                    Ext.MessageBox.alert('Información', 'El docente se eliminó correctamente.'); 
                                    Ext.gest_docentes.stDocentes.load();
                                }
                            }
                        });
                    }
                },
                icon: Ext.MessageBox.QUESTION
            });

        }
        if (btn.text == 'Buscar') {
            Ext.gest_docentes.stDocentes.load({params: {start: 0,limit: 12}});
        }
    }

    Ext.gest_docentes.addBtn = new Ext.Button({
        text: 'Adicionar',
        handler: Ext.gest_docentes.myBtnHandler,
        icon: '../../images/add.png'
    });

    Ext.gest_docentes.editBtn = new Ext.Button({
        text: 'Modificar',
        handler: Ext.gest_docentes.myBtnHandler,
        icon: '../../images/bogus.png'
    });

    Ext.gest_docentes.editDel = new Ext.Button({
        text: 'Eliminar',
        handler: Ext.gest_docentes.myBtnHandler,
        icon: '../../images/delete.gif'
    });
    Ext.gest_docentes.buscar  = new Ext.Button({
        text: 'Buscar',
        handler: Ext.gest_docentes.myBtnHandler,
        icon: '../../images/lupa.png'
    });

    Ext.gest_docentes.tbFill = new Ext.Toolbar.Fill();

    Ext.gest_docentes.sm = new Ext.grid.RowSelectionModel({});

    Ext.gest_docentes.gpAsig = new Ext.grid.GridPanel({
        frame: true,
        iconCls: 'icon-grid',
     //   autoExpandColumn: 'expandir',
        loadMask: 'Cargando...',
        width: 450,
        store: Ext.gest_docentes.stDocentes,
        clicksToEdit: 1,
        sm: Ext.gest_docentes.sm,
        stripeRows: true,
        floating: false,
        columns: [
            new Ext.grid.RowNumberer(),
            {hidden: true, hideable: false, dataIndex: 'id'},
            {header: 'Nombre', width: 120, dataIndex: 'nombre', id: 'expandir'},
            {header: 'Segundo nombre', width: 120, dataIndex: 'segundo_nombre'},
            {header: 'Apellido', width: 120, dataIndex: 'apellido'},
            {header: 'Apellido materno', width: 120, dataIndex: 'apellido_materno'},
            {header: 'Cedula', width: 80, dataIndex: 'cedula'},
            {header: 'Ruc', width: 80, dataIndex: 'ruc'},
            {header: 'Direccion domicilio', width: 200, dataIndex: 'direccion_domicilio'},
            {header: 'Telefono domicilio', width: 120, dataIndex: 'telefono_domicilio'},
            {header: 'Direccion trabajo', width: 200, dataIndex: 'direccion_trabajo'},
            {header: 'Telefono trabajo', width: 120, dataIndex: 'telefono_trabajo'},
            {header: 'Telefono celular', width: 120, dataIndex: 'telefono_celular'},
            {header: 'Email', width: 120, dataIndex: 'email'},
            {header: 'Titulo tn', width: 200, dataIndex: 'titulo_tn'},
            {header: 'Titulo cn', width: 200, dataIndex: 'titulo_cn'},
            {header: 'Universidad titulo cn', width: 200, dataIndex: 'universidad_titulo_cn'},
            {header: 'Nivel_titulo cn', width: 200, dataIndex: 'nivel_titulo_cn'},
            {header: 'Pais titulo cn', width: 100, dataIndex: 'pais_titulo_cn'}
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
                                         Ext.gest_docentes.stDocentes.load({params: {start: 0,limit: 12}});
                                    }
                         }
                        }      
            },
            Ext.gest_docentes.buscar
          //  Ext.gest_docentes.addBtn, '-',
            //Ext.gest_docentes.editBtn, '-',
           // Ext.gest_docentes.editDel
        ],
        bbar: new Ext.PagingToolbar({
            pageSize: 12,
            store: Ext.gest_docentes.stDocentes,
            displayInfo: true,
            displayMsg: 'Resultados {0} - {1} de {2}',
            emptyMsg: 'Ning&uacute;n resultado para mostrar.'
        })
    });

    new Ext.Viewport({
        layout: 'fit',
        items: [
            Ext.gest_docentes.gpAsig
        ]
    });


});