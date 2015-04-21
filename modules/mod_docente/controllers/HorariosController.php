<?php

namespace app\modules\mod_docente\controllers;

use Yii;
use app\modules\mod_docente\models\datHorario;
use app\modules\mod_docente\models\datAulas;
use app\modules\mod_nomencladores\models\datDocentes;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class HorariosController extends \yii\web\Controller
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
    
    public function actionCargardia(){

        $arreglo[] = array(
            'dia' =>'LUNES',
        ); 
        $arreglo[] = array(
            'dia' =>'MARTES',
        ); 
        $arreglo[] = array(
            'dia' =>'MIÉRCOLES',
        );
        $arreglo[] = array(
            'dia' =>'JUEVES',
        );
        $arreglo[] = array(
            'dia' =>'VIERNES',
        );
        $arreglo[] = array(
            'dia' =>'SÁBADO',
        );
        $arreglo[] = array(
            'dia' =>'DOMINGO',
        );
        
        echo json_encode(array('data' => $arreglo));

    }
    public function actionModalidad(){

        $arreglo[] = array(
            'modalidad' =>'Presencial',
        ); 
        $arreglo[] = array(
            'modalidad' =>'Semipresencial',
        ); 
        
        
        echo json_encode(array('data' => $arreglo));

    }


    public function actionCargartodos(){
        $query = datHorario::find();
        $data = $query->orderBy('id')->asArray()->all();
        $arreglo = array();
            foreach ($data as $key => $value) {
                $value['nombre_aula'] = $this->getAula($value['id_aula'])->nombre;
                $value['edificio'] = $this->getAula($value['id_aula'])->edificio;
                $value['hora_inicio'] = date("h:i a", strtotime($value['hora_inicio']));
                $value['hora_fin'] = date("h:i a", strtotime($value['hora_fin']));
               $arreglo[]= $value;
            }
        echo json_encode(array('data' => $arreglo));
    }

    public function actionCargar()
    {
        $request = Yii::$app->request;
        $offset = $request->post('start');
        $limit = $request->post('limit');
        $filter = $request->post('query');
        $dia = $request->post('dia');
        $periodo = $request->post('periodo');

        if ($offset == NULL) {
            $offset = 0;
        }
        if ($limit == NULL) {
            $limit = 14;
        }

        if($filter!=''&&$dia!=''&&$periodo!=''){

            $count = datHorario::find()
                         ->where('nombre_materia ILIKE :query or nombre_docente ILIKE :query')
                         ->andWhere(['dia_semana' => $dia])
                         ->andWhere(['id_trimestre' => $periodo])
                         ->addParams([':query'=>'%'.$filter.'%'])
                         ->count();
            $query = datHorario::find();
            $data = $query->offset($offset)
                         ->limit($limit)
                         ->orderBy('hora_inicio')
                         ->where('nombre_materia ILIKE :query or nombre_docente ILIKE :query')
                         ->andWhere(['dia_semana' => $dia])
                         ->andWhere(['id_trimestre' => $periodo])
                         ->addParams([':query'=>'%'.$filter.'%'])
                         ->asArray()->all();
            $arreglo = array();
            foreach ($data as $key => $value) {
                $value['nombre_aula'] = $this->getAula($value['id_aula'])->nombre;
                $value['edificio'] = $this->getAula($value['id_aula'])->edificio;
                //$value['hora_inicio'] = date("h:i a", strtotime($value['hora_inicio']));
                //$value['hora_fin'] = date("h:i a", strtotime($value['hora_fin']));
               $arreglo[]= $value;
            }

        echo json_encode(array('count' => $count, 'data' => $arreglo));

        }else if($dia!=''&&$periodo!=''){

            $count = datHorario::find()
                          ->where(['dia_semana' => $dia])
                          ->andWhere(['id_trimestre' => $periodo])
                          ->count();
            $query = datHorario::find();
            $data = $query->offset($offset)
                          ->limit($limit)
                          ->where(['dia_semana' => $dia])
                          ->andWhere(['id_trimestre' => $periodo])
                          ->orderBy('hora_inicio')
                          ->asArray()->all();

            $arreglo = array();
            foreach ($data as $key => $value) {
                $value['nombre_aula'] = $this->getAula($value['id_aula'])->nombre;
                $value['edificio'] = $this->getAula($value['id_aula'])->edificio;
                //$value['hora_inicio'] = date("h:i a", strtotime($value['hora_inicio']));
                //$value['hora_fin'] = date("h:i a", strtotime($value['hora_fin']));
               $arreglo[]= $value;
            }
        
            echo json_encode(array('count' => $count, 'data' => $arreglo));
        }
    }
	/**
     * Creates a new Roles model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
	public function actionCreate() {
		$model = new datHorario();
        $request = Yii::$app->request;
        $repetir = $request->post('repetir');
        $lunes 		= $request->post('cb-auto-1');
        $martes 	= $request->post('cb-auto-2');
        $miercoles	= $request->post('cb-auto-3');
        $jueves		= $request->post('cb-auto-4');
        $viernes	= $request->post('cb-auto-5');
        $sabado 	= $request->post('cb-auto-6');
        $domingo	= $request->post('cb-auto-7');
        $acl_user   = $this->findAclUser($request->post('nombre_docente'));

        if($repetir!='on'){
	        $model->id_materia          = $request->post('nombre_materia');
	        $model->nombre_materia      = $request->post('ext-comp-1002');
	        $model->nombre_docente      = $request->post('ext-comp-1003');
	        $model->hora_inicio         = $request->post('hora_inicio');
	        $model->hora_fin       		= $request->post('hora_fin');
            $model->id_docente          = $request->post('nombre_docente');
	        $model->id_aula             = $request->post('nombre_aula');
	        $model->dia_semana          = $request->post('dia');
	        $model->id_trimestre        = $request->post('periodo');
            $model->modalidad           = $request->post('modalidad');
            $model->id_acl_user         = $acl_user;

	        if($request->post('periodo')!=''){
	            if ($model->save()) {
	                $result = new \stdClass();
	                $result->success = true;
	                $result->msg = 'Se creó correctamente';
	                echo json_encode($result);
	            } 
	        }
	        else {
	                $result = new \stdClass();
	                $result->success = false;
	                $result->msg = 'Ocurrió un error.';
	                echo json_encode($result);
	        }
    	}
    	else if($repetir=='on')
    	{
    		$error = false;
            if($lunes=='on'){
    			$model = new datHorario();
    			$model->id_materia          = $request->post('nombre_materia');
		        $model->nombre_materia      = $request->post('ext-comp-1002');
		        $model->nombre_docente      = $request->post('ext-comp-1003');
		        $model->hora_inicio         = $request->post('hora_inicio_lunes');
		        $model->hora_fin       		= $request->post('hora_fin_lunes');
		        $model->id_docente      	= $request->post('nombre_docente');
		        $model->id_aula             = $request->post('nombre_aula_lunes');
		        $model->dia_semana          = 'LUNES';
		        $model->id_trimestre        = $request->post('periodo');
                $model->id_acl_user         = $acl_user;

		        if($request->post('periodo')!=''){
                    $model->save();
                }else{
                    $error = true;
                }
    		}
    		if($martes=='on'){
    			$model = new datHorario();
    			$model->id_materia          = $request->post('nombre_materia');
		        $model->nombre_materia      = $request->post('ext-comp-1002');
		        $model->nombre_docente      = $request->post('ext-comp-1003');
		        $model->hora_inicio         = $request->post('hora_inicio_martes');
		        $model->hora_fin       		= $request->post('hora_fin_martes');
		        $model->id_docente      	= $request->post('nombre_docente');
		        $model->id_aula             = $request->post('nombre_aula_martes');
		        $model->dia_semana          = 'MARTES';
		        $model->id_trimestre        = $request->post('periodo');
                $model->id_acl_user         = $acl_user;

		        if($request->post('periodo')!=''){
                    $model->save();
                }else{
                    $error = true;
                }
    		}
    		if($miercoles=='on'){
    			$model = new datHorario();
    			$model->id_materia          = $request->post('nombre_materia');
		        $model->nombre_materia      = $request->post('ext-comp-1002');
		        $model->nombre_docente      = $request->post('ext-comp-1003');
		        $model->hora_inicio         = $request->post('hora_inicio_miercoles');
		        $model->hora_fin       		= $request->post('hora_fin_miercoles');
		        $model->id_docente      	= $request->post('nombre_docente');
		        $model->id_aula             = $request->post('nombre_aula_miercoles');
		        $model->dia_semana          = 'MIÉRCOLES';
		        $model->id_trimestre        = $request->post('periodo');
                $model->id_acl_user         = $acl_user;

		        if($request->post('periodo')!=''){
                    $model->save();
                }else{
                    $error = true;
                }
    		}
    		if($jueves=='on'){
    			$model = new datHorario();
    			$model->id_materia          = $request->post('nombre_materia');
		        $model->nombre_materia      = $request->post('ext-comp-1002');
		        $model->nombre_docente      = $request->post('ext-comp-1003');
		        $model->hora_inicio         = $request->post('hora_inicio_jueves');
		        $model->hora_fin       		= $request->post('hora_fin_jueves');
		        $model->id_docente      	= $request->post('nombre_docente');
		        $model->id_aula             = $request->post('nombre_aula_jueves');
		        $model->dia_semana          = 'JUEVES';
		        $model->id_trimestre        = $request->post('periodo');
                $model->id_acl_user         = $acl_user;

		        if($request->post('periodo')!=''){
                    $model->save();
                }else{
                    $error = true;
                }
    		}
    		if($viernes=='on'){
    			$model = new datHorario();
    			$model->id_materia          = $request->post('nombre_materia');
		        $model->nombre_materia      = $request->post('ext-comp-1002');
		        $model->nombre_docente      = $request->post('ext-comp-1003');
		        $model->hora_inicio         = $request->post('hora_inicio_viernes');
		        $model->hora_fin       		= $request->post('hora_fin_viernes');
		        $model->id_docente      	= $request->post('nombre_docente');
		        $model->id_aula             = $request->post('nombre_aula_viernes');
		        $model->dia_semana          = 'VIERNES';
		        $model->id_trimestre        = $request->post('periodo');
                $model->id_acl_user         = $acl_user;

		        if($request->post('periodo')!=''){
                    $model->save();
                }else{
                    $error = true;
                }
    		}
    		if($sabado=='on'){
    			$model = new datHorario();
    			$model->id_materia          = $request->post('nombre_materia');
		        $model->nombre_materia      = $request->post('ext-comp-1002');
		        $model->nombre_docente      = $request->post('ext-comp-1003');
		        $model->hora_inicio         = $request->post('hora_inicio_sabado');
		        $model->hora_fin       		= $request->post('hora_fin_sabado');
		        $model->id_docente      	= $request->post('nombre_docente');
		        $model->id_aula             = $request->post('nombre_aula_sabado');
		        $model->dia_semana          = 'SÁBADO';
		        $model->id_trimestre        = $request->post('periodo');
                $model->id_acl_user         = $acl_user;

		        if($request->post('periodo')!=''){
                    $model->save();
                }else{
                    $error = true;
                }
    		}
    		if($domingo=='on'){
    			$model = new datHorario();
    			$model->id_materia          = $request->post('nombre_materia');
		        $model->nombre_materia      = $request->post('ext-comp-1002');
		        $model->nombre_docente      = $request->post('ext-comp-1003');
		        $model->hora_inicio         = $request->post('hora_inicio_domingo');
		        $model->hora_fin       		= $request->post('hora_fin_domingo');
		        $model->id_docente      	= $request->post('nombre_docente');
		        $model->id_aula             = $request->post('nombre_aula_domingo');
		        $model->dia_semana          = 'DOMINGO';
		        $model->id_trimestre        = $request->post('periodo');
                $model->id_acl_user         = $acl_user;

		        if($request->post('periodo')!=''){
                    $model->save();
                }else{
                    $error = true;
                }
    		}
    		if($error==false){
                $result = new \stdClass();
                $result->success = true;
                $result->msg = 'Se creó correctamente';
                echo json_encode($result);    
            }
            if($error==true){
                $result = new \stdClass();
                $result->success = false;
                $result->msg = 'Ocurrió un error.';
                echo json_encode($result);
            }
            
    	}
	}


    public function actionUpdate()
    {
        $request = Yii::$app->request;
        $id = $request->post('id');
        
        $nombre_materia      = $request->post('ext-comp-1002');
        $nombre_docente      = $request->post('ext-comp-1003');
        $hora_inicio         = $request->post('hora_inicio');
        $hora_fin            = $request->post('hora_fin');

        if(is_numeric($request->post('nombre_materia')))
        {
            $id_materia              = $request->post('nombre_materia');
        }
        else if (!is_numeric($request->post('nombre_materia'))) {
            $id_materia              = $this->findModel($id)->id_materia;
        }
        if(is_numeric($request->post('nombre_docente')))
        {
            $id_docente              = $request->post('nombre_docente');
        }
        else if (!is_numeric($request->post('nombre_docente'))) {
            $id_docente              = $this->findModel($id)->id_docente;
        }
        if(is_numeric($request->post('nombre_aula')))
        {
            $id_aula                 = $request->post('nombre_aula');
        }
        else if (!is_numeric($request->post('nombre_aula'))) {
            $id_aula                = $this->findModel($id)->id_aula;
        }

        $model = $this->findModel($id);

        $model->id_materia          = $id_materia;
        $model->id_docente          = $id_docente;
        $model->id_aula             = $id_aula;
        $model->nombre_materia      = $nombre_materia;
        $model->nombre_docente      = $nombre_docente;
        $model->hora_inicio         = $hora_inicio;
        $model->hora_fin            = $hora_fin;
        $model->modalidad           = $request->post('modalidad');
        
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

    public function getAula($id){
        if (($model = datAulas::findOne($id)) !== null) {
            return $model;
        } else {
            return false;
        }
    }

    public function findAclUser($id){
        if (($model = datDocentes::findOne($id)) !== null) {
            return $model->id_acl_user;
        } else {
            return false;
        }
    }

    protected function findModel($id)
    {
        if (($model = datHorario::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
