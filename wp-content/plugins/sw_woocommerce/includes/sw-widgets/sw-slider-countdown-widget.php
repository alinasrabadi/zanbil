<?php
/**
	* SW Woocommerce Countdown Slider
	* Register Widget Woocommerce Countdown Slider
	* @author 		smartaddons
	* @version     1.0.0
**/
if ( !class_exists('sw_woo_slider_countdown_widget') ) {
	class sw_woo_slider_countdown_widget extends WP_Widget {
		/**
		 * Widget setup.
		 */
		function __construct(){
			/* Widget settings. */
			$widget_ops = array( 'classname' => 'sw_woo_slider_countdown_widget', 'description' => __('Sw Woo Slider Countdown', 'sw_woocommerce') );

			/* Widget control settings. */
			$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'sw_woo_slider_countdown_widget' );

			/* Create the widget. */
			parent::__construct( 'sw_woo_slider_countdown_widget', __('Sw Woo Slider Countdown Widget', 'sw_woocommerce'), $widget_ops, $control_ops );
			
			/* Create Shortcode */
			add_shortcode( 'woo_slide_countdown', array( $this, 'WS_Shortcode_Countdown' ) );
			
			/* Create Vc_map */
			if ( class_exists('Vc_Manager') ) {
				add_action( 'vc_before_init', array( $this, 'WS_Countdown_integrateWithVC' ) );
			}
			
		}
		public function Get_Product_Countdown(){		
			$p_slugs	= get_posts(
				array( 
					'post_type' => 'product',
					'posts_per_page' => -1,
					'meta_query' => array(
						array(
							'key' => '_sale_price',
							'value' => 0,
							'compare' => '>',
							'type' => 'NUMERIC'
						),
						array(
							'key' => '_sale_price_dates_to',
							'value' => 0,
							'compare' => '>',
							'type' => 'NUMERIC'
						)
					)
				)
			);
			if( count($p_slugs) == 0 || $p_slugs == NULL ){
				return '';
			}
			$p_slug = array( __( 'All Products', 'sw_woocommerce' ) => '' );
			
			foreach( $p_slugs as $key => $item ){
				$p_slug[$item->post_title] = $item->ID;
			}		
			return $p_slug;
		}
		/**
		* Add Vc Params
		**/
		function WS_Countdown_integrateWithVC(){
			$terms = get_terms( 'product_cat', array( 'parent' => 0, 'hide_empty' => false ) );
			if( count( $terms ) == 0 ){
				return ;
			}
			$term = array( __( 'همه دسته بندی ها', 'sw_woocommerce' ) => '' );
			foreach( $terms as $cat ){
				$term[$cat->name] = $cat -> slug;
			}		
			vc_map( array(
			  "name" => __( "تایمر پیشنهاد شگفت انگیز", 'sw_woocommerce' ),
			  "base" => "woo_slide_countdown",
			  "icon" => get_template_directory_uri().'/assets/img/vc-icon.png',
			  "class" => "",
			  "category" => __( "پارسیان", 'sw_woocommerce'),
			  "params" => array(
				 array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => __( "عنوان", 'sw_woocommerce' ),
					"param_name" => "title1",
					"value" => '',
					"description" => __( "عنوان", 'sw_woocommerce' )
				 ),
				  array(
					"type" => "attach_images",
					"holder" => "div",
					"class" => "",
					"heading" => __( "تصویر آیکون", 'sw_woocommerce' ),
					"param_name" => "image_icon",
					"value" => '',
					"description" => __( "تصویر آیکون مورد نظر را انتخاب کنید", 'sw_woocommerce' )
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "چیدمان", 'sw_woocommerce' ),
					"param_name" => "layout",
					"value" => array('طرح یک' => 'default','طرح دو' => 'layout3','طرح سه(در آپدیت حذف شد و در صورت انتخاب همان طرح دو نشان داده خواهد شد.)' => 'layout2' ),
					"description" => __( "Layout", 'sw_woocommerce' )
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "دسته بندی", 'sw_woocommerce' ),
					"param_name" => "category",
					"value" => $term,
					"description" => __( "دسته بندی ها رو اانتخاب کنید", 'sw_woocommerce' )
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "مرتب سازی", 'sw_woocommerce' ),
					"param_name" => "orderby",
					"value" => array('نام' => 'name', 'نویسنده' => 'author', 'تاریخ' => 'date', 'اخیرا ویرایش شده' => 'modified', 'والد' => 'parent', 'آیدی' => 'ID', 'تصادفی' =>'rand', 'تعداد نظرات' => 'comment_count'),
					"description" => __( "مرتب سازی", 'sw_woocommerce' )
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "ترتیب", 'sw_woocommerce' ),
					"param_name" => "order",
					"value" => array('نزولی' => 'DESC', 'صعودی' => 'ASC'),
					"description" => __( "ترتیب", 'sw_woocommerce' )
				 ),
				 array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => __( "تعداد محصولات", 'sw_woocommerce' ),
					"param_name" => "numberposts",
					"value" => 5,
					"description" => __( "تعداد محصولات", 'sw_woocommerce' )
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "تعداد ستون در هر ردیف", 'sw_woocommerce' ),
					"param_name" => "item_row",
					"value" =>array(1,2,3),
					"description" => __( "تعداد ستون در هر ردیف", 'sw_woocommerce' ),
					'dependency' => array(
						'element' => 'layout',
						'value' => array('default'),
					),
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "تعداد ستون در هر ردیف در >1200px:", 'sw_woocommerce' ),
					"param_name" => "columns",
					"value" => array(1,2,3,4,5,6),
					"description" => __( "تعداد ستون در هر ردیف در >1200px:", 'sw_woocommerce' ),
					'dependency' => array(
						'element' => 'layout',
						'value' => array('default'),
					),
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "جا به جایی خودکار", 'sw_woocommerce' ),
					"param_name" => "autoplay",
					"value" => array( 'خیر' => 'false', 'بله' => 'true' ),
					"description" => __( "جا به جایی خودکار", 'sw_woocommerce' ),
					'dependency' => array(
						'element' => 'layout',
						'value' => array('default'),
					),
				 ),
				 array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => __( "فاصله زمانی", 'sw_woocommerce' ),
					"param_name" => "interval",
					"value" => 5000,
					"description" => __( "فاصله زمانی", 'sw_woocommerce' ),
					'dependency' => array(
						'element' => 'layout',
						'value' => array('default'),
					),
				 ),
				
				 array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => __( "تعداد ایتم ها در هر اسلاید", 'sw_woocommerce' ),
					"param_name" => "scroll",
					"value" => 1,
					"description" => __( "تعداد ایتم ها در هر اسلاید", 'sw_woocommerce' ),
					'dependency' => array(
						'element' => 'layout',
						'value' => array('default'),
					),
				 ),
			  )
		   ) );
		}
		/**
			** Add Shortcode
		**/
		function WS_Shortcode_Countdown( $atts, $content = null ){
			extract( shortcode_atts(
				array(
					'title1' => '',	
					'image_icon' => '',
					'p_countdown' =>'',
					'image'	=>'',
					'product_select' => '',
					'orderby' => 'name',
					'order'	=> 'DESC',
					'category' => '',
					'numberposts' => 5,
					'length' => 25,
					'item_row'=> 1,
					'columns' => 3,
					'autoplay' => 'false',
					'interval' => 5000,
					'layout'  => 'default',
					'scroll' => 1
				), $atts )
			);
			ob_start();	 
			if ($layout == 'default'){
				include( plugin_dir_path(dirname(__FILE__)).'/themes/sw-countdown-slider/countdown.php' );
			}elseif ( $layout == 'layout3' ){
				include( plugin_dir_path(dirname(__FILE__)).'/themes/sw-countdown-slider/tab-countdownt.php' );			
			}elseif ($layout == 'layout2') {
				include( plugin_dir_path(dirname(__FILE__)).'/themes/sw-countdown-slider/tab-countdownt-2.php' );	
			}
			$content = ob_get_clean();
			
			return $content;
		}
		/**
			* Cut string
		**/
		public function ya_trim_words( $text, $num_words = 30, $more = null ) {
			$text = strip_shortcodes( $text);
			$text = apply_filters('the_content', $text);
			$text = str_replace(']]>', ']]&gt;', $text);
			return wp_trim_words($text, $num_words, $more);
		}
		/**
		 * Display the widget on the screen.
		 */
		public function widget( $args, $instance ) {
			wp_reset_postdata();
			extract($args);
			$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
			echo $before_widget;
			if ( !empty( $title ) ) { echo $before_title . $title . $after_title; }
			
			if ( !isset($instance['category']) ){
				$instance['category'] = array();
			}
			extract($instance);

			if ( !array_key_exists('widget_template', $instance) ){
				$instance['widget_template'] = 'default';
			}
			if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { 
				_e('Please active woocommerce plugin or install woomcommerce plugin first', 'sw_woocommerce');
				return false;
			}
			if ( $tpl = $this->getTemplatePath( $instance['widget_template'] ) ){ 			
				$link_img = plugins_url('images/', __FILE__);
				$widget_id = $args['widget_id'];		
				include $tpl;
			}
					
			/* After widget (defined by themes). */
			echo $after_widget;
		}    

		protected function getTemplatePath($tpl='default', $type=''){
			$file = '/'.$tpl.$type.'.php';
			$dir =	plugin_dir_path(dirname(__FILE__)).'/themes/sw-countdown-slider';
			
			if ( file_exists( $dir.$file ) ){
				return $dir.$file;
			}
			
			return $tpl=='default' ? false : $this->getTemplatePath('default', $type);
		}
		
		/**
		 * Update the widget settings.
		 */
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			// strip tag on text field
			$instance['title1'] = strip_tags( $new_instance['title1'] );
			$instance['description1'] = strip_tags( $new_instance['description1'] );
			// int or array
			if ( array_key_exists('category', $new_instance) ){
				if ( is_array($new_instance['category']) ){
					$instance['category'] = array_map( 'intval', $new_instance['category'] );
				} else {
					$instance['category'] = intval($new_instance['category']);
				}
			}
			
			if ( array_key_exists('product_select', $new_instance) ){
				$instance['product_select'] = intval( $new_instance['product_select'] );
			}
			
			if ( array_key_exists('image', $new_instance) ){
				$instance['image'] = strip_tags( $new_instance['image'] );
			}
			
			if ( array_key_exists('orderby', $new_instance) ){
				$instance['orderby'] = strip_tags( $new_instance['orderby'] );
			}

			if ( array_key_exists('order', $new_instance) ){
				$instance['order'] = strip_tags( $new_instance['order'] );
			}

			if ( array_key_exists('numberposts', $new_instance) ){
				$instance['numberposts'] = intval( $new_instance['numberposts'] );
			}

			if ( array_key_exists('length', $new_instance) ){
				$instance['length'] = intval( $new_instance['length'] );
			}
			
			if ( array_key_exists('item_row', $new_instance) ){
				$instance['item_row'] = intval( $new_instance['item_row'] );
			}
			
			if ( array_key_exists('columns', $new_instance) ){
				$instance['columns'] = intval( $new_instance['columns'] );
			}
			if ( array_key_exists('start', $new_instance) ){
				$instance['start'] = intval( $new_instance['start'] );
			}
			if ( array_key_exists('scroll', $new_instance) ){
				$instance['scroll'] = intval( $new_instance['scroll'] );
			}	
			if ( array_key_exists('autoplay', $new_instance) ){
				$instance['autoplay'] = strip_tags( $new_instance['autoplay'] );
			}
			$instance['widget_template'] = strip_tags( $new_instance['widget_template'] );
			
						
			
			return $instance;
		}

		function category_select( $field_name, $opts = array(), $field_value = null ){
			$default_options = array(
					'multiple' => false,
					'disabled' => false,
					'size' => 5,
					'class' => 'widefat',
					'required' => false,
					'autofocus' => false,
					'form' => false,
			);
			$opts = wp_parse_args($opts, $default_options);
		
			if ( (is_string($opts['multiple']) && strtolower($opts['multiple'])=='multiple') || (is_bool($opts['multiple']) && $opts['multiple']) ){
				$opts['multiple'] = 'multiple';
				if ( !is_numeric($opts['size']) ){
					if ( intval($opts['size']) ){
						$opts['size'] = intval($opts['size']);
					} else {
						$opts['size'] = 5;
					}
				}
				if (array_key_exists('allow_select_all', $opts) && $opts['allow_select_all']){
					unset($opts['allow_select_all']);
					$allow_select_all = '<option value="0">همه دسته ها</option>';
				}
			} else {
				// is not multiple
				unset($opts['multiple']);
				unset($opts['size']);
				if (is_array($field_value)){
					$field_value = array_shift($field_value);
				}
				if (array_key_exists('allow_select_all', $opts) && $opts['allow_select_all']){
					unset($opts['allow_select_all']);
					$allow_select_all = '<option value="0">All Categories</option>';
				}
			}
		
			if ( (is_string($opts['disabled']) && strtolower($opts['disabled'])=='disabled') || is_bool($opts['disabled']) && $opts['disabled'] ){
				$opts['disabled'] = 'disabled';
			} else {
				unset($opts['disabled']);
			}
		
			if ( (is_string($opts['required']) && strtolower($opts['required'])=='required') || (is_bool($opts['required']) && $opts['required']) ){
				$opts['required'] = 'required';
			} else {
				unset($opts['required']);
			}
		
			if ( !is_string($opts['form']) ) unset($opts['form']);
		
			if ( !isset($opts['autofocus']) || !$opts['autofocus'] ) unset($opts['autofocus']);
		
			$opts['id'] = $this->get_field_id($field_name);
		
			$opts['name'] = $this->get_field_name($field_name);
			if ( isset($opts['multiple']) ){
				$opts['name'] .= '[]';
			}
			$select_attributes = '';
			foreach ( $opts as $an => $av){
				$select_attributes .= "{$an}=\"{$av}\" ";
			}
			
			$categories = get_terms('product_cat');
			//print '<pre>'; var_dump($categories);
			// if (!$templates) return '';
			$all_category_ids = array();
			foreach ($categories as $cat) $all_category_ids[] = (int)$cat->term_id;
			
			$is_valid_field_value = is_numeric($field_value) && in_array($field_value, $all_category_ids);
			if (!$is_valid_field_value && is_array($field_value)){
				$intersect_values = array_intersect($field_value, $all_category_ids);
				$is_valid_field_value = count($intersect_values) > 0;
			}
			if (!$is_valid_field_value){
				$field_value = '0';
			}
		
			$select_html = '<select ' . $select_attributes . '>';
			if (isset($allow_select_all)) $select_html .= $allow_select_all;
			foreach ($categories as $cat){			
				$select_html .= '<option value="' . $cat->term_id . '"';
				if ($cat->term_id == $field_value || (is_array($field_value)&&in_array($cat->term_id, $field_value))){ $select_html .= ' selected="selected"';}
				$select_html .=  '>'.$cat->name.'</option>';
			}
			$select_html .= '</select>';
			return $select_html;
		}
		

		/**
		 * Displays the widget settings controls on the widget panel.
		 * Make use of the get_field_id() and get_field_name() function
		 * when creating your form elements. This handles the confusing stuff.
		 */
		public function form( $instance ) {

			/* Set up some default widget settings. */
			$defaults = array();
			$instance = wp_parse_args( (array) $instance, $defaults ); 		
					 
			$title1 			= isset( $instance['title1'] )    		? 	strip_tags($instance['title1']) : '';
			$description1 		= isset( $instance['description1'] )    ? 	strip_tags($instance['description1']) : '';
			$product_select 	= isset( $instance['product_select'] )  ? 	intval($instance['product_select']) : '';
			$image		 		= isset( $instance['image'] )    		? 	strip_tags($instance['image']) : '';
			$categoryid 		= isset( $instance['category'] ) 		? $instance['category'] : '';
			$orderby    		= isset( $instance['orderby'] )     	? strip_tags($instance['orderby']) : 'ID';
			$order      		= isset( $instance['order'] )       	? strip_tags($instance['order']) : 'ASC';
			$number     		= isset( $instance['numberposts'] ) 	? intval($instance['numberposts']) : 5;
			$length     		= isset( $instance['length'] )      	? intval($instance['length']) : 25;
			$item_row     		= isset( $instance['item_row'] )      	? intval($instance['item_row']) : 1;
			$columns     		= isset( $instance['columns'] )      	? intval($instance['columns']) : 3;
			$autoplay     		= isset( $instance['autoplay'] )      	? strip_tags($instance['autoplay']) : 'true';
			$interval     		= isset( $instance['interval'] )      	? intval($instance['interval']) : 5000;
			$scroll     		= isset( $instance['scroll'] )      	? intval($instance['scroll']) : 1;
			$widget_template   	= isset( $instance['widget_template'] ) ? strip_tags($instance['widget_template']) : 'default';
					   
					 
			?>		
			</p> 
			  <div style="background: Blue; color: white; font-weight: bold; text-align:center; padding: 3px"> * Data Config * </div>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('title1'); ?>"><?php _e('Title', 'sw_woocommerce')?></label>
				<br />
				<input class="widefat" id="<?php echo $this->get_field_id('title1'); ?>" name="<?php echo $this->get_field_name('title1'); ?>"
					type="text"	value="<?php echo esc_attr($title1); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('description1'); ?>"><?php _e('Description', 'sw_woocommerce')?></label>
				<br />
				<input class="widefat" id="<?php echo $this->get_field_id('description1'); ?>" name="<?php echo $this->get_field_name('description1'); ?>"
					type="text"	value="<?php echo esc_attr($description1); ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('product_select'); ?>"><?php _e('Product Select( For layout 2 )', 'sw_woocommerce')?></label>
				<br />
				<select class="widefat"
					id="<?php echo $this->get_field_id('product_select'); ?>"
					name="<?php echo $this->get_field_name('product_select'); ?>">
					<?php
					$option ='';
					$product_ids = $this->Get_Product_Countdown();
					foreach ($product_ids as $key => $value ) :
						$option .= '<option value="' . $value . '" ';
						if ($value == $product_select){
							$option .= 'selected="selected"';
						}
						$option .=  '>'.$key.'</option>';
					endforeach;
					echo $option;
					?>
				</select>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('image'); ?>"><?php _e('Image Attachment ID( for layout countdown 2 )', 'sw_woocommerce')?></label>
				<br />
				<input class="widefat" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>"
					type="text"	value="<?php echo esc_attr($image); ?>" />
			</p>
			
			<p id="wgd-<?php echo $this->get_field_id('category'); ?>">
				<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Category', 'sw_woocommerce')?></label>
				<br />
				<?php echo $this->category_select('category', array('allow_select_all' => true), $categoryid); ?>
			</p>	
			
			<p>
				<label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby', 'sw_woocommerce')?></label>
				<br />
				<?php $allowed_keys = array('name' => 'Name', 'author' => 'Author', 'date' => 'Date', 'title' => 'Title', 'modified' => 'Modified', 'parent' => 'Parent', 'ID' => 'ID', 'rand' =>'Rand', 'comment_count' => 'Comment Count'); ?>
				<select class="widefat"
					id="<?php echo $this->get_field_id('orderby'); ?>"
					name="<?php echo $this->get_field_name('orderby'); ?>">
					<?php
					$option ='';
					foreach ($allowed_keys as $value => $key) :
						$option .= '<option value="' . $value . '" ';
						if ($value == $orderby){
							$option .= 'selected="selected"';
						}
						$option .=  '>'.$key.'</option>';
					endforeach;
					echo $option;
					?>
				</select>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order', 'sw_woocommerce')?></label>
				<br />
				<select class="widefat"
					id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>">
					<option value="DESC" <?php if ($order=='DESC'){?> selected="selected"
					<?php } ?>>
						<?php _e('Descending', 'sw_woocommerce')?>
					</option>
					<option value="ASC" <?php if ($order=='ASC'){?> selected="selected"	<?php } ?>>
						<?php _e('Ascending', 'sw_woocommerce')?>
					</option>
				</select>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('numberposts'); ?>"><?php _e('Number of Posts', 'sw_woocommerce')?></label>
				<br />
				<input class="widefat" id="<?php echo $this->get_field_id('numberposts'); ?>" name="<?php echo $this->get_field_name('numberposts'); ?>"
					type="text"	value="<?php echo esc_attr($number); ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('length'); ?>"><?php _e('Excerpt length (in words): ', 'sw_woocommerce')?></label>
				<br />
				<input class="widefat"
					id="<?php echo $this->get_field_id('length'); ?>" name="<?php echo $this->get_field_name('length'); ?>" type="text" 
					value="<?php echo esc_attr($length); ?>" />
			</p> 
			<?php $row_number = array( '1' => 1, '2' => 2, '3' => 3 ); ?>
			<p>
				<label for="<?php echo $this->get_field_id('item_row'); ?>"><?php _e('Number row per column:  ', 'sw_woocommerce')?></label>
				<br />
				<select class="widefat"
					id="<?php echo $this->get_field_id('item_row'); ?>"
					name="<?php echo $this->get_field_name('item_row'); ?>">
					<?php
					$option ='';
					foreach ($row_number as $key => $value) :
						$option .= '<option value="' . $value . '" ';
						if ($value == $item_row){
							$option .= 'selected="selected"';
						}
						$option .=  '>'.$key.'</option>';
					endforeach;
					echo $option;
					?>
				</select>
			</p> 
			
			<?php $number = array('1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6); ?>
			<p>
				<label for="<?php echo $this->get_field_id('columns'); ?>"><?php _e('Number of Columns >1200px: ', 'sw_woocommerce')?></label>
				<br />
				<select class="widefat"
					id="<?php echo $this->get_field_id('columns'); ?>"
					name="<?php echo $this->get_field_name('columns'); ?>">
					<?php
					$option ='';
					foreach ($number as $key => $value) :
						$option .= '<option value="' . $value . '" ';
						if ($value == $columns){
							$option .= 'selected="selected"';
						}
						$option .=  '>'.$key.'</option>';
					endforeach;
					echo $option;
					?>
				</select>
			</p> 
			
			
			<p>
				<label for="<?php echo $this->get_field_id('autoplay'); ?>"><?php _e('Auto Play', 'sw_woocommerce')?></label>
				<br />
				<select class="widefat"
					id="<?php echo $this->get_field_id('autoplay'); ?>" name="<?php echo $this->get_field_name('autoplay'); ?>">
					<option value="false" <?php if ($autoplay=='false'){?> selected="selected"
					<?php } ?>>
						<?php _e('False', 'sw_woocommerce')?>
					</option>
					<option value="true" <?php if ($autoplay=='true'){?> selected="selected"	<?php } ?>>
						<?php _e('True', 'sw_woocommerce')?>
					</option>
				</select>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('interval'); ?>"><?php _e('Interval', 'sw_woocommerce')?></label>
				<br />
				<input class="widefat" id="<?php echo $this->get_field_id('interval'); ?>" name="<?php echo $this->get_field_name('interval'); ?>"
					type="text"	value="<?php echo esc_attr($interval); ?>" />
			</p>			
			
			<p>
				<label for="<?php echo $this->get_field_id('scroll'); ?>"><?php _e('Total Items Slided', 'sw_woocommerce')?></label>
				<br />
				<input class="widefat" id="<?php echo $this->get_field_id('scroll'); ?>" name="<?php echo $this->get_field_name('scroll'); ?>"
					type="text"	value="<?php echo esc_attr($scroll); ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('widget_template'); ?>"><?php _e("Template", 'sw_woocommerce')?></label>
				<br/>
				
				<select class="widefat"
					id="<?php echo $this->get_field_id('widget_template'); ?>"	name="<?php echo $this->get_field_name('widget_template'); ?>">
					<option value="countdown" <?php if ($widget_template=='default'){?> selected="selected"
					<?php } ?>>
						<?php _e('Default', 'sw_woocommerce')?>		
					</option>			
				</select>
			</p>  
		<?php
		}	
	}
}
?>