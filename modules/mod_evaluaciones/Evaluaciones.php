<?php

namespace app\modules\mod_evaluaciones;

class Evaluaciones extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\mod_evaluaciones\controllers';

    public function init()
    {
        parent::init();
        // custom initialization code goes here
    }

    static  function getMenu(){
         return 'menu';
    }
}