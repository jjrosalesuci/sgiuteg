/*!
 * Ext JS Library 3.3.1
 * Copyright(c) 2006-2010 Sencha Inc.
 * licensing@sencha.com
 * http://www.sencha.com/license
 */
Ext.BLANK_IMAGE_URL = BASE_URL_FRAME+'resources/images/default/s.gif';

Ext.Ajax.extraParams = {
    '_csrf': CSRF_TOKEN
};

Ext.util.Observable.observeClass(Ext.data.Connection);

Ext.data.Connection.on('requestexception', function(dataconn, response, options){
     if(response.status==302){
        window.parent.location = BASE_URL_FRAME;
     }
});


/*
 switch (response.status) {
            case 401: return Nip.utils.handle401Error(conn, response, options, eOpts); // Unauthorized
            case 403: return Nip.utils.handle403Error(conn, response, options, eOpts); // Forbidden
            case 404: return Nip.utils.handle404Error(conn, response, options, eOpts); // Not Found
            case 405: return Nip.utils.handle405Error(conn, response, options, eOpts); // Method Not Allowed
            case 408: return Nip.utils.handle408Error(conn, response, options, eOpts); // Request Timeout
            case 409: return Nip.utils.handle409Error(conn, response, options, eOpts); // Conflict
            case 410: return Nip.utils.handle410Error(conn, response, options, eOpts); // Gone
            case 500: return Nip.utils.handle500Error(conn, response, options, eOpts); // Internal Server Error
            case 501: return Nip.utils.handle501Error(conn, response, options, eOpts); // Not Implemented
            case 502: return Nip.utils.handle502Error(conn, response, options, eOpts); // Bad Gateway
            case 503: return Nip.utils.handle503Error(conn, response, options, eOpts); // Service Unavailable
            case 504: return Nip.utils.handle504Error(conn, response, options, eOpts); // Gateway Timeout
            default: return Nip.utils.handleGeneralError(conn, response, options, eOpts);
        }*/

Ext.example = function(){
    var msgCt;

    function createBox(t, s){
        return ['<div class="msg">',
                '<div class="x-box-tl"><div class="x-box-tr"><div class="x-box-tc"></div></div></div>',
                '<div class="x-box-ml"><div class="x-box-mr"><div class="x-box-mc"><h3>', t, '</h3>', s, '</div></div></div>',
                '<div class="x-box-bl"><div class="x-box-br"><div class="x-box-bc"></div></div></div>',
                '</div>'].join('');
    }
    return {
        msg : function(title, format){
            if(!msgCt){
                msgCt = Ext.DomHelper.insertFirst(document.body, {id:'msg-div'}, true);
            }
            msgCt.alignTo(document, 't-t');
            var s = String.format.apply(String, Array.prototype.slice.call(arguments, 1));
            var m = Ext.DomHelper.append(msgCt, {html:createBox(title, s)}, true);
            m.slideIn('t').pause(1).ghost("t", {remove:true});
        },

        init : function(){
            /*
            var t = Ext.get('exttheme');
            if(!t){ // run locally?
                return;
            }
            var theme = Cookies.get('exttheme') || 'aero';
            if(theme){
                t.dom.value = theme;
                Ext.getBody().addClass('x-'+theme);
            }
            t.on('change', function(){
                Cookies.set('exttheme', t.getValue());
                setTimeout(function(){
                    window.location.reload();
                }, 250);
            });*/

            var lb = Ext.get('lib-bar');
            if(lb){
                lb.show();
            }
        }
    };
}();

Ext.onReady(Ext.example.init, Ext.example);


// old school cookie functions
var Cookies = {};
Cookies.set = function(name, value){
     var argv = arguments;
     var argc = arguments.length;
     var expires = (argc > 2) ? argv[2] : null;
     var path = (argc > 3) ? argv[3] : '/';
     var domain = (argc > 4) ? argv[4] : null;
     var secure = (argc > 5) ? argv[5] : false;
     document.cookie = name + "=" + escape (value) +
       ((expires == null) ? "" : ("; expires=" + expires.toGMTString())) +
       ((path == null) ? "" : ("; path=" + path)) +
       ((domain == null) ? "" : ("; domain=" + domain)) +
       ((secure == true) ? "; secure" : "");
};

Cookies.get = function(name){
	var arg = name + "=";
	var alen = arg.length;
	var clen = document.cookie.length;
	var i = 0;
	var j = 0;
	while(i < clen){
		j = i + alen;
		if (document.cookie.substring(i, j) == arg)
			return Cookies.getCookieVal(j);
		i = document.cookie.indexOf(" ", i) + 1;
		if(i == 0)
			break;
	}
	return null;
};

Cookies.clear = function(name) {
  if(Cookies.get(name)){
    document.cookie = name + "=" +
    "; expires=Thu, 01-Jan-70 00:00:01 GMT";
  }
};

Cookies.getCookieVal = function(offset){
   var endstr = document.cookie.indexOf(";", offset);
   if(endstr == -1){
       endstr = document.cookie.length;
   }
   return unescape(document.cookie.substring(offset, endstr));
};