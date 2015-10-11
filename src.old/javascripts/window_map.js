function showLoadedWin() {
  if (Ajax.checkReadyState() == 200) {
  document.getElementById('popupcontent').innerHTML = Ajax.getResponse();
	document.getElementById('popupbackground').style.visibility = "visible";
	document.getElementById('popupcontainer').style.visibility = "visible";
  }
}

function selectPlace(link) {
  $.ajax({  
     type: "POST",  
     url: link,  
     data: '',  
     success: function(data) {  

      $('#map_right').fadeOut(1000, function() {
        $('#map_right').html(data);  
        $('#map_right').fadeIn(1000);
      });
       
  /*     $('#popupcontent').html(data);  

      $('#popupbackground').fadeIn(1000);

       $('#map_right').fadeIn(1000, function() {  
         $('#message').append("<img id='checkmark' src='images/check.png' />");  
       });  */
     }  
   });  
}



	
function showWin(link, title) {
  //win = new Window({className: "nuncio", title: title, width:900, height:500, minimizable: false, draggable: false, destroyOnClose: true, recenterAuto:true});
    //document.getElementById('popuptitle').innerHTML = title;
   
  //    AjaxUpdater.Update("GET", link, showLoadedWin);
	document.getElementById('popupbackground').style.visibility = "visible";
	//document.getElementById('popupcontainer').style.visibility = "visible";

  var dataString = '';  
  //alert (dataString);return false;  
  $.ajax({  
     type: "POST",  
     url: link,  
     data: dataString,  
     success: function(data) {  
       $('#popuptitle').html(title);  
       $('#popupcontent').html(data);  

      $('#popupbackground').fadeIn(1000);

       $('#popupcontainer').fadeIn(1000, function() {  
         $('#message').append("<img id='checkmark' src='images/check.png' />");  
       });  
     }  
   });  
  return false;  
}





function changeWin(link) {
  var dataString = '';  
  $.ajax({  
     type: "POST",  
     url: link,  
     data: dataString,  
     success: function(data) {  
       $('#popupcontent').html(data);  

     }  
   });  
  return false;  

}

function hidePopUp() {
  $('#popupbackground').fadeOut(1500);
  $('#popupcontainer').fadeOut(1500);
	//document.getElementById('popupcontainer').style.display = "none";
}
