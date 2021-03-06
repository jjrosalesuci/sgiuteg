<?php

namespace app\modules\mod_docente\controllers;

use Yii;
use app\modules\mod_docente\models\datHorario;
use app\modules\mod_docente\models\datAulas;
use app\modules\mod_docente\models\datAsistencia;
use app\modules\mod_docente\models\datEstadisticas;
use app\modules\mod_docente\models\datPeriodoConfig;
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
        $id_periodo_vigente = $this->findModelPeriodoV($dia);

        if($id_docente!=''){
            $data = $query->where(['id_docente' => $id_docente])
                          ->andWhere(['dia_semana' => $dia]) 
                          ->andWhere(['id_trimestre' => $id_periodo_vigente]) 
                          ->asArray()
                          ->all();
            $arreglo = array();
                foreach ($data as $key => $value) {
                    $value['nombre_aula'] = $this->actiongetAula($value['id_aula'])->nombre;
                    $value['edificio'] = $this->actiongetAula($value['id_aula'])->edificio;
                    //$value['hora_inicio'] = date("h:i a", strtotime($value['hora_inicio']));
                    //$value['hora_fin'] = date("h:i a", strtotime($value['hora_fin']));
                   $arreglo[]= $value;
                }
            echo json_encode(array('data' => $arreglo));
        }
        else{

            $data = $query->where(['id_acl_user' => $user_acl])
                          ->andWhere(['dia_semana' => $dia])
                          ->andWhere(['id_trimestre' => $id_periodo_vigente]) 
                          ->asArray()
                          ->all();
            if($data==''){
                echo ("Usted no tiene turnos planificados para hoy");
                die();
            }
            $arreglo = array();
                foreach ($data as $key => $value) {
                    $value['nombre_aula'] = $this->actiongetAula($value['id_aula'])->nombre;
                    $value['edificio'] = $this->actiongetAula($value['id_aula'])->edificio;
                    //$value['hora_inicio'] = date("h:i a", strtotime($value['hora_inicio']));
                    //$value['hora_fin'] = date("h:i a", strtotime($value['hora_fin']));
                   $arreglo[]= $value;
                }
            echo json_encode(array('data' => $arreglo));
        }
    }
    
    public function actiongetAula($id){
        if (($model = datAulas::findOne($id)) !== null) {
            return $model;
        } else {
            return false;
        }
    }

    public function actionGetaulapost(){
        $request = Yii::$app->request;
        $id = $request->post('id_aula');
        $query = datAulas::find()->where(['id' => $id]);
        $data = $query->orderBy('id')->asArray()->all();
        echo json_encode(array('data' => $data));
    }

    public function actionIniciar()
    {
        $model = new datAsistencia();
        $request = Yii::$app->request;
        $suplantar = $request->post('suplantar');
        $user_acl = \Yii::$app->user->identity->id_user_acl;

        $hora_inicio = date("H:i:s",strtotime($request->post('hora_inicio')));

        $hora1111 = $this->restaHoras($this->findModelHorario($request->post('id_turno'))->hora_inicio,'00:30:00');
        $hora2222 = $this->restaHoras($this->findModelHorario($request->post('id_turno'))->hora_fin,'00:30:00');
        

        if($suplantar=='true'){
            $model->id_docent_sup = $this->findDocenteid($user_acl);
        }

        $model->ip_inicio       = $request->userIP;
        //$model->ip_inicio       = $this->getIP();

        $model->id_turno        = $request->post('id_turno');
        $model->fecha           = $request->post('fecha');
        $model->hora_inicio     = $hora_inicio;

        $query = datAsistencia::find()->where(['id_turno' => $request->post('id_turno')])
                                      ->andWhere(['fecha' => $request->post('fecha')])
                                      ->one();

        if($model->id_docent_sup==$this->findModelHorario($request->post('id_turno'))->id_docente){
            $result = new \stdClass();
            $result->success = 'docente';
            $result->msg = 'Usted no puede suplantarse.';
            echo json_encode($result);
            die;
        }

        if($hora_inicio<$hora1111&&!$query){
            $result = new \stdClass();
            $result->success = 'todavia';
            $result->msg = 'Todavia no es Hora.';
            echo json_encode($result);
            die();
        }
        if($hora_inicio>$hora2222&&!$query){
            $result = new \stdClass();
            $result->success = 'concluido';
            $result->msg = 'Demasiado tarde.';
            echo json_encode($result);
            die();
        }

        if(!$query&&$model->id_docent_sup!=$this->findModelHorario($request->post('id_turno'))->id_docente)
        {              
            if ($model->save()) {
                $reporte = new datEstadisticas();
                $hora1 = $hora_inicio;
                $hora2 = $this->findModelHorario($request->post('id_turno'))->hora_inicio;
                if($hora1 > $hora2){
                    $reporte->id_horario      = $request->post('id_turno');
                    $reporte->id_tri          = $this->findModelHorario($request->post('id_turno'))->id_trimestre;
                    $reporte->id_docente      = $this->findModelHorario($request->post('id_turno'))->id_docente;
                    $reporte->minutos_atrasos = $this->restaHoras($hora_inicio,$this->findModelHorario($request->post('id_turno'))->hora_inicio);
                    $reporte->fecha           = $request->post('fecha');
                    if($reporte->save()){
                        $result = new \stdClass();
                        $result->success = true;
                        $result->msg = 'Se inició correctamente.';
                        echo json_encode($result);
                    }
                    else {
                        $result = new \stdClass();
                        $result->success = false;
                        $result->msg = 'Ocurrió un error.';
                        echo json_encode($result);
                    }
                }
                else{
                    $result = new \stdClass();
                    $result->success = true;
                    $result->msg = 'Se inició correctamente.';
                    echo json_encode($result);
                }   
            }
            else {
                $result = new \stdClass();
                $result->success = false;
                $result->msg = 'Ocurrió un error.';
                echo json_encode($result);
            }
        }
        else if($suplantar=='true'&&$model->id_docent_sup==$this->findModelHorario($request->post('id_turno'))->id_docente){
            $result = new \stdClass();
            $result->success = 'docente';
            $result->msg = 'Usted no puede suplantarse.';
            echo json_encode($result);
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

        $hora_fin = date("H:i:s",strtotime($request->post('hora_fin')));
        $hora22 = $this->findModelHorario($request->post('id_turno'))->hora_inicio;

        $hora1111 = $this->sumaHoras($this->findModelHorario($request->post('id_turno'))->hora_fin,'01:00:00');

        $id_turno     = $request->post('id_turno');
        $fecha        = $request->post('fecha');

        $query = datAsistencia::find()->where(['id_turno' => $id_turno])
                                      ->andWhere(['fecha' => $fecha])
                                      ->andWhere(['hora_fin' => null])
                                      ->one();
        $query1 = datAsistencia::find()->where(['id_turno' => $id_turno])
                                      ->andWhere(['fecha' => $fecha])
                                      ->one();

        if($hora_fin>$hora1111&&$suplantar=='false'){
                if ($query!=null) {
                    if($query1->hora_fin==''){
                        $result = new \stdClass();
                        $result->success = 'concluido';
                        $result->msg = 'Demasiado tarde.';
                        echo json_encode($result);
                        die();
                    }
                }else if($query==null){
                    $result = new \stdClass();
                    $result->success = 'noiniciado';
                    $result->msg = 'Ocurrió un error.';
                    echo json_encode($result);
                    die();
                }
                else{
                    $result = new \stdClass();
                    $result->success = false;
                    $result->msg = 'Ya fue finalizado!!';
                    echo json_encode($result);
                    die();
                }
        }
        if($hora_fin<$hora22&&$query!=null){
            $result = new \stdClass();
            $result->success = 'antes_hora';
            $result->msg = 'No se puede finalizar.';
            echo json_encode($result);
            die();
        }   

        if($query!=null&&$suplantar=='true'&&$this->findDocenteid($user_acl)==$query->id_docent_sup){
            $query->hora_fin        = $hora_fin;
            $query->ip_fin          = $request->userIP;
            if ($query->save()) {
                $reporte = $this->findModelEstadistica($id_turno,$fecha);
                    if($reporte){
                        $hora1 = $hora_fin;
                        $hora2 = $this->findModelHorario($id_turno)->hora_fin;
                        if($hora1 < $hora2){
                            $reporte->minutos_salidas_ah = $this->restaHoras($this->findModelHorario($request->post('id_turno'))->hora_fin,$hora_fin);
                            $horas_total_turno           = $this->restaHoras($this->findModelHorario($request->post('id_turno'))->hora_fin,$this->findModelHorario($request->post('id_turno'))->hora_inicio);
                            $total_a_restar              = $this->sumaHoras($reporte->minutos_atrasos,$reporte->minutos_salidas_ah);
                            $reporte->horas_trabajadas   = $this->restaHoras($horas_total_turno,$total_a_restar);
                            $reporte->horas_faltas       = $horas_total_turno;
                            $reporte->horas_reemplazo    = $reporte->horas_trabajadas;
                            if($reporte->save()){
                                $result = new \stdClass();
                                $result->success = true;
                                $result->msg = 'Satisfactorio';
                                echo json_encode($result);
                            }
                            else{
                               $result = new \stdClass();
                               $result->success = false;
                               $result->msg = 'Ocurrió un error.';
                               echo json_encode($result);
                            }
                        }
                        else{
                            $horas_total_turno           = $this->restaHoras($this->findModelHorario($request->post('id_turno'))->hora_fin,$this->findModelHorario($request->post('id_turno'))->hora_inicio);
                            $reporte->horas_trabajadas   = $this->restaHoras($horas_total_turno,$reporte->minutos_atrasos);
                            $reporte->horas_faltas       = $horas_total_turno;
                            $reporte->horas_reemplazo    = $reporte->horas_trabajadas;
                            if($reporte->save()){
                                $result = new \stdClass();
                                $result->success = true;
                                $result->msg = 'Satisfactorio';
                                echo json_encode($result);
                            }
                            else{
                               $result = new \stdClass();
                               $result->success = false;
                               $result->msg = 'Ocurrió un error.';
                               echo json_encode($result);
                            }
                        }
                    }
                    else if($reporte==false){
                        $hora1 = $hora_fin;
                        $hora2 = $this->findModelHorario($id_turno)->hora_fin;
                        if($hora1 < $hora2){
                            $reporte = new datEstadisticas();
                            $reporte->id_horario         = $request->post('id_turno');
                            $reporte->id_tri             = $this->findModelHorario($request->post('id_turno'))->id_trimestre;
                            $reporte->id_docente         = $this->findModelHorario($request->post('id_turno'))->id_docente;
                            $reporte->minutos_salidas_ah = $this->restaHoras($this->findModelHorario($request->post('id_turno'))->hora_fin,$hora_fin);
                            $horas_total_turno           = $this->restaHoras($this->findModelHorario($request->post('id_turno'))->hora_fin,$this->findModelHorario($request->post('id_turno'))->hora_inicio);
                            $reporte->horas_trabajadas   = $this->restaHoras($horas_total_turno,$reporte->minutos_salidas_ah);
                            $reporte->horas_faltas       = $horas_total_turno;
                            $reporte->horas_reemplazo    = $reporte->horas_trabajadas;
                            $reporte->fecha              = $request->post('fecha');
                            if($reporte->save()){
                                $result = new \stdClass();
                                $result->success = true;
                                $result->msg = 'Satisfactorio';
                                echo json_encode($result);
                            }
                            else{
                               $result = new \stdClass();
                               $result->success = false;
                               $result->msg = 'Ocurrió un error.';
                               echo json_encode($result);
                            }
                        }
                        else{
                            $reporte = new datEstadisticas();
                            $reporte->id_horario        = $request->post('id_turno');
                            $reporte->id_tri            = $this->findModelHorario($request->post('id_turno'))->id_trimestre;
                            $reporte->id_docente        = $this->findModelHorario($request->post('id_turno'))->id_docente;
                            $reporte->horas_trabajadas  = $this->restaHoras($this->findModelHorario($request->post('id_turno'))->hora_fin,$this->findModelHorario($request->post('id_turno'))->hora_inicio);
                            $reporte->horas_faltas      = $reporte->horas_trabajadas;
                            $reporte->horas_reemplazo   = $reporte->horas_trabajadas;
                            $reporte->fecha             = $request->post('fecha');
                            $reporte->save();
                            $result = new \stdClass();
                            $result->success = true;
                            $result->msg = 'Satisfactorio';
                            echo json_encode($result);
                        }
                    }
            }
            else {
                   $result = new \stdClass();
                   $result->success = false;
                   $result->msg = 'Ocurrió un error.';
                   echo json_encode($result);
            } 
        }
        else if($query!=null&&$suplantar=='false'&&$query->id_docent_sup==''){
                $query->hora_fin        = $hora_fin;
                $query->ip_fin          = $request->userIP;
                if ($query->save()) {
                    $reporte = $this->findModelEstadistica($id_turno,$fecha);
                    if($reporte){
                        $hora1 = $hora_fin;
                        $hora2 = $this->findModelHorario($id_turno)->hora_fin;
                        if($hora1 < $hora2){
                            $reporte->minutos_salidas_ah = $this->restaHoras($this->findModelHorario($request->post('id_turno'))->hora_fin,$hora_fin);
                            $horas_total_turno           = $this->restaHoras($this->findModelHorario($request->post('id_turno'))->hora_fin,$this->findModelHorario($request->post('id_turno'))->hora_inicio);
                            $total_a_restar              = $this->sumaHoras($reporte->minutos_atrasos,$reporte->minutos_salidas_ah);
                            $reporte->horas_trabajadas   = $this->restaHoras($horas_total_turno,$total_a_restar);
                            if($reporte->save()){
                                $result = new \stdClass();
                                $result->success = true;
                                $result->msg = 'Satisfactorio';
                                echo json_encode($result);
                            }
                            else{
                               $result = new \stdClass();
                               $result->success = false;
                               $result->msg = 'Ocurrió un error.';
                               echo json_encode($result);
                            }
                        }
                        else{
                            $horas_total_turno           = $this->restaHoras($this->findModelHorario($request->post('id_turno'))->hora_fin,$this->findModelHorario($request->post('id_turno'))->hora_inicio);
                            $reporte->horas_trabajadas   = $this->restaHoras($horas_total_turno,$reporte->minutos_atrasos);
                            if($reporte->save()){
                                $result = new \stdClass();
                                $result->success = true;
                                $result->msg = 'Satisfactorio';
                                echo json_encode($result);
                            }
                            else{
                               $result = new \stdClass();
                               $result->success = false;
                               $result->msg = 'Ocurrió un error.';
                               echo json_encode($result);
                            }
                        }
                    }
                    else if($reporte==false){
                        $hora1 = $hora_fin;
                        $hora2 = $this->findModelHorario($id_turno)->hora_fin;
                        if($hora1 < $hora2){
                            $reporte = new datEstadisticas();
                            $reporte->id_horario         = $request->post('id_turno');
                            $reporte->id_tri             = $this->findModelHorario($request->post('id_turno'))->id_trimestre;
                            $reporte->id_docente         = $this->findModelHorario($request->post('id_turno'))->id_docente;
                            $reporte->minutos_salidas_ah = $this->restaHoras($this->findModelHorario($request->post('id_turno'))->hora_fin,$hora_fin);
                            $horas_total_turno           = $this->restaHoras($this->findModelHorario($request->post('id_turno'))->hora_fin,$this->findModelHorario($request->post('id_turno'))->hora_inicio);
                            $reporte->horas_trabajadas   = $this->restaHoras($horas_total_turno,$reporte->minutos_salidas_ah);
                            $reporte->fecha              = $request->post('fecha');
                            if($reporte->save()){
                                $result = new \stdClass();
                                $result->success = true;
                                $result->msg = 'Satisfactorio';
                                echo json_encode($result);
                            }
                            else{
                               $result = new \stdClass();
                               $result->success = false;
                               $result->msg = 'Ocurrió un error.';
                               echo json_encode($result);
                            }
                        }
                        else{
                            $reporte = new datEstadisticas();
                            $reporte->id_tri            = $this->findModelHorario($request->post('id_turno'))->id_trimestre;
                            $reporte->id_docente        = $this->findModelHorario($request->post('id_turno'))->id_docente;
                            $reporte->id_horario        = $request->post('id_turno');
                            $reporte->horas_trabajadas  = $this->restaHoras($this->findModelHorario($request->post('id_turno'))->hora_fin,$this->findModelHorario($request->post('id_turno'))->hora_inicio);
                            $reporte->fecha             = $request->post('fecha');
                            $reporte->save();
                            $result = new \stdClass();
                            $result->success = true;
                            $result->msg = 'Satisfactorio';
                            echo json_encode($result);
                        }
                    }
                    
                }else {
                   $result = new \stdClass();
                   $result->success = false;
                   $result->msg = 'Ocurrió un error.';
                   echo json_encode($result);
                } 
        }
        else if($query==null&&$query1!=null&&$suplantar=='true'&&$this->findDocenteid($user_acl)!=$this->findModelHorario($request->post('id_turno'))->id_docente){
            $result = new \stdClass();
            $result->success = false;
            $result->msg = 'Ya fue finalizado!!';
            echo json_encode($result);
        }
        else if($query==null&&$query1==null&&$suplantar=='true'&&$this->findDocenteid($user_acl)!=$this->findModelHorario($request->post('id_turno'))->id_docente){
            $result = new \stdClass();
            $result->success = 'noiniciado';
            $result->msg = 'Ocurrió un error.';
            echo json_encode($result);
        }
        else if($query==null&&$suplantar=='true'&&$this->findDocenteid($user_acl)==$this->findModelHorario($request->post('id_turno'))->id_docente){
            $result = new \stdClass();
            $result->success = 'noiniciado';
            $result->msg = 'Ocurrió un error.';
            echo json_encode($result);
        }
        else if($query==null&&$query1==null&&$suplantar=='false'&&$this->findDocenteid($user_acl)==$this->findModelHorario($request->post('id_turno'))->id_docente){
            $result = new \stdClass();
            $result->success = 'noiniciado';
            $result->msg = 'Ocurrió un error.';
            echo json_encode($result);
        }
        else if($query1!=null&&$suplantar=='false'&&$this->findDocenteid($user_acl)==$this->findModelHorario($request->post('id_turno'))->id_docente){
            $result = new \stdClass();
            $result->success = false;
            $result->msg = 'Ya fue finalizado!!';
            echo json_encode($result);
        }
    }

    public function restaHoras($horaIni, $horaFin){

        return (date("H:i:s", strtotime("00:00:00") + strtotime($horaIni) - strtotime($horaFin) ));
    }
    public function sumaHoras($horaIni, $horaFin){

        return (date("H:i:s",strtotime($horaIni) + strtotime($horaFin) - strtotime("00:00:00")));
    }

    public function findDocenteid($user_acl){
        if (($model = datDocentes::find()->where(['id_acl_user' => $user_acl])->one()) !== null) {
            return $model->id;
        } else {
            return false;
        }
    }

    public function findModelHorario($id_turno){
        if (($model = datHorario::find()->where(['id' => $id_turno])->one()) !== null) {
            return $model;
        } else {
            return false;
        }
    }

    protected function findModel($id)
    {
        if (($model = datAsistencia::findOne($id)) !== null) {
            return $model;
        } else {
            return false;
        }
    }
    public function findModelEstadistica($id_horario,$fecha)
    {
        if (($model = datEstadisticas::find()->where(['id_horario' => $id_horario, 'fecha' => $fecha])->one()) !== null) {
            if($model->horas_trabajadas==null){
              return $model;
            }
        } else {
            return false;
        }
    }
    public function findModelPeriodoV($dia)
    {
        if($dia=='DOMINGO'||$dia=='SÁBADO')
        {
            if (($model = datPeriodoConfig::find()->where(['tipo' => 'Semipresencial'])->one()) !== null) {
                return $model->id_periodo;
            } else {
                return false;
            }
        }else{
            if (($model = datPeriodoConfig::find()->where(['tipo' => 'Presencial'])->one()) !== null) {
                return $model->id_periodo;
            } else {
                return false;
            }
        }
    }

    public function actionNotificar($id_turno,$hora_fin,$salidas_ah,$horas_total_turno,$horas_trabajadas){

            $cadena = 'Linea 411 Id turno: '.$id_turno.'Hora Fin: '.$hora_fin.' Salida Antes de hora: '.$salidas_ah.' Horas total turno: '.$horas_total_turno.' Horas trabajadas: '.$horas_trabajadas;

                    $message = Yii::$app->mailer->compose();
                    $message->setFrom(array(Yii::$app->params["adminEmail"] =>  Yii::$app->params["adminNameSistem"]))
                            ->setTo('albert840702@gmail.com')
                            ->setSubject('Entrega de material' )
                            ->setTextBody($cadena)                            
                            ->send();

    }

    public function actionSupermetodo(){
        $request = Yii::$app->request;
        $primaryConnection = \Yii::$app->db;
        $command = $primaryConnection->createCommand("TRUNCATE TABLE m_docente.dat_estadisticas RESTART IDENTITY")->queryAll();
        
        $model = datAsistencia::find()->orderBy('id')->asArray()->all();
        foreach ($model as $key => $value) {
            $model1 = new datEstadisticas();
            $model1->id_horario = $value['id_turno'];
            $model1->fecha      = $value['fecha'];
            $model1->id_tri     = $this->findModelHorario($value['id_turno'])->id_trimestre;
            $model1->id_docente = $this->findModelHorario($value['id_turno'])->id_docente;
            if($value['hora_inicio']>$this->findModelHorario($value['id_turno'])->hora_inicio){
                $model1->minutos_atrasos = $this->restaHoras($value['hora_inicio'],$this->findModelHorario($value['id_turno'])->hora_inicio);
            }
            if($value['hora_fin']!=null){
                if($value['hora_fin']<$this->findModelHorario($value['id_turno'])->hora_fin){
                    $model1->minutos_salidas_ah = $this->restaHoras($this->findModelHorario($value['id_turno'])->hora_fin,$value['hora_fin']);
                }
                if($model1->minutos_atrasos==null&&$model1->minutos_salidas_ah==null){
                    $model1->horas_trabajadas   = $this->restaHoras($this->findModelHorario($value['id_turno'])->hora_fin,$this->findModelHorario($value['id_turno'])->hora_inicio);
                }
                elseif ($model1->minutos_atrasos!=null&&$model1->minutos_salidas_ah==null) {
                    $total_horas                = $this->restaHoras($this->findModelHorario($value['id_turno'])->hora_fin,$this->findModelHorario($value['id_turno'])->hora_inicio);
                    $model1->horas_trabajadas   = $this->restaHoras($total_horas,$model1->minutos_atrasos);                
                }
                elseif ($model1->minutos_atrasos==null&&$model1->minutos_salidas_ah!=null) {
                    $total_horas                = $this->restaHoras($this->findModelHorario($value['id_turno'])->hora_fin,$this->findModelHorario($value['id_turno'])->hora_inicio);
                    $model1->horas_trabajadas   = $this->restaHoras($total_horas,$model1->minutos_salidas_ah);
                }
                elseif ($model1->minutos_atrasos!=null&&$model1->minutos_salidas_ah!=null) {
                    $total_horas                = $this->restaHoras($this->findModelHorario($value['id_turno'])->hora_fin,$this->findModelHorario($value['id_turno'])->hora_inicio);
                    $total_a_restar             = $this->sumaHoras($model1->minutos_atrasos,$model1->minutos_salidas_ah);
                    $model1->horas_trabajadas   = $this->restaHoras($total_horas,$total_a_restar);
                }
            }
            if($model1->minutos_atrasos!=null||$model1->minutos_salidas_ah!=null||$model1->horas_trabajadas!=null){
                $model1->save();
            }
        }
    }

}