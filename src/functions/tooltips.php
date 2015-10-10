<?php



function CreateToolTip($num,$title,$text){
		
	$html_code = '<div class=tooltip>';
	$html_code = $html_code.'<div class=tooltip_title>'.$title.'</div> ';
	$html_code = $html_code.$text;
	$html_code = $html_code.'</div>';
		
	echo '<script type="text/javascript">';
	echo 'Text['.$num.']=["","'.$html_code.'"];';
	echo '</script>';
}
	
	// vlozi do tagu prislusny tooltip
function UseToolTip($num){
	return 'onMouseOver="stm(Text['.$num.'],Style[0])" onMouseOut="htm()"';
}

?>