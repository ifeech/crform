<?
Class Cr_Form{
	public $textErrorSend = "Ошибка отправки письма!";
	public $textSuccess = "Форма отправлена!";
	public $mailTo;

	function __construct(){}
	
	public function startCrForm($request, $type = 'mail'){
		switch($this->checkRequest($request)){
			case 'success': $this->startMail(); break;
			case 'error': break;
		}
	}

	/* Обработка запроса */
	protected function checkRequest($data){
		if(empty($data)){
			$this->$textError = 'Ошибка отправки данных';
			return 'error';
		}
		else $this->startMail();
	}

	/* выбор шаблона */
	protected function getTemplate($name, $type){
		switch($type){
			case 'success': $path = 'template/success/'.$name.'.success.crForm.html'; break;
			case 'mail': $path = 'template/success/'.$name.'.mail.crForm.html'; break;
		}
		return $path;
	}
		
	protected function startMail(){
		include ($this->getTemplate('','mail'));
		
		$mailStatus = mail($this->mailTo, $mail['subject'], $mail['message'], $mail['headers']);
		if(!$mailStatus) $this->$textError = 'Ошибка отправки письма';
	}

	protected function filterValues($value, $type){
		if(empty($type)) $type = "text";
		$value = trim($value);
		
		switch($type){
			case "text":
				$value = strip_tags($value);
				$value = htmlspecialchars($value, ENT_QUOTES);
			break;
			case "html":
				$value = htmlspecialchars($value, ENT_QUOTES);		
			break;
			case "email":
				if(!preg_match('#[\+A-Za-z0-9\._-]+@[\+A-Za-z0-9\._-]+\.[A-Za-z]+#', $value)) $value = '';
			break;
			case "phone":
				if(!preg_match('#^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$#', $value)) $value = '';
			break;
			case "login":
				if(!preg_match("#^[0-9]+$#",$value)) $value = '';
			break;
			case "domen":
				if(!preg_match("#^[0-9]+$#",$value)) $value = '';
			break;
			case "int":
				if(!preg_match("#^[0-9]+$#",$value)) $value = '';
			break;
			case "float":
				if(!preg_match($type,$value)) $value = '';
			break;
			default:
				if(!preg_match("#^[0-9]+(\.)?[0-9]*$#",$value)) $value = '';
			break;
		}
		return $value;
	}	
}
?>