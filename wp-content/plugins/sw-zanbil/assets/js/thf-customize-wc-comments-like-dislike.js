const THF_Customize_WC_Comments_Like_Dislike = {

    init() {
        if (isset(window.THF_COMMENTS_LIKE_DISLIKE)) {
            window.THF_COMMENTS_LIKE_DISLIKE = null;
            delete window.THF_COMMENTS_LIKE_DISLIKE;
        }

        window.THF_COMMENTS_LIKE_DISLIKE = THF_Customize_WC_Comments_Like_Dislike.constructor();
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

        _self.$trigger = null;
        _self.like_dislike_trigger_class = '.tcw-comment-like-dislike-trigger';
        _self.ajaxFlag = 0;

    },

    setup() {
        const _self = this;

        _self.$doc.on('click', _self.like_dislike_trigger_class, function () {
            _self.$trigger = jQuery(this);
            _self.comment_like_dislike()
        })

    },

    comment_like_dislike() {
        const _self = this;

        if (_self.ajaxFlag === 1) {
            return false;
        }

        let restriction       = _self.$trigger.attr('data-restriction'),
            comment_id        = _self.$trigger.attr('data-comment-id'),
            trigger_type      = _self.$trigger.attr('data-trigger-type'),
            current_count     = _self.$trigger.attr('data-counter'),
            new_count         = parseInt(current_count) + 1,
            user_ip           = _self.$trigger.attr('data-user-ip'),
            ip_check          = _self.$trigger.attr('data-ip-check'),
            cookie            = thf_getCookie('tcw_comment_' + comment_id),
            like_dislike_flag = 1;

        _self.$like_btn = jQuery('.tcw-comment-like-trigger[data-comment-id="' + comment_id + '"]');
        _self.$dislike_btn = jQuery('.tcw-comment-dislike-trigger[data-comment-id="' + comment_id + '"]');

        if (
            (restriction === 'cookie' && !is_empty(cookie)) ||
            (restriction === 'ip' && ip_check === '1')
        ) {
            like_dislike_flag = 0;
        }

        if (like_dislike_flag === 1) {
            ajaxRequest({
                data      : {
                    comment_id: comment_id,
                    action    : 'tcw_comment_ajax_action',
                    type      : trigger_type,
                    user_ip   : user_ip
                },
                beforeSend: function () {
                    _self.ajaxFlag = 1;

                    _self.like_dislike_disabled(true);
                },
                success   : function (response) {
                    _self.ajaxFlag = 0;
                    _self.like_dislike_disabled(false);

                    if (response.success) {
                        if (restriction === 'ip') {
                            _self.$trigger.attr('data-ip-check', 1);
                        } else if (restriction === 'cookie') {
                            thf_setCookie('tcw_comment_' + comment_id, 1, 365);
                        }

                        _self.$like_btn.attr('data-counter', response.data.like_count);
                        _self.$dislike_btn.attr('data-counter', response.data.dislike_count);
                    }
                }
            });
        }
    },

    like_dislike_disabled(action = true) {
        const _self = this;

        if (action === true) {
            _self.$like_btn.attr('disabled', 'disabled').addClass('disabled');
            _self.$dislike_btn.attr('disabled', 'disabled').addClass('disabled');
        } else {
            _self.$like_btn.removeAttr('disabled').removeClass('disabled');
            _self.$dislike_btn.removeAttr('disabled').removeClass('disabled');
        }
    },

    set_comment_like_dislike(like_step, dislike_step) {
        const _self = this;

        let like_count    = parseInt(_self.$like_btn.attr('data-counter')) + like_step,
            dislike_count = parseInt(_self.$dislike_btn.attr('data-counter')) + dislike_step;

        _self.$like_btn.attr('data-counter', like_count);
        _self.$dislike_btn.attr('data-counter', dislike_count);
    }


};


jQuery(document).ready(function () {
    // Run Main Object
    THF_Customize_WC_Comments_Like_Dislike.init();
});