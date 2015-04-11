<?php
namespace app\modules\mod_evaluaciones\controllers;

use Yii;
use app\modules\mod_evaluaciones\models\datPreguntas;
use app\modules\mod_evaluaciones\models\datRespuestas;
use app\modules\mod_evaluaciones\models\DatEvaluaciones;
use app\modules\mod_evaluaciones\models\DatEvaluacionUsuarioLog;
use app\modules\mod_nomencladores\models\datAlumnos;
use app\modules\mod_nomencladores\models\datAlumnosMaterias;
use app\modules\mod_nomencladores\models\datDocentesMaterias;
use app\modules\mod_nomencladores\models\datCarrera;
use app\modules\mod_nomencladores\models\datAsignatura;
use app\modules\mod_nomencladores\models\datDocentes;
use app\models\User;
use app\modules\mod_evaluaciones\models\datDatosEvaluado;
use app\modules\mod_nomencladores\models\datMateriasCarreras;


class ResponderController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        if(!\Yii::$app->user->identity){
            die('<pre>Acceso denegado, su session expiró recargue la página.');
        }

        $user_id        = \Yii::$app->user->identity->id;
        $id_role        = \Yii::$app->user->identity->role;

        if ($id_role==12) {
          return $this->render('index_decano');
        } 
        return $this->render('index');
    }


    /*
    * Metodo que lee la informacion procedente del formulario de la evaluacion.
    */

    public function actionSalvar(){
    	 

    	  $user_id 	      = \Yii::$app->user->identity->id;
    	  $request        = Yii::$app->request;
        $id_evaluacion  = $request->post('id_evaluacion');
        $id_materia     = $request->post('materia');
        $id_docente     = $request->post('docente');

        $opcion_redireccion  = $request->post('opcion_redireccion');

        $nombre_materia = datAsignatura::findOne($id_materia)->nombre;
        $modelo_docente = datDocentes::findOne($id_docente);
        $nombre_docente = $modelo_docente->nombre.' '.$modelo_docente->segundo_nombre.' '.$modelo_docente->apellido.' '.$modelo_docente->apellido_materno;
     
    
        /*Registrar los datos del evalado*/
        $model_datos_evaluado = new datDatosEvaluado();
        $model_datos_evaluado->id_evaluacion     = $id_evaluacion;
        $model_datos_evaluado->id_usuario        = $user_id;
        $model_datos_evaluado->id_asignatura     = $id_materia;
        $model_datos_evaluado->nombre_asignatura = $nombre_materia;
        $model_datos_evaluado->id_trabajador     = $id_docente;
        $model_datos_evaluado->nombre_trabajador = $nombre_docente;
        $model_datos_evaluado->save();
        /*Registrar los resultados*/

        $data = $_POST;
        unset($data['id_evaluacion']);
        unset($data['materia']);
        unset($data['docente']);
        unset($data['opcion_redireccion']);
        unset($data['carrera']);
    
        foreach ($data as $key => $value) {
            $partes      = explode('_', $key);
            $model = new datRespuestas();
            $model->id_pregunta   = $partes[1];
            $model->id_usuario    = $user_id;
            $model->id_evaluacion = $id_evaluacion;
            $model->respuesta     = $value;
            $model->id_datos_evaluado = $model_datos_evaluado->id;
            $model->save();
        }

        $log = new DatEvaluacionUsuarioLog();
        $log->id_evaluacion  =  $id_evaluacion;
        $log->id_usuario     =  $user_id;
        $log->save();

        if($opcion_redireccion==2){
          return $this->redirect('completar?id='.$id_evaluacion);
        }else{
          echo 'Usted finalizó correctamente la encuesta';
        }        
    }



    public function actionCompletar($id)
    {	
        $sql = "SELECT 
				  dat_evaluacion_pregunta.id_evaluacion, 
				  dat_evaluacion_pregunta.id_pregunta,
                  dat_evaluacion_pregunta.resaltar, 
				  dat_pregunta.id_pregunta, 
				  dat_pregunta.texto, 
				  dat_pregunta.tipo, 
				  dat_pregunta.id_g_pregunta, 
				  dat_pregunta.opciones
		     	 FROM 
				  m_evaluaciones.dat_evaluacion_pregunta, 
				  m_evaluaciones.dat_pregunta
			 	 WHERE 
				  dat_evaluacion_pregunta.id_pregunta = dat_pregunta.id_pregunta
			  	AND
				 dat_evaluacion_pregunta.id_evaluacion = ".$id.";";
	 
		 $primaryConnection = \Yii::$app->db;
         $command 			= $primaryConnection->createCommand($sql);
         $pregutnas 		= $command->queryAll();

         $sql = "SELECT 
				  dat_evaluaciones.id, 
				  dat_evaluaciones.modalidad, 
				  dat_evaluaciones.id_grupo_origen, 
				  dat_evaluaciones.id_periodo, 
				  dat_evaluaciones.fecha, 
				  dat_evaluaciones.descripcion, 
				  dat_evaluaciones.estado, 
				  dat_evaluaciones.nombre_periodo,
				  dat_evaluaciones.titulo, 
				  dat_evaluaciones.tipo
				FROM 
				  m_evaluaciones.dat_evaluaciones
				WHERE dat_evaluaciones.id = ".$id.";";

         $command = $primaryConnection->createCommand($sql);
         $evaluaciones = $command->queryAll();

         $user        = \Yii::$app->user;
         $id_role     = $user->identity->role;
         $id_user     = \Yii::$app->user->identity->id;
         $id_user_acl = \Yii::$app->user->identity->id_user_acl;

         $carrera = null;
         if($id_role==4){
         	  $carrera = $this->get_carreras($id_user_acl);
         }

         $id_docente = null;
         if($id_role==2){
            $docente       = datDocentes::find()->where(['id_acl_user' => $id_user_acl])->asArray()->all();
            $id_docente    = $docente[0]['id'];           
         }
           

         // 2 Docentes
         // 4 Alumnos

         return $this->render('completar',[
         	    	'evaluacion'    => $evaluaciones[0],
                'preguntas'     => $pregutnas,
                'rol'           => $id_role,
                'id_user'	      => $id_user,
                'id_user_acl'   => $id_user_acl,
                'id_evaluacion' => $id,
                'carrera'	      => $carrera,
                'usuario'       => $user->identity,
                'id_docente'    => $id_docente
         ]);
    }

    public function actionCargar(){   
    	$user = \Yii::$app->user;
        $role = $user->identity->role;
        $user_id = \Yii::$app->user->identity->id;
    	$sql=" SELECT 
		  		dat_evaluaciones.id, 
				  dat_evaluaciones.modalidad,
			    dat_evaluaciones.tipo, 
			   	dat_evaluaciones.id_grupo_origen, 
				 (select nombre from m_arquitectura.dat_rol where m_arquitectura.dat_rol.id_rol = dat_evaluaciones.id_grupo_origen) as grupo_origen,
			   	dat_evaluaciones.id_periodo, 
				  dat_evaluaciones.fecha,	
			 	  dat_evaluaciones.descripcion, 
				  dat_evaluaciones.estado,
				  dat_evaluaciones.titulo,
          dat_evaluaciones.nombre_periodo
			  FROM 
			    m_evaluaciones.dat_evaluaciones
			  WHERE estado = 'En proceso'
			  AND dat_evaluaciones.id in (
			  		select id_evaluacion 
					from m_evaluaciones.dat_evaluacion_grupo_destino
					where id_grupo_destino = ".$role."
			  	)
			  ;
			";

	    	$primaryConnection = \Yii::$app->db;
        $command = $primaryConnection->createCommand($sql);
        $evaluaciones = $command->queryAll();
        echo json_encode(array('data'=>$evaluaciones));
    }


    /*Para los alumnos*/
    public function actionGetuseraclmaterias(){
    	$request       = Yii::$app->request;
      $id_user_acl   = $request->post('id_user_acl');
    	$id_alumno     = datAlumnos::findOne(['id_acl_user' => $id_user_acl])->id;
    	$resultado     = $this->get_materias($id_alumno);
  		echo json_encode(array('data'=>$resultado));    	
    }

    /*
    * Para los decanos
    */
    public function actionGetmaterias(){
        

    }


    /*
    * Metodo que carga los docentes en el combo
    */
    public function actionGetmateriadocentes(){
     	$request     = Yii::$app->request;
      $id_materia     = $request->post('id_materia');
      $id_evaluacion  = $request->post('id_evaluacion');

      $user              = \Yii::$app->user;
      $role              = $user->identity->role;
      $id_usuario        = \Yii::$app->user->identity->id;
      //Todos los docentes
    	$docentes          = $this->get_docente($id_materia);
      //Filtrar por los docentes que el usuario no ha evaluado
      $docentes_evaluados= datDatosEvaluado::find()->where(['id_evaluacion' => $id_evaluacion,'id_usuario' => $id_usuario])->asArray()->all();
            
      $resultado = array();      
      foreach ($docentes as $key => $value) {
         //buscarlo en los que ya se evaluo
          $bandera=false;
          foreach ($docentes_evaluados as $key_evaluados => $value_evaluados) {
            if($value['id_docente_e']==$value_evaluados['id_trabajador']){
              $bandera=true;
            }
          }

          if(!$bandera){
             $resultado[]=$value;
          }
      }    
  		echo json_encode(array('data_e'=>$resultado)); 
    }

    /*
    * value
	* Return id_carrera, nombre
    */
    public function get_carreras($id_user_acl){
    	$model = datAlumnos::findOne(['id_acl_user' => $id_user_acl]);
    	$carrera = datCarrera::findOne($model->id_carrera);
        return array('id' =>$carrera->id,'nombre' =>$carrera->nombre);        
	}

	/*
	* array
	* Return id_materia, nombre
    */
	public function get_materias($id_alumno){
		$model = datAlumnosMaterias::find()->where(['id_alumno' => $id_alumno])->asArray()->all();
		//var_dump($model);
		//die();
		$arreglo = array();
		foreach ($model as $key => $value) {
				$nombre = datAsignatura::findOne($value['id_materia']);
				$arreglo[] = array('id_materia'=>$value['id_materia'],'nombre'=>$nombre->nombre);

        }
		return $arreglo;
	}

	/*
	* array
	* Return id_docente, nombre ej: pepe antonio rosales
    */
	public function get_docente($id_materia){
	
		$model = datDocentesMaterias::find()->where(['id_materia' => $id_materia,'estatus' => 'A'])->asArray()->all();
	    $arreglo_id   =  array();
	    $arreglo_id[] =  '-1';
		  $arreglo = array();
		  foreach ($model as $key => $value) {
			    $bandera = array_search($value['id_docente'],$arreglo_id);
				if($bandera==false){
					     $nombre = datDocentes::findOne($value['id_docente']);
				       $arreglo[] = array('id_docente_e'=>(integer)$value['id_docente'],'nombre_e'=>$nombre->nombre.' '.$nombre->segundo_nombre.' '.$nombre->apellido.' '.$nombre->apellido_materno);
 					     $arreglo_id[]=$value['id_docente'];
				}
       }

		return $arreglo;
	}


  /*
  * metodo para obtener las materias de los docentes
  */
  public function actionGetmatedocentes()
  {
    $request       = Yii::$app->request;
    $id_user_acl   = $request->post('id_user_acl');

    $docente       = datDocentes::find()->where(['id_acl_user' => $id_user_acl])->asArray()->all();
    $id_docente    = $docente[0]['id'];
    // datDocentes::findOne($value['id_docente']);
    $model         = datDocentesMaterias::find()->where(['id_docente' => $id_docente,'estatus' => 'A'])->asArray()->all();
    $arreglo       = array();
    foreach ($model as $key => $value) {
        $nombre = datAsignatura::findOne($value['id_materia']);
        $arreglo[] = array('id_materia'=>$value['id_materia'],'nombre'=>$nombre->nombre);
    }
    echo json_encode(array('data'=>$arreglo)); 
  }

  /*
  * Obtener carreras posibles del docente 
  */

  public function actionGetcarrerasdeldocente()
  {
    $request       = Yii::$app->request;
    $id_docente    = $request->post('id_docente');

    $model = datDocentesMaterias::find()->where(['id_docente' => $id_docente,'estatus' => 'A'])->asArray()->all();
    $arreglo = array();
    foreach ($model as $key => $value) {
        $nombre = datAsignatura::findOne($value['id_materia']);
        $arreglo[] = array('id_materia'=>$value['id_materia'],'nombre'=>$nombre->nombre);
        }
       
    $arreglo1 = array();
    //$arreglo_filtrado = array();
    foreach ($arreglo as $key => $value) {
      $model = datMateriasCarreras::find()->where(['id_materia' => $value['id_materia'],'estatus' => 'A'])->asArray()->all();
      foreach ($model as $key => $value) {
        // Buscar si ya se adiciono paraq adicionarlo
        $bandera = false;
        foreach ($arreglo1 as $key_carreras => $value_carreras) {
          if($value_carreras['id_carrera']==$value['id_carrera']){
            $bandera = true;
          }
        }
        if($bandera==false){
          $arreglo1[]= array('id_carrera' => $value['id_carrera']);
        }
      }     
    }
    $arreglo3 = array();
    foreach ($arreglo1 as $key => $value) {
        $nombre = datCarrera::findOne($value['id_carrera']);
        $arreglo3[] = array('id_carrera'=>$value['id_carrera'],'nombre'=>$nombre->nombre);

        }
         echo json_encode(array('data'=>$arreglo3)); 
  }

   /*
    *  Método que es para mostrar los docentes y el estatus que tienen con respecto a una evaluacion
    */

    public function actionGetdocentesmateriasevaluacion()
    {
      $request        = Yii::$app->request;
      $offset         = $request->post('start');
      $limit          = $request->post('limit');
      $filter         = $request->post('query');
      $id_evaluacion  = $request->post('id_evaluacion'); 

      $datos_finales  = array(); 

      if ($offset == NULL) {
          $offset = 0;
      }
      if ($limit == NULL) {
           $limit = 14;
      }

      if($filter!=''){

           $count = datDocentes::find()
                         ->where('nombre LIKE :query or nombre LIKE :query or apellido LIKE :query')
                         ->andWhere(['estatus' => 'A'])
                         ->addParams([':query'=>'%'.$filter.'%'])
                         ->count();
           $docentes = datDocentes::find()
                         ->offset($offset)
                         ->limit($limit)
                         ->orderBy('id')
                         ->where('nombre LIKE :query or nombre LIKE :query or apellido LIKE :query')
                         ->andWhere(['estatus' => 'A'])
                         ->addParams([':query'=>'%'.$filter.'%'])
                         ->asArray()->all();

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
                    'id_evaluacion' =>  $id_evaluacion, //cable quitar
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

          foreach ($arreglo as $key => $value) {
                    $result          = new \stdClass();
                    $result->id_docente             =  $value['id_docente'];
                    $result->nombre_completo        =  utf8_encode($value['nombre_completo']);
                    $result->id_materia             =  $value['id_materia'];
                    $result->materia                =  utf8_encode($value['materia']);
                    $result->e_estudiante           =  $value['e_estudiante'];
                    $result->a_evaluacion           =  $value['a_evaluacion'];
                    $result->e_decano               =  $value['e_decano'];

                    $datos_finales[]= $result;
          }

          echo json_encode(array('count'=>$count,'data'=>$datos_finales)); 
      }
      else{

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
                'id_evaluacion' =>  $id_evaluacion //cable quitar
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

      foreach ($arreglo as $key => $value) {
                $result          = new \stdClass();
                $result->id_docente             =  $value['id_docente'];
                $result->nombre_completo        =  utf8_encode($value['nombre_completo']);
                $result->id_materia             =  $value['id_materia'];
                $result->materia                =  utf8_encode($value['materia']);
                $result->e_estudiante           =  $value['e_estudiante'];
                $result->a_evaluacion           =  $value['a_evaluacion'];
                $result->e_decano               =  $value['e_decano'];

                $datos_finales[]= $result;
      }

      echo json_encode(array('count'=>$count,'data'=>$datos_finales)); 
      }
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
  
}