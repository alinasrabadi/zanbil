<?php
/**
 * Register sidebars and widgets
 */
function zanbil_unregister_default_widgets() {
	     unregister_widget('WC_Widget_Featured_Products');
		 unregister_widget('WC_Widget_Best_Sellers');
}
 add_action('widgets_init', 'zanbil_unregister_default_widgets', 11);
 
function zanbil_widgets_init() {
	// Sidebars
	global $zanbil_widget_areas;
	$zanbil_widget_areas = zanbil_widget_setup_args();
	if ( count($zanbil_widget_areas) ){
		foreach( $zanbil_widget_areas as $sidebar ){
			$sidebar_params = apply_filters('zanbil_sidebar_params', $sidebar);
			register_sidebar($sidebar_params);
		}
	}

	// Widgets
	register_widget('ZANBIL_Posts_Widget');
	register_widget('ZANBIL_Vertical_Megamenu_Widget');
	register_widget('ZANBIL_Categories_Widget');
	//register_widget('ZANBIL_Slider_Widget');
	//register_widget('ZANBIL_Top_Rate_Product');
	//register_widget('ZANBIL_Cpanel');
}
add_action('widgets_init', 'zanbil_widgets_init');

/**
 * Posts widget class
 *
 * @since 2.8.0
*/
class ZANBIL_Posts_Widget extends ZANBIL_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'zanbil_posts', 'description' => esc_html__('نمایش نوشته', 'zanbil'));
		parent::__construct('zanbil_posts', esc_html__('نوشته ها', 'zanbil'), $widget_ops);
		$this->base = get_template_directory().'/lib';
	}
}

class ZANBIL_Vertical_Megamenu_Widget extends ZANBIL_Widget{

	function __construct(){
		$widget_ops = array('classname' => 'vertical_megamenu', 'description' => esc_html__('مگامنو عمودی', 'zanbil'));
		parent::__construct('vertical_megamenu', esc_html__('مگامنو عمودی', 'zanbil'), $widget_ops);
	}
}
class ZANBIL_Categories_Widget extends ZANBIL_Widget{

	function __construct(){
		$widget_ops = array('classname' => 'zanbil_categories', 'description' => esc_html__('دسته بندی نوشته ها', 'zanbil'));
		parent::__construct('zanbil_categories', esc_html__('دسته بندی نوشته ها', 'zanbil'), $widget_ops);
	}
}

class ZANBIL_Cpanel extends WP_Widget{

	public function __construct(){
		$widget_opts = array( 'classname' => 'cpanel', 'description' => esc_html__('Theme Options on Frontend', 'zanbil') );
		parent::__construct('cpanel', esc_html__('Zanbil cPanel', 'zanbil'), $widget_opts);
	}

	public function widget( $args, $instance ){
		
		if ( function_exists('zanbil_options') ){
			$options = zanbil_options();
			$options->cpanel();
		}
	}
	
	public function update( $new_instance, $old_instance ){
		
	}
	
	public function form( $instance ){
		
	}
}

class ZANBIL_Widgets{

	protected $dir = null;
	protected $url = null;
	protected $styles = null;
	
	protected $widget = null;
	protected $enqueues = array();
	protected $fields = array(
			'zanbil_before_widget' => 'Before Widget',
			'zanbil_after_widget'  => 'After Widget',
			'zanbil_before_title'  => 'Before Title',
			'zanbil_after_title'   => 'After Title',
			'zanbil_display_conditions' => 'Display Conditions',
			'_widget_style' => 'Widget Style:'
	);
			
	public function __construct(){
		add_filter('in_widget_form', array($this, 'in_widget_form'), 10, 3);
		add_filter('widget_update_callback', array($this, 'widget_update_callback'), 10, 3);
		add_filter('widget_display_callback', array($this, 'widget_display_callback'), 10, 3);

		// enqueue
		add_filter('admin_enqueue_scripts', array($this, 'wp_enqueue_scripts'));
		$this->getWidgetStyles();
	}

	public function widget_display_callback( $instance, $widget, $args ){		
		//Widget Display
		$widget_display = isset($instance['widget_display']) ? $instance['widget_display'] : array();
		
		if ( check_wdisplay($widget_display) == false ) return false;
		
		if ( $style = $this->getStyleIn($instance) ){
			if ( isset($style['before_widget']) && !empty($style['before_widget'])){
				// Substitute HTML id and class attributes into before_widget
				global $wp_registered_widgets;
				$classname_ = '';
				foreach ( (array) $wp_registered_widgets[$widget->id]['classname'] as $cn ) {
					if ( is_string($cn) )
						$classname_ .= '_' . $cn;
					elseif ( is_object($cn) )
					$classname_ .= '_' . get_class($cn);
				}
				$classname_ = ltrim($classname_, '_');
				$args['before_widget'] = sprintf($style['before_widget'], $widget->id, $classname_);
			}
			if ( isset($style['after_widget']) ){
				$args['after_widget'] = $style['after_widget'];
			}
			if ( isset($style['before_title']) || isset($style['after_title']) ){
				$args['before_title'] = $style['before_title'];
				$args['after_title'] = $style['after_title'];
			}
			//var_dump($args);
			$widget->widget($args, $instance);
			
			return false;
		}

		return $instance;
	}

	public function widget_update_callback( $instance, $new, $old ){
		$instance_new['widget_style'] = isset($new['widget_style']) ? $new['widget_style'] : 'inherit';
		$instance_new['adoptions'] = isset($new['adoptions']) ? $new['adoptions'] : false;
		
		//Widget Display
		$instance_new['widget_display'] = isset($new['widget_display']) ? $new['widget_display'] : array();
		return wp_parse_args($instance_new, $instance);
	}
	
	public function in_widget_form($widget, $r = null, $instance = array() ){
		
		$widget_advanced = zanbil_options()->getCpanelValue('widget-advanced');
		if ( !$widget_advanced ) return ;

		$this->widget = &$widget;
		$widget_style = isset($instance['widget_style']) ? trim($instance['widget_style']) : '';
		$adoptions = isset( $instance['adoptions'] ) ? $instance['adoptions'] : false;
		$adoptions_checked = $adoptions ? 'checked="checked"' : '';
		$adoptions_on_class = $adoptions ? 'on' : '';
		
		//Widgets Style
		$styles = $this->getWidgetStyles();
		$styles = array_merge(array('default' => 'Default'), $styles);
		$styles = array_unique($styles);
		//Widget Display
		$widget_display = isset($instance['widget_display']) ? $instance['widget_display'] : array();
		?>

		<div class="advanced-opt <?php echo $adoptions_on_class; ?>">
			<div class="advanced-opt-controls" >
				<label class="button toggle" for="<?php echo esc_attr( $widget->get_field_id('adoptions') ); ?>"><?php esc_html_e( 'Advanced Options', 'zanbil' ) ?>
					<input type="checkbox" class="pane-toggler" <?php echo $adoptions_checked; ?> style="display: none;" name="<?php echo esc_attr( $widget->get_field_name('adoptions') ); ?>" id="<?php echo esc_attr( $widget->get_field_id('adoptions') ); ?>">
				</label>
			</div>
			<div class="advanced-opt-pane">
				<div class="advanced-opt-pane-inner">
					<div class="pane-content">
						<div class="pane-left">
							<p>
								<label for="<?php echo esc_attr( $widget->get_field_id('widget_style') ); ?>"><?php esc_html_e( 'Widget Style', 'zanbil' ) ?>
								</label> <select class="widefat"
									id="<?php echo esc_attr( $widget->get_field_id('widget_style') ); ?>"
									name="<?php echo esc_attr( $widget->get_field_name('widget_style') ); ?>">
									<?php foreach ( $styles as $key => $value ){
										$selected = '';
													if ($key == $widget_style) $selected = 'selected="selected"'; ?>
									<option <?php echo 'value="'.esc_attr( $key ).'" '.$selected ; ?>>
										<?php echo $value; ?>
									</option>
									<?php }	?>
								</select>
							</p>
							<?php echo $this->render_wdisplay($widget_display); ?>
						</div>
						<div class="pane-right"></div>
					</div>
				</div>
			</div>
			<br class="clear">
		</div>

	<?php
	}

	public function wp_enqueue_scripts(){

		if (!isset($this->_enqueue)){
			$this->initialize();
			wp_enqueue_style('widget-options', $this->url . 'admin/css/widget-options.css', array(), null);
			wp_enqueue_script('widget-options', $this->url . 'admin/js/widget-options.js', false, null, false);
			$this->_enqueue = true;
		}
	}

	public function initialize(){

		$this->dir 	= trailingslashit( str_replace( '\\', '/', get_template_directory() . '/lib/' ) );
		$this->url 	= trailingslashit( get_template_directory_uri() ) . '/lib/' ;

	}
	
	private $menu_assignment_tpl = null;
	private $menu_assignments = array();
	private $display_select_tpl = null;
	private $display_select_options = array();
	private $display_language_options = array();
	private $display_language_tpl = null;
	
	private $display_checkbox_users_tpl = null;
	private $display_checkbox_users = null;
	
	private $display_checkbox_general_tpl = null;
	private $display_checkbox_general = null;
	
	private $display_checkbox_menus = null;
	
	
	private function menu_assignment( $widget_display = array() ){

		$html = '';
		
		$display_select = &$this->getDisplaySelectOptions();
		$display_language = &$this->getDisplayLanguage();
		$display_checkbox_users = &$this->getDisplayCheckboxUsers();
		$menu_assignment_tpl = &$this->getMenuAssignmentTemplate();
		
		$html .= $display_language;
		$html .= $display_select;
		$html .= '<div class="widefat adoptions-display-content">';
		$html .= $display_checkbox_users;
		$html .= $menu_assignment_tpl;
		$html .= '</div>';
		
		$display_id = $this->widget->get_field_id('widget_display');
		$display_name = $this->widget->get_field_name('widget_display');
		
		$display_language_value = isset($widget_display['display_language']) ? $widget_display['display_language'] : '';
		$display_select_value = isset($widget_display['display_select']) ? $widget_display['display_select'] : '';
		$menus_values = isset($widget_display['checkbox']['menus']) ? $widget_display['checkbox']['menus'] : array();
		$display_checkbox_users_value = isset($widget_display['checkbox']['users']) ? $widget_display['checkbox']['users'] : array();
		
		$menu_key_checked = array();
		
		// Display select
		$menu_key_checked['id-display-select'] = $display_id . '-display-select';
		$menu_key_checked['name-display-select'] = $display_name . '[display_select]';
		$menu_key_checked['id-display-language'] = $display_id . '-display-language';
		$menu_key_checked['name-display-language'] = $display_name . '[display_language]';
		
		foreach ($this->display_select_options as $key => $value) {
			if ($key == $display_select_value) {
				$menu_key_checked['display-select-' . $key] = 'selected="selected"';
			} else $menu_key_checked['display-select-' . $key] = '';
		}
		
		foreach ($this->display_language_options as $key => $value) {
			if ($key == $display_language_value) {
				$menu_key_checked['display-language-' . $key] = 'selected="selected"';
			} else $menu_key_checked['display-language-' . $key] = '';
		}
		
		// Checkbox User
		foreach ($this->display_checkbox_users as $key => $value) {
			$menu_key_checked['id-display-checkbox-user-' . $key] = $display_id . '-users-' . $key;
			$menu_key_checked['name-display-checkbox-user-' . $key] = $display_name . '[checkbox][users][' . $key . ']';
		
			if (array_key_exists($key, $display_checkbox_users_value)) {
				$menu_key_checked['checked-display-checkbox-user-'. $key] = 'checked="checked"';
			} else $menu_key_checked['checked-display-checkbox-user-'. $key] = '';
		}
		
		// Menu assignments
		foreach ($this->menu_assignments as $menu_slug => $menu) {
			foreach ($menu as $item) {
				$menu_key_checked[ $menu_slug . '-' . $item->ID ] = $display_id . '-' . $menu_slug . '-' . $item->ID;
				$menu_key_checked[ $menu_slug . '_' . $item->ID ] = $display_name . '[checkbox][menus][' . $menu_slug . '][' . $item->ID . ']';
				
				if (isset($menus_values[$menu_slug]) && array_key_exists($item->ID, $menus_values[$menu_slug])) {
					$menu_key_checked[$menu_slug . $item->ID ] = 'checked="checked"';
				} else {
					$menu_key_checked[$menu_slug . $item->ID] = '';
				}
			}
		}
		
		return str_replace( array_keys($menu_key_checked), array_values($menu_key_checked), $html);
	}
	
	private function getDisplaySelectOptions(){
		if ( is_null($this->display_select_tpl) ){
			$this->display_select_options = $display_select_options = array(
				'all' => esc_html__('All', 'zanbil'),
				'if_selected' => esc_html__('Only on selected', 'zanbil'),
				'if_no_selected' => esc_html__('Except selected', 'zanbil')
			);
			$html = '';
			$html .= '<p>';
			$html .= '<label for="id-display-select">'. esc_html__( 'Widget Display', 'zanbil' ) .  '</label>';
			
			$html .= '<select class="adoptions-display-select widefat " id="id-display-select" name="name-display-select">';
					
			foreach ( $display_select_options as $key => $value) {
				$html .= '<option value="' . $key . '" display-select-'. $key .'>' . $value . '</option>';
			}
			$html .= '</select>';
			$html .= '</p>';
			
			$this->display_select_tpl = $html;
		}
		
		return $this->display_select_tpl;
	}

	private function getDisplayCheckboxUsers(){
		if ( is_null($this->display_checkbox_users_tpl) ){
			$this->display_checkbox_users = $display_checkbox_users = array(
				'login' => esc_html__('Show only for login users' , 'zanbil'),
				'logout' => esc_html__('Show only for logout users' , 'zanbil')
			);
				
			$html = '';
			$html .='<p><strong>Users</strong><br/>';
					
			foreach ($display_checkbox_users as $key => $name) {
						
				$html .= '<label for="id-display-checkbox-user-' . $key . '">
						<input type="checkbox" id="id-display-checkbox-user-' . $key . '"
								name="name-display-checkbox-user-' . $key . '" checked-display-checkbox-user-'. $key .'/> ' .  $name . 
					'</label> <br/>';
			}
			$html .= '</p>';
			
			$this->display_checkbox_users_tpl = $html;
		}
		
			
		return $this->display_checkbox_users_tpl;
	}
	private function getDisplayLanguage(){
		if ( !in_array( 'sitepress-multilingual-cms/sitepress.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { 
			return ;
		}
		$language = icl_get_languages('skip_missing=N&orderby=KEY&order=DIR&link_empty_to=str');
		$this -> display_language_options = $language;
		$html = '';
		$html .= '<p>';
		$html .= '<label for="id-display-language">'. esc_html__( 'Language Display', 'zanbil' ) .  '</label>';
		
		$html .= '<select class="adoptions-display-language widefat " id="id-display-language" name="name-display-language">';
				
		foreach ( $language as $key => $value) {
			$html .= '<option value="' . $key . '" display-language-'. $key .'>' . $value['language_code'] . '</option>';
		}
		$html .= '</select>';
		$html .= '</p>';
		
		$this->display_language_tpl = $html;
		return $this->display_language_tpl;
	}
	private function getDisplayCheckboxGeneral(){
		if ( is_null($this->display_checkbox_general_tpl) ){
			$this->display_checkbox_general = $display_checkbox_general = array(
					'home' => esc_html__('Blog posts', 'zanbil'),
					'archive' => esc_html__('Archive page', 'zanbil'),
					'date' => esc_html__('Archive Date', 'zanbil'),
					'category' => esc_html__('Category archive', 'zanbil'),
					'tag' => esc_html__('Tag archive', 'zanbil'),
					'author' => esc_html__('Author archive', 'zanbil'),
					'search' => esc_html__('Search page', 'zanbil'),
					'single' => esc_html__('Single post', 'zanbil'),
					'page' => esc_html__('Page template', 'zanbil'),
					'404' => esc_html__('Page 404', 'zanbil')
				);
			$html = '';
			$html .='<p><strong>General</strong><br/>';
				
			foreach ($display_checkbox_general as $key => $name) {
	
				$html .= '<label for="id-display-checkbox-general-' . $key . '">
						<input type="checkbox" id="id-display-checkbox-general-' . $key . '"
								name="name-display-checkbox-general-' . $key . '" checked-display-checkbox-general-'. $key .'/> ' . $name .
									'</label> <br/>';
			}
		
			$html .= '</p>';
				
			$html .= '<p><label>' . esc_html__( 'Taxonomy slugs', 'zanbil' ) . '</label> <br/>';
			$html .= '<input type="text" id="id-display-taxonomy-slugs"
								name="name-display-taxonomy-slugs" value="value-display-taxonomy-slugs"/></p>';
			
			$html .= '<p><label>' . esc_html__( 'Posts type', 'zanbil' ) . '</label> <br/>';
			$html .= '<input type="text" id="id-display-post-type"
								name="name-display-post-type" value="value-display-post-type"/></p>';

			$html .= '<p><label>' . esc_html__( 'Categories ID', 'zanbil' ) . '</label> <br/>';
			$html .= '<input type="text" id="id-display-catid"
								name="name-display-catid" value="value-display-catid"/></p>';
				
			$html .= '<p><label>' . esc_html__( 'Posts ID', 'zanbil' ) . '</label> <br/>';
			$html .= '<input type="text" id="id-display-postid"
								name="name-display-postid" value="value-display-postid"/></p>';
				
			$this->display_checkbox_general_tpl = $html;
		}
	
			
		return $this->display_checkbox_general_tpl;
	}
	
	private function getMenuAssignmentTemplate(){
		
		if (count($this->menu_assignments) < 1) {
			$menu_locations = get_nav_menu_locations();
			if(count($menu_locations) == 0 )
			return ;
			foreach ($menu_locations as $menu){ 
				
					$menu = wp_get_nav_menu_object($menu);
					$this->menu_assignments[] = $menu;
			}
		}
		
		if ( is_null($this->display_checkbox_menus) ){
			$menus = $this->menu_assignments;
			$html = '';
			$html .= '<div class="adoptions-display-menu">';
			
			foreach ( $menus as $menu ) {
				if(!isset($menu) || $menu != NULL || $menu != "")
					{
						$items = $this->menu_assignments[$menu->slug] = wp_get_nav_menu_items($menu);
						if (count($items) > 0) {
							$menu_checkbox = new ZANBIL_Menu_Checkbox( $menu->slug);
							$html .= '<strong>' . $menu->name . '</strong><span> (Menu)</span>';
							$html .= '<ul>';
							$html .= $menu_checkbox->init($items);
							$html .= '</ul>';
						}
					}
			}
			$html .= '</div>';
			$this->display_checkbox_menus = $html;
		}
		
		return $this->display_checkbox_menus;
	}

	private function render_wdisplay($widget_display){
		$widget_display = json_decode(json_encode($widget_display), true);
		$display_select_value = isset($widget_display['display_select']) ? $widget_display['display_select'] : '';
		$display_language_value = isset($widget_display['display_language']) ? $widget_display['display_language'] : '';
		$menus_values = isset($widget_display['checkbox']['menus']) ? $widget_display['checkbox']['menus'] : array();
		$display_checkbox_users_value = isset($widget_display['checkbox']['users']) ? $widget_display['checkbox']['users'] : array();
		$display_checkbox_general_value = isset($widget_display['checkbox']['general']) ? $widget_display['checkbox']['general'] : array();
		
		$taxonomy_slugs = isset($widget_display['taxonomy-slugs']) ? trim($widget_display['taxonomy-slugs']) : '';
		$post_type = isset($widget_display['post-type']) ? trim($widget_display['post-type']) : '';
		$catid = isset($widget_display['catid']) ? trim($widget_display['catid']) : '';
		$postid = isset($widget_display['postid']) ? trim($widget_display['postid']) : '';
		
		$html = '';
		
		$display_language = $this->getDisplayLanguage();
		$display_select = $this->getDisplaySelectOptions();
		$display_checkbox_users = $this->getDisplayCheckboxUsers();
		$display_checkbox_general = $this->getDisplayCheckboxGeneral();
		$menu_assignment_tpl = $this->getMenuAssignmentTemplate();
		$html .= $display_language;
		$html .= $display_select;
		$html .= '<div class="widefat adoptions-display-content" ';
		
		if ($display_select_value == 'all') {
			$html .= 'style="display: none;"';
		}
		$html .= '>';
		$html .= $display_checkbox_users;
		$html .= $display_checkbox_general;
		$html .= $menu_assignment_tpl;
		$html .= '</div>';
		
		$display_id = $this->widget->get_field_id('widget_display');
		$display_name = $this->widget->get_field_name('widget_display');
		
		$menu_key_checked = array();
		
		// Display select
		$menu_key_checked['id-display-select'] = $display_id . '-display-select';
		$menu_key_checked['name-display-select'] = $display_name . '[display_select]';
		$menu_key_checked['id-display-language'] = $display_id . '-display-language';
		$menu_key_checked['name-display-language'] = $display_name . '[display_language]';
		
		foreach ($this->display_select_options as $key => $value) {
			if ($key == $display_select_value) {
				$menu_key_checked['display-select-' . $key] = 'selected="selected"';
			} else $menu_key_checked['display-select-' . $key] = '';
		}
		
		foreach ($this->display_language_options as $key => $value) {
			if ($key == $display_language_value) {
				$menu_key_checked['display-language-'. $key] = 'selected="selected"';
			} else $menu_key_checked['display-language-'. $key] = '';
		}
		
		// Checkbox User
		foreach ($this->display_checkbox_users as $key => $value) {
			$menu_key_checked['id-display-checkbox-user-' . $key] = $display_id . '-users-' . $key;
			$menu_key_checked['name-display-checkbox-user-' . $key] = $display_name . '[checkbox][users][' . $key . ']';
		
			if (array_key_exists($key, $display_checkbox_users_value)) {
				$menu_key_checked['checked-display-checkbox-user-'. $key] = 'checked="checked"';
			} else $menu_key_checked['checked-display-checkbox-user-'. $key] = '';
		}
		
		// Checkbox General
		foreach ($this->display_checkbox_general as $key => $value) {
			$menu_key_checked['id-display-checkbox-general-' . $key] = $display_id . '-general-' . $key;
			$menu_key_checked['name-display-checkbox-general-' . $key] = $display_name . '[checkbox][general][' . $key . ']';
		
			if (array_key_exists($key, $display_checkbox_general_value)) {
				$menu_key_checked['checked-display-checkbox-general-'. $key] = 'checked="checked"';
			} else $menu_key_checked['checked-display-checkbox-general-'. $key] = '';
		}

		// Taxonomy slugs
		$menu_key_checked['id-display-taxonomy-slugs'] = $display_id . '-taxonomy-slugs';
		$menu_key_checked['name-display-taxonomy-slugs'] = $display_name . '[taxonomy-slugs]';
		$menu_key_checked['value-display-taxonomy-slugs'] = $taxonomy_slugs;
		
		// Posts type
		$menu_key_checked['id-display-post-type'] = $display_id . '-post-type';
		$menu_key_checked['name-display-post-type'] = $display_name . '[post-type]';
		$menu_key_checked['value-display-post-type'] = $post_type;
		
		// Categories ID
		$menu_key_checked['id-display-catid'] = $display_id . '-catid';
		$menu_key_checked['name-display-catid'] = $display_name . '[catid]';
		$menu_key_checked['value-display-catid'] = $catid;
		
		// Posts ID
		$menu_key_checked['id-display-postid'] = $display_id . '-postid';
		$menu_key_checked['name-display-postid'] = $display_name . '[postid]';
		$menu_key_checked['value-display-postid'] = $postid;
		
		// Menu assignments
		foreach ($this->menu_assignments as $menu_slug => $menu) {
			if(is_array($menu)){
				foreach ($menu as $item) {
					$menu_key_checked[ $menu_slug . '-' . $item->post_name . '-' . $item->ID ] = $display_id . '-' . $menu_slug . '-' . $item->post_name . '-' . $item->ID;
					$menu_key_checked[ $menu_slug . '_' . $item->post_name . '_' . $item->ID ] = $display_name . '[checkbox][menus][' . $menu_slug . '][' . $item->ID . ']';
					
					if (isset($menus_values[$menu_slug]) && array_key_exists($item->ID, $menus_values[$menu_slug])) {
						$menu_key_checked[$menu_slug . $item->post_name . $item->ID ] = 'checked="checked"';
					} else {
						$menu_key_checked[$menu_slug . $item->post_name . $item->ID] = '';
					}
				}
			}
		}
		
		return str_replace( array_keys($menu_key_checked), array_values($menu_key_checked), $html);
	}

	/**
	 * Scans a directory for files of a certain extension.
	 *
	 * @since 3.4.0
	 * @access private
	 *
	 * @param string $path Absolute path to search.
	 * @param mixed  Array of extensions to find, string of a single extension, or null for all extensions.
	 * @param int $depth How deep to search for files. Optional, defaults to a flat scan (0 depth). -1 depth is infinite.
	 * @param string $relative_path The basename of the absolute path. Used to control the returned path
	 * 	for the found files, particularly when this function recurses to lower depths.
	 */
	protected function scandir( $path, $extensions = null, $depth = 0, $relative_path = '' ) {
		
		if ( ! is_dir( $path ) )
			return false;

		if ( $extensions ) {
			$extensions = (array) $extensions;
			$_extensions = implode( '|', $extensions );
		}

		$relative_path = trailingslashit( $relative_path );
		if ( '/' == $relative_path )
			$relative_path = '';

		$results = scandir( $path );
		$files = array();

		foreach ( $results as $result ) {
			if ( '.' == $result[0] )
				continue;
			if ( is_dir( $path . '/' . $result ) ) {
				if ( ! $depth || 'CVS' == $result )
					continue;
				$found = self::scandir( $path . '/' . $result, $extensions, $depth - 1 , $relative_path . $result );
				$files = array_merge_recursive( $files, $found );
			} elseif ( ! $extensions || preg_match( '~\.(' . $_extensions . ')$~', $result ) ) {
				$files[ $relative_path . $result ] = $path . '/' . $result;
			}
		}

		return $files;
	}

	protected function getWidgetStyles(){
		
		if ( is_null($this->styles) ){
			$tmp = array();
			if ( $_core_styles = $this->scandir(ZANBIL_DIR.'/widgets/_styles', 'php') )
			foreach( $_core_styles as $f ){
				$alias = basename($f, '.php');
				$name  = ucfirst($alias);
				$tmp[ $alias ] = $name;
			}

			if ( $_theme_styles = $this->scandir(get_template_directory().'/widgets/_styles') )
			foreach( $_theme_styles as $f ){
				$alias = basename($f, '.php');
				$name  = ucfirst($alias);
				$tmp[ $alias ] = $name;
			}
			
			$this->styles = $tmp;
		}
		return $this->styles;
	}
	
	protected function getStyleIn( $instance = array(), $load_style = true ){
		
		$styles = $this->getWidgetStyles();
		$current_style = isset( $instance['widget_style'] ) ? trim($instance['widget_style']) : '';
		if ( !empty($current_style) && isset($styles[$current_style]) ){
			
		} else {
			$current_style = '';
		}
		return $load_style ? $this->loadStyle($current_style) : $current_style;
	}
	
	protected function loadStyle( $style = '' ){
		
		if ( !empty($style) ){

			$_theme_style = get_template_directory().'/widgets/_styles/'.$style.'.php';
			
			if ( file_exists($_theme_style) ){
				require $_theme_style;
				return @$ws[$style];
			}
			
			$_core_style = ZANBIL_DIR.'/widgets/_styles/'.$style.'.php';
			if ( file_exists($_core_style) ){
				require $_core_style;
				return @$ws[$style];
			}
			
			if ( $style != 'default' ){
				return $this->loadStyle('default');
			}
			
		}
		return false;
	}
}

$widgets = new ZANBIL_Widgets;
