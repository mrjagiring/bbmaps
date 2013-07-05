<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/styles.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

	<?php Yii::app()->bootstrap->register(); ?>
</head>

<body>
<?php $this->widget('bootstrap.widgets.TbNavbar', array(
	'type'=>'inverse', // null or 'inverse'
	'brand'=>'BB Maps',
	'brandUrl'=>'#',
	'collapse'=>true, // requires bootstrap-responsive.css
	'items'=>array(
		array(
			'class'=>'bootstrap.widgets.TbMenu',
			'items'=>array(
				array('label'=>'Home', 'icon'=>'home', 'url'=>array('/site/index')),
				array('label'=>'Profile', 'icon'=>'user', 'url'=>Yii::app()->getModule('user')->profileUrl, 'visible'=>!Yii::app()->user->isGuest),
				array('label'=>'Customer', 'icon'=>'user', 'url'=>'#', 'visible'=>!Yii::app()->user->isGuest, 'items'=>array(
					array('label'=>'Manage Customer', 'url'=>array('/customer/admin')),
					)),
				array('label'=>'Activity', 'icon'=>'book', 'url'=>'#', 'visible'=>!Yii::app()->user->isGuest, 'items'=>array(
					array('label'=>'Manage Activity', 'url'=>array('/kegiatan/admin')),
					)),
				array('label'=>'Maps', 'icon'=>'book', 'url'=>'#', 'visible'=>!Yii::app()->user->isGuest, 'items'=>array(
					array('label'=>'Manage Maps', 'url'=>array('/maps/admin')),
					array('label'=>'Multi Maps', 'url'=>array('/maps/multiviews')),
					)),
				array('label'=>'Tools', 'icon'=>'cog', 'url'=>'#', 'visible'=>!Yii::app()->user->isGuest, 'items'=>array(
					array('label'=>'Reload Activity', 'url'=>array('/kegiatan/reload')),
					array('label'=>'Reload Maps', 'url'=>array('/maps/reload')),
					array('label'=>'Manage Territory', 'url'=>array('/territory/admin')),
					)),
				array('label'=>'Login', 'icon'=>'cog', 'url'=>Yii::app()->getModule('user')->loginUrl, 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest),
				array('label'=>'Register', 'url'=>Yii::app()->getModule('user')->registrationUrl, 'visible'=>Yii::app()->user->isGuest),
				),
			),
		),
	));
?>
<div class="container" id="page">

	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer">
		<br/>
		Copyright &copy; <?php echo date('Y'); ?>
		Design and Customize by Mr. Jagiring.<br/>
		<?php echo Yii::powered(); ?>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
