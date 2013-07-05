<?php
/* @var $this MapsController */
/* @var $model Maps */

$this->breadcrumbs=array(
	'Maps'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Maps', 'url'=>array('index')),
	array('label'=>'Create Maps', 'url'=>array('create')),
	array('label'=>'Multi Views', 'url'=>array('multiviews')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('maps-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Maps</h1>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'type'=>'striped bordered condensed',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'template'=>"{items}",
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
			'headerHtmlOptions'=>array('width'=>'200px'),
			'htmlOptions'=>array('style'=>'text-align: center;'),
			'value'=>function($data){ return strstr($data->email, '@', true); },
		),
		'subject',
		array(
			'name'=>'latitude',
			'type'=>'text',
			'headerHtmlOptions'=>array('width'=>'100px'),
			'htmlOptions'=>array('style'=>'text-align: center;'),
		),
		array(
			'name'=>'longitude',
			'type'=>'text',
			'headerHtmlOptions'=>array('width'=>'100px'),
			'htmlOptions'=>array('style'=>'text-align: center;'),
		),
		/*
		'detail',
		'create_at',
		*/
		array(
			'header'=>'Action',
			'headerHtmlOptions'=>array('width'=>'40px'),
			'class'=>'CButtonColumn',
			'template'=>'{view}{update}',
		),
	),
)); ?>
