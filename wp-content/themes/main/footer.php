<?php 
	$zanbil_copyright_text =zanbil_options()->getCpanelValue('footer_copyright');
?>
	<footer id="footer" class="footer default theme-clearfix" >
		<div class="footer-top">
			<div class="sidebar-footer">
				<div class="container">
					<div class="row">
						<?php if (is_active_sidebar_ZANBIL('footer')){ ?>		
								<?php dynamic_sidebar('footer'); ?>			
						<?php } ?>
					</div>
				</div>
			</div>					
		</div>
		<div class="copyright theme-clearfix clearfix">
			<?php if (is_active_sidebar_ZANBIL('footer-copyright')){ ?>
				<div class="sidebar-copyright clearfix">
					<?php dynamic_sidebar('footer-copyright'); ?>
				</div>
			<?php } ?>
			<div class="copyright-text">
			    <div class="container">
					<div class="copyright-footer pull-left">
							<p><?php  echo wp_kses( $zanbil_copyright_text, array( 'a' => array( 'href' => array(), 'title' => array(), 'class' => array() ), 'p' => array()  ) ) ; ?></p>
					</div>				
					<div class="payment">
                        <a class="payment1" title="پشتیبانی از تمام درگاه های عضو شتاب" href="#"></a>
					</div>
                    <?php if(zanbil_options()->getCpanelValue('back_active') == '1') { ?>
<a id="zanbil-totop" href="#" ></a>
<?php }?>
				</div>
			</div>
		</div>
	</footer>
</div>
<?php wp_footer(); ?>
</body>
</html>