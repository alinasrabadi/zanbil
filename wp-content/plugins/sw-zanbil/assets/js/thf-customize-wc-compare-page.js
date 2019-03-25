/*! jquery.kinetic - v2.2.4 - 2017-09-10 https://davetayls.me/jquery.kinetic
 * Copyright (c) 2017 Dave Taylor (https://davetayls.me); Licensed MIT */
!function (t) {
    "use strict";
    window.requestAnimationFrame || (window.requestAnimationFrame = window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame || function (t, e) {
        window.setTimeout(t, 1e3 / 60)
    }), t.support = t.support || {}, t.extend(t.support, {touch: "ontouchend" in document});
    var e = function (e, s) {
        return this.settings = s, this.el = e, this.$el = t(e), this._initElements(), this
    };
    e.DATA_KEY = "kinetic", e.DEFAULTS = {
        cursor           : "move",
        decelerate       : !0,
        triggerHardware  : !1,
        threshold        : 0,
        y                : !0,
        x                : !0,
        slowdown         : .9,
        maxvelocity      : 40,
        throttleFPS      : 60,
        invert           : !1,
        movingClass      : {
            up   : "kinetic-moving-up",
            down : "kinetic-moving-down",
            left : "kinetic-moving-left",
            right: "kinetic-moving-right"
        },
        deceleratingClass: {
            up   : "kinetic-decelerating-up",
            down : "kinetic-decelerating-down",
            left : "kinetic-decelerating-left",
            right: "kinetic-decelerating-right"
        }
    }, e.prototype.start = function (e) {
        this.settings = t.extend(this.settings, e), this.velocity = e.velocity || this.velocity, this.velocityY = e.velocityY || this.velocityY, this.settings.decelerate = !1, this._move()
    }, e.prototype.end = function () {
        this.settings.decelerate = !0
    }, e.prototype.stop = function () {
        this.velocity = 0, this.velocityY = 0, this.settings.decelerate = !0, t.isFunction(this.settings.stopped) && this.settings.stopped.call(this)
    }, e.prototype.detach = function () {
        this._detachListeners(), this.$el.removeClass("kinetic-active").css("cursor", "")
    }, e.prototype.attach = function () {
        this.$el.hasClass("kinetic-active") || (this._attachListeners(this.$el), this.$el.addClass("kinetic-active").css("cursor", this.settings.cursor))
    }, e.prototype.destroy = function () {
        this.detach(), this.$el = null, this.el = null, this.settings = null
    }, e.prototype._initElements = function () {
        this.$el.addClass("kinetic-active"), t.extend(this, {
            xpos           : null,
            prevXPos       : !1,
            ypos           : null,
            prevYPos       : !1,
            mouseDown      : !1,
            throttleTimeout: 1e3 / this.settings.throttleFPS,
            lastMove       : null,
            elementFocused : null
        }), this.velocity = 0, this.velocityY = 0, t(document).mouseup(t.proxy(this._resetMouse, this)).click(t.proxy(this._resetMouse, this)), this._initEvents(), this.$el.css("cursor", this.settings.cursor), this.settings.triggerHardware && this.$el.css({
            "-webkit-transform"          : "translate3d(0,0,0)",
            "-webkit-perspective"        : "1000",
            "-webkit-backface-visibility": "hidden"
        })
    }, e.prototype._initEvents = function () {
        var e = this;
        this.settings.events = {
            touchStart    : function (t) {
                var s;
                e._useTarget(t.target, t) && (s = t.originalEvent.touches[0], e.threshold = e._threshold(t.target, t), e._start(s.clientX, s.clientY), t.stopPropagation())
            }, touchMove  : function (t) {
                var s;
                e.mouseDown && (s = t.originalEvent.touches[0], e._inputmove(s.clientX, s.clientY), t.preventDefault && t.preventDefault())
            }, inputDown  : function (t) {
                e._useTarget(t.target, t) && (e.threshold = e._threshold(t.target, t), e._start(t.clientX, t.clientY), e.elementFocused = t.target, "IMG" === t.target.nodeName && t.preventDefault(), t.stopPropagation())
            }, inputEnd   : function (t) {
                e._useTarget(t.target, t) && (e._end(), e.elementFocused = null, t.preventDefault && t.preventDefault())
            }, inputMove  : function (t) {
                e.mouseDown && (e._inputmove(t.clientX, t.clientY), t.preventDefault && t.preventDefault())
            }, scroll     : function (s) {
                t.isFunction(e.settings.moved) && e.settings.moved.call(e, e.settings), s.preventDefault && s.preventDefault()
            }, inputClick : function (t) {
                if (Math.abs(e.velocity) > 0) {
                    return t.preventDefault(), !1
                }
            }, dragStart  : function (t) {
                if (e._useTarget(t.target, t) && e.elementFocused) {
                    return !1
                }
            }, selectStart: function (s) {
                return t.isFunction(e.settings.selectStart) ? e.settings.selectStart.apply(e, arguments) : !e._useTarget(s.target, s) && void 0
            }
        }, this._attachListeners(this.$el, this.settings)
    }, e.prototype._inputmove = function (e, s) {
        var i = this.$el;
        this.el;
        if ((!this.lastMove || new Date > new Date(this.lastMove.getTime() + this.throttleTimeout)) && (this.lastMove = new Date, this.mouseDown && (this.xpos || this.ypos))) {
            var o = e - this.xpos, n = s - this.ypos;
            if (this.settings.invert && (o *= -1, n *= -1), this.threshold > 0) {
                var l = Math.sqrt(o * o + n * n);
                if (this.threshold > l) {
                    return;
                }
                this.threshold = 0
            }
            this.elementFocused && (t(this.elementFocused).blur(), this.elementFocused = null, i.focus()), this.settings.decelerate = !1, this.velocity = this.velocityY = 0;
            var r = this.scrollLeft(), c = this.scrollTop();
            this.scrollLeft(this.settings.x ? r - o : r), this.scrollTop(this.settings.y ? c - n : c), this.prevXPos = this.xpos, this.prevYPos = this.ypos, this.xpos = e, this.ypos = s, this._calculateVelocities(), this._setMoveClasses(this.settings.movingClass), t.isFunction(this.settings.moved) && this.settings.moved.call(this, this.settings)
        }
    }, e.prototype._calculateVelocities = function () {
        this.velocity = this._capVelocity(this.prevXPos - this.xpos, this.settings.maxvelocity), this.velocityY = this._capVelocity(this.prevYPos - this.ypos, this.settings.maxvelocity), this.settings.invert && (this.velocity *= -1, this.velocityY *= -1)
    }, e.prototype._end = function () {
        this.xpos && this.prevXPos && !1 === this.settings.decelerate && (this.settings.decelerate = !0, this._calculateVelocities(), this.xpos = this.prevXPos = this.mouseDown = !1, this._move())
    }, e.prototype._useTarget = function (e, s) {
        return !t.isFunction(this.settings.filterTarget) || !1 !== this.settings.filterTarget.call(this, e, s)
    }, e.prototype._threshold = function (e, s) {
        return t.isFunction(this.settings.threshold) ? this.settings.threshold.call(this, e, s) : this.settings.threshold
    }, e.prototype._start = function (t, e) {
        this.mouseDown = !0, this.velocity = this.prevXPos = 0, this.velocityY = this.prevYPos = 0, this.xpos = t, this.ypos = e
    }, e.prototype._resetMouse = function () {
        this.xpos = !1, this.ypos = !1, this.mouseDown = !1
    }, e.prototype._decelerateVelocity = function (t, e) {
        return 0 === Math.floor(Math.abs(t)) ? 0 : t * e
    }, e.prototype._capVelocity = function (t, e) {
        var s = t;
        return t > 0 ? t > e && (s = e) : t < 0 - e && (s = 0 - e), s
    }, e.prototype._setMoveClasses = function (t) {
        var e = this.settings, s = this.$el;
        s.removeClass(e.movingClass.up).removeClass(e.movingClass.down).removeClass(e.movingClass.left).removeClass(e.movingClass.right).removeClass(e.deceleratingClass.up).removeClass(e.deceleratingClass.down).removeClass(e.deceleratingClass.left).removeClass(e.deceleratingClass.right), this.velocity > 0 && s.addClass(t.right), this.velocity < 0 && s.addClass(t.left), this.velocityY > 0 && s.addClass(t.down), this.velocityY < 0 && s.addClass(t.up)
    }, e.prototype._move = function () {
        var e = this._getScroller()[0], s = this, i = s.settings;
        i.x && e.scrollWidth > 0 ? (this.scrollLeft(this.scrollLeft() + this.velocity), Math.abs(this.velocity) > 0 && (this.velocity = i.decelerate ? s._decelerateVelocity(this.velocity, i.slowdown) : this.velocity)) : this.velocity = 0, i.y && e.scrollHeight > 0 ? (this.scrollTop(this.scrollTop() + this.velocityY), Math.abs(this.velocityY) > 0 && (this.velocityY = i.decelerate ? s._decelerateVelocity(this.velocityY, i.slowdown) : this.velocityY)) : this.velocityY = 0, s._setMoveClasses(i.deceleratingClass), t.isFunction(i.moved) && i.moved.call(this, i), Math.abs(this.velocity) > 0 || Math.abs(this.velocityY) > 0 ? this.moving || (this.moving = !0, window.requestAnimationFrame(function () {
            s.moving = !1, s._move()
        })) : s.stop()
    }, e.prototype._getScroller = function () {
        var e = this.$el;
        return (this.$el.is("body") || this.$el.is("html")) && (e = t(window)), e
    }, e.prototype.scrollLeft = function (t) {
        var e = this._getScroller();
        if ("number" != typeof t) {
            return e.scrollLeft();
        }
        e.scrollLeft(t), this.settings.scrollLeft = t
    }, e.prototype.scrollTop = function (t) {
        var e = this._getScroller();
        if ("number" != typeof t) {
            return e.scrollTop();
        }
        e.scrollTop(t), this.settings.scrollTop = t
    }, e.prototype._attachListeners = function () {
        var e = this.$el, s = this.settings;
        t.support.touch && e.bind("touchstart", s.events.touchStart).bind("touchend", s.events.inputEnd).bind("touchmove", s.events.touchMove), e.mousedown(s.events.inputDown).mouseup(s.events.inputEnd).mousemove(s.events.inputMove), e.click(s.events.inputClick).scroll(s.events.scroll).bind("selectstart", s.events.selectStart).bind("dragstart", s.events.dragStart)
    }, e.prototype._detachListeners = function () {
        var e = this.$el, s = this.settings;
        t.support.touch && e.unbind("touchstart", s.events.touchStart).unbind("touchend", s.events.inputEnd).unbind("touchmove", s.events.touchMove), e.unbind("mousedown", s.events.inputDown).unbind("mouseup", s.events.inputEnd).unbind("mousemove", s.events.inputMove), e.unbind("click", s.events.inputClick).unbind("scroll", s.events.scroll).unbind("selectstart", s.events.selectStart).unbind("dragstart", s.events.dragStart)
    }, t.Kinetic = e, t.fn.kinetic = function (s, i) {
        return this.each(function () {
            var o = t(this), n = o.data(e.DATA_KEY), l = t.extend({}, e.DEFAULTS, o.data(), "object" == typeof s && s);
            n || o.data(e.DATA_KEY, n = new e(this, l)), "string" == typeof s && n[s](i)
        })
    }
}(window.jQuery || window.Zepto);

const THF_Customize_WC_Compare_Page = {

    init() {
        if (isset(window.THF_WOOCOMPARE_PAGE)) {
            window.THF_WOOCOMPARE_PAGE = null;
            delete window.THF_WOOCOMPARE_PAGE;
        }

        window.THF_WOOCOMPARE_PAGE = THF_Customize_WC_Compare_Page.constructor();
    },

    constructor() {
        const _self = this;

        _self.defineVariables();
        _self.clear();
        _self.setup();
        _self.events();
    },

    defineVariables() {
        const _self = this;

        _self.$doc = jQuery(document);
        _self.$window = jQuery(window);

        _self.$compare = jQuery('.js-compare-products-container', _self.$doc);
        _self.$compareHeader = jQuery('.c-compare__list--header', _self.$compare);
        _self.$compareProductRemoveBtn = jQuery('.js-remove-compare-product', _self.$compare);

        _self.$modal = jQuery('#addNewProductModal', _self.$doc);
        _self.$modalSearchForm = jQuery('form', _self.$modal);
        _self.$modalSearchInput = jQuery('input[name="search-product"]', _self.$modal);
        _self.$modalSelectBrand = jQuery('select[name="product-brand"]', _self.$modal);
        _self.$modalProductslist = jQuery('.products-list', _self.$modal);
        _self.$modalProduct = jQuery('.product', _self.$modalProductslist);
        _self.$modalSpinner = jQuery('.modal-spinner', _self.$modal);
        _self.$modalSpinnerSearchIcon = _self.$modalSpinner.html();
        _self.$modalSpinnerIcon = THF.spinner;
        _self.$modalCategories = _self.$modal.attr('data-categories');

        _self.searchTypeDelay = 700;
        _self.searchMinCharsToSearch = 3;
        _self.searchToBeExecuted = null;

        // _self.searchCurrentData = {};
        // _self.searchTimeout = null;
        // _self.searchCache = {};

    },

    clear() {
        const _self = this;

        _self.$compare.unbind();
        _self.$compareHeader.unbind();
        _self.$compareProductRemoveBtn.unbind();

        // _self.$modal.unbind();
        _self.$modalSearchForm.unbind();
        _self.$modalProductslist.unbind();
        _self.$modalProduct.unbind();
        _self.$modalSearchInput.unbind();
        _self.$modalSelectBrand.unbind();
        _self.$modalSpinner.unbind();

        _self.clearAjaxSearch()
    },

    setup() {
        const _self = this;

        // Compare List
        _self.stickyCompareHeader();
        _self.disableImagesDragable();
        _self.headerDragScroll();

        // Modal
        _self.$modalSearchInput.attr('autocomplete', 'off');
        _self.modalMultipleSelectBrandSetup();

    },

    events() {
        const _self = this;

        /**
         * Modal Ajax Search
         */

        //-> on form submit
        _self.$modalSearchForm.submit(function (e) {
            e.preventDefault();

            _self.clearSearchTimeout();
            _self.modalUpdateProductsList();

            return false;
        });

        //-> on user start typing
        _self.$modalSearchInput.keyup(function (event) {
            let searchValue         = jQuery.trim(jQuery(this).val()),
                searchLength        = searchValue.length,
                charCode            = String.fromCharCode(event.keyCode),
                isWordCharacter     = charCode.match(/\w/),
                isBackspaceOrDelete = (event.keyCode === 8 || event.keyCode === 46);

            _self.clearSearchTimeout();

            if (isWordCharacter || isBackspaceOrDelete) {
                if (searchLength === 0) {
                    _self.modalUpdateProductsList()
                } else if (searchLength >= _self.searchMinCharsToSearch) {
                    _self.searchTimeout = setTimeout(() => _self.modalUpdateProductsList(), _self.searchTypeDelay);
                } else {
                    _self.modalSpinner('hide');
                }
            }
        });

        //-> on select Brand
        _self.$modalSelectBrand.change(function () {
            _self.clearSearchTimeout();
            _self.modalUpdateProductsList();
        });

        /**
         * Add new product to compare list
         */
        _self.$modalProduct.click(function () {
            _self.compareProductAction(jQuery(this).attr('data-id'), 'add')
        });

        /**
         * Remove product form compare list
         */
        _self.$compareProductRemoveBtn.click(function () {
            _self.compareProductAction(jQuery(this).attr('data-id'), 'remove');
        });

        /**
         * Sticky compare list header
         */
        _self.$window.scroll(() => _self.stickyCompareHeader());
    },

    clearAjaxSearch() {
        const _self = this;

        // _self.clearSearchCurrentData();
        _self.clearSearchTimeout();
        // _self.clearSearchCache();
    },

    clearSearchCurrentData() {
        const _self = this;

        _self.searchCurrentData = {};
    },

    clearSearchTimeout() {
        const _self = this;

        if (_self.searchTimeout) {
            clearTimeout(_self.searchTimeout);
        }
    },

    clearSearchCache() {
        const _self = this;

        _self.searchCache = null;
    },

    compareProductAction(pid, action) {
        const _self = this;

        if (isset(pid) && isset(action)) {

            if (action === 'remove') {
                let $product    = jQuery('[data-id="' + pid + '"]', _self.$compare),
                    childsCount = jQuery('.c-compare__list li', _self.$compare).children().length - 1;

                ajaxRequest({
                    data      : {
                        action: 'thf_woocompare_remove_product',
                        pid   : pid,
                    },
                    beforeSend: function () {
                        $product.addClass('loading');
                    },
                    success   : function (respons) {
                        if (respons.success) {
                            if (childsCount === 1) {
                                thfScrollTo(_self.$compare.selector, function () {
                                    $product.remove();
                                    jQuery('.c-compare-quick__title', _self.$compare).remove();
                                    jQuery('.c-compare-quick__list', _self.$compare).remove();
                                });
                            } else {
                                $product.remove();
                            }

                            _self.modalUpdateProductsList(true);
                        }
                    }
                });
            } else if (action === 'add') {
                let $product = jQuery('[data-id="' + pid + '"]', _self.$modal);

                ajaxRequest({
                    data      : {
                        action: 'thf_woocompare_add_product',
                        pid   : pid,
                    },
                    beforeSend: function () {
                        $product.addClass('loading');
                    },
                    success   : function (response) {
                        if (response.success) {
                            _self.$compare.html(jQuery(jQuery.parseHTML(response.data.html)).contents());
                            $product.parent().remove();

                            _self.init();
                        }
                    }
                });
            }
        }
    },

    disableImagesDragable() {
        const _self = this;

        _self.$doc.on('dragstart', 'img', function (event) {
            event.preventDefault();
        });
    },

    stickyCompareHeader() {
        const _self = this;

        let scrollTop = _self.$window.scrollTop(),
            offsetTop = _self.$compareHeader.offset().top,
            stickyTop = 0;

        stickyTop += wpAdminMenuHeight();
        stickyTop += stickyHeaderMenuHeight();

        scrollTop += stickyTop;

        if (scrollTop >= offsetTop) {
            _self.$compare.addClass('is-sticky-enable').find('.c-compare__list--header').css({
                top: stickyTop
            });
        } else {
            _self.$compare.removeClass('is-sticky-enable').find('.c-compare__list--header').removeAttr('style');
        }
    },

    headerDragScroll() {
        const _self    = this,
              $header  = jQuery('.c-compare__list li', _self.$compare),
              $content = jQuery('.c-compare-quick__list > li', _self.$compare);

        $header.kinetic({
            y: false,

            moved: function (event) {
                $content.scrollLeft(event.scrollLeft);
            },

            filterTarget: function (target, e) {
                if (!/down|start/.test(e.type)) {
                    return !/area|a|input/i.test(target.tagName);
                }
            }
        });
    },

    modalMultipleSelectBrandSetup() {
        const _self = this;

        // if (jQuery.fn.fSelect) {
        //     _self.$modalSelectBrand.fSelect({
        //         placeholder  : 'تمامی برند ها',
        //         numDisplayed : 2,
        //         overflowText : '{n} انتخاب شده',
        //         noResultsText: 'موردی یافت نشد',
        //         searchText   : 'جستجو',
        //         showSearch   : true
        //     });
        // }
    },

    modalUpdateProductsList(skip_cache = false) {
        const _self = this;

        if (!is_empty(_self.searchToBeExecuted)) {
            _self.searchToBeExecuted.abort();
            _self.searchToBeExecuted = null;
        }

        _self.searchCurrentData = {
            action    : 'thf_woocompare_update_products_list',
            search    : jQuery.trim(_self.$modalSearchInput.val()) || null,
            brands    : _self.$modalSelectBrand.val() || null,
            categories: _self.$modalCategories || null,
        };

        if (!skip_cache && compare(_self.searchCurrentData, _self.searchCache)) {
            return false;
        }

        _self.searchToBeExecuted = ajaxRequest({
            data      : _self.searchCurrentData,
            beforeSend: function () {
                _self.modalSpinner('show');
                _self.searchCache = _self.searchCurrentData;
            },
            success   : function (response) {
                _self.modalSpinner('hide');

                if (response.success) {
                    _self.$modalProductslist.html(response.data.html);
                }

                _self.init();
            }
        });

    },

    modalSpinner(action) {
        const _self       = this,
              currentIcon = _self.$modalSpinner.attr('data-icon');

        if (action === 'show' && currentIcon !== 'spinner') {
            _self.$modalSpinner.html(_self.$modalSpinnerIcon).attr('data-icon', 'spinner');
        } else if (action !== 'show' && currentIcon === 'spinner') {
            _self.$modalSpinner.html(_self.$modalSpinnerSearchIcon).attr('data-icon', 'search');
        }
    }
};


jQuery(document).ready(function () {
    // Run Main Object
    THF_Customize_WC_Compare_Page.init();
});

