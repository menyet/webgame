<?php


function sellValue($merchant) {
	$max = 90;
	$min = 30;
	return (sin($merchant/200*3.14/2)*($max - $min) + $min)/100;
}

function buyValue($merchant) {
	$max = 200;
	$min = 110;
	return ($max - sin($merchant/200*3.14/2)*($max - $min))/100;
}




?>