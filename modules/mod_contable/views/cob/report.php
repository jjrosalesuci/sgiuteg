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