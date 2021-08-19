<?php 

define('WORKING_DIRECTORY', dirname(__FILE__) );

require_once( WORKING_DIRECTORY. '/mail/Exception.php');
require_once( WORKING_DIRECTORY. '/mail/OAuth.php');
require_once( WORKING_DIRECTORY. '/mail/PHPMailer.php');
require_once( WORKING_DIRECTORY. '/mail/POP3.php');
require_once( WORKING_DIRECTORY. '/mail/SMTP.php');


if( lcword($_SERVER['REQUEST_METHOD']) == "post") {

	$result = Process($_POST);
	echo "{ status:\" ". $result ." \"}";
}



function Process($post){

	$template_content = file_get_contents(WORKING_DIRECTORY. '/template.php');

	$mail_body = null;

	if( !is_null( $post ) ) {

		$mail_body = str_replace(array_keys($post), array_values($post), $template_content);

		$emailer = new PHPMailer\PHPMailer\PHPMailer();
		$emailer->isHTML(TRUE);
		$emailer->isSMTP();
		$emailer->addAddress("inquiry@mynumber.lk", "Team MyNumber");
		$emailer->setFrom( $post['contact_email'], $post['contact_names']);
		$emailer->Body = $mail_body;
		$emailer->Subject = "Contact Lead : ". $post['contact_email'];

		try{
			$send_result = $emailer->send();
			if( !is_null( $send_result) && $send_result !== FALSE ) {
				return "ok";
			}else{
				return "failed";
			}
		}catch(Exception $ex) {

			return "failed";
		}
		
	}else{
		return "failed";
	}
}