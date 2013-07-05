<?php
/* @var $this KegiatanController */
/* @var $model Kegiatan */

$this->breadcrumbs=array(
	'Kegiatans'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'Manage Kegiatan', 'url'=>array('admin')),
	array('label'=>'Update Kegiatan', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Kegiatan', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>View Kegiatan #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'time',
		'email',
		'subject',
		'detail',
		'create_at',
	),
)); ?>
