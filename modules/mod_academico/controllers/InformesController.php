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
				case '3':
					$this->CanteraIngresos();
					break;
				case '4':
					$this->ExportarCanteraDeudas();
					break;
				default:
					break;
			}
		}
	}

	public function reporte1(){
		require_once(Yii::getAlias('@vendor'). '/jpgraph/jpgraph.php');
		require_once(Yii::getAlias('@vendor'). '/jpgraph/jpgraph_pie.php');
		require_once(Yii::getAlias('@vendor'). '/jpgraph/jpgraph_bar.php');

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
		$label = array();
		$data = array();
		array_push($label, 'Presencial('.$datoss['Presencial'].' est)',' Semipresencial('.$datoss['Semiresencial'].' est)');
		array_push($data,$datoss['Presencial'] ,$datoss['Semiresencial']);


		#GENERAR GRAFICA
		$graph = new \Graph(500,250);
		$graph->SetScale("textlin");

		$theme_class=new \UniversalTheme;

		$graph->SetTheme($theme_class);
		$graph->img->SetAntiAliasing(false);
		$graph->title->Set('Cantidad de alumnos por modalidad');
		$graph->SetBox(false);

		$graph->img->SetAntiAliasing();

		$graph->yaxis->HideLine(false);
		$graph->yaxis->HideTicks(false,false);

		$graph->xgrid->Show();
		$graph->xgrid->SetLineStyle("solid");
		$graph->xaxis->SetTickLabels($label);
		$graph->xgrid->SetColor('#E3E3E3');

		if(count($data)>0){
			$p1 = new \BarPlot($data);
			$graph->Add($p1);
			$p1->SetColor("#6495ED");

			$p1->value->SetFormat('%d');
			$p1->value->Show();
			$p1->value->SetColor('#55bbdd');

			$graph->legend->SetFrameWeight(1);
			$graph->legend->SetPos(0.5,0.98,'center','bottom');

			$uta_base = "public/";
			$ruta = "grafica_periodo_lectivo3.png";
			$graph ->Stroke($uta_base.$ruta);

			echo '<img src="'.Url::base().'/public/'.$ruta.'" border="0" alt="Este es el ejemplo de un texto alternativo" >';
		}
			
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

		$cadena_periodos = implode(',', $id_periodos);

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
		(sa_materia_periodo_lectivo.id_periodo_lectivo IN (".$cadena_periodos."))
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
			$graph->title->Set('Cantidad de alumnos por categorías ('.$nombre_periodo_lectivo.')');
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
			$p1->SetLegend('Categorías');

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
		sa_materia_periodo_lectivo.id_periodo_lectivo IN (". implode(',',$id_periodos).") and sa_alumno.estatus = 'A' and sa_saldo_alumno.saldo != '0.00 AND sa_alumno.baja=0'
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
		$graph->title->Set('Cartera de grado - Deudas');
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
	
		if(isset($_GET['print'])){
			if($_GET['print']==true){
				echo '<script type="text/javascript">  window.print() </script>';
			}
		}
	
	}



	/*Exportar a excel*/
	public function ExportarCanteraDeudas(){
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
		sa_materia_periodo_lectivo.id_periodo_lectivo IN (". implode(',',$id_periodos).") and sa_alumno.estatus = 'A' and sa_saldo_alumno.saldo != '0.00 AND sa_alumno.baja=0'
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

		$objPHPExcel = new \PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getProperties()->setCreator("Uteg")
		->setLastModifiedBy("Uteg")
		->setTitle("Office 2007 XLSX Document")
		->setSubject("Office 2007 XLSX Document")
		->setDescription("Document for Office 2007 XLSX, generated using PHP classes.")
		->setKeywords("office 2007 openxml php")
		->setCategory("Cartera de grado");
		 
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', 'Modalidad')
		->setCellValue('B1', 'Alumno')
		->setCellValue('C1', 'Cédula')
		->setCellValue('D1', 'Email')
		->setCellValue('E1', 'Telefono')
		->setCellValue('F1', 'Valor Pendiente');

		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);

		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)
		->setName('Verdana')
		->setSize(12)
		->getColor()->setRGB('6F6F6F');
		$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true)
		->setName('Verdana')
		->setSize(12)
		->getColor()->setRGB('6F6F6F');
		$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true)
		->setName('Verdana')
		->setSize(12)
		->getColor()->setRGB('6F6F6F');
		$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true)
		->setName('Verdana')
		->setSize(12)
		->getColor()->setRGB('6F6F6F');
		$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true)
		->setName('Verdana')
		->setSize(12)
		->getColor()->setRGB('6F6F6F');
		$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true)
		->setName('Verdana')
		->setSize(12)
		->getColor()->setRGB('6F6F6F');

		$i=2;
		$j=0;
		foreach ($arreglo as $key_e => $value_e) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$i, $arreglo[$j]['modalidad']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$i, $arreglo[$j]['nombre']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$i, $arreglo[$j]['cedula']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$i, $arreglo[$j]['email_uteg']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$i, $arreglo[$j]['telefono']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$i, $arreglo[$j]['saldo']);
			$i++;
			$j++;
		}

		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="Cartera de grado.xlsx"');
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



	public function CanteraIngresos(){
		//OBTENER DATA
		$periodos = datPeriodoConfig::find()->asArray()->all();
		$id_periodos = array();
		foreach ($periodos as $key => $value) {
			$id_periodos[] = $value['id_periodo'];
		}

		$sql = "SELECT sa_registro.id,
		sa_registro.descripcion,
		sa_registro_materias.creditos,
		sa_registro_materias.costo,
		sa_registro.id_periodo_lectivo
		FROM sa_registro
		INNER JOIN sa_registro_materias ON(sa_registro.id=sa_registro_materias.id_registro)
		WHERE sa_registro.id_periodo_lectivo IN (".implode(',', $id_periodos).")";
		
		$primaryConnection = \Yii::$app->db_siga;
		$command = $primaryConnection->createCommand($sql);
		$results = $command->queryAll();
		 
		$array_datas = array();
		foreach ($results as $key => $value) {
			$obj = new \stdClass();
			if($this->findModelPeriodoV($value['id_periodo_lectivo'])=='Presencial'){
				$obj->tipoPeriodo = 'Presencial';
			}else{
				$obj->tipoPeriodo = 'Semipresencial';
			}
			$obj->descripcion = $value['descripcion'];
			$obj->costo    = $value['costo'];
			$obj->creditos = $value['creditos'];
			array_push($array_datas, $obj);
		}
		$total_presencial = 0;
		$total_semipresencial = 0;
		$count_presencial = 0;
		$count_semipresencial = 0;
		for($i =0; $i < count($array_datas); $i++){
			if($array_datas[$i]->tipoPeriodo == 'Presencial'){
				$total_presencial += ($array_datas[$i]->costo+$array_datas[$i]->creditos);
				$count_presencial++;
			}else if($array_datas[$i]->tipoPeriodo == 'Semipresencial'){
				$total_semipresencial += ($array_datas[$i]->costo+$array_datas[$i]->creditos);
				$count_semipresencial++;
			}
		}

		$labels = array();
		$data = array();
		array_push($data, $total_presencial);
		array_push($data, $total_semipresencial);
		array_push($labels, 'Presencial ','Semipresencial');

		//COMENZAR EL REPORTE
		require_once(Yii::getAlias('@vendor'). '/jpgraph/jpgraph.php');
		require_once(Yii::getAlias('@vendor'). '/jpgraph/jpgraph_bar.php');

		$graph = new \Graph(500,250);
		$graph->SetScale("textlin");

		$theme_class=new \UniversalTheme;

		$graph->SetTheme($theme_class);
		$graph->img->SetAntiAliasing(false);
		$graph->SetBox(false);
		$graph->title->Set('Cartera de grado - Ingresos');

		$graph->img->SetAntiAliasing();

		$graph->yaxis->HideLine(false);
		$graph->yaxis->HideTicks(false,false);

		$graph->xgrid->Show();
		$graph->xgrid->SetLineStyle("solid");
		$graph->xaxis->SetTickLabels($labels);
		$graph->xgrid->SetColor('#E3E3E3');


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
		$ruta = "ingresos.png";
		$graph ->Stroke($uta_base.$ruta);

		echo '<img src="'.Url::base().'/public/'.$ruta.'" border="0" alt="Este es el ejemplo de un texto alternativo" >';
		
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
	public function findModelPeriodoV($id_periodo) {
		if (($model = datPeriodoConfig::find()->where(['id_periodo' => $id_periodo])->one()) !== null) {
			return $model->tipo;
		} else {
			return false;
		}
	}
}
