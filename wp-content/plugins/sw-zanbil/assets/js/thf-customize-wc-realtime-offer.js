const THF_Customize_WC_Realtime_Offer = {

    init() {
        if (isset(window.THF_REALTIME_OFFER)) {
            window.THF_REALTIME_OFFER = null;
            delete window.THF_REALTIME_OFFER;
        }

        window.THF_REALTIME_OFFER = THF_Customize_WC_Realtime_Offer.constructor();
    },

    constructor() {
        const _self = this;

        _self.defineVariables();
        _self.setup();
    },


    defineVariables() {
        const _self = this;

        _self.$doc = jQuery(document);
        _self.$window = jQuery(window);

        _self.$realtimeOffer = jQuery('.js-realtime-offer', _self.$doc);

        _self.realtimeOfferDelay = 7000;
        _self.realtimeOfferSpeed = 500;

        window.swipers = [];
    },

    setup() {
        const _self = this;

        if (!isset(Swiper)) {
            console.error('Themesfa "Real-time Offer" Need Swiper Plugin To Work! http://idangero.us/swiper/');
            return false;
        }

        _self.$realtimeOffer.each(function (i) {
            let $this             = jQuery(this),
                $realtimeOfferBar = $this.find('.js-realtime-offer-bar'),
                $realtimeOfferBox = $this.find('.js-realtime-offer-box');

            swipers[i] = new Swiper($realtimeOfferBox.get(0), {
                slidesPerView : 1,
                allowTouchMove: false,
                lazy          : true,
                loop          : true,
                speed         : _self.realtimeOfferSpeed,
                autoplay      : {
                    delay: _self.realtimeOfferDelay
                },
                on            : {
                    init                    : function () {
                        $realtimeOfferBar.addClass('is-active');
                    },
                    slideChangeTransitionEnd: function () {
                        $realtimeOfferBar.addClass('is-active');
                    },
                    slideChange             : function () {
                        $realtimeOfferBar.removeClass('is-active');
                    },
                    resize                  : function () {
                        setTimeout(jQuery.proxy(function () {
                            this.detachEvents();
                            this.attachEvents();
                            this.update();
                            this.autoplay.run();
                        }, this), 500);
                    }
                }
            });

            $realtimeOfferBox.on('mouseenter', function (e) {
                swipers[i].pauseTime = new Date().getTime();
                swipers[i].autoplay.stop();
                $realtimeOfferBar.addClass('is-paused');
            });

            $realtimeOfferBox.on('mouseleave', function (e) {
                swipers[i].autoplay.start();
                swipers[i].pauseTime = 0;
                $realtimeOfferBar.removeClass('is-paused');
            });

        });


    },

};


jQuery(document).ready(function () {
    // Run Main Object
    THF_Customize_WC_Realtime_Offer.init();
});