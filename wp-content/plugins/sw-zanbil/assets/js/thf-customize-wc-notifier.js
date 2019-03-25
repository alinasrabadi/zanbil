const THF_Customize_WC_Notifier = {

    init() {
        if (isset(window.THF_NOTIFIER)) {
            window.THF_NOTIFIER = null;
            delete window.THF_NOTIFIER;
        }

        window.THF_NOTIFIER = THF_Customize_WC_Notifier.constructor();
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
        _self.$subscribeForm = null;

        _self.subscribeModal = '#notifierModal';
        _self.subscribeForm = '#notifierModalForm';
        _self.subscribeFormBtn = '.btn[type="submit"]';
        _self.subscribeFormAlert = '.modal-form-alert';
        _self.$subscribeFormInputs = null;
        _self.subscribeFormData = {};

        _self.subscribeToBeExecuted = null;
    },

    setup() {
        const _self = this;

        _self.$doc.on('submit', _self.subscribeForm, function (e) {
            e.preventDefault();

            _self.$subscribeForm = jQuery(this);
            _self.subscribeUser();
        });
    },

    subscribeUser() {
        const _self = this;
        let messages = null;

        _self.$subscribeFormInputs = _self.$subscribeForm.find('input,button[type="submit"]');
        _self.$subscribeFormBtn = _self.$subscribeForm.find(_self.subscribeFormBtn);

        if (!is_empty(_self.subscribeToBeExecuted)) {
            return false;
        }

        _self.subscribeToBeExecuted = ajaxRequest({
            data      : _self.getFormData(),
            beforeSend: function () {
                _self.$subscribeFormBtn.button('loading');
                _self.inputsDisabled(true);
                _self.setFormAlert('hide');
                messages = null;
            },
            success   : function (response) {
                _self.$subscribeFormBtn.button('reset');
                _self.inputsDisabled(false);

                if (isset(response.data.msg)) {
                    messages = response.data.msg;
                }

                if (response.success) {
                    _self.setFormAlert('success', messages);

                    // jQuery(_self.subscribeModal).modal('hide');
                } else {
                    _self.setFormAlert('danger', messages)
                }
            },
            complete  : function () {
                _self.subscribeToBeExecuted = null;
            }
        });
    },

    inputsDisabled(action = true) {
        const _self = this;

        if (action === true) {
            _self.$subscribeFormInputs.attr('disabled', 'disabled').addClass('disabled');
        } else {
            _self.$subscribeFormInputs.removeAttr('disabled').removeClass('disabled');
        }
    },

    getFormData() {
        const _self = this;

        _self.subscribeFormData = {};
        _self.subscribeFormData['action'] = 'thf_woocommerce_subscribe_user';

        jQuery.map(_self.$subscribeForm.serializeArray(), function (n, i) {
            _self.subscribeFormData[n['name']] = n['value'];
        });

        return _self.subscribeFormData;
    },

    setFormAlert(type, messages = null) {
        const _self = this;

        _self.$subscribeFormAlert = _self.$subscribeForm.find(_self.subscribeFormAlert);

        _self.$subscribeFormAlert.removeClass('hidden').hide();

        if (type === 'hide') {
            _self.$subscribeFormAlert.hide().html("");
        } else if (type === 'success') {
            _self.$subscribeFormAlert.addClass('alert-success').removeClass('alert-danger').show()
        } else if (type === 'danger') {
            _self.$subscribeFormAlert.addClass('alert-danger').removeClass('alert-success').show()
        }

        if (type !== 'hide' && is_empty(messages)) {
            _self.$subscribeFormAlert.hide();

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

                _self.$subscribeFormAlert.html(template);
            } else {
                _self.$subscribeFormAlert.html(messages[0]);
            }
        } else {
            _self.$subscribeFormAlert.html(messages);
        }


    }
};


jQuery(document).ready(function () {
    // Run Main Object
    THF_Customize_WC_Notifier.init();
});