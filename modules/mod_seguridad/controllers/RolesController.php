<?php

namespace app\modules\mod_seguridad\controllers;

use Yii;
use app\modules\mod_seguridad\models\Roles;
use app\modules\mod_seguridad\models\RolesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RolesController implements the CRUD actions for Roles model.
 */
class RolesController extends Controller
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

    /**
     * Lists all Roles models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }


    public function actionCargarroles()
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
        $count = Roles::find()->count();
        $query = Roles::find();
        $data = $query->offset($offset)->limit($limit)->orderBy('id_rol')->asArray()->all();
        echo json_encode(array('count' => $count, 'data' => $data));
    }


    public function actionCargartodos(){
        $query = Roles::find();
        $data = $query->orderBy('id_rol')->asArray()->all();
        echo json_encode(array('data' => $data));
    }


    /**
     * Creates a new Roles model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Roles();
        $request = Yii::$app->request;
        $model->nombre = $request->post('nombre');
        if ($model->save()) {
            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Se creo correctamente el rol.';
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
        $id_rol = $request->post('id_rol');
        $nombre = $request->post('nombre');

        $model = $this->findModel($id_rol);
        $model->nombre = $nombre;
        if ($model->save()) {
            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Se modifico correctamente el rol.';
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
        $request    = Yii::$app->request;
        $id         = $request->post('id_rol');
        $this->findModel($id)->delete();  

        $result = new \stdClass();
        $result->success = true;
        $result->msg = 'Se elimino correctamente el rol.';
        echo json_encode($result);        
    }

    
    protected function findModel($id)
    {
        if (($model = Roles::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
