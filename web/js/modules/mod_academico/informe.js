Ext.onReady(function () {

	Ext.QuickTips.init();
	Ext.form.Field.prototype.msgTarget = 'side';   

	Ext.ns('Ext.mc_acad_infor');

	Ext.mc_acad_infor.id_reporte = 0;

	Ext.mc_acad_infor.states = [
	                            ['0', 'Períodos actuales'],
	                            ['1', 'Períodos actuales por categorías'],
	                            ['2', 'Cartera de grado deudas'],
	                            ['3', 'Cartera de grado ingresos']
	                            ];

	Ext.mc_acad_infor.store = new Ext.data.ArrayStore({
		fields: ['id', 'texto'],
		data :  Ext.mc_acad_infor.states 
	});

	Ext.mc_acad_infor.combo = new Ext.form.ComboBox({
		store: Ext.mc_acad_infor.store,
		displayField:'texto',
		typeAhead: true,
		mode: 'local',
		forceSelection: true,
		triggerAction: 'all',
		emptyText:'Seleccione un reporte...',
		selectOnFocus:true,
		listeners: {
			'select': function(combo,record,index){
				Ext.mc_acad_infor.id_reporte = index;
				if(Ext.mc_acad_infor.id_reporte==2){
					Ext.mc_acad_infor.btnExportarExcel.enable();
				}else{
					Ext.mc_acad_infor.btnExportarExcel.disable();
				}
				document.getElementById('iframe_reporte_cob').src='informes/reporte?id='+index;
			}
		}
	});

	Ext.mc_acad_infor.btnimprimir = new Ext.Button({
		text: 'Imprimir',
		handler: function(){
			document.getElementById('iframe_reporte_cob').src='informes/reporte?id='+Ext.mc_acad_infor.id_reporte+'&print=true';
		},
		icon: '../../images/print.png'
	});

	Ext.mc_acad_infor.btnExportarExcel = new Ext.Button({
		text: 'Exportar a excel',
		disabled:true,
		handler: function(){
			document.getElementById('iframe_reporte_cob').src='informes/reporte?id=4';
		},
		icon: '../../images/grid.png'
	});

	Ext.mc_acad_infor.panel = new Ext.Panel({
		html:'<iframe id="iframe_reporte_cob" src="" style="width:100%; height: 100%; border:none;" ></iframe>',
		tbar:[
			   'Vista del reporte:',Ext.mc_acad_infor.combo,'-',Ext.mc_acad_infor.btnimprimir,'-',Ext.mc_acad_infor.btnExportarExcel         
		     ]
	});

	new Ext.Viewport({
		layout: 'fit',     
		items: [
		        Ext.mc_acad_infor.panel
		       ]
	});

	document.getElementById('iframe_reporte_cob').src='informes/reporte?id=0';  
});