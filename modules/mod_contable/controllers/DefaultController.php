<?php

namespace app\modules\mod_contable\controllers;

use yii\web\Controller;
use Yii;

use app\modules\mod_contable\models\UploadForm;
use app\modules\mod_contable\models\ResumenSaldos;

use yii\web\UploadedFile;


class DefaultController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        //include(Yii::getAlias('@phpexcel'). 'PHPExcel.php');
        return $this->render('index');
    }

    public function actionUpload()
    {
        date_default_timezone_set('America/Havana');

        $time = time();
        $fecha = date('d-m-Y', $time);
        $hora = date('h:i:s a', $time);

        $uploaddir = 'uploads/mod_contable/';

        $pedasos = explode('.', $_FILES['file']['name']);
        $extencion = $pedasos[count($pedasos) - 1];

        $uploadfile = $uploaddir . $fecha . '_' . $hora . '.' . $extencion; //.$hora.$extencion;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
            //Leer el fichero y meter la data en la base de datos

            try {

                $inputFileType = \PHPExcel_IOFactory::identify($uploadfile);

                $objReader = \PHPExcel_IOFactory::createReader($inputFileType);

                $objReader->setReadDataOnly(true);

                $objPHPExcel = $objReader->load($uploadfile);

                $sheetData = $objPHPExcel->getSheet(0)->toArray(null, true, true, true);

                $array = [8,9,10,11,12,13,14,15,16,17];
                //D,E,F,G,H,I,J,K,M

                foreach ($array as $rowIndex) {
                    $model = new ResumenSaldos();
                    if ($rowIndex == 17) {
                        $model->nombre = '<b>TOTALES</b>';
                    } else {
                        $model->nombre = $sheetData[$rowIndex]['D'];
                    }
                    $model->disponible = str_replace(',', '.', $sheetData[$rowIndex]['E']);
                    $model->contable = str_replace(',', '.', $sheetData[$rowIndex]['F']);
                    $model->girados = str_replace(',', '.', $sheetData[$rowIndex]['G']);
                    $model->no_entregados = str_replace(',', '.', $sheetData[$rowIndex]['H']);
                    $model->diferido = str_replace(',', '.', $sheetData[$rowIndex]['I']);
                    $model->saldo = str_replace(',', '.', $sheetData[$rowIndex]['J']);
                    $model->tc = str_replace(',', '.', $sheetData[$rowIndex]['K']);
                    $model->sobre_giro_otorgado = str_replace(',', '.', $sheetData[$rowIndex]['M']);

                    if ($model->disponible == '') {
                        $model->disponible = '0.00';
                    }
                    if ($model->contable == '') {
                        $model->contable = '0.00';
                    }
                    if ($model->girados == '') {
                        $model->girados = '0.00';
                    }
                    if ($model->no_entregados == '') {
                        $model->no_entregados = '0.00';
                    }
                    if ($model->diferido == '') {
                        $model->diferido = '0.00';
                    }
                    if ($model->saldo == '') {
                        $model->saldo = '0.00';
                    }
                    if ($model->tc == '') {
                        $model->tc = '0.00';
                    }
                    if ($model->sobre_giro_otorgado == '') {
                        $model->sobre_giro_otorgado = '0.00';
                    }

                    $model->fecha = $fecha;
                    $model->hora = $hora;
                    $model->save(false);
                }

            } catch (\PHPExcel_Exception $exception) {
                $result = new \stdClass();
                $result->success = false;
                $result->msg = $exception->getMessage();
                echo json_encode($result);
                return;
            }

            $result = new \stdClass();
            $result->success = true;
            
            $this->actionNotificar();

            $result->msg = 'Se importo correctamente el fichero.';


            echo json_encode($result);
        } else {
            $result = new \stdClass();
            $result->success = false;
            $result->msg = 'Ocurrio un al subir el fichero.';
            echo json_encode($result);
        }
    }


    public function actionResumen()
    {
        return $this->render('resumen');
    }

    public function actionLoadresumen()
    {
        date_default_timezone_set('America/Havana');

        $time = time();

        $request = Yii::$app->request;
        $offset  = $request->post('start');
        $limit   = $request->post('limit');

        $fecha = $request->post('fecha');

        $query  = ResumenSaldos::find();
        $query_f  = ResumenSaldos::find();
            
        $contar_total  = ResumenSaldos::find()->count(); 
              
        if ($fecha == '') {
            $ultimos = $query_f->select(['id','fecha'])->offset($contar_total-1)
                ->limit(1)
                ->orderBy(['id' => SORT_ASC])
                ->asArray()
                ->all();

            if(count($ultimos)>0){
               $fecha = $ultimos[0]['fecha'];
            }            
            //$fecha = date('d-m-Y', $time);
        }

        if ($offset == NULL) {
            $offset = 0;
        }
        if ($limit == NULL) {
            $limit = 100;
        }

        $query1 = ResumenSaldos::find();
        $count  = ResumenSaldos::find()->where(['fecha' => $fecha])->count();

        
        $arreglo_de_diferencias = array();

        if($contar_total>=10){
            //buscar la diferencia de la ultima actualizacion con el anterioir
            //Buscar los saldos de  la importacion anterior
            $data_finales = $query1->select(['id','nombre','saldo'])->offset($contar_total-20)
                ->limit(20)
                ->orderBy(['id' => SORT_ASC])
                ->asArray()
                ->all();


           // var_dump($data_finales);die();

            if($data_finales[0]['saldo']<>$data_finales[10]['saldo']){
                $arreglo_de_diferencias[]=array(
                    'id'=>$data_finales[10]['id'],
                    'diferencia'=>$this->armarCadenaDiferencia($data_finales[10]['saldo']-$data_finales[0]['saldo'])
                );
            }
            if($data_finales[1]['saldo']<>$data_finales[11]['saldo']){
                $arreglo_de_diferencias[]=array(
                    'id'=>$data_finales[11]['id'],
                    'diferencia'=>$this->armarCadenaDiferencia($data_finales[11]['saldo']-$data_finales[1]['saldo'])
                );
            }
            if($data_finales[2]['saldo']<>$data_finales[12]['saldo']){
                $arreglo_de_diferencias[]=array(
                    'id'=>$data_finales[12]['id'],
                    'diferencia'=>$this->armarCadenaDiferencia($data_finales[12]['saldo']-$data_finales[2]['saldo'])
                );
            }
            if($data_finales[3]['saldo']<>$data_finales[13]['saldo']){
                $arreglo_de_diferencias[]=array(
                    'id'=>$data_finales[13]['id'],
                    'diferencia'=>$this->armarCadenaDiferencia($data_finales[13]['saldo']-$data_finales[3]['saldo'])
                );
            }
            if($data_finales[4]['saldo']<>$data_finales[14]['saldo']){
                $arreglo_de_diferencias[]=array(
                    'id'=>$data_finales[14]['id'],
                    'diferencia'=>$this->armarCadenaDiferencia($data_finales[14]['saldo']-$data_finales[4]['saldo'])
                );
            }
            if($data_finales[5]['saldo']<>$data_finales[15]['saldo']){
                $arreglo_de_diferencias[]=array(
                    'id'=>$data_finales[15]['id'],
                    'diferencia'=>$this->armarCadenaDiferencia($data_finales[15]['saldo']-$data_finales[5]['saldo'])
                );
            }
            if($data_finales[6]['saldo']<>$data_finales[16]['saldo']){
                $arreglo_de_diferencias[]=array(
                    'id'=>$data_finales[16]['id'],
                    'diferencia'=>$this->armarCadenaDiferencia($data_finales[16]['saldo']-$data_finales[6]['saldo'])
                );
            }
        }

        $data = $query->offset($offset)
            ->limit($limit)
            ->orderBy(['id' => SORT_ASC])
            ->where(['fecha' => $fecha])
            ->asArray()
            ->all();


        $array_final = array();
        foreach ($data as $index) {
            $partes = explode(':', $index['hora']);
            $time = mktime($partes[0], $partes[1], $partes[2]);
            $index['hora'] = date('h:i:s a', $time);

            foreach($arreglo_de_diferencias as $diferencia){
                if($index['id']==$diferencia['id']){
                    $index['diferencia']=$diferencia['diferencia'];
                }
            }
         

            if($index['nombre']=='<b>TOTALES</b>'){
                $index['disponible']             = '<b style="background:#eeeeee;">'.number_format($index['disponible'],2,",",".").'</b>';
                $index['contable']               = '<b style="background:#eeeeee;">'.number_format($index['contable'],2,",",".").'</b>';
                $index['girados']                = '<b style="background:#eeeeee;">'.number_format($index['girados'],2,",",".").'</b>';
                $index['no_entregados']          = '<b style="background:#eeeeee;">'.number_format($index['no_entregados'],2,",",".").'</b>';
                $index['diferido']               = '<b style="background:#eeeeee;">'.number_format($index['diferido'],2,",",".").'</b>';
                $index['saldo']                  = '<b style="background:#eeeeee;">'.number_format($index['saldo'],2,",",".").'</b>';               
                $index['tc']                     = '<b style="background:#eeeeee;">'.number_format($index['tc'],2,",",".").'</b>';
                $index['sobre_giro_otorgado']    = '<b style="background:#eeeeee;">'.number_format($index['sobre_giro_otorgado'],2,",",".").'</b>';               
            }else{
                $index['disponible']             = number_format($index['disponible'],2,",",".");
                $index['contable']               = number_format($index['contable'],2,",",".");
                $index['girados']                = number_format($index['girados'],2,",",".");
                $index['no_entregados']          = number_format($index['no_entregados'],2,",",".");
                $index['diferido']               = number_format($index['diferido'],2,",",".");
                $index['saldo']                  = number_format($index['saldo'],2,",",".");               
                $index['tc']                     = number_format($index['tc'],2,",",".");
                $index['sobre_giro_otorgado']    = number_format($index['sobre_giro_otorgado'],2,",",".");               
            }

            $array_final[] = $index;
        }

        echo json_encode(array('count' => $count, 'data' => $array_final));
    }


    public function armarCadenaDiferencia($saldo){
        if($saldo>0){
            return '<b style="color: #088B27;">'.number_format($saldo,2,",",".").'</b>';
        }else{
            return '<b style="color: #E20C0C;">'.number_format($saldo,2,",",".").'</b>';
        }
    }


    public function actionLoadhoras()
    {

        date_default_timezone_set('America/Havana');
        $request = Yii::$app->request;

        $fecha = $request->post('fecha');

        if ($fecha == '') {
            $time = time();
            $fecha = date('d-m-Y', $time);
        }


        $primaryConnection = \Yii::$app->db;
        $command = $primaryConnection->createCommand("select distinct hora from
                                                      m_contable.resumen_saldos
                                                      where fecha = '$fecha';");
        $data = $command->queryAll();


        $result = array();
        foreach ($data as $index) {
            $partes = explode(':', $index['hora']);
            $time = mktime($partes[0], $partes[1], $partes[2]);
            $index['hora_v'] = date('h:i:s a', $time);
            $result[] = $index;
        }
        echo json_encode(array('data' => $result));
    }




    public function actionCargarultimafecha(){
            $query_f       = ResumenSaldos::find();
            $contar_total  = ResumenSaldos::find()->count(); 
            $ultimos = $query_f->select(['id','fecha'])->offset($contar_total-1)
                ->limit(1)
                ->orderBy(['id' => SORT_ASC])
                ->asArray()
                ->all();

            if(count($ultimos)>0){
                  $fecha = $ultimos[0]['fecha'];
                  $result = new \stdClass();
                  $result->success = true;
                  $result->fecha = $fecha;
                  echo json_encode($result);
            }else{
                  $result = new \stdClass();
                  $result->success = false;
                  echo json_encode($result);
            }
    }


    public function actionDescarga($file)
    {
        $filename = 'uploads/mod_contable/' . $file;
        header("Expires: -1");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Content-type: application/vnd.ms-excel;\n"); //or yours?
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $len = filesize($filename);
        //header("Content-Length: $len;\n");
        $outname = $file;
        header("Content-Disposition: attachment; filename=" . $outname . ";\n\n");
        readfile($filename);
    }


    /*
    * Function que genera el pdf de los saldos y enviarlos por correo
    */
    public function actionNotificar()
    {
        date_default_timezone_set('America/Havana');

        $primaryConnection = \Yii::$app->db;
        $command = $primaryConnection->createCommand(" SELECT m_contable.dat_conf_notificaciones.correo
                                                       FROM   m_contable.dat_conf_notificaciones                                                     
                                                       WHERE m_contable.dat_conf_notificaciones.modulo='Saldos'");
        $correos = $command->queryAll();
        $destinatarios  = array();

        foreach ($correos as $key => $value) {
            $destinatarios[]= $value['correo'];
        }

        //Carga de la data
        $time = time();
        $fecha = date('d-m-Y', $time);
       
        $offset = 0;       
        $limit  = 100;        

        $query1 = ResumenSaldos::find();
        $query  = ResumenSaldos::find();
        $count  = ResumenSaldos::find()->where(['fecha' => $fecha])->count();

        $contar_total           = ResumenSaldos::find()->count();
        $arreglo_de_diferencias = array();

        if($contar_total>=10){
            //buscar la diferencia de la ultima actualizacion con el anterioir
            //Buscar los saldos de  la importacion anterior
            $data_finales = $query1->select(['id','nombre','saldo'])->offset($contar_total-20)
                ->limit(20)
                ->orderBy(['id' => SORT_ASC])
                ->asArray()
                ->all();


           // var_dump($data_finales);die();

            if($data_finales[0]['saldo']<>$data_finales[10]['saldo']){
                $arreglo_de_diferencias[]=array(
                    'id'=>$data_finales[10]['id'],
                    'diferencia'=>$this->armarCadenaDiferencia($data_finales[10]['saldo']-$data_finales[0]['saldo'])
                );
            }
            if($data_finales[1]['saldo']<>$data_finales[11]['saldo']){
                $arreglo_de_diferencias[]=array(
                    'id'=>$data_finales[11]['id'],
                    'diferencia'=>$this->armarCadenaDiferencia($data_finales[11]['saldo']-$data_finales[1]['saldo'])
                );
            }
            if($data_finales[2]['saldo']<>$data_finales[12]['saldo']){
                $arreglo_de_diferencias[]=array(
                    'id'=>$data_finales[12]['id'],
                    'diferencia'=>$this->armarCadenaDiferencia($data_finales[12]['saldo']-$data_finales[2]['saldo'])
                );
            }
            if($data_finales[3]['saldo']<>$data_finales[13]['saldo']){
                $arreglo_de_diferencias[]=array(
                    'id'=>$data_finales[13]['id'],
                    'diferencia'=>$this->armarCadenaDiferencia($data_finales[13]['saldo']-$data_finales[3]['saldo'])
                );
            }
            if($data_finales[4]['saldo']<>$data_finales[14]['saldo']){
                $arreglo_de_diferencias[]=array(
                    'id'=>$data_finales[14]['id'],
                    'diferencia'=>$this->armarCadenaDiferencia($data_finales[14]['saldo']-$data_finales[4]['saldo'])
                );
            }
            if($data_finales[5]['saldo']<>$data_finales[15]['saldo']){
                $arreglo_de_diferencias[]=array(
                    'id'=>$data_finales[15]['id'],
                    'diferencia'=>$this->armarCadenaDiferencia($data_finales[15]['saldo']-$data_finales[5]['saldo'])
                );
            }
            if($data_finales[6]['saldo']<>$data_finales[16]['saldo']){
                $arreglo_de_diferencias[]=array(
                    'id'=>$data_finales[16]['id'],
                    'diferencia'=>$this->armarCadenaDiferencia($data_finales[16]['saldo']-$data_finales[6]['saldo'])
                );
            }
        }

        $data = $query->offset($offset)
            ->limit($limit)
            ->orderBy(['id' => SORT_ASC])
            ->where(['fecha' => $fecha])
            ->asArray()
            ->all();


        $array_final = array();




        foreach ($data as $index) {
            $partes = explode(':', $index['hora']);
            $time = mktime($partes[0], $partes[1], $partes[2]);
            $index['hora'] = date('h:i:s a', $time);

            foreach($arreglo_de_diferencias as $diferencia){
                if($index['id']==$diferencia['id']){
                    $index['diferencia']=$diferencia['diferencia'];
                }
            }
         

            if($index['nombre']=='<b>TOTALES</b>'){
                $index['disponible']             = '<b style="background:#eeeeee;">'.number_format($index['disponible'],2,",",".").'</b>';
                $index['contable']               = '<b style="background:#eeeeee;">'.number_format($index['contable'],2,",",".").'</b>';
                $index['girados']                = '<b style="background:#eeeeee;">'.number_format($index['girados'],2,",",".").'</b>';
                $index['no_entregados']          = '<b style="background:#eeeeee;">'.number_format($index['no_entregados'],2,",",".").'</b>';
                $index['diferido']               = '<b style="background:#eeeeee;">'.number_format($index['diferido'],2,",",".").'</b>';
                $index['saldo']                  = '<b style="background:#eeeeee;">'.number_format($index['saldo'],2,",",".").'</b>';               
                $index['tc']                     = '<b style="background:#eeeeee;">'.number_format($index['tc'],2,",",".").'</b>';
                $index['sobre_giro_otorgado']    = '<b style="background:#eeeeee;">'.number_format($index['sobre_giro_otorgado'],2,",",".").'</b>';               
            }else{
                $index['disponible']             = number_format($index['disponible'],2,",",".");
                $index['contable']               = number_format($index['contable'],2,",",".");
                $index['girados']                = number_format($index['girados'],2,",",".");
                $index['no_entregados']          = number_format($index['no_entregados'],2,",",".");
                $index['diferido']               = number_format($index['diferido'],2,",",".");
                $index['saldo']                  = number_format($index['saldo'],2,",",".");               
                $index['tc']                     = number_format($index['tc'],2,",",".");
                $index['sobre_giro_otorgado']    = number_format($index['sobre_giro_otorgado'],2,",",".");               
            }

            $array_final[] = $index;
        }



       
        //0-10
        //10-19
        //Armar el pdf aca
        ob_start();
        ?>
                <style type="text/css">
                        #the-table { border:1px solid #bbb;border-collapse:collapse;margin: 10px ;width: 97%}
                        #the-table td,#the-table th { border:1px solid #ccc;border-collapse:collapse;padding:2px; }                     
                </style>
              
                <table cellspacing="0" id="the-table">
                        <thead>
                            <tr style="background:#eeeeee;">
                                <td colspan="2"><b>Resumen de los saldos <?php echo $fecha; ?></b></td>
                            </tr>
                            <tr style="background:#eeeeee;">
                                <th>Nombre</th>
                                <th>Saldos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php                 
                                   if(count($array_final)>10){
                                        $i = 10;
                                   }else{
                                        $i = 0;
                                   } 
                            ?>

                            <?php  for ($i; $i < count($array_final); $i++) { ?>                                 
                                  <tr>
                                       <td><?php echo $array_final[$i]['nombre']; ?> </td>                                     
                                       <td><?php echo $array_final[$i]['saldo']; ?> </td>                                     
                                  </tr>
                            <?php  }?>                           
                        </tbody>
                    </table>

        <?php
        $content = ob_get_clean(); 
        
        //echo $content ;die;

       // require_once(Yii::getAlias('@vendor'). '/html2pdf/html2pdf.class.php');        
        //try
        //{
         //   $html2pdf = new \HTML2PDF('L', 'A4', 'fr');
        //    $html2pdf->setDefaultFont('Arial');
        //    $html2pdf->writeHTML($content,true);
        //    $html2pdf->Output('uploads/mod_contable/'.$fecha.'resumen.pdf');

            Yii::$app->mailer->compose()
                    // ->attach('uploads/mod_contable/'.$fecha.'resumen.pdf')
                     ->setFrom(array(Yii::$app->params["adminEmail"] =>  Yii::$app->params["adminNameSistem"]))                 
                     //cargar de la base de datos
                     ->setTo($destinatarios)
                     ->setSubject('Actualizacion de los saldos '.$fecha)
                     ->setTextBody('Se ha actualizado el estado de los saldos')
                     ->setHtmlBody($content)
                     ->send();
      //  }
      //  catch(HTML2PDF_exception $e) {
      //      echo $e;
      //      exit;
      //  }
    }






}
