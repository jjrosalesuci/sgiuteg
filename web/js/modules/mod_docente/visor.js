/*!
 * Ext JS Library 3.3.1
 * Copyright(c) 2006-2010 Sencha Inc.
 * licensing@sencha.com
 * http://www.sencha.com/license
 */


Ext.onReady(function(){

    Ext.QuickTips.init();
    Ext.form.Field.prototype.msgTarget = 'side';
    Ext.ns('Ext.visor_horarios');


    moment.locale('es');
    var currentDate = 'Fecha Actual : '+moment().format('LL')+' Hora: '+moment().format('h:mm a');
    
    Ext.visor_horarios.stHorarios = new Ext.data.Store({
        url: 'horarios/cargartodos',
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

        // tabs for the center

    
    Ext.visor_horarios.lunes = new Ext.Button({
            text: 'Lunes',
            iconAlign: 'left',
            width: 148,
            enableToggle: true,
            allowDepress:false,
            handler: Ext.visor_horarios.myBtnHandler,

        });
    Ext.visor_horarios.martes = new Ext.Button({
            text: 'Martes',
            width: 148,
            enableToggle: true,
            handler: Ext.visor_horarios.myBtnHandler,

        });
    Ext.visor_horarios.miercoles = new Ext.Button({
            text: 'Miercoles',
            width: 148,
            enableToggle: true,
            handler: Ext.visor_horarios.myBtnHandler,

        });
    Ext.visor_horarios.jueves = new Ext.Button({
            text: 'Jueves',
            width: 148,
            enableToggle: true,
            handler: Ext.visor_horarios.myBtnHandler,

        });
    Ext.visor_horarios.viernes = new Ext.Button({
            text: 'Viernes',
            width: 148,
            enableToggle: true,
            handler: Ext.visor_horarios.myBtnHandler,

        });
    Ext.visor_horarios.sabado = new Ext.Button({
            text: 'Sábado',
            width: 148,
            enableToggle: true,
            handler: Ext.visor_horarios.myBtnHandler,

        });
    Ext.visor_horarios.domingo = new Ext.Button({
            text: 'Domingo',
            width: 148,
            enableToggle: true,
            handler: Ext.visor_horarios.myBtnHandler,

    });
    Ext.visor_horarios.completo = new Ext.Button({
            text: 'Ver Todos',
            width: 148,
            enableToggle: true,
            handler: Ext.visor_horarios.myBtnHandler,

    });
    Ext.visor_horarios.buscar  = new Ext.Button({
        text: 'Buscar',
        handler: Ext.visor_horarios.myBtnHandler,
        icon: '../../images/lupa.png'
    });

    Ext.visor_horarios.tbFill = new Ext.Toolbar.Fill();

    Ext.visor_horarios.smlunes = new Ext.grid.RowSelectionModel({});

    Ext.visor_horarios.gpLunes = new Ext.grid.GridPanel({
        frame: true,
        iconCls: 'icon-grid',
        autoExpandColumn: 'expandir',
        loadMask: 'Cargando...',
        //width: 600,
        store: Ext.visor_horarios.stHorarios,
        //clicksToEdit: 1,
        sm: Ext.visor_horarios.smlunes,
        stripeRows: true,
        //floating: false,
        autoHeight: true,

        layout: 'fit',

        columns: [
            new Ext.grid.RowNumberer(),
            {hidden: true, hideable: false, dataIndex: 'id'},
            {id:'expandir',header: 'Materia', width: 250,sortable: true, dataIndex: 'nombre_materia'},
            {header: 'Hora Inicio', width: 100, dataIndex: 'hora_inicio'},
            {header: 'Hora Fin', width: 100, dataIndex: 'hora_fin'},
            {header: 'Aula', width: 80, dataIndex: 'nombre_aula'},
            {header: 'Edificio', width: 80, dataIndex: 'edificio'},
        ],
    });

    Ext.visor_horarios.gpMartes = new Ext.grid.GridPanel({
        frame: true,
        iconCls: 'icon-grid',
        autoExpandColumn: '1',
        loadMask: 'Cargando...',
        //width: 600,
        store: Ext.visor_horarios.stHorarios,
        //clicksToEdit: 1,
        sm: Ext.visor_horarios.smlunes,
        stripeRows: true,
        //floating: false,
        autoHeight: true,

        layout: 'fit',

        columns: [
            new Ext.grid.RowNumberer(),
            {hidden: true, hideable: false, dataIndex: 'id'},
            {id:'1',header: 'Materia', width: 250,sortable: true, dataIndex: 'nombre_materia'},
            {header: 'Hora Inicio', width: 100, dataIndex: 'hora_inicio'},
            {header: 'Hora Fin', width: 100, dataIndex: 'hora_fin'},
            {header: 'Aula', width: 80, dataIndex: 'nombre_aula'},
            {header: 'Edificio', width: 80, dataIndex: 'edificio'},
        ],
    });

    Ext.visor_horarios.gpMiercoles = new Ext.grid.GridPanel({
        frame: true,
        iconCls: 'icon-grid',
        autoExpandColumn: '2',
        loadMask: 'Cargando...',
        //width: 600,
        store: Ext.visor_horarios.stHorarios,
        //clicksToEdit: 1,
        sm: Ext.visor_horarios.smlunes,
        stripeRows: true,
        //floating: false,
        autoHeight: true,

        layout: 'fit',

        columns: [
            new Ext.grid.RowNumberer(),
            {hidden: true, hideable: false, dataIndex: 'id'},
            {id:'2',header: 'Materia', width: 250,sortable: true, dataIndex: 'nombre_materia'},
            {header: 'Hora Inicio', width: 100, dataIndex: 'hora_inicio'},
            {header: 'Hora Fin', width: 100, dataIndex: 'hora_fin'},
            {header: 'Aula', width: 80, dataIndex: 'nombre_aula'},
            {header: 'Edificio', width: 80, dataIndex: 'edificio'},
        ],
    });

    Ext.visor_horarios.tabs = new Ext.TabPanel({
            region: 'center',
            margins:'3 3 3 0', 
            activeTab: 0,
            defaults:{autoScroll:true},

            items:[{
                title: currentDate,
                //html: 'Hola',
                items:[
                    {
                        html:'Lunes'
                    },                
                    Ext.visor_horarios.gpLunes,
                    {
                        html:'Martes'
                    },
                    Ext.visor_horarios.gpMartes,
                    {
                        html:'Miercoles'
                    },
                    Ext.visor_horarios.gpMiercoles
                ]
            },
             
            ]
    });

        // Panel for the west
    Ext.visor_horarios.nav = new Ext.Panel({

            title: 'Día de la Semana',
            region: 'west',
            split: false,
            width: 150,
            collapsible: true,
            items:[
                Ext.visor_horarios.lunes,
                Ext.visor_horarios.martes,
                Ext.visor_horarios.miercoles,
                Ext.visor_horarios.jueves,
                Ext.visor_horarios.viernes,
                Ext.visor_horarios.sabado,
                Ext.visor_horarios.domingo,
                Ext.visor_horarios.completo,


                
            ],
            margins:'3 0 3 3',
            cmargins:'3 3 3 3'
        });



    new Ext.Viewport({
        layout: 'border', 
        plain:true,    
        items: [
           Ext.visor_horarios.nav, Ext.visor_horarios.tabs
        ]
    });
});