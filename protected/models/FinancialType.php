<?php

/**
 * This is the model class for table "financial_type".
 *
 * The followings are the available columns in table 'financial_type':
 * @property string $ft_id
 * @property string $ft_user_id
 * @property string $ft_name
 * @property integer $ft_is_incomes
 *
 * The followings are the available model relations:
 * @property FinancialExpense[] $financialExpenses
 * @property FinancialIncome[] $financialIncomes
 * @property User $ftUser
 */
class FinancialType extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'financial_type';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ft_user_id, ft_name, ft_is_incomes', 'required'),
			array('ft_is_incomes', 'numerical', 'integerOnly'=>true),
			array('ft_user_id', 'length', 'max'=>20),
			array('ft_name', 'length', 'max'=>25),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ft_id, ft_user_id, ft_name, ft_is_incomes', 'safe', 'on'=>'search'),
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
			'financialExpenses' => array(self::HAS_MANY, 'FinancialExpense', 'fe_ft_id'),
			'financialIncomes' => array(self::HAS_MANY, 'FinancialIncome', 'fi_ft_id'),
			'ftUser' => array(self::BELONGS_TO, 'User', 'ft_user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ft_id' => 'Ft',
			'ft_user_id' => 'Ft User',
			'ft_name' => 'Ft Name',
			'ft_is_incomes' => 'Ft Is Incomes',
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

		$criteria->compare('ft_id',$this->ft_id,true);
		$criteria->compare('ft_user_id',$this->ft_user_id,true);
		$criteria->compare('ft_name',$this->ft_name,true);
		$criteria->compare('ft_is_incomes',$this->ft_is_incomes);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FinancialType the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
