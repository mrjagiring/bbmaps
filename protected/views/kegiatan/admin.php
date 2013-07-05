<?php
/* @var $this KegiatanController */
/* @var $model Kegiatan */

$this->breadcrumbs=array(
	'Activity'=>array('admin'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Manage Activity', 'url'=>array('admin')),
);
?>

<h1>Manage Activity</h1>
<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'type'=>'striped bordered condensed',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'template'=>'{summary}{items}{pager}',
	'columns'=>array(
		array(
			'name'=>'time',
			'type'=>'text',
			'headerHtmlOptions'=>array('width'=>'120px'),
			'htmlOptions'=>array('style'=>'text-align: center;/* font-size:10px;*/'),
			'value'=>function($data){ return strstr($data->time, ' ', true); },
		),
		array(
			'name'=>'email',
			'type'=>'text',
			'headerHtmlOptions'=>array('width'=>'120px'),
			'htmlOptions'=>array('style'=>'text-align: center;'),
			'value'=>function($data){ return strstr($data->email, '@', true); },
		),
		'subject',
		'detail',
		array(
			'header'=>'Action',
			'headerHtmlOptions'=>array('width'=>'50px'),
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{view}{update}',
		),
	),
)); ?>
