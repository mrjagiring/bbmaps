<?php
/* @var $this KegiatanController */
/* @var $model Kegiatan */

$this->breadcrumbs=array(
	'Activity'=>array('admin'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'View Kegiatan', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Kegiatan', 'url'=>array('admin')),
);
?>

<h1>Update Kegiatan <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
