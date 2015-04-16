Ext.onReady(function() {

    Ext.QuickTips.init();
    Ext.ns('Ext.gest_estadisticas');

    Ext.gest_estadisticas.fechainicio = new Ext.form.DateField({
                fieldLabel: 'Buscar por  fecha',
                id: 'fechainicio',
                format: 'Y-m-d',
                name:'fecha_reporte',
                //value: moment().format('YYYY[-]MM[-]DD'),
                allowBlank: false
    });

    Ext.gest_estadisticas.fechafin = new Ext.form.DateField({
                fieldLabel: 'Buscar por  fecha',
                id: 'fechafin',
                format: 'Y-m-d',
                name:'fecha_reporte1',
                //value: moment().format('YYYY[-]MM[-]DD'),
                allowBlank: false
    });
    
    /*Ext.gest_estadisticas.reader = new Ext.data.JsonReader({
      idProperty: 'id',
      root: 'data',
      totalProperty: 'count',
      fields: ['id_evaluacion','id_asignatura','id_periodo','nombre_periodo','id_trabajador','nombre_trabajador', 'nombre_asignatura', 'e_1', 'e_2','e_3','e_4']
    });

    Ext.gest_estadisticas.groupingStore = new Ext.data.GroupingStore({
      url: 'resultados/getevaluados',
      listeners: {'beforeload': function (store, objeto) {
            store.baseParams.query         = Ext.getCmp('buscar_usuario').getRawValue();
            store.baseParams.id_periodo    = Ext.gest_estadisticas.comboevaluaciones.getValue();
      }},
      //autoLoad: true,
      reader: Ext.gest_estadisticas.reader,
      sortInfo: { field: 'nombre_asignatura', direction: "desc" },
      groupField: 'nombre_trabajador'
    });*/

    var xg = Ext.grid;


    // shared reader
    var reader = new Ext.data.ArrayReader({idProperty: 'id'},
        
     [
       {name: 'company'},
       {name: 'price', type: 'float'},
       {name: 'change', type: 'float'},
       {name: 'pctChange', type: 'float'},
       {name: 'lastChange', type: 'float'},
       {name: 'industry'},
       {name: 'thsah', type: 'float'},
       {name: 'id', type: 'float'},
       {name: 'desc'}
    ]);

    var store = new Ext.data.GroupingStore({
            reader: reader,
            data: xg.dummyData,
            sortInfo:{field: 'company', direction: "ASC"},
            groupField:'industry'
        });
  
  /////////////////////////////////////
    //    Combobox autofill periodo    //
    /////////////////////////////////////

    Ext.gest_estadisticas.stcombogrupoparcial = new Ext.data.JsonStore({
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
    
    Ext.gest_estadisticas.comboevaluaciones = new Ext.form.ComboBox({
        store: Ext.gest_estadisticas.stcombogrupoparcial,
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
               Ext.gest_estadisticas.groupingStore.load({params: {start: 0,limit: 14}});               
            }
        }
    }); 


    /*Ext.gest_estadisticas.sm  = new Ext.grid.RowSelectionModel({});
    Ext.gest_estadisticas.grid = new Ext.grid.GridPanel({
        store: store,
        frame:true,
        title: 'Docentes',
        sm: Ext.gest_estadisticas.sm,
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
            {
                xtype: 'actioncolumn',
                width: 8,
                css: 'text-align: center',
                items: [{
                    icon   : '../../images/view.png',
                    tooltip: 'Ver resultado de la evaluacion',
                    handler: function(grid,rowIndex,colIndex) {
                        //var rec = Ext.gest_estadisticas.groupingStore.getAt(rowIndex);
                        //window.location="resultados/resultado"+"?id_trabajador="+rec.data.id_trabajador+"&id_periodo="+rec.data.id_periodo+"&nombre_periodo="+rec.data.nombre_periodo+"&nombre_trabajador="+rec.data.nombre_trabajador+"&nombre_asignatura="+rec.data.nombre_asignatura+"&id_asignatura="+rec.data.id_asignatura+"";
                    }
                }]
            }
        ],
        view: new Ext.grid.GroupingView({
                forceFit:true,
                startCollapsed:true,
                groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Item" : "Item"]})'
        }),
        tbar: [
                'Seleccione el périodo:',Ext.gest_estadisticas.comboevaluaciones,'-',
                'Inicio:',
                Ext.gest_estadisticas.fechainicio,
                'Fin:',
                Ext.gest_estadisticas.fechafin,'-',
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
                                                 if(Ext.gest_estadisticas.comboevaluaciones.getValue()==''){
                                                    alert('Seleccione una evaluación');
                                                 }else{
                                                    Ext.gest_estadisticas.groupingStore.load({params: {start: 0,limit: 14}});
                                                 }
                                            }
                                 }
                      }      
                },
                 {
                      text:'Buscar',
                      icon: '../../images/lupa.png',
                      handler : function(){
                      if(Ext.gest_estadisticas.comboevaluaciones.getValue()==''){
                                  alert('Seleccione una evaluación');
                        }else{
                           Ext.gest_estadisticas.groupingStore.load({params: {start: 0,limit: 14}});
                       }
                      }
                }

        ],
        bbar: new Ext.PagingToolbar({
            pageSize: 14,
            store: Ext.gest_estadisticas.groupingStore,
            displayInfo: true,
            displayMsg: 'Resultados {0} - {1} de {2}',
            emptyMsg: 'Ning&uacute;n resultado para mostrar.'
        })  
    });*/


    var grid = new xg.EditorGridPanel({
        store: store,
        columns: [
            {id:'company',header: "Materias", width: 40,summaryType:'count', sortable: true, dataIndex: 'company'},
            {header: "T/H TRABAJADAS", width: 20,summaryType:'sum', sortable: true,dataIndex: 'price'},
            {header: "T/H REEMPLAZO", width: 20, summaryType:'sum',sortable: true, dataIndex: 'change'},
            {header: "T/H-M ATRASOS", width: 20,summaryType:'sum', sortable: true, dataIndex: 'pctChange'},
            {header: "Docente", width: 20,hidden:true, sortable: true, dataIndex: 'industry'},
            {header: "T/H FALTAS", width: 20,summaryType:'sum', sortable: true, dataIndex: 'lastChange'},
            {header: "T/H-M SALIDAS ANTES DE HORA", width: 20,summaryType:'sum', sortable: true, dataIndex: 'thsah'},
            {
                xtype: 'actioncolumn',
                width: 8,
                css: 'text-align: center',
                items: [{
                    icon   : '../../images/view.png',
                    tooltip: 'Ver estadisticas',
                    handler: function() {
                        Ext.gest_estadisticas.ventana.show();
                        //var rec = Ext.gest_estadisticas.groupingStore.getAt(rowIndex);
                        //window.location="resultados/resultado"+"?id_trabajador="+rec.data.id_trabajador+"&id_periodo="+rec.data.id_periodo+"&nombre_periodo="+rec.data.nombre_periodo+"&nombre_trabajador="+rec.data.nombre_trabajador+"&nombre_asignatura="+rec.data.nombre_asignatura+"&id_asignatura="+rec.data.id_asignatura+"";
                    }
                }]
            }
        ],

        view: new Ext.grid.GroupingView({
            forceFit:true,
            showGroupName: false,
            enableNoGroups: false,
            enableGroupingMenu: false,
            hideGroupedColumn: true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Materias" : "Materia"]})'
        }),

        frame:true,
        width: 700,
        height: 450,
        collapsible: false,
        animCollapse: false,
        //title: 'Grouping Example',
        iconCls: 'icon-grid',
        fbar  : ['->', {
            text:'Clear Grouping',
            iconCls: 'icon-clear-group',
            handler : function(){
                store.clearGrouping();
            }
        }],
        tbar: [
                'Seleccione el périodo:',Ext.gest_estadisticas.comboevaluaciones,'-',
                'Inicio:',
                Ext.gest_estadisticas.fechainicio,
                'Fin:',
                Ext.gest_estadisticas.fechafin,'-',
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
                                                 if(Ext.gest_estadisticas.comboevaluaciones.getValue()==''){
                                                    alert('Seleccione una evaluación');
                                                 }else{
                                                    Ext.gest_estadisticas.groupingStore.load({params: {start: 0,limit: 14}});
                                                 }
                                            }
                                 }
                      }      
                },
                 {
                      text:'Buscar',
                      icon: '../../images/lupa.png',
                      handler : function(){
                      if(Ext.gest_estadisticas.comboevaluaciones.getValue()==''){
                                  alert('Seleccione una evaluación');
                        }else{
                           Ext.gest_estadisticas.groupingStore.load({params: {start: 0,limit: 14}});
                       }
                      }
                }

        ],
        renderTo: document.body
    });

    var store1 = new Ext.data.JsonStore({
        fields:['name', 'visits', 'views'],
        data: [
            {name:'T/H REEMPLAZO', visits: 2, views: 4},
            {name:'T/H-M ATRASOS', visits: 3, views: 5},
            {name:'T/H FALTAS', visits: 2, views: 4},
            {name:'T/H-M SALIDAS ANTES DE HORA', visits: 0, views: 0},
            {name:'T/H-M TARDE', visits: 7, views: 10},
        ]
    });


    Ext.gest_estadisticas.ventana = new Ext.Window({
        title:'Gestión de las preguntas',
        closeAction: 'hide',
        height: 450,
        width: 750,
        layout:'fit',
        constrain: true,
        modal:true,
        items:[{
            xtype: 'columnchart',
            store: store1,
            url:'../../resources/charts.swf',
            xField: 'name',
            yAxis: new Ext.chart.NumericAxis({
                displayName: 'T/H TRABAJADAS',
                //labelRenderer : Ext.util.Format.numberRenderer('0')
            }),
            tipRenderer : function(chart, record, index, series){
                if(series.yField == 'visits'){
                    return Ext.util.Format.number(record.data.visits, '0,0') + ' ' + record.data.name;
                }else{
                    return Ext.util.Format.number(record.data.views, '0,0') + ' ' + record.data.name;
                }
            },
            chartStyle: {
                padding: 10,
                animationEnabled: true,
                font: {
                    name: 'Tahoma',
                    color: 0x444444,
                    size: 11
                },
                dataTip: {
                    padding: 5,
                    border: {
                        color: 0x99bbe8,
                        size:1
                    },
                    background: {
                        color: 0xDAE7F6,
                        alpha: .9
                    },
                    font: {
                        name: 'Tahoma',
                        color: 0x15428B,
                        size: 10,
                        bold: true
                    }
                },
                xAxis: {
                    color: 0x69aBc8,
                    majorTicks: {color: 0x69aBc8, length: 4},
                    minorTicks: {color: 0x69aBc8, length: 2},
                    majorGridLines: {size: 1, color: 0xeeeeee}
                },
                yAxis: {
                    color: 0x69aBc8,
                    majorTicks: {color: 0x69aBc8, length: 4},
                    minorTicks: {color: 0x69aBc8, length: 2},
                    majorGridLines: {size: 1, color: 0xdfe8f6}
                }
            },
            series: [{
                type: 'column',
                displayName: 'Page Views',
                yField: 'views',
                style: {
                    image:'bar.gif',
                    mode: 'stretch',
                    color:0x99BBE8
                }
            },{
                type:'line',
                displayName: 'Visits',
                yField: 'visits',
                style: {
                    color: 0x15428B
                }
            }]
        }]
    });

    new Ext.Viewport({
        layout: 'fit',
        items: [
            grid
        ]
    });    
});


// Array data for the grids
Ext.grid.dummyData = [
    /*['3m Co',71.72,0.02,0.03,'4/2 12:00am', 'Manufacturing'],
    ['Alcoa Inc',29.01,0.42,1.47,'4/1 12:00am', 'Manufacturing'],
    ['Altria Group Inc',83.81,0.28,0.34,'4/3 12:00am', 'Manufacturing'],
    ['American Express Company',52.55,0.01,0.02,'4/8 12:00am', 'Finance'],
    ['American International Group, Inc.',64.13,0.31,0.49,'4/1 12:00am', 'Services'],
    ['AT&T Inc.',31.61,-0.48,-1.54,'4/8 12:00am', 'Services'],
    ['Boeing Co.',75.43,0.53,0.71,'4/8 12:00am', 'Manufacturing'],
    ['Caterpillar Inc.',67.27,0.92,1.39,'4/1 12:00am', 'Services'],
    ['Citigroup, Inc.',49.37,0.02,0.04,'4/4 12:00am', 'Finance'],
    ['E.I. du Pont de Nemours and Company',40.48,0.51,1.28,'4/1 12:00am', 'Manufacturing'],
    ['Exxon Mobil Corp',68.1,-0.43,-0.64,'4/3 12:00am', 'Manufacturing'],*/
    ['Matematicas I',40,4,0.10,4, 'Salvador Gonzalez Gomez',0,1],
    ['Programación I',40,2,0.05,2, 'Salvador Gonzalez Gomez',0,2],
    /*['Matematicas 1',30.27,1.09,3.74,'4/3 12:00am', 'Automotive'],
    ['Hewlett-Packard Co.',36.53,-0.03,-0.08,'4/3 12:00am', 'Computer'],
    ['Honeywell Intl Inc',38.77,0.05,0.13,'4/3 12:00am', 'Manufacturing'],
    ['Intel Corporation',19.88,0.31,1.58,'4/2 12:00am', 'Computer'],
    ['International Business Machines',81.41,0.44,0.54,'4/1 12:00am', 'Computer'],
    ['Johnson & Johnson',64.72,0.06,0.09,'4/2 12:00am', 'Medical'],
    ['JP Morgan & Chase & Co',45.73,0.07,0.15,'4/2 12:00am', 'Finance'],
    ['McDonald\'s Corporation',36.76,0.86,2.40,'4/2 12:00am', 'Food'],
    ['Merck & Co., Inc.',40.96,0.41,1.01,'4/2 12:00am', 'Medical'],
    ['Microsoft Corporation',25.84,0.14,0.54,'4/2 12:00am', 'Computer'],
    ['Pfizer Inc',27.96,0.4,1.45,'4/8 12:00am', 'Services', 'Medical'],
    ['The Coca-Cola Company',45.07,0.26,0.58,'4/1 12:00am', 'Food'],
    ['The Home Depot, Inc.',34.64,0.35,1.02,'4/8 12:00am', 'Retail'],
    ['The Procter & Gamble Company',61.91,0.01,0.02,'4/1 12:00am', 'Manufacturing'],
    ['United Technologies Corporation',63.26,0.55,0.88,'4/1 12:00am', 'Computer'],
    ['Verizon Communications',35.57,0.39,1.11,'4/3 12:00am', 'Services'],
    ['Wal-Mart Stores, Inc.',45.45,0.73,1.63,'4/3 12:00am', 'Retail'],
    ['Walt Disney Company (The) (Holding Company)',29.89,0.24,0.81,'4/1 12:00am', 'Services']*/
];

// add in some dummy descriptions
for(var i = 0; i < Ext.grid.dummyData.length; i++){
    Ext.grid.dummyData[i].push('Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Sed metus nibh, sodales a, porta at, vulputate eget, dui. Pellentesque ut nisl. Maecenas tortor turpis, interdum non, sodales non, iaculis ac, lacus. Vestibulum auctor, tortor quis iaculis malesuada, libero lectus bibendum purus, sit amet tincidunt quam turpis vel lacus. In pellentesque nisl non sem. Suspendisse nunc sem, pretium eget, cursus a, fringilla vel, urna.<br/><br/>Aliquam commodo ullamcorper erat. Nullam vel justo in neque porttitor laoreet. Aenean lacus dui, consequat eu, adipiscing eget, nonummy non, nisi. Morbi nunc est, dignissim non, ornare sed, luctus eu, massa. Vivamus eget quam. Vivamus tincidunt diam nec urna. Curabitur velit.');
}