function setCookie(c_name, value, exdays) {
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value = encodeURIComponent(value) + ((exdays === null) ? "" : "; expires=" + exdate.toUTCString());
    document.cookie = c_name + "=" + c_value;
}

function getCookie(c_name) {
    var i, x, y, ARRcookies = document.cookie.split(";");
    for (i = 0; i < ARRcookies.length; i++) {
        x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
        y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
        x = x.replace(/^\s+|\s+$/g, "");
        if (x == c_name) {
            return decodeURIComponent(y);
        }
    }
}

jQuery(document).ready(function ($) {
    $('.wpb_settings_accordion').accordion({
        active:(getCookie('wpb_js_composer_settings_group_tab') ? getCookie('wpb_js_composer_settings_group_tab') : false),
        collapsible:true,
        change:function (event, ui) {
            if (ui.newHeader.attr('id') !== undefined)
                setCookie('wpb_js_composer_settings_group_tab', '#' + ui.newHeader.attr('id'), 365 * 24 * 60 * 60);
            else
                setCookie('wpb_js_composer_settings_group_tab', '', 365 * 24 * 60 * 60);
        }
    });
    $('.wpb-settings-select-all-shortcodes').click(function (e) {
        e.preventDefault();
        $(this).parent().parent().find('[type=checkbox]').attr('checked', true);
    });
    $('.wpb-settings-select-none-shortcodes').click(function (e) {
        e.preventDefault();
        $(this).parent().parent().find('[type=checkbox]').removeAttr('checked');
    });
    $('.vc-settings-tab-control').click(function (e) {
        e.preventDefault();
        if ($(this).hasClass('nav-tab-active')) return false;
        var tab_id = $(this).attr('href');
        $('.vc-settings-tabs > .nav-tab-active').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        $('.vc-settings-tab-content').hide().removeClass('vc-settings-tab-content-active');
        $(tab_id).fadeIn(400, function () {
            $(this).addClass('vc-settings-tab-content-active');
        });
    });
    $('.vc-settings-tab-content').submit(function () {
        setCookie('wpb_js_composer_settings_active_tab', $('.vc-settings-tab-control.nav-tab-active').attr('href'), 365 * 24 * 60 * 60);
        return true;
    });

    $('#vc-settings-disable-notification-button').click(function (e) {
        e.preventDefault();
        $.ajax({
            type:'POST',
            url:window.ajaxurl,
            data:{action:'wpb_remove_settings_notification_element_css_class'}
        });
        $(this).remove();
    });
    $('.vc_show_example').click(function (e) {
        e.preventDefault();
        var $helper = $('.vc_helper');
        if ($helper.is(':animated')) return false;
        $helper.toggle(100);
    });

    $('#vc-settings-custom-css-reset-data').click(function (e) {
        e.preventDefault();
        if (confirm(window.i18nLocaleSettings.are_you_sure_reset_css_classes)) {
            $('#vc-settings-element_css-action').val('remove_all_css_classes');
            $('#vc-settings-element_css').attr('action', window.location.href).find('[type=submit]').click();
        }
    });
    $('.color-control').wpColorPicker();
    $('#vc-settings-color-restore-default').click(function (e) {
        e.preventDefault();
        if (confirm(window.i18nLocaleSettings.are_you_sure_reset_color)) {
            $('#vc-settings-color-action').val('restore_color');
            $('#vc-settings-color').attr('action', window.location.href).find('[type=submit]').click();
        }
    });
    $('#wpb_js_use_custom').change(function () {
        if ($(this).is(':checked')) {
            $('#vc-settings-color').addClass('color_enabled');
        } else {
            $('#vc-settings-color').removeClass('color_enabled');

        }
    });
});

