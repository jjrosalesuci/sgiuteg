    moment.locale('es');
    var currentDate = 'Fecha: '+moment().format('LL')+' Hora: '+moment().format('h:mm:ss a');
Ext.onReady(function(){

    Ext.QuickTips.init();
    Ext.form.Field.prototype.msgTarget = 'side';
    Ext.ns('Ext.asistencia');


    Ext.asistencia.stHorarios = new Ext.data.Store({
        url: 'asistencia/cargartodos',
        /*listeners: {'beforeload': function (store, objeto) {
            store.baseParams.query      = Ext.getCmp('buscar_usuario').getRawValue();
            store.baseParams.dia        = Ext.getCmp('combo_dia').getRawValue();
            store.baseParams.periodo    = Ext.getCmp('combo_periodo').getValue();
        }},*/
        autoLoad: true,
        reader: new Ext.data.JsonReader({
            root: "data",
            totalProperty: "count",
            id: "id"
        }, [
            {name: 'id'},
            {name: 'id_materia'},
            {name: 'nombre_materia'},
            {name: 'id_aula'},
            {name: 'hora_inicio'},
            {name: 'hora_fin'},
            {name: 'id_docente'},
            {name: 'nombre_docente'},
            {name: 'dia_semana'},
            {name: 'trimestre'},
            {name: 'nombre_aula'},
            {name: 'edificio'}
        ])
    });

    Ext.asistencia.iniciar = new Ext.Button({
        text: 'Iniciar Materia',
        handler: function (btn) {
            if (!Ext.asistencia.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un turno para iniciar');
                return false;
            }
            Ext.MessageBox.show({
                title: 'Mensaje de confirmación',
                msg: '¿ Usted está seguro que desea iniciar esta materia ?',
                buttons: Ext.MessageBox.YESNOCANCEL,
                icon: Ext.MessageBox.QUESTION
            });    
        },
        icon: '../../images/alarm16x16.png'
    });

    Ext.asistencia.finalizar = new Ext.Button({
        text: 'Finalizar Materia',
        handler:function (btn) {
            if (!Ext.asistencia.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un turno para finalizar');
                return false;
            }
            Ext.MessageBox.show({
                title: 'Mensaje de confirmación',
                msg: '¿ Usted está seguro que desea finalizar esta materia ?',
                buttons: Ext.MessageBox.YESNOCANCEL,
                icon: Ext.MessageBox.QUESTION
            });    
        },
        icon: '../../images/alarm16x16.png'
    });

    Ext.asistencia.tbFill = new Ext.Toolbar.Fill();

    //Ext.asistencia.sm = new Ext.grid.RowSelectionModel({});

    Ext.asistencia.gpHorarios = new Ext.grid.GridPanel({
        frame: true,
        iconCls: 'icon-grid',
        //autoExpandColumn: 'expandir',
        loadMask: 'Cargando...',
        //title:'Horario del día',
        icon: '../../images/alarm16x16.png',
        width: 450,
        store: Ext.asistencia.stHorarios,
        //clicksToEdit: 1,
        //sm: Ext.asistencia.sm,
        region: 'center',
        stripeRows: true,
        floating: false,
        columns: [
            new Ext.grid.RowNumberer(),
            {hidden: true, hideable: false, dataIndex: 'id'},
            {header: 'Materia', width: 200, dataIndex: 'nombre_materia'},
            {header: 'Hora Inicio', width: 80, dataIndex: 'hora_inicio'},
            {header: 'Hora Fin', width: 80, dataIndex: 'hora_fin'},
            {header: 'Aula', width: 70, dataIndex: 'nombre_aula'},
            {header: 'Edificio', width: 70, dataIndex: 'edificio'},
            {
                xtype: 'actioncolumn',
                width: 60,
                css: 'text-align: center',
                header: 'Iniciar',
                items: [{
                    icon   :'../../images/iniciar.png',  // Use a URL in the icon config
                    tooltip:'Iniciar Materia',
                    handler: function (btn) {
                            Ext.MessageBox.show({
                                title: 'Mensaje de confirmación',
                                msg: '¿ Usted está seguro que desea iniciar esta materia ?',
                                buttons: Ext.MessageBox.YESNO,
                                icon: Ext.MessageBox.QUESTION
                            });    
                        }
                }]
            },
            {
                xtype: 'actioncolumn',
                width: 60,
                css: 'text-align: center',
                header: 'Finalizar',
                items: [{
                    icon   :'../../images/finalizar.png',  // Use a URL in the icon config
                    tooltip:'Finalizar Materia',
                    handler: function (btn) {
                            Ext.MessageBox.show({
                                title: 'Mensaje de confirmación',
                                msg: '¿ Usted está seguro que desea finalizar esta materia ?',
                                buttons: Ext.MessageBox.YESNO,
                                icon: Ext.MessageBox.QUESTION
                            });    
                        }
                }]
            }
        ],
        /*tbar: [
            Ext.asistencia.iniciar,Ext.asistencia.finalizar
        ],*/
        bbar: new Ext.PagingToolbar({
            pageSize: 14,
            store: Ext.asistencia.stHorarios,
            displayInfo: true,
            displayMsg: 'Resultados {0} - {1} de {2}',
            emptyMsg: 'Ning&uacute;n resultado para mostrar.'
        })
    });

    new Ext.Viewport({
        layout: 'fit',
        plain:true,    
        items: [
                Ext.asistencia.gpHorarios
        ]
    });
});