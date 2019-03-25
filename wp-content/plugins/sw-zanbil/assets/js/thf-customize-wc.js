jQuery(document).ready(function ($) {
    var $window = $(window);

    var fixStickyHeader = function () {
        var $stickyMenu      = $('.sticky-menu'),
            $afterStickyMenu = $('<div class="fix-sticky-menu"></div>');

        $afterStickyMenu.css({
            width : $stickyMenu.width(),
            height: $stickyMenu.height(),
        });

        if ($stickyMenu.length > 0) {
            $('body').addClass('menu-sticked');

            if ($('.fix-sticky-menu').length <= 0) {
                $stickyMenu.after($afterStickyMenu);
            }

            $stickyMenu.css({
                top: wpAdminMenuHeight()
            });
        } else {
            $('body').removeClass('menu-sticked');
            $('.fix-sticky-menu').remove();
        }
    };

    fixStickyHeader();

    $window.scroll(function () {
        fixStickyHeader();
    });

});

function isset(value) {
    return typeof value !== 'undefined';
}

function is_empty(value) {
    return value === null || value === '';
}

function is_function(callback) {
    return typeof callback === 'function';
}


function compare(a, b) {
    var obj_str = '[object Object]',
        arr_str = '[object Array]',
        a_type  = Object.prototype.toString.apply(a),
        b_type  = Object.prototype.toString.apply(b);

    if (a_type !== b_type) {
        return false;
    }
    else if (a_type === obj_str) {
        return compareObject(a, b);
    }
    else if (a_type === arr_str) {
        return compareArray(a, b);
    }
    return (a === b);
}


function compareArray(arrayA, arrayB) {
    var a, b, i, a_type, b_type;
    // References to each other?
    if (arrayA === arrayB) {
        return true;
    }

    if (arrayA.length !== arrayB.length) {
        return false;
    }
    // sort modifies original array
    // (which are passed by reference to our method!)
    // so clone the arrays before sorting
    a = jQuery.extend(true, [], arrayA);
    b = jQuery.extend(true, [], arrayB);
    a.sort();
    b.sort();
    for (i = 0, l = a.length; i < l; i += 1) {
        a_type = Object.prototype.toString.apply(a[i]);
        b_type = Object.prototype.toString.apply(b[i]);

        if (a_type !== b_type) {
            return false;
        }

        if (compare(a[i], b[i]) === false) {
            return false;
        }
    }
    return true;
}


function compareObject(objA, objB) {

    var i, a_type, b_type;

    // Compare if they are references to each other
    if (objA === objB) {
        return true;
    }

    if (Object.keys(objA).length !== Object.keys(objB).length) {
        return false;
    }
    for (i in objA) {
        if (objA.hasOwnProperty(i)) {
            if (typeof objB[i] === 'undefined') {
                return false;
            }
            else {
                a_type = Object.prototype.toString.apply(objA[i]);
                b_type = Object.prototype.toString.apply(objB[i]);

                if (a_type !== b_type) {
                    return false;
                }
            }
        }
        if (compare(objA[i], objB[i]) === false) {
            return false;
        }
    }
    return true;
}


function getHeight(element) {
    var $element = jQuery(element);

    if ($element.length > 0) {
        return $element.outerHeight(true);
    }

    return 0;
}

function wpAdminMenuHeight() {
    return getHeight('#wpadminbar');
}

function stickyHeaderMenuHeight() {
    return getHeight('.sticky-menu');
}

function headerMenuHeight() {
    return getHeight('.yt-header-under-wrap');
}

function thfScrollTo(el, callback) {
    if (!is_empty(el)) {
        jQuery('html, body').animate({
            scrollTop: jQuery(el).offset().top - wpAdminMenuHeight() - headerMenuHeight() - 15,
        }, "normal", function () {
            if (isset(callback) && is_function(callback)) {
                callback.call(this);
            }
        });
    }
}

function thf_setCookie(cname, cvalue, exdays) {
    let d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function thf_getCookie(cname) {
    let name = cname + "=",
        ca   = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) === 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function ajaxRequest(args) {
    if (!isset(args)) {
        return false;
    }

    args = jQuery.extend({
        url  : THF.ajaxURL,
        type : 'POST',
        cache: THF.cache,
    }, args);

    args.data.security = THF.ajaxNonce;

    return jQuery.ajax(args);
}