<!-- NOTIFICATION -->
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl;?>/js/general/notification/js/jquery.gritter/css/jquery.gritter.css">
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl;?>/js/general/notification/css/style.css">

<?php echo $content; ?>

 <!-- NOTIFICATION -->
<?php Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl . '/js/general/notification/js/jquery.gritter/js/jquery.gritter.js', CClientScript::POS_END);?>  
