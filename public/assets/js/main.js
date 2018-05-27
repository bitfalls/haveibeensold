/*
	Urban by TEMPLATED
	templated.co @templatedco
	Released for free under the Creative Commons Attribution 3.0 license (templated.co/license)
*/

/*! modernizr 3.6.0 (Custom Build) | MIT *
 * https://modernizr.com/download/?-webp-webpalpha-webpanimation-webplossless_webp_lossless-setclasses !*/
!function(A,e,n){function a(A){var e=c.className,n=Modernizr._config.classPrefix||"";if(u&&(e=e.baseVal),Modernizr._config.enableJSClass){var a=new RegExp("(^|\\s)"+n+"no-js(\\s|$)");e=e.replace(a,"$1"+n+"js$2")}Modernizr._config.enableClasses&&(e+=" "+n+A.join(" "+n),u?c.className.baseVal=e:c.className=e)}function o(A,e){return typeof A===e}function s(){var A,e,n,a,s,i,r;for(var f in l)if(l.hasOwnProperty(f)){if(A=[],e=l[f],e.name&&(A.push(e.name.toLowerCase()),e.options&&e.options.aliases&&e.options.aliases.length))for(n=0;n<e.options.aliases.length;n++)A.push(e.options.aliases[n].toLowerCase());for(a=o(e.fn,"function")?e.fn():e.fn,s=0;s<A.length;s++)i=A[s],r=i.split("."),1===r.length?Modernizr[r[0]]=a:(!Modernizr[r[0]]||Modernizr[r[0]]instanceof Boolean||(Modernizr[r[0]]=new Boolean(Modernizr[r[0]])),Modernizr[r[0]][r[1]]=a),t.push((a?"":"no-")+r.join("-"))}}function i(A,e){if("object"==typeof A)for(var n in A)f(A,n)&&i(n,A[n]);else{A=A.toLowerCase();var o=A.split("."),s=Modernizr[o[0]];if(2==o.length&&(s=s[o[1]]),"undefined"!=typeof s)return Modernizr;e="function"==typeof e?e():e,1==o.length?Modernizr[o[0]]=e:(!Modernizr[o[0]]||Modernizr[o[0]]instanceof Boolean||(Modernizr[o[0]]=new Boolean(Modernizr[o[0]])),Modernizr[o[0]][o[1]]=e),a([(e&&0!=e?"":"no-")+o.join("-")]),Modernizr._trigger(A,e)}return Modernizr}var t=[],l=[],r={_version:"3.6.0",_config:{classPrefix:"",enableClasses:!0,enableJSClass:!0,usePrefixes:!0},_q:[],on:function(A,e){var n=this;setTimeout(function(){e(n[A])},0)},addTest:function(A,e,n){l.push({name:A,fn:e,options:n})},addAsyncTest:function(A){l.push({name:null,fn:A})}},Modernizr=function(){};Modernizr.prototype=r,Modernizr=new Modernizr;var f,c=e.documentElement,u="svg"===c.nodeName.toLowerCase();!function(){var A={}.hasOwnProperty;f=o(A,"undefined")||o(A.call,"undefined")?function(A,e){return e in A&&o(A.constructor.prototype[e],"undefined")}:function(e,n){return A.call(e,n)}}(),r._l={},r.on=function(A,e){this._l[A]||(this._l[A]=[]),this._l[A].push(e),Modernizr.hasOwnProperty(A)&&setTimeout(function(){Modernizr._trigger(A,Modernizr[A])},0)},r._trigger=function(A,e){if(this._l[A]){var n=this._l[A];setTimeout(function(){var A,a;for(A=0;A<n.length;A++)(a=n[A])(e)},0),delete this._l[A]}},Modernizr._q.push(function(){r.addTest=i}),Modernizr.addAsyncTest(function(){var A=new Image;A.onerror=function(){i("webpanimation",!1,{aliases:["webp-animation"]})},A.onload=function(){i("webpanimation",1==A.width,{aliases:["webp-animation"]})},A.src="data:image/webp;base64,UklGRlIAAABXRUJQVlA4WAoAAAASAAAAAAAAAAAAQU5JTQYAAAD/////AABBTk1GJgAAAAAAAAAAAAAAAAAAAGQAAABWUDhMDQAAAC8AAAAQBxAREYiI/gcA"}),Modernizr.addAsyncTest(function(){var A=new Image;A.onerror=function(){i("webpalpha",!1,{aliases:["webp-alpha"]})},A.onload=function(){i("webpalpha",1==A.width,{aliases:["webp-alpha"]})},A.src="data:image/webp;base64,UklGRkoAAABXRUJQVlA4WAoAAAAQAAAAAAAAAAAAQUxQSAwAAAABBxAR/Q9ERP8DAABWUDggGAAAADABAJ0BKgEAAQADADQlpAADcAD++/1QAA=="}),Modernizr.addAsyncTest(function(){function A(A,e,n){function a(e){var a=e&&"load"===e.type?1==o.width:!1,s="webp"===A;i(A,s&&a?new Boolean(a):a),n&&n(e)}var o=new Image;o.onerror=a,o.onload=a,o.src=e}var e=[{uri:"data:image/webp;base64,UklGRiQAAABXRUJQVlA4IBgAAAAwAQCdASoBAAEAAwA0JaQAA3AA/vuUAAA=",name:"webp"},{uri:"data:image/webp;base64,UklGRkoAAABXRUJQVlA4WAoAAAAQAAAAAAAAAAAAQUxQSAwAAAABBxAR/Q9ERP8DAABWUDggGAAAADABAJ0BKgEAAQADADQlpAADcAD++/1QAA==",name:"webp.alpha"},{uri:"data:image/webp;base64,UklGRlIAAABXRUJQVlA4WAoAAAASAAAAAAAAAAAAQU5JTQYAAAD/////AABBTk1GJgAAAAAAAAAAAAAAAAAAAGQAAABWUDhMDQAAAC8AAAAQBxAREYiI/gcA",name:"webp.animation"},{uri:"data:image/webp;base64,UklGRh4AAABXRUJQVlA4TBEAAAAvAAAAAAfQ//73v/+BiOh/AAA=",name:"webp.lossless"}],n=e.shift();A(n.name,n.uri,function(n){if(n&&"load"===n.type)for(var a=0;a<e.length;a++)A(e[a].name,e[a].uri)})}),Modernizr.addAsyncTest(function(){var A=new Image;A.onerror=function(){i("webplossless",!1,{aliases:["webp-lossless"]})},A.onload=function(){i("webplossless",1==A.width,{aliases:["webp-lossless"]})},A.src="data:image/webp;base64,UklGRh4AAABXRUJQVlA4TBEAAAAvAAAAAAfQ//73v/+BiOh/AAA="}),s(),a(t),delete r.addTest,delete r.addAsyncTest;for(var p=0;p<Modernizr._q.length;p++)Modernizr._q[p]();A.Modernizr=Modernizr}(window,document);

(function ($) {

  // in your application
    navigator.serviceWorker.register('/assets/js/myworker.js').then(function (registration) {
      // Registration was successful
    }, function (err) {
      // Registration failed
    });


  skel.breakpoints({
    xlarge: '(max-width: 1680px)',
    large: '(max-width: 1280px)',
    medium: '(max-width: 980px)',
    small: '(max-width: 736px)',
    xsmall: '(max-width: 480px)'
  });

  $(function () {

    var $window = $(window),
      $body = $('body'),
      $header = $('#header'),
      $banner = $('#banner');

    // Disable animations/transitions until the page has loaded.
    $body.addClass('is-loading');

    $window.on('load', function () {
      window.setTimeout(function () {
        $body.removeClass('is-loading');
      }, 30);
    });

    // Fix: Placeholder polyfill.
    $('form').placeholder();

    // Prioritize "important" elements on medium.
    skel.on('+medium -medium', function () {
      $.prioritize(
        '.important\\28 medium\\29',
        skel.breakpoint('medium').active
      );
    });

    // Menu.
    $('#menu')
      .append('<a href="#menu" class="close"></a>')
      .appendTo($body)
      .panel({
        delay: 100,
        hideOnClick: true,
        hideOnSwipe: true,
        resetScroll: true,
        resetForms: true,
        side: 'right'
      });

    function warning(action, message) {
      if (message === undefined) {
        message = '';
      }
      $('#warning').text(message)[action]();
    }

    function checkInput(email) {
      if (email === "") {
        warning('show', 'Please enter a valid email address');
        return false;
      } else {
        warning('hide');
        return true;
      }
    }

    let email;
    $('#email').keyup(function () {
      $('#success').hide();
      $('#warning').hide();
      $("#lists").html("").hide();
      $("section.lists").hide();
    });

    $('button').click(function(e) {
      $("#lists").html("").hide();
      $("section.lists").hide();
    });

    $('#check').click(function () {

      $('#success').hide();
      email = $('#email').val();

      if (!checkInput(email)) {
        return false;
      }

      $.post('/api/api.php', {email: email, action: "check"}, function (data) {
        if (data.status === 'error') {
          if (data.data === 'E_NOT_VALID') {
            checkInput('', 'This email does not look valid');
          }
          if (data.data === 'E_QUERY_FAILED') {
            warning('show', 'Something went wrong :(');
          }
        }

        if (data.status === 'success') {
          if (data.data.length === 0) {
            $('#success').show().text('Your email is not on any sold list we are currently aware of! 👍');
          } else {
            $('#success').show().text('Uh oh. You were found on some lists! Scroll down for results!');
            $("#lists").show();
            $("section.lists").show();
            $(data.data).each(function (i, list) {
              $("#lists").append("<div class='listitem'><span>"+list.info+"</span></div>");
            });
          }
        }
      })
    });

    $('#remove').click(function () {
      $('#success').hide();
      email = $('#email').val();
      $("button").attr("disabled", "disabled");

      if (!checkInput(email)) {
        return false;
      }

      $.post('/api/api.php', {email: email, action: "del"}, function (data) {
        $("button").removeAttr("disabled");
        if (data.status === 'error') {
          if (data.data === 'E_NOT_VALID') {
            checkInput('', 'This email does not look valid');
          }
          if (data.data === 'E_QUERY_FAILED') {
            warning('show', 'Something went wrong :(');
          }
        }

        if (data.status === 'success') {
          if (data.data === "NOT_THERE") {
            warning('show', 'You are not in our database!');
          }
          if (data.data === "OK") {
            $('#success').show().text("Ok, we'll remove you - check your email for a confirmation link!");
          }
        }
      })
    });

    $('#add').click(function () {
      $('#success').hide();
      email = $('#email').val();
      $("button").attr("disabled", "disabled");

      if (!checkInput(email)) {
        return false;
      }

      $.post('/api/api.php', {email: email, action: "add"}, function (data) {
        $("button").removeAttr("disabled");

        if (data.status === 'error') {
          if (data.data === 'E_NOT_VALID') {
            checkInput('', 'This email does not look valid');
          }
          if (data.data === 'E_QUERY_FAILED') {
            warning('show', 'Something went wrong :(');
          }
          if (data.data === 'E_ALREADY_LISTED') {
            warning('show', 'Already listed, sit back and wait for a notification!');
          }
        }

        if (data.status === 'success') {
          $('#success').show().text("Ok, we'll let you know, but first check your email for a confirmation link ❤️");
        }
      })
    });

    // Header.
    if (skel.vars.IEVersion < 9)
      $header.removeClass('alt');

    if ($banner.length > 0
      && $header.hasClass('alt')) {

      $window.on('resize', function () {
        $window.trigger('scroll');
      });

    }

    // Banner.

    if ($banner.length > 0) {

      // IE fix.
      if (skel.vars.IEVersion < 12) {

        $window.on('resize', function () {

          var wh = $window.height() * 0.60,
            bh = $banner.height();

          $banner.css('height', 'auto');

          window.setTimeout(function () {

            if (bh < wh)
              $banner.css('height', wh + 'px');

          }, 0);

        });

        $window.on('load', function () {
          $window.triggerHandler('resize');
        });

      }

      // Video check.
      var video = $banner.data('video');

      if (video)
        $window.on('load.banner', function () {

          // Disable banner load event (so it doesn't fire again).
          $window.off('load.banner');

          // Append video if supported.
          if (!skel.vars.mobile
            && !skel.breakpoint('large').active
            && skel.vars.IEVersion > 9)
            $banner.append('<video autoplay loop><source src="' + video + '.mp4" type="video/mp4" /><source src="' + video + '.webm" type="video/webm" /></video>');

        });

    }

    // Tabs.
    $('.flex-tabs').each(function () {

      var t = jQuery(this),
        tab = t.find('.tab-list li a'),
        tabs = t.find('.tab');

      tab.click(function (e) {

        var x = jQuery(this),
          y = x.data('tab');

        // Set Classes on Tabs
        tab.removeClass('active');
        x.addClass('active');

        // Show/Hide Tab Content
        tabs.removeClass('active');
        t.find('.' + y).addClass('active');

        e.preventDefault();

      });

    });

  });

})(jQuery);