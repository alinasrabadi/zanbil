const THF_Customize_WC_Sharing = {

    init() {
        if (isset(window.THF_NOTIFIER)) {
            window.THF_NOTIFIER = null;
            delete window.THF_NOTIFIER;
        }

        window.THF_NOTIFIER = THF_Customize_WC_Sharing.constructor();
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
        _self.$sharingForm = null;

        _self.sharingModal = '#sharingModal';
        _self.sharingForm = '#sharingModalForm';
        _self.sharingFormBtn = '.btn[type="submit"]';
        _self.sharingFormAlert = '.modal-form-alert';
        _self.$sharingFormInputs = null;
        _self.sharingFormData = {};

        _self.sharingToBeExecuted = null;
    },

    setup() {
        const _self = this;

        _self.$doc.on('submit', _self.sharingForm, function (e) {
            e.preventDefault();

            _self.$sharingForm = jQuery(this);
            _self.startSharing();
        });
    },

    startSharing() {
        const _self = this;
        let messages = null;

        _self.$sharingFormInputs = _self.$sharingForm.find('input,button[type="submit"]');
        _self.$sharingFormBtn = _self.$sharingForm.find(_self.sharingFormBtn);

        if (!is_empty(_self.sharingToBeExecuted)) {
            return false;
        }

        _self.sharingToBeExecuted = ajaxRequest({
            data      : _self.getFormData(),
            beforeSend: function () {
                _self.$sharingFormBtn.button('loading');
                _self.inputsDisabled(true);
                _self.setFormAlert('hide');
                messages = null;
            },
            success   : function (response) {
                _self.$sharingFormBtn.button('reset');
                _self.inputsDisabled(false);

                if (isset(response.data.msg)) {
                    messages = response.data.msg;
                }

                if (response.success) {
                    _self.setFormAlert('success', messages);

                    // jQuery(_self.sharingModal).modal('hide');
                } else {
                    _self.setFormAlert('danger', messages)
                }
            },
            complete  : function () {
                _self.sharingToBeExecuted = null;
            }
        });
    },

    inputsDisabled(action = true) {
        const _self = this;

        if (action === true) {
            _self.$sharingFormInputs.attr('disabled', 'disabled').addClass('disabled');
        } else {
            _self.$sharingFormInputs.removeAttr('disabled').removeClass('disabled');
        }
    },

    getFormData() {
        const _self = this;

        _self.sharingFormData = {};
        _self.sharingFormData['action'] = 'thf_woocommerce_sharing';

        jQuery.map(_self.$sharingForm.serializeArray(), function (n, i) {
            _self.sharingFormData[n['name']] = n['value'];
        });

        return _self.sharingFormData;
    },

    setFormAlert(type, messages = null) {
        const _self = this;

        _self.$sharingFormAlert = _self.$sharingForm.find(_self.sharingFormAlert);

        _self.$sharingFormAlert.removeClass('hidden').hide();

        if (type === 'hide') {
            _self.$sharingFormAlert.hide().html("");
        } else if (type === 'success') {
            _self.$sharingFormAlert.addClass('alert-success').removeClass('alert-danger').show()
        } else if (type === 'danger') {
            _self.$sharingFormAlert.addClass('alert-danger').removeClass('alert-success').show()
        }

        if (type !== 'hide' && is_empty(messages)) {
            _self.$sharingFormAlert.hide();

            return false;
        }

        if (jQuery.isArray(messages)) {
            if (messages.length > 1) {
                let template = '';

                template += '<ul>';
                jQuery.each(messages, function (i, msg) {
                    template += '<li>' + msg + '</li>';
                });
                template += '</ul>';

                _self.$sharingFormAlert.html(template);
            } else {
                _self.$sharingFormAlert.html(messages[0]);
            }
        } else {
            _self.$sharingFormAlert.html(messages);
        }


    }
};


jQuery(document).ready(function () {
    // Run Main Object
    THF_Customize_WC_Sharing.init();
});