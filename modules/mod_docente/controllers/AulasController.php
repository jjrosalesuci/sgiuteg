<?php

namespace app\modules\mod_docente\controllers;

use Yii;
use app\modules\mod_docente\models\datAulas;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class AulasController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCargar()
    {
        $request = Yii::$app->request;
        $offset = $request->post('start');
        $limit = $request->post('limit');
        $filter = $request->post('query');
        if ($offset == NULL) {
            $offset = 0;
        }
        if ($limit == NULL) {
            $limit = 14;
        }

        if($filter!=''){

           $count = datAulas::find()
                         ->where('nombre ILIKE :query or edificio ILIKE :query')
                         ->addParams([':query'=>'%'.$filter.'%'])
                         ->count();
           $query = datAulas::find();
           $data = $query->offset($offset)
                         ->limit($limit)
                         ->orderBy('id')
                         ->where('nombre ILIKE :query or edificio ILIKE :query')
                         ->addParams([':query'=>'%'.$filter.'%'])
                         ->asArray()->all();

        echo json_encode(array('count' => $count, 'data' => $data));

        }else{

            $count = datAulas::find()->count();
            $query = datAulas::find();
            $data = $query->offset($offset)->limit($limit)->orderBy('id')->asArray()->all();
        
            echo json_encode(array('count' => $count, 'data' => $data));
        }
    }

    public function actionUploadsearch(){        
        $request = Yii::$app->request;

        $callback = $request->get('callback');
        $filter    = $request->get('query');
        $start    = $request->get('start');
        $limit    = $request->get('limit');
        
        $count = datAulas::find()->where(['ILIKE', 'nombre',$filter])->count();
        $query = datAulas::find();
            
        $data = $query->select(['id','nombre'])
                      ->offset($start)
                      ->limit($limit)
                      ->orderBy('id')
                      ->where(['ILIKE', 'nombre',$filter])
                      ->asArray()->all();
      
        echo ($callback.'('.json_encode(array('count'=>$count,'data' => $data)).')');
    }

    public function actionCargartodos(){
        $query = datAulas::find();
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
        $model = new datAulas();
        $request = Yii::$app->request;

        $model->nombre          = $request->post('nombre');
        $model->edificio        = $request->post('edificio');

        if ($model->save()) {
            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Se creó correctamente.';
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
        
        $id                     = $request->post('id');
        $nombre                 = $request->post('nombre');
        $edificio               = $request->post('edificio');

        $model = $this->findModel($id);
        $model->nombre          = $nombre;
        $model->edificio        = $edificio;
        
        if ($model->save()) {
            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Se modificó correctamente.';
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
        $id         = $request->post('id');
        $this->findModel($id)->delete();  

        $result = new \stdClass();
        $result->success = true;
        $result->msg = 'Se eliminó correctamente.';
        echo json_encode($result);        
    }

    
    protected function findModel($id)
    {
        if (($model = datAulas::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
