<?php

class MapsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('admin','view','multiviews', 'reload'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('create','delete','index','update'),
				'users'=>Yii::app()->getModule('user')->getAdmins(),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Maps;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Maps']))
		{
			$model->attributes=$_POST['Maps'];
			if($model->save())
			{
				if(isset($_FILES['images']))
				{
					foreach($_FILES['images']['name'] as $key=>$filename)
					{
						$filepath = Yii::getPathOfAlias('webroot').'/images/uploads/'.$filename;
						$thumbpath = Yii::getPathOfAlias('webroot').'/images/uploads/thumbs_'.$filename;
				        	move_uploaded_file($_FILES['images']['tmp_name'][$key], $filepath);

						$thumb=Yii::app()->phpThumb->create($filepath);
						$thumb->resize(150,150);
						$thumb->save($thumbpath);

						Yii::app()->db->createCommand()->insert('tbl_images', array(
						    'path'=>$filename,
						    'maps_id'=>$model->id,
						));
					}
				}
				$this->redirect(array('view','id'=>$model->id));
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Maps']))
		{
			$model->attributes=$_POST['Maps'];
			if($model->save())
				if(isset($_FILES['images']))
				{
					//delete older images
					$item_data = Images::model()->findAll('maps_id =' .$id);
					if(!empty($item_data)) {
						foreach ($item_data as $record) {
							$filepath = Yii::getPathOfAlias('webroot').'/images/uploads/'.$record->path;
							$thumbs = Yii::getPathOfAlias('webroot').'/images/uploads/thumbs_'.$record->path;
							unlink($filepath);
							unlink($thumbs);
						}
					}
					Images::model()->deleteAll('maps_id=' .$id);

					//insert new images
					foreach($_FILES['images']['name'] as $key=>$filename)
					{
						$myfile = Yii::getPathOfAlias('webroot').'/images/uploads/'.$filename;
						$thumbpath = Yii::getPathOfAlias('webroot').'/images/uploads/thumbs_'.$filename;
				        	move_uploaded_file($_FILES['images']['tmp_name'][$key], $myfile);

						$thumb=Yii::app()->phpThumb->create($myfile);
						$thumb->resize(150,150);
						$thumb->save($thumbpath);

						Yii::app()->db->createCommand()->insert('tbl_images', array(
						    'path'=>$filename,
						    'maps_id'=>$id,
						));
					}
				}
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	public function updatePhoto($model, $myfile) {
		$image = Yii::app()->image->load($model->getPath());
		//Crunch the photo to a size set in my System Options Table
		//I hold the max size as 800 meaning to fit in an 800px x 800px square
		$size=$this->getOption('PhotoLarge');
		$image->resize($size[0], $size[0])->quality(75)->sharpen(20);
		$image->save(); 

		// Now create a thumb - again the thumb size is held in System Options Table
		$size=$this->getOption('PhotoThumb');
		$image->resize($size[0], $size[0])->quality(75)->sharpen(20);
		$image->save($model->getThumb()); // or $image->save('images/small.jpg');
		return true;
        }

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Maps');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Maps('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Maps']))
			$model->attributes=$_GET['Maps'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Maps::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	public function actionmultiviews()
	{
		$this->render('multiviews');
	}

	public function actionreload()
	{
		$this->render('reload');
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='maps-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
