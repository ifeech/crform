<?
foreach($this->fields as $name=>$tag){
	$template .= '<p>';
	if(!empty($tag['LABEL'])){
		$template .= '<label '.$this->parseAttr($tag['LABEL']['ATTR']).'>';
		if($tag['PARAMS']['required'] == 'Y'){
			$template .= '<span class="'.$tag['LABEL']['PARAMS']['class_required'].'">'.$tag['LABEL']['PARAMS']['text'].'</span>';
			if($tag['LABEL']['PARAMS']['icon_required'] == 'Y') $template .= '<span class="'.$tag['LABEL']['PARAMS']['class_icon'].'">*</span>';
		}
		else $template .= $tag['LABEL']['PARAMS']['text'];
		$template .= '</label>';
		if($tag['PARAMS']['newline'] == 'Y') $template .= '<br />';
	}				
	switch ($tag['NAME']) {
		case 'input': $template .= '<input '.$this->parseAttr($tag['ATTR'], $name).' value="'.$tag['PARAMS']['value'].'">';break;
		case 'textarea': $template .= '<textarea '.$this->parseAttr($tag['ATTR'], $name).'>'.$tag['PARAMS']['value'].'</textarea>'; break;
	}
	$template .= '</p>';
}
?>