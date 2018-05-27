(function ($) {

  if (Modernizr.serviceWorker) {
    // in your application
    navigator.serviceWorker.register('/assets/js/myworker.js').then(function (registration) {
      // Registration was successful
    }, function (err) {
      // Registration failed
    });
  }


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
    }

  });

})($);