Ext.onReady(function () {

	  Ext.QuickTips.init();
    Ext.form.Field.prototype.msgTarget = 'side';
    Ext.ns('Ext.m_evaluaciones_responder');

	//Store de evaluaciones
    Ext.m_evaluaciones_responder.stEvaluaciones = new Ext.data.Store({
        url: 'responder/cargar',
        autoLoad: true,
        reader: new Ext.data.JsonReader({
            root: "data",
            totalProperty: "count",
            id: "id"
        }, [
            {name: 'id'},
            {name: 'modalidad'},
            {name: 'tipo'},
            {name: 'id_grupo_origen'},
            {name: 'grupo_origen'},
            {name: 'id_periodo'},
            {name: 'fecha'},
            {name: 'descripcion'}, 
            {name: 'estado'},
            {name: 'titulo'},
            {name: 'nombre_periodo'}            
        ])
    });


    Ext.m_evaluaciones_responder.sm             = new Ext.grid.RowSelectionModel({});
    Ext.m_evaluaciones_responder.gpEvaluaciones = new Ext.grid.GridPanel({
        frame: false,       
        loadMask: 'Cargando...',
        //  width:'100%',
        //layout:'border',
        store: Ext.m_evaluaciones_responder.stEvaluaciones,
        sm: Ext.m_evaluaciones_responder.sm,
        stripeRows: true,
        floating: false,
        columns: [
            new Ext.grid.RowNumberer(),
            {hidden: true, hideable: false, dataIndex: 'id'},           
            {header: 'Titulo', width: 250, dataIndex: 'titulo'},  
            {header: 'Periodo', width: 200, dataIndex: 'nombre_periodo'},
            {header: 'Tipo', width: 120, dataIndex: 'tipo'},       
            {header: 'Fecha', width: 80, dataIndex: 'fecha'}          
        ],
        tbar: [
           /* Ext.m_evaluaciones_responder.docentes,
            Ext.m_evaluaciones_responder.id_asignatura*/
        ],
        bbar: new Ext.PagingToolbar({
            pageSize: 12,
            store: Ext.m_evaluaciones_responder.stEvaluaciones,
            displayInfo: true,
            displayMsg: 'Resultados {0} - {1} de {2}',
            emptyMsg: 'Ning&uacute;n resultado para mostrar.'
        })
    });

    //Combobox autofill

    Ext.m_evaluaciones_responder.stcombodocentes = new Ext.data.JsonStore({
        root: 'data',
        totalProperty : 'count',
        baseParams: {
            column : 'nombre_completo'
        },
        fields : [
        {
            name: 'nombre',
            mapping : 'nombre'
        },
        {
            name: 'id',
            mapping : 'id'
        },
        {
            name: 'nombre_completo',
            mapping : 'nombre_completo'
        }
        ],
        proxy : new Ext.data.ScriptTagProxy({
            url : '../mod_nomencladores/docentes/uploadsearch'
        })
    });
    
    Ext.m_evaluaciones_responder.docentes  = {
       xtype :'combo',
       fieldLabel: 'Docentes',
       forceSelection:true,
       displayField:'nombre_completo',
       hideTrigger:true,
       valueField:'id',
       pageSize : 20,
       totalProperty : 'count',
       anchor: '95%',
       hiddenName:'nombre_completo',
       hiddenValue: 'id',
       loadingText:'Buscando....',
       minChars:1,
       triggerAction:'nombre',
       store: Ext.m_evaluaciones_responder.stcombodocentes
    };

    Ext.m_evaluaciones_responder.stcomboasig = new Ext.data.JsonStore({
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
            url : '../mod_nomencladores/asignaturas/uploadsearch'
        })
    });
    
    Ext.m_evaluaciones_responder.id_asignatura  = {
       xtype :'combo',
       fieldLabel: 'Asignaturas',
       forceSelection:true,
       displayField:'nombre',
       hideTrigger:true,
       valueField:'id',
       pageSize : 20,
       totalProperty : 'count',
       anchor: '95%',
       hiddenName:'nombre',
       hiddenValue: 'id',
       loadingText:'Buscando....',
       minChars:1,
       triggerAction:'nombre',
       store: Ext.m_evaluaciones_responder.stcomboasig
    };

    //Fin autofill


    Ext.m_evaluaciones_responder.ventana_evaluaciones = new Ext.Window({
        title:'Seleccione una evaluación',
        closeAction: 'hide',
        height: 400,
        width: 700,
        layout:'fit',
        constrain: true,
        modal:true,
        items:[Ext.m_evaluaciones_responder.gpEvaluaciones],
        buttons:[{
            text:'Aceptar',
            icon: '../../images/accept.png',
            handler:function(){
                  if (!Ext.m_evaluaciones_responder.sm.hasSelection()) {
                        Ext.MessageBox.alert('Error!!', 'Seleccione una evaluación.');
                       return false;
                  }else{
                        Ext.m_evaluaciones_responder.ventana_evaluaciones.hide();
                        document.getElementById('iframe_evaluaciones_fill').src='responder/completar?id='+ Ext.m_evaluaciones_responder.sm.getSelected().get("id");
                  }
            }
        }]
    });


    Ext.m_evaluaciones_responder.panel = new Ext.Panel({
          html:'<iframe id="iframe_evaluaciones_fill"  style="width:100%; height: 100%; border:none;" ></iframe>'        
    });

    new Ext.Viewport({
        layout: 'fit',     
        items: [
           Ext.m_evaluaciones_responder.panel
        ]
    });


    
         setTimeout(function(){
                Ext.m_evaluaciones_responder.ventana_evaluaciones.show();
         },1000)
    

  

  


});