<?php
	class EmailController extends Controller	{

		public function filters() {
			return array(
				'accessControl', 
				'postOnly + delete', 
			);
		}

		public function accessRules() {
			return array(
				array('allow', 
					'actions'=>array('sendEmail'),
					'users'=>array('*'),
				),
				array('allow', 
					'actions'=>array('index'),
					'roles'=>array('Admin','Email manager'),
				),

				array('deny',  
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

////////////////////////////////////////////////////////////////////////////////////////////

		public function actionSendEmail() {
			$emailQueue = EmailQueue::model()->find("eq_sent <> 1 ORDER BY eq_priority DESC");
			if ($emailQueue != null){
				Yii::app()->emailUtils->sendSimpleEmail(	$emailQueue->eqEt->et_url, 
															array($emailQueue->eq_recipient_email => $emailQueue->eq_recipient_name), 
															$emailQueue->eq_sender_name,
															$emailQueue->eq_subject, 
															unserialize($emailQueue->eq_dynamic_data_json) );	
				$emailQueue->eq_sent = 1;
				$emailQueue->save();		
			}		
		}	

		




		// public function actionDeleteQueue() {
		// 	$array = explode(',', $_POST['data']);
		// 	foreach ($array as $value) {
		// 		$model = EmailQueue::model()->findByPk($value);
		// 		$model->delete();
		// 	}
		// }

		// public function actionDeleteTemplate($id) {
		// 	try{ 
		// 		$model= EmailTemplate::model()->findByPk($id);
		// 		$model->delete();
		// 	} catch (Exception $e){ } 
		// 	if(!isset($_GET['ajax']))
		// 		$this->redirect(Yii::app()->request->baseUrl."/email/templateManagement");
		// }


		// public function actionTemplateManagement (){
		// 	$model = new EmailTemplate();
		// 	$this->render('templateManagement',array(
		// 		'model'=>$model,
		// 	));
		// }


		// public function actionUpdateTemplateEmail ($id){
		// 	$model = EmailTemplate::model()->findByPk($id);
		// 	if(isset($_POST['EmailTemplate'])) {
		// 		$model->attributes=$_POST['EmailTemplate'];
		// 		$model->image = CUploadedFile::getInstance($model,'image');
		// 		$model->validate();
		// 		if ($model->image != null ) {
		// 			$model->et_url = 'templates/'.$model->et_code."-".$model->image->name;
		// 			$model->et_url = str_replace(".php", "",$model->et_url);
		// 			if($model->save()) {
		// 				$model->image->saveAs(Yii::app()->params["DEFAULT_EMAIL_TEMPLATE_UPLOAD_FOLDER"].$model->et_code."-".$model->image->name);
		// 				echo Yii::app()->params["DEFAULT_EMAIL_TEMPLATE_UPLOAD_FOLDER"].$model->et_code."-".$model->image->name;
		// 				$this->redirect(array('templateManagement'));
		// 			}
		// 		}
		// 	} 			
		// 	$this->render('templateEmailDetail',array(
		// 		'model'=>$model,
		// 	));
		// }

		// public function actionCreateTemplateEmail(){
		// 	$model=new EmailTemplate;
		// 	if(isset($_POST['EmailTemplate'])) {
		// 		$user = User::model()->find("user_login = '".Yii::app()->user->name."'");
		// 		$model->et_user_id = $user->user_id;
		// 		$model->attributes=$_POST['EmailTemplate'];
		// 		$model->image = CUploadedFile::getInstance($model,'image');
		// 		$model->validate();
		// 		if ($model->image != null ) {
		// 			$model->et_url = 'templates/'.$model->et_code."-".$model->image->name;
		// 			$model->et_url = str_replace(".php", "",$model->et_url);
		// 			if($model->save()){
		// 				$model->image->saveAs(Yii::app()->params["DEFAULT_EMAIL_TEMPLATE_UPLOAD_FOLDER"].$model->et_code."-".$model->image->name);
		// 				$this->redirect(array('templateManagement'));
		// 			}
		// 		}		
		// 	} 
		// 	$this->render('createTemplateEmail',array(
		// 		'model'=>$model,
		// 	));
		// }


		// public function actionUnsubscribe(){
		// 	$this->layout='//layouts/column2';
		// 	$model = new EmailUnsubscriber();
		// 	if(isset($_POST['EmailUnsubscriber'])) {
		// 		$model->attributes=$_POST['EmailUnsubscriber'];				
		// 		if ($model->save()){
		// 			$this->redirect(array('unsubscribeSuccessful'));
		// 		}
		// 	} 
		// 	$this->render('unsubscriber',array('model'=>$model));			
		// }

		// public function actionUnsubscribeSuccessful(){
		// 	$this->layout='//layouts/column2';
		// 	$this->render('unsubscriberSuccessful');			
		// }

		// public function actionEmailReport (){
		// 	$results = array();			
		// 	$connection = yii::app()->db;
		// 	$logs = EmailLog::model()->findAll();
		// 	$templates = EmailTemplate::model()->findAll();
		// 	$sql="	SELECT el_email,count(*) as count
		// 			FROM email_log
		// 			GROUP BY el_email";
		// 	$command=$connection->createCommand($sql);
		// 	$countByEmail=$command->queryAll(); 
		// 	foreach ($countByEmail as $value) {	
		// 		$subResults = array();
		// 		foreach ($logs as $log) {
		// 			if ($log->el_email == $value['el_email']){
		// 				if ( !array_key_exists($log->el_et_id,$subResults) ){
		// 					$subResults[$log->el_et_id] = 1;
		// 				} else {
		// 					$subResults[$log->el_et_id] += 1;
		// 				}
		// 			}
		// 		}
		// 		$subResults['el_email'] = $value['el_email'];	
		// 		array_push($results , $subResults);
		// 	}
		// 	$this->render('emailReport',array(
		// 		'templates'=>$templates,
		// 		'results'=>$results,
		// 	));
		// }

		// public function actionQueueReport (){
		// 	$connection = yii::app()->db;
		// 	$sql="	SELECT * 
		// 			FROM email_queue 
		// 			LEFT JOIN email_template ON email_template.et_id = email_queue.eq_et_id
		// 			WHERE eq_recipient_email NOT IN (SELECT eu_email FROM email_unsubscriber)
		// 			ORDER BY eq_priority DESC , eq_creation_date ASC";
		// 	$command=$connection->createCommand($sql);								
		// 	$count = $connection->createCommand('SELECT COUNT(*) FROM (' . $sql . ') as count_alias')->queryScalar();
		// 	$queue=new CSqlDataProvider($command, array(
		//         'totalItemCount'=>$count,
		//         'keyField' => 'eq_id',
		//         'pagination'=>array('pageSize'=>$count),
		//     ));	
		// 	$this->render('queueReport',array(
		// 		'queue' => $queue,
		// 	));
		// }

		// public function actionRejectedQueue (){
		// 	$connection = yii::app()->db;
		// 	$sql="	SELECT * 
		// 			FROM email_queue 
		// 			LEFT JOIN email_template ON email_template.et_id = email_queue.eq_et_id
		// 			WHERE eq_recipient_email IN (SELECT eu_email FROM email_unsubscriber)
		// 			ORDER BY eq_priority DESC , eq_creation_date ASC";
		// 	$command=$connection->createCommand($sql);								
		// 	$count = $connection->createCommand('SELECT COUNT(*) FROM (' . $sql . ') as count_alias')->queryScalar();
		// 	$rejectedQueue=new CSqlDataProvider($command, array(
		//         'totalItemCount'=>$count,
		//         'keyField' => 'eq_id',
		//         'pagination'=>array('pageSize'=>$count),
		//     ));	
		// 	$this->render('rejectedQueue',array(
		// 		'rejectedQueue'=>$rejectedQueue
		// 	));
		// }

		

		// public function actionIndex (){
		// 	$this->render('index');
		// }


		// public function actionUnsubscriberEmail ($id){
		// 	if ($id == 0 ){
		// 		$model = new EmailUnsubscriber();
		// 	} else {
		// 		$model = EmailUnsubscriber::model()->findByPk($id);
		// 	}
		// 	$emailsUnsubscriber = new EmailUnsubscriber();		
		// 	$unsubscriptionType = UnsubscriptionType::model()->findAll(array('order'=>'ut_code')); 
		// 	$unsubscriptionTypeList = CHtml::listData($unsubscriptionType, 'ut_id', 'ut_description');
		// 	if( isset($_POST['EmailUnsubscriber']) ) {
		// 		$model->attributes=$_POST['EmailUnsubscriber'];	
		// 		$model->eu_ut_id = $_POST['unsubscriptionType'];	
		// 		$user = User::model()->find("user_login = '".Yii::app()->user->name."'");
		// 		$model->eu_user_id = $user->user_id;	
		// 		if ($model->save()){
		// 			$this->redirect(array('email/unsubscriberEmail/0'));
		// 		}
		// 	} 
		// 	if(isset($_GET['EmailUnsubscriber']))
		// 		$emailsUnsubscriber->attributes=$_GET['EmailUnsubscriber'];

		// 	$this->render('unsubscriberEmail',array(
		// 		'model'=>$model,
		// 		'unsubscriptionTypeList'=>$unsubscriptionTypeList,
		// 		'emailsUnsubscriber'=>$emailsUnsubscriber,
		// 	));
		// }

		// public function actionDeleteUnsubscriberEmail($id){
		// 	$model = EmailUnsubscriber::model()->findByPk($id);
		// 	$model->delete();
		// 	if(!isset($_GET['ajax']))
		// 		$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('email/unsubscriberEmail/0'));
		// }

		// public function actionSentEmailLog (){
		// 	$model = new EmailLog ();
		// 	if(isset($_GET['EmailLog']))
		// 		$model->attributes=$_GET['EmailLog'];
		// 	$this->render('sent_email_log',array(
		// 		'model'=>$model,
		// 	));
		// }


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		


	}

?>