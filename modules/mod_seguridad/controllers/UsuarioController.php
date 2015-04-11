<?php

namespace app\modules\mod_seguridad\controllers;

use app\models\User;
/*Este es del otro framework */
use app\models\AclUser;
use Yii;


class UsuarioController extends \yii\web\Controller
{
	
	public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $user = new User();
        $user->username    = $request->post('username');
        $user->email       = $request->post('email');
        $user->nombres     = $request->post('nombres');
        $user->apellidos   = $request->post('apellidos');
        $user->role        = $request->post('role');
        $user->cedula      = $request->post('cedula');
        $user->sexo        = $request->post('sexo');
        $user->setPassword($request->post('password'));
        $user->generateAuthKey();
        if ($user->save()) {
            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Se creo correctamente el usuario.';
            echo json_encode($result);
        } else {
            $result = new \stdClass();
            $result->success = false;
            $result->msg = 'Ocurrio un error.';
            echo json_encode($result);
        }      
    }

    public function actionCargar_usuarios()
    {       
        $request = Yii::$app->request;        
        $offset  = $request->post('start');
        $limit   = $request->post('limit');        
        $filter   = $request->post('query');

        if ($offset == NULL) {
            $offset = 0;
        }
        if ($limit == NULL) {
            $limit = 12;
        }

        if($filter!=''){

           $count = User::find()
                         ->where('username LIKE :query or nombres LIKE :query or cedula LIKE :query or email LIKE :query')
                         ->addParams([':query'=>'%'.$filter.'%'])
                         ->count();
           $query = User::find();
           $data = $query->offset($offset)
                         ->limit($limit)
                         ->orderBy('id')
                         ->where('username LIKE :query or nombres LIKE :query or cedula LIKE :query or email LIKE :query')
                         ->addParams([':query'=>'%'.$filter.'%'])
                         ->asArray()->all();

           echo json_encode(array('count' => $count, 'data' => $data));

        }else{

           $count = User::find()->count();
           $query = User::find();
           $data = $query->offset($offset)->limit($limit)->orderBy('id')->asArray()->all();
           echo json_encode(array('count' => $count, 'data' => $data));

        }
    }

    public function actionUpdate()
    {
        $request 	= Yii::$app->request;
        $id 		       = $request->post('id');
        $username          = $request->post('username');
        $nombres           = $request->post('nombres');
        $apellidos         = $request->post('apellidos');
        $email             = $request->post('email');
        $role              = $request->post('role');
        $cedula            = $request->post('cedula');
        $sexo              = $request->post('sexo');

        $model = $this->findModel($id);
        $model->username  = $username;
        $model->email     = $email;
        $model->role      = $role;
        $model->nombres   = $nombres;
        $model->apellidos = $apellidos;
        $model->cedula    = $cedula;
        $model->sexo      = $sexo;


        if ($model->save()) {
            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Se modifico correctamente el usuario.';
            echo json_encode($result);
        } else {
            $result = new \stdClass();
            $result->success = false;
            $result->msg = 'Ocurrio un error.';
            echo json_encode($result);
        }
    }

    public function actionUpdatepassword(){

        $request 	   = Yii::$app->request;
        $id 		   = $request->post('id_user');
        $password      = $request->post('password');
        $username      = $request->post('username');
        $update_siga   = $request->post('update_siga');
       

        $model = $this->findModel($id);
        $model->setPassword($password); 

        /*
        * Actualizar password en el otro sistema pero esto hay que quitarlo para que no sea del nucleo de este
        */

        if($update_siga=='on'){
          $user_siga = AclUser::find()
                    ->where(['name' => $username])
                    ->one();
          $user_siga->md5_password = md5($password);
          $user_siga->save();
        }     

        /*
        * Fin de la parte que hay que quitar por la integracion
        */

        if ($model->save()) {
            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Se modifico correctamente el usuario.';
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
        $result->msg = 'Se elimino correctamente el usuario.';
        echo json_encode($result);      
    }


    public function actionCargarsexo()
    {
        
        $arreglo[] = array(
            'sexo' =>'M'
        );

        $arreglo[] = array(
            'sexo' =>'F'
        );        
        
        echo json_encode(array('data' => $arreglo));
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
