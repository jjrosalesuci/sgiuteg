Ext.onReady(function () {

    Ext.QuickTips.init();
    Ext.form.Field.prototype.msgTarget = 'side';   
    Ext.BLANK_IMAGE_URL = '../../../images/s.gif';

    Ext.ns('Ext.mc_acad_infor');

     Ext.mc_acad_infor.panel = new Ext.Panel({
          html:'<iframe id="iframe_reporte_cob" src="" style="width:100%; height: 100%; border:none;" ></iframe>',
          tbar:['->', ''          
        ]
    });

    new Ext.Viewport({
        layout: 'fit',     
        items: [
           Ext.mc_acad_infor.panel
        ]
    });

    document.getElementById('iframe_reporte_cob').src='informes/reporte';
  
});