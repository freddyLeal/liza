<?php

    class EmailUtils {

        public function init(){}
        
     
        public function sendSimpleEmail($view, $to, $from, $subject, $content) {
            Yii::import('ext.yii-mail.YiiMailMessage');
            $message = new YiiMailMessage;
            $message->view = $view;
            $message->subject = $subject;
            $message->setBody($content, 'text/html');
            $message->setTo($to);
            if ($from != null) {
            	$from = array(Yii::app()->params['DEFAULT_NOTIFICATIONS_EMAIL'] => $from);
            } else{
            	$from = array(Yii::app()->params['DEFAULT_NOTIFICATIONS_EMAIL'] => Yii::app()->params['DEFAULT_NOTIFICATIONS_EMAIL_NAME']);
            }
            $message->setFrom($from);
            Yii::app()->mail->send($message);
        }


        public function addQueue($templateId, $priority, $subject, $recipientEmail, $recipientName, $senderName, $data, $sent){
            $queue = new EmailQueue ();
            $queue->eq_et_id = $templateId;
            $queue->eq_priority = $priority;
            $queue->eq_subject =  $subject;  
            $queue->eq_recipient_email =  $recipientEmail;
            $queue->eq_recipient_name =  $recipientName; 
            if($senderName!=null)
                $queue->eq_sender_name =  $senderName;   
            else 
                $queue->eq_sender_name =  Yii::app()->params['DEFAULT_NOTIFICATIONS_EMAIL_NAME'];   
            $queue->eq_sent =  $sent;        
            if ($data != null){
                $queue->eq_dynamic_data_json =  serialize($data);    
            } 
            $queue->save();
        }


        public function sendInstantaneousEmail( $templateId, $subject, $recipientEmail, $recipientName, $data){
            $emailTemplate = EmailTemplate::model()->findByPk($templateId);
            $to = array($recipientEmail => $recipientName);
            $this->addQueue($templateId, 100, $subject, $recipientEmail, $recipientName, null, $data, 1);
            $this->sendSimpleEmail( $emailTemplate->et_url, $to, null, $subject, $data );
        }
        

        public function generateSecurityCode(){
            $string = 'ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789abcdefghijklmnopqrstuvwwyz'; 
            $lenght = strlen($string); 
            $lenght--; 
            $code=NULL; 
            for($x=1;$x<=40;$x++){ 
                $Posicao = rand(0,$lenght); 
                $code .= substr($string,$Posicao,1); 
            } 
            return $code;
        }






    }

?>