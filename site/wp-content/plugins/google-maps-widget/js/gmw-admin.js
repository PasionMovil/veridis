/*
 * Google Maps Widget
 * (c) Web factory Ltd, 2012 - 2016
 */


jQuery(function($) {
  if (typeof gmw === 'undefined') {
    return;
  }
  
  
  // init tabs on settings
  $('#gmw-settings-tabs').tabs({ active: gmw_get_active_tab('gmw-settings-tabs'),
                                 activate: function(event, ui) { gmw_save_active_tab(this); }
  });
  
  
  // open promo dialog on settings
  $('.settings_page_gmw_options .open_promo_dialog').on('click', function(e) {
    e.preventDefault();

    gmw_open_promo_dialog();

    return false;
  }); // open promo dialog

  
  // branding for widget title
  if (gmw.is_activated) {
    $("[id*='" + gmw.id_base + "-'].widget").each(function (i, widget) {
      title_tmp = $('.widget-title h3', widget).html();
      if (!title_tmp) {
        return true;
      }
      title_tmp = title_tmp.replace('PRO', '<span class="gmw-pro-red">PRO</span>');
      $('.widget-title h3', widget).html(title_tmp);
    }); // foreach GMW widget
  } // branding if PRO
  
  
  // init variables
  if (typeof google != 'undefined') {
    gmw.geocoder = new google.maps.Geocoder();
  }
  gmw.map = gmw.marker = false;

  
  // add clone button only to GMW widgets
  if (gmw.is_activated) {
    $('div[id*="' + gmw.id_base + '"] .widget-control-actions').each(function() {
      var $clone = $('<a>');
      var clone = $clone.get()[0];
      $clone.addClass('gmw-clone-me')
            .attr('title', 'Clone and save the widget in the same sidebar')
            .attr('href', '#')
            .html('Clone');
      $clone.insertAfter($(this).find('.alignleft .widget-control-remove'));
      clone.insertAdjacentHTML('beforebegin', ' | ');
    }); // add clone button  
  } // if activated - clone
  
  
  // init JS for each active widget
  $(".widget-liquid-right [id*='" + gmw.id_base + "-'].widget, .inactive-sidebar [id*='" + gmw.id_base + "'].widget").each(function (i, widget) {
    gmw_init_widget_ui(widget);
  }); // foreach GMW active widget

  
  // re-init JS on widget update and add
  $(document).on('widget-updated', function(event, widget) {
    id = $(widget).attr('id');
    if (id.indexOf(gmw.id_base) != -1) {
      gmw_init_widget_ui(widget);
    }
  });
  $(document).on('widget-added', function(event, widget) {
    id = $(widget).attr('id');
    if (id.indexOf(gmw.id_base) != -1) {
      gmw_init_widget_ui(widget);
    }
  }); // refresh GUI on widget add/update
  
  
  // clone button click
  $(document).on('click', '.gmw-clone-me', function(e) {
    gmw_clone(e, this);
    
    e.preventDefault();
    return false;
  }); // clone button click

  
  // init JS UI for an individual GMW
  function gmw_init_widget_ui(widget) {
    $('.gmw-colorpicker', widget).wpColorPicker();
    $('.gmw-select2', widget).select2({ minimumResultsForSearch: 100, width: '331px' });
    
    // init tabs
    $('.gmw-tabs', widget).tabs({ active: gmw_get_active_tab($('.gmw-tabs', widget).attr('id')),
                 activate: function(event, ui) { gmw_save_active_tab(this); }
    });
    
    // promo options in dropdown
    $('select', widget).on('change', function(event) {
      gmw_promo_option_change(widget, event);
    });

    // handle dropdown fields that have dependant fields
    $('.gmw_thumb_pin_type', widget).on('change', function(e) {
      gmw_change_pin_type(widget);
    }).trigger('change');
    $('.gmw_thumb_link_type', widget).on('change', function(e) {
      gmw_change_link_type(widget);
    }).trigger('change');
    $('.gmw_lightbox_fullscreen', widget).on('change', function(e) {
      gmw_change_fullscreen(widget);
    }).trigger('change');
    $('.gmw_lightbox_mode', widget).on('change', function(e) {
      gmw_change_mode(widget);
    }).trigger('change');

    // open promo/activation dialog
    $('.open_promo_dialog', widget).on('click', function(e) {
      e.preventDefault();

      gmw_open_promo_dialog();

      return false;
    }); // open promo/activation dialog
    
    // open pin library
    $('.open_pins_library', widget).on('click', function(e) {
      e.preventDefault();

      gmw_open_pins_library_dialog(this);

      return false;
    }); // open pin library
    
    // open address picking map dialog - non pro, just ad
    $('a.gmw-pick-address.gmw-pick-address-non-pro', widget).on('click', function(e) {
      e.preventDefault();
                                
      if (confirm(gmw.map_picker_not_active)) {
        gmw_open_promo_dialog();
      }

      return false;
    }); // open address picking map dialog - non pro, just ad

    // open address picking map dialog
    $('a.gmw-pick-address', widget).on('click', function(e) {
      e.preventDefault();

      gmw_open_map_dialog($(this).parents('div.widget'), $(this).data('target'));

      return false;
    }); // open address picking map dialog
    
    // auto-expand textarea
    $('textarea', widget).on('focus', function(e) {
      e.preventDefault();
      
      $(this).attr('rows', '3');

      return false;
    });
    $('textarea', widget).on('focusout', function(e) {
      e.preventDefault();

      $(this).attr('rows', '1');

      return false;
    });
    
    // show help when field is focused
    $('input[type=text], input[type=number], input[type=url], select, textarea', widget).on('focus', function(e) {
      gmw_show_pointer(this, widget, true);
      
    }).on('focusout', function(e) {
      gmw_show_pointer(this, widget, false);
    });
    $('.gmw-select2', widget).on('select2:open', function(e) { gmw_show_pointer(this, widget, true); });
    $('.gmw-select2', widget).on('select2:close', function(e) { gmw_show_pointer(this, widget, false); });
  } // gmw_init_widget_ui

  
  // display help text when element is in focus
  function gmw_show_pointer(element, widget, show) {
    if (gmw.disable_tooltips == '1') {
      return;
    }
    
    if (show) {
      help_text = $(element).data('tooltip');
      
      // skip fields that don't have any help text
      if (!help_text) {
        return;
      }
      
      help_text = help_text.replace(/(?:\r\n|\r|\n)/g, '<br />');
      help_text = help_text.replace(/_(.*)_/g, '<i>$1</i>');
      help_text = help_text.replace(/\*(.*)\*/g, '<b>$1</b>');

      title = $(element).data('title') || $(element).prev('label').html() || gmw.plugin_name;
      title = title.replace(':', '');
    
      try {
        $(gmw_pointer).pointer('close');
      } catch(err) {}
      
      gmw_pointer = $(element).pointer({
          content: '<h3>' + title + '</h3><p>' + help_text + '</p>',
          position: {
              edge: 'bottom',
              align: 'left'
          },
          width: 400,
          pointerClass: 'wp_pointer gmw_pointer'
        }).pointer('open');
    } else {
      try {
        $(gmw_pointer).pointer('close');
      } catch(err) {}
    }
  } // gmw_show_pointer

  
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
    $.cookie($(elem).attr('id'), $(elem).tabs('option', 'active'), { expires: 180 });
  } // save_active_tab


  // show/hide custom link field based on user's link type choice
  function gmw_change_link_type(widget) {
    if ($('.gmw_thumb_link_type', widget).val() == 'custom' || $('.gmw_thumb_link_type', widget).val() == 'custom_blank') {
      $('.gmw_thumb_link_section', widget).show();
    } else {
      $('.gmw_thumb_link_section', widget).hide();
    }
  } // link_type


  // show/hide custom lightbox map size
  function gmw_change_fullscreen(widget) {
    if ($('.gmw_lightbox_fullscreen', widget).val() == '1') {
      $('.gmw_lightbox_fullscreen_custom_section', widget).hide();
    } else {
      $('.gmw_lightbox_fullscreen_custom_section', widget).show();
    }
  } // fullscreen


  // show/hide fields based on mode
  function gmw_change_mode(widget) {
    mode = $('.gmw_lightbox_mode', widget).val();

    $('p[class^="gmw_lightbox_mode_"]', widget).hide();
    $('p.gmw_lightbox_mode_' + mode, widget).show();
  } // mode


  // show/hide custom pin URL field based on user's pin type choice
  function gmw_change_pin_type(widget) {
    type = $('.gmw_thumb_pin_type', widget).val();
    type = type.replace('-', '_');
    
    $('p[class^="gmw_thumb_pin_type_"]', widget).hide();
    $('p.gmw_thumb_pin_type_' + type, widget).show();
  } // pin_type


  // open promo dialog on load
  if (window.location.search.search('gmw_open_promo_dialog') != -1) {
    gmw_open_promo_dialog();
  }

  
  // opens promo dialog when special value is selected in widget's options
  function gmw_promo_option_change(widget, event) {
    if (($(event.target).val()) == '-1') {
      event.stopPropagation();
      event.preventDefault();

      $(event.target).find('option').attr('selected', '');
      $(event.target).find('option:first').attr('selected', 'selected');
      gmw_open_promo_dialog(widget);
    }
  } // promo_option_change

  
  // on hover for pricing table
  $('#gmw_dialog_intro .gmw-promo-box').hover(
    function() {
      $('#gmw_dialog_intro .gmw-promo-box').removeClass('gmw-promo-box-hover');
      $(this).addClass('gmw-promo-box-hover');
    }, function() {
      $('#gmw_dialog_intro .gmw-promo-box').removeClass('gmw-promo-box-hover');
      $('#gmw_dialog_intro .gmw-promo-box:first').addClass('gmw-promo-box-hover');
    }
  );// on hover for pricing table
  
  
  // already have a key button click in dialog
  $('a.gmw_goto_activation').on('click', function(e) {
    $('.gmw_promo_dialog_screen').hide();
    $('#gmw_dialog_activate').show();
    
    if ($(this).data('noprevent')) {
      return true;
    } else {
      e.stopPropagation();
      e.preventDefault();
      return false;  
    }
  }); // already have a key click
  
  
  // already have a key button click in dialog
  $('div.gmw_goto_activation').on('click', function(e) {
    $('.gmw_promo_dialog_screen').hide();
    $('#gmw_dialog_activate').show();
    
    url = $(this).find('a').attr('href');
    win = window.open(url, '_blank');
    win.focus();
      
    return false;
  }); // already have a key click
  
  
  // go to intro button in dialog
  $('.gmw_goto_intro').on('click', function(e) {
    e.preventDefault();
    
    $('.gmw_promo_dialog_screen').hide();
    $('#gmw_dialog_intro').show();

    return false;
  }); // go to intro click
  
  
  // go to PRO features button in dialog
  $('.gmw_goto_pro').on('click', function(e) {
    e.preventDefault();
    
    $('.gmw_promo_dialog_screen').hide();
    $('#gmw_dialog_pro_features').show();

    return false;
  }); // go to PRO features click
  
  
  // go to trial button in dialog
  $('.gmw_goto_trial').on('click', function(e) {
    e.preventDefault();
    
    $('.gmw_promo_dialog_screen').hide();
    $('#gmw_dialog_trial').show();

    return false;
  }); // go to trial click
  
  
  // enter is pressed in license key field
  $('#gmw_code').on('keypress', function(e) {
    if (e.which === 13) {
      e.preventDefault();
      $('#gmw_activate').trigger('click');  
      return false;
    }
  }); // enter press
  
  
  // enter is pressed in license key field in settings
  $('#activation_code').on('keypress', function(e) {
    if (e.which === 13 || e.which === 10) {
      e.preventDefault();
      $('#submit-license').trigger('click');  
      return false;
    }
  }); // enter press
  
  
  // check code and activate button in dialog
  $('#gmw_activate').on('click', function(e) {
    e.preventDefault();

    $('#gmw_dialog_activate input.error').removeClass('error');
    $('#gmw_dialog_activate span.error').hide();
    $('#gmw_dialog_activate input').addClass('gmw_spinner').addClass('gmw_disabled');
    $('#gmw_activate').addClass('gmw_disabled');

    $.post(ajaxurl, { 'action': 'gmw_activate', 'code': $('#gmw_code').val(), '_ajax_nonce': gmw.nonce_activate_license_key},
      function(response) {
        if (typeof response != 'object') {
          alert(gmw.undocumented_error);
        } else if (response.success === true) {
          alert(gmw.activate_ok);
          tmp = window.location.pathname + window.location.search;
          tmp = tmp.replace('gmw_open_promo_dialog', '');
          window.location = tmp;
        } else {
          $('#gmw_dialog_activate input').addClass('error');
          $('#gmw_dialog_activate span.error.gmw_code').html(response.data).show();
          $('#gmw_code').focus().select();
        }
      }, 'json')
    .fail(function() {
      alert(gmw.undocumented_error);
    })
    .always(function() {
      $('#gmw_dialog_activate input').removeClass('gmw_spinner').removeClass('gmw_disabled');
      $('#gmw_activate').removeClass('gmw_disabled');
    });
    
    return false;
  }); // activate button click
  
  
  // get trial click
  $('#gmw_start_trial').on('click', function(e) {
    e.preventDefault();

    err = false;
    $('#gmw_dialog_trial input.error').removeClass('error');
    $('#gmw_dialog_trial span.error').hide();
    $('#gmw_dialog_trial input').addClass('gmw_disabled').addClass('gmw_spinner');
    $('#gmw_start_trial').addClass('gmw_disabled');

    if ($('#gmw_name').val().length < 3) {
      $('#gmw_name').addClass('error');
      $('#gmw_dialog_trial span.error.name').show();
      $('#gmw_name').focus().select();

      err = true;
    } // check name

    re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (!re.test($('#gmw_email').val())) {
      $('#gmw_email').addClass('error');
      $('#gmw_dialog_trial span.error.email').show();
      $('#gmw_email').focus().select();
      return false;
    }

    if (err) {
      return false;
    }

    $.post(ajaxurl, { 'action': 'gmw_get_trial',
                      'name': $('#gmw_name').val(),
                      'email': $('#gmw_email').val(),
                      '_ajax_nonce': gmw.nonce_get_trial},
      function(response) {
        if (response && response.success == true) {
          alert(gmw.trial_ok);
          document.location = gmw.settings_url;
        } else if (response && response.success == false && response.data) {
          alert(response.data);
        } else {
          alert(gmw.undocumented_error);
        }
      }, 'json')
    .fail(function() {
      alert(gmw.undocumented_error);
    })
    .always(function() {
      $('#gmw_dialog_trial input').removeClass('gmw_disabled').removeClass('gmw_spinner');
      $('#gmw_start_trial').removeClass('gmw_disabled');
    });

    return false;
  }); // get trial click

  
  // open promo/activation dialog
  function gmw_open_promo_dialog() {
    $('.gmw_promo_dialog_screen').hide();
    $('#gmw_dialog_intro').show();

    $('#gmw_promo_dialog').dialog({
        'dialogClass' : 'wp-dialog gmw-dialog',
        'modal' : true,
        'resizable': false,
        'width': 650,
        'title': gmw.plugin_name,
        'autoOpen': false,
        'closeOnEscape': false,
        open: function(event, ui) { 
          $(this).siblings().find('span.ui-dialog-title').html(gmw.dialog_promo_title);
          $('.ui-widget-overlay').bind('click', function () { $(this).siblings('.ui-dialog').find('.ui-dialog-content').dialog('close'); });
          $('.gmw_goto_pro').blur();
        },
        close: function(event, ui) { }
    }).dialog('open');
  } // open_promo_dialog
  
  
  // open pin picker library dialog
  function gmw_open_pins_library_dialog(button) {
    $('#gmw_pins_dialog').dialog({
        'dialogClass' : 'wp-dialog gmw-map-dialog',
        'modal' : true,
        'resizable': true,
        'width': Math.min(1100, $(window).width() * 0.75),
        'height': 585,
        'title': gmw.dialog_pins_title,
        'autoOpen': false,
        'closeOnEscape': true,
        open: function(event, ui) {
          $('.ui-widget-overlay').bind('click', function () { $(this).siblings('.ui-dialog').find('.ui-dialog-content').dialog('close'); });
          $('#pins_container').height($('#gmw_pins_dialog').dialog('option', 'height') - 185);
          $('#pins_container img').each(function(ind, el) {
            if (!$(el).attr('src')) {
              tmp = gmw.pins_library + $(el).parent('a').data('filename');
              $(el).attr('src', tmp);
            }
          });
        },
        close: function(event, ui) {  },
        resizeStop: function(event, ui) {
          $('#gmw_pins_dialog').dialog('option', 'position', {my: 'center', at: 'center', of: window});
          $('#pins_container').height($('#gmw_pins_dialog').dialog('option', 'height') - 185);
        }
    }).dialog('open');
    
    if (button) {
      $('#gmw_pins_dialog').data('widget-id', $(button).parents('div.widget').attr('id'));
    }
  } // open_pins_library_dialog
  

  // recenter dialogs when window resizes  
  $(window).resize(function(e) {
    if ($('.ui-dialog #gmw_promo_dialog').is(':visible')) {
      $('#gmw_promo_dialog').dialog('option', 'position', {my: 'center', at: 'center', of: window});
    }
    if ($('.ui-dialog #gmw_map_dialog').is(':visible')) {
      $('#gmw_map_dialog').dialog('option', 'position', {my: 'center', at: 'center', of: window});
    }
    if ($('.ui-dialog #gmw_pins_dialog').is(':visible')) {
      $('#gmw_pins_dialog').dialog('option', 'position', {my: 'center', at: 'center', of: window});
    }
    
    return true;
  }); // recenter dialogs

  
  // open address picking map dialog
  function gmw_open_map_dialog(widget, target) {
    $('#gmw_map_dialog').dialog({
        'dialogClass' : 'wp-dialog gmw-map-dialog',
        'modal' : true,
        'width': 880,
        'minWidth': 500,
        'minHeight': 500,
        'resizable': true,
        'title': gmw.dialog_map_title,
        'autoOpen': false,
        'closeOnEscape': true,
        open: function(event, ui) {
          $('.ui-widget-overlay').bind('click', function () { $(this).siblings('.ui-dialog').find('.ui-dialog-content').dialog('close'); });
          gmw_init_map($('input[id$="-' + target + '"]', widget).val());
          $('#gmw_map_dialog').data('widget-id', $(widget).attr('id'));
          $('#gmw_map_dialog').data('target', target);
        },
        resizeStop: function(event, ui) {
          $('#gmw_map_dialog').dialog('option', 'position', {my: 'center', at: 'center', of: window});
          $('#gmw_map_canvas').height($('#gmw_map_dialog').dialog('option', 'height') - $('#gmw_map_dialog_footer').height() - 120);
          google.maps.event.trigger(gmw.map, 'resize');
        },
        close: function(event, ui) {}
    }).dialog('open');
  } // open_map_dialog
  
  
  // filter pins
  // bind and run
  var last_search = '', last_icon_set = '';
  $('#pins_search').val($.cookie('gmw_pins_search'));
  $('#pins_set').val($.cookie('gmw_pins_set'));
  $('#pins_search').on('change mouseup keyup focus blur search', function(e) {
    search = $(this).val();
    icon_set = $('#pins_set').val();

    if (search == last_search && icon_set == last_icon_set) {
      return false;
    }
    
    last_search = search;
    last_icon_set = icon_set;
    
    if (!search && !icon_set) {
      $.cookie('gmw_pins_search', search, { expires: 90 });
      $.cookie('gmw_pins_set', icon_set, { expires: 90 });
      $('#pins_container a').show();
    } else {
      $.cookie('gmw_pins_search', search, { expires: 90 });
      $.cookie('gmw_pins_set', icon_set, { expires: 90 });
      reg_exp = new RegExp(search, 'i');
      reg_exp2 = new RegExp(icon_set, 'i');

      $('#pins_container a:not(.skip-search)').each(function(ind, el) {
        name = $('span', el).text();
        filename = $(el).data('filename');
        if (name.search(reg_exp) != -1 && filename.search(reg_exp2) != -1) {
          $(el).show();
        } else {
          $(el).hide();
        }
      });
    }
  }).trigger('search');
  // filter pins
  
  // trigger search on pin set change
  $('#pins_set').on('change focus blur', function(e) {
    $('#pins_search').trigger('search');
  }); // trigger search

  
  // select pin from dialog
  $('#pins_container a:not(.skip-search)').on('click', function(e) {
    e.preventDefault();
    widget_id = $('#gmw_pins_dialog').data('widget-id');

    $('#' + widget_id + ' .thumb_pin_img_library').val($(this).data('filename'));
    $('#' + widget_id + ' .thumb_pin_img_library_preview').attr('src', gmw.pins_library + $(this).data('filename'));
    
    $('#gmw_pins_dialog').dialog('close');
    
    return false;
  }); // select pin from dialog
  
  function gmw_init_map(address) {
    if (!address) {
      address = 'New York, USA';
    }
    gmw_put_pin(address);
  } // gmw_init_map

  
  function gmw_put_pin(address) {
    gmw.geocoder.geocode({'address': address}, function(results, status) {
      if (status === google.maps.GeocoderStatus.OK) {
        point = results[0].geometry.location;
        $('#gmw_map_pin_coordinates').val(results[0].geometry.location.lat().toFixed(5) + ', ' + results[0].geometry.location.lng().toFixed(5));
        $('#gmw_map_pin_address').val(results[0].formatted_address);
        gmw.map = new google.maps.Map(document.getElementById('gmw_map_canvas'), {
          zoom: 15,
          center: point,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        gmw.marker = new google.maps.Marker({
          position: point,
          title: 'Drag and drop pin to change the address',
          map: gmw.map,
          draggable: true
        });
        google.maps.event.addListener(gmw.marker, 'dragend', function(e) {
          $('#gmw_map_pin_coordinates').val(e.latLng.lat().toFixed(5) + ', ' + e.latLng.lng().toFixed(5));
          gmw_update_address_by_pos(gmw.marker.getPosition());
        });
        google.maps.event.addListener(gmw.marker, 'drag', function(e) {
          $('#gmw_map_pin_coordinates').val(e.latLng.lat().toFixed(5) + ', ' + e.latLng.lng().toFixed(5));
          $('#gmw_map_pin_address').val('Searching for the closest address ...');
        });
      } else {
        alert('Geocoder was unable to process the address; ' + status);
      }
    });
  } // gmw_put_pin

  
  // get address from coordinates
  function gmw_update_address_by_pos(point) {
    $('#gmw_map_dialog_address').val('Processing coordinates ...');
    gmw.geocoder.geocode({
      latLng: point
    }, function(responses) {
      if (responses && responses.length > 0) {
        $('#gmw_map_pin_address').val(responses[0].formatted_address);
      } else {
        $('#gmw_map_pin_address').val('Can\'t determine address at this location.');
      }
    });
  } // gmw_update_address_by_pos

  
  // move pin in dialog based on entered coordinates or address
  $('.gmw-move-pin').on('click', function(e) {
    e.preventDefault();

    field = $(this).data('location-holder');
    gmw_put_pin($('#' + field).val());

    return false;
  }); // move pin in dialog

  
  // just close the map dialog
  $('#gmw_close_map_dialog').on('click', function(e) {
    e.preventDefault();

    $('#gmw_map_dialog').dialog('close');

    return false;
  }); // close dialog


  // close map dialog and transfer address or coordinates
  $('.gmw_close_save_map_dialog').on('click', function(e) {
    e.preventDefault();

    field = $(this).data('location-holder');
    field_val = $('#' + field).val();

    widget_id = $('#gmw_map_dialog').data('widget-id');
    target = $('#gmw_map_dialog').data('target');
    $('#' + widget_id + ' input[id$="-' + target + '"]').val(field_val);

    $('#gmw_map_dialog').dialog('close');
    
    return false;
  }); // move pin in dialog
  
  
  // test API key
  $('.gmw-test-api-key').on('click', function(e) {
    e.preventDefault();
    var button = this;
    
    api_key = $('#api_key').val();
    if (api_key.length < 30) {
      alert(gmw.bad_api_key);
      return false;
    }
    
    $(button).addClass('gmw_spinner').addClass('gmw_disabled');
    
    $.get(ajaxurl, {'action': 'gmw_test_api_key', 'api_key': api_key, '_ajax_nonce': gmw.nonce_test_api_key},
          function(response) {
            if (typeof response == 'object') {
              alert(response.data);  
            } else {
              alert(gmw.undocumented_error);
            }
          }, 'json'
    ).fail(function(response) {
      alert(gmw.undocumented_error);
    }).always(function(response) {
      $(button).removeClass('gmw_spinner').removeClass('gmw_disabled');
    });
    
    return false;
  }); // test api key
  
  
  // clone and save new instance of GMW
  function gmw_clone(ev, org_widget) {
      var $original = $(org_widget).parents('.widget');
      var $widget = $original.clone();
      var idbase = $widget.find('input[name="id_base"]').val();
      var number = $widget.find('input[name="widget_number"]').val();
      var mnumber = $widget.find('input[name="multi_number"]').val();
      var highest = 0;

      if (mnumber != '') {
        number = mnumber;
      }

      $('input.widget-id[value|="' + idbase + '"]').each(function() {
        var match = this.value.match(/-(\d+)$/);
        if (match && parseInt(match[1]) > highest) {
          highest = parseInt(match[1]);
        }
      });

      var newnum = highest + 1;

      $widget.find('.widget-content').find('input, select, textarea, label').each(function() {
        if ($(this).attr('name')) {
          $(this).attr('name', $(this).attr('name').replace(number, newnum));
        }
        if ($(this).attr('id')) {
          $(this).attr('id', $(this).attr('id').replace(number, newnum));
        }
        if ($(this).attr('for')) {
          $(this).attr('for', $(this).attr('for').replace(number, newnum));
        }
      });

      var match = $widget[0].id.match(/^widget-(\d+)/i);
      if (match && parseInt(match[1])) {
        newid = parseInt(match[1]);
      } 

      $widget.find('input.add_new').val('multi');
      $widget[0].id = 'widget-' + newid + '_' + idbase + '-' + newnum;
      $widget.find('input.widget-id').val(idbase + '-' + newnum);
      $widget.find('input.widget_number').val(newnum);
      $widget.hide();
      $original.after($widget);
      $widget.fadeIn();

      $widget.find('.multi_number').val(newnum);

      $(document).ajaxSuccess(function(event, xhr, settings) {
        if (xhr.responseText == '' && xhr.status == 200 && settings.data.search('&action=save-widget&')) {
          wpWidgets.save($widget, 0, 0, 1);
          $(document).unbind('ajaxSuccess');
        }
      });

      wpWidgets.save($widget, 0, 0, 1);

      $widget.find('input.multi_number').val(''); 
      $widget.find('input.add_new').val('');

      ev.stopPropagation();
      ev.preventDefault();
  } // clone widget
}); // onload