/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can
 * always reference jQuery with $, even when in .noConflict() mode.
 * ======================================================================== */

(function($) {

  // Use this variable to set up the common and page specific functions. If you
  // rename this variable, you will also need to rename the namespace below.
  var Sage = {
    // All pages
    'common': {
      init: function() {
        // JavaScript to be fired on all pages

        $('.menu-item-2857 a').attr('target','_blank');
        $('.menu-item-2858 a').attr('target','_blank');
        $('.post-2930 a').attr('target','_blank');
        

        function getPathFromUrl(url) {
          return url.split("?")[0];
        }

        $( ".hamburger" ).on( "click", function() {
          $(this).toggleClass("close");
          $("nav.overlay").toggleClass("show");

          $("body").toggleClass("menu-active");
        });

        $( ".checkbox-custom" ).on( "click", function() {

          var input = $('input', this);

          var baseHref = getPathFromUrl(window.location.href);

          if ( !$( input ).prop( "checked" ) ) {
            window.location.href = baseHref;
          } else {
            var eventType = $('input', this).attr('name');

            window.location.href = baseHref + "?type=" + eventType;
          }

        });

        $('.category-parent a, .cfe-nav > li > a, .standard-menu > div > ul > li > a').click(function() { return false; });

        $( ".standard-menu > div > ul > li" ).hover(
          function() {
            $(this).addClass('menu-open');
          }, function() {
            $(this).removeClass('menu-open');
          }
        );

      },
      finalize: function() {
        // JavaScript to be fired on all pages, after page specific JS is fired
      }
    },
    // Home page
    'home': {
      init: function() {
        // JavaScript to be fired on the home page
      },
      finalize: function() {
        // JavaScript to be fired on the home page, after the init JS
      }
    },
    // About us page, note the change from about-us to about_us.
    'about_us': {
      init: function() {
        // JavaScript to be fired on the about us page
      }
    }
  };

  // The routing fires all common scripts, followed by the page specific scripts.
  // Add additional events for more control over timing e.g. a finalize event
  var UTIL = {
    fire: function(func, funcname, args) {
      var fire;
      var namespace = Sage;
      funcname = (funcname === undefined) ? 'init' : funcname;
      fire = func !== '';
      fire = fire && namespace[func];
      fire = fire && typeof namespace[func][funcname] === 'function';

      if (fire) {
        namespace[func][funcname](args);
      }
    },
    loadEvents: function() {
      // Fire common init JS
      UTIL.fire('common');

      // Fire page-specific init JS, and then finalize JS
      $.each(document.body.className.replace(/-/g, '_').split(/\s+/), function(i, classnm) {
        UTIL.fire(classnm);
        UTIL.fire(classnm, 'finalize');
      });

      // Fire common finalize JS
      UTIL.fire('common', 'finalize');
    }
  };

  // Load Events
  $(document).ready(UTIL.loadEvents);

})(jQuery); // Fully reference jQuery after this point.
