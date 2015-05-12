<?php
namespace app\modules\mod_academico\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;

use app\modules\mod_docente\models\datPeriodoConfig;

class InformesController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionReporte(){
        if(isset($_GET['id'])){
            switch ($_GET['id']) {
                case '0':
                    $this->reporte1();
                    break;
                case '1':
                    $this->reporte2();
                    break;
                case '2':
                    $this->reporte3();
                    break;
                default:                 
                    break;
            }
        }
    }

    public function reporte1(){
        require_once(Yii::getAlias('@vendor'). '/jpgraph/jpgraph.php'); 
        require_once(Yii::getAlias('@vendor'). '/jpgraph/jpgraph_pie.php');  

        $periodos    = datPeriodoConfig::find()->asArray()->all();
        $id_periodos = array();
        foreach ($periodos as $key => $value) {
            $id_periodos[] = $value['id_periodo'];
        }

        $sql_reporte1  = "
                     SELECT DISTINCT (sa_alumno.id) as id_alumno,
                      sa_periodo_lectivo.id AS id_periodo_lectivo,
                      sa_periodo_lectivo.nombre AS nombre_periodo_lectivo,
                      sa_modalidad.id AS id_modalidad,
                      sa_modalidad.descripcion AS nombre_modalidad
                     FROM sa_registro
                      INNER JOIN sa_alumno_flujo ON (sa_registro.id_alumno_flujo = sa_alumno_flujo.id)
                      INNER JOIN sa_alumno ON (sa_alumno_flujo.id_alumno = sa_alumno.id)
                      INNER JOIN sa_flujo_carrera ON (sa_alumno_flujo.id_flujo = sa_flujo_carrera.id)
                      INNER JOIN sa_carrera ON (sa_flujo_carrera.id_carrera = sa_carrera.id)
                      INNER JOIN sa_registro_materias ON (sa_registro.id = sa_registro_materias.id_registro)
                      INNER JOIN sa_materia_periodo_lectivo ON (sa_registro_materias.id_materia_periodo_lectivo = sa_materia_periodo_lectivo.id)
                      INNER JOIN sa_periodo_lectivo ON (sa_materia_periodo_lectivo.id_periodo_lectivo = sa_periodo_lectivo.id)
                      INNER JOIN sa_modalidad ON (sa_materia_periodo_lectivo.id_modalidad = sa_modalidad.id)
                     WHERE (sa_registro.sybase = 'S')
                     AND (sa_materia_periodo_lectivo.id_periodo_lectivo IN (". implode(',',$id_periodos)."))
        ";


        $primaryConnection = \Yii::$app->db_siga;
        $command    = $primaryConnection->createCommand($sql_reporte1);
        $resultados = $command->queryAll();
           
        $data          = array();
        $data_final    = array();
        $data_periodos = array();

        //Intercambiar esta talla aca

        foreach ($resultados as $key => $value) {
            if(isset($data[$value['id_periodo_lectivo']] [ $value['id_modalidad']]))
            {
                 $data[$value['id_periodo_lectivo']] [ $value['id_modalidad']] =$data[$value['id_periodo_lectivo']] [ $value['id_modalidad']] +1;
            }
            else
            {
                 $data[$value['id_periodo_lectivo']] [ $value['id_modalidad']] =0;
                 $data_final[] = array(
                                          'id_modalidad'           => $value['id_modalidad'],
                                          'nombre_modalidad'       => $value['nombre_modalidad'],
                                          'id_periodo_lectivo'     => $value['id_periodo_lectivo'],
                                          'nombre_periodo_lectivo' => $value['nombre_periodo_lectivo']                                      
                                      ) ;

                 /*Agregar los periodos lectivos al arreglo*/
                 $bandera = false;
                 foreach ($data_periodos as $key_periodos => $value_periodos) {
                    if($value_periodos['id_periodo_lectivo'] ==$value['id_periodo_lectivo']){
                        $bandera = true;        
                    }
                 }

                 if(!$bandera){
                    $data_periodos[] = array(
                        'id_periodo_lectivo' => $value['id_periodo_lectivo'],
                        'nombre_periodo_lectivo' => $value['nombre_periodo_lectivo']
                     );
                 }
            }
        }
       

        foreach ($data_periodos as $key => $value) { 
            #BUSCAR LA CANTIDAD DE ESTUDIANTES
            $datos = $data[$value['id_periodo_lectivo']];

            $elementos = null;
            $leyenda   = null;

            foreach ($datos as $key_e => $value_e) {
                $elementos [] = $value_e;
                $leyenda   [] = $this->buscarNombreModalidad($data_final,$key_e);//nombre modalidad
            }
            
            #GENERAR GRAFICAS
            $graph = new \PieGraph(900,400);
            $graph->SetShadow();         
            $graph->title->Set($value['nombre_periodo_lectivo']);        
            $p1 = new \PiePlot($elementos);
            $p1->SetLegends($leyenda);
            $graph->Add($p1);
            //$graph->Stroke();}
            $uta_base = "public/";
            $ruta = "grafica_periodo_lectivo".$value['id_periodo_lectivo'].".png";
            $graph ->Stroke($uta_base.$ruta);
        
            /* 
            * Aca puedo pintar los resultados
            */
       
            $html_table_output ='

                <style type="text/css">
                        #the-table2 { border:1px solid #bbb;border-collapse:collapse;margin: 10px ;width: 97%}
                        #the-table2 td,#the-table2 th { border:1px solid #ccc;border-collapse:collapse;padding:2px; }
                </style>

                <table cellspacing="1"  id="the-table2">
                            <thead>
                                <tr style="background:#eeeeee;">
                                    <td colspan="2"><b>'.$value['nombre_periodo_lectivo'].'</b></td>
                                </tr>
                                <tr style="background:#eeeeee;">
                                    <th>Modalidad</th>
                                    <th>Cantidad de estudiantes</th>
                                </tr>
                            </thead>
                            <tbody>
                               ';   

                              $i=0;
                             foreach ($elementos as $key => $value) {
                                          $html_table_output = $html_table_output . '<tr>';
                                          $html_table_output = $html_table_output . '<td>'. $leyenda[$i].'</td>';   
                                          $html_table_output = $html_table_output . '<td>'. $elementos[$i].'</td>';
                                          $html_table_output = $html_table_output . '</tr>';
                                           $i++;
                              }             

                              $html_table_output = $html_table_output .  '
                            </tbody>
                    </table>
           ';
            echo $html_table_output;
            echo '<img src="'.Url::base().'/public/'.$ruta.'" border="0" alt="Este es el ejemplo de un texto alternativo" >';

            if(isset($_GET['print'])){
              if($_GET['print']==true){
                echo '<script type="text/javascript">  window.print() </script>';
             }
            }
           }
    }



    public function reporte2(){
        $periodos    = datPeriodoConfig::find()->asArray()->all();
        $id_periodos = array();
        foreach ($periodos as $key => $value) {
            $id_periodos[] = $value['id_periodo'];
        }

        $sql_reporte1 = "
            SELECT 
                  count(DISTINCT(sa_alumno.id)) AS cantidad,
                  sa_periodo_lectivo.id AS id_periodo_lectivo,
                  sa_periodo_lectivo.nombre AS nombre_periodo_lectivo,
                  sa_modalidad.id AS id_modalidad,
                  sa_modalidad.descripcion AS nombre_modalidad,
                  sa_alumno.categoria_definicion
                FROM
                  sa_registro
                  INNER JOIN sa_alumno_flujo ON (sa_registro.id_alumno_flujo = sa_alumno_flujo.id)
                  INNER JOIN sa_alumno ON (sa_alumno_flujo.id_alumno = sa_alumno.id)
                  INNER JOIN sa_flujo_carrera ON (sa_alumno_flujo.id_flujo = sa_flujo_carrera.id)
                  INNER JOIN sa_carrera ON (sa_flujo_carrera.id_carrera = sa_carrera.id)
                  INNER JOIN sa_registro_materias ON (sa_registro.id = sa_registro_materias.id_registro)
                  INNER JOIN sa_materia_periodo_lectivo ON (sa_registro_materias.id_materia_periodo_lectivo = sa_materia_periodo_lectivo.id)
                  INNER JOIN sa_periodo_lectivo ON (sa_materia_periodo_lectivo.id_periodo_lectivo = sa_periodo_lectivo.id)
                  INNER JOIN sa_modalidad ON (sa_materia_periodo_lectivo.id_modalidad = sa_modalidad.id)
                WHERE
                  (sa_registro.sybase = 'S') AND 
                  (sa_materia_periodo_lectivo.id_periodo_lectivo IN (377,376))
                GROUP BY
                  sa_periodo_lectivo.id,
                  sa_periodo_lectivo.nombre,
                  sa_modalidad.id,
                  sa_modalidad.descripcion,
                  sa_alumno.categoria_definicion
        ";

        $primaryConnection = \Yii::$app->db_siga;
        $command    = $primaryConnection->createCommand($sql_reporte1);
        $resultados = $command->queryAll();
        



        foreach ($id_periodos as $key => $value) {


          $nombre_periodo_lectivo = "";
          foreach ($resultados as $key_b => $value_b) {
              if($value  == $value_b['id_periodo_lectivo'] ){
                 $nombre_periodo_lectivo = $value_b['nombre_periodo_lectivo'];
                 break;
              }
          }

          $html_table_output ='
                <style type="text/css">
                        #the-table2 { border:1px solid #bbb;border-collapse:collapse;margin: 10px ;width: 97%}
                        #the-table2 td,#the-table2 th { border:1px solid #ccc;border-collapse:collapse;padding:2px; }
                </style>

                <table cellspacing="1"  id="the-table2">
                            <thead>
                                <tr style="background:#eeeeee;">
                                    <td colspan="3"><b>'.$nombre_periodo_lectivo.'</b></td>
                                </tr>
                                <tr style="background:#eeeeee;">
                                    <th>Número de Alumnos</th>
                                    <th>Modalidad</th>
                                    <th>Categoría Asignada</th>
                                </tr>
                            </thead>
                            <tbody>';   

                              $i=0;
                             foreach ($resultados as $key_e => $value_e) {
                                         if($resultados[$i]['id_periodo_lectivo'] == $value) {
                                           $html_table_output = $html_table_output . '<tr>';                                          
                                           $html_table_output = $html_table_output . '<td>'. $resultados[$i]['cantidad'].'</td>';   
                                           $html_table_output = $html_table_output . '<td>'. $resultados[$i]['nombre_modalidad'].'</td>';   
                                           $html_table_output = $html_table_output . '<td>'. $resultados[$i]['categoria_definicion'].'</td>';
                                           $html_table_output = $html_table_output . '</tr>';
                                          }
                                 $i++;
                              }            

                              $html_table_output = $html_table_output .  '
                            </tbody>
                    </table>
           ';
            echo $html_table_output;
        }





          
            if(isset($_GET['print'])){
              if($_GET['print']==true){
                echo '<script type="text/javascript">  window.print() </script>';
             }
            }
    }

    public function reporte3(){
        echo 'En desarrollo..';
    }

    public function buscarNombreModalidad($arreglo, $id_modalidad){     
      foreach ($arreglo as $key => $value) {
        if($value['id_modalidad']==$id_modalidad){
          return $value['nombre_modalidad'];
        }
      }
    }
}