Ext.onReady(function () {

    Ext.QuickTips.init();
    Ext.form.Field.prototype.msgTarget = 'side';

    Ext.ns('Ext.gest_alumnos');
    Ext.gest_alumnos.stAlumnos = new Ext.data.Store({
        url: 'alumnos/cargaralumnos',
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
            {name: 'cedula'},
            {name: 'matricula'},
            {name: 'nombre'},
            {name: 'apellido'},
            {name: 'nacionalidad'},
            {name: 'apellido_materno'},
            {name: 'genero'},
            {name: 'fecha_inicio_estudios'},
            {name: 'fecha_nacimiento'},
            {name: 'direccion_trabajo'},
            {name: 'email'},
            {name: 'email_uteg'},
            {name: 'direccion'},
            {name: 'telefono'},
            {name: 'civil'},
            {name: 'fecha_ingreso'},
            {name: 'user'},
            {name: 'anio_gradua'},
            {name: 'colegio'}
        ])
    });

    Ext.gest_alumnos.fpItems = [
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

    Ext.gest_alumnos.fp = new Ext.form.FormPanel({
        frame: true,
        bodyStyle: 'padding: 6px',
        labelWidth: 60,
        defaultType: 'textfield',
        defaults: {
            msgTarget: 'side',
            anchor: '-20'
        },
        items: Ext.gest_alumnos.fpItems
    });

    Ext.gest_alumnos.myBtnHandler = function (btn) {
        if (btn.text == 'Adicionar') {
            if (!Ext.gest_alumnos.win) {
                var title = 'Adicionar una asignatura';
                Ext.gest_alumnos.win = new Ext.Window({
                    closeAction: 'hide',
                    title: title,
                    height: 110,
                    width: 300,
                    constrain: true,
                    items: [Ext.gest_alumnos.fp],
                    buttons: [
                        {
                            text: 'Aceptar',
                            handler: function (btn) {
                                if (Ext.gest_alumnos.fp.getForm().isValid()) {
                                    Ext.gest_alumnos.fp.el.mask('Por favor espere..', 'x-mask-loading');
                                    Ext.gest_alumnos.fp.getForm().submit({
                                        url: 'asignaturas/create',
                                        success: function (form, action) {
                                            Ext.gest_alumnos.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_alumnos.win.hide();
                                                Ext.gest_alumnos.stAlumnos.load();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        },
                                        failure: function (form, action) {
                                            Ext.gest_alumnos.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_alumnos.win.hide();
                                                Ext.gest_alumnos.stAlumnos.load();
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
                                Ext.gest_alumnos.win.hide();
                            }
                        }
                    ]
                });
            } else {
                Ext.gest_alumnos.fp.getForm().reset();
            }
            
            Ext.gest_alumnos.win.add(Ext.gest_alumnos.fp);
            Ext.gest_alumnos.win.doLayout();
            Ext.gest_alumnos.win.show();
        }

        if (btn.text == 'Modificar') {

            if (!Ext.gest_alumnos.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento para modificar');
                return false;
            }

            if (!Ext.gest_alumnos.winmod) {
                var title = 'Modificar la asignatura';
                Ext.gest_alumnos.winmod = new Ext.Window({
                    closeAction: 'hide',
                    title: title,
                    height: 110,
                    width: 300,
                    constrain: true,
                    items: [Ext.gest_alumnos.fp],
                    buttons: [
                        {
                            text: 'Aceptar',
                            handler: function (btn) {
                                if (Ext.gest_alumnos.fp.getForm().isValid()) {
                                    Ext.gest_alumnos.fp.el.mask('Por favor espere..', 'x-mask-loading');
                                    Ext.gest_alumnos.fp.getForm().submit({
                                        url: 'asignaturas/update',
                                        success: function (form, action) {
                                            Ext.gest_alumnos.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_alumnos.winmod.hide();
                                                Ext.gest_alumnos.stAlumnos.load();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        },
                                        failure: function (form, action) {
                                            Ext.gest_alumnos.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_alumnos.winmod.hide();
                                                Ext.gest_alumnos.stAlumnos.load();
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
                                Ext.gest_alumnos.winmod.hide();
                            }
                        }
                    ]
                });
            } else {
                Ext.gest_alumnos.fp.getForm().reset();
            }
            Ext.gest_alumnos.fp.getForm().reset();
            Ext.gest_alumnos.winmod.add(Ext.gest_alumnos.fp);
            Ext.gest_alumnos.winmod.doLayout();
            Ext.gest_alumnos.winmod.show();
            Ext.gest_alumnos.fp.getForm().loadRecord(Ext.gest_alumnos.sm.getSelected());
        }

        if (btn.text == 'Eliminar') {
            if (!Ext.gest_alumnos.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento para eliminar');
                return false;
            }

            Ext.MessageBox.show({
                title: 'Mensaje de confirmación',
                msg: '¿ Usted está seguro que desea eliminar la asignatura ?',
                buttons: Ext.MessageBox.YESNOCANCEL,
                fn: function (btn) {
                    if (btn == 'yes') {
                        Ext.Ajax.request({
                            url: 'asignaturas/delete',
                            method: 'POST',
                            params: {id: Ext.gest_alumnos.sm.getSelected().get("id")},
                            callback: function (options, success, response) {
                                responseData = Ext.decode(response.responseText);
                                if (responseData.success == true) {
                                    Ext.MessageBox.alert('Información', 'La asignatura se eliminó correctamente.'); 
                                    Ext.gest_alumnos.stAlumnos.load();
                                }
                            }
                        });
                    }
                },
                icon: Ext.MessageBox.QUESTION
            });

        }
        if (btn.text == 'Buscar') {
            Ext.gest_alumnos.stAlumnos.load({params: {start: 0,limit: 12}});
        }
    }

    Ext.gest_alumnos.addBtn = new Ext.Button({
        text: 'Adicionar',
        handler: Ext.gest_alumnos.myBtnHandler,
        icon: '../../images/add.png'
    });

    Ext.gest_alumnos.editBtn = new Ext.Button({
        text: 'Modificar',
        handler: Ext.gest_alumnos.myBtnHandler,
        icon: '../../images/bogus.png'
    });

    Ext.gest_alumnos.editDel = new Ext.Button({
        text: 'Eliminar',
        handler: Ext.gest_alumnos.myBtnHandler,
        icon: '../../images/delete.gif'
    });
    Ext.gest_alumnos.buscar  = new Ext.Button({
        text: 'Buscar',
        handler: Ext.gest_alumnos.myBtnHandler,
        icon: '../../images/lupa.png'
    });

    Ext.gest_alumnos.tbFill = new Ext.Toolbar.Fill();

    Ext.gest_alumnos.sm = new Ext.grid.RowSelectionModel({});

    Ext.gest_alumnos.gpAsig = new Ext.grid.GridPanel({
        frame: true,
        iconCls: 'icon-grid',
        loadMask: 'Cargando...',
        width: 450,
        store: Ext.gest_alumnos.stAlumnos,
        clicksToEdit: 1,
        sm: Ext.gest_alumnos.sm,
        stripeRows: true,
        floating: false,
        columns: [
            new Ext.grid.RowNumberer(),
            {hidden: true, hideable: false, dataIndex: 'id'},
            {header: 'Nombre', width: 150, dataIndex: 'nombre'},
            {header: 'Apellidos', width: 150, dataIndex: 'apellido'},
            {header: 'Apellido materno', width: 150, dataIndex:'apellido_materno'},
            {header: 'Cédula', width: 100, dataIndex:'cedula'},
            {header: 'Matricula', width: 100, dataIndex:'matricula'},
            {header: 'Nacionalidad', width: 100, dataIndex:'nacionalidad'},
            {header: 'Género', width: 50, dataIndex:'genero'},
            {header: 'Fecha inicio estudios', width: 110, dataIndex:'fecha_inicio_estudios'},
            {header: 'Fecha nacimiento', width: 110, dataIndex:'fecha_nacimiento'},
            {header: 'Direccion trabajo', width: 200, dataIndex:'direccion_trabajo'},
            {header: 'Email', width: 200, dataIndex:'email'},
            {header: 'Email uteg', width: 200, dataIndex:'email_uteg'},
            {header: 'Dirección', width: 200, dataIndex:'direccion'},
            {header: 'Teléfono', width: 200, dataIndex:'telefono'},
            {header: 'Civil', width: 50, dataIndex:'civil'},
            {header: 'Fecha ingreso', width: 80, dataIndex:'fecha_ingreso'},
            {header: 'Año graduación', width: 80, dataIndex:'anio_gradua'},
            {header: 'Colegio', width: 200, dataIndex:'colegio'}
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
                                         Ext.gest_alumnos.stAlumnos.load({params: {start: 0,limit: 12}});
                                    }
                         }
                        }      
            },
            Ext.gest_alumnos.buscar
          //  Ext.gest_alumnos.addBtn, '-',
            //Ext.gest_alumnos.editBtn, '-',
           // Ext.gest_alumnos.editDel
        ],
        bbar: new Ext.PagingToolbar({
            pageSize: 100,
            store: Ext.gest_alumnos.stAlumnos,
            displayInfo: true,
            displayMsg: 'Resultados {0} - {1} de {2}',
            emptyMsg: 'Ning&uacute;n resultado para mostrar.'
        })
    });

    new Ext.Viewport({
        layout: 'fit',
        items: [
            Ext.gest_alumnos.gpAsig
        ]
    });


});