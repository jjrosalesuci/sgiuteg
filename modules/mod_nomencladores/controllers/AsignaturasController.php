<?php

namespace app\modules\mod_nomencladores\controllers;

use Yii;
use app\modules\mod_nomencladores\models\datAsignatura;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class AsignaturasController extends \yii\web\Controller
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

    public function actionCargarasignaturas()
    {
        //$this->enableCsrfValidation = false;
        $request = Yii::$app->request;
        $offset = $request->post('start');
        $limit = $request->post('limit');
        $filter = $request->post('query');
        if ($offset == NULL) {
            $offset = 0;
        }
        if ($limit == NULL) {
            $limit = 12;
        }

        if($filter!=''){

           $count = datAsignatura::find()
                         ->where('nombre LIKE :query or nombre LIKE :query or cod_legal LIKE :query or creditos LIKE :query')
                         ->andWhere(['estatus' => 'A'])
                         ->addParams([':query'=>'%'.$filter.'%'])
                         ->count();
           $query = datAsignatura::find();
           $data = $query->offset($offset)
                         ->limit($limit)
                         ->orderBy('id')
                         ->where('nombre LIKE :query or nombre LIKE :query or cod_legal LIKE :query or creditos LIKE :query')
                         ->andWhere(['estatus' => 'A'])
                         ->addParams([':query'=>'%'.$filter.'%'])
                         ->asArray()->all();

            $datos_finales = array();
            foreach ($data as $key => $value) {
           
            $result                  = new \stdClass();
            $result->id              =  $value['id'];
            $result->cod_legal       =  utf8_encode($value['cod_legal']);
            $result->nombre          =  utf8_encode($value['nombre']);
            $result->creditos        =  utf8_encode($value['creditos']);
            $result->horasXsemana    =  utf8_encode($value['horasXsemana']);
            $result->estatus         =  utf8_encode($value['estatus']);
            $result->fecha_cierre    =  utf8_encode($value['fecha_cierre']);
           
           $datos_finales[]= $result;
            }

            echo json_encode(array('count' => $count, 'data' => $datos_finales));

        }else{

            $count = datAsignatura::find()->where(['estatus' => 'A'])->count();
            $query = datAsignatura::find();
            $data = $query->offset($offset)->limit($limit)->where(['estatus' => 'A'])->orderBy('id')->asArray()->all();
        
            $datos_finales = array();
            foreach ($data as $key => $value) {
           
            $result                  = new \stdClass();
            $result->id              =  $value['id'];
            $result->cod_legal       =  utf8_encode($value['cod_legal']);
            $result->nombre          =  utf8_encode($value['nombre']);
            $result->creditos        =  utf8_encode($value['creditos']);
            $result->horasXsemana    =  utf8_encode($value['horasXsemana']);
            $result->estatus         =  utf8_encode($value['estatus']);
            $result->fecha_cierre    =  utf8_encode($value['fecha_cierre']);
           
           $datos_finales[]= $result;
            }

            echo json_encode(array('count' => $count, 'data' => $datos_finales));
        }

    }

    public function actionCargartodos(){
        $query = datAsignatura::find();
        $data = $query->orderBy('id')->asArray()->all();
        echo json_encode(array('data' => $data));
    }
    public function actionUploadsearch(){        
        $request = Yii::$app->request;

        $callback = $request->get('callback');
        $filter    = $request->get('query');
        $start    = $request->get('start');
        $limit    = $request->get('limit');
        
        $count = datAsignatura::find()->where(['LIKE', 'nombre',$filter])->andWhere(['estatus' => 'A'])->count();
        $query = datAsignatura::find();
            
        $data = $query->select(['id','nombre'])
                      ->offset($start)
                      ->limit($limit)
                      ->orderBy('id')
                      ->where(['LIKE', 'nombre',$filter])
                      ->andWhere(['estatus' => 'A'])
                      ->asArray()->all();

        $datos_finales = array();
        foreach ($data as $key => $value) {
           $result          = new \stdClass();
           $result->id_m      =  $value['id'];
           $result->nombre_m  =  utf8_encode($value['nombre']);
           $datos_finales[]= $result;
        }
       
        echo ($callback.'('.json_encode(array('count'=>$count,'data' => $datos_finales)).')');
    }


    /**
     * Creates a new Roles model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new datAsignatura();
        $request = Yii::$app->request;
        $model->nombre = $request->post('nombre');
        if ($model->save()) {
            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Se creo correctamente la asignatura.';
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
        $id_asig = $request->post('id');
        $nombre = $request->post('nombre');

        $model = $this->findModel($id_asig);
        $model->nombre = $nombre;
        if ($model->save()) {
            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Se modificó correctamente la asignatura.';
            echo json_encode($result);
        } else {
            $result = new \stdClass();
            $result->success = false;
            $result->msg = 'Ocurrio un error.';
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
        $result->msg = 'Se elimino correctamente la asignatura.';
        echo json_encode($result);        
    }

    
    protected function findModel($id)
    {
        if (($model = datAsignatura::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
