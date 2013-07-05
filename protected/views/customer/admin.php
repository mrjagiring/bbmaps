<?php
/* @var $this CustomerController */
/* @var $model Customer */

$this->breadcrumbs=array(
	'Customers'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Customer', 'url'=>array('index')),
	array('label'=>'Create Customer', 'url'=>array('create')),
);
?>

<h1>Manage Customers</h1>
<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'type'=>'striped bordered condensed',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'template'=>'{summary}{items}{pager}',
	'columns'=>array(
		array(
			'name'=>'erp_customer',
			'type'=>'text',
			'headerHtmlOptions'=>array('width'=>'210px'),
		),
		array(
			'name'=>'address',
			'type'=>'text',
		),
		array(
			'name'=>'city',
			'type'=>'text',
			'headerHtmlOptions'=>array('width'=>'150px'),
		),
		array(
			'name'=>'territory',
			'type'=>'text',
			'headerHtmlOptions'=>array('width'=>'100px'),
			'value'=> array($this,'getTerritoryName'),
			'filter' => 
				CHtml::listData(
				is_numeric($model->territory) ? Territory::model()->findAll(new CDbCriteria(array(
					'condition' => 'id = :parentId',
					'params' => array(':parentId' => $model->territory),
				))) : Territory::model()->findAll(), 'id', 'name'),
		),
		array(
			'header'=>'Action',
			'headerHtmlOptions'=>array('width'=>'50px'),
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{view}{update}',
		),
	),
)); ?>
