=== Google Maps Widget ===
Contributors: WebFactory
Tags: address, best google maps, best maps, chinese, cross-browser, custom google map, custom google maps, custom marker, custom marker icons, directions, easy map, fancybox, fancybox2, fast, fully documented, gecode, geo, gmaps, gmw, google map, google map plugin, google map shortcode, google map widget, google maps, google maps api, google maps builder, google maps directions, google maps plugin, google maps v3, google maps widget, googlemaps, latitude, lightbox, location, location by address, location by latitude/longitude, longitude, map, map directions, map marker, map markers, map plugin, map shortcode, map styles, map widget, maps, marker, marker icons, multilingual, pin, place, placemarker, post map, posts, reverse geocode, shortcode, sidebar, streetview, traffic/bike lanes, widget, widget map, wp google map, wp google maps, wp map, wp maps
Donate link: https://www.paypal.com/cgi-bin/webscr?business=gordan@webfactoryltd.com&cmd=_xclick&currency_code=USD&amount=&item_name=Google%20Maps%20Widget%20Donation
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 3.3
Tested up to: 4.2
Stable tag: 2.45

Displays a single-image super-fast loading Google map in a widget. A larger map with all the usual features is available on click in a lightbox.

== Description ==

Check out some examples on the <a href="http://www.googlemapswidget.com/">official plugin site</a>, view the [Google Maps Widget video](http://www.youtube.com/watch?v=y1siX9ha7Pw) or give us a shout on Twitter <a href="http://twitter.com/WebFactoryLtd">@WebFactoryLtd</a>.

http://www.youtube.com/watch?v=y1siX9ha7Pw&hd=1

**General widget options**

* title
* address

**Thumbnail map options**

* map size - width & height
* map type - road, satellite, map or hybrid
* pin color
* pin size
* custom pin icon - any image can be used
* zoom level
* link type - lightbox, custom URL or disable link
* map color scheme - default, refreshed, apple, gray, paper
* text above map
* text below map

**Lightbox map options**

* map size - width & height (auto-adjusted on smaller screens)
* map type - road, satellite, map or hybrid
* zoom level
* skin - light, dark, blue, rounded
* show/hide address bubble
* show/hide map title
* header text
* footer text

**Shortcode**

* by using the _[gmw id="#"]_ shortcode you can display the map in any page, post, or custom post type


> If you need a Google Maps shortcode plugin you might be interested in purchasing our premium <a title="5sec Google Maps" href="http://5sec-google-maps-pro.webfactoryltd.com/">5sec Google Maps PRO</a> plugin.


**What others say about the plugin**

* voted on the <a href="http://themesplugins.com/Plugin-detail/google-maps-widget-google-map-free-plugin-for-wordpress/" title="Top 100 WordPressian plugin">Top 100 List</a> by WordPressian
* made it on the <a href="http://tidyrepo.com/google-maps-widget/">Tidy Repo</a> list
* reviewed 5/5 by <a href="http://websmush.com/google-maps-widget-plugin-review/">Web Smush</a>
* one of 3 best map plugins by <a href="http://torquemag.io/the-3-best-map-plugins-for-wordpress/">The Torque Mag</a>
* "an easier way to add Google maps to your site" says <a href="http://www.wpbeginner.com/wp-tutorials/how-to-add-google-maps-in-wordpress/">WP Beginner</a>
* <a href="http://designscrazed.org/wordpress-google-map-plugins/">Design Crazed</a> puts in on the top 20 Google maps list
* <a href="http://www.inkthemes.com/easily-integrate-google-map-in-your-wordpress-themes-widget-area/09/">InkThemes</a> shows how easy it is to use GMW
* <a href="http://www.indexwp.com/google-maps-widget/">IndexWP</a> calls it a "handy map plugin"


**Translators (thank you!)**

* English - original :)
* Swedish - Sofia Asklund
* Spanish - Jesus Garica
* Croatian - Gordan from <a href="http://www.webfactoryltd.com/">Web factory Ltd</a>
* German - Karimba
* French - Karimba
* Chinese simplified  - Wyeoh
* Chinese traditional - Wyeoh
* Dutch - Arno
* Ukrainian - Victor Shutovskiy
* Serbian - Ogi Djuraskovic from <a href="http://firstsiteguide.com/">FirstSiteGuide</a>
* Russian - Ivanka from <a href="http://www.coupofy.com/">Coupofy</a>

== Installation ==

Follow the usual routine;

1. Open WP admin - Plugins - Add New
2. Enter "Google Maps Widget" under search and hit Enter
3. Plugin will show up as the first on the list, click "Install Now"

Or if needed, upload manually;

1. Download the plugin.
2. Unzip it and upload to wp-content/plugin/
3. Open WP admin - Plugins and click "Activate" next to the plugin
4. Configure the plugin under Appearance - Widgets


== Frequently Asked Questions ==

= Who is this plugin for? =

For just about anyone who needs a map on their site in 5 seconds.

= Thumbnail map works but lightbox won't open on click =

You most probably have Fancybox JS and CSS files included twice; once by the theme and second time by GMW plugin. Remove one instance of files. If that's not the case then you have a fatal JS error thats preventing execution of other JS code. Hit F12 in Firefox or Chrome and have a look at the debug console. If there are any red lines - it's a fatal error. Open a new thread in the support forums but please bear in mind that support is community based and we do this in our spare time.

= It's not working!!! Arrrrrrrrr =

A more detailed help is coming soon. Till then check 2 things: does your theme have _wp_footer()_ function call in the footer and if there are any jQuery errors on the site.
If you can't figure it out open a thread in the support forums.

== Screenshots ==

1. Small map is shown as a widget and since it's just one image it loads super-fast
2. Larger map with all features is available in the lightbox
3. Widget options - thumbnail map
4. Widget options - lightbox map
5. Widget options - shortcode
6. Widget options - info & support

== Changelog ==
= 2.45 =
* 2015/06/15
* fixed a bug on notice dismiss action
* added Russian translation - thanks Ivanka!

= 2.40 =
* 2015/05/25
* few small bugs fixed
* admin JS completely rebuilt
* fixed PO file
* we broke 90,000 installations ;)

= 2.35 =
* 2015/04/27
* few small bugs fixed
* WP v4.2 compatibility checked
* remove_query_arg() security issue fixed
* we broke 500,000 downloads ;)

= 2.30 =
* 2015/03/02
* JS rewrites
* few small bugs fixed

= 2.25 =
* 2015/02/23
* a few visual enhancements
* new screenshots
* shortcode name availability is checked before registering it
* visual builder compatibility fix

= 2.20 =
* 2015/02/16
* added shortcode support

= 2.15 =
* 2015/02/09
* fixed a _plugin_deactivate_ bug nobody noticed for 2 years :(
* all JS texts are now loaded via wp_localize_script()

= 2.10 =
* 2015/02/02
* auto-adjust map size on smaller screens - thanks bruzm!
* marked each widget with core version for future updates

= 2.06 =
* 2015/01/26
* language file updated
* preparing for JS rewrite

= 2.05 =
* 2015/01/19
* code rewriting
* minor bug fixes

= 2.01 =
* 2015/01/13
* somehow one JS file got renamed :(

= 2.0 =
* 2015/01/13
* lots of rewrites
* additional features can now be activated by subscribing to our newsletter

= 1.95 =
* 2014/12/19
* minor WP v4.1 updates

= 1.93 =
* 2014/12/03
* due to someone being a huge cun* we can no longer offer discounts for our Envato products in GMW
* so no changes to the plugin, just some messages edited

= 1.92 =
* 2014/11/12
* minor bug fixes
* preparations for admin JS rewrite

= 1.90 =
* 2014/10/20
* added Serbian translation; thanks Ogi!

= 1.86 =
* 2014/10/12
* updated POT file
* updated Croatian translation

= 1.85 =
* 2014/09/22
* added custom pin image option for thumbnail map - thanks Rudloff!

= 1.80 =
* 2014/09/08
* minor updates for WordPress v4.0

= 1.75 =
* 2014/07/29
* lightbox skins are back; light and dark for now, more coming soon
* updated lightbox jS

= 1.70 =
* 2014/07/10
* fixed a small bug on thumbnail
* finished up a todo

= 1.65 =
* 2014/05/06
* finished up a few todos

= 1.60 =
* 2014/04/17
* update for WordPress v3.9, widget edit GUI now works in theme customizer
* if you run into any issues please report them in the support forums

= 1.55 =
* 2014/04/07
* fixed shortcode handling in map's header & footer
* added Ukrainian translation - thank you Victor Shutovskiy!

= 1.50 =
* 2014/03/25
* minor bug fixes
* new Spanish translation - thank you Jesus!
* still working on those lightbox skins, sorry :(

= 1.47 =
* 2014/03/05
* minor bug fix
* working on those lightbox skins :)

= 1.45 =
* 2014/03/04
* switched to <a href="http://www.jacklmoore.com/colorbox/">Colorbox</a> lightbox script
* lightbox skin is still temporarily unavailable

= 1.40 =
* 2014/02/10
* due to licensing issues switched to FancyBox v1.3.4
* lightbox skin is temporarily unavailable
* minor bug fix related to activate/upgrade hook calls

= 1.35 =
* 2014/02/05
* added optional plugin usage tracking (<a href="http://www.googlemapswidget.com/plugin-tracking-info/">detailed info</a>)

= 1.31 =
* 2014/02/03
* WP v3.8.1 compatibility check

= 1.30 =
* 2014/01/16
* added Dutch translation; thank you Arno!

= 1.25 =
* 2014/01/03
* preparations for opt-in plugin usage tracking
* Spanish translation updated; thanks Jesus!

= 1.20 =
* 2013/12/17
* WP v3.8 update
* language files update

= 1.15 =
* 2013/11/25
* added option for thumbnail map to link to a custom URL which disables the lightbox; you can link to a lightbox, a custom link or remove the link all together

= 1.10 =
* 2013/11/18
* added option for thumbnail map to use the new look/color scheme

= 1.05 =
* 2013/11/04
* added Chinese traditional translation; thanks Wyeoh

= 1.0 =
* 2013/10/28
* WP 3.7 compatibility check
* added Chinese simplified translation; thanks Wyeoh

= 0.95 =
* 2013/10/21
* added French translation; thanks Karimba

= 0.90 =
* 2013/10/14
* added German translation; thanks Karimba
* we reached 100k downloads ;)

= 0.86 =
* 2013/10/07
* fixed a few strict standards errors; thanks Jay!

= 0.85 =
* 2013/10/03
* added Croatian translation; thank you Gordan

= 0.80 =
* 2013/09/28
* minor translation fixes
* added Spanish translation; thank you Jesus!

= 0.75 =
* 2013/09/24
* map language is autodetected based on user's browser language (HTTP_ACCEPT_LANGUAGE header)
* added Swedish translation; thank you Sofia!
* German and Croatian translations will be up next

= 0.71 =
* 2013/09/17
* few more preparations for translation
* Swedish translation coming in a few days

= 0.70 =
* 2013/09/05
* prepared everything for translation, POT file is available and all strings are wrapped in <i>__()</i>
* protocols should now match http/https for both thumbnail and ligtbox map
* <a href="http://www.googlemapswidget.com/">www.googlemapswidget.com</a> is up and running

= 0.65 =
* 2013/08/05
* updated JS for WP v3.6

= 0.60 =
* 2013/04/06
* fixed zoom bug in lightbox

= 0.55 =
* 2013/04/05
* added 2 new options - text above and below thumbnail map
* updated fancyBox JS to the latest version
* minor code improvements

= 0.50 =
* 2012/12/12
* small WP 3.5 compatibility fixes

= 0.41 =
* 2012/12/03
* removed screenshots from plugin package

= 0.4 =
* 2012/11/28
* fixed non UTF-8 address bug

= 0.37 =
* 2012/11/19
* fixed bug to use google.com instead of google.co.uk

= 0.35 =
* 2012/09/28
* added 4 skins for lightbox

= 0.31 =
* 2012/09/14
* fix for bad themes which don't respect proper sidebar markup

= 0.3 =
* 2012/09/04
* lightbox script changed from jQuery UI Dialog to <a href="http://fancyapps.com/fancybox/">fancyBox2</a>
* added "show map title on lightbox" option
* significant speed improvements
* preparations for lightbox skins

= 0.22 =
* 2012/08/31
* Fixed small JS related GUI bug

= 0.2 =
* 2012/08/28
* Complete GUI rewrite
* Added header text option
* Added address bubble visibility option
* Fixed thumbnail map scaling bug
* Fixed lightbox map size bug

= 0.13 =
* 2012/08/09
* Added pin size for thumbnail map

= 0.12 =
* 2012/08/07
* Added pin color for thumbnail map
* Fixed a few minor bugs

= 0.11 =
* 2012/08/06
* Fixed a few minor bugs

= 0.1 =
* 2012/08/03
* Initial release