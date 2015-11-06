<?php

class FinancialController extends Controller
{
	public function filters() {
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	public function accessRules() {
		return array(

			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(	'getReport','createIncome','createExpense','createFinancialType','getFinancialTypes',
								  	'getIncomesAndExpenses','getIncomeOrExpense','editIncomeOrExpense','deleteIncomeOrExpense'),
				'users'=>array('@'),//colocar el @ para usuarios logueados 
			),
			array('deny',  // deny 
				'users'=>array('*'),
			),
		);
	}

	public function actions() {
		return array(
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////


	public function actionGetReport(){
		$sistemConfirm = false;
		$sistemMessage = "";
		$report = FinancialReport::model()->find("fr_user_id = ".yii::app()->user->id." ORDER BY fr_id DESC" );
		if($report != null){
			$sistemMessage = array(
				'income'=>$report->fr_incomes,
				'expense'=>$report->fr_expenses,
				'available'=>$report->fr_available,
			);
			$sistemConfirm = true;
		}
		echo json_encode( array('sistemConfirm'=>$sistemConfirm, 'sistemMessage'=>$sistemMessage) );
	}

	public function actionCreateIncome(){
		$sistemConfirm = false;
		$sistemMessage = "";
		$data = file_get_contents("php://input");
		if( $data ){
			$data = json_decode($data, true);
			$income = new FinancialIncome();
			$income->fi_value = $data['income'];
			$income->fi_date  = $data['date'];
			$income->fi_ft_id = $data['type'];
			if(array_key_exists('note', $data))
				$income->fi_note  = $data['note'];
			$income->fi_user_id = yii::app()->user->id;
			if($income->save()){				
				if( $this->setFinancialReport(true, $income->fi_value, null, null, 1) ){
					$sistemConfirm = true;
				} else {
					$income->delete();
					$sistemMessage = "Error en el servidor, por favor, inténtalo de nuevo";
				}
			}else
				$sistemMessage = CHtml::errorSummary( $income );
		} 
		echo json_encode( array('sistemConfirm'=>$sistemConfirm, 'sistemMessage'=>$sistemMessage) );
	}	

	public function actionCreateExpense(){
		$sistemConfirm = false;
		$sistemMessage = "";
		$data = file_get_contents("php://input");
		if( $data ){
			$data = json_decode($data, true);
			$expense = new FinancialExpense();
			$expense->fe_value = $data['expense'];
			$expense->fe_date  = $data['date'];
			$expense->fe_ft_id = $data['type'];
			if(array_key_exists('note', $data))
				$expense->fe_note  = $data['note'];
			$expense->fe_user_id = yii::app()->user->id;
			if($expense->save()){
				if( $this->setFinancialReport(false, $expense->fe_value, null, null, 2) ){
					$sistemConfirm = true;
				} else {
					$expense->delete();
					$sistemMessage = "Error en el servidor, por favor, inténtalo de nuevo";
				}
			}else
				$sistemMessage = CHtml::errorSummary( $income );
		} 
		echo json_encode( array('sistemConfirm'=>$sistemConfirm, 'sistemMessage'=>$sistemMessage) );
	}

	public function actionCreateFinancialType(){
		$sistemConfirm = false;
		$sistemMessage = "";
		$data = file_get_contents("php://input");
		if( $data ){
			$data = json_decode($data, true);
			$financialType = new FinancialType();
			$financialType->ft_user_id = yii::app()->user->id;
			$financialType->ft_name = $data['typeName'];
			$financialType->ft_is_incomes = $data['isIncome']==1?1:0;
			if( $financialType->save() )
				$sistemConfirm = true;
			else
				$sistemMessage = CHtml::errorSummary( $financialType );
		}		
		echo json_encode( array('sistemConfirm'=>$sistemConfirm, 'sistemMessage'=>$sistemMessage) );
	}


	public function actionGetFinancialTypes(){
		$sistemConfirm = true;
		$sistemMessage = "";
		$sistemMessage = $this->getFinancialTypes();
		echo json_encode( array('sistemConfirm'=>$sistemConfirm, 'sistemMessage'=>$sistemMessage) );
	}


	public function actionGetIncomesAndExpenses(){
		$sistemConfirm = true;
		// $limit = 10;
		$sistemMessage = array();
		$incomesModel = FinancialIncome::model()->findAll(	array(
															'condition'=>"fi_user_id = ".yii::app()->user->id,
															'order'=>'fi_date DESC',
															// 'limit'=>$limit, 
															));
		$expensesModel = FinancialExpense::model()->findAll(	array(
															'condition'=>"fe_user_id = ".yii::app()->user->id,
															'order'=>'fe_date DESC',
															// 'limit'=>$limit, 
															));

		foreach ($incomesModel as $key => $income) {
			array_push($sistemMessage, array(	'id'=>$income->fi_id,
												'name'=>$income->fiFt->ft_name,
												'date'=>$income->fi_date,
												'note'=>$income->fi_note, 
												'value'=>$income->fi_value,  
												'is_income'=>true, ));
		}
		foreach ($expensesModel as $key => $expense) {
			array_push($sistemMessage, array(	'id'=>$expense->fe_id,
												'name'=>$expense->feFt->ft_name,
												'date'=>$expense->fe_date,
												'note'=>$expense->fe_note, 
												'value'=>$expense->fe_value,
												'is_income'=>false,  ));
		}
		echo json_encode( array('sistemConfirm'=>$sistemConfirm, 'sistemMessage'=>$sistemMessage) );
	}


	public function actionGetIncomeOrExpense(){
		$sistemConfirm = false;
		$sistemMessage = "The information supplied don't be correct.";
		$data = file_get_contents("php://input");
		if( $data ) {
			$data = json_decode($data, true);
			if( array_key_exists('isIncome',$data) &&  array_key_exists('id',$data)){
				if( $data['isIncome'] == "true" ){
					$register = FinancialIncome::model()->find('fi_user_id = '.yii::app()->user->id." AND fi_id = ".$data['id']);
					if( $register != null){
						$sistemConfirm = true;
						$sistemMessage = array(	'id'=>$register->fi_id,
												'isIncome'=>true,
												'value'=>floatval($register->fi_value),
												'date'=>$register->fi_date,
												'note'=>$register->fi_note, 
												'typeId'=>$register->fi_ft_id,
												'uneditable'=>$register->fi_uneditable,	);
					}
				}
				else {
					$register = FinancialExpense::model()->find('fe_user_id = '.yii::app()->user->id." AND fe_id = ".$data['id']);
					if( $register != null){
						$sistemConfirm = true;
						$sistemMessage = array(	'id'=>$register->fe_id,
												'isIncome'=>false,
												'value'=>floatval($register->fe_value),
												'date'=>$register->fe_date,
												'note'=>$register->fe_note, 
												'typeId'=>$register->fe_ft_id,
												'uneditable'=>$register->fe_uneditable,	);
					}
				}
			
			} 
		} 
		$sistemMessage = array('financialRegister'=>$sistemMessage, 'financialTypes'=>$this->getFinancialTypes() );
		echo json_encode( array('sistemConfirm'=>$sistemConfirm, 'sistemMessage'=>$sistemMessage) );
	}


	public function actionEditIncomeOrExpense(){
		$sistemConfirm = false;
		$sistemMessage = "The information supplied don't be correct.";
		$data = file_get_contents("php://input");
		if( $data ) {
			$data = json_decode($data, true);
			if( array_key_exists('isIncome',$data) &&  array_key_exists('id',$data)){
				if( $data['isIncome'] == "true" ){
					$register = FinancialIncome::model()->findByPk($data['id']);
					$oldregister = $register;
					if( $register != null){
						if ($register->fi_uneditable == 0 && $register->fi_user_id == yii::app()->user->id){
							$oldValue = $register->fi_value;
							$register->fi_value = $data['value'];
							$register->fi_date = $data['date'];
							$register->fi_note = $data['note'];
							$register->fi_ft_id = $data['typeId'];
							if($register->save()){
								if($this->setFinancialReport(true, $data['value'] , true, $oldValue , 3))
									$sistemConfirm = true;
								else {
									$register = $oldregister;
									$register->save();
								}
							} else
								$sistemMessage = "The income couldn't be saved.";
						} else
							$sistemMessage = "The register is uneditable.";
					}
				}
				else {
					$register = FinancialExpense::model()->findByPk($data['id']);
					$oldregister = $register;
					if( $register != null){
						if ($register->fe_uneditable == 0 && $register->fe_user_id == yii::app()->user->id){
							$oldValue = $register->fe_value;
							$register->fe_value = $data['value'];
							$register->fe_date = $data['date'];
							$register->fe_note = $data['note'];
							$register->fe_ft_id = $data['typeId'];
							if($register->save())
								if($this->setFinancialReport(false, $data['value'] , true, $oldValue, 4))
									$sistemConfirm = true;
								else {
									$register = $oldregister;
									$register->save();
								}
							else
								$sistemMessage = "The expense couldn't be saved.";
						} else
							$sistemMessage = "The register is uneditable.";
					}
				}			
			} 
		} 
		echo json_encode( array('sistemConfirm'=>$sistemConfirm, 'sistemMessage'=>$sistemMessage) );
	}


	public function actionDeleteIncomeOrExpense(){
		$sistemConfirm = false;
		$sistemMessage = "The information supplied don't be correct.";
		$data = file_get_contents("php://input");
		if( $data ) {
			$data = json_decode($data, true);
			if( array_key_exists('isIncome',$data) &&  array_key_exists('id',$data)){
				if( $data['isIncome'] == "true" ){
					$register = FinancialIncome::model()->findByPk($data['id']);
					$oldregister = $register;
					if( $register != null){
						if ($register->fi_uneditable == 0 && $register->fi_user_id == yii::app()->user->id){
							$oldValue = $register->fi_value;
							if($register->delete()){
								if($this->setFinancialReport(true, 0 , true, $oldValue, 5))
									$sistemConfirm = true;
								else {
									$register = $oldregister;
									$register->save();
								}
							} else
								$sistemMessage = "The income couldn't be deleted.";
						} else
							$sistemMessage = "The register is uneditable.";
					}
				}
				else {
					$register = FinancialExpense::model()->findByPk($data['id']);
					$oldregister = $register;
					if( $register != null){
						if ($register->fe_uneditable == 0 && $register->fe_user_id == yii::app()->user->id){
							$oldValue = $register->fe_value;
							if($register->delete())
								if($this->setFinancialReport(false, 0 , true, $oldValue, 6))
									$sistemConfirm = true;
								else {
									$register = new FinancialExpense();
									$register = $oldregister;
									$register->save();
								}
							else
								$sistemMessage = "The expense couldn't be deleted.";
						} else
							$sistemMessage = "The register is uneditable.";
					}
				}
			}
		}
		echo json_encode( array('sistemConfirm'=>$sistemConfirm, 'sistemMessage'=>$sistemMessage) );
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	

	private function getFinancialTypes(){
		$financialTypes = FinancialType::model()->findAll(array('condition'=>"ft_user_id = ".yii::app()->user->id));
		$incomes = array();
		$expenses = array();
		foreach ($financialTypes as $financialType) {
			if($financialType->ft_is_incomes)
				array_push($incomes, array( 'ft_id'=>$financialType->ft_id, 'ft_name'=>$financialType->ft_name ) );
			else 
				array_push($expenses, array( 'ft_id'=>$financialType->ft_id, 'ft_name'=>$financialType->ft_name ) );
		}
		return array( 'incomes'=>$incomes, 'expenses'=>$expenses);
	}


	private function setFinancialReport($isIncome, $newValue, $editRegister, $oldValue, $financialMove){
		$report = FinancialReport::model()->find("fr_user_id = ".yii::app()->user->id." ORDER BY fr_id DESC" );
		$newReport = new FinancialReport();
		if($editRegister!=true) {
			if($isIncome){
				$newReport->fr_incomes = $report->fr_incomes + $newValue;
				$newReport->fr_expenses = $report->fr_expenses;
				$newReport->fr_available = $report->fr_available + $newValue; 
			} else {
				$newReport->fr_incomes = $report->fr_incomes;
				$newReport->fr_expenses = $report->fr_expenses + $newValue;
				$newReport->fr_available = $report->fr_available - $newValue; 
			}
		} else {
			if($isIncome){
				$newReport->fr_incomes = $report->fr_incomes - $oldValue + $newValue;
				$newReport->fr_expenses = $report->fr_expenses;
				$newReport->fr_available = $report->fr_available - $oldValue + $newValue;
			} else {
				$newReport->fr_incomes = $report->fr_incomes;
				$newReport->fr_expenses = $report->fr_expenses - $oldValue + $newValue;
				$newReport->fr_available = $report->fr_available + $oldValue - $newValue;
			}
		}
		$newReport->fr_user_id = yii::app()->user->id;
		$newReport->fr_fm_id = $financialMove;
		if( $newReport->save() )
			return true;
		return false;
	}



}

?>