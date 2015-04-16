<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;
use app\models\AclUser;

use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\models\SignupForm;

use yii\caching\FileCache;

use app\modules\mod_seguridad\models\DatMenu;

class SiteController extends Controller
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {

        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'index'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $user = \Yii::$app->user;
        $role = $user->identity->role;

        $cache = new FileCache();
        $key_cache = 'menuacl' . $role;

        $menu = $cache->get($key_cache);

        if ($menu === false) {

            $primaryConnection = \Yii::$app->db;
            $command = $primaryConnection->createCommand("      SELECT dat_menu.id, dat_menu.name,dat_menu.link,dat_menu.alto,dat_menu.ancho
                                                                FROM   m_arquitectura.dat_rol_acceso
                                                                INNER JOIN  m_arquitectura.dat_menu
                                                                ON (dat_menu.id = dat_rol_acceso.id_menu_item)
                                                                WHERE dat_rol_acceso.id_rol=$role
                                                                AND dat_menu.id_padre=1
                                                                ORDER BY id_padre;");
            $modulos = $command->queryAll();
            $query = DatMenu::find();
            $menu = array();
            foreach ($modulos as $key => $value) {
                $menu['mod_' . $value['id']]['name']  = $value['name'];
                $menu['mod_' . $value['id']]['link']  = $value['link'];
                $menu['mod_' . $value['id']]['alto']  = $value['alto'];
                $menu['mod_' . $value['id']]['ancho'] = $value['ancho'];
                $idp = $value['id'];
                $command = $primaryConnection->createCommand("  SELECT dat_menu.id, dat_menu.name,dat_menu.link,dat_menu.alto,dat_menu.ancho
                                                                FROM   m_arquitectura.dat_rol_acceso
                                                                INNER JOIN  m_arquitectura.dat_menu
                                                                ON (dat_menu.id = dat_rol_acceso.id_menu_item)
                                                                WHERE dat_rol_acceso.id_rol=$role
                                                                AND dat_menu.id_padre=$idp
                                                                ORDER BY dat_menu.id;");

                $funcionalidades = $modulos = $command->queryAll();
                foreach ($funcionalidades as $key => $feature) {
                    $menu['mod_' . $value['id']]['menu_items'][] = array(
                                                                         'name'  => $feature['name'],
                                                                         'link'  => $feature['link'],
                                                                         'alto'  => $feature['alto'],
                                                                         'ancho' => $feature['ancho']
                                                                         );
                }
            }
            $cache->set($key_cache, $menu);
        }


        return $this->render('index', ['modulos' => $menu]);
    }

    public function actionLogin()
    {
        return $this->render('login', []);
    }

    public function actionAuth()
    {
        $request = Yii::$app->request;

        $username = $request->post('username');
        $password = $request->post('password');

        $model = new LoginForm();
        $model->username = $username;
        $model->password = $password;

        if ($model->login()) {
            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Entrando al sistema, por favor espere....';
            echo json_encode($result);
        } else {
            // AUTENTICACION DE LA BASE DE DATOS ALTERNATIVA      
            $query = AclUser::find();
            $data = $query->where(['name'=>$username,'md5_password'=>md5($password)])->all();
            if(isset($data[0])){
                   //Buscar datos y registrar o actualizar
                    $id_user_acl = $data[0]["id"];
                    //antes de agregarlo verificar si el user existe en la base de datos para actualizar el password
                    $q2     = User::find();
                    $dataq2 = $q2->where(['username'=>$username])->all();                    
                    if(isset($dataq2[0]))
                    {
                      $model_user = User::findOne($dataq2[0]['id']);                        
                      $model_user->setPassword($password); 
                      $model_user->save();
                    }
                    else
                    {                        
                    // Si no existe continuo buscando los datos para registrar el usuario
                    //Buscar en los docentes
                     $secundaryConnection = \Yii::$app->db_siga;
                     $command = $secundaryConnection->createCommand("SELECT * FROM `sa_docente` WHERE `id_acl_user` =".$id_user_acl.";");
                     $docentes = $command->queryAll();
                     if(count($docentes)>0){
                        $user = new User();
                        $user->username    = $username;
                        $user->email       = $docentes[0]['email'];
                        $user->nombres     = $docentes[0]['nombre'].' '.$docentes[0]['segundo_nombre'];
                        $user->apellidos   = $docentes[0]['apellido'].' '.$docentes[0]['apellido_materno'];
                        $user->role        = 2;
                        $user->id_user_acl = $id_user_acl;
                        $user->cedula      = $docentes[0]['cedula'];
                        $user->setPassword($password);
                        $user->generateAuthKey();
                        $user->save();
                      }
                      //Buscar en los alumnos
                      $command = $secundaryConnection->createCommand("SELECT * FROM `sa_alumno` WHERE `id_acl_user` =".$id_user_acl.";");
                      $alumnos= $command->queryAll();
                      if(count($alumnos)>0){
                        $user = new User();
                        $user->username    = $username;
                        $user->email       = $alumnos[0]['email_uteg'];
                        $user->nombres     = $alumnos[0]['nombre'];
                        $user->apellidos   = $alumnos[0]['apellido'];
                        $user->role        = 4;
                        $user->id_user_acl = $id_user_acl;
                        $user->cedula      = $alumnos[0]['cedula'];
                        $user->setPassword($password);
                        $user->generateAuthKey();
                        $user->save();
                      }
                    }

                    sleep(1);
                    
                    $model_f = new LoginForm();
                    $model_f->username = $username;
                    $model_f->password = $password;

                    if ($model_f->login()) {
                        $result = new \stdClass();
                        $result->success = true;
                        $result->msg = 'Entrando al sistema, por favor espere....';
                        echo json_encode($result);
                    }
                    return;
            } 

            $result = new \stdClass();
            $result->success = false;
            $result->msg = 'Usuario y/o contraseña incorrecta.';
            echo json_encode($result);
        }
    }

    public function actionAuthkredirect($key,$modulekey)
    {
        $query = User::find();
        $data = $query->where(['auth_key'=>$key])->all();
        if(isset($data[0])){
            Yii::$app->user->login($data[0], 3600 * 24 * 30);
            return $this->redirect("index/?modulekey=$modulekey");
        } else {
            echo 'Acceso denegado';
        }
    }


    public function actionLogout()
    {
        Yii::$app->user->logout();
        $result = new \stdClass();
        $result->success = true;
        echo json_encode($result);
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
}