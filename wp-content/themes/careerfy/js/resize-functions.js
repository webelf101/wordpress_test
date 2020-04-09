jQuery(window).resize(function(){
    'use strict';
    var $ = jQuery;
    var $menu = $('#menu'),
    $menulink = $('.menu-link');
    $menu.removeClass('active');
    $menulink.addClass('active');
    
    if (jQuery('.careerfy-header-one').length > 0) {
        var sub_nav = jQuery('.careerfy-header-one').find('.navigation-sub');
        var sub_nav_html = sub_nav.html();
        if(window.innerWidth < 765) {
            var car_right_dev = jQuery('.careerfy-header-one').find('.careerfy-right');
            sub_nav.remove();
            car_right_dev.html('<div class="navigation-sub">' + sub_nav_html + '</div>' + car_right_dev.html());
        } else {
            var logo_con = jQuery('.careerfy-header-one').find('.careerfy-logo-con');
            sub_nav.remove();
            logo_con.html(logo_con.html() + '<div class="navigation-sub">' + sub_nav_html + '</div>');
        }
    }

    if (jQuery('.careerfy-header-three').length > 0) {
        var sub_nav = jQuery('.careerfy-header-three').find('.navigation-subthree');
        var sub_nav_html = sub_nav.html();
        if(window.innerWidth > 520 && window.innerWidth < 980) {
            var car_right_dev = jQuery('.careerfy-header-three').find('.careerfy-logo-con');
            sub_nav.remove();
            if (car_right_dev.find('.navigation-subthree').length == 0) {
                car_right_dev.html('<div class="navigation-subthree">' + sub_nav_html + '</div>' + car_right_dev.html());
            }
        } else {
            var logo_con = jQuery('.careerfy-header-three').find('.careerfy-right');
            sub_nav.remove();
            if (logo_con.find('.navigation-subthree').length == 0) {
                logo_con.html(logo_con.html() + '<div class="navigation-subthree">' + sub_nav_html + '</div>');
            }
        }
    }
});
