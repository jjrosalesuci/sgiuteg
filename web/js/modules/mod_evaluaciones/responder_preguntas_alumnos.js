/*
* Este es para los estudiantes
*/
 Ext.onReady(function(){
    Ext.QuickTips.init();


    Ext.namespace('Ext.gest_evaluaciones_resp');
   //Tipo de evaluacion
    Ext.gest_evaluaciones_resp.materia_store = new Ext.data.Store({
        url: '../responder/getuseraclmaterias',
        autoLoad: true,
        listeners: {'beforeload': function (store, objeto) {
            store.baseParams.id_user_acl = id_user_acl;
        }},
        reader: new Ext.data.JsonReader({
            root: "data",
            id: "id"
        }, [
            {name: 'id_materia'},
            {name: 'nombre'}
        ])
    });


    Ext.gest_evaluaciones_resp.materia = new Ext.form.ComboBox({
        hiddenName: 'materia',
        valueField: 'id_materia',
        displayField: 'nombre',
        store: Ext.gest_evaluaciones_resp.materia_store,
        autoCreate: true,
        typeAhead: true,
        triggerAction: 'all',
        readOnly: false,
        autoSelect:false,
        mode: 'local',
        emptyText:'Seleccione una materia...',
        width: 300,
        align: 'rigth',
        fieldLabel: 'Tipo',
        allowBlank: false,
        applyTo: 'materia',
       // autoSelect:true,
        listeners: {
            'select': function(combo,record,index){
                Ext.gest_evaluaciones_resp.docente.reset();
                Ext.gest_evaluaciones_resp.docente_store.load({noddde:'1'});
            }
        }
    });
    
    //id_user_acl
    Ext.gest_evaluaciones_resp.docente_store = new Ext.data.Store({
        url: '../responder/getmateriadocentes',
        listeners: {'beforeload': function (store, objeto) {
            store.baseParams.id_materia    = Ext.gest_evaluaciones_resp.materia.getValue();
            store.baseParams.id_evaluacion = id_evaluacion;
        }},
        reader: new Ext.data.JsonReader({
            root: "data_e",
             id: "id_e"
        }, [
            {name: 'id_docente_e'},
            {name: 'nombre_e'}
        ])
    });

      Ext.gest_evaluaciones_resp.docente = new Ext.form.ComboBox({
        hiddenName: 'docente',
        valueField: 'id_docente_e',
        displayField: 'nombre_e',
        store: Ext.gest_evaluaciones_resp.docente_store,
         autoCreate: true,
         autoSelect:false,
       // typeAhead: true,
        triggerAction: 'all',
         emptyText:'Seleccione un docente...',
        //readOnly: false,
        mode: 'local',
        width: 300,
        align: 'rigth',
        loadingText:'Cargandoo..',
        //fieldLabel: 'Tipo',
        allowBlank: false,
        applyTo: 'docente'
    });


   // Ext.GetCmp('the-table');
    
});