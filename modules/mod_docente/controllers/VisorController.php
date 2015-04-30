<?php

namespace app\modules\mod_docente\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class VisorController extends \yii\web\Controller
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
    /*public function actionView() {
		$request = Yii::$app->request;
        $offset = $request->get('hora_inicio');
        $limit = $request->get('hora_fin');

        if($offset!='' && $limit!=''){
            $count = datHorario::find()->count();
            $query = datHorario::find();
            $data = $query->where(['>=','start',$offset])
                          ->andWhere(['<=','end',$limit])  
                          ->orderBy('id')
                          ->asArray()->all();

            $arreglo = array();
            foreach ($data as $key => $value) {
            	 $value['start'] = $value['start'].'T'.$value['hora_inicio'];
            	 $value['end'] = $value['end'].'T'.$value['hora_fin'];
            	 //$value['title'] = $value['nombre_materia'];
            	 $arreglo[] = $value;
            }
            echo json_encode(array('count'=>$count,'data' => $arreglo));
        }
        /*else {
            $count = datHorario::find()->count();
            $query = datHorario::find();
            $data = $query->orderBy('id')
                          ->asArray()->all();

            $arreglo = array();
            foreach ($data as $key => $value) {
                 $value['start'] = $value['start'].'T'.$value['hora_inicio'];
                 $value['end'] = $value['end'].'T'.$value['hora_fin'];
                 //$value['title'] = $value['nombre_materia'];
                 $arreglo[] = $value;
            }
            echo json_encode(array('count'=>$count,'data' => $arreglo));

        }
	}*/


}
