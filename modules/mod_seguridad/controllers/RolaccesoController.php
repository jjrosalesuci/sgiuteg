<?php

namespace app\modules\mod_seguridad\controllers;

use Yii;
use app\modules\mod_seguridad\models\Roles;
use app\modules\mod_seguridad\models\DatRolAcceso;
use app\modules\mod_seguridad\models\DatMenu;
use yii\caching\FileCache;


class RolaccesoController extends \yii\web\Controller
{
	public $enableCsrfValidation = false;
	
    public function actionIndex()
    {
        return $this->render('index');
    }

    /*
    * Agregar el permiso
    */

    public function actionAdd(){

        $model = new DatRolAcceso();
        $request = Yii::$app->request;
        $model->id_rol       = $request->post('id_rol');
        $model->id_menu_item = $request->post('id_menu_item');

        if ($model->save()) {
            $cache = new FileCache();
            $key_cache   = 'menuacl'.$model->id_rol;
            $cache->delete($key_cache);

            $result = new \stdClass();
            $result->success = true;
            echo json_encode($result);
        } else {
            $result = new \stdClass();
            $result->success = false;
            $result->msg = 'Ocurrio un error.';
            echo json_encode($result);
        }
    }

    /*
    *Quitar permiso
    */
    public function actionRemove(){
        $request = Yii::$app->request;
        $id_rol       = $request->post('id_rol');
        $id_menu_item = $request->post('id_menu_item');
        $model = DatRolAcceso::findOne(['id_rol' => $id_rol, 'id_menu_item' =>$id_menu_item])->delete();   

        $cache = new FileCache();
        $key_cache   = 'menuacl'.$id_rol;
        $cache->delete($key_cache);


 
        $result = new \stdClass();
        $result->success = true;
        echo json_encode($result);
    }

    public function actionGetnodes(){

        $request = Yii::$app->request;
        $node      = $request->post('node');
        $id_rol    = $request->post('id_rol');
        
        $query = DatMenu::find();
        $data = $query->where(['id_padre'=>$node])->orderBy('id')->asArray()->all();
        $nodes = array();
        foreach ($data as $key => $value) {         
            if($value['name']=='root'){
                continue;
            }
           
            $count = DatRolAcceso::find()->where(['id_rol' => $id_rol,'id_menu_item'=> $value['id']])->count();
            $count_hijos = DatMenu::find()->where(['id_padre' =>  $value['id']])->count();

            $item = array(
                'text' => $value['name'],
                'link' => $value['link'],
                'id'   => $value['id'],
                /*'qtip' => $value['link'],*/
                //'qtipTitle' => $f,
                'cls'  => 'file'
            );

            if($count>0){
                $item['checked'] = true;
            }else{
                $item['checked'] = false;
            }
            if($count_hijos>0){
                $item['leaf'] = false;
            }else{
                $item['leaf'] = true;
            }
            $nodes[] = $item;
        }
        echo json_encode($nodes);
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
