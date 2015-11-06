<?php

class SiteController extends Controller
{
	public function filters() {
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	public function accessRules() {
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','login','error','isloged','register','accountActivation','forgetPassword'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('logout'),
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

	public function actionIndex() {		
		$this->render('index');
	}

	public function actionError() {
		if($error=Yii::app()->errorHandler->error) {
			echo json_encode( array('sistemConfirm'=>false,'sistemMessage'=>"Unexpected error.") );
			// if(Yii::app()->request->isAjaxRequest)
			// 	echo $error['message'];
			// else
			// 	$this->render('error', $error);
		}
	}

	public function actionLogin() {
		$sistemConfirm = false;
		$sistemMessage = "Error to login, please try again.";
		$data = file_get_contents("php://input");
		if( $data ){
			$data = json_decode($data, true);
			$model = new LoginForm;
			$model->username = $data['username'];
			$model->password = $data['password'];
			if($model->validate() && $model->login())
				$sistemConfirm = true; 
		} else {
			$this->redirect(array('index'));
		}
		echo json_encode( array('sistemConfirm'=>$sistemConfirm, 'sistemMessage'=>$sistemMessage) );
	}

	public function actionLogout() {
		Yii::app()->user->logout();
		echo json_encode( array('sistemConfirm'=>true, 'sistemMessage'=>"") );
		// $this->redirect(Yii::app()->homeUrl);
	}

	public function actionIsLoged(){
		$sistemConfirm = false;
		if( yii::app()->user->id != null)
			$sistemConfirm = true;
		echo json_encode( array('sistemConfirm'=>$sistemConfirm) );
	}

	public function actionRegister(){
		$sistemConfirm = false;
		$sistemMessage = "";
		$data = file_get_contents("php://input");
		if( $data ){
			$data = json_decode( $data, true);
			if( isset( $data['email'], $data['name'], $data['password'], $data['verifyPassword'] ) ){
				if( User::model()->find("user_email = '".$data['email']."'") != null )
					$sistemMessage = "User already registered.";
				if( $data['email'] == "" || $data['email'] == null)
					$sistemMessage = "The email field can not be empty.";
				if( $data['name'] == "" || $data['name'] == null)
					$sistemMessage = "The name field can not be empty.";
				if( !filter_var($data['email'], FILTER_VALIDATE_EMAIL) )
					$sistemMessage = "Invalid email.";
				if( $data['password'] != $data['verifyPassword'] || $data['password'] == null || $data['password'] == ""  )
					$sistemMessage = "The password does not match and can not be empty.";
				if( $sistemMessage === ""){
					$newUser = new User();
					$newUser->user_name = $data['name'];
					$newUser->user_password = $data['password'];
					$newUser->user_email = $data['email'];
					$newUser->user_active = 0;
					$newUser->user_security_code = yii::app()->emailUtils->generateSecurityCode();
					if( $newUser->save()){
						$sistemConfirm = true;
						Yii::app()->emailUtils->sendInstantaneousEmail(	Yii::app()->params['TEMPLATE_REGISTER_EMAIL'],
																		"Register acount",
																		$newUser->user_email,
																		$newUser->user_name,
																		array('data'=>'q='.$newUser->user_security_code) );
					}
				}
			} else 
				$sistemMessage = "Error transmitting information.";
		} 
		echo json_encode( array('sistemConfirm'=>$sistemConfirm, 'sistemMessage'=>$sistemMessage) );
	}	

	public function actionAccountActivation(){
		if( isset($_GET['q']) ){
			$user = User::model()->find("user_security_code = '".$_GET['q']."'");
			if($user!=null){
				$user->user_security_code = null;
				$user->user_active = 1;
				$user->save();
				$financialReport = new FinancialReport();
				$financialReport->fr_user_id = $user->user_id;
				$financialReport->fr_incomes = 0;
				$financialReport->fr_expenses = 0;
				$financialReport->fr_available = 0;
				$financialReport->fr_fm_id = 1;
				$financialReport->save();
				$financialType = new FinancialType();
				$financialType->ft_user_id = $user->user_id;
				$financialType->ft_name = "Salary";
				$financialType->ft_is_incomes = 1;
				$financialType->save();
				$financialType = new FinancialType();
				$financialType->ft_user_id = $user->user_id;
				$financialType->ft_name = "Home";
				$financialType->ft_is_incomes = 0;
				$financialType->save();
				$this->render('account_activation');
			} else
				$this->redirect(array('site/index'));
		} else  
			$this->redirect(array('site/index'));
	}

	public function actionForgetPassword(){
		$sistemConfirm = false;
		$sistemMessage = "";
		$data = file_get_contents("php://input");
		if( $data ){
				if( $data == "" || $data == null)
				$sistemMessage = "The email field can not be empty.";
			$user = User::model()->find("user_email = '".$data."'");
			if( $user == null )
				$sistemMessage = "User not registered.";
			if( !filter_var($data, FILTER_VALIDATE_EMAIL) )
				$sistemMessage = "Invalid email.";
			if( $sistemMessage == ""){			
				$sistemConfirm = true;
				$user->user_security_code = yii::app()->emailUtils->generateSecurityCode();
				$user->save();
				Yii::app()->emailUtils->sendInstantaneousEmail(	Yii::app()->params['TEMPLATE_FORGET_EMAIL'],
																"Forget password.",
																$user->user_email,
																$user->user_name,
																array('data'=>'q='.$user->user_security_code) );
			}
		} 
		echo json_encode( array('sistemConfirm'=>$sistemConfirm, 'sistemMessage'=>$sistemMessage) );
	}




//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	
}

?>