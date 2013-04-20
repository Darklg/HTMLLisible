/* ----------------------------------------------------------
   Functions from JavaScriptUtilities
   https://github.com/Darklg/JavaScriptUtilities
   ------------------------------------------------------- */

/* $_ : Get Element
   ----------------------- */

function $_(id) {
    return document.getElementById(id);
}

/* Domready
   ----------------------- */

/* From the amazing Dustin Diaz : http://www.dustindiaz.com/smallest-domready-ever */
// «!document.body» check ensures that IE fires domReady correctly
window.domReady = function(func) {
    if (/in/.test(document.readyState) || !document.body) {
        setTimeout(function() {
            domReady(func);
        }, 9);
    }
    else {
        func();
    }
};

/* Ajax
   ----------------------- */

var jsuAjax = function(args) {
    var xmlHttpReq = false,
        self = this;

    /* Tests */
    if (!args.url) {
        return false;
    }
    if (!args.method) {
        args.method = 'GET';
    }
    if (!args.callback) {
        args.callback = function() {};
    }
    if (!args.data) {
        args.data = '';
    }
    if (typeof args.data == 'object') {
        var ndata = '';
        for (var i in args.data) {
            if (ndata !== '') {
                ndata += '&';
            }
            ndata += i + '=' + args.data[i];
        }
        args.data = ndata;
    }

    /* XHR Object */
    if (window.XMLHttpRequest) {
        self.xmlHttpReq = new XMLHttpRequest();
    }
    else if (window.ActiveXObject) {
        self.xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
    }
    /* Opening request */
    self.xmlHttpReq.open(args.method, args.url, true);
    //self.xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    self.xmlHttpReq.onreadystatechange = function() {
        /* Callback when complete */
        if (self.xmlHttpReq.readyState == 4) {
            args.callback(self.xmlHttpReq.responseText);
        }
    };
    /* Sending request */
    self.xmlHttpReq.send(args.data);

};

/* ----------------------------------------------------------
   Domready actions
   ------------------------------------------------------- */

window.domReady(function() {

    // Demo button
    var demo_button = $_('demo-button'),
        html_to_clean = $_('html_to_clean');
    if (demo_button && html_to_clean) {
        demo_button.onclick = function(e) {
            e.preventDefault();
            jsuAjax({
                'url': 'html/code-sale.html',
                'callback': function(response) {
                    html_to_clean.value = response;
                }
            });
        };
    }
});