<?php

if (isset($_POST['newplace'])) {
	Place::newPlace($_POST['x'],$_POST['y'],$_POST['name'],0,0,$_POST['description']);
}

if (isset($_POST['updateplace'])) {
	$place = Place::getPlace($_POST['updateplace']);
	$place->x = $_POST['x'];
	$place->y = $_POST['y'];
	$place->name = $_POST['name'];
	$place->description = $_POST['description'];
	$place->save();
}

if (isset($_POST['deleteplace'])) {
	$place = Place::getPlace($_POST['deleteplace']);
	$place->delete();
}

$places = Place::getPlaces();

echo '
<div style="width:2000px;">
	<div style="position:relative;float:left;">
		<img src="images/mapa.jpg" alt="map" style="" />
		';
		
		for ($y = 1; $y < 144; $y= $y+3) {
			for ($x = 1; $x < 61; $x= $x + 3) {
				echo '<div style="text-align:center;font-size:10px;position:absolute;left:'.(15*$x-6).'px;top:'.(15*$y-6).'px;width:27px;height:27px;border:solid 1px black">'.$x.' '.$y.'</div>';
			}
		}
		
		foreach($places as $place) {
			echo '
					<img alt="" onclick="showWin(\''.URL.'popups/PopupMap.php?type=place&amp;id='.$place->id.'\', \'Mapa\')" style="cursor: help; border-style: none; position: absolute; left: '.(15*$place->x).'px; top: '.(15*$place->y).'px; width: 17px; height: 17px;" src="'.URL.'images/play_map_dot.gif" title="'.$place->name.'">
					<div onclick="showWin(\''.URL.'popups/PopupMap.php?type=place&amp;id='.$place->id.'\', \'Mapa\')" style="cursor: help;font-size:12px;width:100px;background-color:black;color:white;text-align:center;border:solid 1px #aaaaaa;position:absolute;left: '.(15*$place->x).'px; top: '.(15*$place->y+20).'px;">'.$place->name.'</div>
';
		}
		
		
		echo '

	</div>

	<div class="admin_table" style="width:auto;">
	<div>
		<div>Id</div>
		<div>X</div>
		<div>Y</div>
		<div>Name</div>
		<div>Quest</div>
		<div>Image</div>
		<div>Description</div>
		<div>Save</div>
	</div>';
	
	
	foreach($places as $place) {
		echo '
	<form action="admin.php?page=places" method="post">
		<div>'.$place->id.'</div>
		<div><input type="text" name="x" value="'.$place->x.'" /></div>
		<div><input type="text" name="y" value="'.$place->y.'" /></div>
		<div><input type="text" name="name" value="'.$place->name.'" /></div>
		<div>Quest</div>
		<div>Image</div>
		<div><input type="text" name="description" value="'.$place->description.'" /></div>
		<div><button type="submit" name="updateplace" value="'.$place->id.'">Save</button></div>
		<div><button type="submit" name="deleteplace" value="'.$place->id.'">Delete</button></div>
	</form>';
		
		
	}
	
	
	echo '
	
	<form action="admin.php?page=places" method="post">
		<div>&nbsp;</div>
		<div><input type="text" name="x" value="0" /></div>
		<div><input type="text" name="y" value="0" /></div>
		<div><input type="text" name="name" value="" /></div>
		<div>Quest</div>
		<div>Image</div>
		<div><input type="text" name="description" value="" /></div>
		<div><button type="submit" name="newplace" value="newplace">New place</button></div>
	</form>';





echo '
	</div>

</div>
';



?>