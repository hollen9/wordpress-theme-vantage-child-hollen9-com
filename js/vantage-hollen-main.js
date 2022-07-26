jQuery(function ($) {
  $("body.no-js").removeClass("no-js"), $(".entry-content .flexslider:not(.metaslider .flexslider), #metaslider-demo.flexslider, .gallery-format-slider").flexslider({namespace: "flex-vantage-"}), void 0 !== $.fn.fitVids && "undefined" != typeof vantage && (vantage.fitvids, 1) && $(".entry-content, .entry-content .panel, .entry-video, .woocommerce #main, #masthead-widgets, #header-sidebar").fitVids({ignore: ".tableauViz"});
  var e = $("body").hasClass("so-vantage-mobile-device"), n = $("body").hasClass("so-vantage-customizer-preview"), t = $("nav.site-navigation.primary").hasClass("mobile-navigation");
  (!e && $("#scroll-to-top").hasClass("scroll-to-top") || n || e) && ($(window).on("scroll", function () {
    150 < $(window).scrollTop() ? $("#scroll-to-top").addClass("displayed") : $("#scroll-to-top").removeClass("displayed");
  }), $("#scroll-to-top").on("click", function () {
    return $("html, body").animate({scrollTop: "0px"}), false;
  })), $(".vantage-carousel").each(function () {
    function l() {
      u < 0 && (u = 0), u >= i.find(".carousel-entry").length - 1 && (u = i.find(".carousel-entry").length - 1, s || o || (s = true, a++, i.append('<li class="loading"></li>'), $.get(i.data("ajax-url"), {query: i.data("query"), action: "vantage_carousel_load", paged: a}, function (e, n) {
        var t = $(e.html).find(".carousel-entry").appendTo(i).hide().fadeIn().length;
        r += t, 0 === t ? (o = true, i.find(".loading").fadeOut(function () {
          $(this).remove();
        })) : i.find(".loading").remove(), s = false;
      }))), i.css("transition-duration", "0.45s"), i.css("margin-left", -f * u + "px");
    }
    var i = $(this), e = i.closest(".widget").find(".widget-title"), n = i.find(".carousel-entry"), t = n.eq(0), u = 0, a = 1, s = false, o = false, r = n.length, f = t.width() + parseInt(t.css("margin-right"));
    e.find("a.previous").on("click", function () {
      return u -= 1, l(), false;
    }), e.find("a.next").on("click", function () {
      return u += 1, l(), false;
    });
    var h, m = false, g = 0, v = 0, p = 0, y = 0;
    i.swipe({excludedElements: "", triggerOnTouchEnd: true, threshold: 75, swipeStatus: function (e, n, i, a, t, s, o) {
      if ("start" === n) v = -f * u, y = (new Date).getTime(), clearInterval(h); else if ("move" === n) {
        "left" === i && (a *= -1), v + a < 50 && -f * r < v + a && (i.css("transition-duration", "0s"), i.css("margin-left", v + a + "px"), true);
        var r = (new Date).getTime();
        p = (a - g) / ((r - y) / 1e3), y = r, g = a;
      } else if ("end" === n) if (m = true, "left" === i && (a *= -1), 400 < Math.abs(p)) {
        p *= 0.1;
        var d = (new Date).getTime(), c = 0;
        h = setInterval(function () {
          var e = ((new Date).getTime() - d) / 1e3, n = v + a + (c += p * e), t = Math.abs(p) - 30 < 0;
          "left" === i ? p += 30 : p -= 30, !t && (n < 50 && -f * r < n && (i.css("transition-duration", "0s"), i.css("margin-left", n + "px"), true)) || (clearInterval(h), b());
        }, 20);
      } else b(); else "cancel" === n && l();
    }});
    var b = function () {
      var e = parseInt(i.css("margin-left"));
      u = Math.abs(Math.round(e / f)), l();
    };
    i.on("click", "li.carousel-entry .thumbnail a", function (e) {
      m && (e.preventDefault(), m = false);
    });
  }), $(".menu-item").children("a").on("focusin", function () {
    $(this).parents("li").addClass("focus");
  }), $(".menu-item").children("a").on("click", function () {
    $(this).parents("li").removeClass("focus");
  }), $(".menu-item").children("a").on("focusout", function () {
    $(this).parents("li").removeClass("focus");
  }), $("#header-sidebar .widget_nav_menu", "#masthead-widgets .widget_nav_menu").on("mouseenter", "ul.menu li", function () {
    $(this).find("> ul").finish().css("opacity", 0).hide().slideDown(200).animate({opacity: 1}, {queue: false, duration: 200});
  }).on("mouseleave", "ul.menu li", function () {
    $(this).find("> ul").finish().fadeOut(150);
  });
  var i = false;
  $(document).on("click", function () {
    i || $("#search-icon form").fadeOut(250);
  });
  var a = navigator.userAgent.toLowerCase();
  if ($(document).on("click keydown", "#search-icon-icon", function (e) {
    if ("keydown" == e.type) {
      if (13 !== e.keyCode) return;
      e.preventDefault();
    }
    var n = $(this).parent();
    n.find("form").fadeToggle(250), a.match(/(iPad|iPhone|iPod)/i) ? n.find('input[type="search"]').trigger("focus") : setTimeout(function () {
      n.find('input[type="search"]').trigger("focus");
    }, 300);
  }), $(document).on("keyup", function (e) {
    27 == e.keyCode && $("#search-icon form").fadeOut(250);
  }), $(document).on("mouseenter", "#search-icon", function () {
    i = true;
  }).on("mouseleave", "#search-icon", function () {
    i = false;
  }), $(window).on("resize", function () {
    $("#search-icon .searchform").each(function () {
      $(this).width($(this).closest(".full-container").width());
    });
  }).trigger("resize"), $("nav.site-navigation.primary").hasClass("use-sticky-menu") /*&& !e*/ || (e || n) && t) {
    function s() {
      //alert("STICKY!");
      c.hasClass("sticky") || (o = c.offset().top, r = c.width());
      var e = 0;
      $("body").hasClass("admin-bar") && (e = "absolute" == $("#wpadminbar").css("position") ? 0 :
       $("#wpadminbar").outerHeight()), parseInt(o - $(window).scrollTop()) < e ? (c.addClass("sticky"), $("body").addClass("sticky-menu"), $("#masthead").css("padding-bottom", c.innerHeight()), l && c.width(r)) : c.hasClass("sticky") && ($("#masthead").css("padding-bottom", 0), c.removeClass("sticky"), $("body").removeClass("sticky-menu"), l && c.width("auto"));
    }
    var o, r, c = $("nav.site-navigation.primary"), l = $("body.mega-menu-primary.layout-boxed").length;
    $(window).on("scroll resize", s), s();
  }
  $('body.layout-full #main-slider[data-stretch="true"]').each(function () {
    var e = $(this);
    e.find(">div").css("max-width", "100%"), e.find(".slides li").each(function () {
      var e = $(this), n = e.find("img.ms-default-image").eq(0);
      n.length || (n = e.find("img").eq(0)), e.css("background-image", "url(" + n.attr("src") + ")"), n.css("visibility", "hidden"), e.wrapInner('<div class="full-container"></div>');
      var i = e.find("a");
      i.length && (e.on("mouseenter", function () {
        e.css("cursor", "hand");
      }), e.on("mouseout", function () {
        e.css("cursor", "pointer");
      }), e.on("click", function (e) {
        e.preventDefault();
        var n = $(e.target), t = n.is("a") ? n : i;
        window.open(t.attr("href"), t.attr("target"));
      }));
    });
  }), $("#header-sidebar").each(function () {
    var n = $(this), t = 0;
    if (n.find("> aside").each(function () {
      var e = (n.outerHeight() - n.find("> aside").outerHeight()) / 2;
      t < e && (t = e);
    }), 15 < t ? n.css({"padding-top": t, "padding-bottom": t}) : (t = 15 - t, $("#masthead .logo > *").css({"padding-top": t, "padding-bottom": t})), n.hasClass("no-logo-overlay")) {
      function e() {
        n.closest("#masthead").removeClass("force-responsive");
        var e = $("#masthead .logo").find("h1, img");
        n.offset().left < e.offset().left + e.outerWidth() && n.closest("#masthead").addClass("force-responsive");
      }
      $(window).on("resize", e), e();
    }
  }), $("#colophon #footer-widgets .widget_nav_menu a").each(function () {
    $(this);
    var e = 10 * $(this).parents(".sub-menu").length + "px";
    $(this).css("padding-left", e);
  });
});
