/*
* Este es para los estudiantes
*/
 Ext.onReady(function(){
    Ext.QuickTips.init();


    Ext.namespace('Ext.gest_evaluaciones_resp');

    //Carreras

    Ext.gest_evaluaciones_resp.store_carreras = new Ext.data.JsonStore({
        root: 'data',
        totalProperty : 'count',
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
        ],
        proxy : new Ext.data.ScriptTagProxy({
            url : '../../mod_nomencladores/carreras/uploadsearch'
        })
    });
    
    Ext.gest_evaluaciones_resp.combo_carreras  = new Ext.form.ComboBox({
       hiddenName: 'carrera',
       fieldLabel: 'Carreras',
       forceSelection:true,
       displayField:'nombre',
       hideTrigger:true,
       valueField:'id',
       pageSize : 20,
       width: 300,
       align: 'rigth',
       totalProperty : 'count',
       emptyText:'Buscar carrera...',

       anchor: '95%',
       hiddenName:'nombre_carrera',
       hiddenValue: 'id_carrera',
       loadingText:'Buscando....',
       minChars:1,
       triggerAction:'all',
       applyTo: 'carrera',
       store: Ext.gest_evaluaciones_resp.store_carreras
    });

    //Materias
     Ext.gest_evaluaciones_resp.store_materias = new Ext.data.JsonStore({
        root: 'data',
        totalProperty : 'count',
        baseParams: {
            column : 'nombre'
        },
        fields : [
        {
            name: 'nombre_m',
            mapping : 'nombre_m'
        },
        {
            name: 'id_m',
            mapping : 'id_m'
        }
        ],
        proxy : new Ext.data.ScriptTagProxy({
            url : '../../mod_nomencladores/asignaturas/uploadsearch'
        })
    });
    
    Ext.gest_evaluaciones_resp.combo_materias = new Ext.form.ComboBox({
       xtype :'combo',
       fieldLabel: 'Asignaturas',
       forceSelection:true,
       displayField:'nombre_m',
       hiddenName: 'materia',
       valueField: 'id_m',      
       pageSize : 20,
       width: 300,
       totalProperty : 'count',
       emptyText:'Buscar materia...',
       anchor: '95%',
       hiddenName:'materia',
       hideTrigger:true,
       hiddenValue: 'id_m',
       loadingText:'Buscando....',
       minChars:1,
       triggerAction:'all',
       applyTo: 'materia',
       store: Ext.gest_evaluaciones_resp.store_materias,
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
            store.baseParams.id_materia    = Ext.gest_evaluaciones_resp.combo_materias.getValue();
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