(function ($) {
    "use strict";
    $.haggle = {
        options: {},
        init: function (options) {
            var that = this;
            that.options = options;
            this.initHaggle();
        },
        initHaggle: function () {

            if (this.options.modal_type == 'jquery_ui') {
                this.initUiDialog();
            } else {
                this.initSimplemodal();
            }
        },
        initUiDialog: function () {
            var that = this;
            if (!$("#haggle-dialog").leghth) {
                $('body').append('<div id="haggle-dialog"><div>');
            }
            $('.haggle_link').on('click', function () {

                $("#haggle-dialog").html('<i class="icon16 loading"></i>');
                $("#haggle-dialog").dialog('open');
                $.ajax({
                    url: that.options.haggle_url,
                    data: $(this).data('json'),
                    cache: false,
                    success: function (html) {
                        $("#haggle-dialog").html(html);
                        $("#haggle-dialog").dialog({
                            width: $('#haggle-dialog > div').width() + 50,
                            height: $('#haggle-dialog > div').height() + 50
                        });
                    }});

                return false;
            });
        },
        initSimplemodal: function () {
            var that = this;
            if (!$("#haggle-dialog").leghth) {
                $('body').append('<div id="haggle-dialog"><div>');
            }
            $('.haggle_link').on('click', function () {

                $("#haggle-dialog").html('<i class="icon16 loading"></i>');
                $("#haggle-dialog").modal();
                $.ajax({
                    url: that.options.haggle_url,
                    data: $(this).data('json'),
                    cache: false,
                    success: function (html) {
                        $("#haggle-dialog").html(html);
                        $("#simplemodal-container").css({
                            width: $('#haggle-dialog > div').width() + 50,
                            height: $('#haggle-dialog > div').height() + 50
                        });
                    }});

                return false;
            });
        }
    };
})(jQuery);
