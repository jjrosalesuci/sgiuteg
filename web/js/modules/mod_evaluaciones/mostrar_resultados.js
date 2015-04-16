/*
* Juan José Rosales Rodríguez
*/
Ext.onReady(function () {

        Ext.QuickTips.init();
        Ext.form.Field.prototype.msgTarget = 'side';
        Ext.ns('Ext.m_encuestas_r');

        Ext.m_encuestas_r.id_asignatura = id_asignatura;

        /*
        * Store del grid de evaluaciones de un smestre de un docente y una asignatura
        */

        Ext.m_encuestas_r.stEvaSemeDocenteAsignatura = new Ext.data.Store({
            url: '../resultados/getbvaluacionespdasig',
            autoLoad: true,
            listeners: {
                'beforeload': function (store, objeto) {
                  store.baseParams.id_trabajador         = id_trabajador;
                  store.baseParams.id_periodo            = id_periodo;
                  store.baseParams.id_asignatura         = id_asignatura;
                },
                'load': function (store,records,options) {
                    var total = store.getTotalCount(); 
                    var suma = store.sum('nota_promedio');
                    if(total>0){
                        var nota_promedio = suma / total;
                    }else{
                        var nota_promedio =  0 ;
                    }
                    Ext.getCmp('mostrar_resultado_nota_final').setValue(nota_promedio);
                }                 
            },
            reader: new Ext.data.JsonReader({
                root: "data",
                totalProperty: "count",
                id: "id"
            }, [
                {name: 'id_evaluacion'},               
                {name: 'fecha'},
                {name: 'titulo'},
                {name: 'nota'} ,
                {name: 'nota_promedio'}                              
            ])
        });

        Ext.m_encuestas_r.sm             = new Ext.grid.RowSelectionModel({});
        Ext.m_encuestas_r.gpEvaluaciones = new Ext.grid.GridPanel({
                frame: false,
                iconCls: 'icon-grid',
                autoExpandColumn: 'expandir',
                loadMask: 'Cargando...',
                width: 450,
                store: Ext.m_encuestas_r.stEvaSemeDocenteAsignatura,
                clicksToEdit: 1,
                sm: Ext.m_encuestas_r.sm,
                stripeRows: true,
                layout:'fit',
                floating: false,
                columns: [
                    new Ext.grid.RowNumberer(),
                    {hidden: true, hideable: false, dataIndex: 'id_evaluacion'},           
                    {header: 'Titulo', width: 100, dataIndex: 'titulo', id: 'expandir'}, 
                    {header: 'Nota', width: 110, dataIndex: 'nota'}
                ]
        });


        Ext.m_encuestas_r.sm.on("rowselect", function(){
            if(Ext.m_encuestas_r.sm.getSelected())
            {
              Ext.m_encuestas_r.stnotas_preguntas.load({params: {start: 0,limit: 100}});     
              //Ext.m_encuestas_r.store.load({params: {start: 0,limit: 100}});
            }
        });

        /*Fin de la lista de las evaluaciones*/

        /*Notas por preguntas de una evaluacion*/
        /*
        * Store del grid de evaluaciones de un smestre de un docente
        */

        Ext.m_encuestas_r.stnotas_preguntas = new Ext.data.Store({
            url: '../resultados/cantidadesrespuestas',
            autoLoad: false,
            listeners: {'beforeload': function (store, objeto) {
                 store.baseParams.id_trabajador         = id_trabajador;
                 store.baseParams.id_evaluacion         = Ext.m_encuestas_r.sm.getSelected().get("id_evaluacion");
                 store.baseParams.id_asignatura         = id_asignatura;
             }},
            reader: new Ext.data.JsonReader({
                root: "data",
                totalProperty: "count",
                id: "id"
            }, [
                {name: 'id_pregunta'},
                {name: 'texto'},
                {name: 'Excelente'},
                {name: 'Muy bien'}, 
                {name: 'Bien'}, 
                {name: 'Regular'}, 
                {name: 'Deficiente'},
                {name: 'promedio_nota'},
                {name: 'nota'}
            ])
        });

         Ext.m_encuestas_r.sm_notas                 = new Ext.grid.RowSelectionModel({});
         Ext.m_encuestas_r.respuestanotas_preguntas = new Ext.grid.GridPanel({
                frame: false,
                title:'Cantidad de respuestas por pregunta',
                autoExpandColumn: 'expandir',
                loadMask: 'Cargando...',
                width: 450,
                store: Ext.m_encuestas_r.stnotas_preguntas,
                clicksToEdit: 1,
                sm: Ext.m_encuestas_r.sm_notas,
                stripeRows: true,
                layout:'fit',
                floating: false,
                columns: [
                  /*  new Ext.grid.RowNumberer(),*/
                    {header: 'No', width: 25, dataIndex: 'id_pregunta'},           
                    {header: 'Pregunta', width: 100, dataIndex: 'texto', id: 'expandir'}, 
                    {header: 'Excelente', width: 60, dataIndex: 'Excelente'},
                    {header: 'Muy bien', width: 60, dataIndex: 'Muy bien'},
                    {header: 'Bien', width: 60, dataIndex: 'Bien'},
                    {header: 'Regular', width: 60, dataIndex: 'Regular'},
                    {header: 'Deficiente', width: 60, dataIndex: 'Deficiente'},
                    {header: 'Nota', width: 40, dataIndex: 'nota'}
                ]
            });

        /*Fin de la lista de las evaluaciones*/
        /*Fin de notas por preguntas de una evaluacion*/

        /*GRAFICO*/

                     //store respuestas
             /*   Ext.m_encuestas_r.store = new Ext.data.Store({
                    url: '../resultados/cargarrespuestas',
                    listeners: {'beforeload': function (store, objeto) {
                         store.baseParams.id_trabajador         = id_trabajador;
                         store.baseParams.id_evaluacion         = Ext.m_encuestas_r.sm.getSelected().get("id_evaluacion");
                         store.baseParams.id_asignatura         = id_asignatura;
                    }},
                    autoLoad: false,
                    reader: new Ext.data.JsonReader({
                        root: "data",
                        id: "id",
                        fields:['indice', 'cantidad'],
                    })
                });
*/
                    //panel estadisticas
                   Ext.m_encuestas_r.panel = new Ext.Panel({
                        width:'100%',
                        height:195,
                        items: {
                            xtype: 'linechart',
                            store: Ext.m_encuestas_r.stnotas_preguntas,
                            url: '../../../resources/charts.swf',
                            xField: 'id_pregunta',
                            xLabel: 'Preguntas',
                         
                            /*yAxis: new Ext.chart.NumericAxis({
                                displayName: 'Cantidad',
                                labelRenderer : Ext.util.Format.numberRenderer('0,0')
                            }),*/
                            /*  tipRenderer : function(chart, record){
                                return Ext.util.Format.number(record.data.nota, '0,0') + ' respuesta(s) (Excelente,Muy bien,Bien) en la pregunta: ' + record.data.indice;
                            },*/
                            series: [{
                                type:'line',
                                yField: 'nota',
                                displayName: 'Nota evaluado',
                                //yField: 'respuesta',
                                style: {
                                    color: 0x69aBc8,
                                }
                              },
                             {
                               type:'line',
                               displayName: 'Nota promedio',
                               yField: 'promedio_nota',
                               style: {
                                     color: 0x15428B
                               }
                              }
                            ],
                            extraStyle:{            //Step 1
                                legend:{        //Step 2
                                    display: 'left'//Step 3
                                }
                            }
                        }
                    });

                   //panel estadisticas
                   Ext.m_encuestas_r.panel2 = new Ext.Panel({
                        width:'100%',
                        height:195,
                        items: {
                            xtype: 'piechart',
                            store: Ext.m_encuestas_r.store,
                            url: '../../../resources/charts.swf',
                            dataField: 'cantidad',
                            categoryField: 'id_pregunta',
                            //extra styles get applied to the chart defaults
                            extraStyle:
                            {
                                legend:
                                {
                                    display: 'bottom',
                                    padding: 5,
                                    font:
                                    {
                                        family: 'Tahoma',
                                        size: 13
                                    }
                                }
                            }
                        }
                  });

        /*FIN GRAFICO*/
            
        var viewport = new Ext.Viewport({
            layout: 'border',
            items: [
            { 
                region: 'north',
                height: 60, 
                split: true,
                height: 60, 
                frame:true,
                title: 'Detalles del docente',
                margins: '5 5 0 5',               
                items: [{
                    layout:'column',
                    items:[{
                        columnWidth:.2,
                        layout: 'form',                       
                        items: [{
                            margins: '5 5 0 5',
                            xtype:'textfield',
                            fieldLabel: '<b>Semestre</b>',
                            name: 'first',
                            value: nombre_periodo,
                            anchor:'95%'
                        }]
                    },{
                        columnWidth:.3,
                        layout: 'form',                        
                        items: [{
                            xtype:'textfield',
                            margins: '5 5 0 5',
                            fieldLabel: '<b>Nombre</b>',
                            name: 'last',
                            anchor:'95%',
                            value: nombre_trabajador
                        }]
                    },
                    {
                        columnWidth:.2,
                        layout: 'form',                        
                        items: [{
                            xtype:'textfield',
                            margins: '5 5 0 5',
                            fieldLabel: '<b>Asignatura</b>',
                            name: 'last',
                            anchor:'95%',
                            value: nombre_asignatura
                        },
                        ]
                    },
                    {
                        columnWidth:.2,
                        layout: 'form',                        
                        items: [{
                            xtype:'textfield',
                            margins: '5 5 0 5',
                            id:'mostrar_resultado_nota_final',
                            fieldLabel: '<b>Nota final</b>',
                            name: 'last',
                            anchor:'95%',
                            value: '..Cargando..'
                        }]
                    }                                      
                  ]
                }]           
            },
            {              
                region: 'south',             
                split: true,
                height: 220,
                minSize: 200,
                maxSize: 400,
                frame:false,
                collapsible: true,
                layout:'column',
                title: 'Gráfica de cantidad respuesas por pregunta',
                margins: '0 5 5 5',
                items:[{
                    columnWidth:.50,
                    baseCls:'x-plain',
                    bodyStyle:'padding:5px 0 5px 5px',
                    items:[                      
                        Ext.m_encuestas_r.panel
                    ]
                },{
                    columnWidth:.50, 
                    baseCls:'x-plain',
                    bodyStyle:'padding:5px 0 5px 5px',
                    frame:true,
                    items:[ 
                      {
                        columnWidth:.1,
                        layout: 'form',
                        title:'Acciones comunesu',
                        frame:true,                    
                        buttons: [
                         new Ext.Button({
                             icon: '../../../images/save.png',
                             text:'Descargar resumen',
                             handler:function(){
                                var oIFrm = document.getElementById('myIFrm');
                                oIFrm.src = "../resultados/exportarpdfdocente";
                             }
                         }),
                         new Ext.Button({
                             icon: '../../../images/save.png',
                             text:'Volver al listado',
                             handler:function(){
                              window.history.back();
                             }
                         })
                        ]
                      }                   
                       //
                    ]
                }]

            }, {
                region: 'west',
                id: 'west-panel', 
                title: 'Evaluaciones',
                split: true,
                width: 300,
                minSize: 175,
                frame:false,
                maxSize: 400,
                collapsible: true,
                 layout:'fit',
                margins: '0 0 0 5',              
                items: [                 
                    
                     Ext.m_encuestas_r.gpEvaluaciones

                ]
            },       
            new Ext.TabPanel({
                region: 'center', 
                deferredRender: false,
                activeTab: 0, 
                margins: '0 5 0 0',   

                items: [
                    Ext.m_encuestas_r.respuestanotas_preguntas
                ]
            })]
        });
      
    });
   
