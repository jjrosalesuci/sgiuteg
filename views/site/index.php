<style>
    iframe{
        height: 100%;
        width: 100%;
        border: 0;
    }
</style>

<?php
use yii\helpers\Html;
//**********************************************************
// Juan Jose Rosales Rodriguez jjrosalesuci@gmail.com
//**********************************************************

//Modulo del portal para acceder a los modulos
$this->registerCssFile('@web/css/desktop.css',['depends' => ['app\assets\AppAsset']]);
$this->registerJsFile('@web/js/StartMenu.js',['depends' => ['app\assets\AppAsset']]);
$this->registerJsFile('@web/js/TaskBar.js',['depends' => ['app\assets\AppAsset']]);

//Armar dinamicamente este componente para la carga de los modulos
$this->registerJsFile('@web/js/Desktop.js',['depends' => ['app\assets\AppAsset']]) ;

// Fin de la carga dinamica de modulos

$this->registerJsFile('@web/js/App.js',['depends' => ['app\assets\AppAsset']]) ;
$this->registerJsFile('@web/js/Module.js',['depends' => ['app\assets\AppAsset']]) ;
//$this->registerJsFile('@web/js/sample.js',['depends' => ['app\assets\AppAsset']]) ;

$this->registerJs($this->render('_modulos', ['modulos' => $modulos]), \yii\web\VIEW::POS_END);

?>


<div id="x-desktop">
</div>
<div id="ux-taskbar">
	<div id="ux-taskbar-start"></div>
	<div id="ux-taskbuttons-panel"></div>
	<div class="x-clear"></div>
</div>
