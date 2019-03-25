const THF_Customize_WC_Ajax_Search_Product = {

    init() {
        if (isset(window.THF_AJAX_SEARCH_PRODUCT)) {
            window.THF_AJAX_SEARCH_PRODUCT = null;
            delete window.THF_AJAX_SEARCH_PRODUCT;
        }

        window.THF_AJAX_SEARCH_PRODUCT = THF_Customize_WC_Ajax_Search_Product.constructor();
    },

    constructor() {
        const _self = this;

        _self.defineVariables();
        _self.setup();
        _self.events();
    },

    defineVariables() {
        const _self = this;

        _self.$doc = jQuery(document);
        _self.$window = jQuery(window);

        _self.$searchForm = jQuery('#searchform_special', _self.$doc);
        _self.$searchInput = jQuery('input[name="s"]', _self.$searchForm);
        _self.$searchInputSelect = jQuery('select[name="search_category"]', _self.$searchForm);
        _self.$searchSubmitBtn = jQuery('.button-search-pro', _self.$searchForm);

        _self.$searchSubmitBtn.after('<div class="search-result"></div>');
        _self.$searchResult = jQuery('.search-result', _self.$searchForm);

        _self.$searchSpinnerIcon = THF.spinner;
        _self.$searchSpinner = jQuery('<div class="search-spinner d-flex align-center justify-center">' + _self.$searchSpinnerIcon + '</div>');

        _self.searchTypeDelay = 0;
        _self.searchMinCharsToSearch = 3;
        _self.searchToBeExecuted = null;

    },


    setup() {
        const _self = this;

        _self.$searchForm.addClass('ajax-search');
        _self.$searchInput.attr('autocomplete', 'off');

        _self.$doc.mouseup(function (e) {
            let container = _self.$searchForm;

            // if the target of the click isn't the container nor a descendant of the container
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                _self.searchSpinner('hide');
                _self.$searchResult.html("").hide();
            }
        });

    },

    events() {
        const _self = this;

        //-> on user start typing
        _self.$searchInput.keyup(function (event) {
            let searchValue         = jQuery.trim(jQuery(this).val()),
                searchLength        = searchValue.length,
                charCode            = String.fromCharCode(event.keyCode),
                isWordCharacter     = charCode.match(/\w/),
                isBackspaceOrDelete = (event.keyCode === 8 || event.keyCode === 46);

            _self.clearSearchTimeout();

            if (isWordCharacter || isBackspaceOrDelete) {
                if (searchLength >= _self.searchMinCharsToSearch && searchLength !== 0) {
                    _self.searchTimeout = setTimeout(() => _self.searchProduct(), _self.searchTypeDelay);
                } else {
                    _self.$searchResult.hide();
                    _self.searchSpinner('hide');
                }
            }
        });

        //-> on select category
        _self.$searchInputSelect.change(function () {
            _self.clearSearchTimeout();
            _self.searchProduct();
        });

    },


    clearSearchTimeout() {
        const _self = this;

        if (_self.searchTimeout) {
            clearTimeout(_self.searchTimeout);
        }
    },

    searchProduct() {
        const _self = this;

        if (!is_empty(_self.searchToBeExecuted)) {
            _self.searchToBeExecuted.abort();
            _self.searchToBeExecuted = null;
        }

        _self.searchCurrentData = {
            action  : 'thf_woocommerce_search_product',
            search  : jQuery.trim(_self.$searchInput.val()) || null,
            category: _self.$searchInputSelect.val() || null,
        };

        if (is_empty(_self.searchCurrentData.search)) {
            return false;
        }

        _self.searchToBeExecuted = ajaxRequest({
            data      : _self.searchCurrentData,
            beforeSend: function () {
                _self.searchSpinner('show');
            },
            success   : function (response) {
                _self.searchSpinner('hide');

                if (response.success) {
                    _self.$searchResult.html(response.data.html).show();
                } else if (isset(response.data.msg)) {
                    _self.$searchResult.html(response.data.msg).show();
                }
            }
        });

    },

    searchSpinner(action) {
        const _self       = this,
              currentIcon = _self.$searchResult.attr('data-icon');

        if (action === 'show' && currentIcon !== 'spinner') {
            _self.$searchResult.html(_self.$searchSpinner).attr('data-icon', 'spinner').show();
        } else if (action !== 'show' && currentIcon === 'spinner') {
            _self.$searchResult.html("").attr('data-icon', 'search');
        }
    }
};


jQuery(document).ready(function () {
    // Run Main Object
    THF_Customize_WC_Ajax_Search_Product.init();
});