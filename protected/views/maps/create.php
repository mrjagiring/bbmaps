<?php
/* @var $this MapsController */
/* @var $model Maps */

$this->breadcrumbs=array(
	'Maps'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Maps', 'url'=>array('index')),
	array('label'=>'Manage Maps', 'url'=>array('admin')),
);
?>

<h1>Create Maps</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>