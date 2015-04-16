<?php
$this->registerJsFile('@web/js/moment.min.js',['depends' => ['app\assets\AppAsset']]);
$this->registerJsFile('@web/js/modules/mod_biblioteca/prestamos.js',['depends' => ['app\assets\AppAsset']]);

?>