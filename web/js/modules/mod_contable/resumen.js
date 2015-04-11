Ext.onReady(function () {

    Ext.QuickTips.init();
    Ext.form.Field.prototype.msgTarget = 'side';
    Ext.ns('Ext.mc_resumen');

    Ext.BLANK_IMAGE_URL = '../../../images/s.gif';

    Ext.mc_resumen.bool = false;

    Ext.mc_resumen.sthoras = new Ext.data.Store({
        url: 'loadhoras',
        autoLoad: true,
        listeners: {'beforeload': function (store, objeto) {
            store.baseParams.fecha = Ext.getCmp('m_contable_dp_fecha').getRawValue();
        }},
        reader: new Ext.data.JsonReader({
            root: "data"
        }, [
            {name: 'hora'},
            {name: 'hora_v'}
        ])
    });

    Ext.mc_resumen.combo_horas = new Ext.form.ComboBox({
        id: 'mc_resumen_combo_horas',
        hiddenName: 'deposito',
        valueField: 'hora_v',
        displayField: 'hora_v',
        store: Ext.mc_resumen.sthoras,
        autoCreate: true,
        typeAhead: true,
        triggerAction: 'all',
        readOnly: false,
        mode: 'local',
        width: 250,
        anchor: '85%',
        listWidth: 250,
        align: 'rigth'
    });


    Ext.mc_resumen.stdata = new Ext.data.GroupingStore({
        url: 'loadresumen',
        autoLoad: true,
        reader: new Ext.data.JsonReader({
            root: "data",
            totalProperty: "count",
            id: "id"
        }, [
            {name: 'nombre'},
            {name: 'disponible'},
            {name: 'contable'},
            {name: 'girados'},
            {name: 'no_entregados'},
            {name: 'diferido'},
            {name: 'saldo'},
            {name: 'diferencia'},
            {name: 'tc'},
            {name: 'sobre_giro_otorgado'},
            {name: 'fecha'},
            {name: 'hora'}
        ]),
        sortInfo: {field: 'hora', direction: "DESC"},
        groupField: 'hora',
        listeners: {
            beforeload: function (store, objeto) {
                store.baseParams.fecha = Ext.getCmp('m_contable_dp_fecha').getRawValue();
            },
            load: function (store, objeto) {
                Ext.mc_resumen.gview.toggleRowIndex(Ext.mc_resumen.stdata.getCount()-1);
            }             
        }
    });

    Ext.mc_resumen.gview = new Ext.grid.GroupingView({
            forceFit: true,
            startCollapsed:true,
            groupTextTpl: '{text}'
        });

    Ext.mc_resumen.grid = new Ext.grid.GridPanel({
        store: Ext.mc_resumen.stdata,
        columns: [
            {id: 'nombre', header: "Nombre", width: 60, sortable: true, dataIndex: 'nombre'},
            {header: "Disponible", width: 20, sortable: true, hidden: true, dataIndex: 'disponible'},
            {header: "Contable", width: 20, sortable: true, hidden: true, dataIndex: 'contable'},
            {header: "Girados", width: 20, sortable: true, hidden: true, dataIndex: 'girados'},
            {header: "No entregados", width: 20, sortable: true, hidden: true, dataIndex: 'no_entregados'},
            {header: "Diferido", width: 20, sortable: true, hidden: true, dataIndex: 'diferido'},
            {header: "Saldo", width: 20, sortable: true, dataIndex: 'saldo'},
            {header: "Diferencia", width: 20, sortable: true, dataIndex: 'diferencia'},
            {header: "tc", width: 20, sortable: true, hidden: true, dataIndex: 'tc'},
            {header: "Sobre giro otorgado", width: 20, hidden: true, sortable: true, dataIndex: 'sobre_giro_otorgado'},
            {header: "Hora", width: 20, sortable: true, dataIndex: 'hora',hidden: true}
        ],

        //myGrid.getColumnModel().setHidden(0, true);
        view: Ext.mc_resumen.gview  ,
        loadMask:new Ext.LoadMask(Ext.getBody(), {msg:"Cargando datos..."}),
        tbar: [
            new Ext.Button({
                text: 'Mostrar detalles',
                icon: '../../../images/folder_edit.png',
                handler: function () {
                    if (Ext.mc_resumen.bool == false) {
                        Ext.mc_resumen.grid.getColumnModel().setHidden(1, false);
                        Ext.mc_resumen.grid.getColumnModel().setHidden(2, false);
                        Ext.mc_resumen.grid.getColumnModel().setHidden(3, false);
                        Ext.mc_resumen.grid.getColumnModel().setHidden(4, false);
                        Ext.mc_resumen.grid.getColumnModel().setHidden(5, false);                        
                        Ext.mc_resumen.grid.getColumnModel().setHidden(8, false);
                        this.setText('Ocultar detalles');
                        Ext.mc_resumen.bool = true;
                    } else {
                        Ext.mc_resumen.grid.getColumnModel().setHidden(1, true);
                        Ext.mc_resumen.grid.getColumnModel().setHidden(2, true);
                        Ext.mc_resumen.grid.getColumnModel().setHidden(3, true);
                        Ext.mc_resumen.grid.getColumnModel().setHidden(4, true);
                        Ext.mc_resumen.grid.getColumnModel().setHidden(5, true);                        
                        Ext.mc_resumen.grid.getColumnModel().setHidden(8, true);
                        this.setText('Mostrar detalles');
                        Ext.mc_resumen.bool = false;
                    }
                }
            }), '-',
            new Ext.Button({
                text: 'Buscar y descargar documento',
                icon: '../../../images/save.png',
                handler: function () {


                    if (!Ext.mc_resumen.wind) {
                        Ext.mc_resumen.wind = new Ext.Window({
                            closeAction: 'hide',
                            title: 'Seleccione el horario para realizar la descarga',
                            height: 110,
                            width: 300,
                            bodyStyle: 'padding: 10px 10px 10px 10px;',
                            constrain: true,
                            items: [Ext.mc_resumen.combo_horas],
                            buttons: [
                                {
                                    text: 'Descargar documento',
                                    icon: '../../../images/save.png',
                                    handler: function (btn) {
                                        var fecha = Ext.getCmp('m_contable_dp_fecha').getRawValue();
                                        if (fecha == '') {
                                            //fecha = new Date();
                                            //fecha = fecha.toLocaleDateString();                                           
                                            //var rest = fecha.replace('/', '-');
                                            //fecha = rest.replace('/', '-');
                                             Ext.MessageBox.alert('Información..', 'Seleccione una fecha.');
                                             return false;
                                        }
                                        window.open(BASE_URL_FRAME+'/index.php/mod_contable/default/descarga/?file=' + fecha + '_' + Ext.mc_resumen.combo_horas.getValue() + '.xlsx');
                                    }
                                },
                                {
                                    text: 'Cancelar',
                                    handler: function (btn) {
                                        Ext.mc_resumen.wind.hide();
                                    }
                                }
                            ]
                        });


                    }

                    Ext.mc_resumen.wind.show();
                }

            }),

            '->', 'Fecha',
            new Ext.form.DateField({
                fieldLabel: 'Buscar por  fecha',
                id: 'm_contable_dp_fecha',
                format: 'd-m-Y',
                listeners: {
                    'select': function () {
                        var fecha = Ext.getCmp('m_contable_dp_fecha').getValue();
                        Ext.mc_resumen.stdata.load({params: {f: 3}});
                        Ext.mc_resumen.sthoras.load({params: {f: 3}});
                    }
                }
            })
        ],
        frame: true,
        width: 700,
        height: 450,
        iconCls: 'icon-grid',
        fbar: ['Reportes de saldos']
    });

    new Ext.Viewport({
        layout: 'fit',
        items: [
            Ext.mc_resumen.grid
        ]
    });
    
   Ext.Ajax.request({
        url: 'cargarultimafecha',
        method: 'POST',
        params: {},
         callback: function (options, success, response) {
                    responseData = Ext.decode(response.responseText);
                                if (responseData.success == true) {
                                     Ext.MessageBox.alert('Información', 'Fecha última actualización:'+responseData.fecha);
                                }
        }
     });
});