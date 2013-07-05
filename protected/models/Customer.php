<?php

/**
 * This is the model class for table "{{customer}}".
 *
 * The followings are the available columns in table '{{customer}}':
 * @property integer $id_customer
 * @property string $erp_customer
 * @property string $company
 * @property string $address
 * @property string $city
 * @property integer $territory
 * @property double $latitude
 * @property double $longitude
 * @property string $create_at
 */
class Customer extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Customer the static model class
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
		return '{{customer}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('erp_customer, company, address, city, territory, latitude, longitude, create_at', 'required'),
			array('territory', 'numerical', 'integerOnly'=>true),
			array('latitude, longitude', 'numerical'),
			array('erp_customer, company, address', 'length', 'max'=>255),
			array('city', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_customer, erp_customer, company, address, city, territory, latitude, longitude, create_at', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_customer' => 'Id Customer',
			'erp_customer' => 'Erp Customer',
			'company' => 'Company',
			'address' => 'Address',
			'city' => 'City',
			'territory' => 'Territory',
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

		$criteria->compare('id_customer',$this->id_customer);
		$criteria->compare('erp_customer',$this->erp_customer,true);
		$criteria->compare('company',$this->company,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('territory',$this->territory);
		$criteria->compare('latitude',$this->latitude);
		$criteria->compare('longitude',$this->longitude);
		$criteria->compare('create_at',$this->create_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
				'pageSize' => 20,
			),
		));
	}
}
