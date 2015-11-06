<?php

/**
 * This is the model class for table "financial_income".
 *
 * The followings are the available columns in table 'financial_income':
 * @property string $fi_id
 * @property string $fi_user_id
 * @property string $fi_ft_id
 * @property double $fi_value
 * @property string $fi_date
 * @property string $fi_note
 * @property integer $fi_uneditable
 *
 * The followings are the available model relations:
 * @property FinancialType $fiFt
 * @property User $fiUser
 */
class FinancialIncome extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'financial_income';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fi_user_id, fi_ft_id, fi_value, fi_date', 'required'),
			array('fi_uneditable', 'numerical', 'integerOnly'=>true),
			array('fi_value', 'numerical'),
			array('fi_user_id, fi_ft_id', 'length', 'max'=>20),
			array('fi_note', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('fi_id, fi_user_id, fi_ft_id, fi_value, fi_date, fi_note, fi_uneditable', 'safe', 'on'=>'search'),
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
			'fiFt' => array(self::BELONGS_TO, 'FinancialType', 'fi_ft_id'),
			'fiUser' => array(self::BELONGS_TO, 'User', 'fi_user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'fi_id' => 'Fi',
			'fi_user_id' => 'Fi User',
			'fi_ft_id' => 'Fi Ft',
			'fi_value' => 'Fi Value',
			'fi_date' => 'Fi Date',
			'fi_note' => 'Fi Note',
			'fi_uneditable' => 'Fi Uneditable',
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

		$criteria->compare('fi_id',$this->fi_id,true);
		$criteria->compare('fi_user_id',$this->fi_user_id,true);
		$criteria->compare('fi_ft_id',$this->fi_ft_id,true);
		$criteria->compare('fi_value',$this->fi_value);
		$criteria->compare('fi_date',$this->fi_date,true);
		$criteria->compare('fi_note',$this->fi_note,true);
		$criteria->compare('fi_uneditable',$this->fi_uneditable);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FinancialIncome the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
