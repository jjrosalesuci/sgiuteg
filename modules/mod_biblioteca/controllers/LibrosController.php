<?php

namespace app\modules\mod_biblioteca\controllers;

use Yii;
use app\modules\mod_biblioteca\models\datTitulos;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class LibrosController extends \yii\web\Controller
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

           $count = datTitulos::find()
                         ->where('titulo ILIKE :query or autor ILIKE :query or clasificacion ILIKE :query or isbn ILIKE :query')
                         ->addParams([':query'=>'%'.$filter.'%'])
                         ->count();
           $query = datTitulos::find();
           $data = $query->offset($offset)
                         ->limit($limit)
                         ->orderBy('ficha_no')
                         ->where('titulo ILIKE :query or autor ILIKE :query or clasificacion ILIKE :query or isbn ILIKE :query')
                         ->addParams([':query'=>'%'.$filter.'%'])
                         ->asArray()->all();

        echo json_encode(array('count' => $count, 'data' => $data));

        }else{

            $count = datTitulos::find()->count();
            $query = datTitulos::find();
            $data = $query->offset($offset)->limit($limit)->orderBy('ficha_no')->asArray()->all();
        
            echo json_encode(array('count' => $count, 'data' => $data));
        }
    }

    public function actionUploadsearch(){        
        $request = Yii::$app->request;

        $callback = $request->get('callback');
        $filter    = $request->get('query');
        $start    = $request->get('start');
        $limit    = $request->get('limit');
        
        $count = datTitulos::find()->where(['ILIKE', 'titulo',$filter])->count();
        $query = datTitulos::find();
            
        $data = $query->select(['ficha_no','titulo'])
                      ->offset($start)
                      ->limit($limit)
                      ->orderBy('ficha_no')
                      ->where(['ILIKE', 'titulo',$filter])
                      ->asArray()->all();
      
        echo ($callback.'('.json_encode(array('count'=>$count,'data' => $data)).')');
    }

    public function actionCargartodos(){
        $query = datTitulos::find();
        $data = $query->orderBy('ficha_no')->asArray()->all();
        echo json_encode(array('data' => $data));
    }


    /**
     * Creates a new Roles model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new datTitulos();
        $request = Yii::$app->request;

        $model->titulo          = $request->post('titulo');
        $model->autor           = $request->post('autor');
        $model->clasificacion   = $request->post('clasificacion');
        $model->isbn            = $request->post('isbn');
        $model->num_adqui       = $request->post('num_adqui');
        $model->biblioteca      = $request->post('biblioteca');
        $model->ejemplar        = $request->post('ejemplar');

        if ($model->save()) {
            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Se creó correctamente el libro.';
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
        
        $ficha_no        = $request->post('ficha_no');
        $titulo          = $request->post('titulo');
        $autor           = $request->post('autor');
        $clasificacion   = $request->post('clasificacion');
        $isbn            = $request->post('isbn');
        $num_adqui       = $request->post('num_adqui');
        $biblioteca      = $request->post('biblioteca');
        $ejemplar        = $request->post('ejemplar');

        $model = $this->findModel($ficha_no);
        $model->titulo          = $titulo;
        $model->autor           = $autor;
        $model->clasificacion   = $clasificacion;
        $model->isbn            = $isbn;
        $model->num_adqui       = $num_adqui;
        $model->biblioteca      = $biblioteca;
        $model->ejemplar        = $ejemplar;
        
        if ($model->save()) {
            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Se modificó correctamente el libro.';
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
        $id         = $request->post('ficha_no');
        $this->findModel($id)->delete();  

        $result = new \stdClass();
        $result->success = true;
        $result->msg = 'Se eliminó correctamente el libro.';
        echo json_encode($result);        
    }

    
    protected function findModel($id)
    {
        if (($model = datTitulos::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
