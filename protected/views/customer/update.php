<?php
/* @var $this CustomerController */
/* @var $model Customer */

$this->breadcrumbs=array(
	'Customers'=>array('index'),
	$model->id_customer=>array('view','id'=>$model->id_customer),
	'Update',
);

$this->menu=array(
	array('label'=>'List Customer', 'url'=>array('index')),
	array('label'=>'Create Customer', 'url'=>array('create')),
	array('label'=>'View Customer', 'url'=>array('view', 'id'=>$model->id_customer)),
	array('label'=>'Manage Customer', 'url'=>array('admin')),
);
?>

<h1>Update Customer <?php echo $model->id_customer; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>