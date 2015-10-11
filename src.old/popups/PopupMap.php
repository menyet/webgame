<?php

require '../security.php';
require '../classes/class_external_script.php';
//require '../configuration.php';

class MAP
{
	function Display() {

	$sql = MySQLConnection::getInstance();

	if (isset($_GET['id'])) {
		$place = Place::getPlace($_GET['id']);
		echo $place->name;
	} else {
	
		$places = Place::getPlaces();
		echo '
			<div style="position:relative;width:880px;height:477px;background-image:url('.URL.'images/map.png);padding-top:23px;padding-left:20px;">
				<div style="position:relative;width:460px;height:455px;overflow:scroll;">
					<img src="'.URL.'images/mapa.bmp" />	
		';
				$i=0;
				foreach($places as $place) {
					echo '
					<img alt="" onclick="selectPlace(\''.URL.'popups/PopupMap.php?type=place&amp;id='.$place->id.'\')" style="cursor: help; border-style: none; position: absolute; left: '.(15*$place->x).'px; top: '.(15*$place->y).'px; width: 17px; height: 17px;" src="'.URL.'images/play_map_dot.gif" title="'.$place->name.'">
					<div onclick="selectPlace(\''.URL.'popups/PopupMap.php?type=place&amp;id='.$place->id.'\')" style="cursor: help;font-size:12px;width:100px;background-color:black;text-align:center;border:solid 1px #aaaaaa;position:absolute;left: '.(15*$place->x).'px; top: '.(15*$place->y+20).'px;">'.$place->name.'</div>
					';
				}

		echo '
			</div>
			<div id="map_right" style="position:absolute;left:510px;top:30px;width:360px;height:440px;">';
				if (isset($_GET['id'])) {
					$place = Place::getPlace($_GET['id']);
					echo $place->name;
				}
				echo '
				</div>
		</div>';
	}
		
		
		
		
		
		
		
		
		
		
	
	}

	function __construct() {
		session_start();
		$this->Display();
	}

}

	// vytvorenie objektu skriptu
	$page = new MAP();

?>