Ext.onReady(function() {

    Ext.QuickTips.init();
    Ext.ns('Ext.responder_decano');
    
    Ext.responder_decano.reader = new Ext.data.JsonReader({
      idProperty: 'id',
      root: 'data',
      totalProperty: 'count',
      fields: ['id_evaluacion','id_asignatura','id_periodo','nombre_periodo','id_trabajador','nombre_trabajador', 'nombre_asignatura', 'e_1', 'e_2','e_3','e_4']
    });

    Ext.responder_decano.groupingStore = new Ext.data.GroupingStore({
      url: 'resultados/getevaluados',
      listeners: {'beforeload': function (store, objeto) {
            store.baseParams.query         = Ext.getCmp('buscar_usuario').getRawValue();
            store.baseParams.id_periodo    = Ext.responder_decano.comboevaluaciones.getValue();
      }},
      //autoLoad: true,
      reader: Ext.responder_decano.reader,
      sortInfo: { field: 'nombre_asignatura', direction: "desc" },
      groupField: 'nombre_trabajador'
    });


    /////////////////////////////////////
    //    Combobox autofill periodo    //
    /////////////////////////////////////

    Ext.responder_decano.stcombogrupoparcial = new Ext.data.JsonStore({
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
    
    Ext.responder_decano.comboevaluaciones = new Ext.form.ComboBox({
        store: Ext.responder_decano.stcombogrupoparcial,
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
        listeners: {
            'select': function(combo,record,index){
               Ext.responder_decano.groupingStore.load({params: {start: 0,limit: 14}});               
            }
        }
    });    

    //fin combo



    Ext.responder_decano.sm  = new Ext.grid.RowSelectionModel({});
    Ext.responder_decano.grid = new Ext.grid.GridPanel({
        store: Ext.responder_decano.groupingStore,
        frame:true,
        title: 'Docentes',
        sm: Ext.responder_decano.sm,
        loadMask: true,
        renderTo: Ext.getBody(),
        columns: [
            {hidden: true, hideable: false, dataIndex: 'id_docente'},
            {hidden: true, hideable: false, dataIndex: 'id_materia'},
            {id:'nombre_trabajador',header: "Nombre", width: 60,hidden: true, sortable: true, dataIndex: 'nombre_trabajador'},
            {header: "Materias", width: 50,sortable: true, dataIndex: 'nombre_asignatura'},
            {header: "Evaluación 1", width: 25, sortable: true, dataIndex: 'e_1'},
            {header: "Evaluación 2", width: 25, sortable: true, dataIndex: 'e_2'},
            {header: "Evaluación 3", width: 25, sortable: true, dataIndex: 'e_3'},
            {header: "Evaluación 4", width: 25, sortable: true, dataIndex: 'e_4'},         
            /*{
                xtype: 'actioncolumn',
                width: 8,
                css: 'text-align: center',
                items: [{
                    icon   :'../../images/Validar.png',  // Use a URL in the icon config
                    tooltip:'Evaluar docente',
                    handler: function(grid, rowIndex, colIndex) {
                        //var rec = store.getAt(rowIndex);
                        alert("Pagina para evaluar");
                    }
                }]
            },*/
            {
                xtype: 'actioncolumn',
                width: 8,
                css: 'text-align: center',
                items: [{
                    icon   : '../../images/view.png',
                    tooltip: 'Ver resultado de la evaluacion',
                    handler: function(grid,rowIndex,colIndex) {
                        var rec = Ext.responder_decano.groupingStore.getAt(rowIndex);
                        window.location="resultados/resultado"+"?id_trabajador="+rec.data.id_trabajador+"&id_periodo="+rec.data.id_periodo+"&nombre_periodo="+rec.data.nombre_periodo+"&nombre_trabajador="+rec.data.nombre_trabajador+"&nombre_asignatura="+rec.data.nombre_asignatura+"&id_asignatura="+rec.data.id_asignatura+"";
                    }
                }]
            }
        ],
        view: new Ext.grid.GroupingView({
                forceFit:true,
                startCollapsed:true,
                groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Materias" : "Item"]})'
        }),
        tbar: [
                'Seleccione el périodo:',Ext.responder_decano.comboevaluaciones,'-',
                {
                      text:'Limpiar',
                      icon: '../../images/eliminar_modelo.png',
                      handler : function(){
                      Ext.responder_decano.comboevaluaciones.reset();}
                },
                '->',
                {
                      xtype:'textfield',
                      fieldLabel: 'Nombre(s)',
                      allowBlank: true,
                      emptyText: 'Escriba el nombre', 
                      name: 'nombres',
                      id:'buscar_usuario',
                      enableKeyEvents:true,
                      listeners:{
                                 'keyup':function(textField, eventoObject){
                                            if(eventoObject.getCharCode() == 13){
                                                 if(Ext.responder_decano.comboevaluaciones.getValue()==''){
                                                    alert('Seleccione una evaluación');
                                                 }else{
                                                    Ext.responder_decano.groupingStore.load({params: {start: 0,limit: 14}});
                                                 }
                                            }
                                 }
                      }      
                },
                 {
                      text:'Buscar',
                      icon: '../../images/lupa.png',
                      handler : function(){
                      if(Ext.responder_decano.comboevaluaciones.getValue()==''){
                                  alert('Seleccione una evaluación');
                        }else{
                           Ext.responder_decano.groupingStore.load({params: {start: 0,limit: 14}});
                       }
                      }
                }

        ],
        bbar: new Ext.PagingToolbar({
            pageSize: 14,
            store: Ext.responder_decano.groupingStore,
            displayInfo: true,
            displayMsg: 'Resultados {0} - {1} de {2}',
            emptyMsg: 'Ning&uacute;n resultado para mostrar.'
        })  
    });

    new Ext.Viewport({
        layout: 'fit',
        items: [
            Ext.responder_decano.grid
        ]
    });    
});