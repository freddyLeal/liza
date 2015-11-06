<?php

/**
 * This is the model class for table "email_queue".
 *
 * The followings are the available columns in table 'email_queue':
 * @property integer $eq_id
 * @property integer $eq_et_id
 * @property string $eq_creation_date
 * @property integer $eq_priority
 * @property string $eq_subject
 * @property string $eq_recipient_email
 * @property string $eq_recipient_name
 * @property string $eq_sender_name
 * @property string $eq_dynamic_data_json
 * @property integer $eq_sent
 *
 * The followings are the available model relations:
 * @property EmailTemplate $eqEt
 */
class EmailQueue extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'email_queue';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('eq_et_id, eq_priority, eq_subject, eq_recipient_email', 'required'),
			array('eq_et_id, eq_priority, eq_sent', 'numerical', 'integerOnly'=>true),
			array('eq_subject, eq_recipient_email, eq_recipient_name, eq_sender_name', 'length', 'max'=>100),
			array('eq_dynamic_data_json', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('eq_id, eq_et_id, eq_creation_date, eq_priority, eq_subject, eq_recipient_email, eq_recipient_name, eq_sender_name, eq_dynamic_data_json, eq_sent', 'safe', 'on'=>'search'),
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
			'eqEt' => array(self::BELONGS_TO, 'EmailTemplate', 'eq_et_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'eq_id' => 'Eq',
			'eq_et_id' => 'Eq Et',
			'eq_creation_date' => 'Eq Creation Date',
			'eq_priority' => 'Eq Priority',
			'eq_subject' => 'Eq Subject',
			'eq_recipient_email' => 'Eq Recipient Email',
			'eq_recipient_name' => 'Eq Recipient Name',
			'eq_sender_name' => 'Eq Sender Name',
			'eq_dynamic_data_json' => 'Eq Dynamic Data Json',
			'eq_sent' => 'Eq Sent',
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

		$criteria->compare('eq_id',$this->eq_id);
		$criteria->compare('eq_et_id',$this->eq_et_id);
		$criteria->compare('eq_creation_date',$this->eq_creation_date,true);
		$criteria->compare('eq_priority',$this->eq_priority);
		$criteria->compare('eq_subject',$this->eq_subject,true);
		$criteria->compare('eq_recipient_email',$this->eq_recipient_email,true);
		$criteria->compare('eq_recipient_name',$this->eq_recipient_name,true);
		$criteria->compare('eq_sender_name',$this->eq_sender_name,true);
		$criteria->compare('eq_dynamic_data_json',$this->eq_dynamic_data_json,true);
		$criteria->compare('eq_sent',$this->eq_sent);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EmailQueue the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
