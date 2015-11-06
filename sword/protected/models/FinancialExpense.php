<?php

/**
 * This is the model class for table "financial_expense".
 *
 * The followings are the available columns in table 'financial_expense':
 * @property string $fe_id
 * @property string $fe_user_id
 * @property string $fe_ft_id
 * @property double $fe_value
 * @property string $fe_date
 * @property string $fe_note
 * @property integer $fe_uneditable
 *
 * The followings are the available model relations:
 * @property FinancialType $feFt
 * @property User $feUser
 */
class FinancialExpense extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'financial_expense';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fe_user_id, fe_ft_id, fe_value, fe_date', 'required'),
			array('fe_uneditable', 'numerical', 'integerOnly'=>true),
			array('fe_value', 'numerical'),
			array('fe_user_id, fe_ft_id', 'length', 'max'=>20),
			array('fe_note', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('fe_id, fe_user_id, fe_ft_id, fe_value, fe_date, fe_note, fe_uneditable', 'safe', 'on'=>'search'),
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
			'feFt' => array(self::BELONGS_TO, 'FinancialType', 'fe_ft_id'),
			'feUser' => array(self::BELONGS_TO, 'User', 'fe_user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'fe_id' => 'Fe',
			'fe_user_id' => 'Fe User',
			'fe_ft_id' => 'Fe Ft',
			'fe_value' => 'Fe Value',
			'fe_date' => 'Fe Date',
			'fe_note' => 'Fe Note',
			'fe_uneditable' => 'Fe Uneditable',
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

		$criteria->compare('fe_id',$this->fe_id,true);
		$criteria->compare('fe_user_id',$this->fe_user_id,true);
		$criteria->compare('fe_ft_id',$this->fe_ft_id,true);
		$criteria->compare('fe_value',$this->fe_value);
		$criteria->compare('fe_date',$this->fe_date,true);
		$criteria->compare('fe_note',$this->fe_note,true);
		$criteria->compare('fe_uneditable',$this->fe_uneditable);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FinancialExpense the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
