<?
define("EMAIL_ADMIN","");

/* Форма "Заказ в 1 клик" */
$formOneClick = new Template_CrForm('oneclick', array("action"=>"", "method"=>"post", "enctype"=>"", "class"=>"form-oneclick", "onsubmit"=>"return sendFormAjax(this);"));
$formOneClick->fields['phone'] = array(
								"NAME" => "input",
								"ATTR" => array("name"=>"oneclick-phone", "type"=>"tel", "class"=>"phone-mask", "placeholder"=>"Ваш телефон", "maxlength"=>"20"),
								"PARAMS" => array("type"=>"phone", "required"=>"Y", "text_error"=>"Вы не указали свой телефон.", "value"=> ''),
								"LABEL" => array("PARAMS" => array("text"=>"Номер телефона", "class_required"=>"required","icon_required"=>'Y',"class_icon"=>'redz')),
							);
$formOneClick->fields['btn'] = array(
								"NAME" => "input",
								"ATTR" => array("name"=>"oneclick-btn", "type"=>"submit", "class"=>"button button2 form-oneclick__btn"),
								"PARAMS" => array("value"=>"Заказать"),
							);
$formOneClick->textStart = "";
$formOneClick->mailTo[] = EMAIL_ADMIN;
?>
