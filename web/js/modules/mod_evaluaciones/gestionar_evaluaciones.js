Ext.onReady(function(){
    Ext.QuickTips.init();
    Ext.form.Field.prototype.msgTarget = 'side';
    Ext.ns('Ext.gest_evaluaciones');
    // Panel de adicionar modificar evaluacion
    
    //Tipo de evaluacion
    Ext.gest_evaluaciones.tipo_store = new Ext.data.Store({
        url: 'evaluaciones/cargartipo',
        autoLoad: true,
        reader: new Ext.data.JsonReader({
            root: "data",
            id: "id"
        }, [
            {name: 'tipo'}
        ])
    });

    Ext.gest_evaluaciones.tipo = new Ext.form.ComboBox({
        hiddenName: 'tipo',
        valueField: 'tipo',
        displayField: 'tipo',
        store: Ext.gest_evaluaciones.tipo_store,
        autoCreate: true,
        typeAhead: true,
        triggerAction: 'all',
        readOnly: false,
        mode: 'local',
        width: 200,
        anchor: '95%',      
        align: 'rigth',
        fieldLabel: 'Tipo',
        allowBlank: false,
    });
    // fin tipo de evaluacion
    
    //
    //Combobox autofill
    //

    Ext.gest_evaluaciones.stcombogrupoparcial = new Ext.data.JsonStore({
        root: 'data',
        totalProperty : 'count',
        baseParams: {
            column : 'id'
        },
        fields : [
        {
            name: 'nombre',
            mapping : 'nombre'
        },
        {
            name: 'id',
            mapping : 'id'
        }
        ],
        proxy : new Ext.data.ScriptTagProxy({
            url : '../mod_nomencladores/semestres/uploadsearch'
        })
    });
    
    Ext.gest_evaluaciones.grupoparcial  = {
       listeners: {
            'select': function(cmb, rec, idx) {
            Ext.gest_evaluaciones.stcombogrupoparcial.load({
            params: { 'id_periodo': this.getValue("id")}
            });
            }
       },
       submitValue: true,
       xtype :'combo',
       fieldLabel: 'Período',
       forceSelection:true,
       displayField:'nombre',
       hideTrigger:true,
       valueField:'id',
       pageSize : 20,
       totalProperty : 'count',
       anchor: '95%',
       hiddenName:'nombre_periodo',
       hiddenValue: 'id_periodo',
       loadingText:'Buscando....',
       minChars:1,
       triggerAction:'nombre',
       mode: 'remote',
       store: Ext.gest_evaluaciones.stcombogrupoparcial
    };
    
    //Grupo origen
    Ext.gest_evaluaciones.g_o_store = new Ext.data.Store({
        url: '../mod_seguridad/roles/cargartodos',
        autoLoad: true,
        reader: new Ext.data.JsonReader({
            root: "data",
            id: "id"
        }, [
            {name: 'id_rol'},
            {name: 'nombre'}
        ])
    });

    Ext.gest_evaluaciones.grupo_origen = new Ext.form.ComboBox({
        hiddenName: 'id_grupo_origen',
        valueField: 'id_rol',
        displayField: 'nombre',
        store: Ext.gest_evaluaciones.g_o_store,
        autoCreate: true,
        typeAhead: true,
        triggerAction: 'all',
        readOnly: false,
        mode: 'local',
        width: 200,
        anchor: '95%',       
        align: 'rigth',
        fieldLabel: 'Grupo origen',
        allowBlank: false,
    });

    
    //Fin grupo origen
    /*
    * Tree de los roles
    */
    Ext.gest_evaluaciones.TreeLoader =  new Ext.tree.TreeLoader({
        dataUrl: 'evaluaciones/getnodes',  
        listeners:{
                      beforeload:function(loader, nodo, fnCallBack){    
                         if (Ext.gest_evaluaciones.crud_status==2) {
                           loader.baseParams.id_evaluacion = Ext.gest_evaluaciones.sm.getSelected().get("id");           
                         }else{
                           loader.baseParams.id_evaluacion = -1;
                         }                                   
                     }
            }
    });

    Ext.gest_evaluaciones.TreeMenu = new Ext.tree.TreePanel({
        useArrows: true,
        autoScroll: true,
        enabled:false,
        animate: true,
        height: 185,
        anchor: '95%', 
        title:'Seleccione los grupos',
        frame:true,
        disabled : false,
        containerScroll: true,
        border: false,
        loader:Ext.gest_evaluaciones.TreeLoader,
        root: {
            nodeType: 'async',
            text: 'Roles',
            draggable: false,
            id: 1
        },
        listeners: {
            'checkchange': function(node, checked){
                /*if(checked){
                            
                            //Agregar permiso
                            Ext.Ajax.request({
                                url: 'rolacceso/add',
                                method: 'POST',
                               // params: {id_rol: Ext.gestseg_rol_acc.combo_roles.getValue(),id_menu_item:node.attributes.id},
                                success: function (response) {                               
                                },
                                failure: function () {
                                      Ext.MessageBox.alert('No completado!!', 'Error en el servidor');                              
                                 }                            
                           });
                }else{
                           //Quitar permiso
                           Ext.Ajax.request({
                                url: 'rolacceso/remove',
                                method: 'POST',
                                //params: {id_rol: Ext.gestseg_rol_acc.combo_roles.getValue(),id_menu_item:node.attributes.id},
                                success: function (response) {                               
                                },
                                failure: function () {
                                      Ext.MessageBox.alert('No completado!!', 'Error en el servidor');                              
                                 }                            
                           });

                }*/
            }
        },
    });

    



    /*
    * Fin del tree de los roles
    */

    Ext.gest_evaluaciones.fp = new Ext.FormPanel({
        labelAlign: 'top',
        frame:true,
       // title: 'Multi Column, Nested Layouts and Anchoring',
        bodyStyle:'padding:5px 5px 0',
        //width: 600,
        anchor:'100%',
        items: [{
            layout:'column',
            items:[
            {
                columnWidth:.5,
                layout: 'form',
                items: [{
                    xtype:'textfield',
                    fieldLabel: 'Titulo',
                    allowBlank: false,
                    name: 'titulo',
                    anchor:'95%'
                  },
                 Ext.gest_evaluaciones.tipo,
                 Ext.gest_evaluaciones.TreeMenu
                ]
            },{
                columnWidth:.5,
                layout: 'form',
                items: [{
                    xtype:'datefield',
                    fieldLabel: 'Fecha',
                    allowBlank: false,
                    name: 'fecha',
                    anchor:'95%'
                  },
                 Ext.gest_evaluaciones.grupo_origen
                ]
            },
            {
                columnWidth:.5,
                layout: 'form',
                items: [
                    Ext.gest_evaluaciones.grupoparcial
                ]
            },
            {
            fieldLabel: 'id',
            allowBlank: true,
            name: 'id',
            hidden: true
            },
            {
                columnWidth:.5,
                layout: 'form',
                items: [{
                    xtype:'textfield',
                    fieldLabel: 'Modalidad',
                    name: 'modalidad',
                    anchor:'95%'
                }
                ]
            },
            {
                columnWidth:.5,
                layout: 'form',
                items: [
                {
                    xtype:'textarea',
                    fieldLabel: 'Descripción',
                    name: 'descripcion',
                    anchor:'95%'
                }
                ]
            }

            ]
        }]       
    });
    // Fin del panel


    Ext.gest_evaluaciones.myBtnHandler = function (btn) {
        if (btn.text == 'Adicionar') {
            if (!Ext.gest_evaluaciones.win) {
                var title = 'Adicionar una evaluación';
                Ext.gest_evaluaciones.win = new Ext.Window({
                    closeAction: 'hide',
                    title: title,
                    modal:true,
                    height: 370,
                    width: 750,
                    constrain: true,
                    items: [Ext.gest_evaluaciones.fp],
                    buttons: [
                        {
                            text: 'Aceptar',
                            icon: '../../images/accept.png',
                            handler: function (btn) {
                                 var roles = new Array();
                                 selNodes = Ext.gest_evaluaciones.TreeMenu.getChecked();                                
                                 Ext.each( selNodes, function(node){
                                    roles.push(node.id);                                   
                                 });

                                 if (Ext.gest_evaluaciones.fp.getForm().isValid()) {
                                    Ext.gest_evaluaciones.fp.el.mask('Por favor espere..', 'x-mask-loading');
                                    Ext.gest_evaluaciones.fp.getForm().submit({
                                        url: 'evaluaciones/create',
                                        params:{id_roles:Ext.util.JSON.encode(roles)},
                                        success: function (form, action) {
                                            Ext.gest_evaluaciones.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_evaluaciones.win.hide();
                                                Ext.gest_evaluaciones.stEvaluaciones.load();
                                                Ext.gest_evaluaciones.sm.clearSelections();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        },
                                        failure: function (form, action) {
                                            Ext.gest_evaluaciones.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_evaluaciones.win.hide();
                                                Ext.gest_evaluaciones.stEvaluaciones.load();
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
                            icon: '../../images/cross.png',
                            handler: function (btn) {
                                Ext.gest_evaluaciones.win.hide();
                            }
                        }
                    ]
                });
            } else {
                Ext.gest_evaluaciones.fp.getForm().reset();
            }
            
            Ext.gest_evaluaciones.fp.getForm().reset();
            Ext.gest_evaluaciones.win.add(Ext.gest_evaluaciones.fp);
            Ext.gest_evaluaciones.win.doLayout();
            Ext.gest_evaluaciones.win.show();
            Ext.gest_evaluaciones.crud_status = 1;
            Ext.gest_evaluaciones.TreeMenu.getRootNode().reload({node:1});            
        }

        if (btn.text == 'Modificar') {

            if (!Ext.gest_evaluaciones.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento para modificar');
                return false;
            }

            if (!Ext.gest_evaluaciones.winmod) {
                var title = 'Modificar la evaluaciones';
                Ext.gest_evaluaciones.winmod = new Ext.Window({
                    closeAction: 'hide',
                    title: title,
                    modal:true,
                    height: 370,
                    width: 750,
                    constrain: true,
                    items: [Ext.gest_evaluaciones.fp],
                    buttons: [
                        {
                            text: 'Aceptar',
                            handler: function (btn) {
                                var roles = new Array();
                                
                                selNodes = Ext.gest_evaluaciones.TreeMenu.getChecked();                                
                                Ext.each( selNodes, function(node){
                                       roles.push(node.id);                                   
                                });

                                if (Ext.gest_evaluaciones.fp.getForm().isValid()) {
                                    Ext.gest_evaluaciones.fp.el.mask('Por favor espere..', 'x-mask-loading');
                                    Ext.gest_evaluaciones.fp.getForm().submit({
                                        url: 'evaluaciones/update',
                                        params: {id: Ext.gest_evaluaciones.sm.getSelected().get("id"),id_roles:Ext.util.JSON.encode(roles)},
                                        success: function (form, action) {
                                            Ext.gest_evaluaciones.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_evaluaciones.winmod.hide();
                                                Ext.gest_evaluaciones.stEvaluaciones.load();
                                                Ext.gest_evaluaciones.sm.clearSelections();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        },
                                        failure: function (form, action) {
                                            Ext.gest_evaluaciones.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_evaluaciones.winmod.hide();
                                                Ext.gest_evaluaciones.stEvaluaciones.load();
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
                                Ext.gest_evaluaciones.winmod.hide();
                            }
                        }
                    ]
                });
            } else {
                Ext.gest_evaluaciones.fp.getForm().reset();
            }
            Ext.gest_evaluaciones.fp.getForm().reset();
            Ext.gest_evaluaciones.winmod.add(Ext.gest_evaluaciones.fp);
            Ext.gest_evaluaciones.winmod.doLayout();
            Ext.gest_evaluaciones.winmod.show();
            Ext.gest_evaluaciones.fp.getForm().loadRecord(Ext.gest_evaluaciones.sm.getSelected());
            Ext.gest_evaluaciones.crud_status = 2;
            Ext.gest_evaluaciones.TreeMenu.getRootNode().reload({node:1});
        }

        if (btn.text == 'Eliminar') {
            if (!Ext.gest_evaluaciones.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento para eliminar');
                return false;
            }

            Ext.MessageBox.show({
                title: 'Mensaje de confirmación',
                msg: '¿ Usted está seguro que desea eliminar la evaluacion ?',
                buttons: Ext.MessageBox.YESNOCANCEL,
                fn: function (btn) {
                    if (btn == 'yes') {
                        Ext.Ajax.request({
                            url: 'evaluaciones/delete',
                            method: 'POST',
                            params: {id: Ext.gest_evaluaciones.sm.getSelected().get("id")},
                            callback: function (options, success, response) {
                                responseData = Ext.decode(response.responseText);
                                if (responseData.success == true) {
                                    Ext.MessageBox.alert('Información', 'La evaluación se eliminó correctamente.'); 
                                    Ext.gest_evaluaciones.stEvaluaciones.load();
                                    Ext.gest_evaluaciones.sm.clearSelections();
                                }
                                else {
                                    Ext.MessageBox.alert('Información', 'No se puede eliminar, debe estar en Elaboración.'); 
                                }
                            }
                        });
                    }
                },
                icon: Ext.MessageBox.QUESTION
            });

        }
        if (btn.text == 'Preguntas'){
            if (!Ext.gest_evaluaciones.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione una evaluación');
                return false;
            }
        
            Ext.gest_evaluaciones.ventana_evaluaciones.show();
            Ext.gest_evaluaciones.pregeval_store.load();
            Ext.gest_evaluaciones.preguntas_store.load();            
        }

        if (btn.text == 'Procesar'){
            if (!Ext.gest_evaluaciones.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione una evaluación');
                return false;
            }
            Ext.MessageBox.show({
                title: 'Mensaje de confirmación',
                msg: '¿ Usted está seguro que desea procesar la evaluación ?',
                buttons: Ext.MessageBox.YESNOCANCEL,
                fn: function (btn) {
                    if (btn == 'yes') {
                        Ext.Ajax.request({
                            url: 'evaluaciones/asignarestadoproceso',
                            method: 'POST',
                            params: {id: Ext.gest_evaluaciones.sm.getSelected().get("id")},
                            callback: function (options, success, response) {
                                responseData = Ext.decode(response.responseText);
                                if (responseData.success == true) {
                                    Ext.MessageBox.alert('Información', 'La evaluación se procesó correctamente.'); 
                                    Ext.gest_evaluaciones.stEvaluaciones.load();
                                    Ext.gest_evaluaciones.sm.clearSelections();
                                }
                            }
                        });
                    }
                },
                icon: Ext.MessageBox.QUESTION
            });
            Ext.gest_evaluaciones.stEvaluaciones.load();
        }
        if (btn.text == 'Finalizar'){
            if (!Ext.gest_evaluaciones.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione una evaluación');
                return false;
            }
            Ext.MessageBox.show({
                title: 'Mensaje de confirmación',
                msg: '¿ Usted está seguro que desea finalizar la evaluación ?',
                buttons: Ext.MessageBox.YESNOCANCEL,
                fn: function (btn) {
                    if (btn == 'yes') {
                        Ext.Ajax.request({
                            url: 'evaluaciones/asignarestadofinal',
                            method: 'POST',
                            params: {id: Ext.gest_evaluaciones.sm.getSelected().get("id")},
                            callback: function (options, success, response) {
                                responseData = Ext.decode(response.responseText);
                                if (responseData.success == true) {
                                    Ext.MessageBox.alert('Información', 'La evaluación se finalizó correctamente.'); 
                                    Ext.gest_evaluaciones.stEvaluaciones.load();
                                    Ext.gest_evaluaciones.sm.clearSelections();
                                }
                            }
                        });
                    }
                },
                icon: Ext.MessageBox.QUESTION
            });
            Ext.gest_evaluaciones.stEvaluaciones.load();
        }
        if (btn.text == 'Duplicar'){
            if (!Ext.gest_evaluaciones.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione una evaluación');
                return false;
            }
            Ext.MessageBox.show({
                title: 'Mensaje de confirmación',
                msg: '¿ Usted está seguro que desea duplicar la evaluación ?',
                buttons: Ext.MessageBox.YESNOCANCEL,
                fn: function (btn) {
                    if (btn == 'yes') {
                        Ext.Ajax.request({
                            url: 'evaluaciones/duplicar',
                            method: 'POST',
                            params: {id: Ext.gest_evaluaciones.sm.getSelected().get("id")},
                            callback: function (options, success, response) {
                                responseData = Ext.decode(response.responseText);
                                if (responseData.success == true) {
                                    Ext.MessageBox.alert('Información', 'La evaluación se duplicó correctamente.'); 
                                    Ext.gest_evaluaciones.stEvaluaciones.load();
                                }
                            }
                        });
                    }
                },
                icon: Ext.MessageBox.QUESTION
            });
            Ext.gest_evaluaciones.stEvaluaciones.load();
        }
        if (btn.text == 'Grupos Preguntas'){
            Ext.gest_evaluaciones.ventana_grupos.show();
            Ext.gest_evaluaciones.grupos_store.load();
        }
        if (btn.text == 'Buscar') {
            Ext.gest_evaluaciones.stEvaluaciones.load({params: {start: 0,limit: 12}});
        }


    }
    Ext.gest_evaluaciones.myBtnHandlerPreguntas = function (btn) {
        if (btn.text == 'Adicionar') {
            if (!Ext.gest_evaluaciones.windpreg) {
                var title = 'Adicionar una pregunta';
                Ext.gest_evaluaciones.windpreg = new Ext.Window({
                    closeAction: 'hide',
                    title: title,
                    modal:true,
                    height: 192,
                    width: 400,
                    constrain: true,
                    items: [Ext.gest_evaluaciones.fppreguntas],
                    buttons: [
                        {
                            text: 'Aceptar',
                            icon: '../../images/accept.png',
                            handler: function (btn) {
                                if (Ext.gest_evaluaciones.fppreguntas.getForm().isValid()) {
                                    Ext.gest_evaluaciones.fppreguntas.el.mask('Por favor espere..', 'x-mask-loading');
                                    Ext.gest_evaluaciones.fppreguntas.getForm().submit({
                                        url: 'preguntas/create',
                                        params: {id_evaluacion: Ext.gest_evaluaciones.sm.getSelected().get("id")},
                                        success: function (form, action) {
                                            Ext.gest_evaluaciones.fppreguntas.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_evaluaciones.windpreg.hide();
                                                Ext.gest_evaluaciones.pregeval_store.load();
                                                Ext.gest_evaluaciones.preguntas_store.load();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        },
                                        failure: function (form, action) {
                                            Ext.gest_evaluaciones.fppreguntas.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_evaluaciones.windpreg.hide();
                                                Ext.gest_evaluaciones.pregeval_store.load();
                                                Ext.gest_evaluaciones.preguntas_store.load();
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
                            icon: '../../images/cross.png',
                            handler: function (btn) {
                                Ext.gest_evaluaciones.windpreg.hide();
                            }
                        }
                    ]
                });
            } else {
                Ext.gest_evaluaciones.fppreguntas.getForm().reset();
            }
            
            Ext.gest_evaluaciones.fppreguntas.getForm().reset();
            Ext.gest_evaluaciones.windpreg.add(Ext.gest_evaluaciones.fppreguntas);
            Ext.gest_evaluaciones.windpreg.doLayout();
            Ext.gest_evaluaciones.windpreg.show();
        }

        if (btn.text == 'Modificar') {

            if (!Ext.gest_evaluaciones.preguntas_store.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento para modificar');
                return false;
            }

            if (!Ext.gest_evaluaciones.winpregmod) {
                var title = 'Modificar la pregunta';
                Ext.gest_evaluaciones.winpregmod = new Ext.Window({
                    closeAction: 'hide',
                    title: title,
                    modal:true,
                    height: 192,
                    width: 400,
                    constrain: true,
                    items: [Ext.gest_evaluaciones.fppreguntas],
                    buttons: [
                        {
                            text: 'Aceptar',
                            handler: function (btn) {
                                if (Ext.gest_evaluaciones.fppreguntas.getForm().isValid()) {
                                    Ext.gest_evaluaciones.fppreguntas.el.mask('Por favor espere..', 'x-mask-loading');
                                    Ext.gest_evaluaciones.fppreguntas.getForm().submit({
                                        url: 'preguntas/update',
                                        params: {id: Ext.gest_evaluaciones.preguntas_store.sm.getSelected().get("id")},
                                        success: function (form, action) {
                                            Ext.gest_evaluaciones.fppreguntas.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_evaluaciones.winpregmod.hide();
                                                Ext.gest_evaluaciones.pregeval_store.load();
                                                Ext.gest_evaluaciones.preguntas_store.load();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        },
                                        failure: function (form, action) {
                                            Ext.gest_evaluaciones.fppreguntas.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_evaluaciones.winpregmod.hide();
                                                Ext.gest_evaluaciones.pregeval_store.load();
                                                Ext.gest_evaluaciones.preguntas_store.load();
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
                                Ext.gest_evaluaciones.winpregmod.hide();
                            }
                        }
                    ]
                });
            } else {
                Ext.gest_evaluaciones.fp.getForm().reset();
            }
            Ext.gest_evaluaciones.fppreguntas.getForm().reset();
            Ext.gest_evaluaciones.winpregmod.add(Ext.gest_evaluaciones.fppreguntas);
            Ext.gest_evaluaciones.winpregmod.doLayout();
            Ext.gest_evaluaciones.winpregmod.show();
            Ext.gest_evaluaciones.fppreguntas.getForm().loadRecord(Ext.gest_evaluaciones.preguntas_store.sm.getSelected());
        }

        if (btn.text == 'Eliminar') {
            if (!Ext.gest_evaluaciones.preguntas_store.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento para eliminar');
                return false;
            }

            Ext.MessageBox.show({
                title: 'Mensaje de confirmación',
                msg: '¿ Usted está seguro que desea eliminar la pregunta ?',
                buttons: Ext.MessageBox.YESNOCANCEL,
                fn: function (btn) {
                    if (btn == 'yes') {
                        Ext.Ajax.request({
                            url: 'preguntas/delete',
                            method: 'POST',
                            params: {id_pregunta: Ext.gest_evaluaciones.preguntas_store.sm.getSelected().get("id_pregunta")},
                            callback: function (options, success, response) {
                                responseData = Ext.decode(response.responseText);
                                if (responseData.success == true) {
                                    Ext.MessageBox.alert('Información', 'La pregunta se eliminó correctamente.'); 
                                    Ext.gest_evaluaciones.pregeval_store.load();
                                    Ext.gest_evaluaciones.preguntas_store.load();
                                }
                                if (responseData.success == false) {
                                    Ext.MessageBox.alert('Información', 'No se puede eliminar pues esta asignada a una evaluacion.'); 
                                    Ext.gest_evaluaciones.pregeval_store.load();
                                    Ext.gest_evaluaciones.preguntas_store.load();
                                }
                            }
                        });
                    }
                },
                icon: Ext.MessageBox.QUESTION
            });

        }
        if (btn.text == 'Asignar Pregunta a la Evalucación') {
            if (!Ext.gest_evaluaciones.preguntas_store.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento para asignar');
                return false;
            }

            Ext.MessageBox.show({
                title: 'Mensaje de confirmación',
                msg: '¿ Usted está seguro que desea asignar la pregunta ?',
                buttons: Ext.MessageBox.YESNOCANCEL,
                fn: function (btn) {
                    if (btn == 'yes') {
                        Ext.Ajax.request({
                            url: 'preguntas/asignarpreg',
                            method: 'POST',
                            params: {
                                        id_evaluacion: Ext.gest_evaluaciones.sm.getSelected().get("id"),
                                        id_pregunta:   Ext.gest_evaluaciones.preguntas_store.sm.getSelected().get("id_pregunta")
                                    },
                            callback: function (options, success, response) {
                                responseData = Ext.decode(response.responseText);
                                if (responseData.success == true) {
                                    Ext.MessageBox.alert('Información', 'La pregunta se asignó correctamente.'); 
                                    Ext.gest_evaluaciones.pregeval_store.load();
                                    //Ext.gest_evaluaciones.preguntas_store.load();
                                }
                                if (responseData.success == false) {
                                    Ext.MessageBox.alert('Información', 'Error!!!.'); 
                                    Ext.gest_evaluaciones.pregeval_store.load();
                                    Ext.gest_evaluaciones.preguntas_store.load();
                                }
                            }
                        });
                    }
                },
                icon: Ext.MessageBox.QUESTION
            });

        }
        if (btn.text == 'Eliminar Pregunta de la Evaluación') {
            if (!Ext.gest_evaluaciones.pregeval_store.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento para eliminar asignación');
                return false;
            }

            Ext.MessageBox.show({
                title: 'Mensaje de confirmación',
                msg: '¿ Usted está seguro que desea quitar la asignación de la pregunta ?',
                buttons: Ext.MessageBox.YESNOCANCEL,
                fn: function (btn) {
                    if (btn == 'yes') {
                        Ext.Ajax.request({
                            url: 'preguntas/quitarpreguntaevaluacion',
                            method: 'POST',
                            params: {
                                        id_evaluacion: Ext.gest_evaluaciones.sm.getSelected().get("id"),
                                        id_pregunta:   Ext.gest_evaluaciones.pregeval_store.sm.getSelected().get("id_pregunta")
                                    },
                            callback: function (options, success, response) {
                                responseData = Ext.decode(response.responseText);
                                if (responseData.success == true) {
                                    Ext.MessageBox.alert('Información', 'La pregunta se eliminó correctamente.'); 
                                    Ext.gest_evaluaciones.pregeval_store.load();
                                    Ext.gest_evaluaciones.preguntas_store.load();
                                }
                                if (responseData.success == false) {
                                    Ext.MessageBox.alert('Información', 'Error!!!.'); 
                                    Ext.gest_evaluaciones.pregeval_store.load();
                                    Ext.gest_evaluaciones.preguntas_store.load();
                                }
                            }
                        });
                    }
                },
                icon: Ext.MessageBox.QUESTION
            });

        }

        //Resaltar

        if (btn.text == 'Resaltar') {
            if (!Ext.gest_evaluaciones.pregeval_store.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento para resaltar');
                return false;
            }

             Ext.Ajax.request({
                url: 'preguntas/resaltarpreguntaeva',
                method: 'POST',
                params: {
                          id_evaluacion: Ext.gest_evaluaciones.sm.getSelected().get("id"),
                          id_pregunta:   Ext.gest_evaluaciones.pregeval_store.sm.getSelected().get("id_pregunta")
                        },
                        callback: function (options, success, response) {
                          responseData = Ext.decode(response.responseText);
                          if (responseData.success == true) {
                            Ext.MessageBox.alert('Información', 'La pregunta se resaltó correctamente.'); 
                            Ext.gest_evaluaciones.pregeval_store.load();
                          }
                          if (responseData.success == false) {
                             Ext.MessageBox.alert('Información', 'Error!!!.');                                    
                          }
                        }
            });  
        }
        if (btn.text == 'Quitar resalto') {
            if (!Ext.gest_evaluaciones.pregeval_store.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento para eliminar resaltar');
                return false;
            }

             Ext.Ajax.request({
                url: 'preguntas/quitarresaltarpreguntaeva',
                method: 'POST',
                params: {
                          id_evaluacion: Ext.gest_evaluaciones.sm.getSelected().get("id"),
                          id_pregunta:   Ext.gest_evaluaciones.pregeval_store.sm.getSelected().get("id_pregunta")
                        },
                        callback: function (options, success, response) {
                          responseData = Ext.decode(response.responseText);
                          if (responseData.success == true) {
                            Ext.MessageBox.alert('Información', 'Se eliminó correctamente el resalto.'); 
                            Ext.gest_evaluaciones.pregeval_store.load();
                          }
                          if (responseData.success == false) {
                             Ext.MessageBox.alert('Información', 'Error!!!.');                                    
                          }
                        }
            });  
        }

    }

    //Botones Grupo preguntas

    Ext.gest_evaluaciones.myBtnHandlerGrupo = function (btn) {

        if (btn.text == 'Adicionar') {
            if (!Ext.gest_evaluaciones.wingrupo) {
                var title = 'Adicionar';
                Ext.gest_evaluaciones.wingrupo = new Ext.Window({
                    closeAction: 'hide',
                    title: title,
                    height: 110,
                    width: 300,
                    modal:true,
                    constrain: true,
                    items: [Ext.gest_evaluaciones.fpGrupos],
                    buttons: [
                        {
                            text: 'Aceptar',
                            handler: function (btn) {
                                if (Ext.gest_evaluaciones.fpGrupos.getForm().isValid()) {
                                    Ext.gest_evaluaciones.fpGrupos.el.mask('Por favor espere..', 'x-mask-loading');
                                    Ext.gest_evaluaciones.fpGrupos.getForm().submit({
                                        url: 'grupos/create',
                                        success: function (form, action) {
                                            Ext.gest_evaluaciones.fpGrupos.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_evaluaciones.wingrupo.hide();
                                                Ext.gest_evaluaciones.grupos_store.load();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        },
                                        failure: function (form, action) {
                                            Ext.gest_evaluaciones.fpGrupos.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_evaluaciones.wingrupo.hide();
                                                Ext.gest_evaluaciones.grupos_store.load();
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
                                Ext.gest_evaluaciones.wingrupo.hide();
                            }
                        }
                    ]
                });
            } else {
                Ext.gest_evaluaciones.fpGrupos.getForm().reset();
            }
            
            Ext.gest_evaluaciones.wingrupo.add(Ext.gest_evaluaciones.fpGrupos);
            Ext.gest_evaluaciones.wingrupo.doLayout();
            Ext.gest_evaluaciones.wingrupo.show();
        }

        if (btn.text == 'Modificar') {

            if (!Ext.gest_evaluaciones.grupos_store.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento para modificar');
                return false;
            }

            if (!Ext.gest_evaluaciones.wingrupomod) {
                var title = 'Modificar';
                Ext.gest_evaluaciones.wingrupomod = new Ext.Window({
                    closeAction: 'hide',
                    title: title,
                    height: 110,
                    width: 300,
                    modal:true,
                    constrain: true,
                    items: [Ext.gest_evaluaciones.fpGrupos],
                    buttons: [
                        {
                            text: 'Aceptar',
                            handler: function (btn) {
                                if (Ext.gest_evaluaciones.fpGrupos.getForm().isValid()) {
                                    Ext.gest_evaluaciones.fpGrupos.el.mask('Por favor espere..', 'x-mask-loading');
                                    Ext.gest_evaluaciones.fpGrupos.getForm().submit({
                                        url: 'grupos/update',
                                        success: function (form, action) {
                                            Ext.gest_evaluaciones.fpGrupos.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_evaluaciones.wingrupomod.hide();
                                                Ext.gest_evaluaciones.grupos_store.load();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        },
                                        failure: function (form, action) {
                                            Ext.gest_evaluaciones.fpGrupos.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_evaluaciones.wingrupomod.hide();
                                                Ext.gest_evaluaciones.grupos_store.load();
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
                                Ext.gest_evaluaciones.wingrupomod.hide();
                            }
                        }
                    ]
                });
            } else {
                Ext.gest_evaluaciones.fpGrupos.getForm().reset();
            }
            Ext.gest_evaluaciones.fpGrupos.getForm().reset();
            Ext.gest_evaluaciones.wingrupomod.add(Ext.gest_evaluaciones.fpGrupos);
            Ext.gest_evaluaciones.wingrupomod.doLayout();
            Ext.gest_evaluaciones.wingrupomod.show();
            Ext.gest_evaluaciones.fpGrupos.getForm().loadRecord(Ext.gest_evaluaciones.grupos_store.sm.getSelected());
        }

        if (btn.text == 'Eliminar') {
            if (!Ext.gest_evaluaciones.grupos_store.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento para eliminar');
                return false;
            }

            Ext.MessageBox.show({
                title: 'Mensaje de confirmación',
                msg: '¿ Usted está seguro que desea eliminar el grupo ?',
                buttons: Ext.MessageBox.YESNOCANCEL,
                fn: function (btn) {
                    if (btn == 'yes') {
                        Ext.Ajax.request({
                            url: 'grupos/delete',
                            method: 'POST',
                            params: {id: Ext.gest_evaluaciones.grupos_store.sm.getSelected().get("id")},
                            callback: function (options, success, response) {
                                responseData = Ext.decode(response.responseText);
                                if (responseData.success == true) {
                                    Ext.MessageBox.alert('Información', 'La asignatura se eliminó correctamente.'); 
                                    Ext.gest_evaluaciones.grupos_store.load();
                                }
                            }
                        });
                    }
                },
                icon: Ext.MessageBox.QUESTION
            });

        }
    }

    Ext.gest_evaluaciones.stEvaluaciones = new Ext.data.Store({
        url: 'evaluaciones/cargar',
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
            {name: 'modalidad'},
            {name: 'tipo'},
            {name: 'id_grupo_origen'},
            {name: 'grupo_origen'},
            {name: 'id_periodo'},
            {name: 'fecha'},
            {name: 'descripcion'}, 
            {name: 'estado'},
            {name: 'titulo'}, 
            {name: 'nombre_periodo'}
        ])
    });

    Ext.gest_evaluaciones.addBtn = new Ext.Button({
        text: 'Adicionar',
        handler: Ext.gest_evaluaciones.myBtnHandler,
        icon: '../../images/add.png'
    });

    Ext.gest_evaluaciones.editBtn = new Ext.Button({
        text: 'Modificar',
        handler: Ext.gest_evaluaciones.myBtnHandler,
        icon: '../../images/bogus.png'
    });

    Ext.gest_evaluaciones.editDel = new Ext.Button({
        text: 'Eliminar',
        handler: Ext.gest_evaluaciones.myBtnHandler,
        icon: '../../images/delete.gif'
    });

    Ext.gest_evaluaciones.procesar = new Ext.Button({
        text: 'Procesar',
        handler: Ext.gest_evaluaciones.myBtnHandler,
        icon: '../../images/Validar.png'
    });

    Ext.gest_evaluaciones.finalizar = new Ext.Button({
        text: 'Finalizar',
        handler: Ext.gest_evaluaciones.myBtnHandler,
        icon: '../../images/Properties_16x16.png'
    });

    Ext.gest_evaluaciones.duplicar = new Ext.Button({
        text: 'Duplicar',
        handler: Ext.gest_evaluaciones.myBtnHandler,
        icon: '../../images/Copyv2_16x16.png'
    });

    Ext.gest_evaluaciones.preguntas = new Ext.Button({
        text: 'Preguntas',
        handler: Ext.gest_evaluaciones.myBtnHandler,
        icon: '../../images/add.png'
    });

    Ext.gest_evaluaciones.grupospreguntas = new Ext.Button({
        text: 'Grupos Preguntas',
        handler: Ext.gest_evaluaciones.myBtnHandler,
        icon: '../../images/add.png'
    });

    Ext.gest_evaluaciones.addBtn1 = new Ext.Button({
        text: 'Adicionar',
        handler: Ext.gest_evaluaciones.myBtnHandlerPreguntas,
        icon: '../../images/add.png'
    });

    Ext.gest_evaluaciones.editBtn2 = new Ext.Button({
        text: 'Modificar',
        handler: Ext.gest_evaluaciones.myBtnHandlerPreguntas,
        icon: '../../images/bogus.png'
    });

    Ext.gest_evaluaciones.editDel3 = new Ext.Button({
        text: 'Eliminar',
        handler: Ext.gest_evaluaciones.myBtnHandlerPreguntas,
        icon: '../../images/delete.gif'
    });
    Ext.gest_evaluaciones.agregarp = new Ext.Button({
        text: 'Asignar Pregunta a la Evalucación',
        handler: Ext.gest_evaluaciones.myBtnHandlerPreguntas,
        icon: '../../images/backward.png'
    });
    
    Ext.gest_evaluaciones.delasig = new Ext.Button({
        text: 'Eliminar Pregunta de la Evaluación',
        handler: Ext.gest_evaluaciones.myBtnHandlerPreguntas,
        icon: '../../images/eliminar_modelo.png'
    });

    //Resaltar 
    Ext.gest_evaluaciones.resaltar_pregunta_eva= new Ext.Button({
        text: 'Resaltar',
        handler: Ext.gest_evaluaciones.myBtnHandlerPreguntas,
        icon: '../../images/query_add.png'
    });
    //quitar resalto
    Ext.gest_evaluaciones.quitar_resaltar_pregunta_eva= new Ext.Button({
        text: 'Quitar resalto',
        handler: Ext.gest_evaluaciones.myBtnHandlerPreguntas,
        icon: '../../images/query_delete.png'
    });

    Ext.gest_evaluaciones.buscar  = new Ext.Button({
        text: 'Buscar',
        handler: Ext.gest_evaluaciones.myBtnHandler,
        icon: '../../images/lupa.png'
    });

    Ext.gest_evaluaciones.addBtnGrupo = new Ext.Button({
        text: 'Adicionar',
        handler: Ext.gest_evaluaciones.myBtnHandlerGrupo,
        icon: '../../images/add.png'
    });

    Ext.gest_evaluaciones.editBtnGrupo = new Ext.Button({
        text: 'Modificar',
        handler: Ext.gest_evaluaciones.myBtnHandlerGrupo,
        icon: '../../images/bogus.png'
    });

    Ext.gest_evaluaciones.editDelGrupo = new Ext.Button({
        text: 'Eliminar',
        handler: Ext.gest_evaluaciones.myBtnHandlerGrupo,
        icon: '../../images/delete.gif'
    });

    Ext.gest_evaluaciones.tbFill         = new Ext.Toolbar.Fill();
    Ext.gest_evaluaciones.sm             = new Ext.grid.RowSelectionModel({});
    Ext.gest_evaluaciones.gpEvaluaciones = new Ext.grid.GridPanel({
        frame: true,
        iconCls: 'icon-grid',
        loadMask: 'Cargando...',
        width: 450,
        store: Ext.gest_evaluaciones.stEvaluaciones,
        clicksToEdit: 1,
        sm: Ext.gest_evaluaciones.sm,
        stripeRows: true,
        floating: false,
        columns: [
            new Ext.grid.RowNumberer(),
            {hidden: true, hideable: false, dataIndex: 'id'},          
            {header: 'Titulo', width: 400, dataIndex: 'titulo'}, 
            {header: 'Periodo', width: 250, dataIndex: 'nombre_periodo'},
            {header: 'Tipo', width: 150, dataIndex: 'tipo'},       
            {header: 'Estado', width: 100, dataIndex: 'estado'},
            {header: 'Fecha', width: 100, dataIndex: 'fecha'},
            {header: 'Grupo origen', width: 150, dataIndex: 'grupo_origen'}    
        ],
        tbar: [
              Ext.gest_evaluaciones.addBtn, '-',
              Ext.gest_evaluaciones.editBtn, '-',
              Ext.gest_evaluaciones.editDel, '-',
              Ext.gest_evaluaciones.procesar, '-',
              Ext.gest_evaluaciones.finalizar, '-',
              Ext.gest_evaluaciones.duplicar,'-',
              Ext.gest_evaluaciones.preguntas, '-',
              Ext.gest_evaluaciones.grupospreguntas,'->',
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
                                         Ext.gest_evaluaciones.stEvaluaciones.load({params: {start: 0,limit: 12}});
                                    }
                         }
                        }      
              },
              Ext.gest_evaluaciones.buscar
        ],
        bbar: new Ext.PagingToolbar({
            pageSize: 12,
            store: Ext.gest_evaluaciones.stEvaluaciones,
            displayInfo: true,
            displayMsg: 'Resultados {0} - {1} de {2}',
            emptyMsg: 'Ning&uacute;n resultado para mostrar.'
        })
    });

    //Activar o desactivar botones

    Ext.gest_evaluaciones.sm.on("rowselect", function(){
        if(Ext.gest_evaluaciones.sm.getSelected())
         {
             if( Ext.gest_evaluaciones.sm.getSelected().get("estado") == 'Finalizada')
             {
                 Ext.gest_evaluaciones.editBtn.disable();
                 Ext.gest_evaluaciones.editDel.disable();
                 Ext.gest_evaluaciones.procesar.disable();
                 Ext.gest_evaluaciones.preguntas.disable();
                 Ext.gest_evaluaciones.finalizar.disable();
             }
             if( Ext.gest_evaluaciones.sm.getSelected().get("estado") == 'En proceso')
             {
                //habilitar todo
                 Ext.gest_evaluaciones.finalizar.enable();
                //Desabilitar las que tocan.
                 Ext.gest_evaluaciones.editBtn.disable();
                 Ext.gest_evaluaciones.editDel.disable();              
                 Ext.gest_evaluaciones.procesar.disable();
                 Ext.gest_evaluaciones.preguntas.disable();
             }
             if( Ext.gest_evaluaciones.sm.getSelected().get("estado") == 'Elaboración')
             {
                //habilitar todo
                 Ext.gest_evaluaciones.editBtn.enable();
                 Ext.gest_evaluaciones.editDel.enable();
                 Ext.gest_evaluaciones.preguntas.enable();
                 Ext.gest_evaluaciones.procesar.enable();
                 Ext.gest_evaluaciones.finalizar.enable();
             }
         }
    });


    /*********************
    //Gestión Preguntas  *
    *********************/

    Ext.gest_evaluaciones.preguntas_store = new Ext.data.Store({
        url: 'preguntas/cargarpreguntas',
        autoLoad: false,
        reader: new Ext.data.JsonReader({
            root: "data",
            totalProperty: "count",
            id: "id_pregunta"
        }, [
            {name: 'id_pregunta'},
            {name: 'texto'},
            {name: 'tipo'},
            {name: 'opciones'},
            {name: 'id_g_pregunta'},
            {name: 'nombre_grupo'},
            {name: 'tipo_texto'},
        ])
    });


     Ext.gest_evaluaciones.tipo_evaluaciones = new Ext.data.Store({
        url: 'evaluaciones/cargartipopregunta',
        autoLoad: true,
        reader: new Ext.data.JsonReader({
            root: "data",
            totalProperty: "count",
            id: "id_pregunta"
        }, [
            {name: 'tipo'},
            {name: 'tipo_texto'},
          ])
    });



    //Combobox Tipo preguntas
    Ext.gest_evaluaciones.combopreg = new Ext.form.ComboBox({
        hiddenName: 'tipo',
        valueField: 'tipo',
        displayField: 'tipo_texto',
        store: Ext.gest_evaluaciones.tipo_evaluaciones,
        autoCreate: true,
        typeAhead: true,
        triggerAction: 'all',
        readOnly: false,
        mode: 'local',
        width: 200,
        anchor: '95%',      
        align: 'rigth',
        fieldLabel: 'Tipo',
        allowBlank: false,
    });

    //Store Preguntas de la evaluación selected

    Ext.gest_evaluaciones.pregeval_store = new Ext.data.Store({
        url: 'preguntas/cargarpregeval',
        autoLoad: false,
        listeners: {'beforeload': function (store, objeto) {
            store.baseParams.id_evaluacion = Ext.gest_evaluaciones.sm.getSelected().get("id");
        }},
        reader: new Ext.data.JsonReader({
            root: "data",
            totalProperty: "count",
            id: "id_pregunta"
        }, [
            {name: 'id_pregunta',type: 'string'},
            {name: 'texto',type: 'string'},
            {name: 'tipo',type: 'string'},
            {name: 'opciones',type: 'string'},
            {name: 'id_g_pregunta',type: 'string'},
            {name: 'tipo_texto',type: 'string'},
            {name: 'resaltar',type: 'bool'}
        ])
    });

     /**
     * Custom function used for column renderer
     * @param {Object} val
     */
    function change(val) {
        if (val > 0) {
            return '<img src="../../images/Check_16x16.png" />';
        } else if (val == 0) {
            return '<img align="center" src="../../images/Remove_16x16.png" />';
        }
        return val;
    }

    Ext.gest_evaluaciones.pregeval_store.sm  = new Ext.grid.RowSelectionModel({});
    Ext.gest_evaluaciones.preguntas_grid     = new Ext.grid.EditorGridPanel({
        frame: true,
        title: 'Preguntas de la evaluación',
        iconCls: 'icon-grid',
        autoExpandColumn: 'expandir',
        loadMask: 'Cargando...',
        width: 450,
        store: Ext.gest_evaluaciones.pregeval_store,
        clicksToEdit: 1,
        sm: Ext.gest_evaluaciones.pregeval_store.sm,
        stripeRows: true,
        floating: false,
        region: 'center',
        columns: [
            new Ext.grid.RowNumberer(),
            {hidden: true, hideable: false, dataIndex: 'id_pregunta'},          
            {header: 'Texto', width: 200, dataIndex: 'texto', id: 'expandir'},  
            {header: 'Tipo', width: 100, dataIndex: 'tipo_texto'},
            {hidden: true, hideable: false, dataIndex: 'tipo'}, 
            {header: 'Resaltar',dataIndex: 'resaltar',width: 55, renderer : change}
        ],
        tbar: [
              Ext.gest_evaluaciones.delasig,
              Ext.gest_evaluaciones.resaltar_pregunta_eva,
              Ext.gest_evaluaciones.quitar_resaltar_pregunta_eva
        ],
        /*bbar: new Ext.PagingToolbar({
            pageSize: 12,
            store: Ext.gest_evaluaciones.stEvaluaciones,
            displayInfo: true,
            displayMsg: 'Resultados {0} - {1} de {2}',
            emptyMsg: 'Ning&uacute;n resultado para mostrar.'
        })*/
    });

    Ext.gest_evaluaciones.preguntas_store.sm = new Ext.grid.RowSelectionModel({});
    Ext.gest_evaluaciones.preguntas_grid_todas = new Ext.grid.GridPanel({
        frame: true,
        title: 'Preguntas Exitentes',
        iconCls: 'icon-grid',
        autoExpandColumn: 'expandir',
        loadMask: 'Cargando...',
        width: 450,
        store: Ext.gest_evaluaciones.preguntas_store,
        clicksToEdit: 1,
        sm: Ext.gest_evaluaciones.preguntas_store.sm,
        stripeRows: true,
        floating: false,
        region: 'east',
        columns: [
            new Ext.grid.RowNumberer(),
            {hidden: true, hideable: false, dataIndex: 'id_pregunta'},          
            {header: 'Texto', width: 200, dataIndex: 'texto', id: 'expandir'},  
            {header: 'Tipo', width: 100, dataIndex: 'tipo_texto'},
            {hidden: true, hideable: false, dataIndex: 'tipo'},       
        ],
        tbar: [
              Ext.gest_evaluaciones.addBtn1, '-',
              Ext.gest_evaluaciones.editBtn2, '-',
              Ext.gest_evaluaciones.editDel3, '-',
              Ext.gest_evaluaciones.agregarp
        ],
        bbar: new Ext.PagingToolbar({
            pageSize: 14,
            store: Ext.gest_evaluaciones.preguntas_store,
            displayInfo: true,
            displayMsg: 'Resultados {0} - {1} de {2}',
            emptyMsg: 'Ning&uacute;n resultado para mostrar.'
        })
    });

    Ext.gest_evaluaciones.grupos_store = new Ext.data.Store({
        url: 'grupos/cargargrupos',
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

    //grid grupos

    Ext.gest_evaluaciones.grupos_store.sm = new Ext.grid.RowSelectionModel({});
    Ext.gest_evaluaciones.grupos_grid = new Ext.grid.GridPanel({
        frame: true,
        title: 'Grupos',
        iconCls: 'icon-grid',
        //autoExpandColumn: 'expandir',
        loadMask: 'Cargando...',
        width: 450,
        store: Ext.gest_evaluaciones.grupos_store,
        clicksToEdit: 1,
        sm: Ext.gest_evaluaciones.grupos_store.sm,
        stripeRows: true,
        floating: false,
        region: 'east',
        columns: [
            new Ext.grid.RowNumberer(),
            {hidden: true, hideable: false, dataIndex: 'id'},          
            {header: 'Nombre', width: 500, dataIndex: 'nombre'},  
   
        ],
        tbar: [
              Ext.gest_evaluaciones.addBtnGrupo, '-',
              Ext.gest_evaluaciones.editBtnGrupo, '-',
              Ext.gest_evaluaciones.editDelGrupo
        ],
        bbar: new Ext.PagingToolbar({
            pageSize: 14,
            store: Ext.gest_evaluaciones.grupos_store,
            displayInfo: true,
            displayMsg: 'Resultados {0} - {1} de {2}',
            emptyMsg: 'Ning&uacute;n resultado para mostrar.'
        })
    });




    /////////////////////////////////////
    //Combobox autofill grupo preguntas//
    /////////////////////////////////////

    Ext.gest_evaluaciones.stcombogrupopreguntas = new Ext.data.Store({
        url: 'grupos/cargartodos',
        autoLoad: true,
        reader: new Ext.data.JsonReader({
            root: "data",
            id: "id"
        }, [
            {name: 'id'},
            {name: 'nombre'}
        ])
    });
    
    Ext.gest_evaluaciones.grupopreguntas = new Ext.form.ComboBox({
        //id: 'mc_gest_usuarios_roles',
        hiddenName: 'id_g_pregunta',
        valueField: 'id',
        displayField: 'nombre',
        store: Ext.gest_evaluaciones.stcombogrupopreguntas,
        autoCreate: true,
        typeAhead: true,
        triggerAction: 'all',
        readOnly: false,
        mode: 'local',
        width: 250,
        allowBlank: false,
        anchor: '85%',
        listWidth: 250,
        align: 'rigth',
        fieldLabel: 'Grupo',
    });

    //fin combo 

    Ext.gest_evaluaciones.fpGrupos = new Ext.form.FormPanel({
        frame: true,
        bodyStyle: 'padding: 6px',
        labelWidth: 105,
        defaultType: 'textfield',
        defaults: {
            msgTarget: 'side',
            anchor: '-20'
        },
        items: [{
            fieldLabel: 'Nombre del grupo',
            allowBlank: false,
            name: 'nombre',
            emptyText: 'Este campo esta vacio!',            
        },
        {
            fieldLabel: 'id',
            allowBlank: true,
            name: 'id',
            hidden: true
        }
        ]
    });



    Ext.gest_evaluaciones.fppreguntas = new Ext.form.FormPanel({
        frame: true,
        bodyStyle: 'padding: 6px',
        labelWidth: 60,
        defaultType: 'textfield',
        defaults: {
            msgTarget: 'side',
            anchor: '-20'
        },
        items: [{
            fieldLabel: 'Texto',
            allowBlank: false,
            name: 'texto',
            emptyText: 'Este campo esta vacio!'
        },
        Ext.gest_evaluaciones.combopreg,
        {
            fieldLabel: 'Opciones',
            //allowBlank: false,
            name: 'opciones',
            emptyText: 'Separe por comas las opciones ("Bien,Regular")'
        },
        Ext.gest_evaluaciones.grupopreguntas,
        {
            fieldLabel: 'id',
            allowBlank: true,
            name: 'id_pregunta',
            hidden: true
        }]
    });

    

    Ext.gest_evaluaciones.ventana_evaluaciones = new Ext.Window({
        title:'Gestión de las preguntas',
        closeAction: 'hide',
        height: 450,
        width: 1000,
        layout:'border',
        constrain: true,
        modal:true,
        items:[Ext.gest_evaluaciones.preguntas_grid,Ext.gest_evaluaciones.preguntas_grid_todas]
    });

    Ext.gest_evaluaciones.ventana_grupos = new Ext.Window({
        title:'Gestión de los grupos',
        closeAction: 'hide',
        height: 450,
        width: 600,
        layout:'fit',
        constrain: true,
        modal:true,
        items:[Ext.gest_evaluaciones.grupos_grid]
    });

  
    new Ext.Viewport({
        layout: 'fit',
        items: [
            Ext.gest_evaluaciones.gpEvaluaciones
        ]
    });

});