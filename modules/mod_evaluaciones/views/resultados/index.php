<script language='javascript'>
	var  id_trabajador      = '<?php echo  $id_trabajador ?>'; 
    var  id_periodo         = '<?php echo  $id_periodo ?>'; 
    var  nombre_periodo     = '<?php echo  $nombre_periodo ?>';  
    var  nombre_trabajador  = '<?php echo  $nombre_trabajador ?>';   
    var  nombre_asignatura  = '<?php echo  $nombre_asignatura ?>';
    var  id_asignatura      = '<?php echo  $id_asignatura ?>';    
</script>

<iframe id="myIFrm" src="" style="visibility:hidden"></iframe>

<?php
	$this->registerJsFile('@web/js/modules/mod_evaluaciones/mostrar_resultados.js',['depends' => ['app\assets\AppAsset']]);
?>