<?php

class PlayerAttributes {
  private $playerId; 
  
  private $critical_chance;
  private $perk_multiplier;
  
  private $base_armor;
  private $base_action_points;
  private $base_nosnost;
  private $base_replenish_life;
  
  private $mod_chance_day;
  private $mod_chance_night;
  private $mod_skillpoints_per_level;
  
  private $attr_strength;
  private $attr_endurance;
  private $attr_intelligence;
  private $attr_speed;
  private $attr_charisma;
  private $attr_luck;
  
  private $ability_melee;
  private $ability_ranged;
  private $ability_human;
  private $ability_magic_attack;
  private $ability_magic_defense;
  private $ability_merchant;
  private $ability_thief;
  private $ability_gambling;
  private $ability_healing;
  private $ability_alchymist;
  
  
  public function __construct($playerId) {
    $this->playerId = $playerId;
    $query = 'SELECT  critical_chance, 
                      perk_multiplier, 
                      base_armor, 
                      base_action_points, 
                      base_nosnost, 
                      base_obnova_zdravia, 
                      mod_sanca_den, 
                      mod_sanca_noc, 
                      mod_skillpointy_za_level, 
                      atribut_sila, 
                      atribut_vydrz, 
                      atribut_inteligencia, 
                      atribut_rychlost, 
                      atribut_charizma, 
                      atribut_stastie, 
                      zrucnost_chladne_zbrane, 
                      zrucnost_strelne_zbrane, 
                      zrucnost_cudzinecke_zbrane, 
                      zrucnost_utocna_magia, 
                      zrucnost_obranna_magia, 
                      zrucnost_obchodovanie, 
                      zrucnost_kradnutie, 
                      zrucnost_gamblovanie, 
                      zrucnost_liecenie, 
                      zrucnost_vyroba_lektvarov 
              FROM players WHERE id = ?';
                      
    $stmt = MySQLConnection::getInstance()->prepare($query);
    
    $stmt->bind_param('i',$playerId);
    $stmt->execute();
    
    $stmt->store_result();
    $stmt->bind_result( $this->critical_chance, $this->perk_multiplier, 
                        $this->base_armor, $this->base_action_points, $this->base_nosnost, $this->base_replenish_life, 
                        $this->mod_chance_day, $this->mod_chance_night, $this->mod_skillpoints_per_level, 
                        $this->attr_strength, $this->attr_endurance, $this->attr_intelligence, $this->attr_speed, $this->attr_charisma, $this->attr_luck, 
                        $this->ability_melee, $this->ability_ranged, $this->ability_human, $this->ability_magic_attack, $this->ability_magic_defense, 
                        $this->ability_merchant, $this->ability_thief, $this->ability_gambling, $this->ability_healing, $this->ability_alchymist);
    $stmt->fetch();
    $stmt->close();

    
  }
  
  
  public function __get($name) {
    switch($name) {
      case 'critical_chance':             return $this->critical_chance;
      case 'perk_multiplier':             return $this->perk_multiplier;
      case 'base_armor':                  return $this->base_armor;
      case 'base_action_points':          return $this->base_action_points;
      case 'base_nosnost':                return $this->base_nosnost;
      case 'base_replenish_life':         return $this->base_replenish_life;
      case 'mod_chance_day':              return $this->mod_chance_day;
      case 'mod_chance_night':            return $this->mod_chance_night;
      case 'mod_skillpoints_per_level':   return $this->mod_skillpoints_per_level;
      
      
      case 'strength':
      case 'attr_strength':               return $this->attr_strength;
      
      case 'endurance':
      case 'attr_endurance':              return $this->attr_endurance;
      
      case 'intelligence':
      case 'attr_intelligence':           return $this->attr_intelligence;
      
      case 'speed':
      case 'attr_speed':                  return $this->attr_speed;
      
      case 'charisma':
      case 'attr_charisma':               return $this->attr_charisma;
      
      case 'luck':
      case 'attr_luck':                   return $this->attr_luck;
      
      
      case 'base_melee':
      case 'ability_melee':               return $this->ability_melee;
      
      case 'base_ranged':
      case 'ability_ranged':              return $this->ability_ranged;
      
      case 'base_alien':
      case 'ability_human':               return $this->ability_human;
      
      case 'base_magic_attack':
      case 'ability_magic_attack':        return $this->ability_magic_attack;
      
      case 'base_magic_defense':
      case 'ability_magic_defense':       return $this->ability_magic_defense;
      
      case 'base_merchant':
      case 'ability_merchant':            return $this->ability_merchant;
      
      case 'base_thief':
      case 'ability_thief':               return $this->ability_thief;
      
      case 'base_gambling':
      case 'ability_gambling':            return $this->ability_gambling;
      
      case 'base_healing':
      case 'ability_healing':             return $this->ability_healing;
      
      case 'base_alchymist':
      case 'ability_alchymist':           return $this->ability_alchymist;
      default: throw new Exception('Class PlayerAttributes does not have property '.$name.'<br/>');
    }
  }
  
  public function __set($name, $value) {
    switch($name) {
      case 'strength':               $this->attr_strength = $value;break;
      case 'endurance':              $this->attr_endurance = $value;break;
      case 'intelligence':           $this->attr_intelligence = $value;break;
      case 'speed':                  $this->attr_speed = $value;break;
      case 'charisma':               $this->attr_charisma = $value;break;
      case 'luck':                   $this->attr_luck = $value;break;
      default: throw new Exception('Object of class PlayerAttributes has no member `'.$name.'`');
    }
  }
  
  public function saveAttributes() {
    $query = 'UPDATE players SET
                atribut_sila=?,
                atribut_vydrz=?,
                atribut_inteligencia=?,
                atribut_rychlost=?,
                atribut_charizma=?,
                atribut_stastie=?,
                register_state=3
              WHERE id=? AND register_state=2';
              
    $stmt = MySQLConnection::getInstance()->prepare($query);
    
    $stmt->bind_param('iiiiiii', $this->attr_strength, $this->attr_endurance, $this->attr_intelligence, $this->attr_speed, $this->attr_charisma, $this->attr_luck, $this->playerId);
    $stmt->execute();
    $stmt->close();
    
  }
  
  
  
  
  
  
}

?>