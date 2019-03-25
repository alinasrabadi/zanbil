<?php if(!defined('ABSPATH')) die('Direct access denied.'); ?>

<?php
// For description of variables go to: http://www.codefleet.net/cyclone-slider-2/#template-variables
?>
<div style="position:relative">
<div class="cycloneslider cycloneslider-template-thumbnails cycloneslider-width-<?php echo esc_attr( $slider_settings['width_management'] ); ?>"
    id="<?php echo esc_attr( $slider_html_id ); ?>"
    >
	<div class="cycloneslider-slides cycle-slideshow"
		data-cycle-allow-wrap="<?php echo esc_attr( $slider_settings['allow_wrap'] ); ?>"
        data-cycle-dynamic-height="<?php echo esc_attr( $slider_settings['dynamic_height'] ); ?>"
        data-cycle-auto-height="<?php echo esc_attr( $slider_settings['auto_height'] ); ?>"
        data-cycle-auto-height-easing="<?php echo esc_attr( $slider_settings['auto_height_easing'] ); ?>"
        data-cycle-auto-height-speed="<?php echo esc_attr( $slider_settings['auto_height_speed'] ); ?>"
        data-cycle-delay="<?php echo esc_attr( $slider_settings['delay'] ); ?>"
        data-cycle-easing="<?php echo esc_attr( $slider_settings['easing'] ); ?>"
        data-cycle-fx="<?php echo esc_attr( $slider_settings['fx'] ); ?>"
        data-cycle-hide-non-active="<?php echo esc_attr( $slider_settings['hide_non_active'] ); ?>"
        data-cycle-log="false"
        data-cycle-pause-on-hover="<?php echo esc_attr( $slider_settings['hover_pause'] ); ?>"
        data-cycle-slides="&gt; div"
        data-cycle-speed="<?php echo esc_attr( $slider_settings['speed'] ); ?>"
        data-cycle-swipe="<?php echo esc_attr( $slider_settings['swipe'] ); ?>"
        data-cycle-tile-count="<?php echo esc_attr( $slider_settings['tile_count'] ); ?>"
        data-cycle-tile-delay="<?php echo esc_attr( $slider_settings['tile_delay'] ); ?>"
        data-cycle-tile-vertical="<?php echo esc_attr( $slider_settings['tile_vertical'] ); ?>"
        data-cycle-timeout="<?php echo esc_attr( $slider_settings['timeout'] ); ?>"
		>
		<?php foreach($slides as $slide): ?>
                <div class="cycloneslider-slide cycloneslider-slide-image" <?php echo $slide['slide_data_attributes']; ?>>
                    <?php if( 'lightbox' == $slide['link_target'] ): ?>
                        <a class="cycloneslider-caption-more magnific-pop" href="<?php echo esc_url( $slide['full_image_url'] ); ?>" alt="<?php echo $slide['img_alt'];?>">
                    <?php elseif ( '' != $slide['link'] ) : ?>
                        <?php if( '_blank' == $slide['link_target'] ): ?>
                            <a class="cycloneslider-caption-more" target="_blank" href="<?php echo esc_url( $slide['link'] );?>">
                        <?php else: ?>
                            <a class="cycloneslider-caption-more" href="<?php echo esc_url( $slide['link'] );?>">
                        <?php endif; ?>
                    <?php endif; ?>

                    <img src="<?php echo $slide['image_url']; ?>" alt="<?php echo $slide['img_alt'];?>" title="<?php echo $slide['img_title'];?>" style="width:100%" />
                    
                    <?php if( 'lightbox' == $slide['link_target'] or ('' != $slide['link']) ) : ?>
                        </a>
                    <?php endif; ?>
                </div>
        <?php endforeach; ?>
	</div>
</div>
    <?php if ($slider_settings['show_nav']) : ?>
<div id="<?php echo $slider_html_id; ?>-pager" class="cycloneslider-template-thumbnails cycloneslider-thumbnails"
	<?php echo ( 'responsive' == $slider_settings['width_management'] ) ? 'style="max-width:'.esc_attr( $slider_settings['width'] ).'px"' : ''; ?>
    <?php echo ( 'fixed' == $slider_settings['width_management'] ) ? 'style="width:'.esc_attr( $slider_settings['width'] ).'px"' : ''; ?>
    >
	<ul class="clearfix">
		<?php foreach($slides as $i=>$slide): ?>
			<?php if ( 'image' == $slide['type'] ) : ?>
                <li>
					<?php echo wp_kses_post( $slide['title'] );?>
				</li>
            <?php endif; ?>
		<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>
</diV>