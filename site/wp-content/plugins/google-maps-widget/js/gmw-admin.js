/*
 * Google Maps Widget
 * (c) Web factory Ltd, 2012 - 2013
 */

jQuery(function($) {
  $('.gmw-tabs').each(function(i, el) {
    change_link_type(el);
    $('.gmw_thumb_link_type', el).on('change', function() { change_link_type(el) });
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

  // re-tab on GUI rebuild
  $('div[id*="googlemapswidget"]').ajaxSuccess(function(event, request, option) {
    $('.gmw-tabs').each(function(i, el) {
      change_link_type(el);
      $('.gmw_thumb_link_type', el).on('change', function() { change_link_type(el) });
      el_id = $(el).attr('id');
      $(el).tabs({ active: get_active_tab(el_id),
                   activate: function(event, ui) { save_active_tab(this); }
      });
    });
  });
}); // onload