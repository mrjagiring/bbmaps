<?php
/* @var $this MapsController */
/* @var $model Maps */

$this->breadcrumbs=array(
	'Maps'=>array('admin'),
	'Multi Views',
);

$this->menu=array(
	array('label'=>'Create Maps', 'url'=>array('create')),
	array('label'=>'Manage Maps', 'url'=>array('admin')),
);
?>
<div align=center class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'multimaps-form',
		'action' => Yii::app()->createUrl('maps/multiviews'),
		'enableAjaxValidation'=>false,
		'htmlOptions'=>array('enctype' => 'multipart/form-data'),
	)); ?>
	
	<div class="row">
	Event Date : 
	<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
		'name'=>'dpf',
		'value'=>'[Silahkan Pilih Tanggal]',
		'options'=>array(
			'showAnim'=>'fold',
			'dateFormat'=>'yy-mm-dd',
		),
		'htmlOptions'=>array(
			'style'=>'height:20px;',
			'style'=>'align:center;',
		),
	)); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

	<?php
	$criteria=new CDbCriteria;
	$criteria->select='email';
	$criteria->distinct=true;
	$list=CHtml::listData(Maps::model()->findAll($criteria),'email','email');
	?>
	Sales Email : <?php echo CHtml::dropDownList('email', '', $list, array('prompt'=>'[Silahkan Pilih Email Sales]'));?>
	<?php echo CHtml::submitButton('Process', array('class'=>'button')); ?>
	</div><!-- row -->
	<?php $this->endWidget(); ?>
</div><!-- form -->

<div align=center>
<?php
if(isset($_POST['dpf']) && $_POST['dpf']!='[Silahkan Pilih Tanggal]')
{
	if(isset($_POST['email']) && $_POST['email']!="")
	{
		$dpf = $_POST['dpf']; $email = $_POST['email'];
		$sql="SELECT * FROM `tbl_maps` WHERE DATE(time)='$dpf' AND email='$email'";
		$connection=Yii::app()->db;
		$command=$connection->createCommand($sql);
		$results=$command->queryAll(); 

		if(!empty($results)) {
			echo '<h3>Sales Maps on '. $dpf.' by '.$email.'</h1>';
			Yii::import('ext.EGMap.*');
			$gMap = new EGMap();
			$gMap->setWidth('100%');
			$gMap->setHeight(500);
			$gMap->zoom = 12;
			$mapTypeControlOptions = array(
			  'position'=> EGMapControlPosition::LEFT_BOTTOM,
			  'style'=>EGMap::MAPTYPECONTROL_STYLE_DROPDOWN_MENU
			);
			$gMap->mapTypeControlOptions= $mapTypeControlOptions; 
			$icon = new EGMapMarkerImage("images/building-32.png");
			$icon->setSize(32, 37);
			$icon->setAnchor(16, 16.5);
			$icon->setOrigin(0, 0);
			$i=0;

			foreach($results AS $result){
				// Create Latitude and Longitude for Waypoint
				$lat[$i]=$result['latitude'];
				$long[$i]=$result['longitude'];

				// Create GMapInfoWindows
				$paths[$i] = "";
				$allImages = Images::model()->findAll('maps_id =' .$result['id']); 
				if(!empty($allImages))
				{
					foreach ($allImages as $record) { $paths[$i] .= '<img src="images/uploads/thumbs_'.$record->path.'">'.'&nbsp'; }
				}
				$info_window[$i] = new EGMapInfoWindow('<div>'.$result['subject'].'<br />'.$result['detail'].'<br /><br />'.$paths[$i]. '</div>');
				// Create marker
				$marker[$i] = new EGMapMarker($result['latitude'], $result['longitude'], array('title' => $result['subject'],'icon'=>$icon));
				$marker[$i]->addHtmlInfoWindow($info_window[$i]);
				$gMap->addMarker($marker[$i]);
				$i++;
			}
			if($i>=2) {
				$start = new EGMapCoord($lat[0], $long[0]);
				$stop = new EGMapCoord($lat[$i-1], $long[$i-1]);
				$direction = new EGMapDirection($start, $stop, 'direction_sample', array());
				$direction->optimizeWaypoints = true;
				$direction->provideRouteAlternatives = true;
				$renderer = new EGMapDirectionRenderer();
				$renderer->draggable = false;
				$renderer->panel = "direction_pane";
				$renderer->setPolylineOptions(array('strokeColor'=>'#ed0808'));
				$direction->setRenderer($renderer);
				$gMap->addDirection($direction);
			}
			$gMap->setCenter($lat[0], $long[0]);
			$gMap->renderMap();
		} else 
		echo '<h3>There are no maps data on database. Please pick other date or sales email.</h1>';
	}
}
?>
</div>
