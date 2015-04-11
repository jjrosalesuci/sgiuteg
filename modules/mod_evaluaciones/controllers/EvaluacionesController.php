<?php

namespace app\modules\mod_evaluaciones\controllers;

use Yii;
use app\modules\mod_evaluaciones\models\DatEvaluaciones;
use app\modules\mod_evaluaciones\models\datEvaluacionPregunta;
use app\modules\mod_nomencladores\models\datDocentes;
use app\modules\mod_nomencladores\models\datCarrera;
use app\modules\mod_nomencladores\models\datAsignatura;
use app\modules\mod_nomencladores\models\datSemestres;
use app\modules\mod_evaluaciones\models\datEvaluacionGrupoDestino;
use app\modules\mod_seguridad\models\Roles;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class EvaluacionesController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCargar(){    	
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
              ORDER BY dat_evaluaciones.id DESC
              ;
			";
        $request = Yii::$app->request;
        $offset = $request->post('start');
        $limit = $request->post('limit');
        $filter   = $request->post('query');
        if ($offset == NULL) {
            $offset = 0;
        }
        if ($limit == NULL) {
            $limit = 12;
        }

		if($filter!=''){

            $primaryConnection = \Yii::$app->db;
            $command = $primaryConnection->createCommand($sql);
            $evaluaciones = $command->queryAll();
            $query = DatEvaluaciones::find();
            $count = $query
                         ->andwhere('titulo LIKE :query or estado LIKE :query')
                         ->addParams([':query'=>'%'.$filter.'%'])
                         ->count();

            $data = $query->offset($offset)
                         ->limit($limit)
                         ->orderBy('id')
                         ->andwhere('titulo LIKE :query or estado LIKE :query')
                         ->addParams([':query'=>'%'.$filter.'%'])
                         ->asArray()->all();


            echo json_encode(array('count'=>$count,'data'=>$data));    

        }
        else{

            $primaryConnection = \Yii::$app->db;
            $command = $primaryConnection->createCommand($sql);
            $evaluaciones = $command->queryAll();

            echo json_encode(array('data'=>$evaluaciones));
        }
    }

    public function actionCargartipo(){

        $arreglo[] = array(
            'tipo' =>'Evaluación a docentes'
        );        
        
        echo json_encode(array('data' => $arreglo));

    }

    public function actionCargartipopregunta(){
         $arreglo[] = array(
            'tipo' =>1,
            'tipo_texto' =>'Cualitativo'
         );
         $arreglo[] = array(
            'tipo' =>2,
            'tipo_texto' =>'Cuantitativo'
         ); 
         $arreglo[] = array(
            'tipo' =>3,
            'tipo_texto' =>'Opciones'
         ); 
         echo json_encode(array('data' => $arreglo));
    }
    

  public function actionCreate()
    {
        $model = new DatEvaluaciones();
        $request = Yii::$app->request;
        $model->modalidad               = $request->post('modalidad');
        $model->titulo                  = $request->post('titulo');
        $model->fecha                   = $request->post('fecha');
        $model->tipo                    = $request->post('tipo');
        $model->id_grupo_origen         = $request->post('id_grupo_origen');
        $model->descripcion             = $request->post('descripcion');
        $model->id_periodo              = $request->post('nombre_periodo');
        $model->estado                  = "Elaboración";
        $model->nombre_periodo          = $this->findGrupo($model->id_periodo);

        $roles                          = json_decode($request->post('id_roles'));

        if ($model->save()) {
            foreach ($roles as $key => $value) {
                $aso = new datEvaluacionGrupoDestino();
                $aso->id_evaluacion     = $model->id;
                $aso->id_grupo_destino  = $value;
                $aso->save();
            }

            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Se creó correctamente la evaluación.';
            echo json_encode($result);
        } else {
            $result = new \stdClass();
            $result->success = false;
            $result->msg = 'Ocurrio un error.';
            echo json_encode($result);
        }
    }

    /**
     * Updates an existing Roles model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
     public function actionUpdate()
    {

        $request = Yii::$app->request;
        $id                      = $request->post('id');
        $modalidad               = $request->post('modalidad');
        $titulo                  = $request->post('titulo');
        $fecha                   = $request->post('fecha');
        $tipo                    = $request->post('tipo');
        $id_grupo_origen         = $request->post('id_grupo_origen');
        $descripcion             = $request->post('descripcion');
        $nombre_periodo          = $request->post('ext-comp-1012');
       
        if(is_numeric($request->post('nombre_periodo')))
        {
            $id_periodo              = $request->post('nombre_periodo');
        }
        else if(!is_numeric($request->post('nombre_periodo'))){
            $id_periodo              = $this->findModel($id)->id_periodo;
        }

        $model = $this->findModel($id);
        $model->modalidad               = $modalidad;
        $model->titulo                  = $titulo;
        $model->fecha                   = $fecha;
        $model->tipo                    = $tipo;
        $model->id_grupo_origen         = $id_grupo_origen;
        $model->descripcion             = $descripcion;
        $model->id_periodo              = $id_periodo;
        $model->nombre_periodo          = $nombre_periodo;

        //$this->findGrupo($id_periodo);

        $roles                          = json_decode($request->post('id_roles'));

        if ($model->save()) {

            /*
            * Eliminar todos los destinos ed esa evaluacion
            */
            $connection = \Yii::$app->db;
            $connection->createCommand()->delete('m_evaluaciones.dat_evaluacion_grupo_destino',  [
                 'id_evaluacion' => $model->id
            ])->execute();
            /*
            * Agregar los seleccionados
            */
            foreach ($roles as $key => $value) {
                $aso = new datEvaluacionGrupoDestino();
                $aso->id_evaluacion     = $model->id;
                $aso->id_grupo_destino  = $value;
                $aso->save();
            }

            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Se modificó correctamente la evaluación.';
            echo json_encode($result);
        } else {
            $result = new \stdClass();
            $result->success = false;
            $result->msg = 'Ocurrió un error. ava';
            echo json_encode($result);
        }
    }

    public function actionAsignarestadoproceso()
    {
        $request = Yii::$app->request;
        $id             = $request->post('id');
        $model          = $this->findModel($id);
        $model->estado  = "En proceso";
        if ($model->save()) {
            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Se modificó correctamente la evaluación.';
            echo json_encode($result);
        } else {
            $result = new \stdClass();
            $result->success = false;
            $result->msg = 'Ocurrió un error.';
            echo json_encode($result);
        }

    }
    public function actionAsignarestadofinal()
    {
        $request = Yii::$app->request;
        $id             = $request->post('id');
        $model          = $this->findModel($id);
        $model->estado  = "Finalizada";
        if ($model->save()) {
            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Se modificó correctamente la evaluación.';
            echo json_encode($result);
        } else {
            $result = new \stdClass();
            $result->success = false;
            $result->msg = 'Ocurrió un error.';
            echo json_encode($result);
        }

    }
    public function actionDuplicar()
    {
        $model = new DatEvaluaciones();
        $request = Yii::$app->request;
        $id             = $request->post('id');
        $eval           = $this->findModel($id);
        
        $modalidad               = $eval->modalidad;
        $titulo                  = $eval->titulo;
        $fecha                   = $eval->fecha;
        $tipo                    = $eval->tipo;
        $id_grupo_origen         = $eval->id_grupo_origen;
        $descripcion             = $eval->descripcion;
        $id_periodo              = $eval->id_periodo;

        $nombre_periodo          = $eval->nombre_periodo;

        $estado                  = "Elaboración";

        $model->modalidad               = $modalidad;
        $model->titulo                  = $titulo;
        $model->fecha                   = $fecha;
        $model->tipo                    = $tipo;
        $model->id_grupo_origen         = $id_grupo_origen;
        $model->descripcion             = $descripcion;
        $model->id_periodo              = $id_periodo;
        $model->estado                  = $estado;
        $model->nombre_periodo          = $nombre_periodo;

        $model->save();
      
        $evalpreg = $this->findEvalPreg($id);
        foreach ($evalpreg as $value) {
                  $neweval = new datEvaluacionPregunta();         
                  $neweval->id_evaluacion   =  $model->id;
                  $neweval->id_pregunta     =  $value['id_pregunta'];                
                  $neweval->save();
        }      
      
        $result = new \stdClass();
        $result->success = true;
        $result->msg = 'Se duplicó correctamente la evaluación.';
        echo json_encode($result);
     }

    public function actionDelete()
    {
        $request    = Yii::$app->request;
        $id         = $request->post('id');

        //eliminar asociaciones
        $connection = \Yii::$app->db;
        
        $resultado = $connection->createCommand()->delete('m_evaluaciones.dat_evaluacion_pregunta',  [
         'id_evaluacion' => $id
        ])->execute();

        if($this->findModel($id)->estado == 'Elaboración')
        {
            $this->findModel($id)->delete();
            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Se eliminó correctamente la evaluación.';
            echo json_encode($result);  
        }
        else {
            $result = new \stdClass();
            $result->success = false;
            $result->msg = 'Ocurrió un error.';
            echo json_encode($result);
        }
    
    }

    protected function findModel($id)
    {
        if (($model = DatEvaluaciones::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    
    public function findGrupo($id)
    {
        if (($model = datSemestres::findOne($id)) !== null) {
            return $model->nombre;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function findEvalPreg($id_e)
    {
        $primaryConnection = \Yii::$app->db;
        $command = $primaryConnection->createCommand("
           SELECT 
                dat_evaluacion_pregunta.id_evaluacion, 
                dat_evaluacion_pregunta.id_pregunta
            FROM 
                m_evaluaciones.dat_evaluacion_pregunta 
            WHERE 
                (dat_evaluacion_pregunta.id_evaluacion = ".$id_e.");
         ");

        return $resultados = $command->queryAll();
    }



    /*
    * Parte de los roles
    */

    public function actionGetnodes(){

        $request = Yii::$app->request;
        $node      = $request->post('node');
        $id_evaluacion    = $request->post('id_evaluacion');

        $query = Roles::find();
        $data = $query->orderBy('id_rol')->asArray()->all();

        $nodes = array();
        foreach ($data as $key => $value) {         
            if($value['nombre']=='root'){
                continue;
            }

            if($id_evaluacion==-1){
               $count = 0; 
            }else{
               $count = datEvaluacionGrupoDestino::find()->where(['id_evaluacion' =>$id_evaluacion,'id_grupo_destino'=> $value['id_rol']])->count();
            }
            
            $item  = array(
                'text' => $value['nombre'],                
                'id'   => $value['id_rol'],
                /*'qtip' => $value['link'],*/
                //'qtipTitle' => $f,
                'cls'  => 'file',
                'leaf' => true
            );

            if($count>0){
                $item['checked'] = true;
            }else{
                $item['checked'] = false;
            }
           
            $nodes[] = $item;
        }
        echo json_encode($nodes);
    }

}