<?php
namespace app\modules\mod_seguridad\controllers;
use Yii;
use app\modules\mod_seguridad\models\DatMenu;
use yii\caching\FileCache;

class MenuController extends \yii\web\Controller
{
	
	public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionGetnodes(){
    	$request = Yii::$app->request;
        $node 	 = $request->post('node');
        
        $query = DatMenu::find();
        $data = $query->where(['id_padre'=>$node])->orderBy('id')->asArray()->all();
        $nodes = array();
        foreach ($data as $key => $value) {        	
              if($value['name']=='root'){
            		continue;
              }

              $count_hijos = DatMenu::find()->where(['id_padre' =>  $value['id']])->count();
        	  $item = array(
                'text'  => $value['name'],
                'link'  => $value['link'],
                'ancho' => $value['ancho'],
                'alto'  => $value['alto'],
                'link'  => $value['link'],
                'id'    => $value['id'],
                'qtip'  => $value['link'],
                //'qtip' => $qtip,
                //'qtipTitle' => $f,
                'cls'  => 'file'
              );
              
            if($count_hijos>0){
                $item['leaf'] = false;
            }else{
                if($value['id_padre']==1){
                   $item['leaf'] = false;
                }else{
                   $item['leaf'] = true;
                }
            }
            $nodes[] = $item;

        }
        echo json_encode($nodes);
    }


    public function actionAddnode(){
    	$request    = Yii::$app->request;
        $id_padre 	= $request->post('id_padre');
        $name 	    = $request->post('name');
        $link 	    = $request->post('link');
        $ancho      = $request->post('ancho');
        $alto       = $request->post('alto');

        $model = new DatMenu();
        $model->id_padre = $id_padre;
        $model->name     = $name;
        $model->ancho    = $ancho;
        $model->alto     = $alto;

        if($link!=''){
            $model->link     = $link;
        }
        
        if ($model->save()) {
             /* Limpiar la cache */
            $cache = new FileCache();      
            $cache->flush();

            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Se creo correctamente el elemento.';
            echo json_encode($result);
        } else {
            $result = new \stdClass();
            $result->success = false;
            $result->msg = 'Ocurrio un error.';
            echo json_encode($result);
        } 
    }

     public function actionUpdatenode(){

    	$request = Yii::$app->request;
        $id 	 = $request->post('id');
        $name 	 = $request->post('name');
        $link 	 = $request->post('link');   

        $ancho   = $request->post('ancho');
        $alto    = $request->post('alto');
     
        $model   = $this->findModel($id);
        $model->name = $name;

        $model->ancho  = $ancho;
        $model->alto   = $alto;

        if($link!=''){
            $model->link     = $link;
        }else{
            $model->link     = null;
        }     

        if ($model->save()) {
            /* Limpiar la cache */
            $cache = new FileCache();      
            $cache->flush();

            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Se actualizo correctamente el elemento.';
            echo json_encode($result);
        } else {
            $result = new \stdClass();
            $result->success = false;
            $result->msg = 'Ocurrio un error.';
            echo json_encode($result);
        }
      
    }


    public function actionCambiarpadre(){
        $request = Yii::$app->request;
        $id          = $request->post('id');
        $id_padre    = $request->post('id_padre');

        $model= $this->findModel($id);
        $model->id_padre = $id_padre ;
      
        if ($model->save()) {
        
            $cache = new FileCache();      
            $cache->flush();

            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Se actualizo correctamente el elemento.';
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
        $id         = $request->post('id');
        $this->findModel($id)->delete();  

        $cache = new FileCache();      
        $cache->flush();

        $result = new \stdClass();
        $result->success = true;
        $result->msg = 'Se elimino correctamente el elemento.';
        echo json_encode($result);        
    }

    protected function findModel($id)
    {
        if (($model = DatMenu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}