<?php

namespace app\modules\mod_docente\controllers;

use Yii;
use app\modules\mod_docente\models\datHorario;
use app\modules\mod_docente\models\datAulas;
use app\modules\mod_docente\models\datAsistencia;
use app\modules\mod_docente\models\datEstadisticas;
use app\modules\mod_nomencladores\models\datDocentes;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
//use yii\helpers\DateTime;

class AsistenciaController extends \yii\web\Controller
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

    public function getUserIP()
    {
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
    }
    
    public function actionCargar(){
        $request = Yii::$app->request;
        $id_docente = $request->post('id_docente');
        $query = datHorario::find();
        $user_acl = \Yii::$app->user->identity->id_user_acl;
        $dia = mb_strtoupper($request->post('dia'), "utf-8");

        if($id_docente!=''){
            $data = $query->where(['id_docente' => $id_docente])
                          ->andWhere(['dia_semana' => $dia]) 
                          ->andWhere(['id_trimestre' => 307]) //Cable quitar
                          ->asArray()
                          ->all();
            $arreglo = array();
                foreach ($data as $key => $value) {
                    $value['nombre_aula'] = $this->getAula($value['id_aula'])->nombre;
                    $value['edificio'] = $this->getAula($value['id_aula'])->edificio;
                    $value['hora_inicio'] = date("h:i a", strtotime($value['hora_inicio']));
                    $value['hora_fin'] = date("h:i a", strtotime($value['hora_fin']));
                   $arreglo[]= $value;
                }
            echo json_encode(array('data' => $arreglo));
        }
        else{

            $data = $query->where(['id_acl_user' => $user_acl])
                          ->andWhere(['dia_semana' => $dia])
                          ->andWhere(['id_trimestre' => 307]) //Cable quitar
                          ->asArray()
                          ->all();
            $arreglo = array();
                foreach ($data as $key => $value) {
                    $value['nombre_aula'] = $this->getAula($value['id_aula'])->nombre;
                    $value['edificio'] = $this->getAula($value['id_aula'])->edificio;
                    $value['hora_inicio'] = date("h:i a", strtotime($value['hora_inicio']));
                    $value['hora_fin'] = date("h:i a", strtotime($value['hora_fin']));
                   $arreglo[]= $value;
                }
            echo json_encode(array('data' => $arreglo));
        }
    }
    
    public function getAula($id){
        if (($model = datAulas::findOne($id)) !== null) {
            return $model;
        } else {
            return false;
        }
    }

    public function actionIniciar()
    {
        $model = new datAsistencia();
        $request = Yii::$app->request;
        $suplantar = $request->post('suplantar');
        $user_acl = \Yii::$app->user->identity->id_user_acl;

        if($suplantar=='true'){
            $model->id_docent_sup = $this->findDocenteid($user_acl);
        }

        $model->ip              = $request->userIP;
        $model->id_turno        = $request->post('id_turno');
        $model->fecha           = $request->post('fecha');
        $model->hora_inicio     = $request->post('hora_inicio');

        $query = datAsistencia::find()->where(['id_turno' => $request->post('id_turno')])
                                      ->andWhere(['fecha' => $request->post('fecha')])
                                      ->one();
        if(!$query)
        {              
            if ($model->save()) {
                $reporte = new datEstadisticas();
                if($request->post('hora_inicio')>$this->findModelHorario($request->post('id_turno'))->hora_inicio){
                    $reporte->id_horario        = $request->post('id_turno');
                    $reporte->minutos_atrasos = $this->restaHoras($request->post('hora_inicio'),$this->findModelHorario($request->post('id_turno'))->hora_inicio);
                    $reporte->save();
                }
                $result = new \stdClass();
                $result->success = true;
                $result->msg = 'Se inició correctamente.';
                echo json_encode($result);
            }
        }
        else {
            $result = new \stdClass();
            $result->success = false;
            $result->msg = 'Ya fue iniciado.';
            echo json_encode($result);
        }
    }

    public function actionFinalizar()
    {
        $request = Yii::$app->request;
        $suplantar = $request->post('suplantar');
        $user_acl = \Yii::$app->user->identity->id_user_acl;



        $id_turno     = $request->post('id_turno');
        $fecha        = $request->post('fecha');
        $query = datAsistencia::find()->where(['id_turno' => $id_turno])
                                      ->andWhere(['fecha' => $fecha])
                                      ->andWhere(['hora_fin' => null])
                                      ->one();   

        if($query!=null&&$suplantar=='true'&&$this->findDocenteid($user_acl)==$query->id_docent_sup){
            $query->hora_fin        = $request->post('hora_fin');
            if ($query->save()) {
                $result = new \stdClass();
                $result->success = true;
                $result->msg = 'Se finalizó correctamenteeee.';
                echo json_encode($result);
            }
            else {
                   $result = new \stdClass();
                   $result->success = false;
                   $result->msg = 'Ocurrió un error.';
                   echo json_encode($result);
            } 
        }
        else if($query!=null&&$suplantar=='false'&&$query->id_docent_sup==''){
                $query->hora_fin        = $request->post('hora_fin');
                if ($query->save()) {
                    $result = new \stdClass();
                    $result->success = true;
                    $result->msg = 'Se finalizó correctamente.';
                    echo json_encode($result);
                }else {
                   $result = new \stdClass();
                   $result->success = false;
                   $result->msg = 'Ocurrió un error.';
                   echo json_encode($result);
                } 
        }
        else if($query==null&&$suplantar=='true'){
            $result = new \stdClass();
            $result->success = false;
            $result->msg = 'Ocurrió un error.';
            echo json_encode($result);
        }

        else {
            $result = new \stdClass();
            $result->success = 'docente';
            $result->msg = 'Usted ya ha sido suplantado.';
            echo json_encode($result);
        } 
    }

    public function restaHoras($horaIni, $horaFin){

        return (date("H:i:s", strtotime("00:00:00") + strtotime($horaIni) - strtotime($horaFin) ));
    }

    public function findDocenteid($user_acl){
        if (($model = datDocentes::find()->where(['id_acl_user' => $user_acl])->one()) !== null) {
            return $model->id;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function findModelHorario($id_turno){
        if (($model = datHorario::find()->where(['id' => $id_turno])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModel($id)
    {
        if (($model = datAsistencia::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}