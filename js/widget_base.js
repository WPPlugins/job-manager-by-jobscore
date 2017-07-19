/* JobScore widget->iframe interface (c) 2011-2012 JobScore, Inc. */
//(function(window) {
jQuery(function () {
//	alert('fondue');
  var use_postmessage = (typeof(window.postMessage) != 'undefined');

  var WIDGET_PREFIX = "js_widget_iframe_";
  var js_if = [];

  function create_iframe(name)
  {
    var iframe = null;
    if (document.createElement && (iframe = document.createElement('iframe'))) {
      iframe.name = name; /* IE8 or below: useless for scripts contained within the iframe */ 
      iframe.id = name; 
      iframe.setAttribute("allowtransparency", "true");
      iframe.frameBorder = "0";
      iframe.setAttribute("scrolling", "no");
      iframe.setAttribute("width", "100%");
      iframe.setAttribute("height", "1000");
    }
    return iframe;
  }

  function getElementsByClassName(cl)
  {
    // if internal function exists, use it
    if (typeof(document.getElementsByClassName) == 'function') {
      return document.getElementsByClassName(cl);
    }
    // fallback for IE7 and other ancient browsers
    var retnode = [];
    var myclass = new RegExp('\\b'+cl+'\\b');
    var elem = document.getElementsByTagName('*');

    for (var i = 0; i < elem.length; i++) {
      var classes = elem[i].className;
      if (myclass.test(classes)) retnode.push(elem[i]);
    }

    return retnode;
  }

  function load() {
    var js_id = 1;
    var js_if_count = 0;
    var resize_count = 0;

    var js_widget_tags = getElementsByClassName("jobscore-jobs");
    for (var i = 0; i < js_widget_tags.length; i++) {
      var url = ('https:' == document.location.protocol ? 'https:' : 'http:') +
      '//www.jobscore.com/jobs/'+get_account_code()+'/widget_iframe';
      var widget = null;;
      var args = [];
      var widget_tag = js_widget_tags[i];
      var id = js_id++;
  
  
      // create widget iframe
      widget = create_iframe(WIDGET_PREFIX + id);

      // clear out widget placeholder
      widget_tag.innerHTML = "";

      var attributes = widget_tag.attributes;
      for (var j = 0; j < attributes.length; j++) {
        if (attributes[j].name.indexOf("data-") == 0) {
          args.push(attributes[j].name.substr(5).replace(/\-/g,'_') + "=" + encodeURIComponent(attributes[j].value));
        }
      }
  
      // used for back link
      args.push("parent_url=" + encodeURIComponent(window.location.toString()));

      // send widget_id to work around IE bug where iframe name attribute is unreliable
      args.push('widget_id=' + WIDGET_PREFIX + id);
      if (args.length > 0) {
        url += "?" + args.join("&");
      }
      widget_tag.appendChild(widget);
      widget.src = url;
      js_if[WIDGET_PREFIX + id] = widget;
      js_if_count++;
    }
  }

  function jobscore_resize(widget, height, scrollToTop) {
    if (scrollToTop) window.scrollTo(0, 0);
    if (js_if[widget]) {
      js_if[widget].height = height + "px";
    } 
//  console.warn("resize: widget: " + widget + " height: " + height);
  }

  /* iframe resize via hash change
   *
   * For older browsers we communicate via the url hash value.
   * Each frame staggers a change message (1.5 * ID) seconds
   * We keep looking for changes for each widget.  Widgets only size once so we can stop polling once we've heard from each widget
   */
  function hashchange_resize_check() {
    if (window.location.hash == "") {
      setTimeout("window.js_hashchange_resize_check();", 300);
      return;
    }
    var h = window.location.hash;
    var r = new RegExp(WIDGET_PREFIX + "([0-9]+)=([0-9]+)");
    var m = r.exec(window.location.hash);
    if (m == null) {
      setTimeout("window.js_hashchange_resize_check();", 300);
      return;
    }
    jobscore_resize(WIDGET_PREFIX + m[1], m[2]);
    if (resize_count++ < js_if_count) {
      setTimeout("window.js_hashchange_resize_check();", 300);
    }
    window.location.hash = '';

//  console.warn('hashchange resize triggered: js_widget_iframe_' + m[1]);
  }

  function onmessage(m) 
  {
    var e = new RegExp(WIDGET_PREFIX + "([0-9]+)=([0-9]+)");
    var r = e.exec(m.data);
    if (r) {
      jobscore_resize(WIDGET_PREFIX + r[1],r[2]);
    }
  }

  // load up the iframe
  load();

  // expose load to the container window.  Note: only the last loaded widget will get reloaded.
  window._jobscore_widget_reload = load;

  /* turn on postmessage or hashchange messaging depending on browser support */
  if (!use_postmessage) {
    window.js_hashchange_resize_check = hashchange_resize_check;
    setTimeout("window.js_hashchange_resize_check();", 100);
  } else {
    if (typeof(window.addEventListener) != 'undefined') { 
      window.addEventListener('message', onmessage, false);
    } else if (typeof(window.attachEvent) != 'undefined') { 
      window.attachEvent('onmessage', onmessage);
    }
  }

});