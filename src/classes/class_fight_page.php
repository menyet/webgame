<?php
class FIGHT_PAGE extends PLAY_PAGE
{

	// konstruktor triedy
    function __construct(){
      $this->display('fight.css');
      //$this->display("play_page.css");
    }

    function displayBody() {
      $player = playerManager::getInstance()->getPlayer();
      $pid = $player->id;

      $db = MySQLConnection::getInstance();

      $query = 'SELECT f.fight_id,f.fight_creature,f.fight_creature_hp, c.creature_name, c.creature_class, c.creature_image, f.fight_creature_strength, f.fight_creature_defense_melee FROM fights f, creature_types c WHERE fight_player = ? AND c.creature_id = f.fight_creature';
      $stmt = $db->prepare($query);
			
			echo $db->error;

      $stmt->bind_param('i',$pid);
      $stmt->execute();
      $stmt->bind_result($id, $creature, $hp, $creature_name, $craeture_class, $creature_image, $creature_strength, $creature_defense_melee);
      $stmt->fetch();
      $stmt->close();
			
			
			$messages = array();
			
			$defense_bonus = 0;

      if (!isset($_GET['action'])) {

      } elseif ($_GET['action']=='attack') {
				$hp = $hp - $player->strength;
				
				$messages[] = 'Zaútočil si na obludu a spôsobil jej zranenie X bodov.';
				
				if ($hp <= 0) {
					$hp = 0;
					$messages[] = 'Zabil si obludu.';
					
					$query = 'DELETE FROM fights WHERE fight_id = ?';
					$stmt = $db->prepare($query);
					$stmt->bind_param('i', $id);
					$stmt->execute();
				} else {
					$query = 'UPDATE fights SET fight_creature_hp = ? WHERE fight_id = ?';
					$stmt = $db->prepare($query);
					$stmt->bind_param('ii', $hp, $id);
					$stmt->execute();
				}
				

      } elseif ($_GET['action']=='defend') {
				$defense_bonus = 30;
				$messages[]  = 'Vďaka obrannej pozícii znižuješ zranenie spôsobené nepriateľom o 30%';
      } elseif ($_GET['action']=='cast') {

      } elseif ($_GET['action']=='potion') {

      }
			
			$damage_by_creature = 10*$creature_strength / ( $player->GetArmor() * (100+$defense_bonus)/100 );
			$damage_by_creature = round($damage_by_creature,0);
			$messages[] = 'Nepriateľ ti spôsobil zranenia '.$damage_by_creature.' bodov';
			
			
			
			
			
			
			$player->hp = $player->hp - $damage_by_creature;
			$player->save();


			



      




      echo '
      <div id="player">
				<div class="name">'.$player->name.' '.$player->hp.'/'.$player->hp_max.'</div>
				<img style="width:200px;" src="'.URL.'images/creatures/hunter.jpg" />
      </div>



      <div id="enemy">
				<div class="name">'.$creature_name.' '.$hp.'</div>
				<img style="width:200px;" src="'.URL.'images/creatures/'.$creature_image.'" />

      </div>

	<div id="attack">
	  <h3>Akcie</h3>
	  <a href="'.URL.'attack">Útok ('.$player->strength.')</a> | <a href="'.URL.'defend">Obrana</a>
		
	</div>

	<div id="magic">
	  <h3>Mágia</h3>
	</div>



			

      <div class="clearer">';
			foreach ($messages as $message) {
				echo '<p>'.$message.'</p>';
			}
			
			echo '
</div>
  ';
    }

}

?>