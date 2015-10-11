<?php

// phpinfo();exit;
session_start();
session_regenerate_id();

require 'security.php';
require 'configuration.php';
require 'classes/class_page.php';

require 'classes/UsersQuest.php';

require_once('classes/Location.php');

require_once('functions/formulas.php');

function __autoload($name) {
    require_once('classes/'.$name.'.php');
    //throw new MissingException("Unable to load $name.");
}


class Wasteland {
    private $player = null;

    public function __construct() {
        	
        $uri = $_SERVER['REQUEST_URI'];
        	
        $path = explode("/", $uri);
        	
        $this->action = $path[1];
        	
        	
        if ($this->action == 'logout') {
            session_unset();
        }

        	
        $this->sql = MySQLConnection::getInstance();

        if (isset($_POST['login'])) {
            Player::login($_POST['login_name'], $_POST['login_password']);
        }



    }


    public function execute() {


        	
        if (isset($_SESSION['id'])) {					// ak je aktivna session, tak vytvori triedu LOGGED_PAGE
            $this->player = Player::actualPlayer();

            if ($this->player->registerState < 10) {
                $this->register($this->player->registerState);
                return;
            }



            if ($this->player->shop != '') {
                if (isset($_GET['tab']) && ($_GET['tab'] == 'exit')) {
                    $this->player->shop = '';
                    $this->player->save();
                }
            }




            $dialogueNPC = $this->player->dialogueNPC;

            if ($dialogueNPC != '') {
                $dialogue = Dialogue::getDialogue($this->player->id, $dialogueNPC);
                	
                if (isset($_GET['dialogueresponse'])) {
                    $dialogue->selectResponse($_GET['dialogueresponse']);
                    header('location:'.URL);
                    exit(0);

                }
            } elseif ($this->player->isInFight()) {
            } else {
                $this->prepareLocation();
            }


            $dialogue = $this->player->dialogueNPC;


            if ($this->player->shop != '') {
                $shopPage = new ShopPage();
                $shopPage->display();
            } else if ($dialogue != '') {
                $this->dialoguePage();
            } elseif ($this->player->isInFight()) {
                $this->fightPage();
            } else {
                $this->showLocation();
            }

        } else {
            $this->notloggedin();
        }

        	
    }


    private function notloggedin() {
        	
        if ($this->action == 'story') {			// ak $action je 'story', tak vytvori triedu STORY_PAGE
            require 'classes/class_story_page.php';
            $son = new STORY_PAGE();
        } elseif ($this->action == 'manual') {	// ak $action je 'manual', tak  vytvori triedu MANUAL_PAGE
            require 'classes/class_manual_page.php';
            $son = new MANUAL_PAGE();
        } elseif ($this->action == 'top') {	// ak $action je 'top', tak  vytvori triedu TOP_PAGE
            require 'classes/class_top_page.php';
            $son = new TOP_PAGE();
        } elseif ($this->action == 'register') {
            require_once('classes/register_page.php');
            $page = new RegisterPage();
            $page->display();

        } else {								// ak je $action akekolvek ine, tak vytvori triedu TITLE_PAGE
            require 'classes/class_title_page.php';
            $son = new TITLE_PAGE();
            $son->display();
        }
    }




    private function prepareLocation() {
        if (isset($this->action) && ($this->action == 'begindialogue')) {
            $this->player->dialogueNPC = $_GET['npc'];

            $this->player->save();

            $dialogue = Dialogue::getDialogue($this->player->id, $this->player->dialogueNPC);
            if ($dialogue == null) {
                $dialogue = Dialogue::newDialogue($this->player->id, $this->player->dialogueNPC);
            }



        }
        	
        	
        	
        if (isset($_GET['move']) && ($this->player->turnsRemaining > 0)){
            try {
                switch ($_GET['move']){
                    case 1:
                        $this->player->move(0,-1);
                        break;
                    case 2:
                        $this->player->move(1,-1);
                        break;
                    case 3:
                        $this->player->move(1,0);
                        break;
                    case 4:
                        $this->player->move(1,1);
                        break;
                    case 5:
                        $this->player->move(0,1);
                        break;
                    case 6:
                        $this->player->move(-1,1);
                        break;
                    case 7:
                        $this->player->move(-1,0);
                        break;
                    case 8:
                        $this->player->move(-1,-1);
                        	
                        break;
                }
                	
                $location = Location::getLocation($this->player->x, $this->player->y);
                	
                $task = $location->getUnavoidableTask();
                	
                if ($task && ($task->type == TASK::TYPE_KILL)) {

                    $stmt = MySQLConnection::getInstance()->prepare('INSERT INTO fights SET fight_npc=?, fight_creature_hp=?, fight_player=?, fight_history=?, fight_player_ap=?, fight_enemy_ap=? ');
                    $playerId = $this->player->id;
                    $npcName = $task->param3;
                    $creatureHP = 100;
                    $history = serialize(array());
                    $playerAP = 10;
                    $enemyAP = 10;

                    $creatureName = $task->param1;
                    	
                    $stmt->bind_param('siisii',$npcName, $creatureHP, $playerId, $history, $playerAP, $enemyAP);
                    $stmt->execute();
                    $stmt->close();

                    return;
                }
                	
                	
                	
                	
                	
                	
                $enemy = $location->getEnemy();
                	
                if (($enemy != null)) {
                    $stmt = MySQLConnection::getInstance()->prepare('INSERT INTO fights SET fight_npc=?, fight_creature_hp=?, fight_player=?, fight_history=?, fight_player_ap=?, fight_enemy_ap=? ');
                    $playerId = $this->player->id;
                    $creatureId = 1;
                    $creatureHP = 100;
                    $history = serialize(array());
                    $playerAP = 10;
                    $enemyAP = 10;
                    	
                    $stmt->bind_param('iiisii',$creatureId, $creatureHP, $playerId, $history, $playerAP, $enemyAP);
                    $stmt->execute();
                    $stmt->close();
                }

                	


                //header('location:'.URL);
                //exit();
            } catch(playerOverLoadedException $e) {
            }
        }
        	
        	
        	
        	
    }




    private function showLocation() {
        $place = Place::getPlaceOnCoords($this->player->x, $this->player->y);
        if ($place == null) {
            require 'classes/NormalPage.php';
            $page = new Normal_Page();
            $page->display();
        } else {
            require 'classes/CityPage.php';
            $page = new CityPage();
            $page->display();
        }
    }




    private function dialoguePage() {
        require 'classes/dialoguepage.php';
        $page = new DialoguePage();
        $page->display();
    }

    private function fightPage() {
        require 'classes/FightPage.php';
        $page = new FightPage();
        $page->display();
    }


    public function register($state) {
        switch($state) {
            case 0:
                require_once('classes/register_select_race.php');
                $page = new registerSelectRace();
                $page->display();
                break;
            case 1:
                require_once('classes/register_select_personal_attributes.php');
                $page = new registerSelectPersonalAttributes();
                $page->display();
                break;
            case 2:
                require_once('classes/register_select_attributes.php');
                $page = new RegisterSelectAttributes();
                $page->display();
                break;
            case 3:
                require_once('classes/register_select_skills.php');
                $page = new RegisterSelectSkills();
                $page->display();
                break;
            case 4:
                require_once('classes/register_select_spells.php');
                $page = new RegisterSelectSpells();
                $page->display();
                break;
        }
    }
}







$page = new Wasteland();
$page->execute();
echo Connection::$queries;
?>
