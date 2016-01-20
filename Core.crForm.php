<?
abstract Class Cr_Form{
	public $fields;	//поля для формы
	public $textStart;
	public $textError = "Заполните обязательныые поля";
	public $textErrorSend = "Ошибка отправки письма!";
	public $textSuccess = "Форма отправлена!";
	public $mailTo;
	public $srcTemplate;
	
	protected $form;
	protected $status;
	protected $template;

	function __construct($nameForm, $attrForm, $titleForm = '', $clearDefError = 'Y'){
		$this->form['NAME'] = $nameForm;
		$this->form['ATTR'] = $attrForm;
		$this->form['TITLE'] = $titleForm;
		if($clearDefError == 'Y') $this->textError = '';
	}
	
	public function startCrForm($request, $type = 'mail', $showAfterSend = 'Y'){
		if($this->getUrlParams($request)) $this->status = 'start';

		//отправка
		if($this->status == 'success'){
			if($this->startMail()){
				if($showAfterSend != 'N') $this->resetForm();
				else $this->templateSuccess(); 
			}
		}
		
		if($this->status != 'success' || $showAfterSend != 'N') $this->templateForm();
		return $this->template;
	}

	public function startAjaxCrForm($request, $type = 'mail'){
		if($this->getUrlParams($request)) $this->status = 'start';

		//отправка
		if($this->status == 'success') $this->startMail();
		
		$this->templateAjax();
		return $this->template;
	}

	/* Обработка запроса */
	protected function getUrlParams($arrParams){
		if(empty($arrParams)) return 1;
		if(empty($arrParams['form-name'])) return 1;
		if($arrParams['form-name'] != $this->form['NAME']) return 1;
		foreach($this->fields as &$tag){
			if(empty($tag['PARAMS']['value'])) $tag['PARAMS']['value'] = $this->filterValues($arrParams[$tag['ATTR']['name']], $tag['PARAMS']['type']);
			if($tag['PARAMS']['required'] == 'Y' && $tag['PARAMS']['value'] == ''){
				$tag['PARAMS']['error'] = 'Y';
				$this->status = 'error#params';
			}
			else $tag['PARAMS']['error'] = 'N';
		}
		if($this->status != 'error#params') $this->status = 'success';
		return 0;
	}

	protected function startError(){
		$tamplate = '';
		switch ($this->status){
			case 'start':
				$text = $this->textStart;
				$status = 'start';
			break;
			case 'success':
				$text = $this->textSuccess;
				$status = 'success';
			break;
			case 'error#send':
				$text = $this->textErrorSend;
				$status = 'error';
			break;
			case 'error#params':
				$status = 'error';
				if(!empty($this->textError)) $text = $this->textError;
			break;
		}

		if($status == 'error' && empty($text)){
			foreach($this->fields as $tag){
				if($tag['PARAMS']['error'] == 'Y' && !empty($tag['PARAMS']['text_error'])){
					$tamplate .= $this->templateError($tag['PARAMS']['text_error'], $status, $this->addTemplate($this->srcTemplate['error'], 'error'));
				}
			}			
		}
		else $tamplate = $this->templateError($text, $status, $this->addTemplate($this->srcTemplate['error'], 'error'));

		return $this->templateError($tamplate, 'wrapper', $this->addTemplate($this->srcTemplate['error'], 'error'));
	}	
	
	protected function parseAttr($arr, $name = ''){
		$str = '';
		if(empty($arr)) return $str;

		if(!empty($name) && !empty($this->fields[$name]['PARAMS']['error'])){
			$fieldStausError = $this->fields[$name]['PARAMS']['error'];
			$fieldClassError = ' '.$this->fields[$name]['PARAMS']['class_error'];
			$fieldClassSucces = ' '.$this->fields[$name]['PARAMS']['class_success'];
		}
		foreach ($arr as $k=>$v){
			if($k == 'class' && isset($fieldStausError)){
				$class = $v;
				continue;
			}
			$str .= "$k=\"$v\"".' ';
		}
		if(isset($fieldStausError)){
			if($fieldStausError == 'Y') $class .= ' '.$fieldClassError;
			else if($fieldStausError == 'N') $class .= ' '.$fieldClassSucces;
			$str .= "class=\"$class\"";
		}
		return $str;
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
	
	/* Очистка value */
	protected function resetForm(){
		foreach($this->fields as &$tag){
			if($tag['ATTR']['type'] == 'submit') continue;
			$tag['PARAMS']['value'] = '';
		}		
	}

	protected function addTemplate($src, $type){
		if(!empty($src) && is_file ($src)) return $src;

		$path = __DIR__.'/template/'.$type.'/'.$this->form['NAME'].'.'.$type.'.crForm.php';
		if(is_file($path)) return $path;

		$default = __DIR__.'/template/'.$type.'/default.'.$type.'.crForm.php';
		if(is_file ($default)) return $default;
		else return '';
	}
	
	protected function templateForm(){
		$this->template = '';
		$error = '';
		if(!empty($this->form['NAME'])){
			$error = $this->startError(); //ошибки формы
			$this->template .= '<form '.$this->parseAttr($this->form['ATTR']).'>';
			$this->template .= '<input type="hidden" name="form-name" value='.$this->form['NAME'].'>';

			$this->template .= $this->templateFields($this->addTemplate($this->srcTemplate['form'], 'form'), $error); //шаблон полей
			$this->template .= '</form>';
		}
		else $this->template .= 'Форма не найдена';
	}

	protected function templateAjax(){
		$this->template = '';
		if(!empty($this->form['NAME'])){
			if($this->status != 'start') $this->template .= $this->startError(); //ошибки формы
		}
		else $this->template .= 'Форма не найдена';
	}
	
	protected function startMail(){
		foreach($this->fields as $var=>$tag){
			$fields[$var] = $tag['PARAMS']['value'];
		}
		$valueMail = $this->templateMail($fields, $this->addTemplate($this->srcTemplate['mail'], 'mail'));
		if($this->sendMail($valueMail)) return 1;
		
		$this->status = 'error#send';
		return 0;
	}
	
	/* Отправка письма */
	abstract protected function sendMail($valueMail);
	
	/* Шаблон письма */
	abstract protected function templateMail($fields, $srcTemplate);
	
	/* Шаблон полей формы */
	abstract protected function templateFields($srcTemplate, $error);

	/* Шаблон ошибок */
	abstract protected function templateError($type, $text, $srcTemplate);

	/* Шаблон успешной отправки (без вывода формы) */
	abstract protected function templateSuccess();
	
	/*** ? абстр ***/	
	protected function insertDb(){
	}
	
	protected function updateDb(){
	}
	/*** ***/
}
?>