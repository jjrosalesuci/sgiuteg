<?php

namespace app\modules\mod_evaluaciones\controllers;

use Yii;
use app\modules\mod_evaluaciones\models\datPregunta;
use app\modules\mod_evaluaciones\models\datRespuestas;
use app\modules\mod_nomencladores\models\datDocentes;
use app\modules\mod_nomencladores\models\datAsignatura;
use app\modules\mod_nomencladores\models\datDocentesMaterias;
use app\modules\mod_evaluaciones\models\datDatosEvaluado;

use app\models\User;

class ResultadosController extends \yii\web\Controller
{   
    public function actionIndex()
    {   
       return $this->render('docente'); 
    }
    
    /*
    * Cargar los doocentes que han sido evaluados
    */
    public function actionGetevaluados(){                       
        $request        = Yii::$app->request;

        $offset         = $request->post('start');
        $limit          = $request->post('limit');
        $filter         = $request->post('query');
        $id_periodo     = $request->post('id_periodo'); 

        $datos_finales  = array(); 

        if ($offset == NULL) {
            $offset = 0;
        }
        if ($limit == NULL) {
             $limit = 14;
        }

         if($filter!=''){
           $sql ="SELECT DISTINCT
                      (dat_evaluaciones.id),
                      dat_datos_evaluado.id_evaluacion, 
                      dat_evaluaciones.id_periodo, 
                      dat_evaluaciones.nombre_periodo,
                      dat_evaluaciones.titulo, 
                      dat_datos_evaluado.id_trabajador, 
                      dat_datos_evaluado.nombre_trabajador, 
                      dat_datos_evaluado.nombre_asignatura, 
                      dat_datos_evaluado.id_asignatura
                    FROM 
                      m_evaluaciones.dat_evaluaciones, 
                      m_evaluaciones.dat_datos_evaluado
                    WHERE 
                      dat_evaluaciones.id = dat_datos_evaluado.id_evaluacion
                    AND id_periodo = ".$id_periodo."
                  ";

                  $primaryConnection = \Yii::$app->db;
                  $command = $primaryConnection->createCommand($sql);
                  $evaluaciones = $command->queryAll();

                  $datos_limpios = array();
                  foreach ($evaluaciones as $key => $value) {
                      if(!isset($datos_limpios[$value['id_trabajador']])){
                        $datos_limpios[$value['id_trabajador']]=$value;
                        unset($datos_limpios[$value['id_trabajador']]['titulo']);
                        $datos_limpios[$value['id_trabajador']]['cant_eva']=1;
                        $datos_limpios[$value['id_trabajador']]['e_1']     =$value['titulo'];
                      }else{
                        $datos_limpios[$value['id_trabajador']]['cant_eva']++;
                        $datos_limpios[$value['id_trabajador']]['e_'.$datos_limpios[$value['id_trabajador']]['cant_eva']]= $value['titulo'];
                      }
                  }

                  $otro = array();
                  foreach ($datos_limpios as $key => $value) {
                     $otro[]=$value;
                  }
                  echo json_encode(array('data'=>$otro));

         }else{
                 $sql ="SELECT DISTINCT
                      (dat_evaluaciones.id),
                      dat_datos_evaluado.id_evaluacion, 
                      dat_evaluaciones.id_periodo,
                      dat_evaluaciones.nombre_periodo,
                      dat_evaluaciones.titulo, 
                      dat_datos_evaluado.id_trabajador, 
                      dat_datos_evaluado.nombre_trabajador, 
                      dat_datos_evaluado.nombre_asignatura, 
                      dat_datos_evaluado.id_asignatura
                    FROM 
                      m_evaluaciones.dat_evaluaciones, 
                      m_evaluaciones.dat_datos_evaluado
                    WHERE 
                      dat_evaluaciones.id = dat_datos_evaluado.id_evaluacion
                    AND id_periodo = ".$id_periodo."
                  ";

                  $primaryConnection = \Yii::$app->db;
                  $command = $primaryConnection->createCommand($sql);
                  $evaluaciones = $command->queryAll();

                  $datos_limpios = array();
                  foreach ($evaluaciones as $key => $value) {
                      if(!isset($datos_limpios[$value['id_trabajador']])){
                        $datos_limpios[$value['id_trabajador']]=$value;
                        unset($datos_limpios[$value['id_trabajador']]['titulo']);
                        $datos_limpios[$value['id_trabajador']]['cant_eva']=1;
                        $datos_limpios[$value['id_trabajador']]['e_1']     =$value['titulo'];
                      }else{
                        $datos_limpios[$value['id_trabajador']]['cant_eva']++;
                        $datos_limpios[$value['id_trabajador']]['e_'.$datos_limpios[$value['id_trabajador']]['cant_eva']]= $value['titulo'];
                      }
                  }

                  $otro = array();
                  foreach ($datos_limpios as $key => $value) {
                     $otro[]=$value;
                  }
                  echo json_encode(array('data'=>$otro));
         }
    }

    public function actionResultado($id_trabajador,$id_periodo,$nombre_periodo,$nombre_trabajador,$nombre_asignatura,$id_asignatura)
    {    
         return $this->render('index',[
                'id_trabajador'      => $id_trabajador,
                'id_periodo'         => $id_periodo,
                'nombre_periodo'     => $nombre_periodo,
                'nombre_trabajador'  => $nombre_trabajador,
                'nombre_asignatura'  => $nombre_asignatura,
                'id_asignatura'      => $id_asignatura

         ]);
    }
      
    public function actionCargarrespuestas()
    {
        $request = Yii::$app->request;
        $id_evaluacion      = $request->post('id_evaluacion');
        $id_docente         = $request->post('id_trabajador');
        $id_asignatura      = $request->post('id_asignatura');

        $primaryConnection = \Yii::$app->db;
        $command = $primaryConnection->createCommand("
          SELECT 
            m_evaluaciones.dat_evaluacion_respuestas.id_pregunta, 
            count(m_evaluaciones.dat_evaluacion_respuestas.id) as cantidad   
          FROM 
            m_evaluaciones.dat_pregunta 
          INNER JOIN 
            m_evaluaciones.dat_evaluacion_respuestas 
            ON (m_evaluaciones.dat_pregunta.id_pregunta = dat_evaluacion_respuestas.id_pregunta)  
          INNER JOIN m_evaluaciones.dat_datos_evaluado
            ON (m_evaluaciones.dat_evaluacion_respuestas.id_datos_evaluado = m_evaluaciones.dat_datos_evaluado.id)   
          WHERE m_evaluaciones.dat_evaluacion_respuestas.respuesta in ('Excelente','Bien','Muy bien')
          AND   m_evaluaciones.dat_datos_evaluado.id_asignatura = ".$id_asignatura."
          AND   m_evaluaciones.dat_datos_evaluado.id_trabajador = ".$id_docente."           
          AND dat_evaluacion_respuestas.id_pregunta 
            IN ( SELECT id_pregunta 
                 FROM m_evaluaciones.dat_evaluacion_pregunta 
                 WHERE id_evaluacion =".$id_evaluacion." )             
          GROUP BY dat_evaluacion_respuestas.id_pregunta
          ORDER BY dat_evaluacion_respuestas.id_pregunta
         ");

        $resultados = $command->queryAll();
        $arreglo_final  = array();
        $i=1;       
        foreach ($resultados as $key => $value) {
          $value['indice']=$value['id_pregunta'];// $i++;
          $arreglo_final[]=$value;
        }
        echo json_encode(array('data' => $arreglo_final));
    }


    /*
    *  Método que es para mostrar los docentes y el estatus que tienen con respecto a una evaluacion
    */

    public function actionGetdocentesmateriasevaluacion()
    {
      $request = Yii::$app->request;
      $offset = $request->post('start');
      $limit = $request->post('limit');
      if ($offset == NULL) {
          $offset = 0;
      }
      if ($limit == NULL) {
           $limit = 14;
      }

      $count    = datDocentes::find()->where(['estatus' => 'A'])->count();
      $docentes = datDocentes::find()->offset($offset)->limit($limit)->where(['estatus' => 'A'])->orderBy('id')->asArray()->all();

      $arreglo = array();
      foreach ($docentes as $key => $value) {
     
        $materias = $this->Getmateriasdocente($value['id']);

        foreach ($materias as $key_m => $value_m) {
            $item =  array();
            $item['id_docente']       = $value['id'];
            $item['nombre_completo']  = $value['nombre'].' '.$value['segundo_nombre'].' '.$value['apellido'].' '.$value['apellido_materno'];
            $item['id_materia']       = $value_m['id_materia'];
            $item['materia']          = $value_m['nombre'];

            $usuarios_respuesta = datDatosEvaluado::find()->where([
                'id_asignatura' =>  $value_m['id_materia'],
                'id_trabajador' =>  $item['id_docente'],
                'id_evaluacion' =>  43, //cable quitar
            ])->asArray()->all();

            //Contar por roles 
            $contador_estudiante = 0;
            $contador_decano     = 0;
            $contador_auto       = 0;
            foreach ($usuarios_respuesta as $key_resp => $value_resp) {
              // Buscar el rol
              $usuario = User::findOne($value_resp['id_usuario']);
              switch ($usuario->role) {
               case 4:
                    $contador_estudiante++;
               break;
               case 2:
                    $contador_auto++;
               break;
               case 12:
                    $contador_decano++;
               break;
              };
            }

            $item['e_estudiante']     = $contador_estudiante;
            $item['a_evaluacion']     = $contador_auto;
            $item['e_decano']         = $contador_decano;

            $arreglo[] = $item;
        }
      }
      echo json_encode(array('count'=>$count,'data'=>$arreglo)); 
    }

    /*
    * Metodo que complementa el de arriba
    */
    
    public function Getmateriasdocente($id_docente)
    {
        $docente       = datDocentes::findOne($id_docente);
        $model         = datDocentesMaterias::find()->where(['id_docente' => $id_docente,'estatus' => 'A'])->asArray()->all();
        $arreglo       = array();
        foreach ($model as $key => $value) {
            $nombre = datAsignatura::findOne($value['id_materia']);
            $bandera = false;
            foreach ($arreglo as $key => $valuemat) {
                if($value['id_materia']==$valuemat['id_materia']){
                    $bandera = true;
                }
            }
            if($bandera==false)
            {
              $arreglo[] = array('id_materia'=>$value['id_materia'],'nombre'=>$nombre->nombre);
            }            
        }
        return $arreglo; 
    }



    /*
    *  Funcion  para obtener las evaluaciones dado
    *  Periodo,Docentes,Asignatura
    */

    public function actionGetbvaluacionespdasig()
    {
        $request         = Yii::$app->request;
        $id_periodo      = $request->post('id_periodo');
        $id_trabajador   = $request->post('id_trabajador');
        $id_asignatura   = $request->post('id_asignatura');
        
        $sql = "
          SELECT  DISTINCT  
            dat_datos_evaluado.id_evaluacion,dat_evaluaciones.titulo,dat_evaluaciones.fecha
          FROM 
            m_evaluaciones.dat_datos_evaluado, 
            m_evaluaciones.dat_evaluaciones
          WHERE 
            dat_datos_evaluado.id_evaluacion = dat_evaluaciones.id
          AND
            dat_evaluaciones.id_periodo      = ".$id_periodo."
          AND 
            dat_datos_evaluado.id_trabajador = ".$id_trabajador."
          AND
            dat_datos_evaluado.id_asignatura = ".$id_asignatura."";

       $primaryConnection = \Yii::$app->db;
       $command           = $primaryConnection->createCommand($sql);
       $evaluaciones      = $command->queryAll();

       $suma_nota = 0;
       $cantidad  = 0;
       //Buscar las notas por evaluaciones
       foreach ($evaluaciones as $key => $value) {         
         $respuestas                 = $this->BuscarRespuestasDocenteEvaluacion($id_trabajador,$value['id_evaluacion'],$id_asignatura);
         $notas_por_preguntas        = $this->NotasDadaRespuesta($respuestas);
         $nota                       = $this->Promedio($notas_por_preguntas);
         $evaluaciones[$key]['nota'] = $nota;
         $suma_nota = $suma_nota + $nota;
         $cantidad ++;
       }

       if($cantidad > 0){
         $nota_promedio = $suma_nota / $cantidad;
       }else{
         $nota_promedio = 0;
       }
       foreach ($evaluaciones as $key => $value) {   
           $evaluaciones[$key]['nota_promedio'] = $nota_promedio;
       }                 
       echo json_encode(array('data'=>$evaluaciones));        
    }

    /*
    * Buscar las cantidads de respuestas dada
    * Periodo,Docentes,Asignatura
    */

    public function actionCantidadesrespuestas()
    {
         $request            = Yii::$app->request;
         $id_evaluacion      = $request->post('id_evaluacion');
         $id_docente         = $request->post('id_trabajador');
         $id_asignatura      = $request->post('id_asignatura');         
         $notas_promedio     = $this-> BuscarNotasPromedio($id_evaluacion);

        /*$id_docente               = 1085;ar
          $id_evaluacion            = 52;
          $id_asignatura            = 410;*/

         $respuestas               = $this->BuscarRespuestasDocenteEvaluacion($id_docente,$id_evaluacion,$id_asignatura);
         $notas_por_preguntas      = $this->NotasDadaRespuesta($respuestas);
         $nota                     = $this->Promedio($notas_por_preguntas);
          
         $datos_finales            = array();
         foreach ($respuestas as $key => $value) {
            
              $id_pregunta = $key;
              $pregunta = datPregunta::findOne($id_pregunta);
              
              $elemento = new \StdClass();
              $elemento->id_pregunta     = $pregunta->id_pregunta;
              $elemento->texto           = $pregunta->texto;
            
              $aux_arr = array();
              foreach ($value as $key_v => $value_v) {
                  $elemento->$key_v = $value_v;
              }

              $elemento->promedio_nota=0;
              foreach ($notas_promedio as $key_np => $value_np) {
                  if( $elemento->id_pregunta   == $value_np['dat_pregunta']){
                      $elemento->promedio_nota   = $value_np['promedio'];
                  }
              }

              $elemento->nota = $notas_por_preguntas[$id_pregunta];

              $datos_finales[]= $elemento;                     
        }       
        echo json_encode(array('data'=>$datos_finales)); 
    }



    public function BuscarNotasPromedio($id_evaluacion){

             $sql = "SELECT DISTINCT dat_trabajador_dat_evaluacion_dat_pregunta_nota.dat_pregunta,avg(dat_trabajador_dat_evaluacion_dat_pregunta_nota.dat_nota) as promedio
                 FROM 
                    m_evaluaciones.dat_trabajador_dat_evaluacion_dat_pregunta_nota 
                 WHERE dat_trabajador_dat_evaluacion_dat_pregunta_nota.dat_evaluacion = ".$id_evaluacion."
                 GROUP BY dat_pregunta
                 ORDER BY dat_pregunta";

            $primaryConnection = \Yii::$app->db;
            $command           = $primaryConnection->createCommand($sql);
            $notas      = $command->queryAll();
            return $notas;

    }   

    /*
    * 
    */
    public function actionTest()
    {
        $id_docente               = 1085;
        $id_evaluacion            = 52;
        $id_asignatura            = 410;
        $respuestas               = $this->BuscarRespuestasDocenteEvaluacion($id_docente,$id_evaluacion,$id_asignatura);
        $notas_por_preguntas      = $this->NotasDadaRespuesta($respuestas);
        $nota                     = $this->Promedio($notas_por_preguntas);
        echo $nota;
    }
     
    
    private function getEvaluaciones($id_periodo,$id_trabajador,$id_asignatura) {
        $sql = "
          SELECT  DISTINCT  
            dat_datos_evaluado.id_evaluacion,dat_evaluaciones.titulo,dat_evaluaciones.fecha
          FROM 
            m_evaluaciones.dat_datos_evaluado, 
            m_evaluaciones.dat_evaluaciones
          WHERE 
            dat_datos_evaluado.id_evaluacion = dat_evaluaciones.id
          AND
            dat_evaluaciones.id_periodo      = ".$id_periodo."
          AND 
            dat_datos_evaluado.id_trabajador = ".$id_trabajador."
          AND
            dat_datos_evaluado.id_asignatura = ".$id_asignatura."";

       $primaryConnection = \Yii::$app->db;
       $command           = $primaryConnection->createCommand($sql);
       $evaluaciones      = $command->queryAll();

       $suma_nota = 0;
       $cantidad  = 0;
       //Buscar las notas por evaluaciones
       foreach ($evaluaciones as $key => $value) {         
         $respuestas                 = $this->BuscarRespuestasDocenteEvaluacion($id_trabajador,$value['id_evaluacion'],$id_asignatura);
         $notas_por_preguntas        = $this->NotasDadaRespuesta($respuestas);
         $nota                       = $this->Promedio($notas_por_preguntas);
         $evaluaciones[$key]['nota'] = $nota;
         $suma_nota = $suma_nota + $nota;
         $cantidad ++;
       }

       if($cantidad > 0){
         $nota_promedio = $suma_nota / $cantidad;
       }else{
         $nota_promedio = 0;
       }
       foreach ($evaluaciones as $key => $value) {   
           $evaluaciones[$key]['nota_promedio'] = $nota_promedio;
       }    
       return $evaluaciones;
    }
    
    private function getPreguntas($id_evaluacion,$id_trabajador,$id_asignatura){
         $notas_promedio     = $this-> BuscarNotasPromedio($id_evaluacion);
         $respuestas               = $this->BuscarRespuestasDocenteEvaluacion($id_trabajador,$id_evaluacion,$id_asignatura);
         $notas_por_preguntas      = $this->NotasDadaRespuesta($respuestas);
         $nota                     = $this->Promedio($notas_por_preguntas);
          
         $datos_finales            = array();
         foreach ($respuestas as $key => $value) {            
              $id_pregunta = $key;
              $pregunta = datPregunta::findOne($id_pregunta);              
              $elemento = new \StdClass();
              $elemento->id_pregunta     = $pregunta->id_pregunta;
              $elemento->texto           = $pregunta->texto;            
              $aux_arr = array();
              foreach ($value as $key_v => $value_v) {
                  $elemento->$key_v = $value_v;
              }
              $elemento->promedio_nota=0;
              foreach ($notas_promedio as $key_np => $value_np) {
                  if( $elemento->id_pregunta   == $value_np['dat_pregunta']){
                      $elemento->promedio_nota   = $value_np['promedio'];
                  }
              }
              $elemento->nota = $notas_por_preguntas[$id_pregunta];
              $datos_finales[]= $elemento;                     
        } 
        return $datos_finales;
    }


    /*
    * Metodo para exportar el resultado del docente
    */
    public function actionExportarpdfdocente() {
        //Get data of request
        $request   = Yii::$app->request;
        $semestre  = $request->get('semestre');  
        $nombre    = $request->get('nombre');
        $asignatura= $request->get('asignatura'); 
        $nota = $request->get('nota');            
        $id_periodo = $request->get('idperiodo');    
        $id_trabajador = $request->get('idtrabajador');    
        $id_asignatura = $request->get('idasignatura');  
        $id_evaluacion = $request->get('idevaluacion');
        
        $myeval = $this->getEvaluaciones($id_periodo, $id_trabajador, $id_asignatura);        
        $mypreg = $this->getPreguntas($id_evaluacion, $id_trabajador, $id_asignatura);  
        $aux_nota = $this->BuscarNotasPromedio($id_evaluacion);
        
        var_dump($id_evaluacion);die;
        
        $notaEvaluado = array();
        $notaPromedio = array();
        $xaxis_array = array();
        for($y = 0; $y<count($aux_nota);$y++){
            array_push($notaPromedio,$aux_nota[$y]['promedio']);
            array_push($xaxis_array, $aux_nota[$y]['dat_pregunta']);
        }
        for($z = 0; $z < count($mypreg);$z++){
            array_push($notaEvaluado, $mypreg[$z]->nota);
        }       
        
        
    
        
        $this->generarGrafico($notaEvaluado,$notaPromedio,$xaxis_array);
            
        require_once(Yii::getAlias('@vendor'). '/html2pdf/html2pdf.class.php');               
        ob_start();         
        ?>        
        <style>
            html{
                width: 450px;
                margin: 0 auto;
            }
            .header{
                color:#00008B;
                text-align: center;
            }
            .image{
                margin-left: 150px;
            }
            .marginleft{
                margin-left: 10px;
            }     
            table
            {
                padding: 0;
                border: solid 1mm LawnGreen;
                font-size: 12pt;
                background: #FFFFFF;
                text-align: left;
                vertical-align: middle;
            }
            #the-table { border:1px solid #bbb;border-collapse:collapse;margin: 0px ;width: 50%;}
            #the-table td,#the-table th { border:1px solid #ccc;border-collapse:collapse;padding:2px; }
            #the-table2 { border:1px solid #bbb;border-collapse:collapse;margin: 10px ;width: 97%}
            #the-table2 td,#the-table2 th { border:1px solid #ccc;border-collapse:collapse;padding:2px; }
            #the-table3 { border:1px solid #bbb;border-collapse:collapse;margin: 10px ;width: 97%}
            #the-table3 td,#the-table3 th { border:1px solid #ccc;border-collapse:collapse;padding:2px; }
            #the-table4 { border:1px solid #bbb;border-collapse:collapse;margin: 10px ;width: 97%}
            #the-table4 td,#the-table4 th { border:1px solid #ccc;border-collapse:collapse;padding:2px; }
            #the-table5 { border:1px solid #bbb;border-collapse:collapse;margin: 10px ;width: 97%}
            #the-table5 td,#the-table5 th { border:1px solid #ccc;border-collapse:collapse;padding:2px; }
        </style>
        <page  backtop="10mm" backbottom="10mm" backleft="10mm" backright="10mm">
            <img src="images/logo.png" alt="imagen" style="text-align: center;width: 160px;height: 50px;"/>
            <h1 class="header">Resultados del docente</h1>           
            <br>            
            <br>
            <p><b>Profesor: </b><?php echo $nombre; ?></p>
            <p><b>Semestre: </b><?php echo $semestre; ?></p>
            <p><b>Asignatura: </b><?php echo $asignatura;?></p>
            <p><b>Nota final:</b><?php echo $nota;?></p>   
            <h2>Desglose de evaluaciones</h2> 
            <br>               
                <?php                                            
                for($j =0;$j < count($myeval); $j++){
                    $titulo = $myeval[$j]['titulo'];
                    $nota = $myeval[$j]['nota'];                     
                    ?>
            <table  id="the-table">
                        <thead>
                            <tr style="background:#eeeeee;">
                                <td><b><?php echo $titulo ;?></b></td>
                                <td><?php echo $nota; ?></td>
                            </tr>
                        </thead>                      
                    <?php
                      
                    for($x = 0;$x < count($mypreg);$x++){
                        $pregunta = $mypreg[$x]->texto;
                        $nota_pre = $mypreg[$x]->nota;  
                        ?>
                            <tr>
                                <td><?php echo $pregunta ;?></td>
                                <td><?php echo $nota_pre ;?></td>
                            </tr>
                        <?php
                    }             
                    ?>
                    </table>
                    <br>            
                    <img src="imagen.png" alt="imagen"/>
                    <?php
                }?>           
            <br>            
        </page>       
        <?php        
        $content = ob_get_clean();
        try{               
            $fecha = date('Y-m-d H:i:s');            
            $html2pdf = new \HTML2PDF('P','A4','fr');
            $html2pdf->setDefaultFont('Arial');             
            $html2pdf->setTestIsImage(true);
            $html2pdf->writeHTML($content, false);            
            $html2pdf->Output('uploads/mod_evaluaciones/res_evaluacion/'.$fecha.'_doc.pdf','D');                        
        } catch (\HTML2PDF_exception $ex) {
            echo 'Error: '.$ex;
            exit();
        }        
    }
    
    private function generarGrafico($notaEvaluado,$notaPromedio,$xaxis_array) {
        //Using jpgraph library
        require_once(Yii::getAlias('@vendor').'/jpgraph/jpgraph.php');
        require_once(Yii::getAlias('@vendor').'/jpgraph/jpgraph_line.php');
            
        //Create the graph.
        $graph = new \Graph(450,250,"auto");        
        $graph->SetScale("textlin");  
        $graph->title->Set('Gráfica de cantidad de respuestas por pregunta');
        $graph->SetBox(false);
        $graph->yaxis->HideZeroLabel();
        $graph->yaxis->HideLine(false);        
        $graph->yaxis->HideTicks(false,false);
        
        $graph->img->SetAntiAliasing(true);        

        $graph->xaxis->SetTickLabels($xaxis_array);        
        $graph->ygrid->SetFill(false);
        $graph->legend->SetFrameWeight(1);
        $graph->legend->SetColor('#4E4E4E','#00A78A');
        $graph->legend->SetMarkAbsSize(8);        
        
        //Notas del evaluado
        $lineplotPromedio = new \LinePlot($notaPromedio);
        $lineplotPromedio->SetColor("#15428B");        
        $lineplotPromedio->mark->SetType(MARK_FILLEDCIRCLE);
        $lineplotPromedio->mark->SetColor('#15428B');
        $lineplotPromedio->mark->SetFillColor('#15428B');        
        $lineplotPromedio->SetWeight(0);
        $lineplotPromedio->SetLegend("Nota promedio");            
        $lineplotPromedio->SetCenter();        

        //Notas evaluado
        $lineplotEvaluado = new \LinePlot($notaEvaluado);
        $lineplotEvaluado->SetColor("green");
        $lineplotEvaluado->SetLegend('Line 1');
        $lineplotEvaluado->mark->SetType(MARK_FILLEDCIRCLE);
        $lineplotEvaluado->mark->SetColor('#69ABC8');
        $lineplotEvaluado->mark->SetFillColor('#69ABC8');
        $lineplotEvaluado->SetWeight(3);
        $lineplotEvaluado->SetLegend("Nota evaluado");
        $lineplotEvaluado->SetCenter();

        //Setuo margin and titles
        $graph->img->SetMargin(40,20,20,40);        

        // Add lineplots to graph        
        $graph->Add($lineplotPromedio);
        $graph->Add($lineplotEvaluado); 
        
        $fileName = "imagen.png";

        $graph->legend->SetFrameWeight(1);
        
        $graph->Stroke($fileName);
    }
    
    /**
     * Esta funcion obtiene todas las evaluaciones de determinado criterio
     * para un docente y las promedia para obtener una evaluacion general
     **/
    public function obtenerEvaluacionGeneralDelDocente($id_docente) {
        $horas_remplazo = $this->contarHorasRemplazo($id_docente);
        $horas_atraso   = $this->contarHorasAtraso($id_docente);
        $horas_faltas   = $this->contarHorasFaltas($id_docente);
        $horas_sah      = $this->contarHorasSalidaAntesHora($id_docente);
        $horas_ict      = $this->contarHorasInicioClasesTarde($id_docente);
        $promedio = ($horas_remplazo+$horas_atraso+$horas_faltas+$horas_sah+$horas_ict)/5;
        if($promedio == 0){
            return "Excelente";
        }else if($promedio == 1){
            return "Muy Bien";
        }else if($promedio>=2 && $promedio < 6){
            return "Bien";
        }else if($promedio>=6 && $promedio < 10){
            return "Regular";
        }else if($promedio >= 10){
            return "Deficiente";
        }
    }
    
    /**
     * Esta funcion obtiner la evaluacion de un docente para cada criterio
     * condicion {horas_remplazo,horas_atraso,horas_faltas,horas_sah,horas_ict}
     **/ 
    public function obtenerEvaluacionDelDocentePorCriterio($id_docente,$tipo) {
        $evaluacion = "";
        if($tipo== "horas_remplazo"){
            $horas = $this->contarHorasRemplazo($id_docente);
        }else if($tipo == "horas_atraso"){
            $horas = $this->contarHorasAtraso($id_docente);
        }else if($tipo == "horas_faltas"){
            $horas = $this->contarHorasFaltas($id_docente);
        }else if($tipo == "horas_sah"){
            $horas = $this->contarHorasSalidaAntesHora($id_docente);
        }else if($tipo == "horas_ict"){
            $horas = $this->contarHorasInicioClasesTarde($id_docente);
        }
        //Calcular la evaluacion en dependencias de las horas
        if($horas == 0){
            $evaluacion = "Excelente";
        }else if($horas == 1){
            $evaluacion = "Muy Bien";
        }else if($horas >= 2 && $horas <6){
            $evaluacion = "Bien";
        }else if($horas >= 6 && $horas < 10){        
            $evaluacion = "Regular";
        }else if($horas >= 10){        
            $evaluacion = "Deficiente";
        }
        return $evaluacion;
    }
    
    /**
     * Esta funcion cuenta el total de horas de remplazo para un docente
     * */
    private  function contarHorasRemplazo($id_docente){
        $total = "";
        $sql = "SELECT SUM(dat_estadisticas.horas_reemplazo) as total FROM m_docente.dat_estadisticas WHERE dat_estadisticas.id_docente=".$id_docente;
        $primaryConnection = \Yii::$app->db;
        $command  = $primaryConnection->createCommand($sql);
        $responses = $command->queryAll();
        foreach ($responses as $key => $value){
            if(isset($value['total'])){
                $total = $value['total'];
                break;
            }
        }
        if(!empty($total)){            
            list($h,$m,$s) = split('[:]', $total);
            $totalHoras = intval($h);
            $minutos = intval($m);
            $totalHoras = $this->formatearHora($total);
            if($minutos>=30)
                $totalHoras++; 
        
        }else{
            $totalHoras = 0;
        }            
        return $totalHoras;
    }
    
     /**
     * Esta funcion cuenta el total de horas de faltas para un docente
     * */
    private function contarHorasFaltas($id_docente){
        $total = "";
        $sql = "SELECT SUM(dat_estadisticas.horas_faltas) as total FROM m_docente.dat_estadisticas WHERE dat_estadisticas.id_docente=".$id_docente;
        $primaryConnection = \Yii::$app->db;
        $command  = $primaryConnection->createCommand($sql);
        $responses = $command->queryAll();
        foreach ($responses as $key => $value) {
            if(isset($value['total'])){
                $total = $value['total'];
                break;
            }
        }
        if(!empty($total)){
            list($h,$m,$s) = split('[:]', $total);
            $totalHoras = intval($h);
            $minutos = intval($m);        
            $totalHoras = $this->formatearHora($total);
            if($minutos>=30)
                $totalHoras++;                    
        }else{            
            $totalHoras = 0;
        }
        return $totalHoras;        
    }
    
     /**
     * Esta funcion cuenta el total de horas de atraso para un docente
     * */
    
     private function contarHorasAtraso($id_docente) {
        $total = "";
        $sql = "SELECT SUM(dat_estadisticas.minutos_atrasos) as total FROM m_docente.dat_estadisticas WHERE dat_estadisticas.id_docente=".$id_docente;
        $primaryConnection = \Yii::$app->db;
        $command  = $primaryConnection->createCommand($sql);
        $responses = $command->queryAll();
        foreach ($responses as $key => $value) {
            if(isset($value['total'])){
                $total = $value['total'];
                break;
            }
        }
        if(!empty($total)){
            list($h,$m,$s) = split('[:]', $total);
            $totalHoras = intval($h);
            $minutos = intval($m);
            $totalHoras = $this->formatearHora($total);
            if($minutos>=30)
                $totalHoras++;                    
        }else{            
            $totalHoras = 0;
        }
        return $totalHoras;     
     }
     
     /**
     * Esta funcion cuenta el total de horas salidas antes de hora para un docente
     * */
     private function contarHorasSalidaAntesHora($id_docente) {
        $total = "";
        $sql = "SELECT SUM(dat_estadisticas.minutos_salidas_ah) as total FROM m_docente.dat_estadisticas WHERE dat_estadisticas.id_docente=".$id_docente;
        $primaryConnection = \Yii::$app->db;
        $command  = $primaryConnection->createCommand($sql);
        $responses = $command->queryAll();
        foreach ($responses as $key => $value) {
            if(isset($value['total'])){
                $total = $value['total'];
                break;
            }
        }
        if(!empty($total)){
            list($h,$m,$s) = split('[:]', $total);
            $totalHoras = intval($h);
            $minutos = intval($m);
            $totalHoras = $this->formatearHora($total);
            if($minutos>=30){
                $totalHoras++;                                    
            }
        }else{            
            $totalHoras = 0;
        }
        return $totalHoras;     
     }
     
    /**
     *Esta funcion utiliza obtiene el total de horas de inicio de clases
     *atrasadas
     *esto hay que consultarlo
     **/
    private function contarHorasInicioClasesTarde($id_docente) {
        $total = "";
        $array_hi_horario    = $this->obtnerHoraInicioHorario($id_docente);
        $array_hi_asistencia = $this->obtenerHoraInicioAsistencia($id_docente);
        for($i = 0;$i < count($array_hi_horario);$i++){
            for($j=0;$j<count($array_hi_asistencia);$j++){
                if(isset($array_hi_horario[$i]) && isset($array_hi_asistencia[$j])){
                    if($array_hi_asistencia[$j]>=$array_hi_horario[$i]){
                          $resta = $this->restarHoras($array_hi_horario[$i], $array_hi_asistencia[$j]);                                                    
                          list($h,$m,$s) = explode(":", $resta);
                          $total += $this->formatearHora($resta);
                          if($m>=30)
                              $total++;                          
                   }
                }
            }
        }
        //echo 'TOTAL DE HORAS: '.$total;
        return $total;
    }     
     
     
     /**
      * Esta funcion busca todas las horas de inicio planificadas en el horario 
      * para un docente
      **/
    private function obtnerHoraInicioHorario($id_docente) {
        $sql = "SELECT dat_horario.hora_inicio as hora FROM m_docente.dat_horario WHERE dat_horario.id_docente=".$id_docente;
        $primaryConnection = \Yii::$app->db;
        $command           = $primaryConnection->createCommand($sql);
        $responses        = $command->queryAll();        
        $array_horas = array(); 
        for($i=0;$i<count($responses);$i++){
            if(isset($responses[$i])){
                $array_horas[$i] = $responses[$i]['hora'];              
            }
        }
        return $array_horas;
    }
    
    /**
     *Esta funcion busca todas las horas de inicio de las asistencias para
     *un docente 
     **/
   private function obtenerHoraInicioAsistencia($id_docente) {
        $sql = "SELECT dat_asistencia.hora_inicio as hora FROM m_docente.dat_asistencia WHERE dat_asistencia.id_docent_sup=".$id_docente;
        $primaryConnection = \Yii::$app->db;
        $command           = $primaryConnection->createCommand($sql);
        $responses        = $command->queryAll();        
        $array_horas = array(); 
        for($i=0;$i<count($responses);$i++){
            if(isset($responses[$i])){
                $array_horas[$i] = $responses[$i]['hora'];                   
            }
        }
        return $array_horas;
   }
   
   /** 
    * Esta funcion formatea la hora y la redondea a un decimal
    * Ej 02:30:00 ~ 2.5
    **/
   
    private function formatearHora($hora) {
        if(isset($hora)){
            list($h,$m,$s) = explode(':', $hora);
            $h = (($h * 60) + $m+ ($s/ 60)) / 60;
            return number_format($h,2,",",".");
        }else{            
            $hora = 0;
            return $hora;
        }
    }
   
    /**
     * Esta funcion resta dos horas
     * Ej: $hi = "10:05:20"
     *     $hf = "14:05:20"
     **/
    private function restarHoras($horaInicio,$horaFin) {
        $horai=substr($horaInicio,0,2);
	$mini=substr($horaInicio,3,2);
	$segi=substr($horaInicio,6,2); 

	$horaf=substr($horaFin,0,2);
	$minf=substr($horaFin,3,2);
	$segf=substr($horaFin,6,2);
 
	$ini=((($horai*60)*60)+($mini*60)+$segi);
	$fin=((($horaf*60)*60)+($minf*60)+$segf); 

        $dif=$fin-$ini; 

	$difh=floor($dif/3600);
	$difm=floor(($dif-($difh*3600))/60);
	$difs=$dif-($difm*60)-($difh*3600);
	return date("H:i:s",mktime($difh,$difm,$difs));
    }
    

    public function BuscarRespuestasDocenteEvaluacion($id_docente,$id_evaluacion,$id_asignatura){
      $sql = "
        SELECT 
          dat_evaluacion_respuestas.id_datos_evaluado, 
          dat_datos_evaluado.id_evaluacion, 
          dat_datos_evaluado.id_usuario, 
          dat_datos_evaluado.id_carrera, 
          dat_datos_evaluado.nombre_carrera, 
          dat_datos_evaluado.id_asignatura, 
          dat_datos_evaluado.nombre_asignatura, 
          dat_datos_evaluado.id_trabajador, 
          dat_datos_evaluado.nombre_trabajador, 
          dat_evaluacion_respuestas.id_pregunta, 
          dat_evaluacion_respuestas.id_usuario, 
          dat_evaluacion_respuestas.respuesta, 
          dat_evaluacion_respuestas.id_evaluacion, 
          dat_evaluacion_respuestas.id,
          m_arquitectura.dat_usuarios.role,
          dat_pregunta.tipo
        FROM
          m_evaluaciones.dat_pregunta
        INNER JOIN m_evaluaciones.dat_evaluacion_respuestas 
         ON(dat_evaluacion_respuestas.id_pregunta = dat_pregunta.id_pregunta)
        INNER JOIN m_evaluaciones.dat_datos_evaluado
         ON(dat_evaluacion_respuestas.id_datos_evaluado = dat_datos_evaluado.id)
        INNER JOIN m_arquitectura.dat_usuarios
         ON( m_evaluaciones.dat_datos_evaluado.id_usuario = m_arquitectura.dat_usuarios.id)  
        WHERE 
          dat_datos_evaluado.id_trabajador=".$id_docente." 
        AND
          dat_datos_evaluado.id_evaluacion=".$id_evaluacion."
        AND
          dat_datos_evaluado.id_asignatura = ".$id_asignatura."
        AND
          dat_pregunta.tipo = '3'
      ";

      /*Filtrar solamente las que son de opciones*/


      $primaryConnection = \Yii::$app->db;
      $command           = $primaryConnection->createCommand($sql);
      $respuestas        = $command->queryAll();


      //var_dump($respuestas);die;

      $arreglo_resumen   = array();
      foreach ($respuestas as $key => $value) {
        if($value["respuesta"]!=""){
          if(isset($arreglo_resumen[$value['id_pregunta']][ $value["respuesta"]])){
            $arreglo_resumen[$value['id_pregunta']][$value["respuesta"]]++;
          }else{
            $arreglo_resumen[$value['id_pregunta']][$value["respuesta"]]=1;
          }
        }
      }
      return $arreglo_resumen;
    }

    /*
    * 
    * Método que devuelve na nota dada las respuestas de una evaluacion utilizando las formulas con la tabla 
    * de equivalencia
    *
    * 5- Excelente; 4- Muy bien; 3- Bien; 2- Regular; 1- Deficiente
    *
    */

    public function NotasDadaRespuesta($preguntas_respuestas)
    {
       $nota_pregunta = array();
       foreach ($preguntas_respuestas as $key => $value) {
          $total                = 0;
          $acumulado_ponderado  = 0;
          foreach ($value as $key_respues => $cantidad) {
              $total=$total+$cantidad;
              switch ($key_respues) {
                  case 'Excelente':
                       $acumulado_ponderado = $acumulado_ponderado + ($cantidad*5);
                  break;
                   case 'Muy bien':
                       $acumulado_ponderado = $acumulado_ponderado + ($cantidad*4);
                  break;
                   case 'Bien':
                       $acumulado_ponderado = $acumulado_ponderado + ($cantidad*3);
                  break;
                   case 'Regular':
                       $acumulado_ponderado = $acumulado_ponderado + ($cantidad*2);
                  break;
                   case 'Deficiente':
                        $acumulado_ponderado = $acumulado_ponderado + ($cantidad*1);
                  break;
              }
          }
          $nota_pregunta[$key] = $acumulado_ponderado/$total;
      }   
      return $nota_pregunta;     
    }

    /*
    * Funcion para promediar las notas
    */
    public function Promedio($notas)
    {
        $cant  = count($notas);
        $total = 0;
        foreach ($notas as $key => $value) {
          $total = $total + $value;
        }
        return $total/$cant;      
    }

}
