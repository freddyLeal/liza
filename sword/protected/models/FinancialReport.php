<?php

/**
 * This is the model class for table "financial_report".
 *
 * The followings are the available columns in table 'financial_report':
 * @property string $fr_id
 * @property string $fr_user_id
 * @property double $fr_incomes
 * @property double $fr_expenses
 * @property double $fr_available
 * @property string $fr_date
 *
 * The followings are the available model relations:
 * @property User $frUser
 */
class FinancialReport extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'financial_report';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fr_user_id, fr_incomes, fr_expenses, fr_available', 'required'),
			array('fr_incomes, fr_expenses, fr_available, fr_fm_id', 'numerical'),
			array('fr_user_id', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('fr_id, fr_user_id, fr_incomes, fr_expenses, fr_available, fr_date, fr_fm_id', 'safe', 'on'=>'search'),
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
			'frUser' => array(self::BELONGS_TO, 'User', 'fr_user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'fr_id' => 'Fr',
			'fr_user_id' => 'Fr User',
			'fr_incomes' => 'Fr Incomes',
			'fr_expenses' => 'Fr Expenses',
			'fr_available' => 'Fr Available',
			'fr_date' => 'Fr Date',
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

		$criteria->compare('fr_id',$this->fr_id,true);
		$criteria->compare('fr_user_id',$this->fr_user_id,true);
		$criteria->compare('fr_incomes',$this->fr_incomes);
		$criteria->compare('fr_expenses',$this->fr_expenses);
		$criteria->compare('fr_available',$this->fr_available);
		$criteria->compare('fr_date',$this->fr_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FinancialReport the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
