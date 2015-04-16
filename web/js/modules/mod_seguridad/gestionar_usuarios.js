Ext.onReady(function(){
    Ext.QuickTips.init();
    Ext.form.Field.prototype.msgTarget = 'side';
    Ext.ns('Ext.gest_usuarios');

    Ext.gest_usuarios.stUsuarios = new Ext.data.Store({
        url: 'usuario/cargar_usuarios',
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
            {name: 'username'},
            {name: 'email'},
            {name: 'nombres'},
            {name: 'apellidos'},
            {name: 'role'},
            {name: 'cedula'},
            {name: 'sexo'}

        ])
    });

    Ext.gest_usuarios.stRoles = new Ext.data.Store({
        url: 'roles/cargartodos',
        autoLoad: true,
        reader: new Ext.data.JsonReader({
            root: "data",
            id: "id"
        }, [
            {name: 'id_rol'},
            {name: 'nombre'}
        ])
    });

    Ext.gest_usuarios.combo_roles = new Ext.form.ComboBox({
        id: 'mc_gest_usuarios_roles',
        hiddenName: 'role',
        valueField: 'id_rol',
        displayField: 'nombre',
        store: Ext.gest_usuarios.stRoles,
        autoCreate: true,
        typeAhead: true,
        triggerAction: 'all',
        readOnly: false,
        mode: 'local',
        width: 250,
        anchor: '85%',
        listWidth: 250,
        align: 'rigth',
        fieldLabel: 'Rol',
    });

    //COMBO BOX SEXO

    Ext.gest_usuarios.sexo = new Ext.data.Store({
        url: 'usuario/cargarsexo',
        autoLoad: true,
        reader: new Ext.data.JsonReader({
            root: "data",
            id: "id"
        }, [
            {name: 'sexo'}
        ])
    });

    Ext.gest_usuarios.combo_sexo = new Ext.form.ComboBox({
        id: 'mc_gest_usuarios_sexo',
        hiddenName: 'sexo',
        valueField: 'sexo',
        displayField: 'sexo',
        store: Ext.gest_usuarios.sexo,
        autoCreate: true,
        typeAhead: true,
        triggerAction: 'all',
        readOnly: false,
        mode: 'local',
        width: 180,
        anchor: '85%',
        listWidth: 180,
        align: 'rigth',
        fieldLabel: 'Sexo',
    });



    Ext.gest_usuarios.fpItems = [
        {
            fieldLabel: 'Usuario',
            allowBlank: false,
            name: 'username'           
        },
        {
            fieldLabel: 'Nombre(s)',
            allowBlank: true,
            name: 'nombres'           
        },
        {
            fieldLabel: 'Apellido(s)',
            allowBlank: true,
            name: 'apellidos'           
        }
        ,
        {
            fieldLabel: 'Correo',
            allowBlank: false,
            name: 'email'           
        },
        {
            fieldLabel: 'Cedula',
            allowBlank: true,
            name: 'cedula',
        },
          Ext.gest_usuarios.combo_sexo
         ,
        {
            fieldLabel: 'id',
            allowBlank: true,
            name: 'id',
            hidden: true
        },
        Ext.gest_usuarios.combo_roles
    ];
 
    Ext.gest_usuarios.fp = new Ext.form.FormPanel({
        frame: true,
        bodyStyle: 'padding: 6px',
        labelWidth: 60,
        defaultType: 'textfield',
        defaults: {
            msgTarget: 'side',
            anchor: '-20'
        },
        items: Ext.gest_usuarios.fpItems
    });

    Ext.gest_usuarios.fpItemsResetPassword = [
        {
            fieldLabel: 'Nueva contraseña',
            allowBlank: false,
            inputType: 'password',
            name: 'password'           
        },
        {
            fieldLabel: 'id',
            allowBlank: true,
            name: 'id',
            hidden: true
        },{
            fieldLabel: 'Usuario',
            allowBlank: true,
            name: 'username',
            disabled:true
        },
        {   
             xtype: 'checkbox',
             fieldLabel: 'Actualizar SIGA',
             boxLabel: 'Sí',
             name: 'update_siga'
        }
    ];

    Ext.gest_usuarios.fpResetPassword = new Ext.form.FormPanel({
        frame: true,
        bodyStyle: 'padding: 6px',
        labelWidth: 110,
        defaultType: 'textfield',
        defaults: {
            msgTarget: 'side',
            anchor: '-20'
        },
        items: Ext.gest_usuarios.fpItemsResetPassword
    });

    Ext.gest_usuarios.myBtnHandler = function (btn) {
        if (btn.text == 'Adicionar') {
            if (!Ext.gest_usuarios.win) {               
                Ext.gest_usuarios.win = new Ext.Window({
                    closeAction: 'hide',
                    title: 'Adicionar un nuevo usuario',
                    height: 260,
                    width: 300,
                    modal:true,
                    constrain: true,
                    items: [Ext.gest_usuarios.fp],
                    buttons: [
                        {
                            text: 'Aceptar',
                            icon: '../../images/validado.png',
                            handler: function (btn) {
                                if (Ext.gest_usuarios.fp.getForm().isValid()) {
                                    Ext.gest_usuarios.fp.el.mask('Por favor espere..', 'x-mask-loading');
                                    Ext.gest_usuarios.fp.getForm().submit({
                                        url: 'usuario/create',
                                        success: function (form, action) {
                                            Ext.gest_usuarios.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_usuarios.win.hide();
                                                Ext.gest_usuarios.stUsuarios.load();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        },
                                        failure: function (form, action) {
                                            Ext.gest_usuarios.fp.el.unmask();
                                            Ext.MessageBox.alert('No completado!!', 'Error en el servidor');
                                        }
                                    });
                                }
                            }
                        },
                        {
                            text: 'Cancelar',
                            icon: '../../images/no_validado.png',
                            handler: function (btn) {
                                Ext.gest_usuarios.win.hide();
                            }
                        }
                    ]
                });
            } else {
                Ext.gest_usuarios.fp.getForm().reset();
            }
            
            Ext.gest_usuarios.win.add(Ext.gest_usuarios.fp);
            Ext.gest_usuarios.win.doLayout();
            Ext.gest_usuarios.win.show();
        }

        if (btn.text == 'Modificar') {

            if (!Ext.gest_usuarios.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento para modificar');
                return false;
            }

            if (!Ext.gest_usuarios.winmod) {
                Ext.gest_usuarios.winmod = new Ext.Window({
                    closeAction: 'hide',
                    title: 'Modificar el usuario',
                    height: 260,
                    width: 300,
                    modal:true,
                    constrain: true,
                    items: [Ext.gest_usuarios.fp],
                    buttons: [
                        {
                            text: 'Aceptar',
                            icon: '../../images/validado.png',
                            handler: function (btn) {
                                if (Ext.gest_usuarios.fp.getForm().isValid()) {
                                    Ext.gest_usuarios.fp.el.mask('Por favor espere..', 'x-mask-loading');
                                    Ext.gest_usuarios.fp.getForm().submit({
                                        url: 'usuario/update',
                                        success: function (form, action) {
                                            Ext.gest_usuarios.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_usuarios.winmod.hide();
                                                Ext.gest_usuarios.stUsuarios.load();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        },
                                        failure: function (form, action) {
                                            Ext.gest_usuarios.fp.el.unmask();
                                            Ext.MessageBox.alert('No completado!!', 'Error en el servidor');
                                        }
                                    });
                                }
                            }
                        },
                        {
                            text: 'Cancelar',
                            icon: '../../images/no_validado.png',
                            handler: function (btn) {
                                Ext.gest_usuarios.winmod.hide();
                            }
                        }
                    ]
                });
            } else {
                Ext.gest_usuarios.fp.getForm().reset();
            }
            Ext.gest_usuarios.fp.getForm().reset();
            Ext.gest_usuarios.winmod.add(Ext.gest_usuarios.fp);
            Ext.gest_usuarios.winmod.doLayout();
            Ext.gest_usuarios.winmod.show();
            Ext.gest_usuarios.fp.getForm().loadRecord(Ext.gest_usuarios.sm.getSelected());
        }


		if (btn.text == 'Cambiar contraseña') {

            if (!Ext.gest_usuarios.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento para modificar');
                return false;
            }

            if (!Ext.gest_usuarios.rpassword) {
                Ext.gest_usuarios.rpassword = new Ext.Window({
                    closeAction: 'hide',
                    title: 'Cambiar contraseña',
                    height: 155,
                    width: 320,
                    modal:true,
                    constrain: true,
                    items: [Ext.gest_usuarios.fpResetPassword],
                    buttons: [
                        {
                            text: 'Aceptar',
                            icon: '../../images/validado.png',
                            handler: function (btn) {
                                if (Ext.gest_usuarios.fpResetPassword.getForm().isValid()) {
                                    Ext.gest_usuarios.fpResetPassword.el.mask('Por favor espere..', 'x-mask-loading');
                                    Ext.gest_usuarios.fpResetPassword.getForm().submit({
                                        url: 'usuario/updatepassword',
                                        params: {id_user: Ext.gest_usuarios.sm.getSelected().get("id"),username:Ext.gest_usuarios.sm.getSelected().get("username")},
                                        success: function (form, action) {
                                            Ext.gest_usuarios.fpResetPassword.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_usuarios.rpassword.hide();                                               
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        },
                                        failure: function (form, action) {
                                            Ext.gest_usuarios.fpResetPassword.el.unmask();
                                            Ext.MessageBox.alert('No completado!!', 'Error en el servidor');
                                        }
                                    });
                                }
                            }
                        },
                        {
                            text: 'Cancelar',
                            icon: '../../images/no_validado.png',
                            handler: function (btn) {
                                Ext.gest_usuarios.rpassword.hide();
                            }
                        }
                    ]
                });
            } else {
                Ext.gest_usuarios.fpResetPassword.getForm().reset();
            }
            Ext.gest_usuarios.fpResetPassword.getForm().reset();
            Ext.gest_usuarios.rpassword.add(Ext.gest_usuarios.fpResetPassword);
            Ext.gest_usuarios.rpassword.doLayout();
            Ext.gest_usuarios.rpassword.show();
            Ext.gest_usuarios.fpResetPassword.getForm().loadRecord(Ext.gest_usuarios.sm.getSelected());
        }


        if (btn.text == 'Eliminar') {
            if (!Ext.gest_usuarios.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento para eliminar');
                return false;
            }

            Ext.MessageBox.show({
                title: 'Mensaje de confirmacion',
                msg: '¿ Usted esta seguro que desea eliminar el usuario ?',
                buttons: Ext.MessageBox.YESNOCANCEL,
                fn: function (btn) {
                    if (btn == 'yes') {
                        Ext.Ajax.request({
                            url: 'usuario/delete',
                            method: 'POST',
                            params: {id: Ext.gest_usuarios.sm.getSelected().get("id")},
                            success: function (response) {
                                responseData = Ext.decode(response.responseText);
                                if (responseData.success == true) {
                                    Ext.MessageBox.alert('Informacion', 'Se elimino correctamente.'); 
                                    Ext.gest_usuarios.stUsuarios.load();
                                }
                            },
                            failure: function () {
                                  Ext.MessageBox.alert('No completado!!', 'Error en el servidor');                              
                             }                            
                        });
                    }
                },
                icon: Ext.MessageBox.QUESTION
            });

        }


        if (btn.text == 'Buscar') {
            Ext.gest_usuarios.stUsuarios.load({params: {start: 0,limit: 12}});
        }

    }

    Ext.gest_usuarios.addBtn = new Ext.Button({
        text: 'Adicionar',
        handler: Ext.gest_usuarios.myBtnHandler,
        icon: '../../images/add.png'
    });

    Ext.gest_usuarios.editBtn = new Ext.Button({
        text: 'Modificar',
        handler: Ext.gest_usuarios.myBtnHandler,
        icon: '../../images/bogus.png'
    });

    Ext.gest_usuarios.editDel = new Ext.Button({
        text: 'Eliminar',
        handler: Ext.gest_usuarios.myBtnHandler,
        icon: '../../images/delete.gif'
    });

    Ext.gest_usuarios.CambiarPassword = new Ext.Button({
        text: 'Cambiar contraseña',
        handler: Ext.gest_usuarios.myBtnHandler,
        icon: '../../images/cambiar_contrasenna.png'
    });

    Ext.gest_usuarios.AsignarRoles = new Ext.Button({
        text: 'Asignar Roles',
        handler: Ext.gest_usuarios.myBtnHandler,
        icon: '../../images/group.png'
    });

    Ext.gest_usuarios.buscar  = new Ext.Button({
        text: 'Buscar',
        handler: Ext.gest_usuarios.myBtnHandler,
        icon: '../../images/lupa.png'
    });


    Ext.gest_usuarios.tbFill = new Ext.Toolbar.Fill();

    Ext.gest_usuarios.sm = new Ext.grid.RowSelectionModel({});

    Ext.gest_usuarios.gpUsuarios = new Ext.grid.GridPanel({
        frame: true,
        iconCls: 'icon-grid',
        autoExpandColumn: 'expandir',
        loadMask: 'Cargando...',
        width: 450,
        store: Ext.gest_usuarios.stUsuarios,
        clicksToEdit: 1,
        sm: Ext.gest_usuarios.sm,
        stripeRows: true,
        floating: false,
        columns: [
            new Ext.grid.RowNumberer(),
            {hidden: true, hideable: false, dataIndex: 'id'},
            {header: 'Usuario', width: 100, dataIndex: 'username', id: 'expandir'},         
            {header: 'Nombre (s)', width: 200, dataIndex: 'nombres'},
            {header: 'Apellidos (s)', width: 200, dataIndex: 'apellidos'},
            {header: 'Correo', width: 180, dataIndex: 'email'},
            {header: 'Cedula', width: 80, dataIndex: 'cedula'},
            {header: 'Sexo', width: 80, dataIndex: 'sexo'}
        ],
        tbar: [
            Ext.gest_usuarios.addBtn,  '-',
            Ext.gest_usuarios.editBtn, '-',
            Ext.gest_usuarios.editDel, '-',
            Ext.gest_usuarios.CambiarPassword,
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
                                         Ext.gest_usuarios.stUsuarios.load({params: {start: 0,limit: 12}});
                                    }
                         }
                        }      
            },
            Ext.gest_usuarios.buscar   
        ],
        bbar: new Ext.PagingToolbar({
            pageSize: 12,
            store: Ext.gest_usuarios.stUsuarios,
            displayInfo: true,
            displayMsg: 'Resultados {0} - {1} de {2}',
            emptyMsg: 'Ning&uacute;n resultado para mostrar.'
        })
    });

    new Ext.Viewport({
        layout: 'fit',
        items: [
            Ext.gest_usuarios.gpUsuarios
        ]
    });

});