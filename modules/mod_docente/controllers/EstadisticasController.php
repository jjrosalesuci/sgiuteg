<?php

namespace app\modules\mod_docente\controllers;

use Yii;
use app\modules\mod_docente\models\datEstadisticas;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class EstadisticasController extends \yii\web\Controller
{
	public $enableCsrfValidation = false;
	public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    public function actionIndex()
    {
        return $this->render('index');
    }

}