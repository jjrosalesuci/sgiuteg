function getservertime(){
    var xmlHttp;
    function srvTime(){
        try {
            //FF, Opera, Safari, Chrome
            xmlHttp = new XMLHttpRequest();
        }
        catch (err1) {
            //IE
            try {
                xmlHttp = new ActiveXObject('Msxml2.XMLHTTP');
            }
            catch (err2) {
                try {
                    xmlHttp = new ActiveXObject('Microsoft.XMLHTTP');
                }
                catch (eerr3) {
                    //AJAX not supported, use CPU time.
                    alert("AJAX not supported");
                }
            }
        }
        xmlHttp.open('HEAD',window.location.href.toString(),false);
        xmlHttp.setRequestHeader("Content-Type", "text/html");
        xmlHttp.send('');
        return xmlHttp.getResponseHeader("Date");
    }

    var st = srvTime();
    var date = new Date(st);
    return moment(date).format('H:mm:ss');
}

function getserverdate(){
    var xmlHttp;
    function srvTime(){
        try {
            //FF, Opera, Safari, Chrome
            xmlHttp = new XMLHttpRequest();
        }
        catch (err1) {
            //IE
            try {
                xmlHttp = new ActiveXObject('Msxml2.XMLHTTP');
            }
            catch (err2) {
                try {
                    xmlHttp = new ActiveXObject('Microsoft.XMLHTTP');
                }
                catch (eerr3) {
                    //AJAX not supported, use CPU time.
                    alert("AJAX not supported");
                }
            }
        }
        xmlHttp.open('HEAD',window.location.href.toString(),false);
        xmlHttp.setRequestHeader("Content-Type", "text/html");
        xmlHttp.send('');
        return xmlHttp.getResponseHeader("Date");
    }

    var st = srvTime();
    var date = new Date(st);
    return moment(date).format('YYYY[-]MM[-]DD');
}

window.onload=function(){
setInterval("getservertime()", 1000)
}

Ext.onReady(function(){

    Ext.QuickTips.init();
    Ext.form.Field.prototype.msgTarget = 'side';
    Ext.ns('Ext.asistencia');

    moment.locale('es');

    Ext.asistencia.stHorarios = new Ext.data.Store({
        url: 'asistencia/cargar',
        listeners: {'beforeload': function (store, objeto) {
            store.baseParams.dia    = moment().format('dddd');
        }},
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

    Ext.asistencia.stAulas = new Ext.data.Store({
        url: 'asistencia/getaulapost',
        /*listeners: {'beforeload': function (store, objeto) {
            store.baseParams.query = Ext.getCmp('buscar_usuario').getRawValue();
        }},*/
        //autoLoad: true,
        reader: new Ext.data.JsonReader({
            root: "data",
            //totalProperty: "count",
            id: "id"
        }, [
            {name: 'id'},
            {name: 'nombre'},
            {name: 'edificio'},
            {name: 'parlantes'},
            {name: 'infocus'},
            {name: 'pc'},
            {name: 'monitor'},
            {name: 'teclado'},
            {name: 'mouse'}
        ])
    });

    Ext.asistencia.stdocentes = new Ext.data.JsonStore({
        root: 'data',
        fields : [
        {
            name: 'nombre_completo',
            mapping : 'nombre_completo'
        },
        {
            name: 'id',
            mapping : 'id'
        }
        ],
        proxy : new Ext.data.ScriptTagProxy({
            url : '../mod_nomencladores/docentes/uploadsearch'
        })
    });

    Ext.asistencia.combodocentes = new Ext.form.ComboBox({
            listeners: {
                'select': function(cmb, rec, idx) {
                Ext.asistencia.stHorarios.load({
                params: { 'id_docente': this.getValue("id")}
                });

                }
            },
            id: 'combo_docentes',
            submitValue: true,
            hiddenName: 'id',
            valueField: 'id',
            displayField: 'nombre_completo',
            store: Ext.asistencia.stdocentes,
            autoCreate: false,
            //typeAhead: true,
            //triggerAction: 'all',
            minChars:1,
            hideTrigger: true,
            pageSize : 20,
            totalProperty : 'count',
            disabled: true,
            readOnly: false,
            mode: 'remote',
            width: 200,
            anchor: '90%',       
            align: 'rigth',
            fieldLabel: 'Docente',
            allowBlank: true,
            //emptyText: 'Este campo está vacío!!!',
    });


    Ext.asistencia.tbFill = new Ext.Toolbar.Fill();

    Ext.asistencia.sm = new Ext.grid.RowSelectionModel({});

    Ext.asistencia.gpHorarios = new Ext.grid.GridPanel({
        frame: true,
        iconCls: 'icon-grid',
        //autoExpandColumn: 'expandir',
        loadMask: 'Cargando...',
        //title:'Horario del día',
        icon: '../../images/alarm16x16.png',
        width: 450,
        store: Ext.asistencia.stHorarios,
        //clicksToEdit: 2,
        sm: Ext.asistencia.sm,
        region: 'center',
        stripeRows: true,
        floating: false,
        columns: [
            new Ext.grid.RowNumberer(),
            {hidden: true, hideable: false, dataIndex: 'id'},
            {hidden: true, hideable: false, dataIndex: 'id_aula'},
            {header: 'Materia', width: 200, dataIndex: 'nombre_materia'},
            {header: 'Hora Inicio', width: 80, dataIndex: 'hora_inicio'},
            {header: 'Hora Fin', width: 80, dataIndex: 'hora_fin'},
            {header: 'Aula', width: 70, dataIndex: 'nombre_aula'},
            {header: 'Edificio', width: 70, dataIndex: 'edificio'},
            {
                xtype: 'actioncolumn',
                id: 'column',
                width: 60,
                css: 'text-align: center',
                header: 'Iniciar',
                disabled: true,
                items: [{
                    icon   :'../../images/iniciar.png',  // Use a URL in the icon config
                    id: 'iniciar',
                    tooltip:'Iniciar Materia',
                    align:'center',
                    handler: function(grid, rowIndex, colIndex) {
                            var rec = grid.getStore().getAt(rowIndex);
                            Ext.MessageBox.show({
                                title: 'Mensaje de confirmación',
                                msg: '¿ Usted está seguro que desea iniciar esta materia ?',
                                buttons: Ext.MessageBox.YESNO,
                                icon: Ext.MessageBox.QUESTION,
                                fn: function(btn){
                                    if(btn == 'yes'){
                                        Ext.Ajax.request({
                                            url: 'asistencia/iniciar',
                                            method: 'POST',
                                            params: {id_turno: rec.get('id'),
                                                    hora_inicio: getservertime(),
                                                    fecha: getserverdate(),
                                                    suplantar: Ext.getCmp('suplantar').getValue()
                                            },
                                            callback: function (options, success, response) {
                                                responseData = Ext.decode(response.responseText);
                                                if (responseData.success == true) {
                                                    Ext.MessageBox.alert('Información', 'El turno se inició correctamente.\n Fecha: '+getserverdate()+' Hora:'+getservertime());
                                                }
                                                else if(responseData.success == 'docente'){
                                                    Ext.MessageBox.alert('Error!!!', 'Usted no puede suplantarse.');
                                                }
                                                else if(responseData.success == 'todavia'){
                                                    Ext.MessageBox.alert('Error!!!', 'Debe esperar 30 minutos antes para poder iniciar.');
                                                }
                                                else if(responseData.success == 'concluido'){
                                                    Ext.MessageBox.alert('Error!!!', 'Fuera del limite de hora para iniciar. ');
                                                }
                                                else {
                                                    Ext.MessageBox.alert('Error!!!', 'Usted ya ha iniciado el turno de clase!!');
                                                }
                                            }
                                        });
                                    }
                                }
                            });  
                    }
                }]
            },
            {
                xtype: 'actioncolumn',
                width: 60,
                css: 'align: center',
                header: 'Finalizar',
                items: [{
                    icon   :'../../images/finalizar.png',  // Use a URL in the icon config
                    tooltip:'Finalizar Materia',
                    handler: function(grid, rowIndex, colIndex) {
                            var rec = grid.getStore().getAt(rowIndex);
                            Ext.MessageBox.show({
                                title: 'Mensaje de confirmación',
                                msg: '¿ Usted está seguro que desea finalizar esta materia ?',
                                buttons: Ext.MessageBox.YESNO,
                                icon: Ext.MessageBox.QUESTION,
                                fn: function(btn){
                                    if(btn == 'yes'){
                                        Ext.Ajax.request({
                                            url: 'asistencia/finalizar',
                                            method: 'POST',
                                            params: {id_turno: rec.get('id'),
                                                    hora_fin: getservertime(),
                                                    fecha: getserverdate(),
                                                    suplantar: Ext.getCmp('suplantar').getValue()
                                            },
                                            callback: function (options, success, response) {
                                                responseData = Ext.decode(response.responseText);
                                                if (responseData.success == true) {
                                                    Ext.MessageBox.alert('Información', 'El turno fue finalizado correctamente.\n Fecha: '+getserverdate()+' Hora:'+getservertime()); 
                                                }
                                                else if(responseData.success == false){
                                                    Ext.MessageBox.alert('Error!!!', 'Usted ya ha finalizado el turno de clase!!!!');
                                                }
                                                else if(responseData.success == 'docente'){
                                                    Ext.MessageBox.alert('Error!!!', 'Usted no puede suplantarse.');
                                                }
                                                else if(responseData.success == 'antes_hora'){
                                                    Ext.MessageBox.alert('Error!!!', 'El turno no puede ser finalizado en este momento.');
                                                }
                                                else if(responseData.success == 'concluido'){
                                                    Ext.MessageBox.alert('Error!!!', 'Fuera del limite de hora para finalizar.');
                                                }
                                                else if(responseData.success == 'noiniciado'){
                                                    Ext.MessageBox.alert('Error!!!', 'El turno no ha sido iniciado!!');
                                                }
                                            }
                                        });
                                    }
                                }
                            });    
                        }
                }]
            },
            {
                xtype: 'actioncolumn',
                width: 80,
                css: 'align: center',
                header: 'Detalle Aula',
                items: [{
                    icon   :'../../images/setup.png',  // Use a URL in the icon config
                    tooltip:'Ver tecnología del aula',
                    handler: function(grid, rowIndex, colIndex) {
                            var rec = grid.getStore().getAt(rowIndex);
                            Ext.asistencia.ventana.show();
                            Ext.getCmp('ventana').setTitle('Tecnologia del '+rec.get('nombre_aula'));
                            Ext.getCmp('myGridID').getStore().load({params: {id_aula:rec.get('id_aula')}});
                                
                        }
                }]
            }
        ],
        tbar: [
            {
                listeners: {'check': function (Checkbox, checked) {
                        if(checked){
                            Ext.asistencia.combodocentes.enable();
                        }else{
                            Ext.asistencia.combodocentes.disable();
                            Ext.asistencia.stHorarios.load();
                            Ext.asistencia.combodocentes.reset();
                        }
                    }
                },
                id: 'suplantar',
                boxLabel:'Suplantar',
                padding: 0,
                align: 'center',
                xtype:'checkbox'
            },'-',
            Ext.asistencia.combodocentes,
            {
            listeners: {
                render: {
                    fn: function(){
                        // Add a class to the parent TD of each text item so we can give them some custom inset box
                        // styling. Also, since we are using a greedy spacer, we have to add a block level element
                        // into each text TD in order to give them a fixed width (TextItems are spans).  Insert a
                        // spacer div into each TD using createChild() so that we can give it a width in CSS.

                        // Kick off the clock timer that updates the clock el every second:
                        Ext.TaskMgr.start({
                            run: function(){
                                var xmlHttp;
                                function srvTime(){
                                    try {
                                        //FF, Opera, Safari, Chrome
                                        xmlHttp = new XMLHttpRequest();
                                    }
                                    catch (err1) {
                                        //IE
                                        try {
                                            xmlHttp = new ActiveXObject('Msxml2.XMLHTTP');
                                        }
                                        catch (err2) {
                                            try {
                                                xmlHttp = new ActiveXObject('Microsoft.XMLHTTP');
                                            }
                                            catch (eerr3) {
                                                //AJAX not supported, use CPU time.
                                                alert("AJAX not supported");
                                            }
                                        }
                                    }
                                    xmlHttp.open('HEAD',window.location.href.toString(),false);
                                    xmlHttp.setRequestHeader("Content-Type", "text/html");
                                    xmlHttp.send('');
                                    return xmlHttp.getResponseHeader("Date");
                                }

                                var st = srvTime();
                                var date = new Date(st);
                                Ext.getCmp('server').setText('Fecha del servidor: '+date);
                            },
                            interval: 1000
                        });
                    }
                }
            }
            },
            {
                xtype:'button',
                id:'server',
            }
        ]
    });

    function inventario(val){
        if (val == 'on') {
            return '<img src="../../images/validado.png" />';
        } else {
            return '<img src="../../images/Remove_16x16.png" />';
        }
        return val;
    }

    Ext.asistencia.gpAulas = new Ext.grid.GridPanel({
        frame: true,
        iconCls: 'icon-grid',
        //autoExpandColumn: 'expandir',
        loadMask: 'Cargando...',
        id:'myGridID',
        width: 450,
        store: Ext.asistencia.stAulas,
        clicksToEdit: 1,
        //sm: Ext.gest_aulas.sm,
        stripeRows: true,
        floating: false,
        columns: [
            new Ext.grid.RowNumberer(),
            {hidden: true, hideable: false, dataIndex: 'id'},
            {header: 'Parlantes', width: 80, dataIndex: 'parlantes', renderer: inventario},
            {header: 'Infocus', width: 80, dataIndex: 'infocus', renderer: inventario},
            {header: 'PC', width: 80, dataIndex: 'pc', renderer: inventario},
            {header: 'Monitor', width: 80, dataIndex: 'monitor', renderer: inventario},
            {header: 'Teclado', width: 80, dataIndex: 'teclado', renderer: inventario},
            {header: 'Mouse', width: 80, dataIndex: 'mouse', renderer: inventario}
        ]
    });

    Ext.asistencia.ventana = new Ext.Window({
        title:'Tecnologia del aula',
        closeAction: 'hide',
        id:'ventana',
        height: 150,
        width: 550,
        layout:'fit',
        constrain: true,
        modal:true,
        items:[
            Ext.asistencia.gpAulas
        ]
    });

    new Ext.Viewport({
        layout: 'fit',
        plain:true,    
        items: [
                Ext.asistencia.gpHorarios
        ]
    });
});

