<?php

namespace app\modules\mod_biblioteca\controllers;

use Yii;
use app\modules\mod_biblioteca\models\datPrestamos;
use app\modules\mod_nomencladores\models\datAlumnos;
use app\modules\mod_nomencladores\models\datCarrera;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class PrestamosController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCargar()
    {
    	$request        = Yii::$app->request;
        $offset         = $request->post('start');
        $limit          = $request->post('limit');
        $filter         = $request->post('query');
        $fuerafecha     = $request->post('filtro');  

        $time = time();
        $fecha = date('d-m-Y', $time);      

        if ($offset == NULL) {
            $offset = 0;
        }
        if ($limit == NULL) {
            $limit = 14;
        }

        if($filter == '' && $fuerafecha == 'si'){
            $count = datPrestamos::find()
                         ->where(['estado' => 'Prestado'])
                         ->andWhere(['<','fecha_d', $fecha])
                         ->count();
           $query = datPrestamos::find();
           $data = $query->offset($offset)
                         ->limit($limit)
                         ->orderBy('id')
                         ->where(['estado' => 'Prestado'])
                         ->andWhere(['<','fecha_d', $fecha])
                         ->asArray()->all();

            echo json_encode(array('count' => $count, 'data' => $data));

        }
        else if($filter!='' && $fuerafecha == 'si'){

           $count = datPrestamos::find()
                         ->where('estado ILIKE :query or nombre_alumno ILIKE :query or apellido_alumno ILIKE :query or nombre_docente ILIKE :query or titulo_libro ILIKE :query')
                         ->andwhere(['estado' => 'Prestado'])
                         ->andWhere(['<','fecha_d', $fecha])
                         ->addParams([':query'=>'%'.$filter.'%'])
                         ->count();
           $query = datPrestamos::find();
           $data = $query->offset($offset)
                         ->limit($limit)
                         ->orderBy('id')
                         ->where('estado ILIKE :query or nombre_alumno ILIKE :query or apellido_alumno ILIKE :query or nombre_docente ILIKE :query or titulo_libro ILIKE :query')
                         ->andwhere(['estado' => 'Prestado'])
                         ->andWhere(['<','fecha_d', $fecha])
                         ->addParams([':query'=>'%'.$filter.'%'])
                         ->asArray()->all();

        echo json_encode(array('count' => $count, 'data' => $data));

        }
        else if($filter!='' && $fuerafecha == ''){

            $count = datPrestamos::find()
                         ->where('estado ILIKE :query or nombre_alumno ILIKE :query or apellido_alumno ILIKE :query or nombre_docente ILIKE :query or titulo_libro ILIKE :query')
                         ->addParams([':query'=>'%'.$filter.'%'])
                         ->count();
            $query = datPrestamos::find();
            $data = $query->offset($offset)
                         ->limit($limit)
                         ->orderBy('id')
                         ->where('estado ILIKE :query or nombre_alumno ILIKE :query or apellido_alumno ILIKE :query or nombre_docente ILIKE :query or titulo_libro ILIKE :query')
                         ->addParams([':query'=>'%'.$filter.'%'])
                         ->asArray()->all();

            echo json_encode(array('count' => $count, 'data' => $data));
        }
        else{

            $count = datPrestamos::find()->count();
            $query = datPrestamos::find();
            $data = $query->offset($offset)->limit($limit)->orderBy('id')->asArray()->all();
        
            echo json_encode(array('count' => $count, 'data' => $data));
        }
    }

    public function actionCreate()
    {
        $model = new datPrestamos();
        $request = Yii::$app->request;
        $model->fecha_e 			= $request->post('fecha_e');
        $model->fecha_d 			= $request->post('fecha_d');
        $model->id_libro            = $request->post('id_libro');
        $model->titulo_libro 		= $request->post('ext-comp-1007');
        $model->id_materia_alumno 	= $request->post('id_materia');
        $model->nombre_materia 		= $request->post('ext-comp-1002');
        $model->id_docente 			= $request->post('id_docente');
        $model->nombre_docente 		= $request->post('ext-comp-1003');
        if($this->get_carrera($request->post('id_alumno'))!==null)
        {
            $model->id_carrera 			= $this->get_carrera($request->post('id_alumno'))->id;
            $model->nombre_carrera 		= $this->get_carrera($request->post('id_alumno'))->nombre;
        }   
        $model->id_alumno	 		= $request->post('id_alumno');
        $model->nombre_alumno		= $this->Getnombreapellido($request->post('id_alumno'))->nombre;
        $model->apellido_alumno 	= $this->Getnombreapellido($request->post('id_alumno'))->apellido;
        $model->estado              = 'Prestado';
        $model->email               = $this->Getnombreapellido($request->post('id_alumno'))->email;

        if ($model->save()) {
            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Se creó correctamente.';
            echo json_encode($result);
        } else {
            $result = new \stdClass();
            $result->success = false;
            $result->msg = 'Ocurrio un error.';
            echo json_encode($result);
        }
    }

    public function actionCargartodos(){
        $query = datPrestamos::find();
        $data = $query->orderBy('id')->asArray()->all();
        echo json_encode(array('data' => $data));
    }

    public function actionEntregado()
    {
        $request = Yii::$app->request;
        $id             = $request->post('id');
        $model          = $this->findModel($id);
        $model->estado  = "Entregado";
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

    public function actionPrestado()
    {
        $request = Yii::$app->request;
        $id             = $request->post('id');
        $model          = $this->findModel($id);
        $model->estado  = "Prestado";
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

    public function Getnombreapellido($id)
    {
    	if (($model = datAlumnos::findOne($id)) !== null) {
            return $model;
        } 
    }

    public function get_carrera($id_user_acl){
        $model = datAlumnos::findOne($id_user_acl);
        if(($carrera = datCarrera::findOne($model->id_carrera)) !== null){
            return $carrera;        
        }
    }

    public function actionNotificar(){

        $request = Yii::$app->request;
        $id      = $request->post('id');

        if($this->findModel($id)->estado == 'Prestado' && $this->findModel($id)->email !== '')
        {    
            $nombre  = $this->findModel($id)->nombre_alumno;
            $email   = $this->findModel($id)->email;
            $titulo_libro = $this->findModel($id)->titulo_libro;
            $cadena = $nombre.' usted ha sobrepasado el tiempo de prestamo del siguiente material: '.$titulo_libro.' , por favor preséntese a la biblioteca a hacer su devolución.';

            try{
                    $message = Yii::$app->mailer->compose();
                    $message->setFrom(array(Yii::$app->params["adminEmail"] =>  Yii::$app->params["adminNameSistem"]))
                            ->setTo($email)
                            ->setSubject('Entrega de material' )
                            ->setTextBody($cadena)                            
                            ->send();
            }
            catch (Swift_TransportException $e) {
                    Yii::$app->getSession()->setFlash('danger', $e->getMessage());
                    return $this->render('smtpedit', array('model' => $has_smtp));
                    return true;
            }
        }
        else{
            $result = new \stdClass();
            $result->success = false;
            $result->msg = 'Error.';
            echo json_encode($result);
        } 

    }

    public function actionNotificartodos(){

        $time = time();
        $fecha = date('d-m-Y', $time);

        $model = datPrestamos::find()->where(['estado' => 'Prestado'])->andWhere(['<','fecha_d', $fecha])->asArray()->all();

        if($model)
        {
            foreach ($model as $key => $value) {

                $email        = $value['email'];
                $titulo_libro = $value['titulo_libro'];
                $nombre       = $value['nombre_alumno'];
                $cadena = $nombre.' usted ha sobrepasado el tiempo de prestamo del siguiente material: '."'$titulo_libro'".' , por favor preséntese a la biblioteca a hacer su devolución.';
                if($email !=='')
                {
                    Yii::$app->mailer->compose()
                        ->setFrom(array(Yii::$app->params["adminEmail"] =>  Yii::$app->params["adminNameSistem"]))
                        ->setTo($email)
                        ->setSubject('Entrega de material' )
                        ->setTextBody($cadena)
                        ->send();
                }
            }
            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Satisfactorio.';
            echo json_encode($result);
        }
        else{
            $result = new \stdClass();
            $result->success = false;
            $result->msg = 'Nada que notificar.';
            echo json_encode($result);
        } 
    }

    protected function findModel($id)
    {
        if (($model = datPrestamos::findOne($id)) !== null) {
            return $model;
        } else {
            return false;
        }
    }

}
