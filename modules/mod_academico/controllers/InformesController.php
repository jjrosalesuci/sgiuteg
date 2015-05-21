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
	    $nombre_periodo_lectivo = $value["nombre_periodo_lectivo"];
			$suma = 0;
			#BUSCAR LA CANTIDAD DE ESTUDIANTES
			$datos = $data[$value['id_periodo_lectivo']];
			$elementos = null;
			$leyenda   = null;
			foreach ($datos as $key_e => $value_e) {
				$elementos [] = $value_e;
				$leyenda   [] = $this->buscarNombreModalidad($data_final,$key_e);//nombre modalidad
			}	

			$i=0;
			foreach ($elementos as $key => $value) {
				$suma = $suma +  $value;
				$i++;
			}	

			$findme = 'PRESE';
			$pos = strpos($nombre_periodo_lectivo, $findme);

			if($pos===false){
				$datoss['Semiresencial'] = $suma;
			}else{
				$datoss['Presencial'] = $suma;
			}
		 }
	
		 //pintar la tabla aca

		  #GENERAR GRAFICAS
			$graph = new \PieGraph(900,350);
			$graph->SetShadow();
			$graph->title->Set('Cantidad de estudiantes por modalidad');

			$p1 = new \PiePlot([$datoss['Presencial'],$datoss['Semiresencial']]);
			$p1->SetLegends(['Presencial : '.$datoss['Presencial'], 'Semiresencial'.' : '.$datoss['Semiresencial']]   );
			$graph->Add($p1);
			$size=0.25;
			$p1->SetSize($size);

			$uta_base = "public/";
			$ruta = "grafica_periodo_lectivo3.png";
			$graph ->Stroke($uta_base.$ruta);

			echo '<img src="'.Url::base().'/public/'.$ruta.'" border="0" alt="Este es el ejemplo de un texto alternativo" >';
 		

	  	if(isset($_GET['print'])){
				if($_GET['print']==true){
					echo '<script type="text/javascript">  window.print() </script>';
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

			$i=0;			
			$array_f = array();
			foreach ($resultados as $key_e => $value_e) {
				if($resultados[$i]['id_periodo_lectivo'] == $value) {
					if(isset($array_f[$resultados[$i]['categoria_definicion']])){
						$array_f[$resultados[$i]['categoria_definicion']]=$array_f[$resultados[$i]['categoria_definicion']]+ $resultados[$i]['cantidad'];
					}else{
						$array_f[$resultados[$i]['categoria_definicion']]= $resultados[$i]['cantidad'];
					}
				 }
				$i++;
			}


				$datay1 = array();
				$keys   = array();
				foreach ($array_f as $key => $index){
					    $datay1[]=$index;
					    $keys[]=$key;
			  }		


		   #GENERAR GRAFICA
				require_once(Yii::getAlias('@vendor'). '/jpgraph/jpgraph.php');
	    	require_once(Yii::getAlias('@vendor'). '/jpgraph/jpgraph_bar.php');

				// Setup the graph
				$graph = new \Graph(500,250);
				$graph->SetScale("textlin");

				$theme_class=new \UniversalTheme;

				$graph->SetTheme($theme_class);
				$graph->img->SetAntiAliasing(false);
				$graph->title->Set('Cantidad de alumnos por categorias ('.$nombre_periodo_lectivo.')');
				$graph->SetBox(false);

				$graph->img->SetAntiAliasing();

				$graph->yaxis->HideZeroLabel();
				$graph->yaxis->HideLine(false);
				$graph->yaxis->HideTicks(false,false);

				$graph->xgrid->Show();
				$graph->xgrid->SetLineStyle("solid");
				$graph->xaxis->SetTickLabels($keys);
				$graph->xgrid->SetColor('#E3E3E3');

				// Create the first line
				$p1 = new \BarPlot($datay1);
				$graph->Add($p1);
				$p1->SetColor("#6495ED");
				$p1->SetLegend('Categorias');

				$p1->value->SetFormat('%d');
        $p1->value->Show();
			  $p1->value->SetColor('#55bbdd');


				$graph->legend->SetFrameWeight(1);


			  $uta_base = "public/";
		  	$ruta = $value."grafica_categorias.png";
		  	$graph ->Stroke($uta_base.$ruta);

		   	echo '<img src="'.Url::base().'/public/'.$ruta.'" border="0" alt="Este es el ejemplo de un texto alternativo" >';
		}

		if(isset($_GET['print'])){
			if($_GET['print']==true){
				echo '<script type="text/javascript">  window.print() </script>';
			}
		}
	}

	public function reporte3(){
		$periodos    = datPeriodoConfig::find()->asArray()->all();
		$id_periodos = array();
		foreach ($periodos as $key => $value) {
			$id_periodos[] = $value['id_periodo'];
		}

		$sql_reporte1 = "
		SELECT
		sa_alumno.id,
		sa_alumno.nombre,
		sa_alumno.apellido,
		sa_alumno.cedula,
		sa_alumno.email_uteg,
		sa_alumno.telefono,
		sa_saldo_alumno.saldo,
		sa_materia_periodo_lectivo.id_periodo_lectivo
		FROM
		sa_alumno
		INNER JOIN sa_alumno_materia ON (sa_alumno.id = sa_alumno_materia.id_alumno)
		INNER JOIN sa_materia_periodo_lectivo ON (sa_alumno_materia.id_materia_periodo_lectivo = sa_materia_periodo_lectivo.id)
		INNER JOIN sa_saldo_alumno ON (sa_saldo_alumno.cedula = sa_alumno.cedula)
		WHERE
		sa_materia_periodo_lectivo.id_periodo_lectivo IN (". implode(',',$id_periodos).") and sa_alumno.estatus = 'A' and sa_saldo_alumno.saldo != '0.00'
		GROUP BY
		sa_alumno.id,
		sa_alumno.nombre
		";

		$primaryConnection = \Yii::$app->db_siga;
		$command    = $primaryConnection->createCommand($sql_reporte1);
		$resultados = $command->queryAll();

		$arreglo = array();
		$cant_pre = 0;
		$cant_semi = 0;
		$suma_pre = 0;
		$suma_semi = 0;
		foreach ($resultados as $key => $value) {
			if($this->findModelPeriodoV($value['id_periodo_lectivo'])=="Presencial"){
				$value['modalidad'] = "Presencial";
				$suma_pre+=$value['saldo'];
				$cant_pre++;
			}else{
				$value['modalidad'] = "Semipresencial";
				$cant_semi++;
				$suma_semi+=$value['saldo'];
			}
			$arreglo[] = $value;
		}
		$label = array();
		$data = array();
		array_push($label, 'Presencial('.$cant_pre.' est)','Semipresencial('.$cant_semi.' est)');
		array_push($data, $suma_pre,$suma_semi);

		 #GENERAR GRAFICA
				require_once(Yii::getAlias('@vendor'). '/jpgraph/jpgraph.php');
	    	require_once(Yii::getAlias('@vendor'). '/jpgraph/jpgraph_bar.php');

				// Setup the graph
				$graph = new \Graph(500,250);
				$graph->SetScale("textlin");

				$theme_class=new \UniversalTheme;

				$graph->SetTheme($theme_class);
				$graph->img->SetAntiAliasing(false);
				//$graph->title->Set('Cantidad de alumnos por categorias ('.$nombre_periodo_lectivo.')');
				$graph->SetBox(false);

				$graph->img->SetAntiAliasing();

				//$graph->yaxis->HideZeroLabel();
				//$graph->yaxis->SetLegend('Cantidad de USD');
				$graph->yaxis->HideLine(false);
				$graph->yaxis->HideTicks(false,false);

				$graph->xgrid->Show();
				$graph->xgrid->SetLineStyle("solid");
				$graph->xaxis->SetTickLabels($label);
				$graph->xgrid->SetColor('#E3E3E3');

				// Create the first line
				$p1 = new \BarPlot($data);
				$graph->Add($p1);
				$p1->SetColor("#6495ED");
				$p1->SetLegend('Cantidad de USD');


				$p1->value->SetFormat('%d');
       			$p1->value->Show();
			  	$p1->value->SetColor('#55bbdd');


				$graph->legend->SetFrameWeight(1);
				$graph->legend->SetPos(0.5,0.98,'center','bottom');


			  $uta_base = "public/";
		  	$ruta = "adeudados.png";
		  	$graph ->Stroke($uta_base.$ruta);

		   	echo '<img src="'.Url::base().'/public/'.$ruta.'" border="0" alt="Este es el ejemplo de un texto alternativo" >';

          $html_table_output ='
                <style type="text/css">
                        #the-table2 { border:1px solid #bbb;border-collapse:collapse;margin: 10px ;width: 97%}
                        #the-table2 td,#the-table2 th { border:1px solid #ccc;border-collapse:collapse;padding:2px; }
                </style>

                <table cellspacing="1"  id="the-table2">
                            <thead>
                                <tr style="background:#CCF2FF;">
                                    <td colspan="6"><b>Valores Adeudados Detalle</b></td>
                                </tr>
                                <tr style="background:#CCF2FF;">
                                    <th>Modalidad</th>
                                    <th>Alumno</th>
                                    <th>Cedula</th>
                                    <th>Email</th>
                                    <th>Telefono</th>
                                    <th>Valor Pendiente</th>
                                </tr>
                            </thead>
                            <tbody>';   

                              $i=0;
                             foreach ($arreglo as $key_e => $value_e) {
                                           $html_table_output = $html_table_output . '<tr>';
                                           $html_table_output = $html_table_output . '<td>'. $arreglo[$i]['modalidad'].'</td>';                                          
                                           $html_table_output = $html_table_output . '<td>'. $arreglo[$i]['nombre'].' '.$resultados[$i]['apellido'].'</td>';   
                                           $html_table_output = $html_table_output . '<td>'. $arreglo[$i]['cedula'].'</td>';   
                                           $html_table_output = $html_table_output . '<td>'. $arreglo[$i]['email_uteg'].'</td>';
                                           $html_table_output = $html_table_output . '<td>'. $arreglo[$i]['telefono'].'</td>';
                                           $html_table_output = $html_table_output . '<td>'. $arreglo[$i]['saldo'].'</td>';
                                           $html_table_output = $html_table_output . '</tr>';
                                 $i++;
                              }          

                              $html_table_output = $html_table_output .  '
                            </tbody>
                    </table>
           ';
            echo $html_table_output;

          
            if(isset($_GET['print'])){
              if($_GET['print']==true){
                echo '<script type="text/javascript">  window.print() </script>';
             }
            }

	}

	public function buscarNombreModalidad($arreglo, $id_modalidad){
		foreach ($arreglo as $key => $value) {
			if($value['id_modalidad']==$id_modalidad){
				return $value['nombre_modalidad'];
			}
		}
	}
	public function findModelPeriodoV($id_periodo)
    {
        if (($model = datPeriodoConfig::find()->where(['id_periodo' => $id_periodo])->one()) !== null) {
                return $model->tipo;
            } else {
                return false;
            }
    }
}