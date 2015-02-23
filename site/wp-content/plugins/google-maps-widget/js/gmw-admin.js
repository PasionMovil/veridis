/*
 * Google Maps Widget
 * (c) Web factory Ltd, 2012 - 2015
 */

jQuery(function($) {
  $('.gmw-tabs').each(function(i, el) {
    change_pin_type(el);
    change_link_type(el);
    $('.gmw_thumb_pin_type', el).on('change', function() { change_pin_type(el) });
    $('.gmw_thumb_link_type', el).on('change', function() { change_link_type(el) });
    $('.gmw_thumb_color_scheme', el).on('change', function() { gmw_promo_option_change(el, '.gmw_thumb_color_scheme') });
    $('.gmw_lightbox_skin', el).on('change', function() { gmw_promo_option_change(el, '.gmw_lightbox_skin') });

    el_id = $(el).attr('id');
    $(el).tabs({ active: get_active_tab(el_id),
                 activate: function(event, ui) { save_active_tab(this); }
    });
  });

  // get active tab index from cookie
  function get_active_tab(el_id) {
    id = parseInt(0 + $.cookie(el_id), 10);

    return id;
  } // get_active_tab

  // save active tab index to cookie
  function save_active_tab(elem) {
    $.cookie($(elem).attr('id'), $(elem).tabs('option', 'active'), { expires: 30 });
  } // save_active_tab

  // show/hide custom link field based on user's link type choice
  function change_link_type(widget) {
    if ($('.gmw_thumb_link_type', widget).val() == 'custom') {
      $('.gmw_thumb_link_section', widget).show();
    } else {
      $('.gmw_thumb_link_section', widget).hide();
    }
  } // link_type

  // show/hide custom pin URL field based on user's pin type choice
  function change_pin_type(widget) {
    if ($('.gmw_thumb_pin_type', widget).val() == 'custom') {
      $('.gmw_thumb_pin_type_custom_section', widget).show();
      $('.gmw_thumb_pin_type_predefined_section', widget).hide();
    } else {
      $('.gmw_thumb_pin_type_custom_section', widget).hide();
      $('.gmw_thumb_pin_type_predefined_section', widget).show();
    }
  } // pin_type

  // opens promo dialog when special value is selected in widget's options
  function gmw_promo_option_change(widget, el) {
    if (($(el, widget).val()) == '-1') {
      $(el, widget).find('option').attr('selected', '');
      $(el, widget).find('option:first').attr('selected', 'selected');
      gmw_open_promo_dialog(widget);
    }
  } // promo_option_change

  // open promo/activation dialog
  $('.open_promo_dialog').on('click', function(e) {
    gmw_open_promo_dialog(this);

    e.preventDefault();
    return false;
  });

  // button in dialog
  $('#gmw_already_subscribed').on('click', function(e) {
    $('#gmw_dialog_subscribe').hide();
    $('#gmw_dialog_activate').show();

    e.preventDefault();
    return false;
  });

  // button in dialog
  $('#gmw_subscribe').on('click', function(e) {
    e.preventDefault();

    $err = 0;
    $('#gmw_promo_dialog input.error').removeClass('error');
    $('#gmw_promo_dialog span.error').hide();

    if ($('#gmw_name').val().length < 3) {
      $('#gmw_name').addClass('error');
      $('#gmw_promo_dialog span.error.name').show();
      $('#gmw_name').focus().select();

      $err = 1;
    }

    re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (!re.test($('#gmw_email').val())) {
      $('#gmw_email').addClass('error');
      $('#gmw_promo_dialog span.error.email').show();
      $('#gmw_email').focus().select();
      return false;
    }

    if ($err) {
      return false;
    }

    $.post(ajaxurl, { action: 'gmw_subscribe', 'name': $('#gmw_name').val(), 'email': $('#gmw_email').val()}, function(data) {
      if (data == 'ok') {
        $('#gmw_dialog_subscribe').hide();
        $('#gmw_dialog_activate').show();
        alert(gmw.subscribe_ok);
      } else if (data == 'duplicate') {
        $('#gmw_dialog_subscribe').hide();
        $('#gmw_dialog_activate').show();
        alert(gmw.subscribe_duplicate);
      } else {
        alert(gmw.subscribe_error);
      }
    });

    return false;
  });

  // button in dialog
  // check code and activate
  $('#gmw_activate').on('click', function(e) {
    e.preventDefault();

    $('#gmw_promo_dialog input.error').removeClass('error');
    $('#gmw_promo_dialog span.error').hide();

    $.get(ajaxurl, { action: 'gmw_activate', 'code': $('#gmw_code').val()}, function(data) {
      if (data == '1') {
        alert(gmw.activate_ok);
        if ($('#gmw_promo_dialog').data('widget-id')) {
          $('#' + $('#gmw_promo_dialog').data('widget-id') + ' .widget-control-save').trigger('click');
        }
        $('#gmw_promo_dialog').dialog('close');
      } else {
        $('#gmw_promo_dialog span.error.gmw_code').show();
        $('#gmw_code').focus().select();
      }
    });

    return false;
  });

  // open promo/activation dialog
  function gmw_open_promo_dialog(widget) {
    $('#gmw_dialog_subscribe').show();
    $('#gmw_dialog_activate').hide();

    $('#gmw_promo_dialog').dialog({
        'dialogClass' : 'wp-dialog gmw-dialog',
        'modal' : true,
        'width': 650,
        'title': gmw.dialog_title,
        'autoOpen': false,
        'closeOnEscape': true,
        close: function(event, ui) { $('#gmw_promo_dialog').data('widget-id', '') }
    }).dialog('open');

    if (widget) {
      $('#gmw_promo_dialog').data('widget-id', $(widget).parents('div.widget').attr('id'));
    }
  } // open_promo_dialog

  // re-tab on GUI rebuild
  $('div[id*="googlemapswidget"]').ajaxSuccess(function(event, request, option) {
    $('.gmw-tabs').each(function(i, el) {
      change_pin_type(el);
      change_link_type(el);
      $('.gmw_thumb_pin_type', el).on('change', function() { change_pin_type(el) });
      $('.gmw_thumb_link_type', el).on('change', function() { change_link_type(el) });
      $('.gmw_thumb_color_scheme', el).on('change', function() { gmw_promo_option_change(el, '.gmw_thumb_color_scheme'); });
      $('.gmw_lightbox_skin', el).on('change', function() { gmw_promo_option_change(el, '.gmw_lightbox_skin') });

      el_id = $(el).attr('id');
      $(el).tabs({ active: get_active_tab(el_id),
                   activate: function(event, ui) { save_active_tab(this); }
      });
    });

    // todo fix multiple actions on single selector
    $('.open_promo_dialog').on('click', function(e) {
      gmw_open_promo_dialog(this);

      e.preventDefault();
      return false;
    });
  });
}); // onload