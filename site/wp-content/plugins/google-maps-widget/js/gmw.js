/*
 * Google Maps Widget
 * (c) Web factory Ltd, 2012 - 2015
 */

jQuery(function($) {
    $('a.gmw-thumbnail-map.gmw-lightbox-enabled').click(function(e) {
      e.preventDefault();

      dialog = $($(this).attr('href'));
      map_width = dialog.attr('data-map-width');
      map_height = dialog.attr('data-map-height');
      map_url = dialog.attr('data-map-iframe-url');
      map_title = dialog.attr('title');
      map_skin = dialog.attr('data-map-skin');

      // adjust map size if screen is too small
      screen_width = $(window).width() - 50;
      if (screen_width < map_width) {
        map_width = screen_width;
        map_height *= screen_width / map_width;
      }
      screen_height = $(window).height() - 50;
      if (screen_height < map_height) {
        map_height = screen_height;
        map_width *= screen_height / map_height;
      }
      
      content = $(dialog.html());
      content.filter('.gmw-map').html('<iframe width="' + map_width + 'px" height="' + map_height + 'px" src="' + map_url + '"></iframe>');

      $.colorbox({ html: content,
                   title: map_title,
                   className: 'gmw-' + map_skin,
                   closeButton: false });

      return false;
    });
}); // onload