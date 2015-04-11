Ext.onReady(function() {

    Ext.QuickTips.init();
    Ext.ns('Ext.responder_decano');
    
    Ext.responder_decano.reader = new Ext.data.JsonReader({
      idProperty: 'id',
      root: 'data',
      totalProperty: 'count',
      fields: ['nombre_completo', 'materia', 'e_estudiante', 'a_evaluacion','e_decano',
                { name: 'e_estudiante', type: 'int' },
                { name: 'a_evaluacion', type: 'int' },
                { name: 'e_decano', type: 'int' }]
    });

    Ext.responder_decano.groupingStore = new Ext.data.GroupingStore({
      url: 'responder/getdocentesmateriasevaluacion',
      listeners: {'beforeload': function (store, objeto) {
            store.baseParams.query         = Ext.getCmp('buscar_usuario').getRawValue();
            store.baseParams.id_evaluacion = Ext.responder_decano.comboevaluaciones.getValue();
      }},
      //autoLoad: true,
      reader: Ext.responder_decano.reader,
      sortInfo: { field: 'materia', direction: "desc" },
      groupField: 'nombre_completo'
    });

    /////////////////////////////////////
    //Combobox autofill Evaluaciones   //
    /////////////////////////////////////

    Ext.responder_decano.stcomboevaluaciones = new Ext.data.Store({
        url: 'evaluaciones/cargar',
        autoLoad: true,
        reader: new Ext.data.JsonReader({
            root: "data",
            id: "id"
        }, [
            {name: 'id'},
            {name: 'titulo'}
        ])
    });
    
    Ext.responder_decano.comboevaluaciones = new Ext.form.ComboBox({
        //id: 'mc_gest_usuarios_roles',
        hiddenName: 'id',
        valueField: 'id',
        displayField: 'titulo',
        store: Ext.responder_decano.stcomboevaluaciones,
        autoCreate: true,
        typeAhead: true,
        triggerAction: 'all',
        readOnly: false,
        mode: 'local',
        width: 250,
        anchor: '85%',
        listWidth: 350,
        align: 'rigth',
        fieldLabel: 'Evaluaciones',
        listeners: {
            'select': function(combo,record,index){
               Ext.responder_decano.groupingStore.load({params: {start: 0,limit: 14}});               
            }
        }

    });    

    //fin combo


     /**
     * Custom function used for column renderer
     * @param {Object} val
     */
    function change_eva_decano(val) {
        if (val > 0) {
            return '<img src="../../images/Check_16x16.png" />';
        } else if (val == 0) {
            return '<img src="../../images/Remove_16x16.png" />';
        }
        return val;
    }

    function change(val){
        if(val > 0){
            return '<span style="color:green;">' + val + '</span>';
        }else if(val == 0){
            return '<span style="color:red;">' + val + '</span>';
        }
        return val;
    }

    Ext.responder_decano.sm  = new Ext.grid.RowSelectionModel({});
    Ext.responder_decano.grid = new Ext.grid.GridPanel({
        store: Ext.responder_decano.groupingStore,
        frame:true,
        title: 'Docentes',
        iconCls: 'icon-grid',
        sm: Ext.responder_decano.sm,
        loadMask: true,
        renderTo: Ext.getBody(),
        columns: [
            {hidden: true, hideable: false, dataIndex: 'id_docente'},
            {hidden: true, hideable: false, dataIndex: 'id_materia'},
            {id:'nombre_completo',header: "Nombre", width: 60,hidden: true, sortable: true, dataIndex: 'nombre_completo'},
            {header: "Materias", width: 60,sortable: true, dataIndex: 'materia'},
            {header: "Evaluación estudiante", width: 14, sortable: true, dataIndex: 'e_estudiante', renderer: change,css: 'text-align: center;'},
            {header: "Autoevaluación", width: 10, sortable: true, dataIndex: 'a_evaluacion',renderer : change_eva_decano,css: 'text-align: center;'},
            {header: "Evaluación decano", width: 12, sortable: true, dataIndex: 'e_decano',renderer : change_eva_decano,css: 'text-align: center;'},
            {
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
                },{
                    icon   : '../../images/view.png',  // Use a URL in the icon config
                    tooltip: 'Ver resultado de la evaluacion',
                    handler: function(grid, rowIndex, colIndex) {
                        //var rec = store.getAt(rowIndex);
                        alert("Resultados de la evaluacion");
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
                'Seleccione la evaluación:',Ext.responder_decano.comboevaluaciones,'-',
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


