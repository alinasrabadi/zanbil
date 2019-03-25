jQuery(document).ready(function ($) {


    $('.cycloneslider .cycloneslider-slides').each(function () {
        var $this      = $(this),
            id         = $this.parent().attr('id'),
            fade       = $this.attr('data-cycle-fx') === 'fade',
            $nextArrow = $($this.attr('data-cycle-next')),
            $prevArrow = $($this.attr('data-cycle-prev')),
            $pager     = $('#' + id + '-pager').find('>ul>li');


        $this.slick({
            rtl   : true,
            arrows: false,
            fade  : fade,
            dots: true,
              infinite: true,
            autoplay: true,
  autoplaySpeed: 4000,
        });

        $this.on('beforeChange', function (event, slick, currentSlide, nextSlide) {
            $pager.eq(nextSlide).addClass('current').siblings().removeClass('current');
        });

        $nextArrow.click(function () {
            $this.slick('slickNext');
        });

        $prevArrow.click(function () {
            $this.slick('slickPrev');
        });

        $pager.click(function () {
            $this.slick('slickGoTo', $(this).index())
        }).eq(0).addClass('current');

    })
});
			