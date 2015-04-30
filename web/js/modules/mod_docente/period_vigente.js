Ext.onReady(function () {

    Ext.QuickTips.init();
    Ext.form.Field.prototype.msgTarget = 'side';

    Ext.ns('Ext.periodo_vigente');
    Ext.periodo_vigente.stPeriodo = new Ext.data.Store({
        url: 'pvigente/cargarsemestres',
        autoLoad: true,
        reader: new Ext.data.JsonReader({
            root: "data",
            totalProperty: "count",
            id: "id"
        }, [
            {name: 'id'},
            {name: 'id_periodo'},
            {name: 'tipo'},
            {name: 'nombre'}
        ])
    });

    Ext.periodo_vigente.stcomboperiodolectivo = new Ext.data.JsonStore({
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
    
    Ext.periodo_vigente.comboperiodolectivo = new Ext.form.ComboBox({
        store: Ext.periodo_vigente.stcomboperiodolectivo,
        id:'combo_m',
        forceSelection:true,
        displayField:'nombre',
        fieldLabel: 'Periodo',
        hideTrigger:true,
        valueField:'id',
        pageSize : 20,
        totalProperty : 'count',
        anchor: '95%',
        hiddenName:'nombre',
        hiddenValue: 'id',
        loadingText:'Buscando....',
        minChars:1,
        //triggerAction: 'all',
        //hideTrigger:false,
        minChars:1,
        pageSize : 20,
        totalProperty : 'count',
        mode: 'remote', 
        listeners:{'select':function(cmb, rec, idx){
            Ext.getCmp('id_p').setRawValue(Ext.getCmp('combo_m').getValue());
            }
        },  
    });

    Ext.periodo_vigente.stmodalidad = new Ext.data.Store({
        url: 'pvigente/modalidad',
        autoLoad: true,
        reader: new Ext.data.JsonReader({
            root: "data",
            id: "id"
        }, [
            {name: 'modalidad'}
        ])
    });

    Ext.periodo_vigente.combo_modalidad = new Ext.form.ComboBox({
        hiddenName: 'tipo',
        valueField: 'tipo',
        displayField: 'modalidad',
        store: Ext.periodo_vigente.stmodalidad,
        autoCreate: true,
        typeAhead: true,
        triggerAction: 'all',
        readOnly: false,
        forceSelection:true,
        mode: 'local',
        disabled: true,
        //width: 100,
        anchor: '85%',  
        allowBlank: false,    
        align: 'rigth',
        fieldLabel: 'Modalidad',
        emptyText:'Seleccione',
        
    });

    Ext.periodo_vigente.fpItems = [
        Ext.periodo_vigente.comboperiodolectivo,
        Ext.periodo_vigente.combo_modalidad,
        {
            fieldLabel: 'id',
            allowBlank: true,
            name: 'id',
            hidden: true
        },
        {
            fieldLabel: 'id_periodo',
            id: 'id_p',
            allowBlank: true,
            name: 'id_periodo',
            hidden: true
        }

    ];

    Ext.periodo_vigente.fp = new Ext.form.FormPanel({
        frame: true,
        bodyStyle: 'padding: 6px',
        labelWidth: 60,
        defaultType: 'textfield',
        defaults: {
            msgTarget: 'side',
            anchor: '-20'
        },
        items: Ext.periodo_vigente.fpItems
    });

    Ext.periodo_vigente.myBtnHandler = function (btn) {
        if (btn.text == 'Adicionar') {
            if (!Ext.periodo_vigente.win) {
                var title = 'Adicionar un semestre';
                Ext.periodo_vigente.win = new Ext.Window({
                    closeAction: 'hide',
                    title: title,
                    height: 110,
                    width: 300,
                    constrain: true,
                    items: [Ext.periodo_vigente.fp],
                    buttons: [
                        {
                            text: 'Aceptar',
                            handler: function (btn) {
                                if (Ext.periodo_vigente.fp.getForm().isValid()) {
                                    Ext.periodo_vigente.fp.el.mask('Por favor espere..', 'x-mask-loading');
                                    Ext.periodo_vigente.fp.getForm().submit({
                                        url: 'semestres/create',
                                        success: function (form, action) {
                                            Ext.periodo_vigente.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.periodo_vigente.win.hide();
                                                Ext.periodo_vigente.stPeriodo.load();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        },
                                        failure: function (form, action) {
                                            Ext.periodo_vigente.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.periodo_vigente.win.hide();
                                                Ext.periodo_vigente.stPeriodo.load();
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
                            handler: function (btn) {
                                Ext.periodo_vigente.win.hide();
                            }
                        }
                    ]
                });
            } else {
                Ext.periodo_vigente.fp.getForm().reset();
            }
            
            Ext.periodo_vigente.win.add(Ext.periodo_vigente.fp);
            Ext.periodo_vigente.win.doLayout();
            Ext.periodo_vigente.win.show();
        }

        if (btn.text == 'Modificar') {

            if (!Ext.periodo_vigente.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento para modificar');
                return false;
            }

            if (!Ext.periodo_vigente.winmod) {
                var title = 'Modificar periodo vigente';
                Ext.periodo_vigente.winmod = new Ext.Window({
                    closeAction: 'hide',
                    title: title,
                    height: 140,
                    width: 300,
                    constrain: true,
                    items: [Ext.periodo_vigente.fp],
                    buttons: [
                        {
                            text: 'Aceptar',
                            handler: function (btn) {
                                if (Ext.periodo_vigente.fp.getForm().isValid()) {
                                    Ext.periodo_vigente.fp.el.mask('Por favor espere..', 'x-mask-loading');
                                    Ext.periodo_vigente.fp.getForm().submit({
                                        url: 'pvigente/update',
                                        success: function (form, action) {
                                            Ext.periodo_vigente.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.periodo_vigente.winmod.hide();
                                                Ext.periodo_vigente.stPeriodo.load();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        },
                                        failure: function (form, action) {
                                            Ext.periodo_vigente.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.periodo_vigente.winmod.hide();
                                                Ext.periodo_vigente.stPeriodo.load();
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
                            handler: function (btn) {
                                Ext.periodo_vigente.winmod.hide();
                            }
                        }
                    ]
                });
            } else {
                Ext.periodo_vigente.fp.getForm().reset();
            }
            Ext.periodo_vigente.fp.getForm().reset();
            Ext.periodo_vigente.winmod.add(Ext.periodo_vigente.fp);
            Ext.periodo_vigente.winmod.doLayout();
            Ext.periodo_vigente.winmod.show();
            Ext.periodo_vigente.fp.getForm().loadRecord(Ext.periodo_vigente.sm.getSelected());
        }

        if (btn.text == 'Eliminar') {
            if (!Ext.periodo_vigente.sm.hasSelection()) {
                Ext.MessageBox.alert('Error !!', 'Seleccione un elemento para eliminar');
                return false;
            }

            Ext.MessageBox.show({
                title: 'Mensaje de confirmación',
                msg: '¿ Usted está seguro que desea eliminar el semestre ?',
                buttons: Ext.MessageBox.YESNOCANCEL,
                fn: function (btn) {
                    if (btn == 'yes') {
                        Ext.Ajax.request({
                            url: 'semestres/delete',
                            method: 'POST',
                            params: {id: Ext.periodo_vigente.sm.getSelected().get("id")},
                            callback: function (options, success, response) {
                                responseData = Ext.decode(response.responseText);
                                if (responseData.success == true) {
                                    Ext.MessageBox.alert('Información', 'EL semestre se eliminó correctamente.'); 
                                    Ext.periodo_vigente.stPeriodo.load();
                                }
                            }
                        });
                    }
                },
                icon: Ext.MessageBox.QUESTION
            });

        }
    }

    Ext.periodo_vigente.addBtn = new Ext.Button({
        text: 'Adicionar',
        handler: Ext.periodo_vigente.myBtnHandler,
        icon: '../../images/add.png'
    });

    Ext.periodo_vigente.editBtn = new Ext.Button({
        text: 'Modificar',
        handler: Ext.periodo_vigente.myBtnHandler,
        icon: '../../images/bogus.png'
    });

    Ext.periodo_vigente.editDel = new Ext.Button({
        text: 'Eliminar',
        handler: Ext.periodo_vigente.myBtnHandler,
        icon: '../../images/delete.gif'
    });

    Ext.periodo_vigente.tbFill = new Ext.Toolbar.Fill();

    Ext.periodo_vigente.sm = new Ext.grid.RowSelectionModel({});

    Ext.periodo_vigente.gpPeriodo = new Ext.grid.GridPanel({
        frame: true,
        iconCls: 'icon-grid',
        //autoExpandColumn: 'expandir',
        loadMask: 'Cargando...',
        width: 450,
        store: Ext.periodo_vigente.stPeriodo,
        clicksToEdit: 1,
        sm: Ext.periodo_vigente.sm,
        stripeRows: true,
        floating: false,
        columns: [
            new Ext.grid.RowNumberer(),
            {hidden: true, hideable: false, dataIndex: 'id_periodo'},
            {header: 'Nombre', width: 200, dataIndex: 'nombre'},
            {header: 'Tipo', width: 200, dataIndex: 'tipo'}
        ],
        tbar: [
            //Ext.periodo_vigente.addBtn, '-',
            Ext.periodo_vigente.editBtn, '-',
            //Ext.periodo_vigente.editDel
        ],
        bbar: new Ext.PagingToolbar({
            pageSize: 12,
            store: Ext.periodo_vigente.stPeriodo,
            displayInfo: true,
            displayMsg: 'Resultados {0} - {1} de {2}',
            emptyMsg: 'Ning&uacute;n resultado para mostrar.'
        })
    });

    new Ext.Viewport({
        layout: 'fit',
        items: [
            Ext.periodo_vigente.gpPeriodo
        ]
    });


});