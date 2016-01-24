<?
/* подкл. ядра */
include_once ('Core.crForm.php');

Class Template_CrForm extends Cr_Form {
	
	protected function templateFields($srcTemplate, $error = ''){
		$template = '';
		include ($srcTemplate);
		return $template;
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