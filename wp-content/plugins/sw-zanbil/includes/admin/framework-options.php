<?php
/**
 * Instagram Class
 *
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly


if ( !class_exists( 'THF_SETTINGS' ) ) {

    class THF_SETTINGS {

        public $item_id;
        public $item_id_attr;
        public $item_id_wrap;
        public $name_attr;
        public $placeholder_attr;
        public $custom_class;
        public $current_value;
        public $option_type;
        public $settings;
        public $current;



        /**
         * __construct
         */
        function __construct( $settings, $option_name, $data ) {

            $this->prepare_data( $settings, $option_name, $data );

            if( empty( $this->option_type ) ){
                return;
            }

            // Options Without Labels
            $with_label = false;
            switch ( $this->option_type ) {
                case 'tab-title':
                    $this->tab_title();
                    break;

                case 'header':
                        $this->section_head();
                        break;

                case 'message':
                case 'success':
                case 'error':
                        $this->notice_message();
                        break;

                case 'hidden':
                        $this->hidden();
                        break;

                default:
                    $with_label = true;
                    break;
            }


            // Options With Label
            if( $with_label ){

                /** Option Start */
                $this->option_head();

                /** The Option */
                switch ( $this->option_type ) {
                    case 'text':
                        $this->text();
                        break;

                    case 'arrayText':
                        $this->text_array();
                        break;

                    case 'number':
                        $this->number();
                        break;

                    case 'radio':
                        $this->radio();
                        break;

                    case 'checkbox':
                        $this->checkbox();
                        break;

                    case 'select-multiple':
                        $this->multiple_select();
                        break;

                    case 'textarea':
                        $this->textarea();
                        break;

                    case 'color':
                        $this->color();
                        break;

                    case 'icon':
                        $this->icon();
                        break;

                    case 'editor':
                        $this->editor();
                        break;

                    case 'fonts':
                        $this->fonts();
                        break;

                    case 'upload':
                        $this->upload();
                        break;

                    case 'typography':
                        $this->typography();
                        break;

                    case 'background':
                        $this->background();
                        break;

                    case 'select':
                        $this->select();
                        break;

                    case 'visual':
                        $this->visual();
                        break;

                    default:
                        break;
                }


                /** Option END */
                $this->hint();

                echo '</div>';
                /**/

            }
        }



        /**
         * Setting Description
         */
        private function hint(){

            if( ! empty( $this->settings['hint'] ) ){
                ?>
                <span class="extra-text">
                    <?php echo $this->settings['hint'] ?>
                </span>
                <?php
            }
        }



        /**
         * Upload
         */
        private function upload(){

            $upload_button = ! empty( $this->settings['custom_text'] ) ? $this->settings['custom_text'] : esc_html__( 'Upload', TCW_TEXTDOMAIN );
            $image_preview = ! empty( $this->current_value ) ? $this->current_value : TCW_ADMIN_URL.'/assets/images/empty.png';
            $hide_preview  = ! empty( $this->current_value ) ? '' : 'style="display:none"';
            ?>

            <div class="image-preview-wrapper">
                <input <?php echo $this->item_id_attr ?> <?php echo $this->name_attr ?> class="thf-img-path" type="text" value="<?php echo esc_attr( $this->current_value ) ?>" <?php echo $this->placeholder_attr ?>>
                <input id="<?php echo 'upload_'. $this->item_id .'_button' ?>" type="button" class="thf-upload-img button" value="<?php echo $upload_button ?>">

                <?php $this->hint(); ?>
            </div>

            <div id="<?php echo $this->item_id . '-preview' ?>" class="img-preview" <?php echo $hide_preview ?>>
                <img src="<?php echo $image_preview ?>" alt="">
                <a class="del-img"></a>
            </div>
            <div class="clear"></div>
            <?php
        }



        /**
         * Text
         */
        private function text(){
            ?>
                <input <?php echo $this->item_id_attr ?> <?php echo $this->name_attr ?> type="text" value="<?php echo esc_attr( $this->current_value ) ?>" <?php echo $this->placeholder_attr ?> dir="auto">
            <?php
        }



        /**
         * Text Array
         */
        private function text_array(){

            $key = $this->settings['key'];
            $single_name = $this->option_name . '['. $key .']';
            $current_value = ! empty( $this->current_value[ $key ] ) ? $this->current_value[ $key ] : '';

            ?>
                <input name="<?php echo $single_name ?>" type="text" value="<?php echo $current_value ?>" <?php echo $this->placeholder_attr ?> dir="auto">
            <?php
        }



        /**
         * Checkbox
         */
        private function checkbox(){
            $checked = checked( $this->current_value, true, false );

            $toggle_data  = ! empty( $this->settings['toggle'] ) ? 'data-thf-toggle="'. $this->settings['toggle'] .'"' : '';
            $toggle_class = ! empty( $this->settings['toggle'] ) ? 'thf-toggle-option' : '';

            ?>
                <input <?php echo $this->item_id_attr ?> <?php echo $this->name_attr ?> class="thf-js-switch <?php echo $toggle_class ?>" <?php echo $toggle_data ?> type="checkbox" value="true" <?php echo $checked ?>>
            <?php
        }



        /**
         * Radio
         */
        private function radio(){

            ?>
            <div class="option-contents">
                <?php
                    $i = 0;
                    foreach ( $this->settings['options'] as $option_key => $option ){
                        $i++;

                        $checked = '';
                        if ( ( ! empty( $this->current_value ) && $this->current_value == $option_key ) || ( empty( $this->current_value ) && $i==1 ) ){
                            $checked = 'checked="checked"';
                        }

                        ?>
                            <label>
                                <input <?php echo $this->name_attr ?> <?php echo $checked ?> type="radio" value="<?php echo $option_key ?>"> <?php echo $option ?>
                            </label>
                        <?php
                    }
                ?>
            </div>
            <div class="clear"></div>

            <?php
                if( empty( $this->settings['toggle'] ) ){
                    return;
                }
            ?>

            <script>
                jQuery(document).ready(function(){
                    jQuery( '.<?php echo esc_js( $this->item_id ) ?>-options' ).hide();
                    <?php
                        if( ! empty( $this->settings['toggle'][ $this->current_value ] )){ ?>
                            jQuery( '<?php echo esc_js( $this->settings['toggle'][ $this->current_value ] ) ?>' ).show();
                            <?php
                        }
                        elseif( is_array( $this->settings['toggle'] ) ){
                            $first_elem = reset( $this->settings['toggle'] ) ?>
                            jQuery( '<?php echo esc_js( $first_elem ) ?>' ).show();
                            <?php
                        }
                    ?>

                    jQuery("input[name='<?php echo esc_js( $this->option_name ) ?>']").change(function(){
                        selected_val = jQuery( this ).val();
                        jQuery( '.<?php echo esc_js( $this->item_id ) ?>-options' ).slideUp('fast');
                        <?php
                            foreach( $this->settings['toggle'] as $tg_item_name => $tg_item_id ){
                                if( ! empty( $tg_item_id ) ){ ?>

                                    if ( selected_val == '<?php echo esc_js( $tg_item_name ) ?>'){
                                        jQuery( '<?php echo esc_js( $tg_item_id ) ?>' ).slideDown('fast');
                                    }
                                <?php
                                }
                            }
                        ?>
                     });
                });
            </script>
            <?php
        }



        /**
         * Multiple Select
         */
        private function multiple_select(){
            ?>
            <select name="<?php echo $this->option_name.'[]' ?>" <?php echo $this->item_id_attr ?> multiple="multiple">

                <?php

                    $data = maybe_unserialize( $this->current_value );

                    $i = 0;
                    foreach ( $this->settings['options'] as $option_key => $option ){
                        $selected = '';
                        if ( ( ! empty( $data ) && !is_array( $data ) && $data == $option_key ) || ( ! empty( $data) && is_array($data) && in_array( $option_key , $data ) ) || ( empty( $data ) && $i==1 ) ){
                            $selected = 'selected="selected"';
                        }

                        ?>
                            <option value="<?php echo $option_key ?>" <?php echo $selected ?>><?php echo $option ?></option>
                        <?php
                    }
                ?>
            </select>
            <?php
        }



        /**
         * Textarea
         */
        private function textarea(){
            ?>
                <textarea <?php echo $this->item_id_attr ?> <?php echo $this->name_attr ?> rows="3" dir="auto"><?php echo esc_textarea( $this->current_value ) ?></textarea>
            <?php
        }



        /**
         * Color
         */
        private function color(){

            $custom_class = ! empty( $this->settings['color_class'] ) ? $this->settings['color_class'] : 'thfColorSelector';
            $plugin_color  = TCW::get_option( 'global_color', '#000000' );
            ?>

                <div class="thf-custom-color-picker">
                    <input class="<?php echo $custom_class ?>" <?php echo $this->item_id_attr ?> <?php echo $this->name_attr ?> type="text" value="<?php echo $this->current_value ?>" data-palette="<?php echo $plugin_color ?>, #9b59b6, #3498db, #2ecc71, #f1c40f, #34495e, #e74c3c" style="width:80px;">
                </div>
            <?php
        }



        /**
         * Icon
         */
        private function icon(){
            ?>
                <div class="thf-custom-icon-picker">
                    <input type="hidden" <?= $this->item_id_attr ?> <?= $this->name_attr ?> value="<?= $this->current_value ?>">
					<div data-target="#<?= $this->item_id ?>" class="button icon-picker fa <?= $this->current_value ?>"></div>
                </div>
            <?php
        }



        /**
         * Editor
         */
        private function editor(){

            wp_editor(
                $this->current_value,
                $this->item_id,
                array(
                    'textarea_name' => $this->option_name,
                    'editor_height' => '400px',
                    'media_buttons' => false,
                )
            );
        }


        /**
         * Fonts
         */
        private function fonts(){
            ?>
            <input <?php echo $this->name_attr ?> <?php echo $this->item_id_attr ?> class="thf-select-font" type="text" value="<?php echo esc_attr( $this->current_value ) ?>">
        <?php
        }



        /**
         * Tab Title
         */
        private function tab_title(){
            ?>
            <div class="thf-tab-head">
                <h2>
                    <?php

                        echo $this->settings['title'];

                        if( ! empty( $this->settings['id'] ) ){
                            do_action( 'Themesfa/admin_after_tab_title', $this->settings['id'] );
                        }
                    ?>
                </h2>

                <?php do_action( 'Themesfa/save_button' ); ?>

                <?= $this->tab_title_submenu(); ?>

                <div class="clear"></div>
            </div>
            <?php
        }

        /**
        * @return mixed
        */
        private function tab_title_submenu(){
            $settings_tabs = apply_filters( 'Themesfa/options_tab_title', '' );

            $output = '';

            $id =   isset( $this->settings['id'] ) ? $this->settings['id'] : null;
            $parent = isset( $this->settings['parent'] ) ? $this->settings['parent'] : $id;

            $id= str_replace('-tab','',$id);
            $parent= str_replace('-tab','',$parent);

            $this->current = [];
            $this->current['id']= $id;
            $this->current['parent']= $parent;

            if( !empty( $parent ) ){
                $parent = str_replace('-tab','',$parent) ?: null;
                $parent = explode('-',$parent);

                if( !empty( $parent ) && is_array( $parent ) )
                    $parent = $parent[0];

                if( isset( $settings_tabs[$parent]['submenu'] ) ){
                    $tabs = [];
                    $tabs[$parent] = $settings_tabs[$parent];
                    unset($tabs[$parent]['submenu']);

                    $tabs = array_merge( $tabs,$settings_tabs[$parent]['submenu'] );

                    $output .= "<div class=\"thf-tab-head-submenu\">";
                    $output .= $this->tab_title_submenu_loop( $tabs, $parent );
                    $output .= "</div>";
                }
            }

            return $output;
        }

        /**
        * @param $tabs
        * @param string $parent
        * @param int $depth
        *
        * @return mixed
        */
        private function tab_title_submenu_loop( $tabs ,$parent = "", $depth = 0 ){
            $output = "";
            $attr =  !empty( $parent ) && $depth > 0 ?  " data-parent=\"thf-options-tab-$parent\"" : '';

            $output .= "<ul$attr>";
            foreach ( $tabs as $tab => $settings ) {
                $class = 'active';

                if( !empty($parent) && $tab !== $parent )
                    $tab   = $parent . '-' . $tab;

                $submenu = "";
                if ( isset( $settings['submenu'] ) ) {
                    $class .= ' has-child';
                    $submenu_tabs = [];
                    $submenu_tabs[$tab] = $settings;
                    unset( $submenu_tabs[$tab]['submenu'] );

                    $submenu_tabs = array_merge($submenu_tabs, $settings['submenu']);
                    $submenu = call_user_func( [__CLASS__,__FUNCTION__], $submenu_tabs, $tab, $depth + 1 );
                }


               $title = $settings['title'];

               $output .= "<li class=\"thf-tabs thf-options-tab-$tab $class\">";
               $output .= "    <a href=\"#thf-options-tab-$tab\">" . $title . "</a>";
               $output .= "    ". $submenu;
               $output .= "</li>";

            }
            $output .= "</ul>";

             return $output;
        }


        /**
         * Notice Message
         */
        private function notice_message(){

            $this->custom_class .= ' thf-message-hint';

            if( $this->option_type == 'error' ){
                $this->custom_class .= ' thf-message-error';
            }
            elseif( $this->option_type == 'success' ){
                $this->custom_class .= ' thf-message-success';
            }

            ?>
                <p <?php echo $this->item_id_wrap ?> class="<?php echo $this->custom_class ?>"><?php echo $this->settings['text'] ?></p>
            <?php
        }



        /**
         * Hidden
         */
        private function hidden(){
            ?>
                <input <?php echo $this->name_attr ?> type="hidden" value="<?php echo esc_attr( $this->current_value ) ?>">
            <?php
        }



        /**
         * Number
         */
        private function number(){
            ?>
            <input style="width:60px" min="-1000" max="1000000" <?php echo $this->item_id_attr ?> <?php echo $this->name_attr ?> type="number" value="<?php echo esc_attr( $this->current_value ) ?>" <?php echo $this->placeholder_attr ?>>
            <?php
        }



        /**
         * Section Head
         */
        private function section_head(){
            ?>

            <h3 <?php echo $this->item_id_attr ?> class="thf-section-title <?php echo $this->custom_class ?>">
                <?php

                    echo $this->settings['title'];

                    if( ! empty( $this->settings['id'] ) ){
                        do_action( 'Themesfa/admin_after_head_title', $this->settings['id'] );
                    }
                ?>
            </h3>

            <?php
        }



        /**
         * Option Head
         */
        private function option_head(){
            ?>

            <div <?php echo $this->item_id_wrap ?> class="option-item <?php echo $this->custom_class ?>">

            <?php

            if( ! empty( $this->settings['pre_text'] ) ){
                ?>
                    <div class="thf-option-pre-label"><?php echo $this->settings['pre_text'] ?></div>
                    <div class="clear"></div>
                <?php
            }

            if( ! empty( $this->settings['name'] ) ){
                ?>
                <span class="thf-label"><?php echo $this->settings['name']; ?></span>
                <?php
            }
        }



        /**
         * Visual
         */
        private function visual(){
            ?>
            <ul id="thf_<?php echo $this->item_id ?>" class="thf-options">

                <?php

                $i = 0;

                $images_path = empty( $external_images ) ? TCW_ADMIN_URL .'/assets/images/' : '';

                foreach ( $this->settings['options'] as $option_key => $option ){
                    $i++;

                    $checked = '';
                    if( ( ! empty( $this->current_value ) && $this->current_value == $option_key ) || ( empty( $this->current_value ) && $i==1 ) ){
                        $checked = 'checked="checked"';
                    }

                    ?>
                        <li class="visual-option-<?php echo $option_key ?>">
                            <input <?php echo $this->name_attr ?> type="radio" value="<?php echo $option_key ?>" <?php echo $checked ?>>
                            <a class="checkbox-select" href="#">

                            <?php

                                if( is_array( $option ) ){
                                    foreach ( $option as $description => $img_data ){

                                        if( is_array( $img_data ) ){

                                            $img_value = reset( $img_data );
                                            $key = key($img_data);
                                            unset( $img_data[ $key ] );

                                            $data_attr = '';
                                            if( !empty( $img_data ) && is_array( $img_data ) ){
                                                foreach ($img_data as $data_name => $data_value) {
                                                    $data_attr = ' data-'. $data_name .'="'. $data_value .'"';
                                                }
                                            }
                                            ?>
                                                <img class="<?php echo $key ?>" <?php echo $data_attr ?> src="<?php echo $images_path.$img_value ?>" alt="">
                                            <?php
                                        }
                                        else{
                                            ?>
                                                <img src="<?php echo $images_path.$img_data ?>" alt="">
                                            <?php
                                        }

                                        if( ! empty( $description ) ){
                                            ?>
                                                <span><?php echo $description ?></span>
                                            <?php
                                        }
                                    }
                                }
                                else{
                                    ?>
                                        <img src="<?php echo $images_path.$option ?>" alt="">
                                    <?php
                                }
                            ?>
                            </a>
                        </li>
                    <?php
                }

            echo"</ul>";

            if( empty( $this->settings['toggle'] ) ){
                return;
            }
            ?>

            <script>
                jQuery(document).ready(function(){
                    jQuery( '.<?php echo esc_js( $this->item_id ) ?>-options' ).hide();
                    <?php
                    if( ! empty( $this->settings['toggle'][ $this->current_value ] )){ ?>
                        jQuery( '<?php echo esc_js( $this->settings['toggle'][ $this->current_value ] ) ?>' ).show();
                    <?php
                    }elseif( is_array( $this->settings['toggle'] ) ){
                        $first_elem = reset( $this->settings['toggle'] ) ?>
                        jQuery( '<?php echo esc_js( $first_elem ) ?>' ).show();
                    <?php
                    }
                    ?>

                jQuery(document).on( 'click', '#thf_<?php echo esc_js( $this->item_id ) ?> a', function(){
                    selected_val = jQuery( this ).parent().find( 'input' ).val();
                    jQuery( '.<?php echo esc_js( $this->item_id ) ?>-options' ).hide();
                    <?php
                        foreach( $this->settings['toggle'] as $tg_item_name => $tg_item_id ){
                            if( ! empty( $tg_item_id ) ){ ?>
                                if ( selected_val == '<?php echo esc_js( $tg_item_name ) ?>'){
                                    jQuery( '<?php echo esc_js( $tg_item_id ) ?>' ).slideDown('fast');

                                    // CodeMirror
                                    jQuery( '<?php echo esc_js( $tg_item_id ) ?>' ).find('.CodeMirror').each(function(i, el){
                                    el.CodeMirror.refresh();
                                    });
                                }
                            <?php
                            }
                        }
                    ?>
                 });
                });
            </script>
            <?php
        }



        /**
         * Select
         */
        private function select(){
            ?>
            <div class="thf-custom-select">
                <select <?php echo $this->item_id_attr ?> <?php echo $this->name_attr ?>>
                    <?php
                        $i = 0;
                        if( ! empty( $this->settings['options'] ) && is_array( $this->settings['options'] ) ){
                            foreach ( $this->settings['options'] as $option_key => $option ){
                                $i++;

                                $selected = '';
                                if ( ( ! empty( $this->current_value ) && $this->current_value == $option_key ) || ( empty( $this->current_value ) && $i==1 ) ){
                                    $selected = 'selected="selected"';
                                }
                                ?>

                                <option value="<?php echo $option_key ?>" <?php echo $selected ?>><?php echo $option ?></option>

                                <?php
                            }
                        }
                    ?>
                </select>
            </div>

            <?php

                if( ! empty( $this->settings['toggle'] ) ){ ?>
                <script>
                    jQuery(document).ready(function(){
                        jQuery( '.<?php echo esc_js( $this->item_id ) ?>-options' ).hide();

                        <?php
                        if( ! empty( $this->settings['toggle'][ $this->current_value ] )){ ?>
                            jQuery( '<?php echo esc_js( $this->settings['toggle'][ $this->current_value ] ) ?>' ).show();
                        <?php
                        }elseif( is_array( $this->settings['toggle'] ) ){
                            $first_elem = reset( $this->settings['toggle'] ) ?>
                            jQuery( '<?php echo esc_js( $first_elem ) ?>' ).show();
                        <?php
                        }
                        ?>

                        jQuery("select[name='<?php echo esc_js( $this->option_name ) ?>']").change(function(){
                            selected_val = jQuery( this ).val();
                            jQuery( '.<?php echo esc_js( $this->item_id ) ?>-options' ).slideUp('fast');

                            <?php
                            foreach( $this->settings['toggle'] as $tg_item_name => $tg_item_id ){
                                if( ! empty( $tg_item_id ) ){ ?>
                                    if ( selected_val == '<?php echo esc_js( $tg_item_name ) ?>'){
                                        jQuery( '<?php echo esc_js( $tg_item_id ) ?>' ).slideDown('fast');
                                    }
                                <?php
                                }
                            }

                            ?>
                         });
                    });
                </script>
                <?php
            }
        }



        /**
         * Background
         */
        private function background(){

            $current_value = maybe_unserialize( $this->current_value );
            ?>

            <input id="<?php echo esc_attr( $this->item_id ) ?>-img" class="thf-img-path thf-background-path" type="text" size="56" name="<?php echo esc_attr( $this->option_name ) ?>[img]" value="<?php if( ! empty( $current_value['img'] )) echo esc_attr( $current_value['img'] ) ?>">
            <input id="upload_<?php echo esc_attr( $this->item_id ) ?>_button" type="button" class="button" value="<?php esc_html_e( 'Upload', TCW_TEXTDOMAIN )  ?>">

            <div class="thf-background-options">

                <select name="<?php echo esc_attr( $this->option_name ) ?>[repeat]" id="<?php echo esc_attr( $this->item_id ) ?>[repeat]">
                    <option value=""></option>
                    <option value="no-repeat" <?php if( ! empty($current_value['repeat'])) selected( $current_value['repeat'], 'no-repeat' ) ?>><?php esc_html_e( 'no-repeat', TCW_TEXTDOMAIN )         ?></option>
                    <option value="repeat"    <?php if( ! empty($current_value['repeat'])) selected( $current_value['repeat'], 'repeat'    ) ?>><?php esc_html_e( 'Tile', TCW_TEXTDOMAIN )              ?></option>
                    <option value="repeat-x"  <?php if( ! empty($current_value['repeat'])) selected( $current_value['repeat'], 'repeat-x'  ) ?>><?php esc_html_e( 'Tile Horizontally', TCW_TEXTDOMAIN ) ?></option>
                    <option value="repeat-y"  <?php if( ! empty($current_value['repeat'])) selected( $current_value['repeat'], 'repeat-y'  ) ?>><?php esc_html_e( 'Tile Vertically', TCW_TEXTDOMAIN )   ?></option>
                </select>

                <select name="<?php echo esc_attr( $this->option_name ) ?>[attachment]" id="<?php echo esc_attr( $this->item_id ) ?>[attachment]">
                    <option value=""></option>
                    <option value="fixed"  <?php if( ! empty($current_value['attachment'])) selected( $current_value['attachment'], 'fixed'  ) ?>><?php esc_html_e( 'Fixed',  TCW_TEXTDOMAIN ) ?></option>
                    <option value="scroll" <?php if( ! empty($current_value['attachment'])) selected( $current_value['attachment'], 'scroll' ) ?>><?php esc_html_e( 'Scroll', TCW_TEXTDOMAIN ) ?></option>
                    <option value="cover"  <?php if( ! empty($current_value['attachment'])) selected( $current_value['attachment'], 'cover'  ) ?>><?php esc_html_e( 'Cover',  TCW_TEXTDOMAIN ) ?></option>
                </select>

                <select name="<?php echo esc_attr( $this->option_name ) ?>[hor]" id="<?php echo esc_attr( $this->item_id ) ?>[hor]">
                    <option value=""></option>
                    <option value="left"   <?php if( ! empty($current_value['hor'])) selected( $current_value['hor'], 'left'   ) ?>><?php esc_html_e( 'Left',   TCW_TEXTDOMAIN ) ?></option>
                    <option value="right"  <?php if( ! empty($current_value['hor'])) selected( $current_value['hor'], 'right'  ) ?>><?php esc_html_e( 'Right',  TCW_TEXTDOMAIN ) ?></option>
                    <option value="center" <?php if( ! empty($current_value['hor'])) selected( $current_value['hor'], 'center' ) ?>><?php esc_html_e( 'Center', TCW_TEXTDOMAIN ) ?></option>
                </select>

                <select name="<?php echo esc_attr( $this->option_name ) ?>[ver]" id="<?php echo esc_attr( $this->item_id ) ?>[ver]">
                    <option value=""></option>
                    <option value="top"    <?php if( ! empty($current_value['ver'])) selected( $current_value['ver'], 'top'    ) ?>><?php esc_html_e( 'Top',    TCW_TEXTDOMAIN ) ?></option>
                    <option value="bottom" <?php if( ! empty($current_value['ver'])) selected( $current_value['ver'], 'bottom' ) ?>><?php esc_html_e( 'Bottom', TCW_TEXTDOMAIN ) ?></option>
                    <option value="center" <?php if( ! empty($current_value['ver'])) selected( $current_value['ver'], 'center' ) ?>><?php esc_html_e( 'Center', TCW_TEXTDOMAIN ) ?></option>
                </select>
            </div>

            <div id="<?php echo esc_attr( $this->item_id ) ?>-preview" class="img-preview" <?php if( empty( $current_value['img'] )) echo 'style="display:none;"' ?>>
                <img src="<?php if( ! empty($current_value['img'] )) echo esc_attr( $current_value['img'] ) ; else echo TCW_ADMIN_URL.'/assets/images/empty.png'; ?>" alt="">
                <a class="del-img" title="<?php esc_html_e( 'Remove', TCW_TEXTDOMAIN ) ?>"></a>
            </div>

            <?php
        }



        /**
         * Typography
         */
        private function typography(){

            $current_value = wp_parse_args( $this->current_value, array(
                'size'        => '',
                'line_height' => '',
                'weight'      => '',
                'transform' 	=> '',
            ));

            ?>

            <div class="thf-custom-select typography-custom-slelect">
                <select name="<?php echo esc_attr( $this->option_name ) ?>[size]" id="<?php echo esc_attr( $this->settings['id'] ) ?>[size]">

                    <option <?php selected( $current_value['size'], '' ); ?> <?php disabled(1,1); ?>><?php esc_html_e( 'Font Size in Pixels', TCW_TEXTDOMAIN ); ?></option>
                    <option value=""><?php esc_html_e( 'Default', TCW_TEXTDOMAIN ); ?></option>
                    <?php for( $i=7 ; $i<61 ; $i++){ ?>
                        <option value="<?php echo esc_attr( $i ) ?>" <?php selected( $current_value['size'], $i ); ?>><?php echo esc_html( $i ) ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="thf-custom-select typography-custom-slelect">
                <select name="<?php echo esc_attr( $this->option_name ) ?>[line_height]" id="<?php echo esc_attr( $this->settings['id'] ) ?>[line_height]">

                    <option <?php selected( $current_value['line_height'], '' ); ?> <?php disabled(1,1); ?>><?php esc_html_e( 'Line Height', TCW_TEXTDOMAIN ); ?></option>
                    <option value=""><?php esc_html_e( 'Default', TCW_TEXTDOMAIN ); ?></option>

                    <?php for( $i=10 ; $i<=60 ; $i+=0.5 ){ ?>
                        <option value="<?php echo esc_attr( $i/10 ) ?>" <?php selected( $current_value['line_height'], ($i/10) ); ?>><?php echo number_format_i18n( $i/10 , 2 )?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="thf-custom-select typography-custom-slelect">
                <select name="<?php echo esc_attr( $this->option_name ) ?>[weight]" id="<?php echo esc_attr( $this->settings['id'] ) ?>[weight]">
                    <option <?php selected( $current_value['weight'], '' ); ?> <?php disabled(1,1); ?>><?php esc_html_e( 'Font Weight', TCW_TEXTDOMAIN ); ?></option>
                    <option value=""><?php esc_html_e( 'Default', TCW_TEXTDOMAIN ); ?></option>
                    <option value="100" <?php selected( $current_value['weight'], 100 ); ?>><?php esc_html_e( 'Thin 100',        TCW_TEXTDOMAIN ); ?></option>
                    <option value="200" <?php selected( $current_value['weight'], 200 ); ?>><?php esc_html_e( 'Extra 200 Light', TCW_TEXTDOMAIN ); ?></option>
                    <option value="300" <?php selected( $current_value['weight'], 300 ); ?>><?php esc_html_e( 'Light 300',       TCW_TEXTDOMAIN ); ?></option>
                    <option value="400" <?php selected( $current_value['weight'], 400 ); ?>><?php esc_html_e( 'Regular 400',     TCW_TEXTDOMAIN ); ?></option>
                    <option value="500" <?php selected( $current_value['weight'], 500 ); ?>><?php esc_html_e( 'Medium 500',      TCW_TEXTDOMAIN ); ?></option>
                    <option value="600" <?php selected( $current_value['weight'], 600 ); ?>><?php esc_html_e( 'Semi 600 Bold',   TCW_TEXTDOMAIN ); ?></option>
                    <option value="700" <?php selected( $current_value['weight'], 700 ); ?>><?php esc_html_e( 'Bold 700',        TCW_TEXTDOMAIN ); ?></option>
                    <option value="800" <?php selected( $current_value['weight'], 800 ); ?>><?php esc_html_e( 'Extra 800 Bold',  TCW_TEXTDOMAIN ); ?></option>
                    <option value="900" <?php selected( $current_value['weight'], 900 ); ?>><?php esc_html_e( 'Black 900',       TCW_TEXTDOMAIN ); ?></option>
                </select>
            </div>

            <div class="thf-custom-select typography-custom-slelect">
                <select name="<?php echo esc_attr( $this->option_name ) ?>[transform]" id="<?php echo esc_attr( $this->settings['id'] ) ?>[transform]">

                    <option <?php selected( $current_value['transform'], '' ); ?> <?php disabled(1,1); ?>><?php esc_html_e( 'Capitalization', TCW_TEXTDOMAIN ); ?></option>
                    <option value=""><?php esc_html_e( 'Default', TCW_TEXTDOMAIN ); ?></option>
                    <option value="uppercase"  <?php selected( $current_value['transform'], 'uppercase' ); ?>><?php esc_html_e( 'UPPERCASE',  TCW_TEXTDOMAIN ); ?></option>
                    <option value="capitalize" <?php selected( $current_value['transform'], 'capitalize' );?>><?php esc_html_e( 'Capitalize', TCW_TEXTDOMAIN ); ?></option>
                    <option value="lowercase"  <?php selected( $current_value['transform'], 'lowercase' ); ?>><?php esc_html_e( 'lowercase',  TCW_TEXTDOMAIN ); ?></option>
                </select>
            </div>
            <?php
        }



        /**
         * Prepare Data
         */
        private function prepare_data( $settings, $option_name, $data ){
            $key = '';

            // Default Settings
            $settings = wp_parse_args( $settings, array(
                'id'    => '',
                'key' => '',
                'class' => '',
            ));

            $this->settings = $settings;
            $this->option_name = $option_name;

            extract( $settings );

            $this->option_type = ! empty( $type ) ? $type : false;

            // ID
            $this->item_id .= ! empty( $prefix ) ? $prefix.'-' : '';
            $this->item_id .= ! empty( $id )     ? $id         : '';

            if( ! empty( $this->item_id ) && $this->item_id != ' ' ){

                $this->item_id = ( $type == 'arrayText' ) ? $this->item_id . '-'. $key : $this->item_id;

                $this->item_id_attr = 'id="'. $this->item_id .'"';
                $this->item_id_wrap = 'id="'. $this->item_id .'-item"';
            }

            // Class
            $this->custom_class = ! empty( $class ) ?  ' '. $class .'-options' : '';

            // Name
            $this->name_attr = 'name="'. $option_name .'"';

            // Placeholder
            $this->placeholder_attr = ! empty( $placeholder ) ? 'placeholder="'. $placeholder .'"' : '';

            // Get the option stored data
            if( ! empty( $data ) ){
                $this->current_value = $data;
            }
            elseif( ! empty( $default ) ){
                $this->current_value = $default;
            }
        }

    }
}


/**
 * Build The options
 */
function thf_build_option( $value, $option_name, $data ) {
    new THF_SETTINGS( $value, $option_name, $data );
}
