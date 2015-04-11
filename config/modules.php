<?php

/**
 * Created by Juan Jose Rosales.
 * User: jjrosales
 * Date: 10/01/15
 * Time: 16:54
 *
 * En este metodo tengo que leer dinamicamente los modulos que esten instalados y almacenarlo en cache
 * por que esto puede poner mas lenta la aplicacion si se ejecuta cada vez que levante el sistema.
 *
 * TODO: Mecanismo de caache
 */

/* Leer los modulos dentro del directorio modules de app */

//Direccion de los modulos

$arr_mod      = array();
$arr_mod_menu = array();

$dir_mt_arq = substr(dirname(__FILE__), 0, strrpos(dirname(__FILE__), 'config'));
$dir_mt_arq_m = $dir_mt_arq.'modules';

function cargar_modulos($dir_mt_arq){
    $arr_mod    = array();
    $dir        = opendir($dir_mt_arq);
    while (false !== ( $file = readdir($dir))) {
          if (is_dir($dir_mt_arq . '/' .$file) & $file!='..' & $file!='.') {
              $direccion_modulo = $dir_mt_arq . '/' .$file.'/';
              $d = dir($direccion_modulo);
              while($entry=$d->read()) {
                  if(!is_dir($direccion_modulo. '/' .$entry)){
                      $ruta = "app\modules\ $file \ $entry ";
                      $ruta = str_replace(" ","",$ruta);
                      $ruta = str_replace(".php","",$ruta);
                      $arr_mod['modulos'][$file] =  $ruta;                  
                  }
              }
              $d->close();
        }
    }
    closedir($dir);
   return $arr_mod;
}

$arr_mod = cargar_modulos($dir_mt_arq_m);