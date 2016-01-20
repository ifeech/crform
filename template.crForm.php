<?
/* подкл. ядра */
include_once ('Core.crForm.php');

Class Template_CrForm extends Cr_Form {
	
	protected function templateFields($srcTemplate, $error = ''){
		$template = '';
		include ($srcTemplate);
		return $template;
	}
	
	protected function sendMail($valueMail){
		foreach($this->mailTo as $email) $error = mail($email, $valueMail['subject'], $valueMail['message'], $valueMail['headers']);
		return $error;
	}
	
	protected function templateMail($fields, $srcTemplate){
		include ($srcTemplate);
		return $valueMail;
	}

	protected function templateError($text, $type, $srcTemplate){
		if(empty($text) && empty($type)) return '';

		$template = '';
		include ($srcTemplate);
		return $template;
	}

	protected function templateSuccess(){
		$template = 'Thank you, be happy!';
		return $template;
	}
}

/* подкл. конфигураций */
include_once ('.config.crForm.php');
?>