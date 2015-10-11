<?php
echo '<h2>Prihlasovacie údaje - krok 1/6</h2>';
			// uvodny text:
			echo '
        <p>
          V prvom kroku tvorby postavy musíš zadať údaje, ktoré budeš potrebovať pre prihlásenie do hry - LOGIN a HESLO, ďalej E-MAIL, na ktorý ti zašleme nové heslo v prípade, že staré zabudneš.<br/><br/>Ďalšie údaje, ktoré vypĺňaš v prvom kroku sú MENO a POHLAVIE, ktoré sa budú zobrazovať v hre.
        </p>
          ';
          
			echo '
        <form action="'.URL.'register" method="post" id="regform">
          <p>
            <label for="inick">Nick:</label>
            <input id="inick" name="nick" type="text" '.(isset($_POST['register'])?('value="'.$_POST['nick'].'"'):'').' />
            '.(isset($_POST['register'])?('<span class="error">'.$this->nickError.'</span>'):'').'
          </p>
          <p>
            <label for="ipass">Heslo</label>
            <input id="ipass" name="heslo1" type="password" />
            '.(isset($_POST['register'])?('<span class="error">'.$this->passwordError.'</span>'):'').'
          </p>
          <p>
            <label for="ipass2">Potvrdenie hesla</label>
            <input id="ipass2" name="heslo2" type="password" />
            '.(isset($_POST['register'])?('<span class="error">'.$this->password2Error.'</span>'):'').'
          </p>
          <p>
            <label for="iemail">Email</label>
            <input id="iemail" name="email" type="text" '.(isset($_POST['register'])?('value="'.$_POST['email'].'"'):'').' />
            '.(isset($_POST['register'])?('<span class="error">'.$this->emailError.'</span>'):'').'
          </p>
          <p>
            <label for="iname">Meno postavy</label>
            <input id="iname" name="meno_postavy" type="text" '.(isset($_POST['register'])?('value="'.$_POST['meno_postavy'].'"'):'').' />
            '.(isset($_POST['register'])?('<span class="error">'.$this->characterNameError.'</span>'):'').'
          </p>
          <p>
            Pohlavie postavy:             
            <label for="male">muž</label><input type="radio" id="male" value="male" checked="checked" name="pohlavie" />
            <label for="female">žena</label><input type="radio" id="female" value="female" name="pohlavie" />
          </p>
          <p>
            <input type="submit" name="register" value="Pokračovať krokom 2" />
          </p>
        </form>'. /* popisky k formularu: */ '
        ';
        
        
?>