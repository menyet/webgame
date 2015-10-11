<?php
require_once('classes/Location.php');



if (isset($_POST['set'])) {
	
	$query = 'UPDATE map SET encounter=ROUND(10* SQRT(  POW( x-?, 2 ) + POW( y-?, 2 ) ))';
	$query = 'UPDATE map SET encounter= encounter +     ROUND(           1000/(1+SQRT(  POW( x-?, 2 ) + POW( y-?, 2 ) ) / 3        )            )';
	
	$query = 'UPDATE map SET encounter= encounter + ? * exp( -   (SQRT(  POW( x-?, 2 ) + POW( y-?, 2 ) ) / ?        )            )';
	
	$stmt = MySQLConnection::getInstance()->prepare($query);
	$stmt->bind_param('iiii', $_POST['max'], $_POST['x'], $_POST['y'], $_POST['dist']);
	$stmt->execute();
	
}

if (isset($_POST['unset'])) {
	
	$query = 'UPDATE map SET encounter= encounter * (1-exp(-SQRT(POW(x-?, 2) + POW(y-?,2) ) / ?   ))';
	
	$stmt = MySQLConnection::getInstance()->prepare($query);
	$stmt->bind_param('iii', $_POST['x'], $_POST['y'], $_POST['dist']);
	$stmt->execute();
	
}


if (isset($_POST['clear'])) {
	$query = 'UPDATE map SET encounter= ?';
	
	$stmt = MySQLConnection::getInstance()->prepare($query);
	$stmt->bind_param('i', $_POST['max']);
	$stmt->execute();
	
}


$locations = Location::getLocations();


echo '

<script type="text/javascript">
	function set(x,y) {
		document.getElementById("x").value = x;
		document.getElementById("y").value = y;
	}
</script>

<form action="admin.php?page=encounters" method="post">
	<label for="max">Max</label><input type="text" name="max" id="max" />
	<label for="dist">Distance</label><input type="text" name="dist" id="dist" />
	<label for="x">X</label><input type="text" name="x" id="x" />
	<label for="y">Y</label><input type="text" name="y" id="y" />
	<input type="submit" name="set" value="Set" />
<input type="submit" name="unset" value="Unset" />
<input type="submit" name="clear" value="Clear" />
</form>
';




echo '
<div style="width:2000px;">
	<div style="position:relative;float:left;">
		<img src="images/mapa.jpg" alt="map" style="" />';
		
		
		$places = Place::getPlaces();
		
		foreach($places as $place) {
			echo '
					<img alt="" onclick="showWin(\''.URL.'popups/PopupMap.php?type=place&amp;id='.$place->id.'\', \'Mapa\')" style="cursor: help; border-style: none; position: absolute; left: '.(15*$place->x).'px; top: '.(15*$place->y).'px; width: 17px; height: 17px;" src="'.URL.'images/play_map_dot.gif" title="'.$place->name.'">
					<div onclick="showWin(\''.URL.'popups/PopupMap.php?type=place&amp;id='.$place->id.'\', \'Mapa\')" style="cursor: help;font-size:12px;width:100px;background-color:black;color:white;text-align:center;border:solid 1px #aaaaaa;position:absolute;left: '.(15*$place->x).'px; top: '.(15*$place->y+20).'px;">'.$place->name.'</div>
';
		}

		
		
		
		foreach($locations as $location) {
			//echo '<a href="admin.php?page=encounters&amp;x='.$location->x.'&amp;y='.$location->y.'" style="opacity:'.($location->encounter/1000).';background-color:red;diplay:block;text-align:center;font-size:5px;position:absolute;left:'.(15*$location->x).'px;top:'.(15*$location->y).'px;width:14px;height:14px;border:solid 1px black;border-bottom:solid 1px black"></a>';
			echo '<a href="#" onclick="set('.$location->x.','.$location->y.')" style="opacity:'.($location->encounter/1000).';background-color:red;diplay:block;text-align:center;font-size:5px;position:absolute;left:'.(15*$location->x).'px;top:'.(15*$location->y).'px;width:14px;height:14px;border:solid 1px black;border-bottom:solid 1px black"></a>';
		}	
		
		for ($y = 0; $y < 144; $y++) {
			for ($x = 0; $x < 62; $x++) {
				//echo '<a href="admin.php?page=encounters&amp;x='.$x.'&amp;'.$y.'" style="diplay:block;text-align:center;font-size:5px;position:absolute;left:'.(15*$x).'px;top:'.(15*$y).'px;width:15px;height:15px;border-right:solid 1px black;border-bottom:solid 1px black"></a>';
			}
		}
		
		
		
		echo '

	</div>

</div>
';



?>