<?php

$this->registerCssFile('@web/css/fileupload.css',['depends' => ['app\assets\AppAsset']]);
$this->registerJsFile('@web/js/FileUploadField.js',['depends' => ['app\assets\AppAsset']]);

$this->registerJsFile('@web/js/modules/mod_contable/importar.js',['depends' => ['app\assets\AppAsset']]);

?>
