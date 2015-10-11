Ajax ={}
Ajax.makeRequest = function (method, url, callbackMethod, params) {
  try {
    // Firefox, Opera 8.0+, Safari
    this.request=new XMLHttpRequest();
  } catch (e) {
    // Internet Explorer
    try {
      this.request=new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        this.request=new ActiveXObject("Microsoft.XMLHTTP");
      } catch (e) {
        alert("Your browser does not support AJAX!");
        return false;
      }
    }
  }
  
  this.request.onreadystatechange = callbackMethod;
  this.request.open(method, url, true);
  
  if (method=="POST") {
    this.request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    this.request.setRequestHeader("Content-length", params.length);
    this.request.setRequestHeader("Connection", "close");
    this.request.send(params);
  } else {
    this.request.send(url);
  }
}

Ajax.checkReadyState = function() {
  switch(this.request.readyState) {
    case 4:
      return this.request.status;
  }
}

Ajax.getResponse = function() {
  if (this.request.getResponseHeader('Content-Type').indexOf('xml') != -1) {
    return this.request.responseXML.documentElement;
  } else {
    return this.request.responseText;
  }
}





AjaxUpdater = {};

AjaxUpdater.initialize = function() {
  AjaxUpdater.isUpdating = false;
}

AjaxUpdater.initialize();

AjaxUpdater.Update = function(method, service, callback, params) {
  if (callback==undefined || callback == '') {
    callback = AjaxUpdater.onResponse;
  }
  
  Ajax.makeRequest(method, service, callback, params);
  AjaxUpdater.isUpdating = true;
}


AjaxUpdater.onResponse = function() {
  if (Ajax.checkReadyState('loading') == 200) {
    AjaxUpdater.isUpdating = false;
  }
}






