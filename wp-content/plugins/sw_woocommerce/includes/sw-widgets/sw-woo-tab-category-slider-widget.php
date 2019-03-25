<?php
/**
 * SW Woo Tab Category Slider Widget 
 * Description: A widget that serves as an slideshow for developing more advanced widgets.
 * Version: 1.0
 *
 * This Widget help you to show images of product as a beauty tab reponsive slideshow
 */
 if ( !class_exists('sw_woo_tab_cat_slider_widget') ) {
	class sw_woo_tab_cat_slider_widget extends WP_Widget {
       private $snumber = 1;
		/**
		 * Widget setup.
		 */
		function sw_woo_tab_cat_slider_widget() {
			/* Widget settings. */
			$widget_ops = array( 'classname' => 'sw_woo_tab_cat_slider', 'description' => 'تب اسلایدر محصول');

			/* Widget control settings. */
			$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'sw_woo_tab_cat_slider' );

			/* Create the widget. */
			parent::__construct( 'sw_woo_tab_cat_slider', __('Sw Woo Tab Category Slider widget', 'sw_woocommerce'), $widget_ops, $control_ops );
					
			add_shortcode( 'woo_tab_cat_slider', array( $this, 'SC_WooTab' ) );
			
			/* Create Vc_map */
			if (class_exists('Vc_Manager')) {
				add_action( 'vc_before_init', array( $this, 'SC_integrateWithVC' ) );
			}
			/* Ajax Call */
			add_action( 'wp_ajax_sw_ajax_tab_callback', array( $this, 'sw_ajax_tab_callback') );
			add_action( 'wp_ajax_nopriv_sw_ajax_tab_callback', array( $this, 'sw_ajax_tab_callback') );
		}
			
		public function generateID() {
			return $this->id_base . '_' . (int) $this->snumber++;
		}
		/**
			* Add Vc Params
		**/
		function SC_integrateWithVC(){
			$args = array(
				'type' => 'post',
				'child_of' => 0,
				'parent' => '',
				'orderby' => 'id',
				'order' => 'ASC',
				'hide_empty' => false,
				'hierarchical' => 1,
				'exclude' => '',
				'include' => '',
				'number' => '',
				'taxonomy' => 'product_cat',
				'pad_counts' => false,

			);
			$terms = get_terms( 'product_cat', array( 'parent' => '', 'hide_empty' => 0 ) );
			$term = array();
			foreach( $terms as $cat ){
				$term[$cat->name] = $cat -> term_id;
			}
			vc_map( array(
			  "name" => "تب اسلایدر محصول",
			  "base" => "woo_tab_cat_slider",
			  "icon" => get_template_directory_uri().'/assets/img/vc-icon.png',
			  "class" => "",
			  "category" => "زنبیل",
			  "params" => array(
				 array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" =>  "عنوان",
					"param_name" => "title1",
					"value" => '',
					"description" => "عنوان"
				 ),
				 array(
					"type" => "attach_images",
					"holder" => "div",
					"class" => "",
					"heading" => "تصویر آیکون",
					"param_name" => "image_icon",
					"value" => '',
				 ),
				 array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => "توضیحات",
					"param_name" => "description",
					"value" => '',
				 ),	
				  array(
					"type" => "my_param",
					"holder" => "div",
					"class" => "",
					"heading" => "دسته بندی",
					"param_name" => "category",
					"value" => $term,
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => "مرتب سازی محصولات بر اساس :",
					"param_name" => "select_order",
					"value" => array('جدیدترین محصولات' => 'latest', 'امتیاز' => 'rating', 'پرفروش ترین' => 'bestsales', 'محصولات ویژه' => 'featured'),
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => " ترتیب بر اساس :",
					"param_name" => "orderby",
					"value" => array('نام' => 'name', 'نویسنده' => 'author', 'تاریخ' => 'date', 'آخرین اصلاح' => 'modified', 'والد' => 'parent', 'آی دی' => 'ID', 'تصادفی' =>'rand', 'تعداد دیدگاه' => 'comment_count'),
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => "نوع ترتیب",
					"param_name" => "order",
					"value" => array('صعودی' => 'DESC', 'نزولی' => 'ASC'),
				 ),
				 array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => "تعداد محصول برای نمایش",
					"param_name" => "numberposts",
					"value" => 5,
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" =>  "تعداد ردیف محصول",
					"param_name" => "item_row",
					"value" =>array(1,2,3),
				 ),				 
				 array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => "شماره تب فعال",
					"param_name" => "tab_active",
					"value" => 1,
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => "تعداد ستون : ",
					"param_name" => "columns",
					"value" => array(1,2,3,4,5),
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => "اسلاید خودکار",
					"param_name" => "autoplay",
					"value" => array( 'False' => 'false', 'True' => 'true' ),
				 ),
				 array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => "فاصله زمانی بین هر اسلاید",
					"param_name" => "interval",
					"value" => 500,
				 ),
				  array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => "طرح",
					"param_name" => "layout",
					"value" => array( 'تب افقی' => 'default','تب عمودی سمت راست'=>'theme1' ),
				 ),
				 array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" =>  "تعداد اسلاید با هر بار کلیک",
					"param_name" => "scroll",
					"value" => 1,
				 ),
				 array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => "کلاس اضافی",
					"param_name" => "elclass",
					"value" => '',
					"description" => ""
				 ),	
			  )
		   ) );
		}
		/**
	 * Get lists of categories.
	 * @since 4.5.3
	 *
	 * @param $parent_id
	 * @param $pos
	 * @param array $array
	 * @param $level
	 * @param array $dropdown - passed by  reference
	 */
	protected function getCategoryChildsFull( $parent_id, $pos, $array, $level, &$dropdown ) {
        
		for ( $i = $pos; $i < count( $array ); $i ++ ) {
			if ( $array[ $i ]->category_parent == $parent_id ) {
				$name = str_repeat( '- ', $level ) . $array[ $i ]->name;
				
				$value = $array[ $i ]->slug;
				
				$dropdown[] = array(
					'label' => $name,
					'value' => $value,
				);

				$this->getCategoryChildsFull( $array[ $i ]->term_id, $i, $array, $level + 1, $dropdown );
			}
		}
	}
		/**
			** Add Shortcode
		**/
		function SC_WooTab( $atts, $content = null ){
			extract( shortcode_atts(
				array(
					'title1' => '',
					'image_icon' => '',
					'description' => '',
					'category' => '',
					'select_order' => 'latest',
					'orderby' => 'name',
					'order'	=> 'DESC',
					'numberposts' => 5,
					'item_row'=> 1,
					'tab_active' => 1,
					'columns' => 4,
					'autoplay' => 'false',
					'interval' => 5000,
					'layout'  => 'default',
					'scroll' => 1,
					'elclass' => ''
				), $atts )
			);
			ob_start();		
			if( $layout == 'default' ){
				include( plugin_dir_path(dirname(__FILE__)).'/themes/sw-woo-tab-category-slider/default.php' );
			}elseif ($layout == 'theme1' ) {
				include( plugin_dir_path(dirname(__FILE__)).'/themes/sw-woo-tab-category-slider/theme1.php' );
			}
			
			$content = ob_get_clean();
			
			return $content;
		}
		
		/*Call to order clause*/
		public static function order_by_rating_post_clauses( $args ) {
			global $wpdb;

			$args['fields'] .= ", AVG( $wpdb->commentmeta.meta_value ) as average_rating ";

			$args['where'] .= " AND ( $wpdb->commentmeta.meta_key = 'rating' OR $wpdb->commentmeta.meta_key IS null ) ";

			$args['join'] .= "
				LEFT OUTER JOIN $wpdb->comments ON($wpdb->posts.ID = $wpdb->comments.comment_post_ID)
				LEFT JOIN $wpdb->commentmeta ON($wpdb->comments.comment_ID = $wpdb->commentmeta.comment_id)
			";

			$args['orderby'] = "average_rating DESC, $wpdb->posts.post_date DESC";

			$args['groupby'] = "$wpdb->posts.ID";

			return $args;
		}
			/**
		* Ajax Callback
		**/
		function sw_ajax_tab_callback(){
          
			$cat 			 	 = ( isset( $_POST["catid"] )   	&& $_POST["catid"] != '' ) ? $_POST["catid"] : '';	
			$so          = ( isset( $_POST["sorder"] )  	&& $_POST["sorder"] != '' ) ? $_POST["sorder"] : 'latest';
			$layout      = ( isset( $_POST["layout"] )  	&& $_POST["layout"] != '' ) ? $_POST["layout"] : '';
			$target      = ( isset( $_POST["target"] )  	&& $_POST["target"] != '' ) ? str_replace( '#', '', $_POST["target"] ) : '';
			$numberposts = ( isset( $_POST["number"] )  	&& $_POST["number"] > 0 ) ? $_POST["number"] : 0;
			$item_row    = ( isset( $_POST["item_row"] )  && $_POST["item_row"] > 0 ) ? $_POST["item_row"] : 1;
			$columns		 = ( isset( $_POST["columns"] )   && $_POST["columns"] > 0 ) ? $_POST["columns"] : 1;
			$interval		 = ( isset( $_POST["interval"] )  && $_POST["interval"] > 0 ) ? $_POST["interval"] : 1000;
			$scroll			 = ( isset( $_POST["scroll"] )  	&& $_POST["scroll"] > 0 ) ? $_POST["scroll"] : 1;
			$autoplay		 = ( isset( $_POST["autoplay"] )  && $_POST["autoplay"] != '' ) ? $_POST["autoplay"] : 'false';
			$Wc_Query = new WC_Query();
			$default = array();
			if( $so == 'latest' ){
				$default = array(
					'post_type'	=> 'product',
					'tax_query'	=> array(
					array(
						'taxonomy'	=> 'product_cat',
						'field'		=> 'id',
						'terms'		=> $cat)),
					'orderby' => 'date',
					'order' => $order,
					'post_status' => 'publish',
					'showposts' => $numberposts
				);
			}
			if( $so == 'rating' ){
				$default = array(
					'post_type' 			=> 'product',
					'post_status' 			=> 'publish',
					'ignore_sticky_posts'   => 1,
					'tax_query'	=> array(
					array(
						'taxonomy'	=> 'product_cat',
						'field'		=> 'id',
						'terms'		=> $cat)),
					'orderby' 				=> $orderby,
					'order'					=> $order,
					'showposts' 		=> $numberposts,
				);
				if( sw_woocommerce_version_check( '3.0' ) ){	
					$default['meta_key'] = '_wc_average_rating';	
					$default['orderby'] = 'meta_value_num';
				}else{	
					add_filter( 'posts_clauses',  array( WC()->query, 'order_by_rating_post_clauses' ) );
				}
			}
			if( $so == 'bestsales' ){
				$default = array(
					'post_type' 			=> 'product',
					'post_status' 			=> 'publish',
					'ignore_sticky_posts'   => 1,
					'tax_query'	=> array(
						array(
							'taxonomy'	=> 'product_cat',
							'field'		=> 'id',
							'terms'		=> $cat)),
					'paged'	=> 1,
					'showposts'				=> $numberposts,
					'meta_key' 		 		=> 'total_sales',
					'orderby' 		 		=> 'meta_value_num',
					'meta_query' 			=> array(
						array(
							'key' 		=> '_visibility',
							'value' 	=> array( 'catalog', 'visible' ),
							'compare' 	=> 'IN'
						)
					)
				);
			}
			if( $so == 'featured' ){
				$default = array(
					'post_type'				=> 'product',
					'post_status' 			=> 'publish',
					'tax_query'	=> array(
						array(
							'taxonomy'	=> 'product_cat',
							'field'		=> 'id',
							'terms'		=> $cat)),
					'ignore_sticky_posts'	=> 1,
					'posts_per_page' 		=> $numberposts,
					'orderby' 				=> $orderby,
					'order' 				=> $order,
				);
				if( sw_woocommerce_version_check( '3.0' ) ){	
					$default['tax_query'][] = array(						
						'taxonomy' => 'product_visibility',
						'field'    => 'name',
						'terms'    => 'featured',
						'operator' => 'IN',	
					);
				}else{
					$default['meta_query'] = array(
						array(
							'key' 		=> '_featured',
							'value' 	=> 'yes'
						)					
					);				
				}
			}
			$list = new WP_Query( $default );
			if( $so == 'rating' && ! sw_woocommerce_version_check( '3.0' ) ){			
				remove_filter( 'posts_clauses',  array( $Wc_Query, 'order_by_rating_post_clauses' ) );
			}
			if( $list->have_posts() ) :
		?>
			<div id="<?php echo esc_attr( 'tab_cat_'. $target ); ?>" class="woo-tab-container-slider responsive-slider loading clearfix" data-lg="<?php echo esc_attr( $columns ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>"  data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
				<div class="resp-slider-container">
					<div class="slider responsive">
				        <?php 
							$count_items 	= 0;
							$numb 			= ( $list->found_posts > 0 ) ? $list->found_posts : count( $list->posts );
							$count_items 	= ( $numberposts >= $numb ) ? $numb : $numberposts;
							$i 				= 0;
							while($list->have_posts()): $list->the_post();
							global $product, $post;
							$class = ( $product->get_price_html() ) ? '' : 'item-nonprice';
							if( $i % $item_row == 0 ){
						?>
							<div class="item <?php echo esc_attr( $class )?>">
						<?php } ?>
								<?php include( WCTHEME . '/default-item.php' ); ?>
							<?php if( ( $i+1 ) % $item_row == 0 || ( $i+1 ) == $count_items ){?> </div><?php } ?>
							<?php $i++; endwhile; wp_reset_postdata();?>
					</div>
				</div>
			</div>
	<?php 
			else :
				echo '<div class="alert alert-warning alert-dismissible" role="alert">
				<a class="close" data-dismiss="alert">&times;</a>
				<p>'. esc_html__( 'There is not product on this tab', 'sw_woocommerce' ) .'</p>
				</div>';
			endif;
			exit;
		}
		
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
			extract($args);
			
			$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
			echo $before_widget;
			if ( !empty( $title ) ) { echo $before_title . $title . $after_title; }

			if ( !array_key_exists('widget_template', $instance) ){
				$instance['widget_template'] = 'default';
			}
			extract($instance);
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
			$dir = plugin_dir_path(dirname(__FILE__)).'/themes/sw-woo-tab-category-slider';
			
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
			$instance['description'] = strip_tags( $new_instance['description'] );
			// int or array
			if ( array_key_exists('category', $new_instance) ){
				if ( is_array($new_instance['category']) ){
					$instance['category'] = array_map( 'intval', $new_instance['category'] );
				} else {
					$instance['category'] = intval($new_instance['category']);
				}
			}		
			if ( array_key_exists('select_order', $new_instance) ){
				$instance['select_order'] = strip_tags( $new_instance['select_order'] );
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
			if ( array_key_exists('item_row', $new_instance) ){
				$instance['item_row'] = intval( $new_instance['item_row'] );
			}		
			if ( array_key_exists('tab_active', $new_instance) ){
				$instance['tab_active'] = intval( $new_instance['tab_active'] );
			}		
			if ( array_key_exists('columns', $new_instance) ){
				$instance['columns'] = intval( $new_instance['columns'] );
			}
			if ( array_key_exists('interval', $new_instance) ){
				$instance['interval'] = intval( $new_instance['interval'] );
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
					'multiple' => true,
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
			$defaults 			= array();
			$instance 			= wp_parse_args( (array) $instance, $defaults ); 		
			$title1				= isset( $instance['title1'] )      ? strip_tags($instance['title1']) : '';  
			$description 		= isset( $instance['description'] )    ? 	strip_tags($instance['description']) : '';
			$categoryid 		= isset( $instance['category'] )    ? $instance['category'] : 0;
			$select_order    	= isset( $instance['select_order'] )     ? strip_tags($instance['select_order']) : 'latest';
			$orderby    		= isset( $instance['orderby'] )     ? strip_tags($instance['orderby']) : 'ID';
			$order      		= isset( $instance['order'] )       ? strip_tags($instance['order']) : 'ASC';
			$number     		= isset( $instance['numberposts'] ) ? intval($instance['numberposts']) : 5;
			$item_row     		= isset( $instance['item_row'] )      	? intval($instance['item_row']) : 1;
			$tab_active    		= isset( $instance['tab_active'] )      ? intval($instance['tab_active']) : 1;
			$columns     		= isset( $instance['columns'] )      ? intval($instance['columns']) : 1;
			$autoplay     		= isset( $instance['autoplay'] )      ? strip_tags($instance['autoplay']) : 'false';
			$interval     		= isset( $instance['interval'] )      ? intval($instance['interval']) : 5000;
			$scroll     		= isset( $instance['scroll'] )      ? intval($instance['scroll']) : 1;
			$widget_template    = isset( $instance['widget_template'] ) ? strip_tags($instance['widget_template']) : 'default';
					   
					 
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
				<label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Description', 'sw_woocommerce')?></label>
				<br />
				<input class="widefat" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>"
					type="text"	value="<?php echo esc_attr($description); ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Category', 'sw_woocommerce')?></label>
				<br />
				<?php echo $this->category_select('category', array('allow_select_all' => true), $categoryid); ?>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('select_order'); ?>"><?php _e('Select Order', 'sw_woocommerce')?></label>
				<br />
				<?php $allowed_key = array('latest' => 'Latest Products', 'rating' => 'Top Rating Products', 'bestsales' => 'Best Selling Products', 'featured' => 'Featured Products'); ?>
				<select class="widefat"
					id="<?php echo $this->get_field_id('select_order'); ?>"
					name="<?php echo $this->get_field_name('select_order'); ?>">
					<?php
					$option ='';
					foreach ($allowed_key as $value => $key) :
						$option .= '<option value="' . $value . '" ';
						if ($value == $select_order){
							$option .= 'selected="selected"';
						}
						$option .=  '>'.$key.'</option>';
					endforeach;
					echo $option;
					?>
				</select>
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
			
			<p>
				<label for="<?php echo $this->get_field_id('tab_active'); ?>"><?php _e('Tab active: ', 'sw_woocommerce')?></label>
				<br />
				<input class="widefat"
					id="<?php echo $this->get_field_id('tab_active'); ?>" name="<?php echo $this->get_field_name('tab_active'); ?>" type="text" 
					value="<?php echo esc_attr($tab_active); ?>" />
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
					<option value="default" <?php if ($widget_template=='default'){?> selected="selected"
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