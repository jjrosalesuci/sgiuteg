Ext.onReady(function(){
    Ext.QuickTips.init();
    Ext.form.Field.prototype.msgTarget = 'side';
    Ext.ns('Ext.gestseg_menu');

    Ext.gestseg_menu.fpItems = [
        {
            fieldLabel: 'Nombre',
            allowBlank: false,
            name: 'name'         
        },
        {
            fieldLabel: 'Enlace',
            allowBlank: true,
            name: 'link'         
        },
        {
            fieldLabel: 'Ancho',
            allowBlank: true,
            name: 'ancho'         
        },{
            fieldLabel: 'Alto',
            allowBlank: true,
            name: 'alto'        
        }
    ];

    Ext.gestseg_menu.fp = new Ext.form.FormPanel({
        frame: true,
        bodyStyle: 'padding: 6px',
        labelWidth: 60,
        defaultType: 'textfield',
        defaults: {
            msgTarget: 'side',
            anchor: '-20'
        },
        items: Ext.gestseg_menu.fpItems
    });


    Ext.gestseg_menu.myBtnHandler = function (btn) {
        if (btn.text == 'Adicionar') {
            if (!Ext.gestseg_menu.win) {               
                Ext.gestseg_menu.win = new Ext.Window({
                    closeAction: 'hide',
                    title: 'Adicionar un nuevo elemento',
                    height: 190,
                    width: 400,
                    modal:true,
                    constrain: true,
                    items: [Ext.gestseg_menu.fp],
                    buttons: [
                        {
                            text: 'Aceptar',
                            icon: '../../images/validado.png',
                            handler: function (btn) {
                                if (Ext.gestseg_menu.fp.getForm().isValid()) {
                                    Ext.gestseg_menu.fp.el.mask('Por favor espere..', 'x-mask-loading');
                                    Ext.gestseg_menu.fp.getForm().submit({
                                        url: 'menu/addnode',
                                        params:{id_padre: Ext.gestseg_menu.nodoSeleccionado.attributes.id},
                                        success: function (form, action) {
                                            Ext.gestseg_menu.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gestseg_menu.win.hide();
                                                Ext.gestseg_menu.nodoSeleccionado.reload();
                                                //Ext.gestseg_menu.TreeMenu.getRootNode().reload();
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        },
                                        failure: function (form, action) {
                                            Ext.gestseg_menu.fp.el.unmask();
                                            Ext.MessageBox.alert('No completado!!', 'Error en el servidor');
                                        }
                                    });
                                }
                            }
                        },
                        {
                            text: 'Cancelar',
                            icon: '../../images/no_validado.png',
                            handler: function (btn) {
                                Ext.gestseg_menu.win.hide();
                            }
                        }
                    ]
                });
            } else {
                Ext.gestseg_menu.fp.getForm().reset();
            }
            
            Ext.gestseg_menu.win.add(Ext.gestseg_menu.fp);
            Ext.gestseg_menu.win.doLayout();
            Ext.gestseg_menu.win.show();
        }

        //Modificar


        if (btn.text == 'Modificar') {

             if (!Ext.gestseg_menu.winmod) {
                Ext.gestseg_menu.winmod = new Ext.Window({
                    closeAction: 'hide',
                    title: 'Modificar el elemento',
                    height: 190,
                    width: 400,
                    modal:true,
                    constrain: true,
                    items: [Ext.gestseg_menu.fp],
                    buttons: [
                        {
                            text: 'Aceptar',
                            icon: '../../images/validado.png',
                            handler: function (btn) {
                                if (Ext.gestseg_menu.fp.getForm().isValid()) {
                                    Ext.gestseg_menu.fp.el.mask('Por favor espere..', 'x-mask-loading');
                                    Ext.gestseg_menu.fp.getForm().submit({
                                        url: 'menu/updatenode',
                                        params:{id: Ext.gestseg_menu.nodoSeleccionado.attributes.id},
                                        success: function (form, action) {
                                            Ext.gestseg_menu.fp.el.unmask();
                                            var result = action.result;
                                            if (result.success) {
                                                Ext.MessageBox.alert('Completado..', action.result.msg);
                                                Ext.gestseg_menu.winmod.hide();
                                                Ext.gestseg_menu.nodoSeleccionado.parentNode.reload();
                                                Ext.gestseg_menu.editBtn.disable();
                                                Ext.gestseg_menu.editDel.disable();
                                                Ext.gestseg_menu.addBtn.disable(); 
                                            }
                                            else {
                                                Ext.MessageBox.alert('No completado!!', action.result.msg);
                                            }
                                        },
                                        failure: function (form, action) {
                                            Ext.gestseg_menu.fp.el.unmask();
                                            Ext.MessageBox.alert('No completado!!', 'Error en el servidor');
                                        }
                                    });
                                }
                            }
                        },
                        {
                            text: 'Cancelar',
                            icon: '../../images/no_validado.png',
                            handler: function (btn) {
                                Ext.gestseg_menu.winmod.hide();
                            }
                        }
                    ]
                });
            } else {
                Ext.gestseg_menu.fp.getForm().reset();
            }

            Ext.gestseg_menu.fp.getForm().reset();
            Ext.gestseg_menu.winmod.add(Ext.gestseg_menu.fp);
            Ext.gestseg_menu.winmod.doLayout();
            Ext.gestseg_menu.winmod.show();
            Ext.gestseg_menu.record = Ext.data.Record.create([
                 {name: 'name'},
                 {name: 'link'},
                 {name: 'ancho'},
                 {name: 'alto'}
            ]);
            Ext.gestseg_menu.myNewRecord = new Ext.gestseg_menu.record(
            {
                name: Ext.gestseg_menu.nodoSeleccionado.attributes.text,
                link: Ext.gestseg_menu.nodoSeleccionado.attributes.link,
                ancho: Ext.gestseg_menu.nodoSeleccionado.attributes.ancho,
                alto: Ext.gestseg_menu.nodoSeleccionado.attributes.alto             
            });
            Ext.gestseg_menu.fp.getForm().loadRecord(Ext.gestseg_menu.myNewRecord);
        }  


        //eliminar 

        if (btn.text == 'Eliminar') {
          
            Ext.MessageBox.show({
                title: 'Mensaje de confirmacion',
                msg: '¿ Usted esta seguro que desea eliminar el elemento ?',
                buttons: Ext.MessageBox.YESNOCANCEL,
                fn: function (btn) {
                    if (btn == 'yes') {
                        Ext.Ajax.request({
                            url: 'menu/delete',
                            method: 'POST',
                            params: {id: Ext.gestseg_menu.nodoSeleccionado.attributes.id},
                            success: function (response) {
                                responseData = Ext.decode(response.responseText);
                                if (responseData.success == true) {
                                    Ext.MessageBox.alert('Informacion', 'Se elimino correctamente.'); 
                                     Ext.gestseg_menu.editBtn.disable();
                                     Ext.gestseg_menu.editDel.disable();
                                     Ext.gestseg_menu.addBtn.disable(); 
                                    Ext.gestseg_menu.nodoSeleccionado.parentNode.reload();
                                }
                            },
                            failure: function () {
                                  Ext.MessageBox.alert('No completado!!', 'Error en el servidor');                              
                             }                            
                        });
                    }
                },
                icon: Ext.MessageBox.QUESTION
            });
        }

    }

    Ext.gestseg_menu.addBtn = new Ext.Button({
        text: 'Adicionar',
        handler: Ext.gestseg_menu.myBtnHandler,
        icon: '../../images/add.png',
        disabled:true
    });

    Ext.gestseg_menu.editBtn = new Ext.Button({
        text: 'Modificar',
        handler: Ext.gestseg_menu.myBtnHandler,
        icon: '../../images/bogus.png',
        disabled:true
    });

    Ext.gestseg_menu.editDel = new Ext.Button({
        text: 'Eliminar',
        handler: Ext.gestseg_menu.myBtnHandler,
        icon: '../../images/delete.gif',
        disabled:true
    });

      
    
     Ext.gestseg_menu.TreeMenu = new Ext.tree.TreePanel({
        useArrows: true,
        autoScroll: true,
        animate: true,
        enableDD: true,
        containerScroll: true,
        border: false,
        //auto create TreeLoader
        dataUrl: 'menu/getnodes',
        tbar:[
                Ext.gestseg_menu.addBtn,'-',
                Ext.gestseg_menu.editBtn,'-',
                Ext.gestseg_menu.editDel
            ],
        root: {
            nodeType: 'async',
            text: 'Modulos',
            draggable: false,
            id: 1
        },
        listeners: {
            'beforemovenode': function(tre,node,node_old_parent,node_parent,index){
                        Ext.Ajax.request({
                            url: 'menu/cambiarpadre',
                            method: 'POST',
                            params: {id_padre: node_parent.attributes.id,id:node.attributes.id},
                            success: function (response) {
                                responseData = Ext.decode(response.responseText);
                                if (responseData.success == true) {                                  
                                }
                            },
                            failure: function () {
                                  Ext.MessageBox.alert('No completado!!', 'Error en el servidor');                              
                             }                            
                        });

              
            }
        }
        
    });

    Ext.gestseg_menu.TreeMenu.on('click', function (node, e){
        Ext.gestseg_menu.nodoSeleccionado = node;       
        if (node.id != 1){
            Ext.gestseg_menu.editBtn.enable(); 
            Ext.gestseg_menu.editDel.enable();
            Ext.gestseg_menu.addBtn.enable();  
        }
        if (node.id == 1){
            Ext.gestseg_menu.editBtn.disable();
            Ext.gestseg_menu.editDel.disable();
            Ext.gestseg_menu.addBtn.enable(); 
        }
    }, this);


    Ext.gestseg_menu.TreeMenu.getRootNode().expand();

    new Ext.Viewport({
        layout: 'fit',
        items: [
            Ext.gestseg_menu.TreeMenu
        ]
    });

});