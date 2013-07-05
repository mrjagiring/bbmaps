<?php
/* @var $this MapsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Maps',
);

$this->menu=array(
	array('label'=>'Create Maps', 'url'=>array('create')),
	array('label'=>'Manage Maps', 'url'=>array('admin')),
);
?>

<h1>Maps</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
