<?php

namespace app\modules\mod_contable\controllers;

use Yii;
use app\modules\mod_contable\models\NotifCobranza;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class NotifcobranzaController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

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

    public function actionCargarnotifcobranza()
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
        $count = NotifCobranza::find()->where(['modulo' => "Cobranza"])->count();
        $query = NotifCobranza::find()->where(['modulo' => "Cobranza"]);
        $data = $query->offset($offset)->limit($limit)->orderBy('id')->asArray()->all();
        echo json_encode(array('count' => $count, 'data' => $data));
    }

    public function actionCargartodos(){
        $query = NotifCobranza::find();
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
        $request = Yii::$app->request;
        $model = new NotifCobranza();
        $model->nombre 		= $request->post('nombre');
        $model->correo  	= $request->post('correo');
        $model->modulo 		= "Cobranza";
        if ($model->save()) {
            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Se creo correctamente la notificación.';
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
        $correo = $request->post('correo');

        $model = $this->findModel($id_asig);
        $model->nombre = $nombre;
        $model->correo = $correo;
        if ($model->save()) {
            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Se modificó correctamente la notificación.';
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
        $result->msg = 'Se elimino correctamente la notificación.';
        echo json_encode($result);        
    }

    
    protected function findModel($id)
    {
        if (($model = NotifCobranza::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
