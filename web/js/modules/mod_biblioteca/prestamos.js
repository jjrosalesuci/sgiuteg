Ext.onReady(function () {

    Ext.QuickTips.init();
    Ext.form.Field.prototype.msgTarget = 'side';

    Ext.ns('Ext.gest_prestamos');
    Ext.gest_prestamos.stPrestamos = new Ext.data.Store({
        url: 'prestamos/cargar',
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
            {name: 'fecha_e'},
            {name: 'fecha_d'},
            {name: 'id_alumno'},
            {name: 'id_materia_alumno'},
            {name: 'titulo_libro'},
            {name: 'id_docente'},
            {name: 'id_carrera'},
            {name: 'nombre_alumno'},
            {name: 'nombre_docente'},
            {name: 'nombre_carrera'},
            {name: 'nombre_materia'},
            {name: 'apellido_alumno'},
            {name: 'estado'},
            {name: 'email'},
            {name: 'id_libro'}
        ])
    });


    //
    //Combobox autofill Titulos
    //

    Ext.gest_prestamos.stlibros = new Ext.data.JsonStore({
        root: 'data',
        totalProperty : 'count',
        baseParams: {
            column : 'ficha_no'
        },
        fields : [
        {
            name: 'titulo',
            mapping : 'titulo'
        },
        {
            name: 'ficha_no',
            mapping : 'ficha_no'
        }
        ],
        proxy : new Ext.data.ScriptTagProxy({
            url : '../mod_biblioteca/libros/uploadsearch'
        })
    });
    
    Ext.gest_prestamos.combolibros = {
       submitValue: true,
       xtype :'combo',
       fieldLabel: 'Titulo',
       forceSelection:true,
       displayField:'titulo',
       hideTrigger:true,
       valueField:'ficha_no',
       pageSize : 20,
       totalProperty : 'count',
       anchor: '90%',
       hiddenName:'id_libro',       
       loadingText:'Buscando....',
       minChars:1,
       triggerAction:'all',
       mode: 'remote',
       allowBlank: false,
       listWidth: 350,
       store: Ext.gest_prestamos.stlibros       
    };
  
    //
    //Combobox autofill Alumnos
    //

    Ext.gest_prestamos.stcomboalumno = new Ext.data.JsonStore({
        root: 'data',
        totalProperty : 'count',
        baseParams: {
            column : 'id_alumno'
        },
        fields : [
        {
            name: 'nombre_completo',
            mapping : 'nombre_completo'
        },
        {
            name: 'id_alumno',
            mapping : 'id_alumno'
        }
        ],
        proxy : new Ext.data.ScriptTagProxy({
            url : '../mod_nomencladores/alumnos/uploadsearch'
        })
    });
    
    Ext.gest_prestamos.comboalumnos = {
        listeners: {
            'select': function(cmb, rec, idx) {
            Ext.gest_prestamos.stmateriaalumno.load({
            params: { 'id_alumno': this.getValue("id_alumno")}
            });
            }
       },
       xtype :'combo',
       fieldLabel: 'Nombre del alumno',
       forceSelection:true,
       displayField:'nombre_completo',
       hideTrigger:true,
       valueField:'id_alumno',
       pageSize : 20,
       totalProperty : 'count',
       anchor: '90%',
       hiddenName:'id_alumno',       
       loadingText:'Buscando....',
       minChars:1,
       triggerAction:'all',
       mode: 'remote',
       allowBlank: false,
       store: Ext.gest_prestamos.stcomboalumno       
    };

    //
    //Combobox autofill Materias del alumno
    //

    Ext.gest_prestamos.stmateriaalumno = new Ext.data.JsonStore({
        root: 'data',
        /*baseParams: {
            column : 'id_alumno'
        },*/
        fields : [
        {
            name: 'nombre',
            mapping : 'nombre'
        },
        {
            name: 'id_materia',
            mapping : 'id_materia'
        }
        ],
        proxy : new Ext.data.ScriptTagProxy({
            url : '../mod_nomencladores/alumnos/getmaterias'
        })
    });

    Ext.gest_prestamos.combomateriasalumnos = new Ext.form.ComboBox({
        listeners: {
            'select': function(cmb, rec, idx) {
            Ext.gest_prestamos.stdocentes.load({
            params: { 'id_materia': this.getValue("id_materia")}
            });
            }
        },
        submitValue: true,
        hiddenName: 'id_materia',
        valueField: 'id_materia',
        displayField: 'nombre',
        store: Ext.gest_prestamos.stmateriaalumno,
        autoCreate: true,
        typeAhead: true,
        triggerAction: 'all',
        readOnly: false,
        mode: 'local',
        width: 200,
        anchor: '90%',       
        align: 'rigth',
        fieldLabel: 'Materia',
        allowBlank: false,
    });

    //
    //Combobox autofill Docentes
    //

    Ext.gest_prestamos.stdocentes = new Ext.data.JsonStore({
        root: 'data',
        fields : [
        {
            name: 'nombre_docente',
            mapping : 'nombre_docente'
        },
        {
            name: 'id_docente',
            mapping : 'id_docente'
        }
        ],
        proxy : new Ext.data.ScriptTagProxy({
            url : '../mod_nomencladores/docentes/get_docentes'
        })
    });

    Ext.gest_prestamos.combodocentes = new Ext.form.ComboBox({
        submitValue: true,
        hiddenName: 'id_docente',
        valueField: 'id_docente',
        displayField: 'nombre_docente',
        store: Ext.gest_prestamos.stdocentes,
        autoCreate: false,
        typeAhead: true,
        triggerAction: 'all',
        readOnly: false,
        mode: 'local',
        width: 200,
        anchor: '90%',       
        align: 'rigth',
        fieldLabel: 'Docente',
        allowBlank: false,
    });


    Ext.gest_prestamos.fp = new Ext.FormPanel({
        labelAlign: 'top',
        frame:true,
        bodyStyle:'padding:5px 5px 0',
        anchor:'100%',
        items: [{
            layout:'column',
            items:[
            {
                columnWidth:.5,
                layout: 'form',
                items: [
                    Ext.gest_prestamos.combolibros,
                    Ext.gest_prestamos.comboalumnos,
                    Ext.gest_prestamos.combomateriasalumnos
                ]
            },
            {
                columnWidth:.5,
                layout: 'form',
                left: 6,
                items: [{
                    xtype:'datefield',
                    fieldLabel: 'Fecha de Entrega',
                    allowBlank: false,
                    name: 'fecha_e',
                    anchor:'90%'
                  },
                  {
                    xtype:'datefield',
                    fieldLabel: 'Fecha de Devolución',
                    allowBlank: false,
                    name: 'fecha_d',
                    anchor:'90%'
                  },
                  Ext.gest_prestamos.combodocentes                  
                ]
            }
            ]
        }]       
    });
    // Fin del panel

    //Menu Notificaciones




    Ext.gest_prestamos.myBtnHandler = function (btn) {
        if (btn.text == 'Prestar libro') {
            if (!Ext.gest_prestamos.win) {
                var title = 'Prestar libro';
                Ext.gest_prestamos.win = new Ext.Window({
                    modal: true,
                    closeAction: 'hide',
                    title: title,
                    height: 233,
                    width: 500,
                    constrain: true,
                    iconCls: '../../../images/book.png',
                    items: [Ext.gest_prestamos.fp],
                    buttons: [
                        {
                            text: 'Aceptar',
                            handler: function (btn) {
                                if (Ext.gest_prestamos.fp.getForm().isValid()) {
                                    Ext.gest_prestamos.fp.el.mask('Por favor espere..', 'x-mask-loading');
                                    Ext.gest_prestamos.fp.getForm().submit({
                                        url: 'prestamos/create',
                                        success: function (form, action) {
                                            Ext.gest_prestamos.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_prestamos.win.hide();
                                                Ext.gest_prestamos.stPrestamos.load();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        },
                                        failure: function (form, action) {
                                            Ext.gest_prestamos.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_prestamos.win.hide();
                                                Ext.gest_prestamos.stPrestamos.load();
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
                                Ext.gest_prestamos.win.hide();
                            }
                        }
                    ]
                });
            } else {
                Ext.gest_prestamos.fp.getForm().reset();
            }
            
            Ext.gest_prestamos.win.add(Ext.gest_prestamos.fp);
            Ext.gest_prestamos.win.doLayout();
            Ext.gest_prestamos.win.show();
        }

        if (btn.text == 'Modificar') {

            if (!Ext.gest_prestamos.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento para modificar');
                return false;
            }

            if (!Ext.gest_prestamos.winmod) {
                var title = 'Modificar la asignatura';
                Ext.gest_prestamos.winmod = new Ext.Window({
                    closeAction: 'hide',
                    title: title,
                    height: 110,
                    width: 300,
                    constrain: true,
                    items: [Ext.gest_prestamos.fp],
                    buttons: [
                        {
                            text: 'Aceptar',
                            handler: function (btn) {
                                if (Ext.gest_prestamos.fp.getForm().isValid()) {
                                    Ext.gest_prestamos.fp.el.mask('Por favor espere..', 'x-mask-loading');
                                    Ext.gest_prestamos.fp.getForm().submit({
                                        url: 'asignaturas/update',
                                        success: function (form, action) {
                                            Ext.gest_prestamos.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_prestamos.winmod.hide();
                                                Ext.gest_prestamos.stPrestamos.load();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        },
                                        failure: function (form, action) {
                                            Ext.gest_prestamos.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_prestamos.winmod.hide();
                                                Ext.gest_prestamos.stPrestamos.load();
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
                                Ext.gest_prestamos.winmod.hide();
                            }
                        }
                    ]
                });
            } else {
                Ext.gest_prestamos.fp.getForm().reset();
            }
            Ext.gest_prestamos.fp.getForm().reset();
            Ext.gest_prestamos.winmod.add(Ext.gest_prestamos.fp);
            Ext.gest_prestamos.winmod.doLayout();
            Ext.gest_prestamos.winmod.show();
            Ext.gest_prestamos.fp.getForm().loadRecord(Ext.gest_prestamos.sm.getSelected());
        }

        if (btn.text == 'Eliminar') {
            if (!Ext.gest_prestamos.sm.hasSelection()) {
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
                            params: {id: Ext.gest_prestamos.sm.getSelected().get("id")},
                            callback: function (options, success, response) {
                                responseData = Ext.decode(response.responseText);
                                if (responseData.success == true) {
                                    Ext.MessageBox.alert('Información', 'La asignatura se eliminó correctamente.'); 
                                    Ext.gest_prestamos.stPrestamos.load();
                                }
                            }
                        });
                    }
                },
                icon: Ext.MessageBox.QUESTION
            });

        }
        if (btn.text == 'Buscar') {
            if(!Ext.getCmp('fuera_fecha').pressed){
                Ext.gest_prestamos.stPrestamos.load({params: {start: 0,limit: 14}});
            }
            if(Ext.getCmp('fuera_fecha').pressed){
                Ext.gest_prestamos.stPrestamos.load({params: {filtro: 'si',start: 0,limit: 14}});
            }
        }

        if (btn.text == 'Marcar Entregado') {
            if (!Ext.gest_prestamos.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento');
                return false;
            }
            Ext.MessageBox.show({
                title: 'Mensaje de confirmación',
                msg: '¿ Está seguro que desea marcar como entregado?',
                buttons: Ext.MessageBox.YESNOCANCEL,
                fn: function (btn) {
                    if (btn == 'yes') {
                        Ext.Ajax.request({
                            url: 'prestamos/entregado',
                            method: 'POST',
                            params: {id: Ext.gest_prestamos.sm.getSelected().get("id")},
                            callback: function (options, success, response) {
                                responseData = Ext.decode(response.responseText);
                                if (responseData.success == true) {
                                    Ext.MessageBox.alert('Información', 'Se procesó correctamente.'); 
                                    Ext.gest_prestamos.stPrestamos.load({params: {start: 0,limit: 14}});
                                }
                            }
                        });
                    }
                },
                icon: Ext.MessageBox.QUESTION
            });
        }
        if (btn.text == 'Marcar Prestado') {
            if (!Ext.gest_prestamos.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento');
                return false;
            }
            Ext.MessageBox.show({
                title: 'Mensaje de confirmación',
                msg: '¿ Está seguro que desea marcar como prestado?',
                buttons: Ext.MessageBox.YESNOCANCEL,
                fn: function (btn) {
                    if (btn == 'yes') {
                        Ext.Ajax.request({
                            url: 'prestamos/prestado',
                            method: 'POST',
                            params: {id: Ext.gest_prestamos.sm.getSelected().get("id")},
                            callback: function (options, success, response) {
                                responseData = Ext.decode(response.responseText);
                                if (responseData.success == true) {
                                    Ext.MessageBox.alert('Información', 'Se procesó correctamente.'); 
                                    Ext.gest_prestamos.stPrestamos.load({params: {start: 0,limit: 14}});
                                    Ext.gest_prestamos.notificar.enable();
                                }
                            }
                        });
                    }
                },
                icon: Ext.MessageBox.QUESTION
            });
        }
        
        if (btn.text == 'Notificar') {
            if (!Ext.gest_prestamos.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento');
                return false;
            }
            Ext.MessageBox.show({
                title: 'Mensaje de confirmación',
                msg: '¿ Está seguro que desea notificar a este alumno?',
                buttons: Ext.MessageBox.YESNOCANCEL,
                fn: function (btn) {
                    if (btn == 'yes') {
                        Ext.Ajax.request({
                            url: 'prestamos/notificar',
                            method: 'POST',
                            params: {id: Ext.gest_prestamos.sm.getSelected().get("id")},
                            callback: function (options, success, response) {
                                responseData = Ext.decode(response.responseText);
                                if (responseData.success == true) {
                                    Ext.MessageBox.alert('Información', 'Notificación enviada!!!'); 
                                }
                                if (responseData.success == false){
                                    Ext.MessageBox.alert('Error...', 'El libro ya se entregó o este usuario no tiene correo agregado!!!');
                                }
                            }
                        });
                    }
                },
                icon: Ext.MessageBox.QUESTION
            });
        }
        if (btn.text == 'Notificar a Todos') {
            Ext.MessageBox.show({
                title: 'Mensaje de confirmación',
                msg: '¿ Está seguro que desea notificar a todos?',
                buttons: Ext.MessageBox.YESNOCANCEL,
                fn: function (btn) {
                    if (btn == 'yes') {
                        Ext.Ajax.request({
                            url: 'prestamos/notificartodos',
                            callback: function (options, success, response) {
                                responseData = Ext.decode(response.responseText);
                                if (responseData.success == true) {
                                    Ext.MessageBox.alert('Información', 'Notificaciones enviadas!!!'); 
                                }
                                if (responseData.success == false){
                                    Ext.MessageBox.alert('Información', 'Nada que notificar.');
                                }
                            }
                        });
                    }
                },
                icon: Ext.MessageBox.QUESTION
            });
        }
    }

    Ext.gest_prestamos.prestarlibro = new Ext.Button({
        text: 'Prestar libro',
        handler: Ext.gest_prestamos.myBtnHandler,
        icon: '../../images/book.png'
    });

    Ext.gest_prestamos.entregado = new Ext.Button({
        text: 'Marcar Entregado',
        handler: Ext.gest_prestamos.myBtnHandler,
        icon: '../../images/Properties_16x16.png'
    });

    Ext.gest_prestamos.prestado = new Ext.Button({
        text: 'Marcar Prestado',
        handler: Ext.gest_prestamos.myBtnHandler,
        icon: '../../images/cambiar_usuario.png'
    });

    Ext.gest_prestamos.notificar = new Ext.Button({
        text: 'Notificar',
        handler: Ext.gest_prestamos.myBtnHandler,
        icon: '../../images/icontexto-webdev-contact-016x016.png'
    });

    Ext.gest_prestamos.notificartodos = new Ext.Button({
        text: 'Notificar a Todos',
        handler: Ext.gest_prestamos.myBtnHandler,
        icon: '../../images/icontexto-webdev-site-map-016x016.png'
    });

    Ext.gest_prestamos.buscar  = new Ext.Button({
        text: 'Buscar',
        handler: Ext.gest_prestamos.myBtnHandler,
        icon: '../../images/lupa.png'
    });

    function change(val){
        if(val == 'Entregado'){
            return '<span style="color:green;">' + val + '</span>';
        }else if(val == 'Prestado'){
            return '<span style="color:red;">' + val + '</span>';
        }
        return val;
    }

    function changedate(val){
        if(val < currentDate){
            return '<span style="color:red;">' + val + '</span>';
        }
        return val;
    }

    Ext.gest_prestamos.tbFill = new Ext.Toolbar.Fill();

    Ext.gest_prestamos.sm = new Ext.grid.RowSelectionModel({});

    Ext.gest_prestamos.gpPrestamos = new Ext.grid.GridPanel({
        frame: true,
        iconCls: 'icon-grid',
        //autoExpandColumn: 'expandir',
        loadMask: 'Cargando...',
        width: 450,
        store: Ext.gest_prestamos.stPrestamos,
        clicksToEdit: 1,
        sm: Ext.gest_prestamos.sm,
        stripeRows: true,
        floating: false,
        columns: [
            new Ext.grid.RowNumberer(),
            {hidden: true, hideable: false, dataIndex: 'id'},
            {header: 'Estado', width: 80, sortable: true, dataIndex: 'estado', renderer: change},
            {header: 'Fecha de Entrega', width: 100, sortable: true, dataIndex: 'fecha_e'},
            {header: 'Fecha de Devolución', width: 120, sortable: true, dataIndex: 'fecha_d', renderer: changedate},
            {header: 'Titulo del libro', width: 150, dataIndex: 'titulo_libro'},
            {header: 'Apellidos', width: 120, dataIndex: 'apellido_alumno'},
            {header: 'Nombre(s)', width: 120, dataIndex: 'nombre_alumno'},
            {header: 'Materia que Cursa', width: 120, dataIndex: 'nombre_materia'},
            {header: 'Docente', width: 120, dataIndex: 'nombre_docente'},
            {header: 'Carrera', width: 120, dataIndex: 'nombre_carrera'},
            {header: 'Correo', width: 140, dataIndex: 'email'},
        ],
        tbar: [
            Ext.gest_prestamos.prestarlibro,'-',
            Ext.gest_prestamos.prestado,'-',
            Ext.gest_prestamos.entregado,'-',
            {
                text: 'Notificaciones',
                icon: '../../images/Send_16x16.png',
                menu: {
                    xtype: 'menu',
                    plain: true,
                    items: {
                        xtype: 'buttongroup',
                        title: 'Opciones',
                        autoWidth: true,
                        columns: 1,
                        defaults: {
                            xtype: 'button',
                            scale: 'small',
                            width: '100%',
                            //iconAlign: 'left'
                        },
                        items: [
                            Ext.gest_prestamos.notificar,
                            Ext.gest_prestamos.notificartodos
                        ]
                        }
                    }
            },'-',
            {
              xtype:'button',
              id: 'fuera_fecha',
              text: 'Fuera de fecha',
              icon: '../../images/filter_16x16.gif',
              enableToggle: true,
              listeners:{
                         'click':function(button ,eventObject){
                            if(this.pressed){
                                Ext.gest_prestamos.stPrestamos.load({params: {filtro: 'si',start: 0,limit: 14}});
                            }
                            if(!this.pressed){
                                Ext.gest_prestamos.stPrestamos.load({params: {start: 0,limit: 14}});
                            }                            
                         }
                        }      
            },
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
                                    if(eventoObject.getCharCode() == 13 && !Ext.getCmp('fuera_fecha').pressed){
                                         Ext.gest_prestamos.stPrestamos.load({params: {start: 0,limit: 14}});
                                    }
                                    if(eventoObject.getCharCode() == 13 && Ext.getCmp('fuera_fecha').pressed){
                                         Ext.gest_prestamos.stPrestamos.load({params: {filtro: 'si',start: 0,limit: 14}});
                                    }                                
                         }
                        }      
            },
            Ext.gest_prestamos.buscar
        ],        
        bbar: new Ext.PagingToolbar({
            pageSize: 14,
            store: Ext.gest_prestamos.stPrestamos,
            displayInfo: true,
            displayMsg: 'Resultados {0} - {1} de {2}',
            emptyMsg: 'Ning&uacute;n resultado para mostrar.'
        })
    });

    var currentDate = moment().format('YYYY[-]MM[-]DD');

    Ext.gest_prestamos.sm.on("rowselect", function(){
        if(Ext.gest_prestamos.sm.getSelected())
         {
             if( Ext.gest_prestamos.sm.getSelected().get("fecha_d") >= currentDate || Ext.gest_prestamos.sm.getSelected().get("estado") == 'Entregado')
             {
                 Ext.gest_prestamos.notificar.disable();
             }
             if( Ext.gest_prestamos.sm.getSelected().get("fecha_d") < currentDate && Ext.gest_prestamos.sm.getSelected().get("estado") == 'Prestado')
             {
                 Ext.gest_prestamos.notificar.enable();
             }
         }
    });

    new Ext.Viewport({
        layout: 'fit',
        items: [
            Ext.gest_prestamos.gpPrestamos
        ]
    });


});


