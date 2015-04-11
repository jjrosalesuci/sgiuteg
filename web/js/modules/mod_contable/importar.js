Ext.onReady(function () {

    Ext.QuickTips.init();
    Ext.form.Field.prototype.msgTarget = 'side';

    Ext.ns('Ext.m_cont_import');

    var fp = new Ext.FormPanel({
        fileUpload: true,
        autoWidth: true,
        frame: true,
        autoHeight: true,
        title:'Herramienta de importaci&oacute;n',
        bodyStyle: 'padding: 30px 10px 0 100px;',
        labelWidth: 50,
        defaults: {
            anchor: '95%',
            allowBlank: false,
            msgTarget: 'side'
        },
        items: [
            {
                xtype: 'fileuploadfield',
                id: 'form-file',
                emptyText: 'Seleccione el fichero',
                fieldLabel: 'Fichero',
                name: 'file',
                buttonText: '',
                buttonCfg: {
                    iconCls: 'upload-icon'
                }
            }
        ],
        buttons: [
            {
                text: 'Importar fichero',
                handler: function () {
                    if (fp.getForm().isValid()) {
                        fp.getForm().submit({
                            url: 'mod_contable/default/upload',
                            waitMsg: 'Importando fichero...',
                            success: function (fp, action) {
                                var result = action.result;
                                if (result.success==true) {
                                    Ext.MessageBox.alert('Completado..', action.result.msg);
                                }else{
                                    Ext.MessageBox.alert('Error de importaci&oacute;n..', action.result.msg);
                                }

                            },
                            failure: function (fp, action) {
                                var result = action.result;
                                if (!result.success) {
                                    Ext.MessageBox.alert('Error de importacion..', action.result.msg);
                                }
                            }
                        });
                    }
                }
            },
            {
                text: 'Limpiar',
                handler: function () {
                    fp.getForm().reset();
                }
            }
        ]
    });

   
    new Ext.Viewport({
        layout: 'fit',
        items: [
             fp
        ]
    });
});
