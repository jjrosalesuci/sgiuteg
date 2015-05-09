Ext.onReady(function() {

    Ext.QuickTips.init();
    Ext.ns('Ext.gest_estadisticas');

    Ext.gest_estadisticas.fechainicio = new Ext.form.DateField({
                fieldLabel: 'Buscar por  fecha',
                id: 'fechainicio',
                format: 'Y-m-d',
                //name:'fecha_reporte',
                disabled: 'true',
                listeners: {'select': function(){
                        if(Ext.getCmp('fechafin').getValue()!='')
                        {
                           Ext.gest_estadisticas.groupingStore.load({params:{fecha_rango_1:this.getValue(),fecha_rango_2:Ext.getCmp('fechafin').getValue()}}); 
                        }
                }},
                //value: moment().format('YYYY[-]MM[-]DD'),
                allowBlank: true
    });

    Ext.gest_estadisticas.fechafin = new Ext.form.DateField({
                fieldLabel: 'Buscar por  fecha',
                id: 'fechafin',
                format: 'Y-m-d',
                disabled: 'true',
                //name:'fecha_reporte1',
                listeners: {'select': function(){
                        if(Ext.getCmp('fechainicio').getValue()!='')
                        {
                           Ext.gest_estadisticas.groupingStore.load({params:{fecha_rango_1:Ext.getCmp('fechainicio').getValue(),fecha_rango_2:this.getValue(),}}); 
                        }
                        else{
                            Ext.MessageBox.alert('Error..', 'Campo vacio!!');
                        }
                }},
                //value: moment().format('YYYY[-]MM[-]DD'),
                allowBlank: true
    });
    
    Ext.gest_estadisticas.reader = new Ext.data.JsonReader({
      idProperty: 'id',
      root: 'data',
      totalProperty: 'count',
      fields: ['id_docente','nombre_docente', 'total_min_atrasos', 'total_min_salidas_ah', 'total_horas_trabajadas','total_horas_faltas','total_horas_reemplazo']
                /*{ name: 'total_min_atrasos', type: 'date' },
                { name: 'total_min_salidas_ah', type: 'date' },
                { name: 'total_horas_trabajadas', type: 'date' },
                { name: 'total_horas_faltas', type: 'date' },
                { name: 'total_horas_reemplazo', type: 'date' }] */
    });

    Ext.gest_estadisticas.groupingStore = new Ext.data.GroupingStore({
      url: 'estadisticas/cargar',
      listeners: {'beforeload': function (store, objeto) {
            store.baseParams.query            = Ext.getCmp('buscar_usuario').getRawValue();
            store.baseParams.id_periodo       = Ext.gest_estadisticas.comboperiodolectivo.getValue();
            store.baseParams.fecha_rango_1    = Ext.gest_estadisticas.fechainicio.getValue();
            store.baseParams.fecha_rango_2    = Ext.gest_estadisticas.fechafin.getValue();
      }},
      //autoLoad: true,
      reader: Ext.gest_estadisticas.reader,
      //sortInfo: { field: 'total_min_atrasos', direction: "desc" },
      groupField: 'nombre_docente'
    });

  
  /////////////////////////////////////
    //    Combobox autofill periodo    //
    /////////////////////////////////////

    Ext.gest_estadisticas.stcomboperiodolectivo = new Ext.data.JsonStore({
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
    
    Ext.gest_estadisticas.comboperiodolectivo = new Ext.form.ComboBox({
        store: Ext.gest_estadisticas.stcomboperiodolectivo,
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
        triggerAction: 'all',
        hideTrigger:false,
        minChars:1,
        pageSize : 20,
        totalProperty : 'count',
        forceSelection: true,
        mode: 'remote',   
        listeners: {
            'select': function(combo,record,index){
               Ext.gest_estadisticas.groupingStore.load(/*{params: {start: 0,limit: 7}}*/);
               Ext.gest_estadisticas.estbtn.setDisabled(false);               
            }
        }
    });

    Ext.gest_estadisticas.estbtn = new Ext.Button({
        text: 'Total General',
        disabled:true,
        listeners: {'click':function(){
                if(Ext.gest_estadisticas.comboperiodolectivo.getValue()!=''){
                    Ext.gest_estadisticas.store1.load({params:{id_docente:'General',graficar: 'universidad',id_periodo: Ext.gest_estadisticas.comboperiodolectivo.getValue()}});
                    Ext.gest_estadisticas.ventana2.show();    
                }else if(Ext.getCmp('por_fecha').checked == true){
                    Ext.gest_estadisticas.store1.load({params:{id_docente:'General',graficar: 'uni_por_fecha',fecha_rango_1:Ext.getCmp('fechainicio').getValue(),fecha_rango_2:Ext.getCmp('fechafin').getValue()}});
                    Ext.gest_estadisticas.ventana2.show();
                }else{
                    Ext.MessageBox.alert('Error..', 'Campo vacio!!');
                }
            }
        },
        //handler: Ext.gest_aulas.myBtnHandler,
        icon: '../../images/chart.png'
    });

    Ext.gest_estadisticas.sm  = new Ext.grid.RowSelectionModel({});
    Ext.gest_estadisticas.grid = new Ext.grid.GridPanel({
        store: Ext.gest_estadisticas.groupingStore,
        sm: Ext.gest_estadisticas.sm,
        columns: [
            {hidden:true,header: "Id", width: 40, dataIndex: 'id_docente'},
            {id:'nombre_docente',hidden:true,header: "Docente", width: 40, sortable: true, dataIndex: 'nombre_docente'},
            {header: "T/H TRABAJADAS", width: 20, sortable: true,dataIndex: 'total_horas_trabajadas'},
            {header: "T/H-M ATRASOS", width: 20, sortable: true, dataIndex: 'total_min_atrasos'},
            {header: "T/H-M SALIDAS ANTES DE HORA", width: 20, sortable: true, dataIndex: 'total_min_salidas_ah'},
            {header: "T/H FALTAS", width: 20, sortable: true, dataIndex: 'total_horas_faltas'},
            {header: "T/H REEMPLAZO", width: 20,sortable: true, dataIndex: 'total_horas_reemplazo'},
            {
                xtype: 'actioncolumn',
                width: 8,
                css: 'text-align: center',
                items: [{
                    icon   : '../../images/chart.png',
                    tooltip: 'Ver estadisticas',
                    handler: function(grid, rowIndex, colIndex) {
                        var rec = Ext.gest_estadisticas.groupingStore.getAt(rowIndex);
                        if(Ext.gest_estadisticas.comboperiodolectivo.getValue()!=''){
                            Ext.gest_estadisticas.store1.load({params:{id_docente: rec.data.id_docente, graficar: 'yes',id_periodo: Ext.gest_estadisticas.comboperiodolectivo.getValue()}});
                            Ext.gest_estadisticas.ventana.show();    
                        }else if(Ext.getCmp('por_fecha').checked == true){
                            Ext.gest_estadisticas.store1.load({params:{id_docente: rec.data.id_docente, graficar: 'por_fecha',fecha_rango_1:Ext.getCmp('fechainicio').getValue(),fecha_rango_2:Ext.getCmp('fechafin').getValue()}});
                            Ext.gest_estadisticas.ventana.show();
                        }
                    }
                }]
            }
        ],

        view: new Ext.grid.GroupingView({
            forceFit:true,
            showGroupName: false,
            /*enableNoGroups: false,
            enableGroupingMenu: false,
            hideGroupedColumn: true,*/
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Item" : "Items"]})'
        }),

        frame:true,
        width: 700,
        height: 450,
        collapsible: false,
        animCollapse: false,
        iconCls: 'icon-grid',
        tbar: [
                'Seleccione el périodo:',Ext.gest_estadisticas.comboperiodolectivo,'-',
                'Por fecha:',
                {
                    xtype: 'checkbox',
                    id: 'por_fecha',
                    listeners: {'check':function(){
                        if (this.checked == true) {
                            Ext.gest_estadisticas.comboperiodolectivo.setValue('');
                            Ext.gest_estadisticas.comboperiodolectivo.setDisabled(true);
                            Ext.gest_estadisticas.fechainicio.setDisabled(false);
                            Ext.gest_estadisticas.fechafin.setDisabled(false);
                            Ext.gest_estadisticas.groupingStore.removeAll();
                            Ext.gest_estadisticas.estbtn.setDisabled(false);
                        }else{
                            Ext.gest_estadisticas.fechainicio.setValue('');
                            Ext.gest_estadisticas.fechafin.setValue('');
                            Ext.gest_estadisticas.comboperiodolectivo.setDisabled(false);
                            Ext.gest_estadisticas.fechainicio.setDisabled(true);
                            Ext.gest_estadisticas.fechafin.setDisabled(true);
                            Ext.gest_estadisticas.groupingStore.removeAll();
                            Ext.gest_estadisticas.estbtn.setDisabled(true);
                        };
                    }

                    }
                },
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
                                                 if(Ext.gest_estadisticas.comboperiodolectivo.getValue()==''&&Ext.getCmp('por_fecha').checked == false){
                                                    alert('Seleccione un periodo o rango de fecha');
                                                 }else if(Ext.gest_estadisticas.comboperiodolectivo.getValue()!=''&&Ext.getCmp('por_fecha').checked == false){
                                                    Ext.gest_estadisticas.groupingStore.load({params: {start: 0,limit: 7}});
                                                 }
                                                 else if(Ext.gest_estadisticas.comboperiodolectivo.getValue()==''&&Ext.getCmp('por_fecha').checked == true&&(Ext.gest_estadisticas.fechainicio.getValue()==''||Ext.gest_estadisticas.fechafin.getValue()=='')){
                                                    alert('Rellene los campos vacios!!');
                                                 }else{
                                                    Ext.gest_estadisticas.groupingStore.load({params:{fecha_rango_1:Ext.getCmp('fechainicio').getValue(),fecha_rango_2:Ext.getCmp('fechafin').getValue()}});
                                                 }
                                            }
                                 }
                      }     
                },
                 {
                      text:'Buscar',
                      icon: '../../images/lupa.png',
                      handler : function(){
                        if(Ext.gest_estadisticas.comboperiodolectivo.getValue()==''&&Ext.getCmp('por_fecha').checked == false){
                            alert('Seleccione un periodo o rango de fecha');
                        }else if(Ext.gest_estadisticas.comboperiodolectivo.getValue()!=''&&Ext.getCmp('por_fecha').checked == false){
                            Ext.gest_estadisticas.groupingStore.load({params: {start: 0,limit: 7}});
                        }
                        else if(Ext.gest_estadisticas.comboperiodolectivo.getValue()==''&&Ext.getCmp('por_fecha').checked == true&&(Ext.gest_estadisticas.fechainicio.getValue()==''||Ext.gest_estadisticas.fechafin.getValue()=='')){
                            alert('Rellene los campos vacios!!');
                        }else{
                            Ext.gest_estadisticas.groupingStore.load({params:{fecha_rango_1:Ext.getCmp('fechainicio').getValue(),fecha_rango_2:Ext.getCmp('fechafin').getValue()}});
                        }
                      }
                }

        ],
        renderTo: Ext.getBody(),
        bbar: new Ext.PagingToolbar({
            pageSize: 7,
            store: Ext.gest_estadisticas.groupingStore,
            displayInfo: true,
            displayMsg: 'Resultados {0} - {1} de {2}',
            emptyMsg: 'Ning&uacute;n resultado para mostrar.'
        }),
        fbar: [Ext.gest_estadisticas.estbtn]
    });

    Ext.gest_estadisticas.store1 = new Ext.data.Store({
        url: 'estadisticas/cargar',
        reader: new Ext.data.JsonReader({
            root: "data",
        }, [
            {name: 'nombre_docente'},
            {name: 'name'},
            {name: 'total'},
            {name: 'total_general'},
        ]),
    });


    Ext.gest_estadisticas.ventana = new Ext.Window({
        title:'Estadisticas',
        closeAction: 'hide',
        height: 450,
        width: 750,
        layout:'fit',
        constrain: true,
        modal:true,
        items:[{
            xtype: 'columnchart',
            store: Ext.gest_estadisticas.store1,
            url:'../../resources/charts.swf',
            xField: 'name',
            /*xAxis: new Ext.chart.CategoryAxis({
                title: 'nombre_docente'
            }),
            /*yAxis: new Ext.chart.NumericAxis({
                displayName: 'Prueba',
                //labelRenderer : Ext.util.Format.numberRenderer('0,0')
                //labelRenderer: function(date) { return date.format("d.H");}
            }),
            /*tipRenderer : function(chart, record, index, series){
                if(series.yField == 'visits'){
                    return Ext.util.Format.number(record.data.visits, '0,0') + ' ' + record.data.name;
                }else{
                    return Ext.util.Format.number(record.data.views, '0,0') + ' ' + record.data.name;
                }
            },*/
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
                displayName: 'Comportamiento Universidad',
                yField: ['total_general'],
                style: {
                    image:'../../bar.gif',
                    mode: 'stretch',
                    color:0x99BBE8
                }
            },{
                type:'line',
                displayName: 'Comportamiento Docente',
                yField: 'total',
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
        }]
    });
    Ext.gest_estadisticas.ventana2 = new Ext.Window({
        title:'Estadisticas',
        closeAction: 'hide',
        height: 450,
        width: 750,
        layout:'fit',
        constrain: true,
        modal:true,
        items:[{
            xtype: 'columnchart',
            store: Ext.gest_estadisticas.store1,
            url:'../../resources/charts.swf',
            xField: 'name',
            /*yAxis: new Ext.chart.NumericAxis({
                displayName: 'Prueba',
                //labelRenderer : Ext.util.Format.numberRenderer('0,0')
                //labelRenderer: function(date) { return date.format("d.H");}
            }),
            /*tipRenderer : function(chart, record, index, series){
                if(series.yField == 'visits'){
                    return Ext.util.Format.number(record.data.visits, '0,0') + ' ' + record.data.name;
                }else{
                    return Ext.util.Format.number(record.data.views, '0,0') + ' ' + record.data.name;
                }
            },*/
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
                displayName: 'Comportamiento Universidad',
                yField: ['total_general'],
                style: {
                    image:'../../bar.gif',
                    mode: 'stretch',
                    color:0x99BBE8
                }
            }
            ],
            extraStyle:{            //Step 1
                                legend:{        //Step 2
                                    display: 'left'//Step 3
                                }
                            }
        }]
    });

    new Ext.Viewport({
        layout: 'fit',
        items: [
            Ext.gest_estadisticas.grid
        ]
    });    
});