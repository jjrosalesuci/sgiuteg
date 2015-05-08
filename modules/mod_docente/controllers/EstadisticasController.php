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
        $id_docente         = $request->post('id_docente');
        $filter             = $request->post('query');
        $fecha_rango_1      = date('Y-m-d',strtotime($request->post('fecha_rango_1')));
        $fecha_rango_2      = date('Y-m-d',strtotime($request->post('fecha_rango_2')));
        $offset = $request->post('start');
        $limit = $request->post('limit');
        if ($offset == NULL) {
            $offset = 0;
        }
        if ($limit == NULL) {
            $limit = 7;
        }

        $sql=" SELECT DISTINCT
              (select sum(horas_trabajadas) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_horas_trabajadas,
              (select sum(minutos_atrasos) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_min_atrasos,
              (select sum(minutos_salidas_ah) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_min_salidas_ah,
              (select sum(horas_faltas) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_horas_faltas,
              (select sum(horas_reemplazo) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_horas_reemplazo,
              dat_horario.nombre_docente,
              dat_horario.id_docente
            FROM 
              m_docente.dat_estadisticas, 
              m_docente.dat_horario
            WHERE dat_estadisticas.id_horario = dat_horario.id and dat_horario.id_trimestre = $id_periodo_lectivo
            ORDER BY dat_horario.nombre_docente ASC
            OFFSET $offset LIMIT $limit 
             ;
            ";
        $sqlquery=" SELECT DISTINCT
              (select sum(horas_trabajadas) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_horas_trabajadas,
              (select sum(minutos_atrasos) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_min_atrasos,
              (select sum(minutos_salidas_ah) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_min_salidas_ah,
              (select sum(horas_faltas) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_horas_faltas,
              (select sum(horas_reemplazo) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_horas_reemplazo,
              dat_horario.nombre_docente,
              dat_horario.id_docente
            FROM 
              m_docente.dat_estadisticas, 
              m_docente.dat_horario
            WHERE dat_estadisticas.id_horario = dat_horario.id and dat_horario.id_trimestre = $id_periodo_lectivo and dat_horario.nombre_docente ilike '%$filter%'
            ORDER BY dat_horario.nombre_docente ASC
            OFFSET $offset LIMIT $limit 
             ;
            ";
        $sqlcount=" SELECT DISTINCT
              (select sum(horas_trabajadas) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_horas_trabajadas,
              (select sum(minutos_atrasos) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_min_atrasos,
              (select sum(minutos_salidas_ah) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_min_salidas_ah,
              (select sum(horas_faltas) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_horas_faltas,
              (select sum(horas_reemplazo) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_horas_reemplazo,
              dat_horario.nombre_docente,
              dat_horario.id_docente
            FROM 
              m_docente.dat_estadisticas, 
              m_docente.dat_horario
            WHERE dat_estadisticas.id_horario = dat_horario.id and dat_horario.id_trimestre = $id_periodo_lectivo
             ;
            ";
        $sqlquerycount=" SELECT DISTINCT
              (select sum(horas_trabajadas) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_horas_trabajadas,
              (select sum(minutos_atrasos) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_min_atrasos,
              (select sum(minutos_salidas_ah) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_min_salidas_ah,
              (select sum(horas_faltas) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_horas_faltas,
              (select sum(horas_reemplazo) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_horas_reemplazo,
              dat_horario.nombre_docente,
              dat_horario.id_docente
            FROM 
              m_docente.dat_estadisticas, 
              m_docente.dat_horario
            WHERE dat_estadisticas.id_horario = dat_horario.id and dat_horario.id_trimestre = $id_periodo_lectivo and dat_horario.nombre_docente ilike '%$filter%'
             ;
            ";
        $sql1=" SELECT DISTINCT 
              (select sum(horas_trabajadas) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_trabajadas,
              (select sum(minutos_atrasos) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_min_atrasos,
              (select sum(minutos_salidas_ah) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_min_salidas_ah,
              (select sum(horas_faltas) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_faltas,
              (select sum(horas_reemplazo) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_reemplazo,
              dat_estadisticas.id_docente,
              dat_horario.nombre_docente
            FROM 
              m_docente.dat_estadisticas, 
              m_docente.dat_horario,
              m_docente.dat_asistencia
            WHERE dat_estadisticas.id_horario = dat_horario.id and dat_estadisticas.id_horario = dat_asistencia.id_turno
            ORDER BY dat_horario.nombre_docente ASC
            OFFSET $offset LIMIT $limit  
              ;
            ";
        $sql1count=" SELECT DISTINCT 
              (select sum(horas_trabajadas) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_trabajadas,
              (select sum(minutos_atrasos) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_min_atrasos,
              (select sum(minutos_salidas_ah) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_min_salidas_ah,
              (select sum(horas_faltas) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_faltas,
              (select sum(horas_reemplazo) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_reemplazo,
              dat_estadisticas.id_docente,
              dat_horario.nombre_docente
            FROM 
              m_docente.dat_estadisticas, 
              m_docente.dat_horario,
              m_docente.dat_asistencia
            WHERE dat_estadisticas.id_horario = dat_horario.id and dat_estadisticas.id_horario = dat_asistencia.id_turno
              ;
            ";
        $sql1query=" SELECT DISTINCT 
              (select sum(horas_trabajadas) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_trabajadas,
              (select sum(minutos_atrasos) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_min_atrasos,
              (select sum(minutos_salidas_ah) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_min_salidas_ah,
              (select sum(horas_faltas) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_faltas,
              (select sum(horas_reemplazo) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_reemplazo,
              dat_estadisticas.id_docente,
              dat_horario.nombre_docente
            FROM 
              m_docente.dat_estadisticas, 
              m_docente.dat_horario,
              m_docente.dat_asistencia
            WHERE dat_estadisticas.id_horario = dat_horario.id and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_horario.nombre_docente ilike '%$filter%'
            ORDER BY dat_horario.nombre_docente ASC
            OFFSET $offset LIMIT $limit  
              ;
            ";
        $sql1querycount=" SELECT DISTINCT 
              (select sum(horas_trabajadas) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_trabajadas,
              (select sum(minutos_atrasos) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_min_atrasos,
              (select sum(minutos_salidas_ah) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_min_salidas_ah,
              (select sum(horas_faltas) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_faltas,
              (select sum(horas_reemplazo) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_reemplazo,
              dat_estadisticas.id_docente,
              dat_horario.nombre_docente
            FROM 
              m_docente.dat_estadisticas, 
              m_docente.dat_horario,
              m_docente.dat_asistencia
            WHERE dat_estadisticas.id_horario = dat_horario.id and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_horario.nombre_docente ilike '%$filter%'
              ;
            ";
        $sql3=" SELECT DISTINCT
              (select sum(horas_trabajadas) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_horas_trabajadas,
              (select sum(minutos_atrasos) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_min_atrasos,
              (select sum(minutos_salidas_ah) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_min_salidas_ah,
              (select sum(horas_faltas) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_horas_faltas,
              (select sum(horas_reemplazo) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_horas_reemplazo,
              dat_horario.nombre_docente,
              dat_horario.id_docente
            FROM 
              m_docente.dat_estadisticas, 
              m_docente.dat_horario
            WHERE dat_estadisticas.id_horario = dat_horario.id and dat_horario.id_trimestre = $id_periodo_lectivo and dat_horario.id_docente = $id_docente
             ;
            ";
        $sql4=" SELECT 
              sum(horas_trabajadas) as general_horas_trabajadas,
              sum(minutos_atrasos) as general_min_atrasos,
              sum(minutos_salidas_ah) as general_min_salidas_ah,
              sum(horas_faltas) as general_horas_faltas,
              sum(horas_reemplazo) as general_horas_reemplazo
            FROM 
              m_docente.dat_estadisticas 
            WHERE dat_estadisticas.id_tri = $id_periodo_lectivo
             ;
            ";

        $sql5=" SELECT DISTINCT 
              (select sum(horas_trabajadas) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_trabajadas,
              (select sum(minutos_atrasos) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_min_atrasos,
              (select sum(minutos_salidas_ah) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_min_salidas_ah,
              (select sum(horas_faltas) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_faltas,
              (select sum(horas_reemplazo) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_reemplazo,
              dat_estadisticas.id_docente,
              dat_horario.nombre_docente
            FROM 
              m_docente.dat_estadisticas, 
              m_docente.dat_horario,
              m_docente.dat_asistencia
            WHERE dat_estadisticas.id_horario = dat_horario.id and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_horario.id_docente = $id_docente
              ;
            ";
        $sql6=" SELECT DISTINCT
              (select sum(horas_trabajadas) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between '$fecha_rango_1' and '$fecha_rango_2') as general_horas_trabajadas,
              (select sum(minutos_atrasos) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between '$fecha_rango_1' and '$fecha_rango_2') as general_min_atrasos,
              (select sum(minutos_salidas_ah) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between '$fecha_rango_1' and '$fecha_rango_2') as general_min_salidas_ah,
              (select sum(horas_faltas) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between '$fecha_rango_1' and '$fecha_rango_2') as general_horas_faltas,
              (select sum(horas_reemplazo) from m_docente.dat_estadisticas,m_docente.dat_asistencia where dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_asistencia.fecha between '$fecha_rango_1' and '$fecha_rango_2') as general_horas_reemplazo
            FROM 
              m_docente.dat_estadisticas, 
              m_docente.dat_asistencia
            --WHERE dat_estadisticas.id_horario = dat_asistencia.id_turno
              ;
            ";

        if($filter==''&&$id_periodo_lectivo!=''&&$request->post('fecha_rango_1')==''&&$request->post('fecha_rango_2')==''&&$request->post('graficar')==''){
            //die('1');
            $primaryConnection = \Yii::$app->db;
            $command = $primaryConnection->createCommand($sql);
            $command1 = $primaryConnection->createCommand($sqlcount);
            $estadisticas = $command->queryAll();
            $estadisticas1 = $command1->queryAll();
            $count = count($estadisticas1);
            echo json_encode(array('count' => $count ,'data' => $estadisticas));
        }
        else if($filter!=''){
          //die('2');
          if($id_periodo_lectivo!=''){
            //die('3');
            $primaryConnection = \Yii::$app->db;
            $command = $primaryConnection->createCommand($sqlquery);
            $command1 = $primaryConnection->createCommand($sqlquerycount);
            $estadisticas = $command->queryAll();
            $estadisticas1 = $command1->queryAll();
            $count = count($estadisticas1);
            echo json_encode(array('count' => $count ,'data' => $estadisticas));
          }

          else if($request->post('fecha_rango_1')!=''&&$request->post('fecha_rango_2')!=''){
            //die('4');
            $primaryConnection = \Yii::$app->db;
            $command = $primaryConnection->createCommand($sql1query);
            $command1 = $primaryConnection->createCommand($sql1querycount);
            $estadisticas = $command->queryAll();
            $estadisticas1 = $command1->queryAll();
            $count = count($estadisticas1);
            echo json_encode(array('count' => $count ,'data' => $estadisticas));
          }
        }
        else if ($filter==''&&$request->post('fecha_rango_1')!=''&&$request->post('fecha_rango_2')!=''&&$request->post('graficar')=='') {
            //die('5');
            $primaryConnection = \Yii::$app->db;
            $command = $primaryConnection->createCommand($sql1);
            $command1 = $primaryConnection->createCommand($sql1count);
            $estadisticas = $command->queryAll();
            $estadisticas1 = $command1->queryAll();
            $count = count($estadisticas1);
            echo json_encode(array('count' => $count ,'data' => $estadisticas));
        }

        else if($request->post('graficar')!=''&&$id_docente!=''){
          //die('6');
          if($request->post('graficar')=='yes'){
            //die('7');
            $primaryConnection = \Yii::$app->db;
            $command = $primaryConnection->createCommand($sql3);
            $command2 = $primaryConnection->createCommand($sql4);
            $estadisticas = $command->queryAll();
            $general = $command2->queryAll();
            
            $arreglo = array();
            foreach ($estadisticas as $key => $value) {
              $value['total_min_atrasos'] = $this->formatearHora($value['total_min_atrasos']);
              $value['total_min_salidas_ah'] = $this->formatearHora($value['total_min_salidas_ah']);
              $value['total_horas_trabajadas'] = $this->formatearHora($value['total_horas_trabajadas']);
              $value['total_horas_faltas'] = $this->formatearHora($value['total_horas_faltas']);
              $value['total_horas_reemplazo'] = $this->formatearHora($value['total_horas_reemplazo']);
              $arreglo[] = $value;
            }
            $arreglo2 = array();
            foreach ($general as $key => $value) {
              $value['general_min_atrasos'] = $this->formatearHora($value['general_min_atrasos']);
              $value['general_min_salidas_ah'] = $this->formatearHora($value['general_min_salidas_ah']);
              $value['general_horas_trabajadas'] = $this->formatearHora($value['general_horas_trabajadas']);
              $value['general_horas_faltas'] = $this->formatearHora($value['general_horas_faltas']);
              $value['general_horas_reemplazo'] = $this->formatearHora($value['general_horas_reemplazo']);
              $arreglo2[] = $value;
            }

            $uno = $arreglo2[0]['general_min_atrasos'];
            $dos = $arreglo2[0]['general_min_salidas_ah'];
            $tres = $arreglo2[0]['general_horas_trabajadas'];
            $cuatro = $arreglo2[0]['general_horas_faltas'];
            $cinco = $arreglo2[0]['general_horas_reemplazo'];

            $arreglo3 = array();
            foreach ($arreglo as $key => $value) {
              $value['general_min_atrasos'] = $uno;
              $value['general_min_salidas_ah'] = $dos;
              $value['general_horas_trabajadas'] = $tres;
              $value['general_horas_faltas'] = $cuatro;
              $value['general_horas_reemplazo'] = $cinco;
              $arreglo3[] = $value;
            }

            $arreglo_final = array();
            array_push($arreglo_final, array('name' => 'Horas trabajadas', 'total' => $arreglo3[0]['total_horas_trabajadas'],'total_general' => $arreglo3[0]['general_horas_trabajadas']));
            array_push($arreglo_final, array('name' => 'Horas atrasos', 'total' => $arreglo3[0]['total_min_atrasos'],'total_general' => $arreglo3[0]['general_min_atrasos']));
            array_push($arreglo_final, array('name' => 'Horas salidas ah', 'total' => $arreglo3[0]['total_min_salidas_ah'],'total_general' => $arreglo3[0]['general_min_salidas_ah']));
            array_push($arreglo_final, array('name' => 'Horas faltas', 'total' => $arreglo3[0]['total_horas_faltas'],'total_general' => $arreglo3[0]['general_horas_faltas']));
            array_push($arreglo_final, array('name' => 'Horas reemplazo', 'total' => $arreglo3[0]['total_horas_reemplazo'],'total_general' => $arreglo3[0]['general_horas_reemplazo']));
            

            echo json_encode(array('data' => $arreglo_final));
          }else if ($request->post('graficar')=='por_fecha') {
            //die('8');
            $primaryConnection = \Yii::$app->db;
            $command = $primaryConnection->createCommand($sql5);
            $command2 = $primaryConnection->createCommand($sql6);
            $estadisticas = $command->queryAll();
            $general = $command2->queryAll();
            
            $arreglo = array();
            foreach ($estadisticas as $key => $value) {
              $value['total_min_atrasos'] = $this->formatearHora($value['total_min_atrasos']);
              $value['total_min_salidas_ah'] = $this->formatearHora($value['total_min_salidas_ah']);
              $value['total_horas_trabajadas'] = $this->formatearHora($value['total_horas_trabajadas']);
              $value['total_horas_faltas'] = $this->formatearHora($value['total_horas_faltas']);
              $value['total_horas_reemplazo'] = $this->formatearHora($value['total_horas_reemplazo']);
              $arreglo[] = $value;
            }
            $arreglo2 = array();
            foreach ($general as $key => $value) {
              $value['general_min_atrasos'] = $this->formatearHora($value['general_min_atrasos']);
              $value['general_min_salidas_ah'] = $this->formatearHora($value['general_min_salidas_ah']);
              $value['general_horas_trabajadas'] = $this->formatearHora($value['general_horas_trabajadas']);
              $value['general_horas_faltas'] = $this->formatearHora($value['general_horas_faltas']);
              $value['general_horas_reemplazo'] = $this->formatearHora($value['general_horas_reemplazo']);
              $arreglo2[] = $value;
            }

            $uno = $arreglo2[0]['general_min_atrasos'];
            $dos = $arreglo2[0]['general_min_salidas_ah'];
            $tres = $arreglo2[0]['general_horas_trabajadas'];
            $cuatro = $arreglo2[0]['general_horas_faltas'];
            $cinco = $arreglo2[0]['general_horas_reemplazo'];

            $arreglo3 = array();
            foreach ($arreglo as $key => $value) {
              $value['general_min_atrasos'] = $uno;
              $value['general_min_salidas_ah'] = $dos;
              $value['general_horas_trabajadas'] = $tres;
              $value['general_horas_faltas'] = $cuatro;
              $value['general_horas_reemplazo'] = $cinco;
              $arreglo3[] = $value;
            }

            $arreglo_final = array();
            array_push($arreglo_final, array('name' => 'Horas trabajadas', 'total' => $arreglo3[0]['total_horas_trabajadas'],'total_general' => $arreglo3[0]['general_horas_trabajadas']));
            array_push($arreglo_final, array('name' => 'Horas atrasos', 'total' => $arreglo3[0]['total_min_atrasos'],'total_general' => $arreglo3[0]['general_min_atrasos']));
            array_push($arreglo_final, array('name' => 'Horas salidas ah', 'total' => $arreglo3[0]['total_min_salidas_ah'],'total_general' => $arreglo3[0]['general_min_salidas_ah']));
            array_push($arreglo_final, array('name' => 'Horas faltas', 'total' => $arreglo3[0]['total_horas_faltas'],'total_general' => $arreglo3[0]['general_horas_faltas']));
            array_push($arreglo_final, array('name' => 'Horas reemplazo', 'total' => $arreglo3[0]['total_horas_reemplazo'],'total_general' => $arreglo3[0]['general_horas_reemplazo']));
            

            echo json_encode(array('data' => $arreglo_final));
          }
        }
    }

    public function restaHoras($horaIni, $horaFin){

        return (date("H:i:s", strtotime("00:00:00") + strtotime($horaIni) - strtotime($horaFin) ));
    }
    public function sumaHoras($horaIni, $horaFin){

        return (date("H:i:s",strtotime($horaIni) + strtotime($horaFin) - strtotime("00:00:00")));
    }
    public function formatearHora($hora){
      if($hora!=null){
        list($horas, $minutos, $segundos) = explode(':', $hora);
        $horas = (($horas * 60) + $minutos + ($segundos / 60)) / 60;
        return number_format($horas,2,".",",");
      }
      else {
        $horas = 0;
        return $horas;
      }
    }

}