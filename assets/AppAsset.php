<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
      	'/css/estilo.css',
        '/css/examples.css',//este es para el globito de las notificaciones
        '/resources/css/ext-all.css',
        '/resources/css/xtheme-gray.css',
    ];
    public $js = [
       'js/ext-base-debug.js',
       '/js/ext-all-debug.js',
       '/js/ext-lang-es.js',
       '/js/examples.js'
    ];
    public $depends = [       
    ];
}
