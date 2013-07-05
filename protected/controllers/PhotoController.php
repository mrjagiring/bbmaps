<?php

class PhotoController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}


 
	public function actionCreate()
	{
		$model=new Photos;
 
		if(isset($_POST['Photos']))
		{
                    $model->attributes=$_POST['Photos'];
                    $myfile = CUploadedFile::getInstance($model,'image');
                    $model->image=$myfile;
 
                    if($model->save())
                        $this->updatePhoto($model, $myfile);
 
					$this->redirect('view'.'id'=>$model->id);
		}
 
        $this->render('create',array(
			'model'=>$model,
		));
	}
 
        /*--------------
        * Upload and crunch an image
        ----------------*/
        public function updatePhoto($model, $myfile ) {
           if (is_object($myfile) && get_class($myfile)==='CUploadedFile') {
                $ext = $model->image->getExtensionName();
 
		//generate a filename for the uploaded image based on a random number
		// but check that the random number has not already been used
                if ($model->filename=='' or is_null($model->filename)) {
                    $n=1;
                    // loop until random is unqiue - which it probably is first time!
                    while ($n>0) {
                        $rnd=dechex(rand()%999999999);
                        $filename=$model->property->ref . '_' . $rnd . '.' . $ext;
                        $n=Photos::model()->count('filename=:filename',array('filename'=>$filename));
                    }
                $model->filename=$filename;
                }
 
                $model->save();
 
                $model->image->saveAs($model->getPath());  //model->getPath see below
 
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
             } else return false;
        }


	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}
