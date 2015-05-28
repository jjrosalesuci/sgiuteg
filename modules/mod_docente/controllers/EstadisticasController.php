<?php
/*
/////////Copyright 2015////////
/autor: Alberto               /
/email: albert840702@gmail.com/
///////////////////////////////
*/
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
        $fecha_rango_1      = $request->post('fecha_rango_1');
        $fecha_rango_2      = $request->post('fecha_rango_2');
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
              (select sum(horas_trabajadas) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_trabajadas,
              (select sum(minutos_atrasos) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_min_atrasos,
              (select sum(minutos_salidas_ah) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_min_salidas_ah,
              (select sum(horas_faltas) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_faltas,
              (select sum(horas_reemplazo) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_reemplazo,
              dat_estadisticas.id_docente,
              dat_horario.nombre_docente
            FROM 
              m_docente.dat_estadisticas, 
              m_docente.dat_horario
            WHERE dat_estadisticas.id_horario = dat_horario.id
            ORDER BY dat_horario.nombre_docente ASC
            OFFSET $offset LIMIT $limit  
              ;
            ";
        $sql1count=" SELECT DISTINCT 
              (select sum(horas_trabajadas) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_trabajadas,
              (select sum(minutos_atrasos) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_min_atrasos,
              (select sum(minutos_salidas_ah) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_min_salidas_ah,
              (select sum(horas_faltas) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_faltas,
              (select sum(horas_reemplazo) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_reemplazo,
              dat_estadisticas.id_docente,
              dat_horario.nombre_docente
            FROM 
              m_docente.dat_estadisticas, 
              m_docente.dat_horario
            WHERE dat_estadisticas.id_horario = dat_horario.id
              ;
            ";
        $sql1query=" SELECT DISTINCT 
              (select sum(horas_trabajadas) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_trabajadas,
              (select sum(minutos_atrasos) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_min_atrasos,
              (select sum(minutos_salidas_ah) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_min_salidas_ah,
              (select sum(horas_faltas) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_faltas,
              (select sum(horas_reemplazo) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_reemplazo,
              dat_estadisticas.id_docente,
              dat_horario.nombre_docente
            FROM 
              m_docente.dat_estadisticas, 
              m_docente.dat_horario
            WHERE dat_estadisticas.id_horario = dat_horario.id and dat_horario.nombre_docente ilike '%$filter%'
            ORDER BY dat_horario.nombre_docente ASC
            OFFSET $offset LIMIT $limit  
              ;
            ";
        $sql1querycount=" SELECT DISTINCT 
              (select sum(horas_trabajadas) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_trabajadas,
              (select sum(minutos_atrasos) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_min_atrasos,
              (select sum(minutos_salidas_ah) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_min_salidas_ah,
              (select sum(horas_faltas) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_faltas,
              (select sum(horas_reemplazo) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_reemplazo,
              dat_estadisticas.id_docente,
              dat_horario.nombre_docente
            FROM 
              m_docente.dat_estadisticas, 
              m_docente.dat_horario
            WHERE dat_estadisticas.id_horario = dat_horario.id and dat_horario.nombre_docente ilike '%$filter%'
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
              (select sum(horas_trabajadas) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_trabajadas,
              (select sum(minutos_atrasos) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_min_atrasos,
              (select sum(minutos_salidas_ah) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_min_salidas_ah,
              (select sum(horas_faltas) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_faltas,
              (select sum(horas_reemplazo) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_reemplazo,
              dat_estadisticas.id_docente,
              dat_horario.nombre_docente
            FROM 
              m_docente.dat_estadisticas, 
              m_docente.dat_horario
            WHERE dat_estadisticas.id_horario = dat_horario.id and dat_horario.id_docente = $id_docente
              ;
            ";
        $sql6=" SELECT DISTINCT
              (select sum(horas_trabajadas) from m_docente.dat_estadisticas where dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as general_horas_trabajadas,
              (select sum(minutos_atrasos) from m_docente.dat_estadisticas where dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as general_min_atrasos,
              (select sum(minutos_salidas_ah) from m_docente.dat_estadisticas where dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as general_min_salidas_ah,
              (select sum(horas_faltas) from m_docente.dat_estadisticas where dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as general_horas_faltas,
              (select sum(horas_reemplazo) from m_docente.dat_estadisticas where dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as general_horas_reemplazo
            FROM 
              m_docente.dat_estadisticas 
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

            $arreglo_final = array();
            array_push($arreglo_final, array('name' => 'Horas trabajadas', 'total' => $arreglo[0]['total_horas_trabajadas'],'total_general' => $arreglo2[0]['general_horas_trabajadas']));
            array_push($arreglo_final, array('name' => 'Horas atrasos', 'total' => $arreglo[0]['total_min_atrasos'],'total_general' => $arreglo2[0]['general_min_atrasos']));
            array_push($arreglo_final, array('name' => 'Horas salidas ah', 'total' => $arreglo[0]['total_min_salidas_ah'],'total_general' => $arreglo2[0]['general_min_salidas_ah']));
            array_push($arreglo_final, array('name' => 'Horas faltas', 'total' => $arreglo[0]['total_horas_faltas'],'total_general' => $arreglo2[0]['general_horas_faltas']));
            array_push($arreglo_final, array('name' => 'Horas reemplazo', 'total' => $arreglo[0]['total_horas_reemplazo'],'total_general' => $arreglo2[0]['general_horas_reemplazo']));
            

            echo json_encode(array('data' => $arreglo_final));
          }
          else if ($request->post('graficar')=='por_fecha') {
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

            $arreglo_final = array();
            array_push($arreglo_final, array('name' => 'Horas trabajadas', 'total' => $arreglo[0]['total_horas_trabajadas'],'total_general' => $arreglo2[0]['general_horas_trabajadas']));
            array_push($arreglo_final, array('name' => 'Horas atrasos', 'total' => $arreglo[0]['total_min_atrasos'],'total_general' => $arreglo2[0]['general_min_atrasos']));
            array_push($arreglo_final, array('name' => 'Horas salidas ah', 'total' => $arreglo[0]['total_min_salidas_ah'],'total_general' => $arreglo2[0]['general_min_salidas_ah']));
            array_push($arreglo_final, array('name' => 'Horas faltas', 'total' => $arreglo[0]['total_horas_faltas'],'total_general' => $arreglo2[0]['general_horas_faltas']));
            array_push($arreglo_final, array('name' => 'Horas reemplazo', 'total' => $arreglo[0]['total_horas_reemplazo'],'total_general' => $arreglo2[0]['general_horas_reemplazo']));
            

            echo json_encode(array('data' => $arreglo_final));
          }
          else if ($request->post('graficar')=='universidad') {
            //die('9');
            $primaryConnection = \Yii::$app->db;
            $command2 = $primaryConnection->createCommand($sql4);
            $general = $command2->queryAll();
            
            $arreglo2 = array();
            foreach ($general as $key => $value) {
              $value['general_min_atrasos'] = $this->formatearHora($value['general_min_atrasos']);
              $value['general_min_salidas_ah'] = $this->formatearHora($value['general_min_salidas_ah']);
              $value['general_horas_trabajadas'] = $this->formatearHora($value['general_horas_trabajadas']);
              $value['general_horas_faltas'] = $this->formatearHora($value['general_horas_faltas']);
              $value['general_horas_reemplazo'] = $this->formatearHora($value['general_horas_reemplazo']);
              $arreglo2[] = $value;
            }

            $arreglo_final = array();
            array_push($arreglo_final, array('name' => 'Horas trabajadas', 'total_general' => $arreglo2[0]['general_horas_trabajadas']));
            array_push($arreglo_final, array('name' => 'Horas atrasos', 'total_general' => $arreglo2[0]['general_min_atrasos']));
            array_push($arreglo_final, array('name' => 'Horas salidas ah', 'total_general' => $arreglo2[0]['general_min_salidas_ah']));
            array_push($arreglo_final, array('name' => 'Horas faltas', 'total_general' => $arreglo2[0]['general_horas_faltas']));
            array_push($arreglo_final, array('name' => 'Horas reemplazo', 'total_general' => $arreglo2[0]['general_horas_reemplazo']));
            

            echo json_encode(array('data' => $arreglo_final));
          }
          else if ($request->post('graficar')=='uni_por_fecha') {
            //die('10');
            $primaryConnection = \Yii::$app->db;
            $command2 = $primaryConnection->createCommand($sql6);
            $general = $command2->queryAll();
            
            $arreglo2 = array();
            foreach ($general as $key => $value) {
              $value['general_min_atrasos'] = $this->formatearHora($value['general_min_atrasos']);
              $value['general_min_salidas_ah'] = $this->formatearHora($value['general_min_salidas_ah']);
              $value['general_horas_trabajadas'] = $this->formatearHora($value['general_horas_trabajadas']);
              $value['general_horas_faltas'] = $this->formatearHora($value['general_horas_faltas']);
              $value['general_horas_reemplazo'] = $this->formatearHora($value['general_horas_reemplazo']);
              $arreglo2[] = $value;
            }

            $arreglo_final = array();
            array_push($arreglo_final, array('name' => 'Horas trabajadas', 'total_general' => $arreglo2[0]['general_horas_trabajadas']));
            array_push($arreglo_final, array('name' => 'Horas atrasos', 'total_general' => $arreglo2[0]['general_min_atrasos']));
            array_push($arreglo_final, array('name' => 'Horas salidas ah', 'total_general' => $arreglo2[0]['general_min_salidas_ah']));
            array_push($arreglo_final, array('name' => 'Horas faltas', 'total_general' => $arreglo2[0]['general_horas_faltas']));
            array_push($arreglo_final, array('name' => 'Horas reemplazo', 'total_general' => $arreglo2[0]['general_horas_reemplazo']));
            

            echo json_encode(array('data' => $arreglo_final));
          }
        }
    }

    public function formatearHora($hora){
      if($hora!=null){
        list($horas, $minutos, $segundos) = explode(':', $hora);
        $horas = (($horas * 60) + $minutos + ($segundos / 60)) / 60;
        return number_format($horas,2,".","");
      }
      else {
        $horas = 0;
        return $horas;
      }
    }

    public function actionCreatereport($id_periodo_lectivo,$fecha_rango_1,$fecha_rango_2,$nombre_periodo){
      date_default_timezone_set('America/Havana');

      $estadisticas = '';
      
      if($id_periodo_lectivo!=''){
        $estadisticas = $this->reportePorPeriodo($id_periodo_lectivo);
      }
      else{
        $estadisticas = $this->reportePorFecha($fecha_rango_1,$fecha_rango_2);
      }

      // Create new PHPExcel object
      $objPHPExcel = new \PHPExcel();

      // Set document properties
      $objPHPExcel->getProperties()->setCreator("Uteg")
                     ->setLastModifiedBy("Uteg")
                     ->setTitle("Office 2007 XLSX Document")
                     ->setSubject("Office 2007 XLSX Document")
                     ->setDescription("Document for Office 2007 XLSX, generated using PHP classes.")
                     ->setKeywords("office 2007 openxml php")
                     ->setCategory("Reporte");


      // Add some data
      if($id_periodo_lectivo!=''&&$nombre_periodo!=''){
        $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A1', 'REPORTE POR PERIODO:'.$nombre_periodo);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(
            array(
                      'alignment' => array(
                      'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                )
              ));
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)
                                  ->setName('Verdana')
                                  ->setSize(14)
                                  ->getColor()->setRGB('6F6F6F');
  
      }else{
        $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A1', 'REPORTE POR FECHA DESDE:'.$fecha_rango_1.' HASTA:'.$fecha_rango_2);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(
            array(
                      'alignment' => array(
                      'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                )
              ));
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)
                                  ->setName('Verdana')
                                  ->setSize(14)
                                  ->getColor()->setRGB('6F6F6F');
      }
      
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A2', 'NOMBRE DOCENTE')
                  ->setCellValue('B2', 'T/H TRABAJADAS')
                  ->setCellValue('C2', 'T/H ATRASOS')
                  ->setCellValue('D2', 'T/H SALIDAS ANTES DE HORA')
                  ->setCellValue('E2', 'T/H FALTAS')
                  ->setCellValue('F2', 'T/H REEMPLAZO')
                  ->setCellValue('G2', 'TIPO DOCENTE');

      


      $objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFont()->setBold(true);
      $objPHPExcel->getActiveSheet()->getStyle('A2:G2')->applyFromArray(
        array(
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '70DBFF')
            )
        ));

      // Miscellaneous glyphs, UTF-8
      $i = 3;
      foreach ($estadisticas as $key => $value) {
          $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i, $value['nombre_docente'])
                    ->setCellValue('B'.$i, $this->formatearHora($value['total_horas_trabajadas']))
                    ->setCellValue('C'.$i, $this->formatearHora($value['total_min_atrasos']))
                    ->setCellValue('D'.$i, $this->formatearHora($value['total_min_salidas_ah']))
                    ->setCellValue('E'.$i, $this->formatearHora($value['total_horas_faltas']))
                    ->setCellValue('F'.$i, $this->formatearHora($value['total_horas_reemplazo']))
                    ->setCellValue('G'.$i, $value['tipo_docente']);
          $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->applyFromArray(
          array(
              'fill' => array(
                  'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                  'color' => array('rgb' => 'D6F5FF')
              )
          ));
          $objPHPExcel->getActiveSheet()->getStyle('B'.$i.':F'.$i)->applyFromArray(
          array(
                    'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              )
            ));
          $i++;

      }
      

      // Rename worksheet
      $objPHPExcel->getActiveSheet()->setTitle('Reporte');

      foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {

          $objPHPExcel->setActiveSheetIndex($objPHPExcel->getIndex($worksheet));

          $sheet = $objPHPExcel->getActiveSheet();
          $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
          $cellIterator->setIterateOnlyExistingCells(true);
          /** @var PHPExcel_Cell $cell */
          foreach ($cellIterator as $cell) {
              $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
          }
      }


      // Redirect output to a client’s web browser (Excel2007)
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="Reporte.xlsx"');
      header('Cache-Control: max-age=0');
      // If you're serving to IE 9, then the following may be needed
      header('Cache-Control: max-age=1');

      // If you're serving to IE over SSL, then the following may be needed
      header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
      header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
      header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
      header ('Pragma: public'); // HTTP/1.0

      $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
      $objWriter->save('php://output');
    }

    public function reportePorPeriodo($id_periodo_lectivo){

      $sql=" SELECT DISTINCT
              (select sum(horas_trabajadas) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_horas_trabajadas,
              (select sum(minutos_atrasos) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_min_atrasos,
              (select sum(minutos_salidas_ah) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_min_salidas_ah,
              (select sum(horas_faltas) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_horas_faltas,
              (select sum(horas_reemplazo) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_horas_reemplazo,
              dat_horario.nombre_docente,
              dat_horario.tipo_docente,
              dat_horario.id_docente
            FROM 
              m_docente.dat_estadisticas, 
              m_docente.dat_horario
            WHERE dat_estadisticas.id_horario = dat_horario.id and dat_horario.id_trimestre = $id_periodo_lectivo
            ORDER BY dat_horario.nombre_docente ASC
             ;
            ";
      $primaryConnection = \Yii::$app->db;
      $command = $primaryConnection->createCommand($sql);
      $estadisticas = $command->queryAll();
      return $estadisticas;
    }

    public function reportePorFecha($fecha_rango_1,$fecha_rango_2){

      $sql1=" SELECT DISTINCT 
              (select sum(horas_trabajadas) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_trabajadas,
              (select sum(minutos_atrasos) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_min_atrasos,
              (select sum(minutos_salidas_ah) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_min_salidas_ah,
              (select sum(horas_faltas) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_faltas,
              (select sum(horas_reemplazo) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_reemplazo,
              dat_estadisticas.id_docente,
              dat_horario.tipo_docente,
              dat_horario.nombre_docente
            FROM 
              m_docente.dat_estadisticas, 
              m_docente.dat_horario
            WHERE dat_estadisticas.id_horario = dat_horario.id
            ORDER BY dat_horario.nombre_docente ASC
              ;
            ";
      $primaryConnection = \Yii::$app->db;
      $command = $primaryConnection->createCommand($sql1);
      $estadisticas = $command->queryAll();
      return $estadisticas;
    }


    public function actionFulltime($id_periodo_lectivo,$fecha_rango_1,$fecha_rango_2,$nombre_periodo){
      date_default_timezone_set('America/Havana');

      $estadisticas = '';
      
      if($id_periodo_lectivo!=''){
        $estadisticas = $this->reporteTiempoCompleto($id_periodo_lectivo);
      }
      else{
        $estadisticas = $this->reporteTiempoCompletoFecha($fecha_rango_1,$fecha_rango_2);
      }

      // Create new PHPExcel object
      $objPHPExcel = new \PHPExcel();

      // Set document properties
      $objPHPExcel->getProperties()->setCreator("Uteg")
                     ->setLastModifiedBy("Uteg")
                     ->setTitle("Office 2007 XLSX Document")
                     ->setSubject("Office 2007 XLSX Document")
                     ->setDescription("Document for Office 2007 XLSX, generated using PHP classes.")
                     ->setKeywords("office 2007 openxml php")
                     ->setCategory("Reporte");


      // ENCABEZADOS

      if($id_periodo_lectivo!=''&&$nombre_periodo!=''){
        $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A1', 'REPORTE POR PERIODO:'.$nombre_periodo);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(
            array(
                      'alignment' => array(
                      'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                )
              ));
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)
                                  ->setName('Verdana')
                                  ->setSize(14)
                                  ->getColor()->setRGB('6F6F6F');
  
      }else{
        $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A1', 'REPORTE POR FECHA DESDE:'.$fecha_rango_1.' HASTA:'.$fecha_rango_2);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(
            array(
                      'alignment' => array(
                      'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                )
              ));
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)
                                  ->setName('Verdana')
                                  ->setSize(14)
                                  ->getColor()->setRGB('6F6F6F');
      }

      /////////////////////////////////////////////////////
      ///////////////CANTIDAD DE REEMPLAZOS////////////////
      /////////////////////////////////////////////////////
      
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A5', 'NOMBRE DOCENTE')
                  ->setCellValue('B5', 'CANTIDAD DE REEMPLAZOS')
                  ->setCellValue('C5', 'TOTAL HORAS REEMPLAZOS');

      $objPHPExcel->getActiveSheet()->getStyle('A5:C5')->getFont()->setBold(true);
      $objPHPExcel->getActiveSheet()->getStyle('A5:C5')->applyFromArray(
        array(
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'D6F5FF')
            )
        ));

      $i = 6;
      $inicio = $i;

      foreach ($estadisticas as $key => $value) {
        if($value['cant_reemplazos']!=0){
          $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i, $value['nombre_docente'])
                    ->setCellValue('B'.$i, $value['cant_reemplazos'])
                    ->setCellValue('C'.$i, $this->formatearHora($value['total_horas_reemplazo']));
          $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray(
          array(
              'fill' => array(
                  'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                  'color' => array('rgb' => 'FFFFFF')
              )
          ));
          $objPHPExcel->getActiveSheet()->getStyle('B'.$i.':C'.$i)->applyFromArray(
          array(
                    'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              )
            ));
          $i++;
        }
      }

      if($i == $inicio){
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i, 'No hay datos')
                    ->setCellValue('B'.$i, 0)
                    ->setCellValue('C'.$i, 0);
          $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray(
          array(
              'fill' => array(
                  'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                  'color' => array('rgb' => 'FFFFFF')
              )
          ));
          $objPHPExcel->getActiveSheet()->getStyle('B'.$i.':C'.$i)->applyFromArray(
          array(
                    'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              )
            ));
          $i++;
      }

      //GRAFICO

      $objWorksheet = $objPHPExcel->getActiveSheet();
      $dataseriesLabels2 = array(
      new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$B$5', null, 1), // 2010
      //new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$C$1', null, 1), // 2011
      //new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$D$1', null, 1), // 2012
      );
      $xAxisTickValues2 = array(
      new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$A$6:$A$'.($i-1), null, 4), // Q1 to Q4
      );
      $dataSeriesValues2 = array(
      new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$B$6:$B$'.($i-1), null, 4),
      //new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$C$2:$C$5', null, 4),
      //new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$D$2:$D$5', null, 4),
      );
      // Build the dataseries
      $series2 = new \PHPExcel_Chart_DataSeries(
      \PHPExcel_Chart_DataSeries::TYPE_BARCHART, // plotType
      \PHPExcel_Chart_DataSeries::GROUPING_STANDARD, // plotGrouping
      range(0, count($dataSeriesValues2)-1), // plotOrder
      $dataseriesLabels2, // plotLabel
      $xAxisTickValues2, // plotCategory
      $dataSeriesValues2  // plotValues
      );
      // Set additional dataseries parameters
      // Make it a vertical column rather than a horizontal bar graph
      $series2->setPlotDirection(\PHPExcel_Chart_DataSeries::DIRECTION_COL);
      // Set the series in the plot area
      $plotarea2 = new \PHPExcel_Chart_PlotArea(null, array($series2));
      // Set the chart legend
      $legend2 = new \PHPExcel_Chart_Legend(\PHPExcel_Chart_Legend::POSITION_TOP, null, false);
      $title2 = new \PHPExcel_Chart_Title('');
      //$yAxisLabel2 = new \PHPExcel_Chart_Title('Value ($k)');
      // Create the chart
      $chart2 = new \PHPExcel_Chart(
      'chart2', // name
      $title2, // title
      $legend2, // legend
      $plotarea2, // plotArea
      true, // plotVisibleOnly
      0, // displayBlanksAs
      null // xAxisLabel
      //$yAxisLabel2  // yAxisLabel
      );
      // Set the position where the chart should appear in the worksheet
      $chart2->setTopLeftPosition('E6');
      $chart2->setBottomRightPosition('L22');
      // Add the chart to the worksheet
      $objWorksheet->addChart($chart2);

      ///GRAFICO2////////////

      $objWorksheet = $objPHPExcel->getActiveSheet();
      $dataseriesLabels22 = array(
      new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$C$5', null, 1), // 2010
      //new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$C$1', null, 1), // 2011
      //new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$D$1', null, 1), // 2012
      );
      $xAxisTickValues22 = array(
      new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$A$6:$A$'.($i-1), null, 4), // Q1 to Q4
      );
      $dataSeriesValues22 = array(
      new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$C$6:$C$'.($i-1), null, 4),
      //new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$C$2:$C$5', null, 4),
      //new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$D$2:$D$5', null, 4),
      );
      // Build the dataseries
      $series22 = new \PHPExcel_Chart_DataSeries(
      \PHPExcel_Chart_DataSeries::TYPE_BARCHART, // plotType
      \PHPExcel_Chart_DataSeries::GROUPING_STANDARD, // plotGrouping
      range(0, count($dataSeriesValues22)-1), // plotOrder
      $dataseriesLabels22, // plotLabel
      $xAxisTickValues22, // plotCategory
      $dataSeriesValues22  // plotValues
      );
      // Set additional dataseries parameters
      // Make it a vertical column rather than a horizontal bar graph
      $series22->setPlotDirection(\PHPExcel_Chart_DataSeries::DIRECTION_COL);
      // Set the series in the plot area
      $plotarea22 = new \PHPExcel_Chart_PlotArea(null, array($series22));
      // Set the chart legend
      $legend22 = new \PHPExcel_Chart_Legend(\PHPExcel_Chart_Legend::POSITION_TOP, null, false);
      $title22 = new \PHPExcel_Chart_Title('');
      //$yAxisLabel2 = new \PHPExcel_Chart_Title('Value ($k)');
      // Create the chart
      $chart22 = new \PHPExcel_Chart(
      'chart22', // name
      $title22, // title
      $legend22, // legend
      $plotarea22, // plotArea
      true, // plotVisibleOnly
      0, // displayBlanksAs
      null // xAxisLabel
      //$yAxisLabel2  // yAxisLabel
      );
      // Set the position where the chart should appear in the worksheet
      $chart22->setTopLeftPosition('M6');
      $chart22->setBottomRightPosition('T22');
      // Add the chart to the worksheet
      $objWorksheet->addChart($chart22);

      //FIN GRAFICO/////////////

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, 'TOTAL GENERAL');

      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getFont()->setBold(true);
      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray(
        array(
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'D6F5FF')
            )
        ));

      $total = 0;
      $total2 = 0;
      foreach ($estadisticas as $key => $value){
        $total += $value['cant_reemplazos'];
        $total2 += $this->formatearHora($value['total_horas_reemplazo']);
      }
      
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('B'.$i, $total);
      $objPHPExcel->getActiveSheet()->getStyle('B'.$i)->applyFromArray(
          array(
                    'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              )
      ));

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('C'.$i, $total2);
      $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->applyFromArray(
          array(
                    'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              )
      ));

      /////////////////////////////////////////////////////
      /////////////////CANTIDAD ATRASOS////////////////////
      /////////////////////////////////////////////////////

      if ($i>=22){
        $i += 5;
      }else{
        $i = 27;
      }

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, 'NOMBRE DOCENTE')
                  ->setCellValue('B'.$i, 'CANTIDAD DE ATRASOS')
                  ->setCellValue('C'.$i, 'TOTAL HORAS ATRASOS');

      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getFont()->setBold(true);
      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray(
        array(
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'D6F5FF')
            )
        ));

      $i++;
      $inicio = $i;
      
      foreach ($estadisticas as $key => $value) {
        if($value['cant_atrasos']!=0){
          $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i, $value['nombre_docente'])
                    ->setCellValue('B'.$i, $value['cant_atrasos'])
                    ->setCellValue('C'.$i, $this->formatearHora($value['total_min_atrasos']));
          $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray(
          array(
              'fill' => array(
                  'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                  'color' => array('rgb' => 'FFFFFF')
              )
          ));
          $objPHPExcel->getActiveSheet()->getStyle('B'.$i.':C'.$i)->applyFromArray(
          array(
                    'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              )
            ));
          $i++;
        }

      }

      if($i == $inicio){
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i, 'No hay datos')
                    ->setCellValue('B'.$i, 0)
                    ->setCellValue('C'.$i, 0);
          $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray(
          array(
              'fill' => array(
                  'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                  'color' => array('rgb' => 'FFFFFF')
              )
          ));
          $objPHPExcel->getActiveSheet()->getStyle('B'.$i.':C'.$i)->applyFromArray(
          array(
                    'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              )
            ));
          $i++;
      }

      ////GRAFICO/////////////

      $objWorksheet = $objPHPExcel->getActiveSheet();
      $dataseriesLabels3 = array(
      new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$B$'.($inicio-1), null, 1), // 2010
      //new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$C$1', null, 1), // 2011
      //new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$D$1', null, 1), // 2012
      );
      $xAxisTickValues3 = array(
      new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$A$'.($inicio).':$A$'.($i-1), null, 4), // Q1 to Q4
      );
      $dataSeriesValues3 = array(
      new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$B$'.($inicio).':$B$'.($i-1), null, 4),
      //new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$C$2:$C$5', null, 4),
      //new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$D$2:$D$5', null, 4),
      );
      // Build the dataseries
      $series3 = new \PHPExcel_Chart_DataSeries(
      \PHPExcel_Chart_DataSeries::TYPE_BARCHART, // plotType
      \PHPExcel_Chart_DataSeries::GROUPING_STANDARD, // plotGrouping
      range(0, count($dataSeriesValues3)-1), // plotOrder
      $dataseriesLabels3, // plotLabel
      $xAxisTickValues3, // plotCategory
      $dataSeriesValues3  // plotValues
      );
      // Set additional dataseries parameters
      // Make it a vertical column rather than a horizontal bar graph
      $series3->setPlotDirection(\PHPExcel_Chart_DataSeries::DIRECTION_COL);
      // Set the series in the plot area
      $plotarea3 = new \PHPExcel_Chart_PlotArea(null, array($series3));
      // Set the chart legend
      $legend3 = new \PHPExcel_Chart_Legend(\PHPExcel_Chart_Legend::POSITION_TOP, null, false);
      $title3 = new \PHPExcel_Chart_Title('');
      //$yAxisLabel3 = new \PHPExcel_Chart_Title('Value ($k)');
      // Create the chart
      $chart3 = new \PHPExcel_Chart(
      'chart3', // name
      $title3, // title
      $legend3, // legend
      $plotarea3, // plotArea
      true, // plotVisibleOnly
      0, // displayBlanksAs
      null // xAxisLabel
      //$yAxisLabel3  // yAxisLabel
      );
      // Set the position where the chart should appear in the worksheet
      $chart3->setTopLeftPosition('E'.$inicio);
      $chart3->setBottomRightPosition('L'.($inicio+16));
      // Add the chart to the worksheet
      $objWorksheet->addChart($chart3);

      ////GRAFICO 2

      $objWorksheet = $objPHPExcel->getActiveSheet();
      $dataseriesLabels33 = array(
      new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$C$'.($inicio-1), null, 1), // 2010
      //new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$C$1', null, 1), // 2011
      //new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$D$1', null, 1), // 2012
      );
      $xAxisTickValues33 = array(
      new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$A$'.($inicio).':$A$'.($i-1), null, 4), // Q1 to Q4
      );
      $dataSeriesValues33 = array(
      new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$C$'.($inicio).':$C$'.($i-1), null, 4),
      //new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$C$2:$C$5', null, 4),
      //new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$D$2:$D$5', null, 4),
      );
      // Build the dataseries
      $series33 = new \PHPExcel_Chart_DataSeries(
      \PHPExcel_Chart_DataSeries::TYPE_BARCHART, // plotType
      \PHPExcel_Chart_DataSeries::GROUPING_STANDARD, // plotGrouping
      range(0, count($dataSeriesValues33)-1), // plotOrder
      $dataseriesLabels33, // plotLabel
      $xAxisTickValues33, // plotCategory
      $dataSeriesValues33  // plotValues
      );
      // Set additional dataseries parameters
      // Make it a vertical column rather than a horizontal bar graph
      $series33->setPlotDirection(\PHPExcel_Chart_DataSeries::DIRECTION_COL);
      // Set the series in the plot area
      $plotarea33 = new \PHPExcel_Chart_PlotArea(null, array($series33));
      // Set the chart legend
      $legend33 = new \PHPExcel_Chart_Legend(\PHPExcel_Chart_Legend::POSITION_TOP, null, false);
      $title33 = new \PHPExcel_Chart_Title('');
      //$yAxisLabel3 = new \PHPExcel_Chart_Title('Value ($k)');
      // Create the chart
      $chart33 = new \PHPExcel_Chart(
      'chart33', // name
      $title33, // title
      $legend33, // legend
      $plotarea33, // plotArea
      true, // plotVisibleOnly
      0, // displayBlanksAs
      null // xAxisLabel
      //$yAxisLabel3  // yAxisLabel
      );
      // Set the position where the chart should appear in the worksheet
      $chart33->setTopLeftPosition('M'.$inicio);
      $chart33->setBottomRightPosition('T'.($inicio+16));
      // Add the chart to the worksheet
      $objWorksheet->addChart($chart33);

      ////FIN GRAFICO

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, 'TOTAL GENERAL');

      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getFont()->setBold(true);
      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray(
        array(
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'D6F5FF')
            )
        ));

      $total = 0;
      $total2= 0;
      foreach ($estadisticas as $key => $value){
        $total += $value['cant_atrasos'];
        $total2 += $this->formatearHora($value['total_min_atrasos']);
      }
      
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('B'.$i, $total);
      $objPHPExcel->getActiveSheet()->getStyle('B'.$i)->applyFromArray(
          array(
                    'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              )
      ));

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('C'.$i, $total2);
      $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->applyFromArray(
          array(
                    'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              )
      ));

      //////////////////////////////////////////////////////////
      /////////////////CANTIDAD FALTAS//////////////////////////
      //////////////////////////////////////////////////////////

      if ($i>=44){
        $i += 5;
      }else{
        $i = 49;
      }

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, 'NOMBRE DOCENTE')
                  ->setCellValue('B'.$i, 'CANTIDAD DE FALTAS')
                  ->setCellValue('C'.$i, 'TOTAL HORAS FALTAS');

      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getFont()->setBold(true);
      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray(
        array(
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'D6F5FF')
            )
        ));

      $i++;
      $inicio = $i;
      
      foreach ($estadisticas as $key => $value) {
        if($value['cant_faltas']!=0){
          $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i, $value['nombre_docente'])
                    ->setCellValue('B'.$i, $value['cant_faltas'])
                    ->setCellValue('C'.$i, $this->formatearHora($value['total_horas_faltas']));
          $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray(
          array(
              'fill' => array(
                  'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                  'color' => array('rgb' => 'FFFFFF')
              )
          ));
          $objPHPExcel->getActiveSheet()->getStyle('B'.$i.':C'.$i)->applyFromArray(
          array(
                    'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              )
            ));
          $i++;
        }

      }

      if($i == $inicio){
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i, 'No hay datos')
                    ->setCellValue('B'.$i, 0)
                    ->setCellValue('C'.$i, 0);
          $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray(
          array(
              'fill' => array(
                  'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                  'color' => array('rgb' => 'FFFFFF')
              )
          ));
          $objPHPExcel->getActiveSheet()->getStyle('B'.$i.':C'.$i)->applyFromArray(
          array(
                    'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              )
            ));
          $i++;
      }

      ////GRAFICO////////////

      $objWorksheet = $objPHPExcel->getActiveSheet();
      $dataseriesLabels4 = array(
      new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$B$'.($inicio-1), null, 1), // 2010
      //new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$C$1', null, 1), // 2011
      //new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$D$1', null, 1), // 2012
      );
      $xAxisTickValues4 = array(
      new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$A$'.($inicio).':$A$'.($i-1), null, 4), // Q1 to Q4
      );
      $dataSeriesValues4 = array(
      new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$B$'.($inicio).':$B$'.($i-1), null, 4),
      //new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$C$2:$C$5', null, 4),
      //new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$D$2:$D$5', null, 4),
      );
      // Build the dataseries
      $series4 = new \PHPExcel_Chart_DataSeries(
      \PHPExcel_Chart_DataSeries::TYPE_BARCHART, // plotType
      \PHPExcel_Chart_DataSeries::GROUPING_STANDARD, // plotGrouping
      range(0, count($dataSeriesValues4)-1), // plotOrder
      $dataseriesLabels4, // plotLabel
      $xAxisTickValues4, // plotCategory
      $dataSeriesValues4  // plotValues
      );
      // Set additional dataseries parameters
      // Make it a vertical column rather than a horizontal bar graph
      $series4->setPlotDirection(\PHPExcel_Chart_DataSeries::DIRECTION_COL);
      // Set the series in the plot area
      $plotarea4 = new \PHPExcel_Chart_PlotArea(null, array($series4));
      // Set the chart legend
      $legend4 = new \PHPExcel_Chart_Legend(\PHPExcel_Chart_Legend::POSITION_TOP, null, false);
      $title4 = new \PHPExcel_Chart_Title('');
      //$yAxisLabel4 = new \PHPExcel_Chart_Title('Value ($k)');
      // Create the chart
      $chart4 = new \PHPExcel_Chart(
      'chart4', // name
      $title4, // title
      $legend4, // legend
      $plotarea4, // plotArea
      true, // plotVisibleOnly
      0, // displayBlanksAs
      null // xAxisLabel
      //$yAxisLabel4  // yAxisLabel
      );
      // Set the position where the chart should appear in the worksheet
      $chart4->setTopLeftPosition('E'.$inicio);
      $chart4->setBottomRightPosition('L'.($inicio+16));
      // Add the chart to the worksheet
      $objWorksheet->addChart($chart4);

      ////GRAFICO2

      $objWorksheet = $objPHPExcel->getActiveSheet();
      $dataseriesLabels44 = array(
      new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$C$'.($inicio-1), null, 1), // 2010
      //new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$C$1', null, 1), // 2011
      //new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$D$1', null, 1), // 2012
      );
      $xAxisTickValues44 = array(
      new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$A$'.($inicio).':$A$'.($i-1), null, 4), // Q1 to Q4
      );
      $dataSeriesValues44 = array(
      new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$C$'.($inicio).':$C$'.($i-1), null, 4),
      //new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$C$2:$C$5', null, 4),
      //new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$D$2:$D$5', null, 4),
      );
      // Build the dataseries
      $series44 = new \PHPExcel_Chart_DataSeries(
      \PHPExcel_Chart_DataSeries::TYPE_BARCHART, // plotType
      \PHPExcel_Chart_DataSeries::GROUPING_STANDARD, // plotGrouping
      range(0, count($dataSeriesValues44)-1), // plotOrder
      $dataseriesLabels44, // plotLabel
      $xAxisTickValues44, // plotCategory
      $dataSeriesValues44  // plotValues
      );
      // Set additional dataseries parameters
      // Make it a vertical column rather than a horizontal bar graph
      $series44->setPlotDirection(\PHPExcel_Chart_DataSeries::DIRECTION_COL);
      // Set the series in the plot area
      $plotarea44 = new \PHPExcel_Chart_PlotArea(null, array($series44));
      // Set the chart legend
      $legend44 = new \PHPExcel_Chart_Legend(\PHPExcel_Chart_Legend::POSITION_TOP, null, false);
      $title44 = new \PHPExcel_Chart_Title('');
      //$yAxisLabel4 = new \PHPExcel_Chart_Title('Value ($k)');
      // Create the chart
      $chart44 = new \PHPExcel_Chart(
      'chart44', // name
      $title44, // title
      $legend44, // legend
      $plotarea44, // plotArea
      true, // plotVisibleOnly
      0, // displayBlanksAs
      null // xAxisLabel
      //$yAxisLabel4  // yAxisLabel
      );
      // Set the position where the chart should appear in the worksheet
      $chart44->setTopLeftPosition('M'.$inicio);
      $chart44->setBottomRightPosition('T'.($inicio+16));
      // Add the chart to the worksheet
      $objWorksheet->addChart($chart44);

      ////FIN GRAFICO

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, 'TOTAL GENERAL');

      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getFont()->setBold(true);
      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray(
        array(
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'D6F5FF')
            )
        ));

      $total = 0;
      $total2 = 0;
      foreach ($estadisticas as $key => $value){
        $total += $value['cant_faltas'];
        $total2 += $this->formatearHora($value['total_horas_faltas']);
      }
      
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('B'.$i, $total);
      $objPHPExcel->getActiveSheet()->getStyle('B'.$i)->applyFromArray(
          array(
                    'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              )
      ));

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('C'.$i, $total2);
      $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->applyFromArray(
          array(
                    'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              )
      ));

      /////////////////////////////////////////////////////////////////
      /////////////////CANTIDAD SALIDAS ANTES DE HORA//////////////////
      /////////////////////////////////////////////////////////////////

      if ($i>=66){
        $i += 5;
      }else{
        $i = 71;
      }

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, 'NOMBRE DOCENTE')
                  ->setCellValue('B'.$i, 'CANTIDAD SALIDAS AH')
                  ->setCellValue('C'.$i, 'TOTAL SALIDAS AH');

      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getFont()->setBold(true);
      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray(
        array(
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'D6F5FF')
            )
        ));

      $i++;
      $inicio = $i;
      
      foreach ($estadisticas as $key => $value) {
        if($value['cant_salidas_ah']!=0){
          $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i, $value['nombre_docente'])
                    ->setCellValue('B'.$i, $value['cant_salidas_ah'])
                    ->setCellValue('C'.$i, $this->formatearHora($value['total_min_salidas_ah']));
          $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray(
          array(
              'fill' => array(
                  'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                  'color' => array('rgb' => 'FFFFFF')
              )
          ));
          $objPHPExcel->getActiveSheet()->getStyle('B'.$i.':C'.$i)->applyFromArray(
          array(
                    'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              )
            ));
          $i++;
        }

      }

      if($i == $inicio){
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i, 'No hay datos')
                    ->setCellValue('B'.$i, 0)
                    ->setCellValue('C'.$i, 0);
          $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray(
          array(
              'fill' => array(
                  'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                  'color' => array('rgb' => 'FFFFFF')
              )
          ));
          $objPHPExcel->getActiveSheet()->getStyle('B'.$i.':C'.$i)->applyFromArray(
          array(
                    'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              )
            ));
          $i++;
      }

      ////GRAFICO//////////////

      $objWorksheet = $objPHPExcel->getActiveSheet();
      $dataseriesLabels5 = array(
      new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$B$'.($inicio-1), null, 1), // 2010
      //new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$C$1', null, 1), // 2011
      //new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$D$1', null, 1), // 2012
      );
      $xAxisTickValues5 = array(
      new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$A$'.($inicio).':$A$'.($i-1), null, 4), // Q1 to Q4
      );
      $dataSeriesValues5 = array(
      new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$B$'.($inicio).':$B$'.($i-1), null, 4),
      //new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$C$2:$C$5', null, 4),
      //new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$D$2:$D$5', null, 4),
      );
      // Build the dataseries
      $series5 = new \PHPExcel_Chart_DataSeries(
      \PHPExcel_Chart_DataSeries::TYPE_BARCHART, // plotType
      \PHPExcel_Chart_DataSeries::GROUPING_STANDARD, // plotGrouping
      range(0, count($dataSeriesValues5)-1), // plotOrder
      $dataseriesLabels5, // plotLabel
      $xAxisTickValues5, // plotCategory
      $dataSeriesValues5  // plotValues
      );
      // Set additional dataseries parameters
      // Make it a vertical column rather than a horizontal bar graph
      $series5->setPlotDirection(\PHPExcel_Chart_DataSeries::DIRECTION_COL);
      // Set the series in the plot area
      $plotarea5 = new \PHPExcel_Chart_PlotArea(null, array($series5));
      // Set the chart legend
      $legend5 = new \PHPExcel_Chart_Legend(\PHPExcel_Chart_Legend::POSITION_TOP, null, false);
      $title5 = new \PHPExcel_Chart_Title('');
      //$yAxisLabel4 = new \PHPExcel_Chart_Title('Value ($k)');
      // Create the chart
      $chart5 = new \PHPExcel_Chart(
      'chart5', // name
      $title5, // title
      $legend5, // legend
      $plotarea5, // plotArea
      true, // plotVisibleOnly
      0, // displayBlanksAs
      null // xAxisLabel
      //$yAxisLabel5  // yAxisLabel
      );
      // Set the position where the chart should appear in the worksheet
      $chart5->setTopLeftPosition('E'.$inicio);
      $chart5->setBottomRightPosition('L'.($inicio+16));
      // Add the chart to the worksheet
      $objWorksheet->addChart($chart5);

      //////GRAFICO2////////

      $objWorksheet = $objPHPExcel->getActiveSheet();
      $dataseriesLabels55 = array(
      new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$C$'.($inicio-1), null, 1), // 2010
      //new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$C$1', null, 1), // 2011
      //new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$D$1', null, 1), // 2012
      );
      $xAxisTickValues55 = array(
      new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$A$'.($inicio).':$A$'.($i-1), null, 4), // Q1 to Q4
      );
      $dataSeriesValues55 = array(
      new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$C$'.($inicio).':$C$'.($i-1), null, 4),
      //new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$C$2:$C$5', null, 4),
      //new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$D$2:$D$5', null, 4),
      );
      // Build the dataseries
      $series55 = new \PHPExcel_Chart_DataSeries(
      \PHPExcel_Chart_DataSeries::TYPE_BARCHART, // plotType
      \PHPExcel_Chart_DataSeries::GROUPING_STANDARD, // plotGrouping
      range(0, count($dataSeriesValues55)-1), // plotOrder
      $dataseriesLabels55, // plotLabel
      $xAxisTickValues55, // plotCategory
      $dataSeriesValues55  // plotValues
      );
      // Set additional dataseries parameters
      // Make it a vertical column rather than a horizontal bar graph
      $series55->setPlotDirection(\PHPExcel_Chart_DataSeries::DIRECTION_COL);
      // Set the series in the plot area
      $plotarea55 = new \PHPExcel_Chart_PlotArea(null, array($series55));
      // Set the chart legend
      $legend55 = new \PHPExcel_Chart_Legend(\PHPExcel_Chart_Legend::POSITION_TOP, null, false);
      $title55 = new \PHPExcel_Chart_Title('');
      //$yAxisLabel4 = new \PHPExcel_Chart_Title('Value ($k)');
      // Create the chart
      $chart55 = new \PHPExcel_Chart(
      'chart55', // name
      $title55, // title
      $legend55, // legend
      $plotarea55, // plotArea
      true, // plotVisibleOnly
      0, // displayBlanksAs
      null // xAxisLabel
      //$yAxisLabel5  // yAxisLabel
      );
      // Set the position where the chart should appear in the worksheet
      $chart55->setTopLeftPosition('M'.$inicio);
      $chart55->setBottomRightPosition('T'.($inicio+16));
      // Add the chart to the worksheet
      $objWorksheet->addChart($chart55);

      ////////FIN GRAFICO////

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, 'TOTAL GENERAL');

      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getFont()->setBold(true);
      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray(
        array(
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'D6F5FF')
            )
        ));

      $total = 0;
      $total2 = 0;
      foreach ($estadisticas as $key => $value){
        $total += $value['cant_salidas_ah'];
        $total2 += $this->formatearHora($value['total_min_salidas_ah']);
      }
      
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('B'.$i, $total);
      $objPHPExcel->getActiveSheet()->getStyle('B'.$i)->applyFromArray(
          array(
                    'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              )
      ));

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('C'.$i, $total2);
      $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->applyFromArray(
          array(
                    'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              )
      ));

      // Rename worksheet
      $objPHPExcel->getActiveSheet()->setTitle('Reporte');

      foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {

          $objPHPExcel->setActiveSheetIndex($objPHPExcel->getIndex($worksheet));

          $sheet = $objPHPExcel->getActiveSheet();
          $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
          $cellIterator->setIterateOnlyExistingCells(true);
          /** @var PHPExcel_Cell $cell */
          foreach ($cellIterator as $cell) {
              $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
          }
      }


      // Redirect output to a client’s web browser (Excel2007)
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="Reporte.xlsx"');
      header('Cache-Control: max-age=0');
      // If you're serving to IE 9, then the following may be needed
      header('Cache-Control: max-age=1');

      // If you're serving to IE over SSL, then the following may be needed
      header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
      header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
      header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
      header ('Pragma: public'); // HTTP/1.0

      $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
      $objWriter->setIncludeCharts(TRUE);
      $objWriter->save('php://output');
    }

    public function reporteTiempoCompleto($id_periodo_lectivo){
    
      $sql="  SELECT DISTINCT
          dat_horario.nombre_docente,
          dat_horario.id_docente,
          (select count(horas_reemplazo) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as cant_reemplazos,
          (select count(minutos_atrasos) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as cant_atrasos,
          (select count(horas_faltas) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as cant_faltas,
          (select count(minutos_salidas_ah) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as cant_salidas_ah,
          (select sum(horas_reemplazo) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_horas_reemplazo,
          (select sum(minutos_atrasos) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_min_atrasos,
          (select sum(horas_faltas) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_horas_faltas,
          (select sum(minutos_salidas_ah) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_min_salidas_ah
       FROM 
          m_docente.dat_estadisticas, 
          m_docente.dat_horario
        WHERE dat_estadisticas.id_horario = dat_horario.id and dat_horario.id_trimestre = $id_periodo_lectivo and dat_horario.tipo_docente = 'Tiempo completo'
        ;
        ";
      $primaryConnection = \Yii::$app->db;
      $command = $primaryConnection->createCommand($sql);
      $estadisticas = $command->queryAll();
      return $estadisticas;
    }

    public function reporteTiempoCompletoFecha($fecha_rango_1,$fecha_rango_2){
    
      $sql="  SELECT DISTINCT 
         (select count(horas_reemplazo) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as cant_reemplazos,
         (select count(minutos_atrasos) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as cant_atrasos,
         (select count(horas_faltas) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as cant_faltas,
         (select count(minutos_salidas_ah) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as cant_salidas_ah,
         (select sum(horas_reemplazo) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_reemplazo,
         (select sum(minutos_atrasos) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_min_atrasos,
         (select sum(horas_faltas) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_faltas,
         (select sum(minutos_salidas_ah) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_min_salidas_ah,
         dat_estadisticas.id_docente,
         dat_horario.nombre_docente
      FROM 
         m_docente.dat_estadisticas, 
         m_docente.dat_horario
      WHERE dat_estadisticas.id_horario = dat_horario.id and dat_horario.tipo_docente = 'Tiempo completo'
      ;      
      ";
      $primaryConnection = \Yii::$app->db;
      $command = $primaryConnection->createCommand($sql);
      $estadisticas = $command->queryAll();
      return $estadisticas;
    }

    ///////////////////////////////////////////////////////////////
    ////////////////////REPORTE ACADEMICO//////////////////////////
    ///////////////////////////////////////////////////////////////

    public function actionAcademico($id_periodo_lectivo,$fecha_rango_1,$fecha_rango_2,$nombre_periodo){
      date_default_timezone_set('America/Havana');

      $estadisticas = '';
      
      if($id_periodo_lectivo!=''){
        $estadisticas = $this->reporteAcademicoPeriodo($id_periodo_lectivo);
      }
      else{
        $estadisticas = $this->reporteAcademicoFecha($fecha_rango_1,$fecha_rango_2);
      }

      // Create new PHPExcel object
      $objPHPExcel = new \PHPExcel();

      // Set document properties
      $objPHPExcel->getProperties()->setCreator("Uteg")
                     ->setLastModifiedBy("Uteg")
                     ->setTitle("Office 2007 XLSX Document")
                     ->setSubject("Office 2007 XLSX Document")
                     ->setDescription("Document for Office 2007 XLSX, generated using PHP classes.")
                     ->setKeywords("office 2007 openxml php")
                     ->setCategory("Reporte");


      // Add some data
      if($id_periodo_lectivo!=''&&$nombre_periodo!=''){
        $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A1', $nombre_periodo)
                  ->setCellValue('A2', 'REPORTE DE HORAS ACADEMICAS');
        $objPHPExcel->getActiveSheet()->mergeCells('A1:J1');
        $objPHPExcel->getActiveSheet()->mergeCells('A2:J2');
        $objPHPExcel->getActiveSheet()->getStyle('A1:A2')->applyFromArray(
            array(
                      'alignment' => array(
                      'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                )
              ));
        $objPHPExcel->getActiveSheet()->getStyle('A1:A2')->getFont()->setBold(true)
                                  ->setName('Verdana')
                                  ->setSize(14)
                                  ->getColor()->setRGB('6F6F6F');
  
      }else{
        $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A1', 'POR FECHA DESDE:'.$fecha_rango_1.' HASTA:'.$fecha_rango_2)
                  ->setCellValue('A2', 'REPORTE DE HORAS ACADEMICAS');
        $objPHPExcel->getActiveSheet()->mergeCells('A1:J1');
        $objPHPExcel->getActiveSheet()->mergeCells('A2:J2');
        $objPHPExcel->getActiveSheet()->getStyle('A1:A2')->applyFromArray(
            array(
                      'alignment' => array(
                      'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                )
              ));
        $objPHPExcel->getActiveSheet()->getStyle('A1:A2')->getFont()->setBold(true)
                                  ->setName('Verdana')
                                  ->setSize(14)
                                  ->getColor()->setRGB('6F6F6F');

      }

      $objPHPExcel->getActiveSheet()->getStyle('A4:J4')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
      
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A4', 'N')
                  ->setCellValue('B4', 'DOCENTE')
                  ->setCellValue('C4', 'MATERIA')
                  ->setCellValue('D4', 'DIAS')
                  ->setCellValue('E4', 'HORA DE ENTRADA')
                  ->setCellValue('F4', 'HORA DE SALIDA')
                  ->setCellValue('G4', 'HORAS A DICTAR')
                  ->setCellValue('H4', 'HORAS DICTADAS')
                  ->setCellValue('I4', 'TOTAL HORAS DICTADAS')
                  ->setCellValue('J4', 'OBSERVACION');

      $objPHPExcel->getActiveSheet()->getStyle('A4:J4')->getFont()->setBold(true);
      $objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(60);
      $objPHPExcel->getActiveSheet()->getStyle('A4:J4')->applyFromArray(
          array(
              'fill' => array(
                  'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                  'color' => array('rgb' => 'E6E6E6')
              )
          ));

      // Miscellaneous glyphs, UTF-8
      $i = 5;
      $c = 1;
      foreach ($estadisticas as $key => $value) {
          $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':J'.$i)->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
          $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i, $c)
                    ->setCellValue('B'.$i, $value['nombre_docente'])
                    ->setCellValue('C'.$i, $value['nombre_materia'])
                    ->setCellValue('D'.$i, $value['fecha'])
                    ->setCellValue('E'.$i, $value['hora_inicio'])
                    ->setCellValue('F'.$i, $value['hora_fin'])
                    ->setCellValue('G'.$i, $this->formatearHora($value['horas_a_dictar']))
                    ->setCellValue('H'.$i, $this->formatearHora($value['horas_trabajadas']));
          /*$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->applyFromArray(
          array(
              'fill' => array(
                  'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                  'color' => array('rgb' => 'D6F5FF')
              )
          ));
          $objPHPExcel->getActiveSheet()->getStyle('B'.$i.':F'.$i)->applyFromArray(
          array(
                    'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              )
            ));*/
          $i++;
          $c++;

      }
      

      // Rename worksheet
      $objPHPExcel->getActiveSheet()->setTitle('Reporte');

      foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {

          $objPHPExcel->setActiveSheetIndex($objPHPExcel->getIndex($worksheet));

          $sheet = $objPHPExcel->getActiveSheet();
          $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
          $cellIterator->setIterateOnlyExistingCells(true);
          /** @var PHPExcel_Cell $cell */
          foreach ($cellIterator as $cell) {
              $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
          }
      }


      // Redirect output to a client’s web browser (Excel2007)
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="Reporte.xlsx"');
      header('Cache-Control: max-age=0');
      // If you're serving to IE 9, then the following may be needed
      header('Cache-Control: max-age=1');

      // If you're serving to IE over SSL, then the following may be needed
      header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
      header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
      header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
      header ('Pragma: public'); // HTTP/1.0

      $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
      $objWriter->save('php://output');
    }

    public function reporteAcademicoPeriodo($id_periodo_lectivo){
    
      $sql="  SELECT 
          dat_horario.nombre_docente, 
          dat_horario.nombre_materia, 
          dat_asistencia.fecha, 
          dat_horario.hora_inicio, 
          dat_horario.hora_fin, 
          dat_horario.hora_fin - dat_horario.hora_inicio as horas_a_dictar,
          dat_estadisticas.horas_trabajadas
        FROM 
          m_docente.dat_asistencia, 
          m_docente.dat_estadisticas, 
          m_docente.dat_horario
        WHERE dat_estadisticas.id_horario = dat_horario.id and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_estadisticas.id_tri = $id_periodo_lectivo and dat_estadisticas.fecha = dat_asistencia.fecha
        ORDER BY nombre_docente,nombre_materia,fecha ASC
        ;
      ";
      $primaryConnection = \Yii::$app->db;
      $command = $primaryConnection->createCommand($sql);
      $estadisticas = $command->queryAll();
      return $estadisticas;
    }

    public function reporteAcademicoFecha($fecha_rango_1,$fecha_rango_2){
    
      $sql="  SELECT 
          dat_horario.nombre_docente, 
          dat_horario.nombre_materia, 
          dat_asistencia.fecha, 
          dat_horario.hora_inicio, 
          dat_horario.hora_fin, 
          dat_horario.hora_fin - dat_horario.hora_inicio as horas_a_dictar,
          dat_estadisticas.horas_trabajadas
        FROM 
          m_docente.dat_asistencia, 
          m_docente.dat_estadisticas, 
          m_docente.dat_horario
        WHERE dat_estadisticas.id_horario = dat_horario.id and dat_estadisticas.id_horario = dat_asistencia.id_turno and dat_estadisticas.fecha = dat_asistencia.fecha and dat_asistencia.fecha between '$fecha_rango_1' and '$fecha_rango_2' 
        ORDER BY nombre_docente,nombre_materia,fecha ASC
        ;
      ";
      $primaryConnection = \Yii::$app->db;
      $command = $primaryConnection->createCommand($sql);
      $estadisticas = $command->queryAll();
      return $estadisticas;
    }

    ///////////////////////////////////////
    ///////////REPORTE MEDIO TIEMPO////////
    ///////////////////////////////////////

    public function actionMiddletime($id_periodo_lectivo,$fecha_rango_1,$fecha_rango_2,$nombre_periodo){
      date_default_timezone_set('America/Havana');

      $estadisticas = '';
      
      if($id_periodo_lectivo!=''){
        $estadisticas = $this->reporteMedioTiempo($id_periodo_lectivo);
      }
      else{
        $estadisticas = $this->reporteMedioTiempoFecha($fecha_rango_1,$fecha_rango_2);
      }

      // Create new PHPExcel object
      $objPHPExcel = new \PHPExcel();

      // Set document properties
      $objPHPExcel->getProperties()->setCreator("Uteg")
                     ->setLastModifiedBy("Uteg")
                     ->setTitle("Office 2007 XLSX Document")
                     ->setSubject("Office 2007 XLSX Document")
                     ->setDescription("Document for Office 2007 XLSX, generated using PHP classes.")
                     ->setKeywords("office 2007 openxml php")
                     ->setCategory("Reporte");


      // ENCABEZADOS

      if($id_periodo_lectivo!=''&&$nombre_periodo!=''){
        $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A1', 'REPORTE POR PERIODO:'.$nombre_periodo);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(
            array(
                      'alignment' => array(
                      'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                )
              ));
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)
                                  ->setName('Verdana')
                                  ->setSize(14)
                                  ->getColor()->setRGB('6F6F6F');
  
      }else{
        $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A1', 'REPORTE POR FECHA DESDE:'.$fecha_rango_1.' HASTA:'.$fecha_rango_2);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(
            array(
                      'alignment' => array(
                      'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                )
              ));
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)
                                  ->setName('Verdana')
                                  ->setSize(14)
                                  ->getColor()->setRGB('6F6F6F');
      }

      /////////////////////////////////////////////////////
      ///////////////CANTIDAD DE REEMPLAZOS////////////////
      /////////////////////////////////////////////////////
      
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A5', 'NOMBRE DOCENTE')
                  ->setCellValue('B5', 'CANTIDAD DE REEMPLAZOS')
                  ->setCellValue('C5', 'TOTAL HORAS REEMPLAZOS');

      $objPHPExcel->getActiveSheet()->getStyle('A5:C5')->getFont()->setBold(true);
      $objPHPExcel->getActiveSheet()->getStyle('A5:C5')->applyFromArray(
        array(
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'D6F5FF')
            )
        ));

      $i = 6;
      $inicio = $i;

      foreach ($estadisticas as $key => $value) {
        if($value['cant_reemplazos']!=0){
          $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i, $value['nombre_docente'])
                    ->setCellValue('B'.$i, $value['cant_reemplazos'])
                    ->setCellValue('C'.$i, $this->formatearHora($value['total_horas_reemplazo']));
          $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray(
          array(
              'fill' => array(
                  'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                  'color' => array('rgb' => 'FFFFFF')
              )
          ));
          $objPHPExcel->getActiveSheet()->getStyle('B'.$i.':C'.$i)->applyFromArray(
          array(
                    'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              )
            ));
          $i++;
        }
      }

      if($i == $inicio){
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i, 'No hay datos')
                    ->setCellValue('B'.$i, 0)
                    ->setCellValue('C'.$i, 0);
          $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray(
          array(
              'fill' => array(
                  'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                  'color' => array('rgb' => 'FFFFFF')
              )
          ));
          $objPHPExcel->getActiveSheet()->getStyle('B'.$i.':C'.$i)->applyFromArray(
          array(
                    'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              )
            ));
          $i++;
      }

      //GRAFICO

      $objWorksheet = $objPHPExcel->getActiveSheet();
      $dataseriesLabels2 = array(
      new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$B$5', null, 1), // 2010
      //new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$C$1', null, 1), // 2011
      //new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$D$1', null, 1), // 2012
      );
      $xAxisTickValues2 = array(
      new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$A$6:$A$'.($i-1), null, 4), // Q1 to Q4
      );
      $dataSeriesValues2 = array(
      new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$B$6:$B$'.($i-1), null, 4),
      //new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$C$2:$C$5', null, 4),
      //new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$D$2:$D$5', null, 4),
      );
      // Build the dataseries
      $series2 = new \PHPExcel_Chart_DataSeries(
      \PHPExcel_Chart_DataSeries::TYPE_BARCHART, // plotType
      \PHPExcel_Chart_DataSeries::GROUPING_STANDARD, // plotGrouping
      range(0, count($dataSeriesValues2)-1), // plotOrder
      $dataseriesLabels2, // plotLabel
      $xAxisTickValues2, // plotCategory
      $dataSeriesValues2  // plotValues
      );
      // Set additional dataseries parameters
      // Make it a vertical column rather than a horizontal bar graph
      $series2->setPlotDirection(\PHPExcel_Chart_DataSeries::DIRECTION_COL);
      // Set the series in the plot area
      $plotarea2 = new \PHPExcel_Chart_PlotArea(null, array($series2));
      // Set the chart legend
      $legend2 = new \PHPExcel_Chart_Legend(\PHPExcel_Chart_Legend::POSITION_TOP, null, false);
      $title2 = new \PHPExcel_Chart_Title('');
      //$yAxisLabel2 = new \PHPExcel_Chart_Title('Value ($k)');
      // Create the chart
      $chart2 = new \PHPExcel_Chart(
      'chart2', // name
      $title2, // title
      $legend2, // legend
      $plotarea2, // plotArea
      true, // plotVisibleOnly
      0, // displayBlanksAs
      null // xAxisLabel
      //$yAxisLabel2  // yAxisLabel
      );
      // Set the position where the chart should appear in the worksheet
      $chart2->setTopLeftPosition('E6');
      $chart2->setBottomRightPosition('L22');
      // Add the chart to the worksheet
      $objWorksheet->addChart($chart2);

      ///GRAFICO2////////////

      $objWorksheet = $objPHPExcel->getActiveSheet();
      $dataseriesLabels22 = array(
      new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$C$5', null, 1), // 2010
      //new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$C$1', null, 1), // 2011
      //new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$D$1', null, 1), // 2012
      );
      $xAxisTickValues22 = array(
      new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$A$6:$A$'.($i-1), null, 4), // Q1 to Q4
      );
      $dataSeriesValues22 = array(
      new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$C$6:$C$'.($i-1), null, 4),
      //new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$C$2:$C$5', null, 4),
      //new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$D$2:$D$5', null, 4),
      );
      // Build the dataseries
      $series22 = new \PHPExcel_Chart_DataSeries(
      \PHPExcel_Chart_DataSeries::TYPE_BARCHART, // plotType
      \PHPExcel_Chart_DataSeries::GROUPING_STANDARD, // plotGrouping
      range(0, count($dataSeriesValues22)-1), // plotOrder
      $dataseriesLabels22, // plotLabel
      $xAxisTickValues22, // plotCategory
      $dataSeriesValues22  // plotValues
      );
      // Set additional dataseries parameters
      // Make it a vertical column rather than a horizontal bar graph
      $series22->setPlotDirection(\PHPExcel_Chart_DataSeries::DIRECTION_COL);
      // Set the series in the plot area
      $plotarea22 = new \PHPExcel_Chart_PlotArea(null, array($series22));
      // Set the chart legend
      $legend22 = new \PHPExcel_Chart_Legend(\PHPExcel_Chart_Legend::POSITION_TOP, null, false);
      $title22 = new \PHPExcel_Chart_Title('');
      //$yAxisLabel2 = new \PHPExcel_Chart_Title('Value ($k)');
      // Create the chart
      $chart22 = new \PHPExcel_Chart(
      'chart22', // name
      $title22, // title
      $legend22, // legend
      $plotarea22, // plotArea
      true, // plotVisibleOnly
      0, // displayBlanksAs
      null // xAxisLabel
      //$yAxisLabel2  // yAxisLabel
      );
      // Set the position where the chart should appear in the worksheet
      $chart22->setTopLeftPosition('M6');
      $chart22->setBottomRightPosition('T22');
      // Add the chart to the worksheet
      $objWorksheet->addChart($chart22);

      //FIN GRAFICO/////////////

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, 'TOTAL GENERAL');

      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getFont()->setBold(true);
      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray(
        array(
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'D6F5FF')
            )
        ));

      $total = 0;
      $total2 = 0;
      foreach ($estadisticas as $key => $value){
        $total += $value['cant_reemplazos'];
        $total2 += $this->formatearHora($value['total_horas_reemplazo']);
      }
      
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('B'.$i, $total);
      $objPHPExcel->getActiveSheet()->getStyle('B'.$i)->applyFromArray(
          array(
                    'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              )
      ));

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('C'.$i, $total2);
      $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->applyFromArray(
          array(
                    'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              )
      ));

      /////////////////////////////////////////////////////
      /////////////////CANTIDAD ATRASOS////////////////////
      /////////////////////////////////////////////////////

      if ($i>=22){
        $i += 5;
      }else{
        $i = 27;
      }

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, 'NOMBRE DOCENTE')
                  ->setCellValue('B'.$i, 'CANTIDAD DE ATRASOS')
                  ->setCellValue('C'.$i, 'TOTAL HORAS ATRASOS');

      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getFont()->setBold(true);
      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray(
        array(
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'D6F5FF')
            )
        ));

      $i++;
      $inicio = $i;
      
      foreach ($estadisticas as $key => $value) {
        if($value['cant_atrasos']!=0){
          $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i, $value['nombre_docente'])
                    ->setCellValue('B'.$i, $value['cant_atrasos'])
                    ->setCellValue('C'.$i, $this->formatearHora($value['total_min_atrasos']));
          $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray(
          array(
              'fill' => array(
                  'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                  'color' => array('rgb' => 'FFFFFF')
              )
          ));
          $objPHPExcel->getActiveSheet()->getStyle('B'.$i.':C'.$i)->applyFromArray(
          array(
                    'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              )
            ));
          $i++;
        }

      }

      if($i == $inicio){
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i, 'No hay datos')
                    ->setCellValue('B'.$i, 0)
                    ->setCellValue('C'.$i, 0);
          $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray(
          array(
              'fill' => array(
                  'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                  'color' => array('rgb' => 'FFFFFF')
              )
          ));
          $objPHPExcel->getActiveSheet()->getStyle('B'.$i.':C'.$i)->applyFromArray(
          array(
                    'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              )
            ));
          $i++;
      }

      ////GRAFICO/////////////

      $objWorksheet = $objPHPExcel->getActiveSheet();
      $dataseriesLabels3 = array(
      new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$B$'.($inicio-1), null, 1), // 2010
      //new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$C$1', null, 1), // 2011
      //new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$D$1', null, 1), // 2012
      );
      $xAxisTickValues3 = array(
      new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$A$'.($inicio).':$A$'.($i-1), null, 4), // Q1 to Q4
      );
      $dataSeriesValues3 = array(
      new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$B$'.($inicio).':$B$'.($i-1), null, 4),
      //new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$C$2:$C$5', null, 4),
      //new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$D$2:$D$5', null, 4),
      );
      // Build the dataseries
      $series3 = new \PHPExcel_Chart_DataSeries(
      \PHPExcel_Chart_DataSeries::TYPE_BARCHART, // plotType
      \PHPExcel_Chart_DataSeries::GROUPING_STANDARD, // plotGrouping
      range(0, count($dataSeriesValues3)-1), // plotOrder
      $dataseriesLabels3, // plotLabel
      $xAxisTickValues3, // plotCategory
      $dataSeriesValues3  // plotValues
      );
      // Set additional dataseries parameters
      // Make it a vertical column rather than a horizontal bar graph
      $series3->setPlotDirection(\PHPExcel_Chart_DataSeries::DIRECTION_COL);
      // Set the series in the plot area
      $plotarea3 = new \PHPExcel_Chart_PlotArea(null, array($series3));
      // Set the chart legend
      $legend3 = new \PHPExcel_Chart_Legend(\PHPExcel_Chart_Legend::POSITION_TOP, null, false);
      $title3 = new \PHPExcel_Chart_Title('');
      //$yAxisLabel3 = new \PHPExcel_Chart_Title('Value ($k)');
      // Create the chart
      $chart3 = new \PHPExcel_Chart(
      'chart3', // name
      $title3, // title
      $legend3, // legend
      $plotarea3, // plotArea
      true, // plotVisibleOnly
      0, // displayBlanksAs
      null // xAxisLabel
      //$yAxisLabel3  // yAxisLabel
      );
      // Set the position where the chart should appear in the worksheet
      $chart3->setTopLeftPosition('E'.$inicio);
      $chart3->setBottomRightPosition('L'.($inicio+16));
      // Add the chart to the worksheet
      $objWorksheet->addChart($chart3);

      ////GRAFICO 2

      $objWorksheet = $objPHPExcel->getActiveSheet();
      $dataseriesLabels33 = array(
      new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$C$'.($inicio-1), null, 1), // 2010
      //new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$C$1', null, 1), // 2011
      //new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$D$1', null, 1), // 2012
      );
      $xAxisTickValues33 = array(
      new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$A$'.($inicio).':$A$'.($i-1), null, 4), // Q1 to Q4
      );
      $dataSeriesValues33 = array(
      new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$C$'.($inicio).':$C$'.($i-1), null, 4),
      //new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$C$2:$C$5', null, 4),
      //new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$D$2:$D$5', null, 4),
      );
      // Build the dataseries
      $series33 = new \PHPExcel_Chart_DataSeries(
      \PHPExcel_Chart_DataSeries::TYPE_BARCHART, // plotType
      \PHPExcel_Chart_DataSeries::GROUPING_STANDARD, // plotGrouping
      range(0, count($dataSeriesValues33)-1), // plotOrder
      $dataseriesLabels33, // plotLabel
      $xAxisTickValues33, // plotCategory
      $dataSeriesValues33  // plotValues
      );
      // Set additional dataseries parameters
      // Make it a vertical column rather than a horizontal bar graph
      $series33->setPlotDirection(\PHPExcel_Chart_DataSeries::DIRECTION_COL);
      // Set the series in the plot area
      $plotarea33 = new \PHPExcel_Chart_PlotArea(null, array($series33));
      // Set the chart legend
      $legend33 = new \PHPExcel_Chart_Legend(\PHPExcel_Chart_Legend::POSITION_TOP, null, false);
      $title33 = new \PHPExcel_Chart_Title('');
      //$yAxisLabel3 = new \PHPExcel_Chart_Title('Value ($k)');
      // Create the chart
      $chart33 = new \PHPExcel_Chart(
      'chart33', // name
      $title33, // title
      $legend33, // legend
      $plotarea33, // plotArea
      true, // plotVisibleOnly
      0, // displayBlanksAs
      null // xAxisLabel
      //$yAxisLabel3  // yAxisLabel
      );
      // Set the position where the chart should appear in the worksheet
      $chart33->setTopLeftPosition('M'.$inicio);
      $chart33->setBottomRightPosition('T'.($inicio+16));
      // Add the chart to the worksheet
      $objWorksheet->addChart($chart33);

      ////FIN GRAFICO

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, 'TOTAL GENERAL');

      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getFont()->setBold(true);
      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray(
        array(
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'D6F5FF')
            )
        ));

      $total = 0;
      $total2= 0;
      foreach ($estadisticas as $key => $value){
        $total += $value['cant_atrasos'];
        $total2 += $this->formatearHora($value['total_min_atrasos']);
      }
      
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('B'.$i, $total);
      $objPHPExcel->getActiveSheet()->getStyle('B'.$i)->applyFromArray(
          array(
                    'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              )
      ));

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('C'.$i, $total2);
      $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->applyFromArray(
          array(
                    'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              )
      ));

      //////////////////////////////////////////////////////////
      /////////////////CANTIDAD FALTAS//////////////////////////
      //////////////////////////////////////////////////////////

      if ($i>=44){
        $i += 5;
      }else{
        $i = 49;
      }

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, 'NOMBRE DOCENTE')
                  ->setCellValue('B'.$i, 'CANTIDAD DE FALTAS')
                  ->setCellValue('C'.$i, 'TOTAL HORAS FALTAS');

      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getFont()->setBold(true);
      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray(
        array(
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'D6F5FF')
            )
        ));

      $i++;
      $inicio = $i;
      
      foreach ($estadisticas as $key => $value) {
        if($value['cant_faltas']!=0){
          $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i, $value['nombre_docente'])
                    ->setCellValue('B'.$i, $value['cant_faltas'])
                    ->setCellValue('C'.$i, $this->formatearHora($value['total_horas_faltas']));
          $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray(
          array(
              'fill' => array(
                  'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                  'color' => array('rgb' => 'FFFFFF')
              )
          ));
          $objPHPExcel->getActiveSheet()->getStyle('B'.$i.':C'.$i)->applyFromArray(
          array(
                    'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              )
            ));
          $i++;
        }

      }

      if($i == $inicio){
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i, 'No hay datos')
                    ->setCellValue('B'.$i, 0)
                    ->setCellValue('C'.$i, 0);
          $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray(
          array(
              'fill' => array(
                  'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                  'color' => array('rgb' => 'FFFFFF')
              )
          ));
          $objPHPExcel->getActiveSheet()->getStyle('B'.$i.':C'.$i)->applyFromArray(
          array(
                    'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              )
            ));
          $i++;
      }

      ////GRAFICO////////////

      $objWorksheet = $objPHPExcel->getActiveSheet();
      $dataseriesLabels4 = array(
      new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$B$'.($inicio-1), null, 1), // 2010
      //new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$C$1', null, 1), // 2011
      //new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$D$1', null, 1), // 2012
      );
      $xAxisTickValues4 = array(
      new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$A$'.($inicio).':$A$'.($i-1), null, 4), // Q1 to Q4
      );
      $dataSeriesValues4 = array(
      new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$B$'.($inicio).':$B$'.($i-1), null, 4),
      //new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$C$2:$C$5', null, 4),
      //new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$D$2:$D$5', null, 4),
      );
      // Build the dataseries
      $series4 = new \PHPExcel_Chart_DataSeries(
      \PHPExcel_Chart_DataSeries::TYPE_BARCHART, // plotType
      \PHPExcel_Chart_DataSeries::GROUPING_STANDARD, // plotGrouping
      range(0, count($dataSeriesValues4)-1), // plotOrder
      $dataseriesLabels4, // plotLabel
      $xAxisTickValues4, // plotCategory
      $dataSeriesValues4  // plotValues
      );
      // Set additional dataseries parameters
      // Make it a vertical column rather than a horizontal bar graph
      $series4->setPlotDirection(\PHPExcel_Chart_DataSeries::DIRECTION_COL);
      // Set the series in the plot area
      $plotarea4 = new \PHPExcel_Chart_PlotArea(null, array($series4));
      // Set the chart legend
      $legend4 = new \PHPExcel_Chart_Legend(\PHPExcel_Chart_Legend::POSITION_TOP, null, false);
      $title4 = new \PHPExcel_Chart_Title('');
      //$yAxisLabel4 = new \PHPExcel_Chart_Title('Value ($k)');
      // Create the chart
      $chart4 = new \PHPExcel_Chart(
      'chart4', // name
      $title4, // title
      $legend4, // legend
      $plotarea4, // plotArea
      true, // plotVisibleOnly
      0, // displayBlanksAs
      null // xAxisLabel
      //$yAxisLabel4  // yAxisLabel
      );
      // Set the position where the chart should appear in the worksheet
      $chart4->setTopLeftPosition('E'.$inicio);
      $chart4->setBottomRightPosition('L'.($inicio+16));
      // Add the chart to the worksheet
      $objWorksheet->addChart($chart4);

      ////GRAFICO2

      $objWorksheet = $objPHPExcel->getActiveSheet();
      $dataseriesLabels44 = array(
      new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$C$'.($inicio-1), null, 1), // 2010
      //new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$C$1', null, 1), // 2011
      //new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$D$1', null, 1), // 2012
      );
      $xAxisTickValues44 = array(
      new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$A$'.($inicio).':$A$'.($i-1), null, 4), // Q1 to Q4
      );
      $dataSeriesValues44 = array(
      new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$C$'.($inicio).':$C$'.($i-1), null, 4),
      //new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$C$2:$C$5', null, 4),
      //new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$D$2:$D$5', null, 4),
      );
      // Build the dataseries
      $series44 = new \PHPExcel_Chart_DataSeries(
      \PHPExcel_Chart_DataSeries::TYPE_BARCHART, // plotType
      \PHPExcel_Chart_DataSeries::GROUPING_STANDARD, // plotGrouping
      range(0, count($dataSeriesValues44)-1), // plotOrder
      $dataseriesLabels44, // plotLabel
      $xAxisTickValues44, // plotCategory
      $dataSeriesValues44  // plotValues
      );
      // Set additional dataseries parameters
      // Make it a vertical column rather than a horizontal bar graph
      $series44->setPlotDirection(\PHPExcel_Chart_DataSeries::DIRECTION_COL);
      // Set the series in the plot area
      $plotarea44 = new \PHPExcel_Chart_PlotArea(null, array($series44));
      // Set the chart legend
      $legend44 = new \PHPExcel_Chart_Legend(\PHPExcel_Chart_Legend::POSITION_TOP, null, false);
      $title44 = new \PHPExcel_Chart_Title('');
      //$yAxisLabel4 = new \PHPExcel_Chart_Title('Value ($k)');
      // Create the chart
      $chart44 = new \PHPExcel_Chart(
      'chart44', // name
      $title44, // title
      $legend44, // legend
      $plotarea44, // plotArea
      true, // plotVisibleOnly
      0, // displayBlanksAs
      null // xAxisLabel
      //$yAxisLabel4  // yAxisLabel
      );
      // Set the position where the chart should appear in the worksheet
      $chart44->setTopLeftPosition('M'.$inicio);
      $chart44->setBottomRightPosition('T'.($inicio+16));
      // Add the chart to the worksheet
      $objWorksheet->addChart($chart44);

      ////FIN GRAFICO

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, 'TOTAL GENERAL');

      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getFont()->setBold(true);
      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray(
        array(
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'D6F5FF')
            )
        ));

      $total = 0;
      $total2 = 0;
      foreach ($estadisticas as $key => $value){
        $total += $value['cant_faltas'];
        $total2 += $this->formatearHora($value['total_horas_faltas']);
      }
      
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('B'.$i, $total);
      $objPHPExcel->getActiveSheet()->getStyle('B'.$i)->applyFromArray(
          array(
                    'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              )
      ));

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('C'.$i, $total2);
      $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->applyFromArray(
          array(
                    'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              )
      ));

      /////////////////////////////////////////////////////////////////
      /////////////////CANTIDAD SALIDAS ANTES DE HORA//////////////////
      /////////////////////////////////////////////////////////////////

      if ($i>=66){
        $i += 5;
      }else{
        $i = 71;
      }

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, 'NOMBRE DOCENTE')
                  ->setCellValue('B'.$i, 'CANTIDAD SALIDAS AH')
                  ->setCellValue('C'.$i, 'TOTAL SALIDAS AH');

      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getFont()->setBold(true);
      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray(
        array(
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'D6F5FF')
            )
        ));

      $i++;
      $inicio = $i;
      
      foreach ($estadisticas as $key => $value) {
        if($value['cant_salidas_ah']!=0){
          $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i, $value['nombre_docente'])
                    ->setCellValue('B'.$i, $value['cant_salidas_ah'])
                    ->setCellValue('C'.$i, $this->formatearHora($value['total_min_salidas_ah']));
          $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray(
          array(
              'fill' => array(
                  'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                  'color' => array('rgb' => 'FFFFFF')
              )
          ));
          $objPHPExcel->getActiveSheet()->getStyle('B'.$i.':C'.$i)->applyFromArray(
          array(
                    'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              )
            ));
          $i++;
        }

      }

      if($i == $inicio){
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i, 'No hay datos')
                    ->setCellValue('B'.$i, 0)
                    ->setCellValue('C'.$i, 0);
          $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray(
          array(
              'fill' => array(
                  'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                  'color' => array('rgb' => 'FFFFFF')
              )
          ));
          $objPHPExcel->getActiveSheet()->getStyle('B'.$i.':C'.$i)->applyFromArray(
          array(
                    'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              )
            ));
          $i++;
      }

      ////GRAFICO//////////////

      $objWorksheet = $objPHPExcel->getActiveSheet();
      $dataseriesLabels5 = array(
      new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$B$'.($inicio-1), null, 1), // 2010
      //new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$C$1', null, 1), // 2011
      //new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$D$1', null, 1), // 2012
      );
      $xAxisTickValues5 = array(
      new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$A$'.($inicio).':$A$'.($i-1), null, 4), // Q1 to Q4
      );
      $dataSeriesValues5 = array(
      new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$B$'.($inicio).':$B$'.($i-1), null, 4),
      //new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$C$2:$C$5', null, 4),
      //new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$D$2:$D$5', null, 4),
      );
      // Build the dataseries
      $series5 = new \PHPExcel_Chart_DataSeries(
      \PHPExcel_Chart_DataSeries::TYPE_BARCHART, // plotType
      \PHPExcel_Chart_DataSeries::GROUPING_STANDARD, // plotGrouping
      range(0, count($dataSeriesValues5)-1), // plotOrder
      $dataseriesLabels5, // plotLabel
      $xAxisTickValues5, // plotCategory
      $dataSeriesValues5  // plotValues
      );
      // Set additional dataseries parameters
      // Make it a vertical column rather than a horizontal bar graph
      $series5->setPlotDirection(\PHPExcel_Chart_DataSeries::DIRECTION_COL);
      // Set the series in the plot area
      $plotarea5 = new \PHPExcel_Chart_PlotArea(null, array($series5));
      // Set the chart legend
      $legend5 = new \PHPExcel_Chart_Legend(\PHPExcel_Chart_Legend::POSITION_TOP, null, false);
      $title5 = new \PHPExcel_Chart_Title('');
      //$yAxisLabel4 = new \PHPExcel_Chart_Title('Value ($k)');
      // Create the chart
      $chart5 = new \PHPExcel_Chart(
      'chart5', // name
      $title5, // title
      $legend5, // legend
      $plotarea5, // plotArea
      true, // plotVisibleOnly
      0, // displayBlanksAs
      null // xAxisLabel
      //$yAxisLabel5  // yAxisLabel
      );
      // Set the position where the chart should appear in the worksheet
      $chart5->setTopLeftPosition('E'.$inicio);
      $chart5->setBottomRightPosition('L'.($inicio+16));
      // Add the chart to the worksheet
      $objWorksheet->addChart($chart5);

      //////GRAFICO2////////

      $objWorksheet = $objPHPExcel->getActiveSheet();
      $dataseriesLabels55 = array(
      new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$C$'.($inicio-1), null, 1), // 2010
      //new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$C$1', null, 1), // 2011
      //new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$D$1', null, 1), // 2012
      );
      $xAxisTickValues55 = array(
      new \PHPExcel_Chart_DataSeriesValues('String', 'Reporte!$A$'.($inicio).':$A$'.($i-1), null, 4), // Q1 to Q4
      );
      $dataSeriesValues55 = array(
      new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$C$'.($inicio).':$C$'.($i-1), null, 4),
      //new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$C$2:$C$5', null, 4),
      //new \PHPExcel_Chart_DataSeriesValues('Number', 'Reporte!$D$2:$D$5', null, 4),
      );
      // Build the dataseries
      $series55 = new \PHPExcel_Chart_DataSeries(
      \PHPExcel_Chart_DataSeries::TYPE_BARCHART, // plotType
      \PHPExcel_Chart_DataSeries::GROUPING_STANDARD, // plotGrouping
      range(0, count($dataSeriesValues55)-1), // plotOrder
      $dataseriesLabels55, // plotLabel
      $xAxisTickValues55, // plotCategory
      $dataSeriesValues55  // plotValues
      );
      // Set additional dataseries parameters
      // Make it a vertical column rather than a horizontal bar graph
      $series55->setPlotDirection(\PHPExcel_Chart_DataSeries::DIRECTION_COL);
      // Set the series in the plot area
      $plotarea55 = new \PHPExcel_Chart_PlotArea(null, array($series55));
      // Set the chart legend
      $legend55 = new \PHPExcel_Chart_Legend(\PHPExcel_Chart_Legend::POSITION_TOP, null, false);
      $title55 = new \PHPExcel_Chart_Title('');
      //$yAxisLabel4 = new \PHPExcel_Chart_Title('Value ($k)');
      // Create the chart
      $chart55 = new \PHPExcel_Chart(
      'chart55', // name
      $title55, // title
      $legend55, // legend
      $plotarea55, // plotArea
      true, // plotVisibleOnly
      0, // displayBlanksAs
      null // xAxisLabel
      //$yAxisLabel5  // yAxisLabel
      );
      // Set the position where the chart should appear in the worksheet
      $chart55->setTopLeftPosition('M'.$inicio);
      $chart55->setBottomRightPosition('T'.($inicio+16));
      // Add the chart to the worksheet
      $objWorksheet->addChart($chart55);

      ////////FIN GRAFICO////

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, 'TOTAL GENERAL');

      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getFont()->setBold(true);
      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray(
        array(
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'D6F5FF')
            )
        ));

      $total = 0;
      $total2 = 0;
      foreach ($estadisticas as $key => $value){
        $total += $value['cant_salidas_ah'];
        $total2 += $this->formatearHora($value['total_min_salidas_ah']);
      }
      
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('B'.$i, $total);
      $objPHPExcel->getActiveSheet()->getStyle('B'.$i)->applyFromArray(
          array(
                    'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              )
      ));

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('C'.$i, $total2);
      $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->applyFromArray(
          array(
                    'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              )
      ));

      // Rename worksheet
      $objPHPExcel->getActiveSheet()->setTitle('Reporte');

      foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {

          $objPHPExcel->setActiveSheetIndex($objPHPExcel->getIndex($worksheet));

          $sheet = $objPHPExcel->getActiveSheet();
          $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
          $cellIterator->setIterateOnlyExistingCells(true);
          /** @var PHPExcel_Cell $cell */
          foreach ($cellIterator as $cell) {
              $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
          }
      }


      // Redirect output to a client’s web browser (Excel2007)
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="Reporte.xlsx"');
      header('Cache-Control: max-age=0');
      // If you're serving to IE 9, then the following may be needed
      header('Cache-Control: max-age=1');

      // If you're serving to IE over SSL, then the following may be needed
      header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
      header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
      header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
      header ('Pragma: public'); // HTTP/1.0

      $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
      $objWriter->setIncludeCharts(TRUE);
      $objWriter->save('php://output');
    }

    public function reporteMedioTiempo($id_periodo_lectivo){
    
      $sql="  SELECT DISTINCT
          dat_horario.nombre_docente,
          dat_horario.id_docente,
          (select count(horas_reemplazo) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as cant_reemplazos,
          (select count(minutos_atrasos) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as cant_atrasos,
          (select count(horas_faltas) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as cant_faltas,
          (select count(minutos_salidas_ah) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as cant_salidas_ah,
          (select sum(horas_reemplazo) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_horas_reemplazo,
          (select sum(minutos_atrasos) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_min_atrasos,
          (select sum(horas_faltas) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_horas_faltas,
          (select sum(minutos_salidas_ah) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.id_tri = $id_periodo_lectivo) as total_min_salidas_ah
       FROM 
          m_docente.dat_estadisticas, 
          m_docente.dat_horario
        WHERE dat_estadisticas.id_horario = dat_horario.id and dat_horario.id_trimestre = $id_periodo_lectivo and dat_horario.tipo_docente = 'Medio tiempo'
        ;
        ";
      $primaryConnection = \Yii::$app->db;
      $command = $primaryConnection->createCommand($sql);
      $estadisticas = $command->queryAll();
      return $estadisticas;
    }

    public function reporteMedioTiempoFecha($fecha_rango_1,$fecha_rango_2){
    
      $sql="  SELECT DISTINCT 
         (select count(horas_reemplazo) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as cant_reemplazos,
         (select count(minutos_atrasos) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as cant_atrasos,
         (select count(horas_faltas) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as cant_faltas,
         (select count(minutos_salidas_ah) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as cant_salidas_ah,
         (select sum(horas_reemplazo) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_reemplazo,
         (select sum(minutos_atrasos) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_min_atrasos,
         (select sum(horas_faltas) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_horas_faltas,
         (select sum(minutos_salidas_ah) from m_docente.dat_estadisticas where dat_estadisticas.id_docente = dat_horario.id_docente and dat_estadisticas.fecha between '$fecha_rango_1' and '$fecha_rango_2') as total_min_salidas_ah,
         dat_estadisticas.id_docente,
         dat_horario.nombre_docente
      FROM 
         m_docente.dat_estadisticas, 
         m_docente.dat_horario
      WHERE dat_estadisticas.id_horario = dat_horario.id and dat_horario.tipo_docente = 'Medio tiempo'
      ;      
      ";
      $primaryConnection = \Yii::$app->db;
      $command = $primaryConnection->createCommand($sql);
      $estadisticas = $command->queryAll();
      return $estadisticas;
    }
    
}