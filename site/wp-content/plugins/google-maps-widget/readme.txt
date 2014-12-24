=== Google Maps Widget ===
Contributors: WebFactory
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=gordan%40webfactoryltd%2ecom&lc=US&item_name=Google%20Maps%20Widget&no_note=0&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHostedGuest
Tags: google maps, maps, gmaps, widget, lightbox, map, google map, fancybox, fancybox2, multilingual, sidebar, chinese
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 3.3
Tested up to: 3.9
Stable tag: 1.75

Displays a single-image super-fast loading Google map in a widget. A larger map with all the usual features is available on click in a lightbox.

== Description ==

Check out some examples on the <a href="http://www.googlemapswidget.com/">official plugin site</a>, view the [Google Maps Widget video](http://www.youtube.com/watch?v=y1siX9ha7Pw) or give us a shout <a href="http://twitter.com/WebFactoryLtd">@WebFactoryLtd</a>.

http://www.youtube.com/watch?v=y1siX9ha7Pw&hd=1

**General widget options**

* title
* address

**Thumbnail map options**

* map size - width & height
* map type - road, satellite, map or hybrid
* pin color
* pin size
* zoom level
* link type - lightbox, custom URL or disable link
* map color scheme - new & old
* text above map
* text below map

**Lightbox map options**

* map size - width & height
* map type - road, satellite, map or hybrid
* zoom level
* skin - light & dark
* show/hide address bubble
* show/hide map title
* header text
* footer text

> If you need a Google Maps shortcode plugin you might be interested in purchasing our premium <a title="5sec Google Maps" href="http://5sec-google-maps-pro.webfactoryltd.com/">5sec Google Maps PRO</a> plugin.

The plugin was voted on the <a href="http://themesplugins.com/Plugin-detail/google-maps-widget-google-map-free-plugin-for-wordpress/" title="Top 100 WordPressian plugin">Top 100 List</a> by WordPressian and made it on the <a href="http://tidyrepo.com/google-maps-widget/">Tidy Repo</a> list.

**Translators (thank you!)**

* English - original :)
* Swedish - Sofia Asklund
* Spanish - Jesus Garica
* Croatian - Gordan
* German - Karimba
* French - Karimba
* Chinese simplified  - Wyeoh
* Chinese traditional - Wyeoh
* Dutch - Arno
* Ukrainian - Victor Shutovskiy

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

For just about anyone who needs a map on their site.

= Thumbnail map works but lightbox won't open on click =

You most probably have Fancybox JS and CSS files included twice; once by the theme and second time by GMW plugin. Remove one instance of files. If that's not the case then you have a fatal JS error thats preventing execution of other JS code. Hit F12 in Firefox or Chrome and have a look at the debug console. If there are any red lines - it's a fatal error. Open a new thread in the support forums but please bear in mind that support is community based and we do this in our spare time.

= It's not working!!! Arrrrrrrrr =

A more detailed help is coming soon. Till then check 2 things: does your theme have wp_footer() function call in the footer and if there are any jQuery errors on the site.
If you can figure it out open a thread in the support forums.

== Screenshots ==

1. Small map is shown as a widget and since it's just one image it loads super-fast
2. Larger map with all features is available in the lightbox
3. Widget options - thumbnail map
4. Widget options - lightbox map

== Changelog ==
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


== Upgrade Notice ==

= 0.2 =
Due to variable name changes footer text will be reset

= 0.1x =
Upgrade without any fear :)

= 0.1 =
Initial release