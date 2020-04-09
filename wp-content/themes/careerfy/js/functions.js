// Multi-Toggle Navigation
var $ = jQuery;

jQuery(function ($) {

    'use strict';

    $('body').addClass('js');

    $(".navbar li").each(function () {
        var each_li = $(this);
        if (each_li.find('ul').length > 0) {
            each_li.append("<span class='has-subnav'><i class='fa fa-angle-down'></i></span>");
        }
    });
});

$(document).on("click", '.menu-link', function (e) {
    e.preventDefault();
    var $ = jQuery;
    if ($(this).hasClass('active')) {
        $(this).removeClass('active');
    } else {
        $(this).addClass('active');
    }
    if ($('#menu').hasClass('active')) {
        $('#menu').removeClass('active');
    } else {
        $('#menu').addClass('active');
    }
});

$(document).on("click", '.has-subnav', function (e) {
    e.preventDefault();
    var $this = jQuery(this);
    $this.parent('li').find('> ul').toggleClass('active');
});

jQuery(document).on('click', '.careerfy-nav-toogle', function () {
    'use strict';
    var _this = jQuery(this);
    var nav_bar = jQuery('.careerfy-nav-area');
    if (nav_bar.hasClass('nav-active')) {
        nav_bar.removeClass('nav-active');
        _this.find('img').attr('src', careerfy_funnc_vars.nav_open_img);
        nav_bar.hide('slide', {direction: 'left'}, 500);
    } else {
        nav_bar.addClass('nav-active');
        _this.find('img').attr('src', careerfy_funnc_vars.nav_close_img);
        nav_bar.show("slide", {direction: "left"}, 500);
    }
});


jQuery(".navbar-nav .sub-menu").parent("li").addClass("submenu-addicon");





jQuery(window).on("load", function() {
  jQuery("body").addClass("active");
  jQuery("body").removeClass("careerfy-page-loading");
});