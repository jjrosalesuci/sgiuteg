<?php

namespace app\modules\mod_nomencladores\controllers;

use Yii;
use app\modules\mod_nomencladores\models\datCarrera;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class CarrerasController extends \yii\web\Controller
{
  
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

    public function actionCargarcarreras()
    {
        //$this->enableCsrfValidation = false;
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

           $count = datCarrera::find()
                         ->where('nombre LIKE :query or nombre LIKE :query or cod_legal LIKE :query or fecha_cierre LIKE :query')
                         ->addParams([':query'=>'%'.$filter.'%'])
                         ->count();
           $query = datCarrera::find();
           $data = $query->offset($offset)
                         ->limit($limit)
                         ->orderBy('id')
                         ->where('nombre LIKE :query or nombre LIKE :query or cod_legal LIKE :query or fecha_cierre LIKE :query')
                         ->addParams([':query'=>'%'.$filter.'%'])
                         ->asArray()->all();

           $datos_finales = array();
          foreach ($data as $key => $value) {
           
           $result                  = new \stdClass();
           $result->id              =  $value['id'];
           $result->cod_legal       =  utf8_encode($value['cod_legal']);
           $result->nombre          =  utf8_encode($value['nombre']);
           $result->descripcion     =  utf8_encode($value['descripcion']);
           $result->estatus         =  utf8_encode($value['estatus']);
           $result->fecha_cierre    =  utf8_encode($value['fecha_cierre']);
           $result->modalidad       =  utf8_encode($value['modalidad']);
           $result->tipo_modalidad  =  utf8_encode($value['tipo_modalidad']);
           
           $datos_finales[]= $result;
          }

          echo json_encode(array('count' => $count, 'data' => $datos_finales));

        }else{

          $count = datCarrera::find()->count();
          $query = datCarrera::find();
          $data = $query->offset($offset)->limit($limit)->orderBy('id')->asArray()->all();


          $datos_finales = array();
          foreach ($data as $key => $value) {
           
           $result                  = new \stdClass();
           $result->id              =  $value['id'];
           $result->cod_legal       =  utf8_encode($value['cod_legal']);
           $result->nombre          =  utf8_encode($value['nombre']);
           $result->descripcion     =  utf8_encode($value['descripcion']);
           $result->estatus         =  utf8_encode($value['estatus']);
           $result->fecha_cierre    =  utf8_encode($value['fecha_cierre']);
           $result->modalidad       =  utf8_encode($value['modalidad']);
           $result->tipo_modalidad  =  utf8_encode($value['tipo_modalidad']);
           
           $datos_finales[]= $result;
          }

          echo json_encode(array('count' => $count, 'data' => $datos_finales));
        }
    }


    public function actionUploadsearch(){        
        $request = Yii::$app->request;

        $callback = $request->get('callback');
        $filter    = $request->get('query');
        $start    = $request->get('start');
        $limit    = $request->get('limit');
        
        $count = datCarrera::find()->where(['LIKE', 'nombre',$filter])->count();
        $query = datCarrera::find();
            
        $data = $query->select(['id','nombre'])
                      ->offset($start)
                      ->limit($limit)
                      ->orderBy('id')
                      ->where(['LIKE', 'nombre',$filter])
                      ->asArray()->all();

        $cadena = json_encode(array('data' => $data));

        $datos_finales = array();
        foreach ($data as $key => $value) {
           $result          = new \stdClass();
           $result->id      =  $value['id'];
           $result->nombre  =  utf8_encode($value['nombre']);
           $datos_finales[]= $result;
        }
       
        echo ($callback.'('.json_encode(array('count'=>$count,'data' => $datos_finales)).')');
    }



    public function actionCargartodos(){
        $query = datCarrera::find();
        $data = $query->orderBy('id')->asArray()->all();
        echo json_encode(array('data' => $data));
    }


    /**
     * Creates a new Roles model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new datCarrera();
        $request = Yii::$app->request;
        $model->nombre = $request->post('nombre');
        if ($model->save()) {
            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Se creó correctamente la carrera.';
            echo json_encode($result);
        } else {
            $result = new \stdClass();
            $result->success = false;
            $result->msg = 'Ocurrió un error.';
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
        $id_asig = $request->post('id');
        $nombre = $request->post('nombre');

        $model = $this->findModel($id_asig);
        $model->nombre = $nombre;
        if ($model->save()) {
            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Se modificó correctamente la carrera.';
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
        $request 	= Yii::$app->request;
        $id 		= $request->post('id');
        $this->findModel($id)->delete();  

        $result = new \stdClass();
        $result->success = true;
        $result->msg = 'Se eliminó correctamente la carrera.';
        echo json_encode($result);        
    }

    
    protected function findModel($id)
    {
        if (($model = datCarrera::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
