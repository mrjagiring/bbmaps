<?php
/* @var $this MapsController */
/* @var $model Maps */

$this->breadcrumbs=array(
	'Maps'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Maps', 'url'=>array('index')),
	array('label'=>'Create Maps', 'url'=>array('create')),
	array('label'=>'View Maps', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Maps', 'url'=>array('admin')),
);
?>

<h1>Update Maps <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>