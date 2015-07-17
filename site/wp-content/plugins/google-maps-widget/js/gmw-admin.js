/*
 * Google Maps Widget
 * (c) Web factory Ltd, 2012 - 2015
 */


jQuery(function($) {
  // init JS for each active widget
  $(".widget-liquid-right [id*='" + gmw.id_base + "'].widget, .inactive-sidebar [id*='" + gmw.id_base + "'].widget").each(function (i, widget) {
    gmw_init_widget_ui(widget);
  }); // foreach GMW active widget

  // re-init JS on widget update and add
  $(document).on('widget-updated', function(event, widget) {
    gmw_init_widget_ui(widget);
  });
  $(document).on('widget-added', function(event, widget) {
    gmw_init_widget_ui(widget);
  });


  // init JS UI for an individual GMW
  function gmw_init_widget_ui(widget) {
    gmw_change_pin_type(widget);
    gmw_change_link_type(widget);

    // handle dropdown fields that have dependant fields
    $('.gmw_thumb_pin_type', widget).on('change', function(e) {
      gmw_change_pin_type(widget);
    });
    $('.gmw_thumb_link_type', widget).on('change', function(e) {
      gmw_change_link_type(widget);
    });
    $('.gmw_thumb_color_scheme', widget).on('change', function(e) {
      gmw_promo_option_change(widget, '.gmw_thumb_color_scheme');
    });
    $('.gmw_lightbox_skin', widget).on('change', function(e) {
      gmw_promo_option_change(widget, '.gmw_lightbox_skin');
    });

    // open promo/activation dialog
    $('.open_promo_dialog', widget).on('click', function(e) {
      e.preventDefault();
      gmw_open_promo_dialog(this);

      return false;
    });

    // init tabs
    $('.gmw-tabs', widget).tabs({ active: gmw_get_active_tab($('.gmw-tabs', widget).attr('id')),
                 activate: function(event, ui) { gmw_save_active_tab(this); }
    });
  } // gmw_init_widget_ui


  // get active tab index from cookie
  function gmw_get_active_tab(el_id) {
    id = parseInt(0 + $.cookie(el_id), 10);
    if (isNaN(id) === true) {
      id = 0;
    }

    return id;
  } // get_active_tab


  // save active tab index to cookie
  function gmw_save_active_tab(elem) {
    $.cookie($(elem).attr('id'), $(elem).tabs('option', 'active'), { expires: 30 });
  } // save_active_tab


  // show/hide custom link field based on user's link type choice
  function gmw_change_link_type(widget) {
    if ($('.gmw_thumb_link_type', widget).val() == 'custom') {
      $('.gmw_thumb_link_section', widget).show();
    } else {
      $('.gmw_thumb_link_section', widget).hide();
    }
  } // link_type


  // show/hide custom pin URL field based on user's pin type choice
  function gmw_change_pin_type(widget) {
    if ($('.gmw_thumb_pin_type', widget).val() == 'custom') {
      $('.gmw_thumb_pin_type_custom_section', widget).show();
      $('.gmw_thumb_pin_type_predefined_section', widget).hide();
    } else {
      $('.gmw_thumb_pin_type_custom_section', widget).hide();
      $('.gmw_thumb_pin_type_predefined_section', widget).show();
    }
  } // pin_type


  // extra features related functions
  // open promo dialog on load
  if (window.location.search.search('gmw_open_promo_dialog') != -1) {
    gmw_open_promo_dialog();
  }


  // opens promo dialog when special value is selected in widget's options
  function gmw_promo_option_change(widget, el) {
    if (($(el, widget).val()) == '-1') {
      $(el, widget).find('option').attr('selected', '');
      $(el, widget).find('option:first').attr('selected', 'selected');
      gmw_open_promo_dialog(widget);
    }
  } // promo_option_change


  // already subscribed button in dialog
  $('#gmw_already_subscribed').on('click', function(e) {
    e.preventDefault();
    $('#gmw_dialog_subscribe').hide();
    $('#gmw_dialog_activate').show();

    return false;
  }); // already subscribed click


  // subscribe button in dialog
  $('#gmw_subscribe').on('click', function(e) {
    e.preventDefault();

    err = false;
    $('#gmw_promo_dialog input.error').removeClass('error');
    $('#gmw_promo_dialog span.error').hide();

    if ($('#gmw_name').val().length < 3) {
      $('#gmw_name').addClass('error');
      $('#gmw_promo_dialog span.error.name').show();
      $('#gmw_name').focus().select();

      err = true;
    } // check name

    re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (!re.test($('#gmw_email').val())) {
      $('#gmw_email').addClass('error');
      $('#gmw_promo_dialog span.error.email').show();
      $('#gmw_email').focus().select();
      return false;
    }

    if (err) {
      return false;
    }

    $.post(ajaxurl, { action: 'gmw_subscribe', 'name': $('#gmw_name').val(), 'email': $('#gmw_email').val()}, function(data) {
      if (data && data.success == true) {
        $('#gmw_dialog_subscribe').hide();
        $('#gmw_dialog_activate').show();
        alert(gmw.subscribe_ok);
      } else if (data && data.success == false && data.data == 'duplicate') {
        $('#gmw_dialog_subscribe').hide();
        $('#gmw_dialog_activate').show();
        alert(gmw.subscribe_duplicate);
      } else {
        alert(gmw.subscribe_error);
      }
    }, 'json').fail(function() {
      alert(gmw.undocumented_error);
    });

    return false;
  }); // subscribe click


  // check code and activate button in dialog
  $('#gmw_activate').on('click', function(e) {
    e.preventDefault();

    $('#gmw_promo_dialog input.error').removeClass('error');
    $('#gmw_promo_dialog span.error').hide();

    $.post(ajaxurl, { action: 'gmw_activate', 'code': $('#gmw_code').val()}, function(data) {
      if (data && data.success == true) {
        alert(gmw.activate_ok);
        if ($('#gmw_promo_dialog').data('widget-id')) {
          $('#' + $('#gmw_promo_dialog').data('widget-id') + ' .widget-control-save').trigger('click');
          $('#gmw_activate_notice').hide();
          $('#gmw_promo_dialog').dialog('close');
        } else {
          window.location = 'widgets.php';
        }
      } else {
        $('#gmw_promo_dialog span.error.gmw_code').show();
        $('#gmw_code').focus().select();
      }
    }, 'json').fail(function() {
      alert(gmw.undocumented_error);
    });

    return false;
  }); // activate button click


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
        close: function(event, ui) { $('#gmw_promo_dialog').data('widget-id', ''); }
    }).dialog('open');

    if (widget) {
      $('#gmw_promo_dialog').data('widget-id', $(widget).parents('div.widget').attr('id'));
    }
  } // open_promo_dialog
}); // onload