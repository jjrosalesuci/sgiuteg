<?php
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

//use yii\web\Request;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<?= Html::csrfMetaTags() ?>

<script language='javascript'>
    var CSRF_TOKEN = '<?php echo  Yii::$app->getRequest()->getCsrfToken(); ?>';
    var BASE_URL_FRAME = '<?php echo  Yii::getAlias('@web').'/'; ?>';
</script>

<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema de información gerencial</title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrap">
    <div class="container">
        <?= $content ?>
    </div>
</div>
<footer class="footer">
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
