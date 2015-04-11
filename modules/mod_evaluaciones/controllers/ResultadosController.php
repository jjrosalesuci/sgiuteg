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
       //Nota final
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

        /*$id_docente               = 1085;
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

              $elemento->nota = '<b>'.$notas_por_preguntas[$id_pregunta].'</b>';

              $datos_finales[]= $elemento;                     
         }

         echo json_encode(array('data'=>$datos_finales)); 
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


    /*
    * Metodo para exportar el resultado del docente
    */
    public function actionExportarpdfdocente()
    { 
         //Armar el pdf aca
        ob_start();
        ?>
        
        <style type="text/css">
         .encabezado{
          color: #00008B;
         }
        </style>


        <p class="encabezado"><b>RESULTADO DE EVALUACION</b></p>
        <p><b>Nombre y apellidos : </b> Juan <b>Periodo: </b> ENERO - FEB -MARZ 2015 <b>Asignatura : </b>  Programación web</p>
        
        <p><b>Nota final : 5</b></p>
        <p><b>Detalles de las evaluaciones</b></p>

        <p><b>En desarrollo</b></p>
                
        <?php
        $content = ob_get_clean();  
        //echo $content; die;

        $time = time();
        $fecha = date('d-m-Y', $time);       
        require_once(Yii::getAlias('@vendor'). '/html2pdf/html2pdf.class.php');        
        try
        {
            $html2pdf = new \HTML2PDF('P', 'A4', 'fr');
            $html2pdf->setDefaultFont('Arial');
            $html2pdf->writeHTML($content,false);
            $html2pdf->Output('uploads/mod_evaluaciones/res_evaluacion/'.$fecha.'_doc.pdf','D');
        }
        catch(HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
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
