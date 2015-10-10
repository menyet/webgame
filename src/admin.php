<?php
require_once('configuration.php');
require_once('classes/Npc.php');
require_once('classes/Dialogue.php');
require_once('classes/Quest.php');


echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
			<head>
  				<meta http-equiv="content-type" content="text/html; charset=utf-8" />
					<link rel="stylesheet" href="admin.css" type="text/css" title="style" />

					<title>Wasteland admin</title>
					<style type="text/css">
						body {
							font-size:12px;
							font-family:arial;
						}
					</style>
			</head>
			
<body>
<div>
<a href="admin.php?page=quests">Quests</a> | 
<a href="admin.php?page=dialogues">Dialogues</a> | 
<a href="admin.php?page=npc">NPCs</a> | 
<a href="admin.php?page=items">Items</a> | 
<a href="admin.php?page=places">Places</a> | 
<a href="admin.php?page=encounters">Encounters</a>

<hr/>
';


if (isset($_GET['page'])) {
	switch($_GET['page']) {
		case 'npc': include('admin/npc.php');break;
		case 'dialogues': include('admin/dialogues.php');break;
		case 'quests': include('admin/quests.php');break;
		case 'items': include('admin/items.php');break;
		case 'places': include('admin/places.php');break;
		case 'encounters': include('admin/encounters.php');break;
	}
	
}



echo '
</div>
</body>
</html>';




?>