<?php

namespace app\modules\mod_evaluaciones\controllers;

use Yii;
use app\modules\mod_evaluaciones\models\datPregunta;
use app\modules\mod_evaluaciones\models\datEvaluacionPregunta;
use app\modules\mod_evaluaciones\models\DatGrupo;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class PreguntasController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    //Todas las preguntas
    public function actionCargarpreguntas()
    {
        //$this->enableCsrfValidation = false;
        
        $request = Yii::$app->request;
        $offset = $request->post('start');
        $limit = $request->post('limit');
        if ($offset == NULL) {
            $offset = 0;
        }
        if ($limit == NULL) {
            $limit = 14;
        }
        $count = datPregunta::find()->count();
        $query = datPregunta::find();
        $data = $query->offset($offset)->limit($limit)->orderBy('id_pregunta')->asArray()->all();
       
        $arreglo_final  = array();
        foreach ($data as $key => $value) {
            if($value['tipo']==1)
            {    
                $value['tipo_texto']= 'Cualitativa';
                $arreglo_final[]=$value;
            }
            if($value['tipo']==2)
            {    
                $value['tipo_texto']= 'Cuantitativa';
                $arreglo_final[]=$value;
            }
            if($value['tipo']==3)
            {    
                $value['tipo_texto']= 'Opciones';
                $arreglo_final[]=$value;
            }
        }
        $arreglo_final1  = array();
        foreach ($arreglo_final as $key => $value) {
                   $value['nombre_grupo'] = $this->findNombreGrupo($value['id_g_pregunta']);
                   $arreglo_final1[] = $value;
        }
   
        echo json_encode(array('count' => $count, 'data' => $arreglo_final1));
    }

    //Las preguntas de una evaluacion

    public function actionCargarpregeval()
    {
        $request = Yii::$app->request;
        $id_evaluacion = $request->post('id_evaluacion');
        
        $primaryConnection = \Yii::$app->db;
        $command = $primaryConnection->createCommand("
           SELECT 
              dat_evaluacion_pregunta.id_pregunta, 
              dat_pregunta.id_pregunta, 
              dat_pregunta.texto, 
              dat_pregunta.tipo, 
              dat_pregunta.id_g_pregunta, 
              dat_pregunta.opciones, 
              dat_evaluacion_pregunta.id_evaluacion, 
              dat_evaluacion_pregunta.resaltar
            FROM 
              m_evaluaciones.dat_pregunta, 
              m_evaluaciones.dat_evaluacion_pregunta
            WHERE 
              dat_evaluacion_pregunta.id_pregunta = dat_pregunta.id_pregunta AND
              dat_evaluacion_pregunta.id_evaluacion = ".$id_evaluacion.";");

        $resultados = $command->queryAll();

        $arreglo_final  = array();
        foreach ($resultados as $key => $value) {
            if($value['tipo']==1)
            {    
                $value['tipo_texto']= 'Cualitativa';               
            }
            if($value['tipo']==2)
            {    
                $value['tipo_texto']= 'Cuantitativa';              
            }
            if($value['tipo']==3)
            {    
                $value['tipo_texto']= 'Opciones';               
            }
            if($value['resaltar']==1)
            {    
                $value['resaltar']=true;               
            }else{
                $value['resaltar']=false;  
            }
            $arreglo_final[]=$value;
        }       


        echo json_encode(array('data' => $arreglo_final));

    }

    public function actionCargartodos(){
        $query = datPregunta::find();
        $data = $query->orderBy('id')->asArray()->all();
        echo json_encode(array('data' => $data));
    }

    
    public function actionCreate()
    {
    	$model = new datPregunta();
        $request = Yii::$app->request;
        $model->texto	                = $request->post('texto');
        $model->tipo 	               	= $request->post('tipo');
        $model->opciones                = $request->post('opciones');
        $model->id_g_pregunta           = $request->post('id_g_pregunta');;
        
        

        if ($model->save()) {
            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Se creó correctamente la pregunta.';
            echo json_encode($result);
        } else {
            $result = new \stdClass();
            $result->success = false;
            $result->msg = 'Ocurrio un error.';
            echo json_encode($result);
        }
        
    }

    public function actionAsignarpreg()
    {
        $request = Yii::$app->request;
        $id_eval = $request->post('id_evaluacion');
        $id_preg = $request->post('id_pregunta');

        if($this->Asignar($id_eval,$id_preg)==true)
        {
            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Se asignó correctamente la pregunta.';
            echo json_encode($result);
        }
        else {
            $result = new \stdClass();
            $result->success = false;
            $result->msg = 'Ocurrio un error.';
            echo json_encode($result);
        }
        

    }

    public function Asignar($id_eval,$id_preg)
    {
        $model = new datEvaluacionPregunta();
        $model->id_evaluacion           = $id_eval;
        $model->id_pregunta             = $id_preg;

        if ($model->save()) {
                return true;
        } else {
                 return false;
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
        $id_pregunta            = $request->post('id_pregunta');  
        $texto                  = $request->post('texto');
        $tipo                   = $request->post('tipo');
        $opciones               = $request->post('opciones');
        $id_g_pregunta          = $request->post('id_g_pregunta');
        
        $model = $this->findModel($id_pregunta);
        $model->texto                   = $texto;
        $model->tipo                    = $tipo;
        $model->opciones                = $opciones;
        $model->id_g_pregunta           = $id_g_pregunta;


        
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

    public function actionDelete()
    {
        $request    = Yii::$app->request;
        $id         = $request->post('id_pregunta');
        if($this->findEvalPreg($id)==null)
        {
            $this->findModel($id)->delete();  
            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Se eliminó correctamente la pregunta.';
            echo json_encode($result); 
        }
        else
        {
            $result = new \stdClass();
            $result->success = false;
            $result->msg = 'No se puede eliminar pues esta asignada a una evaluacion.';
            echo json_encode($result); 
        }
    }

    protected function findModel($id)
    {
        if (($model = datPregunta::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    /*
    * Function que elimina de la evalucion
    */

    public function actionQuitarpreguntaevaluacion(){
        $request       = Yii::$app->request;
        $id_evaluacion = $request->post('id_evaluacion');
        $id_pregunta   = $request->post('id_pregunta');

        $connection = \Yii::$app->db;
        $resultado = $connection->createCommand()->delete('m_evaluaciones.dat_evaluacion_pregunta',  [
        'id_evaluacion' => $id_evaluacion,
        'id_pregunta' => $id_pregunta
        ])->execute();

        if ($resultado) {
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

    /*
    * Function que resalta una pregunta de la evaluacion
    */

    public function actionResaltarpreguntaeva(){

        $request       = Yii::$app->request;
        $id_evaluacion = $request->post('id_evaluacion');
        $id_pregunta   = $request->post('id_pregunta');

        $model = datEvaluacionPregunta::find()->where(['id_evaluacion' => $id_evaluacion,'id_pregunta' => $id_pregunta])->all();
        $model[0]->resaltar = 1;
        if ($model[0]->save()) {
            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Se resaltó correctamente la  pregunta.';
            echo json_encode($result);
        } else {
            $result = new \stdClass();
            $result->success = false;
            $result->msg = 'Ocurrió un error.';
            echo json_encode($result);
        }
    }

     /*
    * Function que resalta una pregunta de la evaluacion
    */

    public function actionQuitarresaltarpreguntaeva(){

        $request       = Yii::$app->request;
        $id_evaluacion = $request->post('id_evaluacion');
        $id_pregunta   = $request->post('id_pregunta');

        $model = datEvaluacionPregunta::find()->where(['id_evaluacion' => $id_evaluacion,'id_pregunta' => $id_pregunta])->all();
        $model[0]->resaltar = 0;
        if ($model[0]->save()) {
            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Se quito el resalto correctamente.';
            echo json_encode($result);
        } else {
            $result = new \stdClass();
            $result->success = false;
            $result->msg = 'Ocurrió un error.';
            echo json_encode($result);
        }
    }

 
    protected function findEvalPreg($id_p)
    {
        $primaryConnection = \Yii::$app->db;
        $command = $primaryConnection->createCommand("
           SELECT 
                dat_evaluacion_pregunta.id_evaluacion, 
                dat_evaluacion_pregunta.id_pregunta
            FROM 
                m_evaluaciones.dat_evaluacion_pregunta 
            WHERE 
                (dat_evaluacion_pregunta.id_pregunta = ".$id_p.");
         ");

        return $resultados = $command->queryAll();
    }

    public function findNombreGrupo($id_grupo)
    {
        if(($model = DatGrupo::findOne($id_grupo)) !== null);
        {
            return $model->nombre;
        }
    }

}