/*!
 * Ext JS Library 3.3.1
 * Copyright(c) 2006-2010 Sencha Inc.
 * licensing@sencha.com
 * http://www.sencha.com/license
 */
Ext.onReady(function () {

    Ext.QuickTips.init();
    Ext.form.Field.prototype.msgTarget = 'side';
    var bd = Ext.getBody();
  
    Ext.ns('Ext.login');

    Ext.login.panel_logo = new Ext.Panel({
        bodyStyle:'margin: 5px,',
        frame: false,
        bodyBorder:false,
        border:false,
        html:'<img src="'+BASE_URL_FRAME+'images/logo.png"/>',
    }),

    Ext.login.ventana = new Ext.Window({
        title: 'ENTRAR AL SISTEMA',
        items: [
            Ext.login.panel_logo,
            new Ext.FormPanel({
                labelWidth: 83,
                url: 'save-form.php',
                frame: false,
                border:false,
                bodyBorder:false,
                bodyStyle: 'padding:5px 5px 0',
                width: 350,
                id: 'login_form',
                defaults: {width: 230},
                defaultType: 'textfield',
                items: [
                    {
                        fieldLabel: 'USUARIO',
                        name: 'username',
                        allowBlank: false
                    },
                    {
                        fieldLabel: 'CONTRASEÑA',
                        name: 'password',
                        inputType: 'password',
                        enableKeyEvents:true,
                        listeners:{
                                'keyup':function(textField, eventoObject){
                                    if(eventoObject.getCharCode() == 13){
                                            enviar();
                                    }
                                }
                        }
                    }
                ],
                buttons: [
                    {
                        text: 'ACEPTAR',
                        icon: '../../images/application_go.png',
                        handler: function () {
                          enviar();
                        }
                        //Auth
                    }
                ]
            })
        ]
    });


    function enviar(){
              if (Ext.getCmp('login_form').getForm().isValid()) {
                    Ext.getCmp('login_form').getForm().submit({
                        url: 'auth',
                        waitMsg:'Verificando credenciales...',
                        success: function (form, action) {
                                        var result = action.result;
                                        if (result.success) {
                                            window.location='index';
                                            Ext.MessageBox.alert('Informacion.', action.result.msg);
                                        }
                                        if (result.success==false) {
                                            Ext.MessageBox.alert('Error.', action.result.msg);
                                        }                                        
                        },
                        failure: function (form, action) {
                                        var result = action.result;
                                        if (result.success) {
                                            Ext.MessageBox.alert('Informacion..', action.result.msg);
                                        }
                                        else {
                                            Ext.MessageBox.alert('Error', action.result.msg);
                                        }
                        }
                    });
             }
    }

    Ext.login.ventana.show();
    
});
