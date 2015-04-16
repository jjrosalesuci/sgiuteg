/*
* Este es para los estudiantes
*/
 Ext.onReady(function(){
    Ext.QuickTips.init();

    Ext.namespace('Ext.gest_evaluaciones_resp');
   //Tipo de evaluacion

    Ext.gest_evaluaciones_resp.materia_store = new Ext.data.Store({
        url: '../responder/getmatedocentes',
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
    });    


    Ext.gest_evaluaciones_resp.store_carreras = new Ext.data.JsonStore({
        root: 'data',
        totalProperty : 'count',
        url: '../responder/getcarrerasdeldocente',
        autoLoad: true,
        listeners: {'beforeload': function (store, objeto) {
            store.baseParams.id_docente = id_docente;
        }},
        baseParams: {
            column : 'nombre'
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
        ]       
    });
    
    Ext.gest_evaluaciones_resp.combo_carreras  = new Ext.form.ComboBox({
       hiddenName: 'carrera',
       fieldLabel: 'Carreras',
       forceSelection:true,
       displayField:'nombre',
       valueField:'id',
       width: 300,
       align: 'rigth',
       totalProperty : 'count',
       emptyText:'Seleccione una carrera...',
       anchor: '95%',
       hiddenName:'nombre_carrera',
       hiddenValue: 'id_carrera',
       loadingText:'Buscando....',
       minChars:1,
       triggerAction:'all',
       applyTo: 'carrera',
       store: Ext.gest_evaluaciones_resp.store_carreras
    });
   

   // Ext.GetCmp('the-table');
    
});