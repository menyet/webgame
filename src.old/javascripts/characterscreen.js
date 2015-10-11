var skills = [];
var increase = [];
var SP = 0;



function SPreset() {
	skills[0] = '.$this->player->baseMelee.';
	skills[1] = '.$this->player->baseRanged.';
	skills[2] = '.$this->player->baseAlien.';
	skills[3] = '.$this->player->baseAttackMagic.';
	skills[4] = '.$this->player->baseDefenseMagic.';
	skills[5] = '.$this->player->baseMerchant.';
	skills[6] = '.$this->player->baseThief.';
	skills[7] = '.$this->player->baseGambling.';
	skills[8] = '.$this->player->baseHealing.';
	skills[9] = '.$this->player->baseAlchemy.';
	SP = '.$this->player->skillPoints.'

	for (var i=0; i<10; i++) {
		increase[i] =0;
	}

	display();
}

function display() {
	for (var i=0; i<10; i++) {
		//document.getElementById("sp_"+ i).value = increase[i];
		//document.getElementById("val_"+i).innerHTML = ((SP>0)?('<a href="#" onclick="add('+i+\')">+</a>\'):"") +    (skills[i]+increase[i]);	
	}
  document.getElementById("val_sp").innerHTML = SP;
}


				
				//SPreset();

function add(skill) {
  var count = parseInt(document.getElementById("val_sp").innerHTML);

  if (count > 0) {
    document.getElementById("sp_"+ skill).value = parseInt(document.getElementById("sp_"+ skill).value) + 1;
  	document.getElementById("val_"+skill).innerHTML = document.getElementById("sp_"+ skill).value;	
    document.getElementById("val_sp").innerHTML = count - 1;
  }
	//increase[skill]++;
	//SP--;
	//display();
}
	


function updateCharacter() {
  var dataString = $("#character").serialize();


  //alert (dataString);return false;  
  $.ajax({  
     type: "POST",  
     url: "http://localhost/andris/wasteland/popups/PopupCharacter.php",  
     data: dataString,  
     success: function(data) {  
       $('#popupcontent').html(data);  
     }  
   });  
  return false;  

}

