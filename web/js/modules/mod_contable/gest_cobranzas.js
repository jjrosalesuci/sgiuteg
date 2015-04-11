Ext.onReady(function () {

    Ext.QuickTips.init();
    Ext.form.Field.prototype.msgTarget = 'side';
    Ext.ns('Ext.gest_cobranzas');



    Ext.gest_cobranzas.fechabar = new Ext.form.DateField({
                fieldLabel: 'Buscar por  fecha',
                id: 'm_contable_dp_fecha',
                format: 'Y-m-d',
                name:'fecha_reporte',
                value: moment().format('YYYY[-]MM[-]DD'),
                allowBlank: false
    });

    Ext.gest_cobranzas.addBtn = new Ext.Button({
        text: 'GUARDAR',
        handler: function (btn) {
                if (Ext.gest_cobranzas.fp1.getForm().isValid()&&Ext.gest_cobranzas.fp2.getForm().isValid()&&Ext.gest_cobranzas.fp3.getForm().isValid()&&Ext.gest_cobranzas.fp4.getForm().isValid()&&Ext.gest_cobranzas.fp5.getForm().isValid()&&Ext.gest_cobranzas.fechabar.getValue()!='') {
                    Ext.gest_cobranzas.fp1.el.mask('Por favor espere..', 'x-mask-loading');
                    Ext.gest_cobranzas.fp1.getForm().submit({
                    url: 'cob/create',
                    params:{fp1:'true',fecha_reporte:Ext.getCmp('m_contable_dp_fecha').getValue()},
                    success: function (form, action) {
                        Ext.gest_cobranzas.fp1.el.unmask();
                        /*var result = action.result;
                        if (result.success) {
                            Ext.MessageBox.alert('Completado..', action.result.msg);
                        }
                        else {
                            Ext.MessageBox.alert('No completado!!', action.result.msg);
                        }*/
                    },
                    failure: function (form, action) {
                        Ext.gest_cobranzas.fp1.el.unmask();
                        /*var result = action.result;
                        if (result.success) {
                            Ext.MessageBox.alert('Completado..', action.result.msg);
                        }
                        else {
                             Ext.MessageBox.alert('No completado!!', action.result.msg);
                        }*/
                    }
                    });
                }else{
                    Ext.MessageBox.alert('No Completado', 'Faltan campos por llenar!!!');
                }
                if (Ext.gest_cobranzas.fp1.getForm().isValid()&&Ext.gest_cobranzas.fp2.getForm().isValid()&&Ext.gest_cobranzas.fp3.getForm().isValid()&&Ext.gest_cobranzas.fp4.getForm().isValid()&&Ext.gest_cobranzas.fp5.getForm().isValid()&&Ext.gest_cobranzas.fechabar.getValue()!='') {
                    Ext.gest_cobranzas.fp2.el.mask('Por favor espere..', 'x-mask-loading');
                    Ext.gest_cobranzas.fp2.getForm().submit({
                    url: 'cob/create',
                    params:{fp2:'true',fecha_reporte:Ext.getCmp('m_contable_dp_fecha').getValue()},
                    success: function (form, action) {
                        Ext.gest_cobranzas.fp2.el.unmask();
                        /*var result = action.result;
                        if (result.success) {
                            Ext.MessageBox.alert('Completado..', action.result.msg);
                        }
                        else {
                            Ext.MessageBox.alert('No completado!!', action.result.msg);
                        }*/
                    },
                    failure: function (form, action) {
                        Ext.gest_cobranzas.fp2.el.unmask();
                        /*var result = action.result;
                        if (result.success) {
                            Ext.MessageBox.alert('Completado..', action.result.msg);
                        }
                        else {
                             Ext.MessageBox.alert('No completado!!', action.result.msg);
                        }*/
                    }
                    });
                }
                if (Ext.gest_cobranzas.fp1.getForm().isValid()&&Ext.gest_cobranzas.fp2.getForm().isValid()&&Ext.gest_cobranzas.fp3.getForm().isValid()&&Ext.gest_cobranzas.fp4.getForm().isValid()&&Ext.gest_cobranzas.fp5.getForm().isValid()&&Ext.gest_cobranzas.fechabar.getValue()!='') {
                    Ext.gest_cobranzas.fp3.el.mask('Por favor espere..', 'x-mask-loading');
                    Ext.gest_cobranzas.fp3.getForm().submit({
                    url: 'cob/create',
                    params:{fp3:'true',fecha_reporte:Ext.getCmp('m_contable_dp_fecha').getValue()},
                    success: function (form, action) {
                        Ext.gest_cobranzas.fp3.el.unmask();
                        /*var result = action.result;
                        if (result.success) {
                            Ext.MessageBox.alert('Completado..', action.result.msg);
                        }
                        else {
                            Ext.MessageBox.alert('No completado!!', action.result.msg);
                        }*/
                    },
                    failure: function (form, action) {
                        Ext.gest_cobranzas.fp3.el.unmask();
                        /*var result = action.result;
                        if (result.success) {
                            Ext.MessageBox.alert('Completado..', action.result.msg);
                        }
                        else {
                             Ext.MessageBox.alert('No completado!!', action.result.msg);
                        }*/
                    }
                    });
                }
                if (Ext.gest_cobranzas.fp1.getForm().isValid()&&Ext.gest_cobranzas.fp2.getForm().isValid()&&Ext.gest_cobranzas.fp3.getForm().isValid()&&Ext.gest_cobranzas.fp4.getForm().isValid()&&Ext.gest_cobranzas.fp5.getForm().isValid()&&Ext.gest_cobranzas.fechabar.getValue()!='') {
                    Ext.gest_cobranzas.fp4.el.mask('Por favor espere..', 'x-mask-loading');
                    Ext.gest_cobranzas.fp4.getForm().submit({
                    url: 'cob/create',
                    params:{fp4:'true',fecha_reporte:Ext.getCmp('m_contable_dp_fecha').getValue()},
                    success: function (form, action) {
                        Ext.gest_cobranzas.fp4.el.unmask();
                        /*var result = action.result;
                        if (result.success) {
                            //Ext.MessageBox.alert('Completado..', action.result.msg);
                        }
                        else {
                            Ext.MessageBox.alert('No completado!!', action.result.msg);
                        }*/
                    },
                    failure: function (form, action) {
                        Ext.gest_cobranzas.fp4.el.unmask();
                        var result = action.result;
                        if (result.success) {
                            //Ext.MessageBox.alert('Completado..', action.result.msg);
                        }
                        else {
                             Ext.MessageBox.alert('No completado!!', action.result.msg);
                        }
                    }
                    });
                }
                if (Ext.gest_cobranzas.fp1.getForm().isValid()&&Ext.gest_cobranzas.fp2.getForm().isValid()&&Ext.gest_cobranzas.fp3.getForm().isValid()&&Ext.gest_cobranzas.fp4.getForm().isValid()&&Ext.gest_cobranzas.fp5.getForm().isValid()&&Ext.gest_cobranzas.fechabar.getValue()!='') {
                    Ext.gest_cobranzas.fp5.el.mask('Por favor espere..', 'x-mask-loading');
                    Ext.gest_cobranzas.fp5.getForm().submit({
                    url: 'cob/create',
                    params:{fp5:'true',fecha_reporte:Ext.getCmp('m_contable_dp_fecha').getValue()},
                    success: function (form, action) {
                        Ext.gest_cobranzas.fp5.el.unmask();
                        var result = action.result;
                        if (result.success) {
                            //Ext.MessageBox.alert('Completado..', action.result.msg);
                            Ext.MessageBox.show({
                                title: 'Mensaje de confirmación',
                                msg: '¿ Desea notificar ahora ?',
                                buttons: Ext.MessageBox.YESNO,
                                fn: function (btn) {
                                    if (btn == 'yes') {
                                        Ext.Ajax.request({
                                            url: 'cob/notificar',
                                            method: 'POST',
                                            params: {fecha:Ext.getCmp('m_contable_dp_fecha').getValue()},
                                            callback: function (options, success, response) {
                                                responseData = Ext.decode(response.responseText);
                                                if (responseData.success == true) {
                                                    Ext.MessageBox.alert('Información', 'Se notificó correctamente.'); 
                                                }
                                            }
                                        });
                                    }
                                },
                                icon: Ext.MessageBox.QUESTION
                            });
                        }
                        else {
                            Ext.MessageBox.alert('No completado!!', action.result.msg);
                        }
                    },
                    failure: function (form, action) {
                        Ext.gest_cobranzas.fp5.el.unmask();
                        var result = action.result;
                        if (result.success) {
                            //Ext.MessageBox.alert('Completado..', action.result.msg);
                            Ext.MessageBox.show({
                                title: 'Mensaje de confirmación',
                                msg: '¿ Desea notificar ahora ?',
                                buttons: Ext.MessageBox.YESNO,
                                fn: function (btn) {
                                    if (btn == 'yes') {
                                        Ext.Ajax.request({
                                            url: 'cob/notificar',
                                            method: 'POST',
                                            params: {fecha:Ext.getCmp('m_contable_dp_fecha').getValue()},
                                            callback: function (options, success, response) {
                                                responseData = Ext.decode(response.responseText);
                                                if (responseData.success == true) {
                                                    Ext.MessageBox.alert('Información', 'Se notificó correctamente.'); 
                                                }
                                            }
                                        });
                                    }
                                },
                                icon: Ext.MessageBox.QUESTION
                            });
                        }
                        else {
                             Ext.MessageBox.alert('No completado!!', action.result.msg);
                        }
                    }
                    });
                }

        },
        icon: '../../images/save.png'
    });


    Ext.gest_cobranzas.fp1 = new Ext.form.FormPanel({
        labelAlign: 'top',
        frame:true,
        bodyStyle:'padding:5px 5px 0',
        //width: 650,
        anchor:'100%',
        height: 411,
        items: [{
            layout:'column',
            items:[
            {
                columnWidth:.5,
                layout: 'form',
                items: [
                {
                    xtype:'textfield',
                    fieldLabel: 'DETALLE',
                    allowBlank: false,
                    name: 'fecha_descripcion',
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    id:'tpp',
                    fieldLabel: 'TOTAL DE PREGRADO PRESENCIAL',
                    allowBlank: false,
                    name: 't_p_p',
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    id:'tpsp',
                    fieldLabel: 'TOTAL DE PREGRADO SEMIPRESENCIAL',
                    allowBlank: false,
                    name: 't_p_sp',
                    anchor:'90%'
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
                columnWidth:.5,
                layout: 'form',
                items: [
                {
                    xtype:'textfield',
                    id:'tp',
                    fieldLabel: 'TOTAL DE POSTGRADO',
                    allowBlank: false,
                    name: 't_postgrado',
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    id:'toi',
                    fieldLabel: 'TOTAL OTROS INGRESOS',
                    allowBlank: false,
                    name: 't_o_ingres',
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    id:'totaltab1',
                    listeners:{'focus': function(){
                        var suma = (parseFloat(Ext.getCmp('tpp').getRawValue())+parseFloat(Ext.getCmp('tpsp').getRawValue())+parseFloat(Ext.getCmp('tp').getRawValue())+parseFloat(Ext.getCmp('toi').getRawValue()));
                        Ext.getCmp('totaltab1').setValue(suma);
                    }
                    },
                    fieldLabel: 'TOTAL GENERAL',
                    allowBlank: false,
                    name: 'total_general',
                    anchor:'90%'
                }
                
                ]
            }
            
            ]
        }]
    });
    
    Ext.gest_cobranzas.afilamas = new Ext.Button({
        listeners:{'click': function(Button,e){
                Ext.getCmp('date_1').setVisible(true);
                Ext.getCmp('text_1').setVisible(true);
                Ext.gest_cobranzas.afilamas.setVisible(false);
                Ext.gest_cobranzas.afilamas1.setVisible(true);
                Ext.gest_cobranzas.afilaless1.setVisible(true);
        }},
        //scale: 'medium',
        icon: '../../images/mas.png'
    });

    Ext.gest_cobranzas.afilaless1 = new Ext.Button({
        listeners:{'click': function(Button,e){
                Ext.getCmp('date_1').setVisible(false);
                Ext.getCmp('text_1').setVisible(false);
                Ext.gest_cobranzas.afilamas.setVisible(true);
                Ext.gest_cobranzas.afilaless1.setVisible(false);
                Ext.gest_cobranzas.afilamas1.setVisible(false);
        }},
        hidden:true,
        icon: '../../images/menos.png'
    });

    Ext.gest_cobranzas.afilamas1 = new Ext.Button({
        listeners:{'click': function(Button,e){
                Ext.getCmp('date_2').setVisible(true);
                Ext.getCmp('text_2').setVisible(true);
                Ext.gest_cobranzas.afilaless1.setVisible(false);
                Ext.gest_cobranzas.afilamas1.setVisible(false);
                Ext.gest_cobranzas.afilaless2.setVisible(true);
                Ext.gest_cobranzas.afilamas2.setVisible(true);
        }},
        hidden:true,
        icon: '../../images/mas.png'
    });

    Ext.gest_cobranzas.afilaless2 = new Ext.Button({
        listeners:{'click': function(Button,e){
                Ext.getCmp('date_2').setVisible(false);
                Ext.getCmp('text_2').setVisible(false);
                Ext.gest_cobranzas.afilaless1.setVisible(true);
                Ext.gest_cobranzas.afilamas1.setVisible(true);
                Ext.gest_cobranzas.afilaless2.setVisible(false);
                Ext.gest_cobranzas.afilamas2.setVisible(false);
        }},
        hidden:true,
        icon: '../../images/menos.png'
    });

    Ext.gest_cobranzas.afilamas2 = new Ext.Button({
        listeners:{'click': function(Button,e){
                Ext.getCmp('date_3').setVisible(true);
                Ext.getCmp('text_3').setVisible(true);
                Ext.gest_cobranzas.afilaless2.setVisible(false);
                Ext.gest_cobranzas.afilamas2.setVisible(false);
                Ext.gest_cobranzas.afilaless3.setVisible(true);
        }},
        hidden:true,
        icon: '../../images/mas.png'
    });

    Ext.gest_cobranzas.afilaless3 = new Ext.Button({
        listeners:{'click': function(Button,e){
                Ext.getCmp('date_3').setVisible(false);
                Ext.getCmp('text_3').setVisible(false);
                Ext.gest_cobranzas.afilaless2.setVisible(true);
                Ext.gest_cobranzas.afilamas2.setVisible(true);
                Ext.gest_cobranzas.afilaless3.setVisible(false);
        }},
        hidden:true,
        icon: '../../images/menos.png'
    });

    //////////////////////////
    //////////////////////////

    Ext.gest_cobranzas.afilamasfp = new Ext.Button({
        listeners:{'click': function(Button1,e1){
                Ext.getCmp('textfp_11').setVisible(true);
                Ext.getCmp('textfp_21').setVisible(true);
                Ext.getCmp('textfp_31').setVisible(true);
                Ext.getCmp('textfp_41').setVisible(true);
                Ext.gest_cobranzas.afilamasfp.setVisible(false);
                Ext.gest_cobranzas.afilamasfp1.setVisible(true);
                Ext.gest_cobranzas.afilalessfp1.setVisible(true);
        }},
        //scale: 'medium',
        icon: '../../images/mas.png'
    });

    Ext.gest_cobranzas.afilalessfp1 = new Ext.Button({
        listeners:{'click': function(Button1,e1){
                Ext.getCmp('textfp_11').setVisible(false);
                Ext.getCmp('textfp_21').setVisible(false);
                Ext.getCmp('textfp_31').setVisible(false);
                Ext.getCmp('textfp_41').setVisible(false);
                Ext.gest_cobranzas.afilamasfp.setVisible(true);
                Ext.gest_cobranzas.afilalessfp1.setVisible(false);
                Ext.gest_cobranzas.afilamasfp1.setVisible(false);
        }},
        hidden:true,
        icon: '../../images/menos.png'
    });

    Ext.gest_cobranzas.afilamasfp1 = new Ext.Button({
        listeners:{'click': function(Button1,e1){
                Ext.getCmp('textfp_12').setVisible(true);
                Ext.getCmp('textfp_22').setVisible(true);
                Ext.getCmp('textfp_32').setVisible(true);
                Ext.getCmp('textfp_42').setVisible(true);
                Ext.gest_cobranzas.afilalessfp1.setVisible(false);
                Ext.gest_cobranzas.afilamasfp1.setVisible(false);
                Ext.gest_cobranzas.afilalessfp2.setVisible(true);
                Ext.gest_cobranzas.afilamasfp2.setVisible(true);
        }},
        hidden:true,
        icon: '../../images/mas.png'
    });

    Ext.gest_cobranzas.afilalessfp2 = new Ext.Button({
        listeners:{'click': function(Button1,e1){
                Ext.getCmp('textfp_12').setVisible(false);
                Ext.getCmp('textfp_22').setVisible(false);
                Ext.getCmp('textfp_32').setVisible(false);
                Ext.getCmp('textfp_42').setVisible(false);
                Ext.gest_cobranzas.afilalessfp1.setVisible(true);
                Ext.gest_cobranzas.afilamasfp1.setVisible(true);
                Ext.gest_cobranzas.afilalessfp2.setVisible(false);
                Ext.gest_cobranzas.afilamasfp2.setVisible(false);
        }},
        hidden:true,
        icon: '../../images/menos.png'
    });

    Ext.gest_cobranzas.afilamasfp2 = new Ext.Button({
        listeners:{'click': function(Button1,e1){
                Ext.getCmp('textfp_13').setVisible(true);
                Ext.getCmp('textfp_23').setVisible(true);
                Ext.getCmp('textfp_33').setVisible(true);
                Ext.getCmp('textfp_43').setVisible(true);
                Ext.gest_cobranzas.afilalessfp2.setVisible(false);
                Ext.gest_cobranzas.afilamasfp2.setVisible(false);
                Ext.gest_cobranzas.afilalessfp3.setVisible(true);
        }},
        hidden:true,
        icon: '../../images/mas.png'
    });

    Ext.gest_cobranzas.afilalessfp3 = new Ext.Button({
        listeners:{'click': function(Button1,e1){
                Ext.getCmp('textfp_13').setVisible(false);
                Ext.getCmp('textfp_23').setVisible(false);
                Ext.getCmp('textfp_33').setVisible(false);
                Ext.getCmp('textfp_43').setVisible(false);
                Ext.gest_cobranzas.afilalessfp2.setVisible(true);
                Ext.gest_cobranzas.afilamasfp2.setVisible(true);
                Ext.gest_cobranzas.afilalessfp3.setVisible(false);
        }},
        hidden:true,
        icon: '../../images/menos.png'
    });

    ///////////////////////////
    ///////////////////////////

    Ext.gest_cobranzas.fp2 = new Ext.form.FormPanel({
        labelAlign: 'top',
        frame:true,
        bodyStyle:'padding:5px 5px 0;overflowY: auto',
        //width: 884,
        anchor:'100%',
        height: 411,
        items: [{
            layout:'column',
            items:[
            {
                columnWidth:.5,
                layout: 'form',
                items: [
                {
                  html:'<p style="font-size:12px">FECHA APROXIMADA DE DEPOSITO: </p>'
                },
                {
                    xtype:'datefield',
                    format: 'd-m-Y',
                    allowBlank: true,
                    name: 'f_ap_dep1',
                    anchor:'90%'
                },
                {
                    xtype:'datefield',
                    format: 'd-m-Y',
                    allowBlank: true,
                    name: 'f_ap_dep2',
                    anchor:'90%'
                },
                {
                    xtype:'datefield',
                    format: 'd-m-Y',
                    allowBlank: true,
                    name: 'f_ap_dep3',
                    anchor:'90%'
                },
                {
                    xtype:'datefield',
                    format: 'd-m-Y',
                    allowBlank: true,
                    name: 'f_ap_dep4',
                    anchor:'90%'
                },
                {
                    xtype:'datefield',
                    format: 'd-m-Y',
                    id:'date_1',
                    allowBlank: true,
                    hidden: true,
                    name: 'f_ap_dep5',
                    anchor:'90%'
                },
                {
                    xtype:'datefield',
                    format: 'd-m-Y',
                    id:'date_2',
                    allowBlank: true,
                    hidden: true,
                    name: 'f_ap_dep6',
                    anchor:'90%'
                },
                {
                    xtype:'datefield',
                    format: 'd-m-Y',
                    id:'date_3',
                    allowBlank: true,
                    hidden: true,
                    name: 'f_ap_dep7',
                    anchor:'90%'
                }
                ]
            },
            {
                columnWidth:.5,
                layout: 'form',
                items: [
                {
                  html:'<p style="font-size:12px">VALOR APROX. A RECIBIR: </p>'
                },
                {
                    xtype:'textfield',
                    id:'vaar1',
                    allowBlank: true,
                    name: 'v_ap_rec1',
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    id:'vaar2',
                    allowBlank: true,
                    name: 'v_ap_rec2',
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    id:'vaar3',
                    allowBlank: true,
                    name: 'v_ap_rec3',
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    id:'vaar4',
                    allowBlank: true,
                    name: 'v_ap_rec4',
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    id:'text_1',
                    allowBlank: true,
                    name: 'v_ap_rec5',
                    hidden: true,
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    id:'text_2',
                    allowBlank: true,
                    name: 'v_ap_rec6',
                    hidden: true,
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    id:'text_3',
                    allowBlank: true,
                    name: 'v_ap_rec7',
                    hidden: true,
                    anchor:'90%'
                },
                {
                    columnWidth:.25,
                    layout: 'table',
                    items: [
                        {
                            columnWidth:.25,
                            layout: 'form',
                            items: [
                                Ext.gest_cobranzas.afilamas,
                                Ext.gest_cobranzas.afilamas1,
                                Ext.gest_cobranzas.afilamas2
                            ]
                        },
                        {
                            columnWidth:.25,
                            layout: 'form',
                            items: [
                                Ext.gest_cobranzas.afilaless1,
                                Ext.gest_cobranzas.afilaless2,
                                Ext.gest_cobranzas.afilaless3,
                            ]
                        }
                    ]
                }
                
                ]
            },
            {
                columnWidth:.5,
                layout: 'form',
                items: [
                {
                    xtype:'textfield',
                    id:'totaltab2',
                    /*listeners:{'focus': function(){
                        if()
                        var c1 = 
                        var suma = (parseFloat(Ext.getCmp('vaar1').getRawValue())+
                                    parseFloat(Ext.getCmp('vaar2').getRawValue())+
                                    parseFloat(Ext.getCmp('vaar3').getRawValue())+
                                    parseFloat(Ext.getCmp('vaar4').getRawValue())+
                                    parseFloat(Ext.getCmp('text_1').getValue())+
                                    parseFloat(Ext.getCmp('text_2').getValue())+
                                    parseFloat(Ext.getCmp('text_3').getValue()));
                        Ext.getCmp('totaltab2').setValue(suma);
                    }
                    },*/
                    fieldLabel: 'TOTAL',
                    allowBlank: false,
                    name: 'total',
                    anchor:'90%'
                }
                
                ]
            }
            
            ]
        }]
    });

    Ext.gest_cobranzas.fp3 = new Ext.form.FormPanel({
        labelAlign: 'top',
        frame:true,
        bodyStyle:'padding:5px 5px 0',
        //width: 650,
        anchor:'100%',
        height: 411,
        items: [{
            layout:'column',
            items:[
            {
                columnWidth:.5,
                layout: 'form',
                items: [
                {
                    xtype:'datefield',
                    format: 'd-m-Y',
                    fieldLabel: 'FECHA',
                    allowBlank: false,
                    name: 'fecha_c_g',
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    fieldLabel: 'TOTAL PRESENCIAL',
                    allowBlank: false,
                    name: 'total_p',
                    anchor:'90%'
                }
                ]
            },
            {
                columnWidth:.5,
                layout: 'form',
                items: [
                {
                    xtype:'textfield',
                    fieldLabel: 'TOTAL SEMIPRESENCIAL',
                    allowBlank: false,
                    name: 'total_sp',
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    fieldLabel: 'TOTAL CARTERA',
                    allowBlank: false,
                    name: 't_cartera',
                    anchor:'90%'
                }
                ]
            }
            
            ]
        }]
    });

    Ext.gest_cobranzas.fp4 = new Ext.form.FormPanel({
        labelAlign: 'top',
        frame:true,
        bodyStyle:'padding:5px 5px 0',
        //width: 650,
        anchor:'100%',
        height: 411,
        items: [{
            layout:'column',
            items:[
            {
                columnWidth:.5,
                layout: 'form',
                items: [
                {
                    xtype:'datefield',
                    format: 'd-m-Y',
                    fieldLabel: 'FECHA',
                    allowBlank: false,
                    name: 'fecha_r_c',
                    anchor:'90%'
                }
                ]
            },
            {
                columnWidth:.5,
                layout: 'form',
                items: [
                {
                    xtype:'textfield',
                    fieldLabel: 'TOTAL EN CAJA',
                    allowBlank: false,
                    name: 'total_e_c',
                    anchor:'90%'
                }
                ]
            }
            
            ]
        }]
    });

    
    Ext.gest_cobranzas.fp5 = new Ext.form.FormPanel({
        labelAlign: 'top',
        frame:true,
        bodyStyle:'padding:5px 5px 0;overflowY: auto',
        //width: 650,
        anchor:'100%',
        height: 411,
        items: [{
            layout:'column',
            items:[
            {
                columnWidth:.90,
                layout: 'table',
                bodyStyle:'padding:5px 0px 15px',
                items: [
                {
                  html:'<p style="font-size:12px">FECHA: </p>'
                },
                {
                    xtype:'textfield',
                    width: 314,
                    allowBlank: false,
                    name: 'fecha_c_rp',
                    anchor:'90%'
                }
                ]
            },
            {
                columnWidth:.25,
                layout: 'form',
                items: [
                {
                  html:'<p style="font-size:12px">GRUPOS FDS:</p>'
                },
                {
                    xtype:'textfield',
                    allowBlank: false,
                    name: 'g_fds1',
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    allowBlank: true,
                    name: 'g_fds2',
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    allowBlank: true,
                    name: 'g_fds3',
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    allowBlank: true,
                    name: 'g_fds4',
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    allowBlank: true,
                    id:'textfp_11',
                    name: 'g_fds5',
                    hidden:true,
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    allowBlank: true,
                    id:'textfp_12',
                    name: 'g_fds6',
                    hidden:true,
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    allowBlank: true,
                    id:'textfp_13',
                    name: 'g_fds7',
                    hidden:true,
                    anchor:'90%'
                }

                ]
            },
            {
                columnWidth:.25,
                layout: 'form',
                items: [
                {
                  html:'<p style="font-size:12px">CATERA FDS:</p>'
                },
                {
                    xtype:'textfield',
                    allowBlank: false,
                    name: 'c_fds1',
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    allowBlank: true,
                    name: 'c_fds2',
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    allowBlank: true,
                    name: 'c_fds3',
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    allowBlank: true,
                    name: 'c_fds4',
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    allowBlank: true,
                    id:'textfp_21',
                    name: 'c_fds5',
                    hidden:true,
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    allowBlank: true,
                    id:'textfp_22',
                    name: 'c_fds6',
                    hidden:true,
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    allowBlank: true,
                    id:'textfp_23',
                    name: 'c_fds7',
                    hidden:true,
                    anchor:'90%'
                }
                ]
            },
            {
                columnWidth:.25,
                layout: 'form',
                items: [
                {
                  html:'<p style="font-size:12px">COMPROMISOS ESTIMADOS:</p>'
                },
                {
                    xtype:'textfield',
                    allowBlank: false,
                    name: 'c_e1',
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    allowBlank: true,
                    name: 'c_e2',
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    allowBlank: true,
                    name: 'c_e3',
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    allowBlank: true,
                    name: 'c_e4',
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    allowBlank: true,
                    id:'textfp_31',
                    name: 'c_e5',
                    hidden:true,
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    allowBlank: true,
                    id:'textfp_32',
                    name: 'c_e6',
                    hidden:true,
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    allowBlank: true,
                    id:'textfp_33',
                    name: 'c_e7',
                    hidden:true,
                    anchor:'90%'
                }
                ]
            },
            {
                columnWidth:.25,
                layout: 'form',
                items: [
                {
                  html:'<p style="font-size:12px">CARTERA RECAUDADA:</p>'
                },
                {
                    xtype:'textfield',
                    allowBlank: false,
                    name: 'c_r1',
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    allowBlank: true,
                    name: 'c_r2',
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    allowBlank: true,
                    name: 'c_r3',
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    allowBlank: true,
                    name: 'c_r4',
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    allowBlank: true,
                    id:'textfp_41',
                    name: 'c_r5',
                    hidden:true,
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    allowBlank: true,
                    id:'textfp_42',
                    name: 'c_r6',
                    hidden:true,
                    anchor:'90%'
                },
                {
                    xtype:'textfield',
                    allowBlank: true,
                    id:'textfp_43',
                    name: 'c_r7',
                    hidden:true,
                    anchor:'90%'
                }
                ]
            },
            {
                    columnWidth:.90,
                    layout: 'table',
                    items: [
                        {
                            columnWidth:.25,
                            layout: 'form',
                            items: [
                                Ext.gest_cobranzas.afilamasfp,
                                Ext.gest_cobranzas.afilamasfp1,
                                Ext.gest_cobranzas.afilamasfp2
                            ]
                        },
                        {
                            columnWidth:.25,
                            layout: 'form',
                            items: [
                                Ext.gest_cobranzas.afilalessfp1,
                                Ext.gest_cobranzas.afilalessfp2,
                                Ext.gest_cobranzas.afilalessfp3
                            ]
                        }
                    ]
            },
            {
                    columnWidth:.25,
                    layout: 'form',
                    items: [
                        {
                          html:'<p style="font-size: 12px; margin-top: 22px; margin-left: 93px;">TOTAL CARTERA:</p>'
                        }
                        ]
                    },
                    {
                    columnWidth:.25,
                    layout: 'form',
                    items: [
                        {
                            xtype:'textfield',
                            allowBlank: false,
                            name: 'total_c_fds',
                            anchor:'90%'
                        }
                        ]
                    },
                    {
                    columnWidth:.25,
                    layout: 'form',
                    items: [
                        {
                            xtype:'textfield',
                            allowBlank: false,
                            name: 'total_c_e',
                            anchor:'90%'
                        }
                        ]
                    },
                    {
                    columnWidth:.25,
                    layout: 'form',
                    items: [
                        {
                            xtype:'textfield',
                            allowBlank: false,
                            name: 'total_c_r',
                            anchor:'90%'
                        }
                        ]
            },
            {
                    columnWidth:.25,
                    layout: 'form',
                    items: [
                        {
                          html:'<p style="font-size: 12px; margin-top: 22px; margin-left: 93px;">% RECAUDACION:</p>'
                        }
                        ]
                    },
                    {
                    columnWidth:.25,
                    layout: 'form',
                    items: [
                        {
                            xtype:'textfield',
                            allowBlank: false,
                            name: 'recaudacion',
                            anchor:'90%'
                        }
                        ]
                    }            
            ]
        }]
    });

    Ext.gest_cobranzas.tabpanel = new Ext.TabPanel({
            region: 'center',
            //margins:'3 3 3 0', 
            activeTab: 0,
            defaults:{autoScroll:true},
            enableTabScroll:true,
            disabled:true,
            items:[{
                title: 'DESGLOSE DE PAGOS SEMANALES',
                items:[
                  Ext.gest_cobranzas.fp1
                ]
            },
            {
                title: 'REPORTE DE TARJETAS DE CREDITO',
                items:[
                  Ext.gest_cobranzas.fp2
                ]
            },
            {
                title: 'CARTERA DE GRADO',
                items:[
                    Ext.gest_cobranzas.fp3
                ]
            },
            {
                title: 'REPORTE DE CAJA',
                items:[
                    Ext.gest_cobranzas.fp4
                ]
            },
            {
                title: 'CARTERA RECUPERADA DE POSTGRADO DEL FIN DE SEMANA',
                items:[
                    Ext.gest_cobranzas.fp5
                ]
            },
            ],
            tbar:{
              items:[
                Ext.gest_cobranzas.addBtn//,'->',
                
              ]
            }
    });

    Ext.gest_cobranzas.panel = new Ext.Panel({
            region: 'west',
            frame:'true',
            width: 115,
            //margins:'3 0 3 3',
            collapsible:true,
            //cmargins:'10 10 10 10',
            items:[
                Ext.gest_cobranzas.fechabar,
                {
                    text:'Crear reporte',
                    id: 'this_button',
                    xtype:'button',
                    handler: function (btn) {
                        if(Ext.getCmp('m_contable_dp_fecha').getValue()!=''){
                            Ext.Ajax.request({
                                url: 'cob/createreport',
                                method: 'POST',
                                params:{fecha_reporte:Ext.getCmp('m_contable_dp_fecha').getValue()},
                                callback: function (options, success, response) {
                                    /*responseData = Ext.decode(response.responseText);
                                    if (responseData.success == true) {
                                        Ext.MessageBox.alert('Información', 'Se creó correctamente!!'); 
                                    }
                                    else if (responseData.success == false) {
                                        Ext.MessageBox.alert('Información', 'Ya fue creado!!'); 
                                    }*/
                                    Ext.gest_cobranzas.tabpanel.setDisabled(false);
                                    Ext.gest_cobranzas.panel.collapse(true);
                                    Ext.getCmp('this_button').setDisabled(true);
                                    Ext.getCmp('m_contable_dp_fecha').setDisabled(true);
                                }
                            });
                        }
                        else{
                            Ext.MessageBox.alert('Error!!', 'Seleccione una fecha!!');
                        }
                    },
                    icon: '../../images/grid.png'
                }
            ]
    });
    
    new Ext.Viewport({
        layout: 'border',     
        items: [
          Ext.gest_cobranzas.panel,Ext.gest_cobranzas.tabpanel
        ]
    });

});