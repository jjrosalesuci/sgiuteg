Ext.onReady(function () {

    Ext.QuickTips.init();
    Ext.form.Field.prototype.msgTarget = 'side';
    Ext.ns('Ext.gest_horarios');
    var currentDate = moment().format('DD[-]MM[-]YYYY');

    Ext.gest_horarios.stHorarios = new Ext.data.Store({
        url: 'horarios/cargar',
        listeners: {'beforeload': function (store, objeto) {
            store.baseParams.query      = Ext.getCmp('buscar_usuario').getRawValue();
            store.baseParams.dia        = Ext.getCmp('combo_dia').getRawValue();
            store.baseParams.periodo    = Ext.getCmp('combo_periodo').getValue();
        }},
        autoLoad: false,
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
            {name: 'edificio'},
            {name: 'modalidad'},
        ])
    });

    //
    //Combobox autofill Materias del alumno
    //

    Ext.gest_horarios.stmateria = new Ext.data.JsonStore({
        root: 'data',
        autoload:true,
        /*baseParams: {
            column : 'id_alumno'
        },*/
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
            url : '../mod_nomencladores/asignaturas/uploadsearch'
        })
    });

    Ext.gest_horarios.stdocentes = new Ext.data.JsonStore({
        root: 'data',
        fields : [
        {
            name: 'nombre_docente',
            mapping : 'nombre_docente'
        },
        {
            name: 'id_docente',
            mapping : 'id_docente'
        }
        ],
        proxy : new Ext.data.ScriptTagProxy({
            url : '../mod_nomencladores/docentes/get_docentes'
        })
    });

    Ext.gest_horarios.staulas = new Ext.data.JsonStore({
        root: 'data',
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
            url : '../mod_docente/aulas/uploadsearch'
        })
    });

    Ext.gest_horarios.combomaterias = new Ext.form.ComboBox({
            listeners: {
                'select': function(cmb, rec, idx) {
                Ext.gest_horarios.combodocentes.reset();
                Ext.gest_horarios.stdocentes.load({
                params: { 'id_materia': this.getValue("id_m")}
                });

                }
            },
            submitValue: true,
            hiddenName: 'nombre_materia',
            valueField: 'id_m',
            displayField: 'nombre_m',
            store: Ext.gest_horarios.stmateria,
            autoCreate: true,
            //typeAhead: true,
            //triggerAction: 'all',
            readOnly: false,
            hideTrigger:true,
            minChars:1,
            pageSize : 20,
            totalProperty : 'count',
            mode: 'remote',
            width: 200,
            anchor: '90%',       
            align: 'rigth',
            fieldLabel: 'Materia',
            allowBlank: false,
            emptyText: 'Este campo está vacío!!!',
        });

    Ext.gest_horarios.combodocentes = new Ext.form.ComboBox({
            submitValue: true,
            hiddenName: 'nombre_docente',
            valueField: 'id_docente',
            displayField: 'nombre_docente',
            store: Ext.gest_horarios.stdocentes,
            autoCreate: false,
            typeAhead: true,
            triggerAction: 'all',
            readOnly: false,
            mode: 'local',
            width: 200,
            anchor: '90%',       
            align: 'rigth',
            fieldLabel: 'Docente',
            allowBlank: false,
            emptyText: 'Este campo está vacío!!!',
    });

    Ext.gest_horarios.comboaulas = new Ext.form.ComboBox({
            hiddenName: 'nombre_aula',
            id:'combo_aula',
            valueField: 'id',
            displayField: 'nombre',
            store: Ext.gest_horarios.staulas,
            autoCreate: false,
            //typeAhead: true,
            //triggerAction: 'all',
            readOnly: false,
            mode: 'remote',
            //width: 200,
            hideTrigger:true,
            minChars:1,
            pageSize : 20,
            totalProperty : 'count',
            anchor: '85%',       
            align: 'rigth',
            fieldLabel: 'Aula o Laboratorio',
            allowBlank: false,
            emptyText: 'Este campo está vacío!!!',
    });

    Ext.gest_horarios.dia_store = new Ext.data.Store({
        url: 'horarios/cargardia',
        autoLoad: true,
        reader: new Ext.data.JsonReader({
            root: "data",
            id: "id"
        }, [
            {name: 'dia'}
        ])
    });

    Ext.gest_horarios.dia = new Ext.form.ComboBox({
        listeners: {
                'select': function(cmb, rec, idx) {
                Ext.gest_horarios.stHorarios.load();
                }/*,
                'afterrender': function(cmb, rec, idx) {
                Ext.gest_horarios.stHorarios.load();
                }*/
        },
        hiddenName: 'dia_semana',
        id: 'combo_dia',
        valueField: 'dia',
        displayField: 'dia',
        store: Ext.gest_horarios.dia_store,
        autoCreate: true,
        typeAhead: true,
        triggerAction: 'all',
        readOnly: false,
        forceSelection:true,
        mode: 'local',
        width: 100,
        value:'LUNES',
        anchor: '90%',      
        align: 'rigth',
        fieldLabel: 'Dia',
        emptyText:'Seleccione',
    });

    Ext.gest_horarios.stmodalidad = new Ext.data.Store({
        url: 'horarios/modalidad',
        autoLoad: true,
        reader: new Ext.data.JsonReader({
            root: "data",
            id: "id"
        }, [
            {name: 'modalidad'}
        ])
    });

    Ext.gest_horarios.combo_modalidad = new Ext.form.ComboBox({
        hiddenName: 'modalidad',
        valueField: 'modalidad',
        displayField: 'modalidad',
        store: Ext.gest_horarios.stmodalidad,
        autoCreate: true,
        typeAhead: true,
        triggerAction: 'all',
        readOnly: false,
        forceSelection:true,
        mode: 'local',
        //width: 100,
        anchor: '85%',  
        allowBlank: false,    
        align: 'rigth',
        fieldLabel: 'Modalidad',
        emptyText:'Seleccione',
    });

    Ext.gest_horarios.trimestre_store = new Ext.data.JsonStore({
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

    Ext.gest_horarios.combotrimestre = new Ext.form.ComboBox({
            listeners: {
                'select': function(cmb, rec, idx) {
                Ext.gest_horarios.stHorarios.load();
                },
            },
            hiddenName: 'id',
            valueField: 'id',
            displayField: 'nombre',
            id:'combo_periodo',
            store: Ext.gest_horarios.trimestre_store,
            autoCreate: false,
            //typeAhead: true,
            triggerAction: 'all',
            readOnly: false,
            mode: 'remote',
            width: 200,
            enableKeyEvents: true,
            forceSelection:true,
            //hideTrigger:true,
            minChars:1,
            pageSize : 20,
            totalProperty : 'count',
            anchor: '90%',       
            align: 'rigth',
            fieldLabel: 'Trimestre',
            allowBlank: true,
            emptyText: 'Seleccione',
    });

    Ext.gest_horarios.fp = new Ext.FormPanel({
        labelAlign: 'top',
        id:'horariosfp',
        frame:true,
        bodyStyle:'padding:5px 5px 0',
        anchor:'100%',
        items: [{
            layout:'column',
            items:[
            {
                columnWidth:.7,
                layout: 'form',
                items: [
                    Ext.gest_horarios.combomaterias,
                    Ext.gest_horarios.combodocentes,
                    {
                        columnWidth:.7,
                        layout: 'column',
                        /*defaults: {
                            // applied to each contained panel
                            bodyStyle:'padding-right:3px'
                        },
                        layoutConfig: {
                            // The total column count must be specified here
                            columns: 2
                        },*/
                        items: [
                            {
                                columnWidth:.48,
                                layout: 'form',
                                items: [
                                    Ext.gest_horarios.combo_modalidad
                                ]
                            },
                            {
                                columnWidth:.48,
                                layout: 'form',
                                items: [
                                    Ext.gest_horarios.comboaulas
                                ]
                            }
                            
                            ]
                    }  
                ]
            },
            {
                    fieldLabel: 'id',
                    allowBlank: true,
                    name: 'id',
                    hidden: true
            },
            {
                columnWidth:.3,
                layout: 'form',
                left: 6,
                items: [{
                    listeners: {
                        'select': function(cmb, rec, idx) {
                            Ext.getCmp('final_timer').setMinValue(this.getValue());
                        }
                    },
                    id:'start_timer',
                    xtype:'timefield',
                    fieldLabel: 'Hora de Inicio',
                    allowBlank: false,
                    name: 'hora_inicio',
                    minValue: '7:00',
                    maxValue: '22:25',
                    format: 'H:i:s',
                    increment: 5,
                    emptyText: 'Campo vacío!!!',
                    anchor:'85%'
                  },
                  {
                    id: 'final_timer',
                    xtype:'timefield',
                    fieldLabel: 'Hora Fin',
                    allowBlank: false,
                    name: 'hora_fin',
                    minValue: '7:30',
                    maxValue: '22:30',
                    format: 'H:i:s',
                    increment: 5,
                    emptyText: 'Campo vacío!!!',
                    anchor:'85%'
                  },
                  {
                    listeners: {'check': function (Checkbox, checked) {
                        if(checked){
                            Ext.getCmp('ventanadd').setHeight(515);
                            Ext.getCmp('ventanadd').setWidth(800);
                            Ext.getCmp('ventanadd').setAutoScroll(true);
                            Ext.getCmp('horariosfp').setHeight(450);
                            Ext.getCmp('ventanadd').center();
                            Ext.getCmp('repeticiones').show();
                            Ext.getCmp('start_timer').disable();
                            Ext.getCmp('final_timer').disable();
                            Ext.getCmp('combo_aula').disable();
                            Ext.getCmp('start_timer').reset();
                            Ext.getCmp('final_timer').reset();
                            Ext.getCmp('combo_aula').reset();
                            Ext.getCmp('aceptar_btn').disable();
                        }else if(!checked){
                            Ext.getCmp('ventanadd').setHeight(232);
                            Ext.getCmp('ventanadd').setWidth(450);
                            Ext.getCmp('ventanadd').setAutoScroll(false);
                            Ext.getCmp('horariosfp').setHeight(232);
                            Ext.getCmp('ventanadd').center();
                            Ext.getCmp('horariosfp').setPosition(0,0);
                            Ext.getCmp('repeticiones').setVisible(false);
                            Ext.getCmp('start_timer').enable();
                            Ext.getCmp('final_timer').enable();
                            Ext.getCmp('combo_aula').enable();
                            Ext.getCmp('check_lun').reset();
                            Ext.getCmp('check_mar').reset();
                            Ext.getCmp('check_mie').reset();
                            Ext.getCmp('check_jue').reset();
                            Ext.getCmp('check_vie').reset();
                            Ext.getCmp('check_sab').reset();
                            Ext.getCmp('check_dom').reset();
                            Ext.getCmp('aceptar_btn').enable();
                        }
                    }},
                    xtype: 'checkbox',
                    id:'repetir',
                    width: 50,
                    fieldLabel: 'Repetir',
                    name: 'repetir'
                  }
                ]
            },
            {
                columnWidth:.95,
                layout: 'form',
                left: 6,
                items: [
                    {
                        xtype: 'fieldset',
                        title: 'Repeticiones Diarias',
                        id:'repeticiones',
                        autoHeight: true,
                        autoWidth: true,
                        layout: 'table',
                        defaults: {
                            // applied to each contained panel
                            bodyStyle:'padding:5px'
                        },
                        layoutConfig: {
                            // The total column count must be specified here
                            columns: 7
                        },
                        hidden: true,
                        items: [
                            
                            {   
                                columnWidth:.12,
                                layout:'form',
                                //bodyStyle:'padding-left:5px',
                                items: [
                                    {
                                    // Use the default, automatic layout to distribute the controls evenly
                                    // across a single row
                                    xtype: 'checkboxgroup',
                                    //fieldLabel: 'Puede variar los campos marcando el check modificar',
                                    items: [
                                        {
                                            listeners: {'check': function (Checkbox, checked) {
                                                if(checked){
                                                    Ext.getCmp('start_timer_lunes').enable();
                                                    Ext.getCmp('final_timer_lunes').enable();
                                                    Ext.getCmp('combo_aula_lunes').enable();
                                                    Ext.getCmp('salvar_repeticiones').enable();
                                                }else{
                                                    Ext.getCmp('start_timer_lunes').disable();
                                                    Ext.getCmp('final_timer_lunes').disable();
                                                    Ext.getCmp('combo_aula_lunes').disable();
                                                    Ext.getCmp('start_timer_lunes').reset();
                                                    Ext.getCmp('final_timer_lunes').reset();
                                                    Ext.getCmp('combo_aula_lunes').reset();
                                                }
                                                if(!checked&&!Ext.getCmp('check_lun').checked&&!Ext.getCmp('check_mar').checked&&!Ext.getCmp('check_mie').checked&&!Ext.getCmp('check_jue').checked&&!Ext.getCmp('check_vie').checked&&!Ext.getCmp('check_sab').checked&&!Ext.getCmp('check_dom').checked){
                                                    Ext.getCmp('salvar_repeticiones').disable();
                                                }
                                            }},
                                            boxLabel: 'Lunes',
                                            name: 'cb-auto-1',id:'check_lun'
                                        }

                                        /*{boxLabel: 'Martes', name: 'cb-auto-2'},
                                        {boxLabel: 'Miércoles', name: 'cb-auto-3'},
                                        {boxLabel: 'Jueves', name: 'cb-auto-4'},
                                        {boxLabel: 'Viernes', name: 'cb-auto-5'},
                                        {boxLabel: 'Sábado', name: 'cb-auto-4'},
                                        {boxLabel: 'Domingo', name: 'cb-auto-4'}*/
                                    ]
                                    },
                                    {
                                        listeners: {
                                            'select': function(cmb, rec, idx) {
                                                Ext.getCmp('final_timer_lunes').setMinValue(this.getValue());
                                            }
                                        },
                                        id:'start_timer_lunes',
                                        xtype:'timefield',
                                        fieldLabel: 'Hora de Inicio',
                                        allowBlank: false,
                                        name: 'hora_inicio_lunes',
                                        minValue: '7:00',
                                        maxValue: '22:25',
                                        format: 'H:i:s',
                                        increment: 5,
                                        emptyText: 'Vacío!!!',
                                        disabled: true,
                                        width: 80
                                    },
                                    {
                                        id: 'final_timer_lunes',
                                        xtype:'timefield',
                                        fieldLabel: 'Hora Fin',
                                        allowBlank: false,
                                        name: 'hora_fin_lunes',
                                        minValue: '7:30',
                                        maxValue: '22:30',
                                        format: 'H:i:s',
                                        increment: 5,
                                        emptyText: 'Vacío!!!',
                                        disabled: true,
                                        width: 80
                                    },
                                    
                                    {
                                        xtype:'combo',
                                        id:'combo_aula_lunes',
                                        hiddenName: 'nombre_aula_lunes',
                                        valueField: 'id',
                                        displayField: 'nombre',
                                        store: Ext.gest_horarios.staulas,
                                        autoCreate: false,
                                        //typeAhead: true,
                                        triggerAction: 'all',
                                        readOnly: false,
                                        mode: 'remote',
                                        //hideTrigger:true,
                                        minChars:1,
                                        //pageSize : 20,
                                        totalProperty : 'count',
                                        width: 80,
                                        anchor: '90%',
                                        align: 'rigth',
                                        fieldLabel: 'Aula',
                                        allowBlank: false,
                                        disabled: true,
                                        emptyText: 'Vacío!!!',
                                    }
                                    
                                ]
                            },
                            {   
                                columnWidth:.12,
                                layout:'form',
                                items: [
                                        {
                                        // Use the default, automatic layout to distribute the controls evenly
                                        // across a single row
                                        xtype: 'checkboxgroup',
                                        //columns: 1,
                                        //fieldLabel: 'Auto Layout',
                                        items: [
                                            {listeners: {'check': function (Checkbox, checked) {
                                                if(checked){
                                                    Ext.getCmp('start_timer_martes').enable();
                                                    Ext.getCmp('final_timer_martes').enable();
                                                    Ext.getCmp('combo_aula_martes').enable();
                                                    Ext.getCmp('salvar_repeticiones').enable();
                                                }else{
                                                    Ext.getCmp('start_timer_martes').disable();
                                                    Ext.getCmp('final_timer_martes').disable();
                                                    Ext.getCmp('combo_aula_martes').disable();
                                                    Ext.getCmp('start_timer_martes').reset();
                                                    Ext.getCmp('final_timer_martes').reset();
                                                    Ext.getCmp('combo_aula_martes').reset();
                                                }
                                                if(!checked&&!Ext.getCmp('check_lun').checked&&!Ext.getCmp('check_mar').checked&&!Ext.getCmp('check_mie').checked&&!Ext.getCmp('check_jue').checked&&!Ext.getCmp('check_vie').checked&&!Ext.getCmp('check_sab').checked&&!Ext.getCmp('check_dom').checked){
                                                    Ext.getCmp('salvar_repeticiones').disable();
                                                }
                                            }},boxLabel: 'Martes', name: 'cb-auto-2',id:'check_mar'}
                                           ]
                                        },
                                        {
                                            listeners: {
                                                'select': function(cmb, rec, idx) {
                                                    Ext.getCmp('final_timer_martes').setMinValue(this.getValue());
                                                }
                                            },
                                            id:'start_timer_martes',
                                            xtype:'timefield',
                                            fieldLabel: 'Hora de Inicio',
                                            allowBlank: false,
                                            name: 'hora_inicio_martes',
                                            minValue: '7:00',
                                            maxValue: '22:25',
                                            format: 'H:i:s',
                                            increment: 5,
                                            emptyText: 'Vacío!!!',
                                            disabled:true,
                                            width: 80
                                        },
                                        {
                                            id: 'final_timer_martes',
                                            xtype:'timefield',
                                            fieldLabel: 'Hora Fin',
                                            allowBlank: false,
                                            name: 'hora_fin_martes',
                                            minValue: '7:30',
                                            maxValue: '22:30',
                                            format: 'H:i:s',
                                            increment: 5,
                                            emptyText: 'Vacío!!!',
                                            disabled:true,
                                            width: 80
                                        },
                                    
                                    {
                                        xtype:'combo',
                                        id:'combo_aula_martes',
                                        hiddenName: 'nombre_aula_martes',
                                        valueField: 'id',
                                        displayField: 'nombre',
                                        store: Ext.gest_horarios.staulas,
                                        autoCreate: false,
                                        //typeAhead: true,
                                        triggerAction: 'all',
                                        readOnly: false,
                                        mode: 'remote',
                                        //hideTrigger:true,
                                        minChars:1,
                                        //pageSize : 20,
                                        totalProperty : 'count',
                                        width: 80,
                                        anchor: '90%',
                                        align: 'rigth',
                                        fieldLabel: 'Aula',
                                        allowBlank: false,
                                        disabled: true,
                                        emptyText: 'Vacío!!!',
                                    }
                                    ]
                            },
                            {   
                                columnWidth:.12,
                                layout:'form',
                                items: [
                                    {
                                    // Use the default, automatic layout to distribute the controls evenly
                                    // across a single row
                                    xtype: 'checkboxgroup',
                                    //columns: 1,
                                    //fieldLabel: 'Auto Layout',
                                    items: [
                                        {listeners: {'check': function (Checkbox, checked) {
                                                if(checked){
                                                    Ext.getCmp('start_timer_miercoles').enable();
                                                    Ext.getCmp('final_timer_miercoles').enable();
                                                    Ext.getCmp('combo_aula_miercoles').enable();
                                                    Ext.getCmp('salvar_repeticiones').enable();
                                                }else{
                                                    Ext.getCmp('start_timer_miercoles').disable();
                                                    Ext.getCmp('final_timer_miercoles').disable();
                                                    Ext.getCmp('combo_aula_miercoles').disable();
                                                    Ext.getCmp('start_timer_miercoles').reset();
                                                    Ext.getCmp('final_timer_miercoles').reset();
                                                    Ext.getCmp('combo_aula_miercoles').reset();
                                                }
                                                if(!checked&&!Ext.getCmp('check_lun').checked&&!Ext.getCmp('check_mar').checked&&!Ext.getCmp('check_mie').checked&&!Ext.getCmp('check_jue').checked&&!Ext.getCmp('check_vie').checked&&!Ext.getCmp('check_sab').checked&&!Ext.getCmp('check_dom').checked){
                                                    Ext.getCmp('salvar_repeticiones').disable();
                                                }
                                            }},boxLabel: 'Miércoles', name: 'cb-auto-3',id:'check_mie'}
                                    ]
                                    },
                                    {
                                        listeners: {
                                            'select': function(cmb, rec, idx) {
                                                Ext.getCmp('final_timer_miercoles').setMinValue(this.getValue());
                                            }
                                        },
                                        id:'start_timer_miercoles',
                                        xtype:'timefield',
                                        fieldLabel: 'Hora de Inicio',
                                        allowBlank: false,
                                        name: 'hora_inicio_miercoles',
                                        minValue: '7:00',
                                        maxValue: '22:25',
                                        format: 'H:i:s',
                                        increment: 5,
                                        emptyText: 'Vacío!!!',
                                        disabled:true,
                                        width: 80
                                    },
                                    {
                                        id: 'final_timer_miercoles',
                                        xtype:'timefield',
                                        fieldLabel: 'Hora Fin',
                                        allowBlank: false,
                                        name: 'hora_fin_miercoles',
                                        minValue: '7:30',
                                        maxValue: '22:30',
                                        format: 'H:i:s',
                                        increment: 5,
                                        emptyText: 'Vacío!!!',
                                        disabled: true,
                                        width: 80
                                    },
                                    
                                    {
                                        xtype:'combo',
                                        id:'combo_aula_miercoles',
                                        hiddenName: 'nombre_aula_miercoles',
                                        valueField: 'id',
                                        displayField: 'nombre',
                                        store: Ext.gest_horarios.staulas,
                                        autoCreate: false,
                                        //typeAhead: true,
                                        triggerAction: 'all',
                                        readOnly: false,
                                        mode: 'remote',
                                        //hideTrigger:true,
                                        minChars:1,
                                        //pageSize : 20,
                                        totalProperty : 'count',
                                        width: 80,
                                        anchor: '90%',
                                        align: 'rigth',
                                        fieldLabel: 'Aula',
                                        allowBlank: false,
                                        disabled: true,
                                        emptyText: 'Vacío!!!',
                                    }
                                    
                                ]
                            },
                            {   
                                columnWidth:.12,
                                layout:'form',
                                items: [
                                    {
                                    // Use the default, automatic layout to distribute the controls evenly
                                    // across a single row
                                    xtype: 'checkboxgroup',
                                    //columns: 1,
                                    //fieldLabel: 'Auto Layout',
                                    items: [

                                        {listeners: {'check': function (Checkbox, checked) {
                                                if(checked){
                                                    Ext.getCmp('start_timer_jueves').enable();
                                                    Ext.getCmp('final_timer_jueves').enable();
                                                    Ext.getCmp('combo_aula_jueves').enable();
                                                    Ext.getCmp('salvar_repeticiones').enable();
                                                }else{
                                                    Ext.getCmp('start_timer_jueves').disable();
                                                    Ext.getCmp('final_timer_jueves').disable();
                                                    Ext.getCmp('combo_aula_jueves').disable();
                                                    Ext.getCmp('start_timer_jueves').reset();
                                                    Ext.getCmp('final_timer_jueves').reset();
                                                    Ext.getCmp('combo_aula_jueves').reset();
                                                }
                                                if(!checked&&!Ext.getCmp('check_lun').checked&&!Ext.getCmp('check_mar').checked&&!Ext.getCmp('check_mie').checked&&!Ext.getCmp('check_jue').checked&&!Ext.getCmp('check_vie').checked&&!Ext.getCmp('check_sab').checked&&!Ext.getCmp('check_dom').checked){
                                                    Ext.getCmp('salvar_repeticiones').disable();
                                                }
                                            }},boxLabel: 'Jueves', name: 'cb-auto-4',id:'check_jue'}
                                    ]
                                    },
                                    {
                                        listeners: {
                                            'select': function(cmb, rec, idx) {
                                                Ext.getCmp('final_timer_jueves').setMinValue(this.getValue());
                                            }
                                        },
                                        id:'start_timer_jueves',
                                        xtype:'timefield',
                                        fieldLabel: 'Hora de Inicio',
                                        allowBlank: false,
                                        name: 'hora_inicio_jueves',
                                        minValue: '7:00',
                                        maxValue: '22:25',
                                        format: 'H:i:s',
                                        increment: 5,
                                        emptyText: 'Vacío!!!',
                                        disabled:true,
                                        width: 80
                                    },
                                    {
                                        id: 'final_timer_jueves',
                                        xtype:'timefield',
                                        fieldLabel: 'Hora Fin',
                                        allowBlank: false,
                                        name: 'hora_fin_jueves',
                                        minValue: '7:30',
                                        maxValue: '22:30',
                                        format: 'H:i:s',
                                        increment: 5,
                                        emptyText: 'Vacío!!!',
                                        disabled: true,
                                        width: 80
                                    },
                                    
                                    {
                                        xtype:'combo',
                                        id:'combo_aula_jueves',
                                        hiddenName: 'nombre_aula_jueves',
                                        valueField: 'id',
                                        displayField: 'nombre',
                                        store: Ext.gest_horarios.staulas,
                                        autoCreate: false,
                                        //typeAhead: true,
                                        triggerAction: 'all',
                                        readOnly: false,
                                        mode: 'remote',
                                        //hideTrigger:true,
                                        minChars:1,
                                        //pageSize : 20,
                                        totalProperty : 'count',
                                        width: 80,
                                        anchor: '90%',
                                        align: 'rigth',
                                        fieldLabel: 'Aula',
                                        allowBlank: false,
                                        disabled: true,
                                        emptyText: 'Vacío!!!',
                                    }
                                    
                                ]
                            },
                            {   
                                columnWidth:.12,
                                layout:'form',
                                items: [
                                    {
                                    // Use the default, automatic layout to distribute the controls evenly
                                    // across a single row
                                    xtype: 'checkboxgroup',
                                    //columns: 1,
                                    //fieldLabel: 'Auto Layout',
                                    items: [
    
                                        {listeners: {'check': function (Checkbox, checked) {
                                                if(checked){
                                                    Ext.getCmp('start_timer_viernes').enable();
                                                    Ext.getCmp('final_timer_viernes').enable();
                                                    Ext.getCmp('combo_aula_viernes').enable();
                                                    Ext.getCmp('salvar_repeticiones').enable();
                                                }else{
                                                    Ext.getCmp('start_timer_viernes').disable();
                                                    Ext.getCmp('final_timer_viernes').disable();
                                                    Ext.getCmp('combo_aula_viernes').disable();
                                                    Ext.getCmp('start_timer_viernes').reset();
                                                    Ext.getCmp('final_timer_viernes').reset();
                                                    Ext.getCmp('combo_aula_viernes').reset();
                                                }
                                                if(!checked&&!Ext.getCmp('check_lun').checked&&!Ext.getCmp('check_mar').checked&&!Ext.getCmp('check_mie').checked&&!Ext.getCmp('check_jue').checked&&!Ext.getCmp('check_vie').checked&&!Ext.getCmp('check_sab').checked&&!Ext.getCmp('check_dom').checked){
                                                    Ext.getCmp('salvar_repeticiones').disable();
                                                }
                                            }},boxLabel: 'Viernes', name: 'cb-auto-5',id:'check_vie'}
                                    ]
                                    },
                                    {
                                        listeners: {
                                            'select': function(cmb, rec, idx) {
                                                Ext.getCmp('final_timer_viernes').setMinValue(this.getValue());
                                            }
                                        },
                                        id:'start_timer_viernes',
                                        xtype:'timefield',
                                        fieldLabel: 'Hora de Inicio',
                                        allowBlank: false,
                                        name: 'hora_inicio_viernes',
                                        minValue: '7:00',
                                        maxValue: '22:25',
                                        format: 'H:i:s',
                                        increment: 5,
                                        emptyText: 'Vacío!!!',
                                        disabled:true,
                                        width: 80
                                    },
                                    {
                                        id: 'final_timer_viernes',
                                        xtype:'timefield',
                                        fieldLabel: 'Hora Fin',
                                        allowBlank: false,
                                        name: 'hora_fin_viernes',
                                        minValue: '7:30',
                                        maxValue: '22:30',
                                        format: 'H:i:s',
                                        increment: 5,
                                        emptyText: 'Vacío!!!',
                                        disabled: true,
                                        width: 80
                                    },
                                    
                                    {
                                        xtype:'combo',
                                        id:'combo_aula_viernes',
                                        hiddenName: 'nombre_aula_viernes',
                                        valueField: 'id',
                                        displayField: 'nombre',
                                        store: Ext.gest_horarios.staulas,
                                        autoCreate: false,
                                        //typeAhead: true,
                                        triggerAction: 'all',
                                        readOnly: false,
                                        mode: 'remote',
                                        //hideTrigger:true,
                                        minChars:1,
                                        //pageSize : 20,
                                        totalProperty : 'count',
                                        width: 80,
                                        anchor: '90%',
                                        align: 'rigth',
                                        fieldLabel: 'Aula',
                                        allowBlank: false,
                                        disabled: true,
                                        emptyText: 'Vacío!!!',
                                    }
                                    
                                ]
                            },
                            {   
                                columnWidth:.12,
                                layout:'form',
                                items: [
                                    {
                                    // Use the default, automatic layout to distribute the controls evenly
                                    // across a single row
                                    xtype: 'checkboxgroup',
                                    //columns: 1,
                                    //fieldLabel: 'Auto Layout',
                                    items: [
                                        {listeners: {'check': function (Checkbox, checked) {
                                                if(checked){
                                                    Ext.getCmp('start_timer_sabado').enable();
                                                    Ext.getCmp('final_timer_sabado').enable();
                                                    Ext.getCmp('combo_aula_sabado').enable();
                                                    Ext.getCmp('salvar_repeticiones').enable();
                                                }else{
                                                    Ext.getCmp('start_timer_sabado').disable();
                                                    Ext.getCmp('final_timer_sabado').disable();
                                                    Ext.getCmp('combo_aula_sabado').disable();
                                                    Ext.getCmp('start_timer_sabado').reset();
                                                    Ext.getCmp('final_timer_sabado').reset();
                                                    Ext.getCmp('combo_aula_sabado').reset();
                                                    
                                                }
                                                if(!checked&&!Ext.getCmp('check_lun').checked&&!Ext.getCmp('check_mar').checked&&!Ext.getCmp('check_mie').checked&&!Ext.getCmp('check_jue').checked&&!Ext.getCmp('check_vie').checked&&!Ext.getCmp('check_sab').checked&&!Ext.getCmp('check_dom').checked){
                                                    Ext.getCmp('salvar_repeticiones').disable();
                                                }
                                            }},boxLabel: 'Sábado', name: 'cb-auto-6',id:'check_sab'}
                                    ]
                                    },
                                    {
                                        listeners: {
                                            'select': function(cmb, rec, idx) {
                                                Ext.getCmp('final_timer_sabado').setMinValue(this.getValue());
                                            }
                                        },
                                        id:'start_timer_sabado',
                                        xtype:'timefield',
                                        fieldLabel: 'Hora de Inicio',
                                        allowBlank: false,
                                        name: 'hora_inicio_sabado',
                                        minValue: '7:00',
                                        maxValue: '22:25',
                                        format: 'H:i:s',
                                        increment: 5,
                                        emptyText: 'Vacío!!!',
                                        disabled:true,
                                        width: 80
                                    },
                                    {
                                        id: 'final_timer_sabado',
                                        xtype:'timefield',
                                        fieldLabel: 'Hora Fin',
                                        allowBlank: false,
                                        name: 'hora_fin_sabado',
                                        minValue: '7:30',
                                        maxValue: '22:30',
                                        format: 'H:i:s',
                                        increment: 5,
                                        emptyText: 'Vacío!!!',
                                        disabled: true,
                                        width: 80
                                    },
                                    
                                    {
                                        xtype:'combo',
                                        id:'combo_aula_sabado',
                                        hiddenName: 'nombre_aula_sabado',
                                        valueField: 'id',
                                        displayField: 'nombre',
                                        store: Ext.gest_horarios.staulas,
                                        autoCreate: false,
                                        //typeAhead: true,
                                        triggerAction: 'all',
                                        readOnly: false,
                                        mode: 'remote',
                                        //hideTrigger:true,
                                        minChars:1,
                                        //pageSize : 20,
                                        totalProperty : 'count',
                                        width: 80,
                                        anchor: '90%',
                                        align: 'rigth',
                                        fieldLabel: 'Aula',
                                        allowBlank: false,
                                        disabled: true,
                                        emptyText: 'Vacío!!!',
                                    }
                                    
                                ]
                            },
                            {   
                                columnWidth:.12,
                                layout:'form',
                                items: [
                                    {
                                    // Use the default, automatic layout to distribute the controls evenly
                                    // across a single row
                                    xtype: 'checkboxgroup',
                                    //columns: 1,
                                    //fieldLabel: 'Auto Layout',
                                    items: [

                                        {listeners: {'check': function (Checkbox, checked) {
                                                if(checked){
                                                    Ext.getCmp('start_timer_domingo').enable();
                                                    Ext.getCmp('final_timer_domingo').enable();
                                                    Ext.getCmp('combo_aula_domingo').enable();
                                                    Ext.getCmp('salvar_repeticiones').enable();
                                                }else{
                                                    Ext.getCmp('start_timer_domingo').disable();
                                                    Ext.getCmp('final_timer_domingo').disable();
                                                    Ext.getCmp('combo_aula_domingo').disable();
                                                    Ext.getCmp('start_timer_domingo').reset();
                                                    Ext.getCmp('final_timer_domingo').reset();
                                                    Ext.getCmp('combo_aula_domingo').reset();
                                                }
                                                if(!checked&&!Ext.getCmp('check_lun').checked&&!Ext.getCmp('check_mar').checked&&!Ext.getCmp('check_mie').checked&&!Ext.getCmp('check_jue').checked&&!Ext.getCmp('check_vie').checked&&!Ext.getCmp('check_sab').checked&&!Ext.getCmp('check_dom').checked){
                                                    Ext.getCmp('salvar_repeticiones').disable();
                                                }
                                            }},boxLabel: 'Domingo', name: 'cb-auto-7',id:'check_dom'}
                                    ]
                                    },
                                    {
                                        listeners: {
                                            'select': function(cmb, rec, idx) {
                                                Ext.getCmp('final_timer_domingo').setMinValue(this.getValue());
                                            }
                                        },
                                        id:'start_timer_domingo',
                                        xtype:'timefield',
                                        fieldLabel: 'Hora de Inicio',
                                        allowBlank: false,
                                        name: 'hora_inicio_domingo',
                                        minValue: '7:00',
                                        maxValue: '22:25',
                                        format: 'H:i:s',
                                        increment: 5,
                                        emptyText: 'Vacío!!!',
                                        disabled:true,
                                        width: 80
                                    },
                                    {
                                        id: 'final_timer_domingo',
                                        xtype:'timefield',
                                        fieldLabel: 'Hora Fin',
                                        allowBlank: false,
                                        name: 'hora_fin_domingo',
                                        minValue: '7:30',
                                        maxValue: '22:30',
                                        format: 'H:i:s',
                                        increment: 5,
                                        emptyText: 'Vacío!!!',
                                        disabled: true,
                                        width: 80
                                    },
                                    
                                    {
                                        xtype:'combo',
                                        id:'combo_aula_domingo',
                                        hiddenName: 'nombre_aula_domingo',
                                        valueField: 'id',
                                        displayField: 'nombre',
                                        store: Ext.gest_horarios.staulas,
                                        autoCreate: false,
                                        //typeAhead: true,
                                        triggerAction: 'all',
                                        readOnly: false,
                                        mode: 'remote',
                                        //hideTrigger:true,
                                        minChars:1,
                                        //pageSize : 20,
                                        listWidth: 120,
                                        totalProperty : 'count',
                                        width: 80,
                                        anchor: '90%',
                                        align: 'rigth',
                                        fieldLabel: 'Aula',
                                        allowBlank: false,
                                        disabled: true,
                                        emptyText: 'Vacío!!!',
                                    }
                                    
                                ]
                            },
                            { 
                                id: 'salvar_repeticiones',
                                xtype:'button',
                                text: 'Salvar repetición',
                                handler: function (btn) {
                                        if (Ext.gest_horarios.fp.getForm().isValid()) {
                                            Ext.gest_horarios.fp.el.mask('Por favor espere..', 'x-mask-loading');
                                            Ext.gest_horarios.fp.getForm().submit({
                                                url: 'horarios/create',
                                                params: {dia: Ext.getCmp('combo_dia').getRawValue(),periodo: Ext.getCmp('combo_periodo').getValue()},
                                                success: function (form, action) {
                                                    Ext.gest_horarios.fp.el.unmask();
                                                    var result = action.result;
                                                    if (result.success) {
                                                        Ext.MessageBox.alert('Completado..', action.result.msg);
                                                        Ext.getCmp('check_lun').reset();
                                                        Ext.getCmp('check_mar').reset();
                                                        Ext.getCmp('check_mie').reset();
                                                        Ext.getCmp('check_jue').reset();
                                                        Ext.getCmp('check_vie').reset();
                                                        Ext.getCmp('check_sab').reset();
                                                        Ext.getCmp('check_dom').reset();
                                                        Ext.gest_horarios.stHorarios.load();
                                                    }
                                                    else {
                                                        Ext.MessageBox.alert('No completado!!', action.result.msg);
                                                    }
                                                },
                                                failure: function (form, action) {
                                                    Ext.gest_horarios.fp.el.unmask();
                                                    var result = action.result;
                                                    if (result.success) {
                                                        Ext.MessageBox.alert('Completado..', action.result.msg);
                                                        Ext.getCmp('check_lun').reset();
                                                        Ext.getCmp('check_mar').reset();
                                                        Ext.getCmp('check_mie').reset();
                                                        Ext.getCmp('check_jue').reset();
                                                        Ext.getCmp('check_vie').reset();
                                                        Ext.getCmp('check_sab').reset();
                                                        Ext.getCmp('check_dom').reset();
                                                        Ext.gest_horarios.stHorarios.load();
                                                    }
                                                    else {
                                                        Ext.MessageBox.alert('No completado!!', action.result.msg);
                                                    }
                                                }
                                            });
                                        }
                            },
                                boxMaxWidth: 20,
                                disabled: true,
                                icon: '../../images/save.png'
                            }

                        ]
                    }
                ]
            }]
        }]       
    });
    // Fin del panel

    

    Ext.gest_horarios.myBtnHandler = function (btn) {
        if (btn.text == 'Adicionar') {
            if (!Ext.gest_horarios.win) {
                var title = 'Adicionar Turno';
                Ext.gest_horarios.win = new Ext.Window({
                    closeAction: 'hide',
                    id:'ventanadd',
                    modal:true,
                    title: title,
                    height: 232,
                    width: 450,
                    constrain: true,
                    items: [Ext.gest_horarios.fp],
                    buttons: [
                        {
                            text: 'Aceptar',
                            id:'aceptar_btn',
                            icon: '../../images/accept.png',
                            handler: function (btn) {
                                if (Ext.gest_horarios.fp.getForm().isValid()) {
                                    Ext.gest_horarios.fp.el.mask('Por favor espere..', 'x-mask-loading');
                                    Ext.gest_horarios.fp.getForm().submit({
                                        url: 'horarios/create',
                                        params: {dia: Ext.getCmp('combo_dia').getRawValue(),periodo: Ext.getCmp('combo_periodo').getValue()},
                                        success: function (form, action) {
                                            Ext.gest_horarios.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_horarios.win.hide();
                                                Ext.gest_horarios.stHorarios.load();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        },
                                        failure: function (form, action) {
                                            Ext.gest_horarios.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_horarios.win.hide();
                                                Ext.gest_horarios.stHorarios.load();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        }
                                    });
                                }
                            }
                        },
                        {
                            text: 'Cerrar',
                            icon: '../../images/no_validado.png',
                            handler: function (btn) {
                                Ext.gest_horarios.win.hide();
                            }
                        }
                    ]
                });
            } else {
                Ext.gest_horarios.fp.getForm().reset();
            }
            Ext.gest_horarios.fp.getForm().reset()
            Ext.gest_horarios.win.add(Ext.gest_horarios.fp);
            Ext.gest_horarios.win.doLayout();
            Ext.gest_horarios.win.show();
        }

        if (btn.text == 'Modificar') {

            if (!Ext.gest_horarios.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento para modificar');
                return false;
            }

            if (!Ext.gest_horarios.winmod) {
                var title = 'Modificar';
                Ext.gest_horarios.stdocentes.load({
                params: { 'id_materia': Ext.gest_horarios.sm.getSelected().get("id_materia")}
                });
                Ext.gest_horarios.winmod = new Ext.Window({
                    closeAction: 'hide',
                    modal:true,
                    title: title,
                    height: 232,
                    width: 450,
                    constrain: true,
                    items: [Ext.gest_horarios.fp],
                    buttons: [
                        {
                            text: 'Aceptar',
                            icon: '../../images/accept.png',
                            handler: function (btn) {
                                if (Ext.gest_horarios.fp.getForm().isValid()) {
                                    Ext.gest_horarios.fp.el.mask('Por favor espere..', 'x-mask-loading');
                                    Ext.gest_horarios.fp.getForm().submit({
                                        url: 'horarios/update',
                                        params: {id: Ext.gest_horarios.sm.getSelected().get("id")},
                                        success: function (form, action) {
                                            Ext.gest_horarios.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_horarios.winmod.hide();
                                                Ext.gest_horarios.stHorarios.load();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        },
                                        failure: function (form, action) {
                                            Ext.gest_horarios.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gest_horarios.winmod.hide();
                                                Ext.gest_horarios.stHorarios.load();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        }
                                    });
                                }
                            }
                        },
                        {
                            text: 'Cancelar',
                            icon: '../../images/no_validado.png',
                            handler: function (btn) {
                                Ext.gest_horarios.winmod.hide();
                            }
                        }
                    ]
                });
            } else {
                Ext.gest_horarios.fp.getForm().reset();
            }
            Ext.gest_horarios.fp.getForm().reset();
            Ext.gest_horarios.winmod.add(Ext.gest_horarios.fp);
            Ext.gest_horarios.winmod.doLayout();
            Ext.gest_horarios.winmod.show();
            Ext.gest_horarios.fp.getForm().loadRecord(Ext.gest_horarios.sm.getSelected());
        }

        if (btn.text == 'Eliminar') {
            if (!Ext.gest_horarios.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento para eliminar');
                return false;
            }

            Ext.MessageBox.show({
                title: 'Mensaje de confirmación',
                msg: '¿ Usted está seguro que desea eliminar este elemento ?',
                buttons: Ext.MessageBox.YESNOCANCEL,
                fn: function (btn) {
                    if (btn == 'yes') {
                        Ext.Ajax.request({
                            url: 'horarios/delete',
                            method: 'POST',
                            params: {id: Ext.gest_horarios.sm.getSelected().get("id")},
                            callback: function (options, success, response) {
                                responseData = Ext.decode(response.responseText);
                                if (responseData.success == true) {
                                    Ext.MessageBox.alert('Información', 'Se eliminó correctamente.'); 
                                    Ext.gest_horarios.stHorarios.load();
                                }
                            }
                        });
                    }
                },
                icon: Ext.MessageBox.QUESTION
            });

        }
        if (btn.text == 'Buscar') {
            Ext.gest_horarios.stHorarios.load({params: {start: 0,limit: 14}});
        }
    }

    Ext.gest_horarios.addBtn = new Ext.Button({
        listeners:{'click': function(Button,e){
                Ext.getCmp('repetir').setVisible(true);
        }},
        text: 'Adicionar',
        handler: Ext.gest_horarios.myBtnHandler,
        icon: '../../images/add.png'
    });

    Ext.gest_horarios.editBtn = new Ext.Button({
        listeners:{'click': function(Button,e){
                Ext.getCmp('repetir').setVisible(false);
        }},
        text: 'Modificar',
        handler: Ext.gest_horarios.myBtnHandler,
        icon: '../../images/bogus.png'
    });

    Ext.gest_horarios.editDel = new Ext.Button({
        text: 'Eliminar',
        handler: Ext.gest_horarios.myBtnHandler,
        icon: '../../images/delete.gif'
    });
    Ext.gest_horarios.buscar  = new Ext.Button({
        text: 'Buscar',
        handler: Ext.gest_horarios.myBtnHandler,
        icon: '../../images/lupa.png'
    });
    

    Ext.gest_horarios.tbFill = new Ext.Toolbar.Fill();

    Ext.gest_horarios.sm = new Ext.grid.RowSelectionModel({});

    Ext.gest_horarios.gpHorarios = new Ext.grid.GridPanel({
        frame: true,
        iconCls: 'icon-grid',
        //autoExpandColumn: 'expandir',
        loadMask: 'Cargando...',
        width: 450,
        store: Ext.gest_horarios.stHorarios,
        clicksToEdit: 1,
        sm: Ext.gest_horarios.sm,
        stripeRows: true,
        floating: false,
        columns: [
            new Ext.grid.RowNumberer(),
            {hidden: true, hideable: false, dataIndex: 'id'},
            {header: 'Materia', width: 250, dataIndex: 'nombre_materia'},
            {header: 'Docente', width: 200, dataIndex: 'nombre_docente'},
            {header: 'Hora Inicio', width: 100, dataIndex: 'hora_inicio'},
            {header: 'Hora Fin', width: 100, dataIndex: 'hora_fin'},
            {header: 'Aula', width: 80, dataIndex: 'nombre_aula'},
            {header: 'Edificio', width: 80, dataIndex: 'edificio'},
        ],
        tbar: [
            'Período: ',
            Ext.gest_horarios.combotrimestre,
            'Día: ',
            Ext.gest_horarios.dia,'-',
            Ext.gest_horarios.addBtn,'-',
            Ext.gest_horarios.editBtn,'-',
            Ext.gest_horarios.editDel,
            '->',
            {
              xtype:'textfield',
              fieldLabel: 'Nombre(s)',
              allowBlank: true,
              name: 'nombres',
              id:'buscar_usuario',
              enableKeyEvents:true,
              listeners:{
                         'keyup':function(textField, eventoObject){
                                    if(eventoObject.getCharCode() == 13){
                                         Ext.gest_horarios.stHorarios.load({params: {start: 0,limit: 14}});
                                    }
                         }
                        }      
            },
            Ext.gest_horarios.buscar
        ],
        bbar: new Ext.PagingToolbar({
            pageSize: 14,
            store: Ext.gest_horarios.stHorarios,
            displayInfo: true,
            displayMsg: 'Resultados {0} - {1} de {2}',
            emptyMsg: 'Ning&uacute;n resultado para mostrar.'
        })
    });

    Ext.gest_horarios.combotrimestre.on("afterrender", function(){
             if( Ext.gest_horarios.combotrimestre.getRawValue() == '')
             {
                 Ext.gest_horarios.addBtn.disable();
                 Ext.gest_horarios.editBtn.disable();
                 Ext.gest_horarios.editDel.disable();
                 Ext.gest_horarios.dia.disable();
             }
    });
    Ext.gest_horarios.combotrimestre.on("select", function(){
             if( Ext.gest_horarios.combotrimestre.getRawValue() == '')
             {
                 Ext.gest_horarios.addBtn.disable();
                 Ext.gest_horarios.editBtn.disable();
                 Ext.gest_horarios.editDel.disable();
                 Ext.gest_horarios.dia.disable();
             }
             else
             {
                //habilitar todo
                 Ext.gest_horarios.addBtn.enable();
                //Desabilitar las que tocan.
                 Ext.gest_horarios.editBtn.enable();
                 Ext.gest_horarios.editDel.enable();
                 Ext.gest_horarios.dia.enable();              

             }
    });

    Ext.gest_horarios.combotrimestre.on("keyup", function(){
             if( Ext.gest_horarios.combotrimestre.getRawValue() == '')
             {
                 Ext.gest_horarios.addBtn.disable();
                 Ext.gest_horarios.editBtn.disable();
                 Ext.gest_horarios.editDel.disable();
                 Ext.gest_horarios.dia.disable();
                 Ext.gest_horarios.stHorarios.removeAll();
             }
             else
             {
                //habilitar todo
                 Ext.gest_horarios.addBtn.enable();
                //Desabilitar las que tocan.
                 Ext.gest_horarios.editBtn.enable();
                 Ext.gest_horarios.editDel.enable();
                 Ext.gest_horarios.dia.enable();              

             }
    });



    new Ext.Viewport({
        layout: 'fit',     
        items: [
           Ext.gest_horarios.gpHorarios
        ]
    });
});