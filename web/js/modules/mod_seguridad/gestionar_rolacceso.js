Ext.onReady(function(){

    Ext.QuickTips.init();
    Ext.form.Field.prototype.msgTarget = 'side';
    Ext.ns('Ext.gestseg_rol_acc');

    Ext.gestseg_rol_acc.stRoles = new Ext.data.Store({
        url: 'roles/cargartodos',
        autoLoad: true,
        reader: new Ext.data.JsonReader({
            root: "data",
            id: "id"
        }, [
            {name: 'id_rol'},
            {name: 'nombre'}
        ])
    });

    Ext.gestseg_rol_acc.combo_roles = new Ext.form.ComboBox({
        id: 'gestseg_rol_acc_roles',
        hiddenName: 'role',
        valueField: 'id_rol',
        displayField: 'nombre',
        store: Ext.gestseg_rol_acc.stRoles,
        autoCreate: true,
        triggerAction: 'all',
        readOnly: false,
        mode: 'local',
        width: 250,
        anchor: '85%',       
        listWidth: 250,
        align: 'rigth',
        fieldLabel: 'Rol',
        autoSelect:true,
         listeners: {
            'select': function(combo,record,index){
               Ext.gestseg_rol_acc.TreeMenu.getRootNode().reload({id_rol:1,node:'1'});
            }
        }

    });


    Ext.gestseg_rol_acc.TreeLoader =  new Ext.tree.TreeLoader({
        dataUrl: 'rolacceso/getnodes',  
        listeners:{
                      beforeload:function(loader, nodo, fnCallBack){
                          if(Ext.gestseg_rol_acc.combo_roles.getValue()!=''){
                            loader.baseParams.id_rol = Ext.gestseg_rol_acc.combo_roles.getValue();
                        }else{
                            Ext.MessageBox.alert('Informacion', 'Seleccione un rol.');
                            return false;
                        }
                     }
            }

    });

    Ext.gestseg_rol_acc.TreeMenu = new Ext.tree.TreePanel({
        useArrows: true,
        autoScroll: true,
        enabled:false,
        animate: true,
        disabled : false,
        containerScroll: true,
        border: false,
        loader:Ext.gestseg_rol_acc.TreeLoader,
        tbar:[
                'Personalizar el rol : ','-',Ext.gestseg_rol_acc.combo_roles                
        ],         
        root: {
            nodeType: 'async',
            text: 'Modulos',
            draggable: false,
            id: 1
        },
        listeners: {
            'checkchange': function(node, checked){
                if(checked){
                            
                            //Agregar permiso
                            Ext.Ajax.request({
                                url: 'rolacceso/add',
                                method: 'POST',
                                params: {id_rol: Ext.gestseg_rol_acc.combo_roles.getValue(),id_menu_item:node.attributes.id},
                                success: function (response) {                               
                                },
                                failure: function () {
                                      Ext.MessageBox.alert('No completado!!', 'Error en el servidor');                              
                                 }                            
                           });
                }else{
                           //Quitar permiso
                           Ext.Ajax.request({
                                url: 'rolacceso/remove',
                                method: 'POST',
                                params: {id_rol: Ext.gestseg_rol_acc.combo_roles.getValue(),id_menu_item:node.attributes.id},
                                success: function (response) {                               
                                },
                                failure: function () {
                                      Ext.MessageBox.alert('No completado!!', 'Error en el servidor');                              
                                 }                            
                           });

                }
            }
        },
    });


    new Ext.Viewport({
        layout: 'fit',
        items: [
            Ext.gestseg_rol_acc.TreeMenu
        ]
    });

});