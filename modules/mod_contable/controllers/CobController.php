<?php

namespace app\modules\mod_contable\controllers;

use yii\web\Controller;
use Yii;

//use app\modules\mod_contable\models\UploadForm;
use app\modules\mod_contable\models\Cobranzas;
use app\modules\mod_contable\models\CobCartGrado;
use app\modules\mod_contable\models\CobDesPSemanales;
use app\modules\mod_contable\models\CobReportCaja;
use app\modules\mod_contable\models\CobReporte;
use app\modules\mod_contable\models\CobReportRecupPostgrado;
use app\modules\mod_contable\models\CobReportTarjetasCredito;


use yii\web\UploadedFile;


class CobController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {       
        return $this->render('index');
    }

    public function actionCreatereport()
    {
        $request = Yii::$app->request;
        $model  = new CobReporte();
        $model->fecha      = date("Y-m-d" , strtotime($request->post('fecha_reporte')));

        if ($this->findModel($model->fecha)==false){
            $model->save();
            $result = new \stdClass();
            $result->success = true;
            $result->msg = 'Se creo correctamente.';
            echo json_encode($result);
        }
        else if($this->findModel($model->fecha)!=false) {
            $result = new \stdClass();
            $result->success = false;
            $result->msg = 'Ya fue creado.';
            echo json_encode($result);
        } 
    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $fp1 = $request->post('fp1');
        $fp2 = $request->post('fp2');
        $fp3 = $request->post('fp3');
        $fp4 = $request->post('fp4');
        $fp5 = $request->post('fp5');

        if($fp1=='true'){
            
            $model1 = new CobDesPSemanales();

            $fecha_r      = date("Y-m-d" , strtotime($request->post('fecha_reporte')));

            if($this->findCobDesPSemanales($this->findModel($fecha_r))==false)
            {
                $model1->t_p_p              = $request->post('t_p_p');
                $model1->t_p_sp             = $request->post('t_p_sp');
                $model1->t_postgrado        = $request->post('t_postgrado');
                $model1->t_o_ingres         = $request->post('t_o_ingres');
                $model1->total_general      = $request->post('total_general');   
                $model1->id_reporte         = $this->findModel($fecha_r);
                $model1->fecha_descripcion  = $request->post('fecha_descripcion');

                if ($model1->save()) {
                    $result = new \stdClass();
                    $result->success = true;
                    $result->msg = 'Se creo correctamente.';
                    echo json_encode($result);
                } else {
                    $result = new \stdClass();
                    $result->success = false;
                    $result->msg = 'Ocurrio un error.';
                    echo json_encode($result);
                }

            }else{
                $model1 = $this->findCobDesPSemanales($this->findModel($fecha_r));

                $model1->t_p_p              = $request->post('t_p_p');
                $model1->t_p_sp             = $request->post('t_p_sp');
                $model1->t_postgrado        = $request->post('t_postgrado');
                $model1->t_o_ingres         = $request->post('t_o_ingres');
                $model1->total_general      = $request->post('total_general');   
                $model1->id_reporte         = $this->findModel($fecha_r);
                $model1->fecha_descripcion  = $request->post('fecha_descripcion');

                if ($model1->save()) {
                    $result = new \stdClass();
                    $result->success = true;
                    $result->msg = 'Se creo correctamente.';
                    echo json_encode($result);
                } else {
                    $result = new \stdClass();
                    $result->success = false;
                    $result->msg = 'Ocurrio un error.';
                    echo json_encode($result);
                }
            }
        }
        if($fp2=='true'){
            $model1 = new CobReportTarjetasCredito();

            $fecha_r      = date("Y-m-d" , strtotime($request->post('fecha_reporte')));

            if($this->findCobReportTarjetasCredito($this->findModel($fecha_r))==false)
            {
                $model1->f_ap_dep1          = $request->post('f_ap_dep1');
                $model1->f_ap_dep2          = $request->post('f_ap_dep2');
                $model1->f_ap_dep3          = $request->post('f_ap_dep3');
                $model1->f_ap_dep4          = $request->post('f_ap_dep4');
                $model1->f_ap_dep5          = $request->post('f_ap_dep5');   
                $model1->f_ap_dep6          = $request->post('f_ap_dep6');
                $model1->f_ap_dep7          = $request->post('f_ap_dep7');
                $model1->v_ap_rec1          = $request->post('v_ap_rec1');
                $model1->v_ap_rec2          = $request->post('v_ap_rec2');
                $model1->v_ap_rec3          = $request->post('v_ap_rec3');
                $model1->v_ap_rec4          = $request->post('v_ap_rec4');   
                $model1->v_ap_rec5          = $request->post('v_ap_rec5');
                $model1->v_ap_rec6          = $request->post('v_ap_rec6');   
                $model1->v_ap_rec7          = $request->post('v_ap_rec7');
                $model1->total              = $request->post('total');
                $model1->id_reporte         = $this->findModel($fecha_r);
                

                if ($model1->save()) {
                    $result = new \stdClass();
                    $result->success = true;
                    $result->msg = 'Se creo correctamente.';
                    echo json_encode($result);
                } else {
                    $result = new \stdClass();
                    $result->success = false;
                    $result->msg = 'Ocurrio un error.';
                    echo json_encode($result);
                }

            }else{
                $model1 = $this->findCobReportTarjetasCredito($this->findModel($fecha_r));

                $model1->f_ap_dep1          = $request->post('f_ap_dep1');
                $model1->f_ap_dep2          = $request->post('f_ap_dep2');
                $model1->f_ap_dep3          = $request->post('f_ap_dep3');
                $model1->f_ap_dep4          = $request->post('f_ap_dep4');
                $model1->f_ap_dep5          = $request->post('f_ap_dep5');   
                $model1->f_ap_dep6          = $request->post('f_ap_dep6');
                $model1->f_ap_dep7          = $request->post('f_ap_dep7');
                $model1->v_ap_rec1          = $request->post('v_ap_rec1');
                $model1->v_ap_rec2          = $request->post('v_ap_rec2');
                $model1->v_ap_rec3          = $request->post('v_ap_rec3');
                $model1->v_ap_rec4          = $request->post('v_ap_rec4');   
                $model1->v_ap_rec5          = $request->post('v_ap_rec5');
                $model1->v_ap_rec6          = $request->post('v_ap_rec6');   
                $model1->v_ap_rec7          = $request->post('v_ap_rec7');
                $model1->total              = $request->post('total');
                $model1->id_reporte         = $this->findModel($fecha_r);

                if ($model1->save()) {
                    $result = new \stdClass();
                    $result->success = true;
                    $result->msg = 'Se creo correctamente.';
                    echo json_encode($result);
                } else {
                    $result = new \stdClass();
                    $result->success = false;
                    $result->msg = 'Ocurrio un error.';
                    echo json_encode($result);
                }
            }
        }
        if($fp3=='true'){
            $model1 = new CobCartGrado();

            $fecha_r      = date("Y-m-d" , strtotime($request->post('fecha_reporte')));

            if($this->findCobCartGrado($this->findModel($fecha_r))==false)
            {
                $model1->fecha              = $request->post('fecha_c_g');   
                $model1->total_p            = $request->post('total_p');
                $model1->total_sp           = $request->post('total_sp');   
                $model1->total_cartera      = $request->post('t_cartera');
                $model1->id_reporte         = $this->findModel($fecha_r);
                

                if ($model1->save()) {
                    $result = new \stdClass();
                    $result->success = true;
                    $result->msg = 'Se creo correctamente.';
                    echo json_encode($result);
                } else {
                    $result = new \stdClass();
                    $result->success = false;
                    $result->msg = 'Ocurrio un error.';
                    echo json_encode($result);
                }

            }else{
                $model1 = $this->findCobCartGrado($this->findModel($fecha_r));

                $model1->fecha              = $request->post('fecha_c_g');   
                $model1->total_p            = $request->post('total_p');
                $model1->total_sp           = $request->post('total_sp');   
                $model1->total_cartera      = $request->post('t_cartera');
                $model1->id_reporte         = $this->findModel($fecha_r);

                if ($model1->save()) {
                    $result = new \stdClass();
                    $result->success = true;
                    $result->msg = 'Se creo correctamente.';
                    echo json_encode($result);
                } else {
                    $result = new \stdClass();
                    $result->success = false;
                    $result->msg = 'Ocurrio un error.';
                    echo json_encode($result);
                }
            }
        }
        if($fp4=='true'){
            $model1 = new CobReportCaja();

            $fecha_r      = date("Y-m-d" , strtotime($request->post('fecha_reporte')));

            if($this->findCobReportCaja($this->findModel($fecha_r))==false)
            {
                $model1->fecha_r_c            = $request->post('fecha_r_c');   
                $model1->total_e_c            = $request->post('total_e_c');
                $model1->id_reporte           = $this->findModel($fecha_r);
                

                if ($model1->save()) {
                    $result = new \stdClass();
                    $result->success = true;
                    $result->msg = 'Se creo correctamente.';
                    echo json_encode($result);
                } else {
                    $result = new \stdClass();
                    $result->success = false;
                    $result->msg = 'Ocurrio un error.';
                    echo json_encode($result);
                }

            }else{
                $model1 = $this->findCobReportCaja($this->findModel($fecha_r));

                $model1->fecha_r_c            = $request->post('fecha_r_c');   
                $model1->total_e_c            = $request->post('total_e_c');
                $model1->id_reporte           = $this->findModel($fecha_r);

                if ($model1->save()) {
                    $result = new \stdClass();
                    $result->success = true;
                    $result->msg = 'Se creo correctamente.';
                    echo json_encode($result);
                } else {
                    $result = new \stdClass();
                    $result->success = false;
                    $result->msg = 'Ocurrio un error.';
                    echo json_encode($result);
                }
            }
        }
        if($fp5=='true'){
            $model1 = new CobReportRecupPostgrado();

            $fecha_r      = date("Y-m-d" , strtotime($request->post('fecha_reporte')));

            if($this->findCobReportRecupPostgrado($this->findModel($fecha_r))==false)
            {
                $model1->g_fds1          = $request->post('g_fds1');
                $model1->g_fds2          = $request->post('g_fds2');
                $model1->g_fds3          = $request->post('g_fds3');
                $model1->g_fds4          = $request->post('g_fds4');
                $model1->g_fds5          = $request->post('g_fds5');   
                $model1->g_fds6          = $request->post('g_fds6');
                $model1->g_fds7          = $request->post('g_fds7');
                $model1->c_fds1          = $request->post('c_fds1');
                $model1->c_fds2          = $request->post('c_fds2');
                $model1->c_fds3          = $request->post('c_fds3');
                $model1->c_fds4          = $request->post('c_fds4');   
                $model1->c_fds5          = $request->post('c_fds5');
                $model1->c_fds6          = $request->post('c_fds6');   
                $model1->c_fds7          = $request->post('c_fds7');
                $model1->c_e1          = $request->post('c_e1');
                $model1->c_e2          = $request->post('c_e2');
                $model1->c_e3          = $request->post('c_e3');
                $model1->c_e4          = $request->post('c_e4');   
                $model1->c_e5          = $request->post('c_e5');
                $model1->c_e6          = $request->post('c_e6');   
                $model1->c_e7          = $request->post('c_e7');
                $model1->c_r1          = $request->post('c_r1');
                $model1->c_r2          = $request->post('c_r2');
                $model1->c_r3          = $request->post('c_r3');
                $model1->c_r4          = $request->post('c_r4');   
                $model1->c_r5          = $request->post('c_r5');
                $model1->c_r6          = $request->post('c_r6');   
                $model1->c_r7          = $request->post('c_r7');
                $model1->total_c_fds              = $request->post('total_c_fds');
                $model1->total_c_e              = $request->post('total_c_e');
                $model1->total_c_r              = $request->post('total_c_r');
                $model1->recaudacion              = $request->post('recaudacion');
                $model1->fecha_c_rp         = $request->post('fecha_c_rp');
                $model1->id_reporte         = $this->findModel($fecha_r);
                

                if ($model1->save()) {
                    $result = new \stdClass();
                    $result->success = true;
                    $result->msg = 'Se creo correctamente.';
                    echo json_encode($result);
                    
                } else {
                    $result = new \stdClass();
                    $result->success = false;
                    $result->msg = 'Ocurrio un error.';
                    echo json_encode($result);
                }

            }else{
                $model1 = $this->findCobReportRecupPostgrado($this->findModel($fecha_r));

                $model1->g_fds1          = $request->post('g_fds1');
                $model1->g_fds2          = $request->post('g_fds2');
                $model1->g_fds3          = $request->post('g_fds3');
                $model1->g_fds4          = $request->post('g_fds4');
                $model1->g_fds5          = $request->post('g_fds5');   
                $model1->g_fds6          = $request->post('g_fds6');
                $model1->g_fds7          = $request->post('g_fds7');
                $model1->c_fds1          = $request->post('c_fds1');
                $model1->c_fds2          = $request->post('c_fds2');
                $model1->c_fds3          = $request->post('c_fds3');
                $model1->c_fds4          = $request->post('c_fds4');   
                $model1->c_fds5          = $request->post('c_fds5');
                $model1->c_fds6          = $request->post('c_fds6');   
                $model1->c_fds7          = $request->post('c_fds7');
                $model1->c_e1          = $request->post('c_e1');
                $model1->c_e2          = $request->post('c_e2');
                $model1->c_e3          = $request->post('c_e3');
                $model1->c_e4          = $request->post('c_e4');   
                $model1->c_e5          = $request->post('c_e5');
                $model1->c_e6          = $request->post('c_e6');   
                $model1->c_e7          = $request->post('c_e7');
                $model1->c_r1          = $request->post('c_r1');
                $model1->c_r2          = $request->post('c_r2');
                $model1->c_r3          = $request->post('c_r3');
                $model1->c_r4          = $request->post('c_r4');   
                $model1->c_r5          = $request->post('c_r5');
                $model1->c_r6          = $request->post('c_r6');   
                $model1->c_r7          = $request->post('c_r7');
                $model1->total_c_fds              = $request->post('total_c_fds');
                $model1->total_c_e              = $request->post('total_c_e');
                $model1->total_c_r              = $request->post('total_c_r');
                $model1->recaudacion              = $request->post('recaudacion');
                $model1->fecha_c_rp         = $request->post('fecha_c_rp');
                $model1->id_reporte         = $this->findModel($fecha_r);

                if ($model1->save()) {
                    $result = new \stdClass();
                    $result->success = true;
                    $result->msg = 'Se creo correctamente.';
                    echo json_encode($result);
                    
                } else {
                    $result = new \stdClass();
                    $result->success = false;
                    $result->msg = 'Ocurrio un error.';
                    echo json_encode($result);
                }
            }
            
        }

    }

    public function actionResumen(){
         return $this->render('resumen');       
    }

    public function actionReporte($fecha){
        
        $reporte  = CobReporte::find();
        $contar_total  = CobReporte::find()->count();

        if ($fecha == 'null') {
            $ultimos = $reporte->select(['id','fecha'])->offset($contar_total-1)
                ->limit(1)
                ->orderBy(['id' => SORT_ASC])
                ->asArray()
                ->all();

            if(count($ultimos)>0){
               $fecha = $ultimos[0]['fecha'];
            }            
         }

        if ($fecha == 'null') {
            die('No se han importado datos');
        }
        if ($this->findModel($fecha)==false)
        {
            die('No se han importado datos');   
        }

        $reporte  = CobReporte::find()->where(['fecha' => $fecha]) 
                                        ->orderBy(['id' => SORT_ASC])
                                        ->asArray()
                                        ->all();

        $cobcartgrado  = CobCartGrado::find()->where(['id_reporte' => $reporte[0]['id']])
                                        ->orderBy(['id' => SORT_ASC])
                                        ->asArray()
                                        ->all();

        $cobdespsemanales  = CobDesPSemanales::find()->where(['id_reporte' => $reporte[0]['id']])
                                        ->orderBy(['id' => SORT_ASC])
                                        ->asArray()
                                        ->all();    
        $cobreportrecuppostgrado  = CobReportRecupPostgrado::find()->where(['id_reporte' => $reporte[0]['id']])
                                        ->orderBy(['id' => SORT_ASC])
                                        ->asArray()
                                        ->all();
        $cobreporttarjetascredito  = CobReportTarjetasCredito::find()->where(['id_reporte' => $reporte[0]['id']])
                                        ->orderBy(['id' => SORT_ASC])
                                        ->asArray()
                                        ->all();
        $cobreportcaja  = CobReportCaja::find()->where(['id_reporte' => $reporte[0]['id']])
                                        ->orderBy(['id' => SORT_ASC])
                                        ->asArray()
                                        ->all(); 

         return $this->render('report',[
                'reporte' => $reporte,
                'cobcartgrado' => $cobcartgrado,
                'cobdespsemanales' => $cobdespsemanales,
                'cobreportrecuppostgrado' => $cobreportrecuppostgrado,
                'cobreporttarjetascredito' => $cobreporttarjetascredito,
                'cobreportcaja' => $cobreportcaja,
                'fecha'=> $fecha
         ]);
    }

    /*
    * Function que genera el pdf de las conbranzas para enviar por correo.
    */
    public function actionNotificar()
    {
        $request = Yii::$app->request;
        $primaryConnection = \Yii::$app->db;
        $command = $primaryConnection->createCommand(" SELECT m_contable.dat_conf_notificaciones.correo
                                                       FROM   m_contable.dat_conf_notificaciones                                                     
                                                       WHERE m_contable.dat_conf_notificaciones.modulo='Cobranza'");
        $correos = $command->queryAll();
        $destinatarios  = array();

        foreach ($correos as $key => $value) {
            $destinatarios[]= $value['correo'];
        }

        $fecha    = date("Y-m-d" , strtotime($request->post('fecha')));

        $reporte  = CobReporte::find()->where(['fecha' => $fecha]) 
                                        ->orderBy(['id' => SORT_ASC])
                                        ->asArray()
                                        ->all();
        $cobcartgrado  = CobCartGrado::find()->where(['id_reporte' => $reporte[0]['id']])
                                        ->orderBy(['id' => SORT_ASC])
                                        ->asArray()
                                        ->all();

        $cobdespsemanales  = CobDesPSemanales::find()->where(['id_reporte' => $reporte[0]['id']])
                                        ->orderBy(['id' => SORT_ASC])
                                        ->asArray()
                                        ->all();    
        $cobreportrecuppostgrado  = CobReportRecupPostgrado::find()->where(['id_reporte' => $reporte[0]['id']])
                                        ->orderBy(['id' => SORT_ASC])
                                        ->asArray()
                                        ->all();
        $cobreporttarjetascredito  = CobReportTarjetasCredito::find()->where(['id_reporte' => $reporte[0]['id']])
                                        ->orderBy(['id' => SORT_ASC])
                                        ->asArray()
                                        ->all();
        $cobreportcaja  = CobReportCaja::find()->where(['id_reporte' => $reporte[0]['id']])
                                        ->orderBy(['id' => SORT_ASC])
                                        ->asArray()
                                        ->all();                                                                
        //Armar el pdf aca
        ob_start();
        ?>

                 <style type="text/css">
                        #the-table { border:1px solid #bbb;border-collapse:collapse;margin: 10px ;width: 97%}
                        #the-table td,#the-table th { border:1px solid #ccc;border-collapse:collapse;padding:2px; }

                        #the-table2 { border:1px solid #bbb;border-collapse:collapse;margin: 10px ;width: 97%}
                        #the-table2 td,#the-table2 th { border:1px solid #ccc;border-collapse:collapse;padding:2px; }

                        #the-table3 { border:1px solid #bbb;border-collapse:collapse;margin: 10px ;width: 97%}
                        #the-table3 td,#the-table3 th { border:1px solid #ccc;border-collapse:collapse;padding:2px; }

                        #the-table4 { border:1px solid #bbb;border-collapse:collapse;margin: 10px ;width: 97%}
                        #the-table4 td,#the-table4 th { border:1px solid #ccc;border-collapse:collapse;padding:2px; }

                        #the-table5 { border:1px solid #bbb;border-collapse:collapse;margin: 10px ;width: 97%}
                        #the-table5 td,#the-table5 th { border:1px solid #ccc;border-collapse:collapse;padding:2px; }
                </style>


                <?php                 
                     if(count($reporte)==0){
                        echo 'No hay datos importados para esta fecha.'; 
                        exit;
                     }
                     function formatear_numeros($saldo){
                        if($saldo!=''){
                            return number_format($saldo,2,",",".");       
                        }
                        else return '-';
                     }
                ?>

                <table cellspacing="0" id="the-table">
                        <thead>
                            <tr style="background:#eeeeee;">
                                <td colspan="2"><b>DESGLOSE DE PAGOS SEMANALES</b></td>
                            </tr>
                            <tr style="background:#eeeeee;">
                                <th>DETALLE</th>
                                <th><?php echo $cobdespsemanales[0]['fecha_descripcion']; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>TOTAL DE PREGRADO PRESENCIAL </td>
                                <td><?php echo formatear_numeros($cobdespsemanales[0]['t_p_p']); ?></td>              
                            </tr>
                            <tr>
                                <td>TOTAL DE PREGRADO SEMIPRESENCIAL </td>
                                <td><?php echo formatear_numeros($cobdespsemanales[0]['t_p_sp']); ?></td>               
                            </tr>
                            <tr>
                                <td>TOTAL DE POSTGRADO </td>
                                <td><?php echo formatear_numeros($cobdespsemanales[0]['t_postgrado']); ?></td>                
                            </tr>
                            <tr>
                                <td>TOTAL OTROS INGRESOS </td>
                                <td><?php echo formatear_numeros($cobdespsemanales[0]['t_o_ingres']); ?></td>               
                            </tr>
                            <tr style="background:gray;">
                                <td>TOTAL GENERAL</td>
                                <td><?php echo formatear_numeros($cobdespsemanales[0]['total_general']); ?></td>              
                            </tr>
                        </tbody>
                    </table>



                    <table cellspacing="0"  id="the-table2">
                            <thead>
                                <tr style="background:#eeeeee;">
                                    <td colspan="2"><b>REPORTE DE TARJETAS DE CREDITO</b></td>
                                </tr>
                                <tr style="background:#eeeeee;">
                                    <th>FECHA APROXIMADA DE DEPOSITO </th>
                                    <th>VALOR APROX. A RECIBIR </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo $cobreporttarjetascredito[0]['f_ap_dep1']; ?></td>
                                    <td><?php echo formatear_numeros($cobreporttarjetascredito[0]['v_ap_rec1']); ?></td>              
                                </tr>
                                <tr>
                                    <td><?php echo $cobreporttarjetascredito[0]['f_ap_dep2']; ?></td>
                                    <td><?php echo formatear_numeros($cobreporttarjetascredito[0]['v_ap_rec2']); ?></td>             
                                </tr>
                                <tr>
                                    <td><?php echo $cobreporttarjetascredito[0]['f_ap_dep3']; ?></td>
                                    <td><?php echo formatear_numeros($cobreporttarjetascredito[0]['v_ap_rec3']); ?></td>                 
                                </tr>
                                <tr>
                                    <td><?php echo $cobreporttarjetascredito[0]['f_ap_dep4']; ?></td>
                                    <td><?php echo formatear_numeros($cobreporttarjetascredito[0]['v_ap_rec4']); ?></td>              
                                </tr>
                                <tr>
                                    <td><?php echo $cobreporttarjetascredito[0]['f_ap_dep5']; ?></td>
                                    <td><?php echo formatear_numeros($cobreporttarjetascredito[0]['v_ap_rec5']); ?></td>             
                                </tr>
                                <tr>
                                    <td><?php echo $cobreporttarjetascredito[0]['f_ap_dep6']; ?></td>
                                    <td><?php echo formatear_numeros($cobreporttarjetascredito[0]['v_ap_rec6']); ?></td>                 
                                </tr>
                                <tr>
                                    <td><?php echo $cobreporttarjetascredito[0]['f_ap_dep7']; ?></td>
                                    <td><?php echo formatear_numeros($cobreporttarjetascredito[0]['v_ap_rec7']); ?></td>              
                                </tr>
                                <tr style="background:gray;">
                                    <td>TOTAL</td>
                                   <td><?php echo formatear_numeros($cobreporttarjetascredito[0]['total']); ?></td>          
                                </tr>
                            </tbody>
                    </table>

                    <table cellspacing="0"  id="the-table3">
                            <thead>
                                <tr style="background:#eeeeee;">
                                    <td colspan="3"><b>CARTERA DE GRADO</b></td>
                                </tr>
                                <tr style="background:#eeeeee;">
                                    <th></th>
                                    <th><?php echo $cobcartgrado[0]['fecha']; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>TOTAL PRESENCIAL </td>
                                    <td><?php echo formatear_numeros($cobcartgrado[0]['total_p']); ?></td>   
                                </tr>
                                <tr>
                                    <td>TOTAL SEMIPRESENCIAL</td>
                                    <td><?php echo formatear_numeros($cobcartgrado[0]['total_sp']); ?></td> 
                                </tr>           
                                <tr style="background:gray;">
                                    <td>TOTAL CARTERA </td>
                                    <td><?php echo formatear_numeros($cobcartgrado[0]['total_cartera']); ?></td>
                                </tr>
                            </tbody>
                    </table>


                    <table cellspacing="0"  id="the-table4">
                            <thead>
                                <tr style="background:#eeeeee;">
                                    <td colspan="3"><b>REPORTE DE CAJA </b></td>
                                </tr>
                                <tr style="background:#eeeeee;">
                                    <th></th>
                                    <th><?php echo $cobreportcaja[0]['fecha_r_c']; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="background:gray;">
                                    <td>TOTAL EN CAJA  </td>
                                    <td><?php echo formatear_numeros($cobreportcaja[0]['total_e_c']); ?></td>
                                </tr>
                            </tbody>
                    </table>

                    <table cellspacing="0"  id="the-table5">
                            <thead>
                                <tr style="background:#eeeeee;">
                                    <td colspan="4"><b><?php echo $cobreportrecuppostgrado[0]['fecha_c_rp']; ?></b></td>
                                </tr>
                                <tr style="background:#eeeeee;">
                                    <th>GRUPOS FDS</th>
                                    <th>CATERA FDS</th>
                                    <th>COMPROMISOS ESTIMADOS</th>
                                    <th>CARTERA RECAUDADA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo $cobreportrecuppostgrado[0]['g_fds1']; ?></td>
                                    <td><?php echo formatear_numeros($cobreportrecuppostgrado[0]['c_fds1']); ?></td>   
                                    <td><?php echo formatear_numeros($cobreportrecuppostgrado[0]['c_e1']); ?></td>
                                    <td><?php echo formatear_numeros($cobreportrecuppostgrado[0]['c_r1']); ?></td>           
                                </tr>
                                <tr>
                                    <td><?php echo $cobreportrecuppostgrado[0]['g_fds2']; ?></td>
                                    <td><?php echo formatear_numeros($cobreportrecuppostgrado[0]['c_fds2']); ?></td>   
                                    <td><?php echo formatear_numeros($cobreportrecuppostgrado[0]['c_e2']); ?></td>
                                    <td><?php echo formatear_numeros($cobreportrecuppostgrado[0]['c_r2']); ?></td>           
                                </tr>
                                <tr>
                                    <td><?php echo $cobreportrecuppostgrado[0]['g_fds3']; ?></td>
                                    <td><?php echo formatear_numeros($cobreportrecuppostgrado[0]['c_fds3']); ?></td>   
                                    <td><?php echo formatear_numeros($cobreportrecuppostgrado[0]['c_e3']); ?></td>
                                    <td><?php echo formatear_numeros($cobreportrecuppostgrado[0]['c_r3']); ?></td>           
                                </tr>
                                <tr>
                                    <td><?php echo $cobreportrecuppostgrado[0]['g_fds4']; ?></td>
                                    <td><?php echo formatear_numeros($cobreportrecuppostgrado[0]['c_fds4']); ?></td>   
                                    <td><?php echo formatear_numeros($cobreportrecuppostgrado[0]['c_e4']); ?></td>
                                    <td><?php echo formatear_numeros($cobreportrecuppostgrado[0]['c_r4']); ?></td>           
                                </tr>
                                <tr>
                                    <td><?php echo $cobreportrecuppostgrado[0]['g_fds5']; ?></td>
                                    <td><?php echo formatear_numeros($cobreportrecuppostgrado[0]['c_fds5']); ?></td>   
                                    <td><?php echo formatear_numeros($cobreportrecuppostgrado[0]['c_e5']); ?></td>
                                    <td><?php echo formatear_numeros($cobreportrecuppostgrado[0]['c_r5']); ?></td>           
                                </tr>
                                <tr>
                                    <td><?php echo $cobreportrecuppostgrado[0]['g_fds6']; ?></td>
                                    <td><?php echo formatear_numeros($cobreportrecuppostgrado[0]['c_fds6']); ?></td>   
                                    <td><?php echo formatear_numeros($cobreportrecuppostgrado[0]['c_e6']); ?></td>
                                    <td><?php echo formatear_numeros($cobreportrecuppostgrado[0]['c_r6']); ?></td>           
                                </tr>
                                <tr>
                                    <td><?php echo $cobreportrecuppostgrado[0]['g_fds7']; ?></td>
                                    <td><?php echo formatear_numeros($cobreportrecuppostgrado[0]['c_fds7']); ?></td>   
                                    <td><?php echo formatear_numeros($cobreportrecuppostgrado[0]['c_e7']); ?></td>
                                    <td><?php echo formatear_numeros($cobreportrecuppostgrado[0]['c_r7']); ?></td>           
                                </tr>
                                <tr style="background:gray;">
                                    <td>TOTAL CARTERA</td>
                                    <td><?php echo formatear_numeros($cobreportrecuppostgrado[0]['total_c_fds']); ?></td> 
                                    <td><?php echo formatear_numeros($cobreportrecuppostgrado[0]['total_c_e']); ?></td>
                                    <td><?php echo formatear_numeros($cobreportrecuppostgrado[0]['total_c_r']); ?></td>                      
                                </tr>           
                                <tr >
                                    <td>% RECAUDACION </td>
                                    <td><?php echo formatear_numeros($cobreportrecuppostgrado[0]['recaudacion']); ?></td>
                                    <td colspan="2"></td>               
                                </tr>
                            </tbody>
                    </table>

        <?php
        $content = ob_get_clean();         
        /*require_once(Yii::getAlias('@vendor'). '/html2pdf/html2pdf.class.php');        
        try
        {
            $html2pdf = new \HTML2PDF('P', 'A4', 'fr');
            $html2pdf->setDefaultFont('Arial');
            $html2pdf->writeHTML($content,false);
            $html2pdf->Output('uploads/mod_contable/cob/'.$fecha.'cob.pdf','F');*/

            Yii::$app->mailer->compose()
                     //->attach('uploads/mod_contable/cob/'.$fecha.'cob.pdf')
                     ->setFrom(array(Yii::$app->params["adminEmail"] =>  Yii::$app->params["adminNameSistem"]))
                     ->setTo($destinatarios)
                     ->setSubject('Actualizacion de cobranzas '.$fecha)
                     ->setTextBody($content)
                     ->setHtmlBody($content)
                     ->send();
        /*}
        catch(HTML2PDF_exception $e) {
            echo $e;
            exit;
        }*/
    }

    protected function findModel($fecha)
    {
        if (($model = CobReporte::find()->where(['fecha' => $fecha])->exists()) !== false) {
            $model = CobReporte::find()->where(['fecha' => $fecha])->one();
            return $model->id;
        } else {
            return false;
        }
    }

    public function findCobDesPSemanales($id_reporte)
    {
        if (($model = CobDesPSemanales::find()->where(['id_reporte' => $id_reporte])->exists()) !== false) {
            $model = CobDesPSemanales::find()->where(['id_reporte' => $id_reporte])->one();
            return $model;
        } else {
            return false;
        }
    }

    public function findCobReportTarjetasCredito($id_reporte)
    {
        if (($model = CobReportTarjetasCredito::find()->where(['id_reporte' => $id_reporte])->exists()) !== false) {
            $model = CobReportTarjetasCredito::find()->where(['id_reporte' => $id_reporte])->one();
            return $model;
        } else {
            return false;
        }
    }
    public function findCobCartGrado($id_reporte)
    {
        if (($model = CobCartGrado::find()->where(['id_reporte' => $id_reporte])->exists()) !== false) {
            $model = CobCartGrado::find()->where(['id_reporte' => $id_reporte])->one();
            return $model;
        } else {
            return false;
        }
    }
    public function findCobReportCaja($id_reporte)
    {
        if (($model = CobReportCaja::find()->where(['id_reporte' => $id_reporte])->exists()) !== false) {
            $model = CobReportCaja::find()->where(['id_reporte' => $id_reporte])->one();
            return $model;
        } else {
            return false;
        }
    }
    public function findCobReportRecupPostgrado($id_reporte)
    {
        if (($model = CobReportRecupPostgrado::find()->where(['id_reporte' => $id_reporte])->exists()) !== false) {
            $model = CobReportRecupPostgrado::find()->where(['id_reporte' => $id_reporte])->one();
            return $model;
        } else {
            return false;
        }
    }
}
