<?php

/**
 * This is the model class for table "email_template".
 *
 * The followings are the available columns in table 'email_template':
 * @property integer $et_id
 * @property string $et_creation_date
 * @property string $et_code
 * @property string $et_name
 * @property string $et_url
 *
 * The followings are the available model relations:
 * @property EmailQueue[] $emailQueues
 */
class EmailTemplate extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'email_template';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('et_creation_date, et_code, et_name, et_url', 'required'),
			array('et_code', 'length', 'max'=>30),
			array('et_name', 'length', 'max'=>100),
			array('et_url', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('et_id, et_creation_date, et_code, et_name, et_url', 'safe', 'on'=>'search'),
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
			'emailQueues' => array(self::HAS_MANY, 'EmailQueue', 'eq_et_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'et_id' => 'Et',
			'et_creation_date' => 'Et Creation Date',
			'et_code' => 'Et Code',
			'et_name' => 'Et Name',
			'et_url' => 'Et Url',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('et_id',$this->et_id);
		$criteria->compare('et_creation_date',$this->et_creation_date,true);
		$criteria->compare('et_code',$this->et_code,true);
		$criteria->compare('et_name',$this->et_name,true);
		$criteria->compare('et_url',$this->et_url,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EmailTemplate the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
