Ext.onReady(function () {

    Ext.QuickTips.init();
    Ext.form.Field.prototype.msgTarget = 'side';
    Ext.ns('Ext.mc_cob_resumen');

    Ext.BLANK_IMAGE_URL = '../../../images/s.gif';
 
    Ext.mc_cob_resumen.date = new Ext.form.DateField({
                fieldLabel: 'Buscar por  fecha',
                id: 'm_contable_cob_fecha',
                format: 'd-m-Y',
                listeners: {
                    'select': function () {
                        var fecha = Ext.getCmp('m_contable_cob_fecha').getRawValue();
                        if (fecha == '') {
                              //f = new Date();
                              //fecha = f.getDate()+"/"+(f.getMonth()+1)+"/"+f.getFullYear();
                              fecha = moment.format('L');
                              fecha = null;
                        }
                        document.getElementById('iframe_reporte_cob').src='reporte?fecha='+fecha;
                    }
                }
    });

    Ext.mc_cob_resumen.panel = new Ext.Panel({
          html:'<iframe id="iframe_reporte_cob" src="" style="width:100%; height: 100%; border:none;" ></iframe>',
          tbar:['->', 'Fecha',
          Ext.mc_cob_resumen.date
        ]
    });

    new Ext.Viewport({
        layout: 'fit',     
        items: [
           Ext.mc_cob_resumen.panel
        ]
    });

      Ext.mc_cob_resumen.fecha = new Date();
      Ext.mc_cob_resumen.fecha = Ext.mc_cob_resumen.fecha.toLocaleDateString();
      Ext.mc_cob_resumen.rest  = Ext.mc_cob_resumen.fecha.replace('/', '-');
      Ext.mc_cob_resumen.fecha = Ext.mc_cob_resumen.rest.replace('/', '-');
      document.getElementById('iframe_reporte_cob').src='reporte?fecha=null';
});