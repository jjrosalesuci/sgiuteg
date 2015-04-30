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

    public function actionCargar()
    {
        $request            = Yii::$app->request;
        $id_periodo_lectivo = $request->post('id_periodo');
        $fecha_rango_1      = (string)date('Y-m-d',strtotime($request->post('fecha_rango_1')));
        $fecha_rango_2      = (string)date('Y-m-d',strtotime($request->post('fecha_rango_2')));

        /*var_dump($fecha_rango_1);
        die;*/

        $sql=" SELECT DISTINCT 
              (select sum(horas_trabajadas) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente) as total_horas_trabajadas,
              (select sum(minutos_atrasos) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente) as total_min_atrasos,
              (select sum(minutos_salidas_ah) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente) as total_min_salidas_ah,
              (select sum(horas_faltas) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente) as total_horas_faltas,
              (select sum(horas_reemplazo) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente) as total_horas_reemplazo,
              dat_estadisticas.id_docente, 
              dat_horario.nombre_docente
     
            FROM 
              m_docente.dat_estadisticas, 
              m_docente.dat_horario
            WHERE dat_estadisticas.id_horario = dat_horario.id and dat_horario.id_trimestre = $id_periodo_lectivo
              ;
            ";
        $sql1=" SELECT DISTINCT 
              (select sum(horas_trabajadas) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between $fecha_rango_1 and $fecha_rango_2) as total_horas_trabajadas,
              /*(select sum(minutos_atrasos) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between $fecha_rango_1 and $fecha_rango_2) as total_min_atrasos,
              (select sum(minutos_salidas_ah) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between $fecha_rango_1 and $fecha_rango_2) as total_min_salidas_ah,
              (select sum(horas_faltas) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between $fecha_rango_1 and $fecha_rango_2) as total_horas_faltas,
              (select sum(horas_reemplazo) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between $fecha_rango_1 and $fecha_rango_2) as total_horas_reemplazo,*/
              dat_estadisticas.id_docente,
              dat_horario.nombre_docente
            FROM 
              m_docente.dat_estadisticas, 
              m_docente.dat_horario,
              m_docente.dat_asistencia
            WHERE dat_estadisticas.id_horario = dat_horario.id and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_horario.id_trimestre = $id_periodo_lectivo 
              ;
            ";
        $offset = $request->post('start');
        $limit = $request->post('limit');
        if ($offset == NULL) {
            $offset = 0;
        }
        if ($limit == NULL) {
            $limit = 12;
        }

        if($id_periodo_lectivo!=''&&!$fecha_rango_1&&!$fecha_rango_2){
            $primaryConnection = \Yii::$app->db;
            $command = $primaryConnection->createCommand($sql);
            $estadisticas = $command->queryAll();
            echo json_encode(array('data' => $estadisticas));
        }elseif ($id_periodo_lectivo!=''&&$fecha_rango_1!=''&&$fecha_rango_2!='') {
            $primaryConnection = \Yii::$app->db;
            $command = $primaryConnection->createCommand($sql1);
            $estadisticas = $command->queryAll();
            echo json_encode(array('data' => $estadisticas));
        }
    }

    public function restaHoras($horaIni, $horaFin){

        return (date("H:i:s", strtotime("00:00:00") + strtotime($horaIni) - strtotime($horaFin) ));
    }
    public function sumaHoras($horaIni, $horaFin){

        return (date("H:i:s",strtotime($horaIni) + strtotime($horaFin) - strtotime("00:00:00")));
    }

}