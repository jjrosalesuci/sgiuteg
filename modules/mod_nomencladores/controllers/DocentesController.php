<?php

namespace app\modules\mod_nomencladores\controllers;

use Yii;
use app\modules\mod_nomencladores\models\datDocentes;
use app\modules\mod_nomencladores\models\datDocentesMaterias;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class DocentesController extends \yii\web\Controller
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

    public function actionCargardocentes()
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

           $count = datDocentes::find()
                         ->where('nombre LIKE :query or nombre LIKE :query or apellido LIKE :query or cedula LIKE :query')
                         ->andWhere(['estatus' => 'A'])
                         ->addParams([':query'=>'%'.$filter.'%'])
                         ->count();
           $query = datDocentes::find();
           $data = $query->offset($offset)
                         ->limit($limit)
                         ->orderBy('id')
                         ->where('nombre LIKE :query or nombre LIKE :query or apellido LIKE :query or cedula LIKE :query')
                         ->andWhere(['estatus' => 'A'])
                         ->addParams([':query'=>'%'.$filter.'%'])
                         ->asArray()->all();

          $datos_finales = array();
          foreach ($data as $key => $value) {
           
           $result                       = new \stdClass();
           $result->id                   =  $value['id'];
           $result->nombre               =  utf8_encode($value['nombre']);
           $result->segundo_nombre       =  utf8_encode($value['segundo_nombre']);
           $result->apellido             =  utf8_encode($value['apellido']);
           $result->apellido_materno     =  utf8_encode($value['apellido_materno']);
           $result->cedula               =  utf8_encode($value['cedula']);
           $result->ruc                  =  utf8_encode($value['ruc']);
           $result->direccion_domicilio  =  utf8_encode($value['direccion_domicilio']);

           $result->telefono_domicilio   =  utf8_encode($value['telefono_domicilio']);
           $result->direccion_trabajo    =  utf8_encode($value['direccion_trabajo']);
           $result->telefono_trabajo     =  utf8_encode($value['telefono_trabajo']);
           $result->telefono_celular     =  utf8_encode($value['telefono_celular']);
           $result->email                =  utf8_encode($value['email']);
           $result->titulo_tn            =  utf8_encode($value['titulo_tn']);
           $result->titulo_cn            =  utf8_encode($value['titulo_cn']);

           $result->universidad_titulo_cn  =  utf8_encode($value['universidad_titulo_cn']);
           $result->nivel_titulo_cn        =  utf8_encode($value['nivel_titulo_cn']);
           $result->pais_titulo_cn         =  utf8_encode($value['pais_titulo_cn']);
           

           
           $datos_finales[]= $result;
        }
        
          echo json_encode(array('count' => $count, 'data' => $datos_finales));
        }else {

          $count = datDocentes::find()->andwhere(['estatus' => 'A'])->count();
          $query = datDocentes::find();
          $data = $query->offset($offset)->limit($limit)->where(['estatus' => 'A'])->orderBy('id')->asArray()->all();


          $datos_finales = array();
          foreach ($data as $key => $value) {
           
           $result                       = new \stdClass();
           $result->id                   =  $value['id'];
           $result->nombre               =  utf8_encode($value['nombre']);
           $result->segundo_nombre       =  utf8_encode($value['segundo_nombre']);
           $result->apellido             =  utf8_encode($value['apellido']);
           $result->apellido_materno     =  utf8_encode($value['apellido_materno']);
           $result->cedula               =  utf8_encode($value['cedula']);
           $result->ruc                  =  utf8_encode($value['ruc']);
           $result->direccion_domicilio  =  utf8_encode($value['direccion_domicilio']);

           $result->telefono_domicilio   =  utf8_encode($value['telefono_domicilio']);
           $result->direccion_trabajo    =  utf8_encode($value['direccion_trabajo']);
           $result->telefono_trabajo     =  utf8_encode($value['telefono_trabajo']);
           $result->telefono_celular     =  utf8_encode($value['telefono_celular']);
           $result->email                =  utf8_encode($value['email']);
           $result->titulo_tn            =  utf8_encode($value['titulo_tn']);
           $result->titulo_cn            =  utf8_encode($value['titulo_cn']);

           $result->universidad_titulo_cn  =  utf8_encode($value['universidad_titulo_cn']);
           $result->nivel_titulo_cn        =  utf8_encode($value['nivel_titulo_cn']);
           $result->pais_titulo_cn         =  utf8_encode($value['pais_titulo_cn']);
           

           
           $datos_finales[]= $result;
        }
        
          echo json_encode(array('count' => $count, 'data' => $datos_finales));
        }
    }

    public function actionCargartodos(){
        $query = datDocentes::find();
        $data = $query->orderBy('id')->asArray()->all();
        echo json_encode(array('data' => $data));
    }

    public function actionUploadsearch(){        
        $request = Yii::$app->request;

        $callback = $request->get('callback');
        $filter    = $request->get('query');
        $start    = $request->get('start');
        $limit    = $request->get('limit');
        
        $count = datDocentes::find()->where(['LIKE', 'nombre',$filter])->andWhere(['estatus' => 'A'])->count();
        $query = datDocentes::find();
            
        $data = $query->select(['id','nombre','segundo_nombre','apellido','apellido_materno'])
                      ->offset($start)
                      ->limit($limit)
                      ->orderBy('id')
                      ->where(['LIKE', 'nombre',$filter])
                      ->andWhere(['estatus' => 'A'])
                      ->asArray()->all();

        $cadena = json_encode(array('data' => $data));

        $datos_finales = array();
        foreach ($data as $key => $value) {
           $result                    = new \stdClass();
           $result->id                =  $value['id'];
           $result->nombre            =  utf8_encode($value['nombre']);
           $result->segundo_nombre    =  utf8_encode($value['segundo_nombre']);
           $result->apellido          =  utf8_encode($value['apellido']);
           $result->apellido_materno  =  utf8_encode($value['apellido_materno']);
           $result->nombre_completo   =  $result->nombre.' '.$result->segundo_nombre.' '.$result->apellido.' '.$result->apellido_materno;
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
        $model = new datDocentes();
        $request = Yii::$app->request;
        $model->nombre = $request->post('nombre');
        if ($model->save()) {
            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Se creo correctamente el docente.';
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
            $result->msg = 'Se modificó correctamente el docente.';
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
        $result->msg = 'Se elimino correctamente el docente.';
        echo json_encode($result);        
    }


    public function actionGet_docentes($id_materia,$callback){
    
        $model = datDocentesMaterias::find()->where(['id_materia' => $id_materia,'estatus' => 'A'])->asArray()->all();
        $arreglo_id   =  array();
        $arreglo_id[] =  '-1';
        $arreglo = array();
        foreach ($model as $key => $value) {
                $bandera = array_search($value['id_docente'],$arreglo_id);
                if($bandera==false){
                         $nombre = $this->findModel($value['id_docente']);
                         if ($nombre!=null) {
                            $arreglo[] = array('id_docente'=>(integer)$value['id_docente'],'nombre_docente'=>$nombre->nombre.' '.$nombre->segundo_nombre.' '.$nombre->apellido.' '.$nombre->apellido_materno);
                            $arreglo_id[]=$value['id_docente'];
                         }
                       
                }
        }
      echo ($callback.'('.json_encode(array('data' => $arreglo)).')');
    }

    
    protected function findModel($id)
    {
        if (($model = datDocentes::findOne($id)) !== null) {
          if($model->estatus=='A')
          {
            return $model;
          }
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
