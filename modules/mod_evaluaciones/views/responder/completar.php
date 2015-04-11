<style type="text/css">
        #the-table { border:1px solid #bbb;border-collapse:collapse;margin: 10px ;width: 97%}
        #the-table td,#the-table th { border:1px solid #ccc;border-collapse:collapse;padding:2px; }


        body {
  font-family:helvetica,tahoma,verdana,sans-serif;
  padding:20px;
    padding-top:32px;
    font-size:13px;
  background-color:#fff !important;
}
p {
  margin-bottom:15px;
}
h1 {
  font-size:large;
  margin-bottom:20px;
}
h2 {
  font-size:14px;
    color:#333;
    font-weight:bold;
    margin:10px 0;
}
.example-info{
  width:150px;
  border:1px solid #c3daf9;
  border-top:1px solid #DCEAFB;
  border-left:1px solid #DCEAFB;
  background:#ecf5fe url( info-bg.gif ) repeat-x;
  font-size:10px;
  padding:8px;
}
pre.code{
  background: #F8F8F8;
  border: 1px solid #e8e8e8;
  padding:10px;
  margin:10px;
  margin-left:0px;
  border-left:5px solid #e8e8e8;
  font-size: 12px !important;
  line-height:14px !important;
}
.msg .x-box-mc {
    font-size:14px;
}
#msg-div {
    position:absolute;
    left:35%;
    top:10px;
    width:250px;
    z-index:20000;
}
.x-grid3-row-body p {
    margin:5px 5px 10px 5px !important;
}
</style>


<script type="text/javascript">
    var id_user_acl       = <?php
    if($id_user_acl!=NULL){
       echo $id_user_acl;
    }else{
       echo -1;
    };?>;

    var id_docente       = <?php
    if($id_docente!=NULL){
       echo $id_docente;
    }else{
       echo -1;
    };?>;

    var id_user           = <?php echo $id_user;?>;
    var id_role           = <?php echo $rol;?>;
    var id_evaluacion     = <?php echo $id_evaluacion;?>;   

    function valida_envia(opcion){
       if(opcion==1){     
         document.fvalida.opcion_redireccion.value=1;   
       }
       if(opcion==2){      
         document.fvalida.opcion_redireccion.value=2;   
       }
        // 2 Docentes
       if(document.fvalida.materia[0].value==""){
          alert("Seleccione una materia.");
          return false;
       }

       if(id_role!=2){
        if(document.fvalida.docente[0].value==""){
          alert("Seleccione un docente.");
          return false;
        }      
       }
       document.fvalida.submit();
    }
</script>

<form name="fvalida" action="salvar" method="POST">
<table cellspacing="0" id="the-table">
        <thead>          
        	<tr style="">
                <td colspan="1">
                    <img src="../../../images/logo.png"/>                        
                </td>
                <td colspan="1">  
                    <b>UNIVERSIDAD TECNOLÓGICA EMPRESARIAL DE GUAYAQUIL                    
                </td>
            </tr>
             <tr style="background:#eeeeee;" >
                <th colspan="2"></th>
            </tr>

             <tr>
                <td><b>Modalidad : </b><?php echo $evaluacion['modalidad'] ?> </td>
                <td><b>Periodo  : </b> <?php echo $evaluacion['nombre_periodo'] ?></td>              
            </tr>
            
            <!--
               // 2 Docentes
               // 4 Alumnos
               // 12 Decanos    
            -->

            <tr>
                <td>
                    <?php 
                      if($rol==4){
                         echo  '<b>Carrera : </b>'.$carrera["nombre"];
                      }else{
                         echo  '<input type="text" id="carrera" name="carrera"/>';
                      }
                     ?>
                  </td>
                <td><b>Fecha : </b> <?php echo $evaluacion['fecha'] ?> </td>              
            </tr>
            <tr>
                 <td> <input type="text" id="materia" name="materia"/>  </td>
                <?php    
                  switch ($rol) {
                     case 4:
                        //Alumnos
                        echo '<td> <input type="text" id="docente" name="docente"/></td>';   
                     break;
                     case 2:
                       //Docentes
                        echo('<td><b>Docente : </b>'.$usuario->nombres.' '.$usuario->apellidos.'</td>'); 
                        echo '<td><input type="hidden" id="docente" name="docente" value="'.$id_docente.'"/> </td>';         
                     break;                     
                     default:
                      //Demas
                      echo '<td> <input type="text" id="docente" name="docente"/></td>';          
                      break;
                   }                   
                ?>                               
            </tr>

            <tr>
                <td><b>Titulo : </b> <?php echo $evaluacion['titulo'] ?></td>
                <td></td>              
            </tr>


            <?php 
          //  echo '<pre>';
           // var_dump($preguntas); die();?>
            <?php //var_dump($evaluacion->); die();?>
           
        </thead>

        <tbody>

            <tr style="background:#eeeeee;" >
                <th colspan="2"> <?php echo $evaluacion['descripcion'] ?>  </th>
            </tr>

            <tr style="background:#eeeeee;">
                <th>Aspectos a evaluar</th>
                <th>Seleccione su respuesta en esta columna</th>
            </tr>

            
            <input type="hidden" value="<?php echo $evaluacion['id'] ?>" name="id_evaluacion">
            <input type="hidden" value="1" name="opcion_redireccion">

            <?php foreach ($preguntas as $key => $value) { ?>
                <tr <?php   if($value['resaltar']==1){echo 'style="background-color:cadetblue"';}   ?>   >
                    <td><?php echo $value['texto'] ?></td>
                    <td>                        
                        <!-- Tipo cualitativo -->
                        <?php if($value['tipo'] == 1){ ?>
                          <textarea name="res_<?php echo $value['id_pregunta'] ?>" rows="4" cols="50"></textarea>                          
                        <?php } ?>
                        <!-- Tipo cuantitaivo -->
                         <?php if($value['tipo'] == 2){ ?>
                              <input  name="res_<?php echo $value['id_pregunta'] ?>" type="text" name="asd"/>                              
                        <?php } ?>
                        <!-- Tipo opciones -->
                        <?php if($value['tipo'] == 3){ ?>
                              <select name="res_<?php echo $value['id_pregunta'] ?>" class="form-control" id="catalogoprogramas-tipo_guion">
                                 <option value="-1">--Seleccione--</option>
                                 <?php 
                                        $optiones = explode(',', $value['opciones']);
                                        foreach ($optiones as $key_opcion => $opcion) {
                                            echo  '<option value="'.$opcion.'">'.$opcion.'</option>';
                                        }
                                 ?>
                                </select>
                        <?php } ?>
                    </td>
                <tr>
            <?php } ?>  

            <tr>
                <th colspan="2">

                    <?php   switch ($rol) {

   case 2:
       echo '   <input type="button" value="Finalizar" onclick="valida_envia(1)">';


   break;
   
   default:

    echo '   <input type="button" value="Finalizar" onclick="valida_envia(1)">
            <input type="button" value="Finalizar y continuar" onclick="valida_envia(2)">';


     break;
 }

  ?> 

               

                </th>
            </tr>

            </form> 
        </tbody>
</table>

<?php 

 // 2 Docentes
 // 4 Alumnos
 // 12 Decanos

 switch ($rol) {
   case 4:
     $this->registerJsFile('@web/js/modules/mod_evaluaciones/responder_preguntas_alumnos.js',['depends' => ['app\assets\AppAsset']]);
     break;
  
   case 2:
     $this->registerJsFile('@web/js/modules/mod_evaluaciones/responder_preguntas_docentes.js',['depends' => ['app\assets\AppAsset']]);
   break;
   
   default:
     $this->registerJsFile('@web/js/modules/mod_evaluaciones/responder_preguntas_decano.js',['depends' => ['app\assets\AppAsset']]);
     break;
 }

?>