<?php

/**
 * This is the model class for table "{{maps}}".
 *
 * The followings are the available columns in table '{{maps}}':
 * @property string $id
 * @property string $time
 * @property string $email
 * @property string $subject
 * @property string $detail
 * @property double $latitude
 * @property double $longitude
 * @property string $create_at
 *
 * The followings are the available model relations:
 * @property Images[] $images
 */
class Maps extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Maps the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{maps}}';
	}

	public $images;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('time, email, latitude, longitude', 'required'),
			array('latitude, longitude', 'numerical'),
			array('email', 'length', 'max'=>50),
			array('subject', 'length', 'max'=>200),
			array('detail', 'length', 'max'=>500),
			array('time, create_at', 'safe'),
			array('create_at','default',
				'value'=>new CDbExpression('NOW()'),
				'setOnEmpty'=>false,'on'=>'insert'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, time, email, subject, detail, latitude, longitude, create_at', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'images' => array(self::HAS_MANY, 'Images', 'maps_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'time' => 'Time',
			'email' => 'Email',
			'subject' => 'Subject',
			'detail' => 'Detail',
			'latitude' => 'Latitude',
			'longitude' => 'Longitude',
			'create_at' => 'Create At',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('detail',$this->detail,true);
		$criteria->compare('latitude',$this->latitude);
		$criteria->compare('longitude',$this->longitude);
		$criteria->compare('create_at',$this->create_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort' => array(
				'defaultOrder' => 'time desc',
			),
		));
	}
}
