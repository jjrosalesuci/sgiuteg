<?php

namespace app\modules\mod_nomencladores\controllers;

use Yii;
use app\modules\mod_nomencladores\models\datAlumnos;
use app\modules\mod_nomencladores\models\datAlumnosMaterias;
use app\modules\mod_nomencladores\models\datAsignatura;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class AlumnosController extends \yii\web\Controller
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

    public function actionCargaralumnos()
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
            $limit = 100;
        }

        if($filter!=''){

            $count = datAlumnos::find()
                         ->where('nombre LIKE :query or nombre LIKE :query or apellido LIKE :query or cedula LIKE :query')
                         ->addParams([':query'=>'%'.$filter.'%'])
                         ->count();
            $query = datAlumnos::find();
            $data = $query->offset($offset)
                         ->limit($limit)
                         ->orderBy('id')
                         ->where('nombre LIKE :query or nombre LIKE :query or apellido LIKE :query or cedula LIKE :query')
                         ->addParams([':query'=>'%'.$filter.'%'])
                         ->asArray()->all();
            $datos_finales = array();
        
            foreach ($data as $key => $value) {

                $result          = new \stdClass();
                $result->id                     =  $value['id'];
                $result->cedula                 =  utf8_encode($value['cedula']);
                $result->matricula              =  utf8_encode($value['matricula']);
                $result->nombre                 =  utf8_encode($value['nombre']);
                $result->apellido               =  utf8_encode($value['apellido']);
                $result->nacionalidad           =  utf8_encode($value['nacionalidad']);
                $result->genero                 =  utf8_encode($value['genero']);
                $result->organiz_cp             =  utf8_encode($value['organiz_cp']);
                $result->beneficio_beca_c       =  utf8_encode($value['beneficio_beca_c']);
                $result->beneficio_beca_p       =  utf8_encode($value['beneficio_beca_p']);
                $result->beneficio_ay_finan     =  utf8_encode($value['beneficio_ay_finan']);
                $result->beneficio_credito_ies  =  utf8_encode($value['beneficio_credito_ies']);
                $result->beneficio_credito_iece =  utf8_encode($value['beneficio_credito_iece']);
                $result->fecha_inicio_estudios  =  utf8_encode($value['fecha_inicio_estudios']);
                $result->apellido_materno       =  utf8_encode($value['apellido_materno']);
                $result->fecha_nacimiento       =  utf8_encode($value['fecha_nacimiento']);
                $result->categoria_definicion   =  utf8_encode($value['categoria_definicion']);
                $result->direccion_trabajo      =  utf8_encode($value['direccion_trabajo']);
                $result->telefono               =  utf8_encode($value['telefono']);
                $result->civil                  =  utf8_encode($value['civil']);
                $result->estatus                =  utf8_encode($value['estatus']);
                $result->fecha_ingreso          =  utf8_encode($value['fecha_ingreso']);
                $result->user                   =  utf8_encode($value['user']);
                $result->colegio                =  utf8_encode($value['colegio']);

                $datos_finales[]= $result;
            }

            echo json_encode(array('count' => $count, 'data' => $datos_finales));

        }
        else{
            $count = datAlumnos::find()->count();
            $query = datAlumnos::find();

            $data = $query->offset($offset)->limit($limit)->orderBy('id')->asArray()->all();

            $datos_finales = array();
        
            foreach ($data as $key => $value) {

                $result          = new \stdClass();
                $result->id                     =  $value['id'];
                $result->cedula                 =  utf8_encode($value['cedula']);
                $result->matricula              =  utf8_encode($value['matricula']);
                $result->nombre                 =  utf8_encode($value['nombre']);
                $result->apellido               =  utf8_encode($value['apellido']);
                $result->nacionalidad           =  utf8_encode($value['nacionalidad']);
                $result->genero                 =  utf8_encode($value['genero']);
                $result->organiz_cp             =  utf8_encode($value['organiz_cp']);
                $result->beneficio_beca_c       =  utf8_encode($value['beneficio_beca_c']);
                $result->beneficio_beca_p       =  utf8_encode($value['beneficio_beca_p']);
                $result->beneficio_ay_finan     =  utf8_encode($value['beneficio_ay_finan']);
                $result->beneficio_credito_ies  =  utf8_encode($value['beneficio_credito_ies']);
                $result->beneficio_credito_iece =  utf8_encode($value['beneficio_credito_iece']);
                $result->fecha_inicio_estudios  =  utf8_encode($value['fecha_inicio_estudios']);
                $result->apellido_materno       =  utf8_encode($value['apellido_materno']);
                $result->fecha_nacimiento       =  utf8_encode($value['fecha_nacimiento']);
                $result->categoria_definicion   =  utf8_encode($value['categoria_definicion']);
                $result->direccion_trabajo      =  utf8_encode($value['direccion_trabajo']);
                $result->telefono               =  utf8_encode($value['telefono']);
                $result->civil                  =  utf8_encode($value['civil']);
                $result->estatus                =  utf8_encode($value['estatus']);
                $result->fecha_ingreso          =  utf8_encode($value['fecha_ingreso']);
                $result->user                   =  utf8_encode($value['user']);
                $result->colegio                =  utf8_encode($value['colegio']);

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
        
        $count = datAlumnos::find()->where(['LIKE', 'nombre',$filter])->count();
        $query = datAlumnos::find();
            
        $data = $query->select(['id','nombre','apellido'])
                      ->offset($start)
                      ->limit($limit)
                      ->orderBy('id')
                      ->where(['LIKE', 'nombre',$filter])
                      ->asArray()->all();

        //$cadena = json_encode(array('data' => $data));

        $datos_finales = array();
        foreach ($data as $key => $value) {
           $result                   = new \stdClass();
           $result->id_alumno        =  $value['id'];
           $result->nombre_completo  =  utf8_encode($value['nombre']).' '.utf8_encode($value['apellido']);
           $datos_finales[]          = $result;
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
        $model = new datAlumnos();
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

    public function actionGetmaterias($id_alumno,$callback){
        $model = datAlumnosMaterias::find()->where(['id_alumno' => $id_alumno, 'estatus' => 'A'])->asArray()->all();
        $arreglo = array();
        foreach ($model as $key => $value) {
                $nombre = datAsignatura::findOne($value['id_materia']);
                $arreglo[] = array('id_materia'=>$value['id_materia'],'nombre'=>$nombre->nombre);

        }
        echo ($callback.'('.json_encode(array('data' => $arreglo)).')');
    }
    
    protected function findModel($id)
    {
        if (($model = datAlumnos::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
