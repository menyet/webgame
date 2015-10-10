<?php
/*define("DB_HOST","sql13.dnsserver.eu");		// adresa na MySQL server
define("DB_USER","db16133xwaste");		// uzivatel databazy
define("DB_PASSWORD","Wasteland28");	// heslo na prihlasenie k DB
define("DB_NAME","db16133xwaste");		// meno databazy
define('URL', 'http://wasteland.mandli.sk/'); // URL stranky */

define("DB_HOST","localhost");		// adresa na MySQL server
define("DB_USER","root2");		// uzivatel databazy
define("DB_PASSWORD","login");	// heslo na prihlasenie k DB
define("DB_NAME","wasteland");		// meno databazy
define('URL', 'http://wasteland/'); // URL stranky */





/*
define("DB_HOST","sql6.dnsserver.eu");		// adresa na MySQL server
define("DB_USER","db15257xwaste");		// uzivatel databazy
define("DB_PASSWORD","eatwgeel28");	// heslo na prihlasenie k DB
define("DB_NAME","db15257xwaste");		// meno databazy

define('URL', 'http://wasteland.mandli.sk/'); // URL stranky */


require_once('classes/mysql.php');

MySQLConnection::setup(DB_HOST,DB_NAME,DB_USER,DB_PASSWORD);

require_once('classes/Place.php');



// pripojenie sa k databaze, definovanie DB-linku ako konstanty DATABASE a urcenie jazykoveho kodovania 

/*define("DATABASE",mysql_pconnect(DB_HOST,DB_USER,DB_PASSWORD));
mysql_select_db(DB_NAME,DATABASE);
mysql_query("SET NAMES 'UTF8'",DATABASE);*/

define("MAP_WIDTH",62);				// sirka mapy
define("MAP_HEIGHT",144);			// vyska mapy
define("TURN_LENGTH",600); 			// dlzka jedneho tahu v sekundach
define("MAX_TURNS",1000);			// maximalny pocet neodohranych tahov

define("START_LOCATION_X",20);		// startovacia pozicia na mape - X
define("START_LOCATION_Y",20);		// startovacia pozicia na mape - Y
define("START_TAHY",500);			// pocet tahov do zaciatku hry

define("ENCOUNTER_CHANCE",15);		// sanca na nahodne stretnutie na mape v %
?>