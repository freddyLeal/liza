<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property string $user_id
 * @property string $user_email
 * @property string $user_name
 * @property string $user_password
 * @property integer $user_active
 * @property integer $user_failed_login_attempts
 * @property string $user_security_code
 *
 * The followings are the available model relations:
 * @property FinancialExpense[] $financialExpenses
 * @property FinancialIncome[] $financialIncomes
 * @property FinancialReport[] $financialReports
 * @property FinancialType[] $financialTypes
 * @property NotificationEmail[] $notificationEmails
 */
class User extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_email, user_name, user_password, user_active', 'required'),
			array('user_active, user_failed_login_attempts', 'numerical', 'integerOnly'=>true),
			array('user_email', 'length', 'max'=>50),
			array('user_name, user_password', 'length', 'max'=>20),
			array('user_security_code', 'length', 'max'=>40),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_id, user_email, user_name, user_password, user_active, user_failed_login_attempts, user_security_code', 'safe', 'on'=>'search'),
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
			'financialExpenses' => array(self::HAS_MANY, 'FinancialExpense', 'fe_user_id'),
			'financialIncomes' => array(self::HAS_MANY, 'FinancialIncome', 'fi_user_id'),
			'financialReports' => array(self::HAS_MANY, 'FinancialReport', 'fr_user_id'),
			'financialTypes' => array(self::HAS_MANY, 'FinancialType', 'ft_user_id'),
			'notificationEmails' => array(self::HAS_MANY, 'NotificationEmail', 'ne_user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'User',
			'user_email' => 'User Email',
			'user_name' => 'User Name',
			'user_password' => 'User Password',
			'user_active' => 'User Active',
			'user_failed_login_attempts' => 'User Failed Login Attempts',
			'user_security_code' => 'User Security Code',
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

		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('user_email',$this->user_email,true);
		$criteria->compare('user_name',$this->user_name,true);
		$criteria->compare('user_password',$this->user_password,true);
		$criteria->compare('user_active',$this->user_active);
		$criteria->compare('user_failed_login_attempts',$this->user_failed_login_attempts);
		$criteria->compare('user_security_code',$this->user_security_code,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
