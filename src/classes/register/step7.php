<?php

			// posledna kontrola - kvoli refresh buttonu
		if ($this->CheckForm(6) == '') {
						
			$id = $this->GetPlayerID();
			$login = $_POST['login'];
			$meno_postavy = $_POST['meno_postavy'];
			$pohlavie = $_POST['pohlavie'];
			$race = $_POST['race'];
			$heslo = md5($_POST['heslo1']);
			$start_x = START_LOCATION_X;
			$start_y = START_LOCATION_Y;
			$hp = ($_POST['vydrz']*7 + $_POST['sila']);
			$turns_remaining = START_TAHY;
			$lastaction = time();
			$critical_chance = $_POST['stastie'];
			$perk_multiplier = 3;
				if (isset($_POST['trait_skuseny']) && ($_POST['trait_skuseny'] == 'on')) $perk_multiplier = 4;
			$base_armor = 10;
				if (isset($_POST['trait_mutant']) && ($_POST['trait_mutant'] == 'on')) $base_armor += 10;
				if (isset($_POST['trait_tenka_postava']) && ($_POST['trait_tenka_postava'] == 'on')) $base_armor += 10;
				if (isset($_POST['trait_kamikadze']) && ($_POST['trait_kamikadze'] == 'on')) $base_armor -= 10;
			$base_action_points = $_POST['rychlost'];
				if (isset($_POST['trait_kamikadze']) && ($_POST['trait_kamikadze'] == 'on')) $base_action_points++;
			$base_nosnost = $_POST['sila']*10;
				if (isset($_POST['trait_tenka_postava']) && ($_POST['trait_tenka_postava'] == 'on')) $base_nosnost -= 15;
			$base_obnova_zdravia = $_POST['vydrz']+floor($_POST['liecenie'] / 20);
				if (isset($_POST['trait_oziareny']) && ($_POST['trait_oziareny'] == 'on')) $base_obnova_zdravia -= 2;
			$mod_sanca_den = 0;
				if (isset($_POST['trait_vampir']) && ($_POST['trait_vampir'] == 'on')) $mod_sanca_den -= 20;
			$mod_sanca_noc = 0;
				if (isset($_POST['trait_vampir']) && ($_POST['trait_vampir'] == 'on')) $mod_sanca_noc += 20;
				if (isset($_POST['trait_oziareny']) && ($_POST['trait_oziareny'] == 'on')) $mod_sanca_noc += 5;
			$mod_skillpointy_za_level = 0;
				if (isset($_POST['trait_skuseny']) && ($_POST['trait_skuseny'] == 'on')) $mod_skillpointy_za_level += 5;
				if (isset($_POST['trait_nadany']) && ($_POST['trait_nadany'] == 'on')) $mod_skillpointy_za_level -= 5;
			$atribut_sila = $_POST['sila'];
			$atribut_vydrz = $_POST['vydrz'];
			$atribut_inteligencia = $_POST['inteligencia'];
			$atribut_rychlost = $_POST['rychlost'];
			$atribut_charizma = $_POST['charizma'];
			$atribut_stastie = $_POST['stastie'];
			$zrucnost_chladne_zbrane = $_POST['chladne_zbrane']+$this->mod_chladne_zbrane;
			$zrucnost_strelne_zbrane = $_POST['strelne_zbrane']+$this->mod_strelne_zbrane;
			$zrucnost_cudzinecke_zbrane = $_POST['cudzinecke_zbrane']+$this->mod_cudzinecke_zbrane;
			$zrucnost_utocna_magia = $_POST['utocna_magia']+$this->mod_utocna_magia;
			$zrucnost_obranna_magia = $_POST['obranna_magia']+$this->mod_obranna_magia;
			$zrucnost_obchodovanie = $_POST['obchodovanie']+$this->mod_obchodovanie;
			$zrucnost_kradnutie = $_POST['kradnutie']+$this->mod_kradnutie;
			$zrucnost_gamblovanie = $_POST['gamblovanie']+$this->mod_gamblovanie;
			$zrucnost_liecenie = $_POST['liecenie']+$this->mod_liecenie;
			$zrucnost_vyroba_lektvarov = $_POST['vyroba_lektvarov']+$this->mod_vyroba_lektvarov;	
				
			foreach ($_POST as $key => $value) {
				if ((substr($key,0,6) == 'trait_') && ($value == 'on')) {
					mysql_query('INSERT INTO `traits` (`player_id`, `trait_id`) VALUES (\''.$id.'\', \''.$key.'\');',DATABASE);
				}
			}
				
			$sql = "INSERT INTO `players` 
			(`id`, 
			`username`, 
			`name`, 
			`gender`, 
			`race`, 
			`password`, 
			`x`, 
			`y`, 
			`hp`, 
			`hp_max`, 
			`turns_played`, 
			`turns_remaining`, 
			`last_action`, 
			`critical_chance`, 
			`perk_multiplier`, 
			`base_armor`, 
			`base_action_points`, 
			`base_nosnost`, 
			`base_obnova_zdravia`, 
			`mod_sanca_den`, 
			`mod_sanca_noc`, 
			`mod_skillpointy_za_level`, 
			`atribut_sila`, 
			`atribut_vydrz`, 
			`atribut_inteligencia`, 
			`atribut_rychlost`, 
			`atribut_charizma`, 
			`atribut_stastie`, 
			`zrucnost_chladne_zbrane`, 
			`zrucnost_strelne_zbrane`, 
			`zrucnost_cudzinecke_zbrane`, 
			`zrucnost_utocna_magia`, 
			`zrucnost_obranna_magia`, 
			`zrucnost_obchodovanie`, 
			`zrucnost_kradnutie`, 
			`zrucnost_gamblovanie`, 
			`zrucnost_liecenie`, 
			`zrucnost_vyroba_lektvarov`
			) VALUES (
			'$id',
			'$login', 
			'$meno_postavy', 
			'$pohlavie', 
			'$race', 
			'$heslo', 
			'$start_x', 
			'$start_y', 
			'$hp', 
			'$hp', 
			'0', 
			'$turns_remaining', 
			'$lastaction', 
			'$critical_chance', 
			'$perk_multiplier', 
			'$base_armor', 
			'$base_action_points', 
			'$base_nosnost', 
			'$base_obnova_zdravia', 
			'$mod_sanca_den', 
			'$mod_sanca_noc', 
			'$mod_skillpointy_za_level', 
			'$atribut_sila', 
			'$atribut_vydrz', 
			'$atribut_inteligencia', 
			'$atribut_rychlost', 
			'$atribut_charizma', 
			'$atribut_stastie', 
			'$zrucnost_chladne_zbrane', 
			'$zrucnost_strelne_zbrane', 
			'$zrucnost_cudzinecke_zbrane', 
			'$zrucnost_utocna_magia', 
			'$zrucnost_obranna_magia', 
			'$zrucnost_obchodovanie', 
			'$zrucnost_kradnutie', 
			'$zrucnost_gamblovanie', 
			'$zrucnost_liecenie', 
			'$zrucnost_vyroba_lektvarov')";
			
			if ((mysql_num_rows(mysql_query('SELECT id FROM players WHERE username=\''.$_POST['login'].'\' OR name=\''.$_POST['meno_postavy'].'\';',DATABASE)) == 0) && ($res = mysql_query($sql,DATABASE))) {
				echo '<h1>Hotovo!</h1>';
				echo '<div style="width: 100%; text-align: center;">Tvoja postava je úspešne vytvorená a pripravená na život vo svete Wasteland.<br>Ak si pripravený aj ty, môžeš sa prihlásiť a pustiť sa do toho. Veľa šťastia!</div>';
				
				echo '<ul style="position: absolute; top: 185px; left: 180px;">';
				echo '<li>Login: '.$login.'</li>';
				echo '<li>Meno: '.$meno_postavy.'</li>';
				echo '<li>Rasa: '.$race.'</li>';
				echo '<li>Pohlavie: '.$pohlavie.'</li>';
				echo '<li>Hit-Pointov: '.$hp.'</li>';
				echo '<li>Armor: '.$base_armor.'</li>';
				echo '<li>Action-Pointov: '.$base_action_points.'</li>';
				echo '</ul>';
				echo '<ul style="position: absolute; top: 185px; left: 400px;">';
				echo '<li>Perk každé '.$perk_multiplier.' levely.</li>';
				echo '<li>Šanca na kritický zásah: '.$critical_chance.'%</li>';
				echo '<li>Body na zručnosti za level: '.($atribut_inteligencia*2 + $mod_skillpointy_za_level).'</li>';
				echo '<li>Nosnosť: '.$base_nosnost.'kg</li>';
				echo '<li>Obnova zdravia: '.$base_obnova_zdravia.' HP/ťah</li>';
				if ($mod_sanca_den > 0) echo '<li>Bonus k šanci na zásah cez deň: +'.$mod_sanca_den.'%</li>';
				if ($mod_sanca_den < 0) echo '<li>Bonus k šanci na zásah cez deň: '.$mod_sanca_den.'%</li>';
				if ($mod_sanca_noc > 0) echo '<li>Bonus k šanci na zásah v noci: +'.$mod_sanca_noc.'%</li>';
				if ($mod_sanca_noc < 0) echo '<li>Bonus k šanci na zásah v noci: '.$mod_sanca_noc.'%</li>';
				echo '</ul>';
				
				echo '<a href="javascript: window.close();" style="position: absolute; top: 400px; left: 390px;">ZAVRIEŤ</a>';
				
			} else {
				$this->DisplayError('Problem pri vkladaní do databázy. Skús to neskôr.\n'.mysql_error());
			}
			
		} else {
			$this->DisplayError($this->CheckForm(6));
		}
		}
?>