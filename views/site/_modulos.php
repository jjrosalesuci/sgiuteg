<?php

$ruta_web = Yii::getAlias('@web').'/';

/*
 * Juan Jose Rosales Rodriguez
 * The magic is here!!!
 * Construccion del menu en dependencia
 * de los modulos que tiene acceso el rol *
 * */

//echo '<pre>';
//var_dump(\Yii::$app->user->identity->username);die();


$arr_modulos = array();
foreach($modulos as $key => $value){
    $arr_modulos[]= "new MyDesktop.".$key."()";
}
$cadena_modulos = implode(',',$arr_modulos);

$ejecutar = "
MyDesktop = new Ext.app.App({
init :function(){
Ext.QuickTips.init();
},

getModules : function(){
return [
 ".$cadena_modulos."
];
},

// config for the start menu
getStartConfig : function(){
return {
    title: 'Usuario: " . \Yii::$app->user->identity->username  ."',
    iconCls: 'user',
    toolItems: [{
         text:'Salir',
         iconCls:'logout',
         scope:this,
         handler: function () {
           Ext.MessageBox.show({
           title:'Salir del sistema',
           msg: '¿ Usted esta seguro que desea salir del sistema ?',
           buttons: Ext.MessageBox.YESNOCANCEL,
           fn:function (btn){
               if(btn=='yes'){
                  Ext.Ajax.request({
                    url: '".$ruta_web."index.php/site/logout',
                    method: 'POST',
                    callback: function (options, success, response) {
                        responseData = Ext.decode(response.responseText);
                        window.location='';
                    }
                });
               }
            },
            animEl: 'mb4',
            icon: Ext.MessageBox.QUESTION
           });
         }
       }]
     };
    }
});

var windowIndex = 0;

MyDesktop.BogusModule = Ext.extend(Ext.app.Module, {
    init : function(){
    this.launcher = {
    text: 'Window '+(++windowIndex),
    iconCls:'bogus',
    handler : this.createWindow,
        scope: this,
        windowId:windowIndex
    }
    },

  createWindow : function(src){
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('bogus'+src.windowId);
    if(!win){
        win = desktop.createWindow({
            id: 'bogus'+src.windowId,
            title:src.text,
            width:src.ancho,
            height:src.alto,
            html :'<iframe src='+src.link+' ></iframe>',
            iconCls: 'bogus',
            shim:false,
            animCollapse:false,
            constrainHeader:true
        });
    }
   win.show();
  }
});
";

foreach($modulos as $key => $value){
$ejecutar = $ejecutar."
    MyDesktop.$key = Ext.extend(MyDesktop.BogusModule, {
    init : function(){
    this.launcher = {
            text: '".$value['name']."',
            iconCls: 'bogus',";
            if(isset($value['link'])){
                $ejecutar = $ejecutar."
                    handler : this.createWindow,
                    scope: this,
                    windowId: ++windowIndex,
                    module:'$key',
                    alto:".$value['alto'].",
                    ancho:".$value['ancho'].",
                    link:'".$ruta_web.$value["link"]."'
                ";
            }else{
                $ejecutar = $ejecutar."
                handler: function(){
                    return false;
                }";

                //En esta parte concatenar los submenus
                if(isset($value['menu_items'])){
                    $ejecutar = $ejecutar.",
                    menu: {
                    items:[
                     ";


                            $cantidad = count($value['menu_items']);
                            $cont = 1;
                            foreach ($value['menu_items'] as $item) {
                                $ejecutar = $ejecutar." { ";
                                $ejecutar = $ejecutar."
                                text: '".$item['name']."',
                                iconCls:'bogus',
                                scope: this,
                                windowId: ++windowIndex,
                                module:'$key',";

                                if(isset($item["link"])){
                                     $ejecutar = $ejecutar."
                                     handler : this.createWindow,
                                     alto:".$item['alto'].",
                                     ancho:".$item['ancho'].",
                                     link:'".$ruta_web.$item["link"]."' ";
                                }

                                if($cont<$cantidad){
                                    $ejecutar = $ejecutar." }, ";
                                }else{
                                    $ejecutar = $ejecutar." }";
                                }
                                $cont++;
                            }
                            $ejecutar = $ejecutar."



                         ]
                       }";
                }
               // Fin de los submenu
            }
            $ejecutar = $ejecutar."
      }
    }";

   $ejecutar = $ejecutar."
 }); ";
}

//echo ('<pre>');
//die($ejecutar);
echo $ejecutar;


/*
 * , menu: {
                 items:[{
                    text: 'Bogus Window '+(++windowIndex),
                    iconCls:'bogus',
                    handler : this.createWindow,
                    scope: this,
                    windowId: windowIndex
                },{
                    text: 'Bogus Window '+(++windowIndex),
                    iconCls:'bogus',
                    handler : this.createWindow,
                    scope: this,
                    windowId: windowIndex
              }]
          }
 * */



/*
 * return [
    'name' => 'Pago de Artistas',
    'menu_items' => [
        [
            'name' => 'Nomencladores',
            'link' => 'gestnomencladores/index'
        ],
        [
            'name' => 'Conceptos de pago',
            'menu_items' => [
                [
                    'name' => 'Concepto 1',
                    'link' => 'concepto1/index'
                ],
                [
                    'name' => 'Concepto 2',
                    'link' => 'concepto2/index'
                ]
            ]
        ]
    ]
];
 * */


