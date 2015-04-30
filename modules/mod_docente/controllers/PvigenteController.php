<?php

namespace app\modules\mod_docente\controllers;

use Yii;
use app\modules\mod_docente\models\datPeriodoConfig;
use app\modules\mod_nomencladores\models\datSemestres;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class PvigenteController extends \yii\web\Controller
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

    public function actionModalidad(){

        $arreglo[] = array(
            'modalidad' =>'Presencial',
        ); 
        $arreglo[] = array(
            'modalidad' =>'Semipresencial',
        ); 
        
        
        echo json_encode(array('data' => $arreglo));

    }

    public function actionCargarsemestres()
    {
        $query = datPeriodoConfig::find();
        $data = $query->orderBy('id')->asArray()->all();
        $arreglo = array();
        foreach ($data as $key => $value) {
            $value['nombre'] = $this->findGrupo($value['id_periodo']);
            $arreglo[] = $value;
        }
        echo json_encode(array('data' => $arreglo));
    }

    public function actionUpdate()
    {
        $request = Yii::$app->request;
        $id = $request->post('id');
        $id_periodo = $request->post('id_periodo');
        $tipo = $request->post('tipo');

        $model = $this->findModel($id);
        $model->id_periodo  = $id_periodo;
        if ($model->save()) {
            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Se modificó correctamente el periodo.';
            echo json_encode($result);
        } else {
            $result = new \stdClass();
            $result->success = false;
            $result->msg = 'Ocurrio un error.';
            echo json_encode($result);
        }
    }

    protected function findModel($id)
    {
        if (($model = datPeriodoConfig::findOne($id)) !== null) {
            return $model;
        } else {
            return false;
        }
    }

    public function findGrupo($id)
    {
        if (($model = datSemestres::findOne($id)) !== null) {
            return $model->nombre;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}