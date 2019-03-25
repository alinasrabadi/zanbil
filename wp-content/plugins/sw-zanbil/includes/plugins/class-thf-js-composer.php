<?php

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

if ( !class_exists( 'TCW_PLUGIN_JS_COMPOSER' ) ) {

    /**
     * Main TCW_PLUGIN_JS_COMPOSER class
     * @since       1.0.0
     */
    class TCW_PLUGIN_JS_COMPOSER {

        /**
         * The single instance of the class.
         *
         * @var TCW_PLUGIN_JS_COMPOSER
         * @since 1.0.0
         */
        private static $_instance;


        /**
         * instance of the TCW.
         *
         * @var TCW
         * @since 1.0.0
         */
        private static $TCW;


        /**
         * Path to vc elements folder
         *
         * @var string
         * @since 1.0.0
         */
        private static $vc_elements_path = TCW_PATH . '/includes/plugins/vc-elements/';


        /**
         * Get active instance
         *
         * @param   TCW $TCW
         *
         * @since   1.0.0
         * @static
         * @see     tcw_plugin_js_composer()
         * @return  TCW_PLUGIN_JS_COMPOSER - Main instance
         */
        public static function instance( TCW $TCW ) {
            if ( is_null( self::$_instance ) ) {
                self::$TCW       = $TCW;
                self::$_instance = new self();
            }
            return self::$_instance;
        }


        /**
         * Check is active
         *
         * @since   1.0.0
         * @static
         * @return  boolean
         */
        public static function is_active() {
            return THF_JS_COMPOSER_IS_ACTIVE;
        }


        /**
         * TCW_PLUGIN_JS_COMPOSER constructor.
         */
        public function __construct() {
            if ( !self::is_active() ) return;

            $this->hook();
        }

        private function hook() {
            //-> Ajax Search Product
            add_action( 'wp_ajax_thf_vc_wc_search_product', [ $this, 'process_ajax_search_product_request' ] );
            add_action( 'wp_ajax_thf_vc_wc_get_product', [ $this, 'process_ajax_get_product_request' ] );
            vc_add_shortcode_param( 'dropdown_multi_product', [ $this, 'dropdown_multi_product_settings_field' ] );

            add_action( 'vc_before_init', [ $this, 'vc_before_init_actions' ] );
        }

        public function vc_before_init_actions() {
            $this->include_elements();
            $this->register_elements();
        }

        public function include_elements() {
            require_once self::$vc_elements_path . 'realtime-offer-element.php';
            require_once self::$vc_elements_path . 'price-table.php';
        }

        public function register_elements() {
            new vcRealtimeOffer();
            new vcPriceTable();
        }

        /**
         * Create multi dropdown param type
         *
         * @param $param
         * @param $value
         *
         * @return string
         * @since 1.0.0
         */
        public function dropdown_multi_product_settings_field( $param, $value ) {

            $table = '';
            $ids   = [];

            if ( !empty( $value ) ) {
                $ids = explode( ',', $value );
                if ( !is_array( $ids ) ) {
                    $ids = [];
                }
            }

            ob_start();
            self::$TCW->WOOCOMMERCE->PRICE_TABLE->get_shortcode_content( false, $ids );
            $table = ob_get_clean();

            $param_line = '';
            $param_line .= '<div class="tcw_ajax_search_product ' . esc_attr( $param['type'] ) . ' ' . esc_attr( $param['class'] ) . '">';
            $param_line .= '    <div class="tcw_ajax_search_product_inputs">';
            $param_line .= '        <input type="search" class="wpb_vc_param_value wpb-input wpb-select tcw_ajax_search_product_input" autocomplete="off">';
            $param_line .= '        <input type="hidden" class="wpb_vc_param_value wpb-textinput title textfield tcw_ajax_search_product_hidden_input hidden" name="' . esc_attr( $param['param_name'] ) . '" value="' . esc_attr( implode( ',', $ids ) ) . '">';
            $param_line .= '        <div class="tcw_ajax_search_product_result"></div>';
            $param_line .= '    </div>';
            $param_line .= '    <div class="tcw_ajax_search_product_list">' . $table . '</div>';
            $param_line .= '</div>';

            ob_start(); ?>
            <script type="text/javascript">
                let instanse = null;
                const tcwPriceList = {
                    init         : function () {
                        const _self = this;

                        _self.$wrapper = jQuery('.tcw_ajax_search_product');
                        _self.$inputs = _self.$wrapper.find('.tcw_ajax_search_product_inputs');
                        _self.$result = _self.$inputs.find('.tcw_ajax_search_product_result');
                        _self.$searchInput = _self.$inputs.find('input.tcw_ajax_search_product_input');
                        _self.$hiddenInput = _self.$inputs.find('input.tcw_ajax_search_product_hidden_input');
                        _self.$productList = _self.$wrapper.find('.tcw_ajax_search_product_list');
                        _self.$productListBody = _self.$productList.find('tbody');
                        _self.$productListItems = _self.$productListBody.find('> tr');
                        _self.$productListDeleteBtn = _self.$productListItems.find('.delete');
                        _self.searchTypeDelay = 0;
                        _self.searchMinCharsToSearch = 3;
                        _self.searchTimeout = null;
                        _self.searchToBeExecuted = null;
                        _self.searchCurrentData = null;

                        _self.events();

                    },
                    events       : function () {
                        const _self = this;

                        _self.$searchInput.unbind();
                        _self.$productListBody.unbind();
                        _self.$productListItems.unbind();
                        _self.$productListDeleteBtn.unbind();

                        _self.$searchInput.keyup(function (event) {
                            let searchValue         = jQuery.trim(jQuery(this).val()),
                                searchLength        = searchValue.length,
                                charCode            = String.fromCharCode(event.keyCode),
                                isWordCharacter     = charCode.match(/\w/),
                                isBackspaceOrDelete = (event.keyCode === 8 || event.keyCode === 46),
                                isEnter             = event.keyCode === 13;

                            if (_self.searchTimeout) {
                                clearTimeout(_self.searchTimeout);
                            }

                            if (isWordCharacter || isBackspaceOrDelete || isEnter) {
                                if (searchLength >= _self.searchMinCharsToSearch && searchLength !== 0) {
                                    _self.$result.hide();

                                    _self.searchTimeout = setTimeout(() => _self.searchProduct(), _self.searchTypeDelay);
                                } else {
                                    _self.hideResult();
                                }
                            }
                        }).focus(function () {
                            if (!_self.is_empty(jQuery(this).val())) {
                                if (_self.$result.attr('has-item') === 'true') {
                                    _self.$result.show();
                                }
                            }
                        });

                        _self.$productListDeleteBtn.click(function (e) {
                            e.preventDefault();
                            let $this = jQuery(this),
                                id    = $this.parents('tr').attr('data-id');

                            _self.removeProduct(id)
                        });

                        jQuery(document).mouseup(function (e) {
                            let container = _self.$inputs;

                            // if the target of the click isn't the container nor a descendant of the container
                            if (!container.is(e.target) && container.has(e.target).length === 0) {
                                _self.$result.hide().attr('has-item', true);
                            }
                        });
                    },
                    searchProduct: function () {
                        const _self = this;

                        if (!_self.is_empty(_self.searchToBeExecuted)) {
                            _self.searchToBeExecuted.abort();
                            _self.searchToBeExecuted = null;
                        }

                        _self.searchCurrentData = {
                            action : 'thf_vc_wc_search_product',
                            search : jQuery.trim(_self.$searchInput.val()) || null,
                            current: jQuery.trim(_self.$hiddenInput.val()) || null,
                        };

                        if (_self.is_empty(_self.searchCurrentData.search)) {
                            return false;
                        }

                        _self.searchToBeExecuted = _self.ajaxRequest({
                            data      : _self.searchCurrentData,
                            beforeSend: function () {
                                _self.spinner('show');
                            },
                            success   : function (response) {
                                _self.spinner('hide');

                                if (response.success) {
                                    _self.showResult(response.data.items)
                                } else if (_self.isset(response.data.msg)) {
                                    _self.$result.html(response.data.msg).show().attr('has-item', false);
                                }
                            }
                        });

                    },
                    addProduct   : function (id) {
                        const _self = this;

                        if (!_self.is_empty(id)) {
                            _self.ajaxRequest({
                                data      : {
                                    action: 'thf_vc_wc_get_product',
                                    id    : id
                                },
                                beforeSend: function () {
                                    _self.spinner('show', 'result', id);
                                },
                                success   : function (response) {
                                    _self.spinner('success', 'result', id);

                                    if (response.success) {
                                        let hiddenValue = _self.$hiddenInput.val(),
                                            item        = response.data.item,
                                            no_item     = _self.$productListBody.find('.no-items').length > 0,
                                            index       = no_item === true ? 0 : _self.$productListItems.length,
                                            row         = _self.$productListBody.get(0).insertRow(index),
                                            html        = '';

                                        if (_self.is_empty(hiddenValue)) {
                                            hiddenValue = [];
                                        } else {
                                            hiddenValue = hiddenValue.split(',')
                                        }

                                        hiddenValue.push(id);
                                        _self.$hiddenInput.val(hiddenValue.toString());

                                        row.insertCell(0).innerHTML = index + 1;
                                        row.insertCell(1).innerHTML = '<a href="' + item.link + '" target="_blank">' + item.title + '</a>';
                                        row.insertCell(2).innerHTML = item.old_price;
                                        row.insertCell(3).innerHTML = item.new_price;
                                        row.insertCell(4).innerHTML = item.diff;
                                        row.insertCell(5).innerHTML = item.status_icon;

                                        jQuery(row).attr('data-id', id);
                                        jQuery(row).find('>td:last-child').addClass('text-center');

                                        if (no_item) {
                                            _self.$productListBody.html(row);
                                        } else {
                                            _self.$productListBody.append(row);
                                        }

                                        _self.$result.find('li[data-id="' + id + '"]').remove();

                                        _self.init();

                                    } else if (_self.isset(response.data.msg)) {
                                        _self.spinner('error', 'result', id);
                                    }
                                }
                            });
                        }
                    },
                    removeProduct: function (id) {
                        const _self = this;

                        if (!_self.is_empty(id)) {
                            let hiddenValue = _self.$hiddenInput.val(),
                                item        = _self.$productListBody.find('tr[data-id="' + id + '"]');

                            if (_self.is_empty(hiddenValue)) {
                                hiddenValue = [];
                            } else {
                                hiddenValue = hiddenValue.split(',');
                                hiddenValue = jQuery.grep(hiddenValue, function (value) {
                                    return value !== id;
                                });
                            }

                            _self.$hiddenInput.val(hiddenValue.toString());

                            item.remove();
                            _self.init();
                        }
                    },
                    hideResult   : function () {
                        const _self = this;

                        _self.$result.html("").hide().attr('has-item', false);
                    },
                    showResult   : function (items) {
                        const _self = this;

                        let html = '<ul>';

                        jQuery.each(items, function (index, item) {
                            html += '<li data-id="' + item.id + '">' + item.title + '</li>'
                        });

                        html += '</ul>';

                        _self.$result.html(html).show().attr('has-item', true);
                        _self.$result.find('ul>li').click(function () {
                            let $this = jQuery(this),
                                id    = jQuery(this).attr('data-id');

                            _self.addProduct(id);
                        })
                    },
                    spinner      : function (action, type = 'search', id = null) {
                        const _self = this;

                        let el = _self.$wrapper;

                        if (type === 'result') {
                            el = _self.$result.find('li[data-id="' + id + '"]');
                        }

                        if (action === 'show') {
                            el.addClass('tcw_loading').removeClass('tcw_loading_success tcw_loading_error');
                        } else if (action === 'hide') {
                            el.removeClass('tcw_loading tcw_loading_success tcw_loading_error');
                        } else if (action === 'error') {
                            el.addClass('tcw_loading tcw_loading_error').removeClass('tcw_loading_success');
                        } else if (action === 'success') {
                            el.addClass('tcw_loading tcw_loading_success').removeClass('tcw_loading_error');
                        }
                    },
                    ajaxRequest  : function (args) {
                        const _self = this;

                        if (!_self.isset(args)) {
                            return false;
                        }

                        args = jQuery.extend({
                            url : '<?= esc_js( admin_url( 'admin-ajax.php' ) ) ?>',
                            type: 'POST',
                        }, args);

                        args.data.security = '<?= esc_js( wp_create_nonce( 'thf_ajax_security_nonce' ) ) ?>';

                        return jQuery.ajax(args);
                    },
                    isset        : function (value) {
                        return typeof value !== 'undefined';
                    },
                    is_empty     : function (value) {
                        return value === null || value === '';
                    }
                };

                instanse = tcwPriceList.init();
            </script>
            <style>
                .tcw_ajax_search_product {
                    position: relative;
                    font-family: 'DroidNaskh', 'Roboto', tahoma, sans-serif !important;
                }

                .tcw_ajax_search_product_inputs,
                .tcw_ajax_search_product_list {
                    position: relative;
                    margin-bottom: 20px;
                }

                .tcw_ajax_search_product_list .fa {
                    margin: 0;
                    padding: 0;
                }

                .tcw_ajax_search_product_list .svg-icon {
                    width: 25px;
                    height: 25px;
                    margin: 0 auto;
                }

                .tcw_ajax_search_product_list td.td-product-thump img {
                    width: 50px;
                    height: 50px;
                }

                .tcw_loading {
                    position: relative;
                }

                .tcw_loading::before {
                    content: '';
                    background: url(http://localhost/Projects/zanbil/wp/wp-includes/images/spinner.gif) no-repeat center center;
                    width: 20px;
                    height: 20px;
                    position: absolute;
                    top: 0;
                    left: 0;
                }

                .tcw_ajax_search_product.tcw_loading::before {
                    top: -25px
                }

                .tcw_loading.tcw_loading_success::before,
                .tcw_loading.tcw_loading_error::before, {
                    display: inline-block;
                    font: normal normal normal 14px/1 FontAwesome;
                    font-size: inherit;
                    text-rendering: auto;
                    -webkit-font-smoothing: antialiased;
                    -moz-osx-font-smoothing: grayscale;
                    background: none;
                    width: auto;
                    height: auto;
                }

                .tcw_loading.tcw_loading_success::before {
                    content: "\f00c";
                }

                .tcw_loading.tcw_loading_error::before {
                    content: "\f00d";
                }

                .tcw_ajax_search_product_result {
                    display: none;
                    padding: 10px;
                    border: 1px solid #d2d2d2cc;
                    border-top: 0 none;
                }

                .tcw_ajax_search_product_result ul {
                    margin: 0;
                    padding: 0;
                }

                .tcw_ajax_search_product_result ul > li {
                    cursor: pointer;
                    margin: 0;
                    padding: 6px 0;
                    font-weight: 500;
                }

                .tcw_ajax_search_product_result ul > li:hover {
                    color: #000;
                }

                .svg-icon, .svg-icon > svg {
                    width: auto;
                    height: 100%;
                }


                .text-center {
                    text-align: center !important;
                }
            </style>
            <?php
            $param_line .= ob_get_clean();

            return $param_line;

        }

        public function process_ajax_search_product_request() {
            TCW_HELPER::check_ajax_referer();

            $json = array();

            $current = ( explode( ",", $_POST['current'] ) );

            $filters           = [];
            $filters['search'] = $_POST['search'] ? : '';

            if ( !empty( $current ) && is_array( $current ) ) {
                $filters['exclude'] = $current;
            }

            $loop = self::$TCW->WOOCOMMERCE->product_search_query( $filters );

            //-> Check query has error
            if ( is_wp_error( $loop ) ) {
                $json['msg'] = __( 'An error occurred while processing request!', TCW_TEXTDOMAIN );
                $json['msg'] .= PHP_EOL . $loop->get_error_message();
                wp_send_json_error( $json );
            }

            //-> Loop Query
            $items = [];
            if ( !empty( $loop ) && $loop->have_posts() ) {
                while ( $loop->have_posts() ) {
                    $loop->the_post();
                    global $product;

                    $ID    = $product->get_id();
                    $title = $product->get_title();

                    if ( $product->is_type( 'variable' ) ) {
                        $variations = $product->get_available_variations();

                        if ( !empty( $variations ) && is_array( $variations ) ) {
                            foreach ( $variations as $va_index => $va ) {
                                $va_ID = intval( $va['variation_id'] );

                                if ( in_array( $va_ID, $filters['exclude'] ) ) continue;

                                $va_attrs           = $va['attributes'];
                                $va_list_attributes = [];

                                if ( !empty( $va_attrs ) && is_array( $va_attrs ) ) {
                                    foreach ( $va_attrs as $attr_key => $attr_value ) {
                                        $taxonomy             = wc_attribute_taxonomy_name( str_replace( 'attribute_pa_', '', $attr_key ) );
                                        $attr_label           = wc_attribute_label( $taxonomy );
                                        $attr_value           = urldecode( $attr_value );
                                        $va_list_attributes[] = "<strong>$attr_label:</strong> $attr_value";
                                    }
                                }

                                $va_title = $title . ' - ' . implode( ',  ', $va_list_attributes );

                                $items[$va_ID]['id']    = $va_ID;
                                $items[$va_ID]['title'] = $va_title;
                            }
                        }
                    } else {
                        $items[$ID]['id']    = $ID;
                        $items[$ID]['title'] = $title;
                    }

                }
                wp_reset_query();
            } else {
                $json['msg'] = __( 'No result!', TCW_TEXTDOMAIN );

                wp_send_json_error( $json );
            }

            $json['items'] = $items;

            wp_send_json_success( $json );
        }

        public function process_ajax_get_product_request() {
            TCW_HELPER::check_ajax_referer();

            $json = array();

            if ( isset( $_POST['id'] ) && !empty( $_POST['id'] ) ) {
                $ID = intval( $_POST['id'] );

                $data = self::$TCW->WOOCOMMERCE->PRICE_TABLE->get_product( $ID );

                if ( !is_wp_error( $data ) && is_array( $data ) ) {

                    $json['item'] = $data;

                    wp_send_json_success( $json );
                } else {
                    $json['msg'] = $data->get_error_message();
                }
            }

            wp_send_json_error( $json );
        }
    }
} // End if class_exists check

/**
 * Main instance of TCW_PLUGIN_JS_COMPOSER.
 * Returns the main instance of TCW_PLUGIN_JS_COMPOSER to prevent the need to use globals.
 *
 * @param   TCW $TCW
 *
 * @since   1.0.0
 * @return  TCW_PLUGIN_JS_COMPOSER - Main instance
 */
function tcw_plugin_js_composer( TCW $TCW ) {
    return TCW_PLUGIN_JS_COMPOSER::instance( $TCW );
}