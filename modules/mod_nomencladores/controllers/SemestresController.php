<?php

namespace app\modules\mod_nomencladores\controllers;

use Yii;
use app\modules\mod_nomencladores\models\datSemestres;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class SemestresController extends \yii\web\Controller
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

    public function actionCargarsemestres()
    {
        //$this->enableCsrfValidation = false;
        $request = Yii::$app->request;
        $offset = $request->post('start');
        $limit = $request->post('limit');
        if ($offset == NULL) {
            $offset = 0;
        }
        if ($limit == NULL) {
            $limit = 12;
        }
        $count = datSemestres::find()->count();
        $query = datSemestres::find();
        $data = $query->offset($offset)->limit($limit)->orderBy('id')->asArray()->all();
        echo json_encode(array('count' => $count, 'data' => $data));
    }

    public function actionCargartodos(){
        $query = datSemestres::find();
        $data = $query->orderBy('id')->asArray()->all();
        echo json_encode(array('data' => $data));
    }

    public function actionUploadsearch(){        
        $request = Yii::$app->request;

        $callback = $request->get('callback');
        $filter    = $request->get('query');
        $start    = $request->get('start');
        $limit    = $request->get('limit');
        $date = '2014-09-01';
        
        $count = datSemestres::find()->where(['LIKE', 'nombre',$filter])
                                     ->andwhere(['>=','fecha_inicio', $date])
                                     ->andWhere(['estatus' => 'A'])
                                     ->count();

        $query = datSemestres::find();
            
        $data = $query->select(['id','nombre'])
                      ->offset($start)
                      ->limit($limit)
                      ->where(['LIKE', 'nombre',$filter]) 
                      ->andwhere(['>=','fecha_inicio', $date])
                      ->andWhere(['estatus' => 'A'])
                      ->orderBy('id')                     
                      ->asArray()->all();
        
        //$cadena = json_encode(array('data' => $data));

        $datos_finales = array();
        foreach ($data as $key => $value) {
           $result          = new \stdClass();
           $result->id      =  $value['id'];
           $result->nombre  =  utf8_encode($value['nombre']);
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
        $model = new datSemestres();
        $request = Yii::$app->request;
        $model->nombre = $request->post('nombre');
        if ($model->save()) {
            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Se creo correctamente el semestre.';
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
            $result->msg = 'Se modificó correctamente el semestre.';
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
        $result->msg = 'Se elimino correctamente el semestre.';
        echo json_encode($result);        
    }

    
    protected function findModel($id)
    {
        if (($model = datSemestres::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
