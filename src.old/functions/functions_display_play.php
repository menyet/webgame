<?php

// vykresli minimapu sveta
function world_map($x,$y){
	
$places = Place::getPlaces();

echo '
			<img src="'.URL.'images/play_top.jpg" alt="" style="position: absolute; top: 0px; left: 0px;" />
			<div class="world_map">';

for ($i = -4; $i<=4; $i++) {
	for ($j = -4; $j<=4; $j++) {
		$border = 'none';
		$offset = 0;
		if (($i == 0) && ($j == 0)) {
			$border = 'solid';
			$offset = -1;
		}
		if (($x+$i >= 0) && ($x+$i < MAP_WIDTH) && ($y+$j >= 0) && ($y+$j < MAP_HEIGHT)) {
			echo '<img alt="" class="maptile" style="border-style:'.$border.'; left: '.(92+18*$i+$offset).'px; top: '.(95+18*$j+$offset).'px;" src="'.URL.'images/map/x'.($x+$i).'y'.($y+$j).'.gif" />';
		}
	}
}

$i=0;
foreach($places as $place) {
	if ((abs($place->x - $x)< 5)&&(abs($place->y - $y)<5))
	echo '
		<img alt="" onclick="showDetails('.$place->id.')" style="cursor: help; border-style: none; position: absolute; left: '.(92+18*($place->x-$x)).'px; top: '.(95+18*($place->y - $y)).'px; width: 17px; height: 17px;" src="'.URL.'images/play_map_dot.gif" title="'.$place->name.'" />
';
}


$quests = UsersQuest::getActiveQuests(Player::actualPlayer()->id);

foreach($quests as $quest) {
	$task = $quest->getActualTask();
	if ((abs($task->param1 - $x)< 5)&&(abs($task->param2 - $y)<5)) {
		echo '
			<img alt="" onclick="showDetails('.$place->id.')" style="cursor: help; border-style: none; position: absolute; left: '.(92+18*($task->param1-$x)).'px; top: '.(95+18*($task->param2 - $y)).'px; width: 17px; height: 17px;" src="'.URL.'images/play_map_objective.png" title="'.$quest->quest->title.'" />
		';
	}

	
	
}



/*$query = 'SELECT x,y,title FROM map WHERE title != "" AND x <= ? AND x >= ? AND y <= ? AND y >= ?';

$stmt = MySQLConnection::getInstance()->prepare($query);

$x1 = $x+4;
$x2 = $x-4;

$y1 = $y+4;
$y2 = $y-4;


$stmt->bind_param('iiii',$x1,$x2,$y1,$y2);
$stmt->execute();
$stmt->bind_result($mX,$mY,$mTitle);



while ($stmt->fetch()) {
	echo '<img alt="" style="cursor: help; border-style: none; position: absolute; left: '.(92+18*($mX - $x)).'px; top: '.(95+18*($mY - $y)).'px; width: 17px; height: 17px;" src="images/play_map_dot.gif" title="'.$mTitle.'">';
}

$stmt->close();*/

echo '</div>';
}

// vykresli minimapu aktualnej lokacie
function location_map(){
echo '
	<div class="location_map">
		<img src="'.IMG_PATH.'/map.jpg" usemap="directions" alt="mapa lokácie" style="width: 160px; height: 161px; position: relative; top: 23px; left: 20px;">
	</div>';
}

// vykresli informacie o hracovi
function player_info($player){

$name = $player->name;
$hp = $player->hp;
$hp_max = $player->maxHP;
$datetime = $player->datetime;
$turns = $player->turnsRemaining;
$x = $player->x;
$y = $player->y;
$xp = $player->xp;
$xp_next_level = $player->GetXPNextLevel();

echo 'NAME: '.$name;

echo '<div class="player_info">';
	// ukazovatel zivota
	echo '<div class="player_info_hp_left" style="width:'.floor(160*($hp/$hp_max)).'px;" title="HP: '.$hp.' / '.$hp_max.'"></div>';
	echo '<div class="player_info_hp_right" style="width:'.(floor(160*(1-$hp/$hp_max))+1).'px; left: '.(floor(160*($hp/$hp_max))+20).'px;" title="HP: '.$hp.' / '.$hp_max.'"></div>';
		
	echo '<div style="position: relative; top: 12px; left: 25px;">';
		echo '<b>'.$name.'</b><br/>';
		echo 'HP: '.$hp.' / '.$hp_max.'<br/>';
		echo 'Dátum: '.$datetime.'<br/>';
		echo 'Pozícia: '.$x.','.$y.'<br/>';
		if ($turns > 0)	{
			echo 'Ťahov: <span style="color:green">'.$turns.'</span>';
		} else {
			echo 'Ťahov: <span style="color:red">'.$turns.'</span>';
		}
		echo '<br/>Zlato: '.$player->gold;
	echo '</div>';
	
	// ukazovatel experience
	$cast1 = $xp - $player->GetXPThisLevel();
	$cast2 = $xp_next_level - $xp;
	$celok = $cast1+$cast2;
	echo '<div class="player_info_xp_left" style="width:'.floor(160*($cast1/$celok)).'px;" title="EXP: '.$xp.' / '.$xp_next_level.'"></div>';
	echo '<div class="player_info_xp_right" style="width:'.(floor(160*(1-$cast1/$celok))+1).'px; left: '.(floor(160*($cast1/$celok))+20).'px;" title="EXP: '.$xp.' / '.$xp_next_level.'"></div>';

echo '</div>';

}

// vykresli buttony na ovladanie hry (smery S,J,V,Z,SZ,SV,JZ,JV atd)
function controls(){
echo '
// image mapa aplikovana na kompas
<map id="directions" name="directions">
  <area href="'.$_SERVER['PHP_SELF'].'?move=1" alt="SEVER (alt+NUMPAD 8)" 	 title="SEVER (alt+NUMPAD 8)"        shape="poly" accesskey="8" coords="38,0,28,24,38,22,49,25" />
  <area href="'.$_SERVER['PHP_SELF'].'?move=2" alt="SEVEROVÝCHOD (alt+NUMPAD 9)" title="SEVEROVÝCHOD (alt+NUMPAD 9)" shape="poly" accesskey="9" coords="49,21,64,11,56,28,50,25" />
  <area href="'.$_SERVER['PHP_SELF'].'?move=3" alt="VÝCHOD (alt+NUMPAD 6)" 	 title="VÝCHOD (alt+NUMPAD 6)" 	     shape="poly" accesskey="6" coords="53,29,77,38,56,47,55,38" /> 
  <area href="'.$_SERVER['PHP_SELF'].'?move=4" alt="JUHOVÝCHOD (alt+NUMPAD 3)"   title="JUHOVÝCHOD (alt+NUMPAD 3)"   shape="poly" accesskey="3" coords="56,48,65,64,48,56,51,50" />
  <area href="'.$_SERVER['PHP_SELF'].'?move=5" alt="JUH (alt+NUMPAD 2)"          title="JUH (alt+NUMPAD 2)"          shape="poly" accesskey="2" coords="48,54,39,76,29,52,39,55" />
  <area href="'.$_SERVER['PHP_SELF'].'?move=6" alt="JUHOZÁPAD (alt+NUMPAD 1)"    title="JUHOZÁPAD (alt+NUMPAD 1)"    shape="poly" accesskey="1" coords="29,55,12,65,22,48,27,50" />
  <area href="'.$_SERVER['PHP_SELF'].'?move=7" alt="ZÁPAD (alt+NUMPAD 4)"        title="ZÁPAD (alt+NUMPAD 4)"        shape="poly" accesskey="4" coords="25,48,0,38,26,27,22,38" />
  <area href="'.$_SERVER['PHP_SELF'].'?move=8" alt="SEVEROZÁPAD (alt+NUMPAD 7)"  title="SEVEROZÁPAD (alt+NUMPAD 7)"  shape="poly" accesskey="7" coords="20,28,12,11,29,20,27,26" />
</map>



<div class="left_controls" style="top: 0;">
	<a href="#" class="left_controls" title="Fórum">F<br/>Ó<br/>R<br/>U<br/>M</a>
</div>

<div class="left_controls" style="top: 100px;">
	<a href="#" class="left_controls" title="Manuál">M<br/>A<br/>N<br/>U<br/>Á<br/>L</a>
</div>
';


echo '



	<div class="controls">
		<ul>
			<li><a accesskey="o" href="'.URL.'" title="Znovu-načítanie Stránky"><img src="'.URL.'images/play_button_refresh.gif" alt="" />Obnoviť</a></li>
			<li><a accesskey="q" href="#" onclick="showWin(\''.URL.'popups/PopupQuests.php\', \'Questy a úlohy\')"		title="Questy a Úlohy"><img src="'.URL.'images/play_button_quests.gif" alt="" />Questy</a></li>
			<li><a accesskey="b" href="#" onclick="showWin(\''.URL.'popups/PopupDiary.php\', \'Encyklopédia\')" 	title=""><img src="'.URL.'images/play_button_bestiary.gif" alt="" />Zápisník</a></li>
			<li><a accesskey="m" href="#" onclick="showWin(\''.URL.'map\', \'Mapa sveta\')" 		title="Mapa Sveta"><img src="'.URL.'images/play_button_map.gif" alt="" />Mapa</a></li>
		</ul>
		<ul>
			<li><a accesskey="i" href="#" onclick="showWin(\''.URL.'popups/PopupInventory.php\', \'Inventár\')" 	title="Inventár a Vybavenie"><img src="'.URL.'images/play_button_inventory.gif" alt="" />Inventár</a></li>
			<li><a accesskey="p" href="#" onclick="showWin(\''.URL.'popups/PopupCharacter.php\', \'Postava\')" 		title="Informácie o postave"><img src="'.URL.'images/play_button_character.gif" alt="" />Postava</a></li>
			<li><a accesskey="z" href="#" onclick="showWin(\''.URL.'popups/PopupAbilities.php\', \'Zručnosti\')" 	title="Nebojové Zručnosti"><img src="'.URL.'images/play_button_abilities.gif" alt="" />Zručn.</a></li>
			<li><a accesskey="l" href="'.URL.'logout" 	title="Odhlásenie Hráča"><img src="'.URL.'images/play_button_logout.gif" alt="" />Odhlásiť</a></li>
		</ul>
		<img style="position: relative; top: -50px; left: 470px; padding: 0; margin: 0;" src="'.URL.'images/play_compass.gif" alt="smery" usemap="#directions" />
	</div>';
}


function DisplayItemsOnGround() {
	echo '<div class="items_on_ground">';
	$stmt = MySQLConnection::getInstance()->prepare("SELECT id,print_name FROM items,item_classes WHERE player_id=? AND on_ground='1' AND map_x=? AND map_y=? AND items.class=item_classes.class;");
	
	$player = Player::actualPlayer();

	$x = $player->x;
	$y = $player->y;


	$stmt->bind_param('iii', $_SESSION['id'], $x, $y);
	
	$stmt->execute();
	$stmt->bind_result($line['id'],$line['print_name']);
	
	if ($stmt->num_rows > 0) echo '<p style="font-weight: bold; margin: 20px 0 10px 0;">Na zemi ležia predmety:</p>';
	
	
	while ($stmt->fetch()) {
		$left = '';
		$right = '';
		$r = mysql_query("SELECT print_name,position FROM item_modifiers,mod_classes WHERE item_id='".$line['id']."' AND item_modifiers.class=mod_classes.class;",DATABASE);
		while ($l = mysql_fetch_assoc($r)) {
			if ($l['position'] == 'predpona') {
				$left = $l['print_name'];
			} else {
				$right = $l['print_name'];
			}
		}
		$item = $left.' '.$line['print_name'].' '.$right;
		if (($left != '') || ($right != '')) $item = '<span class="magic">'.$item.'</span>';
		echo '<p>';
		echo '<a href="./index.php?action=pick_up_item&amp;id='.$line['id'].'">'.$item.'</a>';
		echo '</p>';
	}
	
	$stmt->close();
	
	echo '</div>';
}

function DisplayMessages() {
	if (isset($GLOBALS['messages'])) {
		foreach ($GLOBALS['messages'] as $key => $value) {
			echo '<div class="message">'.$value.'</div>';
		}
	}
}

function AddMessage($msg) {
	if (!isset($GLOBALS['messages'])) $GLOBALS['messages'] = Array();
	array_push($GLOBALS['messages'],$msg);
}



?>
