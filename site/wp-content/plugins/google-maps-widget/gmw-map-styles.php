<?php
/*
 * Google Maps Widget
 * (c) Web factory Ltd, 2013 - 2016
 */


// this is an include only WP file
if (!defined('ABSPATH')) {
  die;
}


class GMW_styles {
  static $js_styles = array(
    'pale' => '[{"featureType":"water","stylers":[{"visibility":"on"},{"color":"#acbcc9"}]},{"featureType":"landscape","stylers":[{"color":"#f2e5d4"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#c5c6c6"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#e4d7c6"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#fbfaf7"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#c5dac6"}]},{"featureType":"administrative","stylers":[{"visibility":"on"},{"lightness":33}]},{"featureType":"road"},{"featureType":"poi.park","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":20}]},{},{"featureType":"road","stylers":[{"lightness":20}]}]',
    'blue' => '[{"featureType":"water","stylers":[{"color":"#46bcec"},{"visibility":"on"}]},{"featureType":"landscape","stylers":[{"color":"#f2f2f2"}]},{"featureType":"road","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"transit","stylers":[{"visibility":"off"}]},{"featureType":"poi","stylers":[{"visibility":"off"}]}]',
    'light' => '[{"featureType":"water","elementType":"all","stylers":[{"hue":"#e9ebed"},{"saturation":-78},{"lightness":67},{"visibility":"simplified"}]},{"featureType":"landscape","elementType":"all","stylers":[{"hue":"#ffffff"},{"saturation":-100},{"lightness":100},{"visibility":"simplified"}]},{"featureType":"road","elementType":"geometry","stylers":[{"hue":"#bbc0c4"},{"saturation":-93},{"lightness":31},{"visibility":"simplified"}]},{"featureType":"poi","elementType":"all","stylers":[{"hue":"#ffffff"},{"saturation":-100},{"lightness":100},{"visibility":"off"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"hue":"#e9ebed"},{"saturation":-90},{"lightness":-8},{"visibility":"simplified"}]},{"featureType":"transit","elementType":"all","stylers":[{"hue":"#e9ebed"},{"saturation":10},{"lightness":69},{"visibility":"on"}]},{"featureType":"administrative.locality","elementType":"all","stylers":[{"hue":"#2c2e33"},{"saturation":7},{"lightness":19},{"visibility":"on"}]},{"featureType":"road","elementType":"labels","stylers":[{"hue":"#bbc0c4"},{"saturation":-93},{"lightness":31},{"visibility":"on"}]},{"featureType":"road.arterial","elementType":"labels","stylers":[{"hue":"#bbc0c4"},{"saturation":-93},{"lightness":-2},{"visibility":"simplified"}]}]',
    'bright' => '[{"featureType":"water","stylers":[{"color":"#19a0d8"}]},{"featureType":"administrative","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"},{"weight":6}]},{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#e85113"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#efe9e4"},{"lightness":-40}]},{"featureType":"road.arterial","elementType":"geometry.stroke","stylers":[{"color":"#efe9e4"},{"lightness":-20}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"lightness":100}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"lightness":-100}]},{"featureType":"road.highway","elementType":"labels.icon"},{"featureType":"landscape","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"landscape","stylers":[{"lightness":20},{"color":"#efe9e4"}]},{"featureType":"landscape.man_made","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"lightness":100}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"lightness":-100}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"hue":"#11ff00"}]},{"featureType":"poi","elementType":"labels.text.stroke","stylers":[{"lightness":100}]},{"featureType":"poi","elementType":"labels.icon","stylers":[{"hue":"#4cff00"},{"saturation":58}]},{"featureType":"poi","elementType":"geometry","stylers":[{"visibility":"on"},{"color":"#f0e4d3"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#efe9e4"},{"lightness":-25}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#efe9e4"},{"lightness":-10}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"simplified"}]}]',
    'apple' => '[{"featureType":"water","elementType":"geometry","stylers":[{"color":"#a2daf2"}]},{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"color":"#f7f1df"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#d0e3b4"}]},{"featureType":"landscape.natural.terrain","elementType":"geometry","stylers":[{"visibility":"off"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#bde6ab"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi.medical","elementType":"geometry","stylers":[{"color":"#fbd3da"}]},{"featureType":"poi.business","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffe15f"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#efd151"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"black"}]},{"featureType":"transit.station.airport","elementType":"geometry.fill","stylers":[{"color":"#cfb2db"}]}]',
    'gray' => '[{"featureType":"landscape","stylers":[{"saturation":-100},{"lightness":65},{"visibility":"on"}]},{"featureType":"poi","stylers":[{"saturation":-100},{"lightness":51},{"visibility":"simplified"}]},{"featureType":"road.highway","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"road.arterial","stylers":[{"saturation":-100},{"lightness":30},{"visibility":"on"}]},{"featureType":"road.local","stylers":[{"saturation":-100},{"lightness":40},{"visibility":"on"}]},{"featureType":"transit","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"administrative.province","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":-25},{"saturation":-100}]},{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#ffff00"},{"lightness":-25},{"saturation":-97}]}]',
    'gray2' => '[{"featureType":"all","stylers":[{"saturation":-100},{"gamma":0.5}]}]',
    'gowalla' => '[{"featureType":"road","elementType":"labels","stylers":[{"visibility":"simplified"},{"lightness":20}]},{"featureType":"administrative.land_parcel","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"landscape.man_made","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road.local","elementType":"labels","stylers":[{"visibility":"simplified"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"labels","stylers":[{"visibility":"simplified"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.arterial","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"hue":"#a1cdfc"},{"saturation":30},{"lightness":49}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"hue":"#f49935"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"hue":"#fad959"}]}]',
    'mapbox' => '[{"featureType":"water","stylers":[{"saturation":43},{"lightness":-11},{"hue":"#0088ff"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"hue":"#ff0000"},{"saturation":-100},{"lightness":99}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"color":"#808080"},{"lightness":54}]},{"featureType":"landscape.man_made","elementType":"geometry.fill","stylers":[{"color":"#ece2d9"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#ccdca1"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#767676"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"}]},{"featureType":"poi","stylers":[{"visibility":"off"}]},{"featureType":"landscape.natural","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#b8cb93"}]},{"featureType":"poi.park","stylers":[{"visibility":"on"}]},{"featureType":"poi.sports_complex","stylers":[{"visibility":"on"}]},{"featureType":"poi.medical","stylers":[{"visibility":"on"}]},{"featureType":"poi.business","stylers":[{"visibility":"simplified"}]}]',
    'peper' => '[{"featureType":"landscape","stylers":[{"hue":"#F1FF00"},{"saturation":-27.4},{"lightness":9.4},{"gamma":1}]},{"featureType":"road.highway","stylers":[{"hue":"#0099FF"},{"saturation":-20},{"lightness":36.4},{"gamma":1}]},{"featureType":"road.arterial","stylers":[{"hue":"#00FF4F"},{"saturation":0},{"lightness":0},{"gamma":1}]},{"featureType":"road.local","stylers":[{"hue":"#FFB300"},{"saturation":-38},{"lightness":11.2},{"gamma":1}]},{"featureType":"water","stylers":[{"hue":"#00B6FF"},{"saturation":4.2},{"lightness":-63.4},{"gamma":1}]},{"featureType":"poi","stylers":[{"hue":"#9FFF00"},{"saturation":0},{"lightness":0},{"gamma":1}]}]',
    'midnight' => '[{"featureType":"water","stylers":[{"color":"#021019"}]},{"featureType":"landscape","stylers":[{"color":"#08304b"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#0c4152"},{"lightness":5}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#000000"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#0b434f"},{"lightness":25}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#000000"}]},{"featureType":"road.arterial","elementType":"geometry.stroke","stylers":[{"color":"#0b3d51"},{"lightness":16}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#000000"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#ffffff"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#000000"},{"lightness":13}]},{"featureType":"transit","stylers":[{"color":"#146474"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#000000"}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#144b53"},{"lightness":14},{"weight":1.4}]}]');


  static $php_styles = array(
    'pale' => 'style=feature:water|element:all|visibility:on|color:0xacbcc9|&style=feature:landscape|element:all|color:0xf2e5d4|&style=feature:road.highway|element:geometry|color:0xc5c6c6|&style=feature:road.arterial|element:geometry|color:0xe4d7c6|&style=feature:road.local|element:geometry|color:0xfbfaf7|&style=feature:poi.park|element:geometry|color:0xc5dac6|&style=feature:administrative|element:all|visibility:on|lightness:33|&style=&style=feature:poi.park|element:labels|visibility:on|lightness:20|&style=&style=feature:road|element:all|lightness:20|',
    'blue' => 'style=feature:water|element:all|color:0x46bcec|visibility:on|&style=feature:landscape|element:all|color:0xf2f2f2|&style=feature:road|element:all|saturation:-100|lightness:45|&style=feature:road.highway|element:all|visibility:simplified|&style=feature:road.arterial|element:labels.icon|visibility:off|&style=feature:administrative|element:labels.text.fill|color:0x444444|&style=feature:transit|element:all|visibility:off|&style=feature:poi|element:all|visibility:off|',
    'light' => 'style=feature:water|element:all|hue:0xe9ebed|saturation:-78|lightness:67|visibility:simplified|&style=feature:landscape|element:all|hue:0xffffff|saturation:-100|lightness:100|visibility:simplified|&style=feature:road|element:geometry|hue:0xbbc0c4|saturation:-93|lightness:31|visibility:simplified|&style=feature:poi|element:all|hue:0xffffff|saturation:-100|lightness:100|visibility:off|&style=feature:road.local|element:geometry|hue:0xe9ebed|saturation:-90|lightness:-8|visibility:simplified|&style=feature:transit|element:all|hue:0xe9ebed|saturation:10|lightness:69|visibility:on|&style=feature:administrative.locality|element:all|hue:0x2c2e33|saturation:7|lightness:19|visibility:on|&style=feature:road|element:labels|hue:0xbbc0c4|saturation:-93|lightness:31|visibility:on|&style=feature:road.arterial|element:labels|hue:0xbbc0c4|saturation:-93|lightness:-2|visibility:simplified|',
    'bright' => 'style=feature:water|element:all|color:0x19a0d8|&style=feature:administrative|element:labels.text.stroke|color:0xffffff|weight:6|&style=feature:administrative|element:labels.text.fill|color:0xe85113|&style=feature:road.highway|element:geometry.stroke|color:0xefe9e4|lightness:-40|&style=feature:road.arterial|element:geometry.stroke|color:0xefe9e4|lightness:-20|&style=feature:road|element:labels.text.stroke|lightness:100|&style=feature:road|element:labels.text.fill|lightness:-100|&style=&style=feature:landscape|element:labels|visibility:off|&style=feature:landscape|element:all|lightness:20|color:0xefe9e4|&style=feature:landscape.man_made|element:all|visibility:off|&style=feature:water|element:labels.text.stroke|lightness:100|&style=feature:water|element:labels.text.fill|lightness:-100|&style=feature:poi|element:labels.text.fill|hue:0x11ff00|&style=feature:poi|element:labels.text.stroke|lightness:100|&style=feature:poi|element:labels.icon|hue:0x4cff00|saturation:58|&style=feature:poi|element:geometry|visibility:on|color:0xf0e4d3|&style=feature:road.highway|element:geometry.fill|color:0xefe9e4|lightness:-25|&style=feature:road.arterial|element:geometry.fill|color:0xefe9e4|lightness:-10|&style=feature:poi|element:labels|visibility:simplified|',
    'apple' => 'style=feature:water|element:geometry|color:0xa2daf2|&style=feature:landscape.man_made|element:geometry|color:0xf7f1df|&style=feature:landscape.natural|element:geometry|color:0xd0e3b4|&style=feature:landscape.natural.terrain|element:geometry|visibility:off|&style=feature:poi.park|element:geometry|color:0xbde6ab|&style=feature:poi|element:labels|visibility:off|&style=feature:poi.medical|element:geometry|color:0xfbd3da|&style=feature:poi.business|element:all|visibility:off|&style=feature:road|element:geometry.stroke|visibility:off|&style=feature:road|element:labels|visibility:off|&style=feature:road.highway|element:geometry.fill|color:0xffe15f|&style=feature:road.highway|element:geometry.stroke|color:0xefd151|&style=feature:road.arterial|element:geometry.fill|color:0xffffff|&style=feature:road.local|element:geometry.fill|color:black|&style=feature:transit.station.airport|element:geometry.fill|color:0xcfb2db|',
    'gray' => 'style=feature:landscape|element:all|saturation:-100|lightness:65|visibility:on|&style=feature:poi|element:all|saturation:-100|lightness:51|visibility:simplified|&style=feature:road.highway|element:all|saturation:-100|visibility:simplified|&style=feature:road.arterial|element:all|saturation:-100|lightness:30|visibility:on|&style=feature:road.local|element:all|saturation:-100|lightness:40|visibility:on|&style=feature:transit|element:all|saturation:-100|visibility:simplified|&style=feature:administrative.province|element:all|visibility:off|&style=feature:water|element:labels|visibility:on|lightness:-25|saturation:-100|&style=feature:water|element:geometry|hue:0xffff00|lightness:-25|saturation:-97|',
    'gray2' => 'style=feature:all|element:all|saturation:-100|gamma:0.5|',
    'gowalla' => 'style=feature:road|element:labels|visibility:simplified|lightness:20|&style=feature:administrative.land_parcel|element:all|visibility:off|&style=feature:landscape.man_made|element:all|visibility:off|&style=feature:transit|element:all|visibility:off|&style=feature:road.local|element:labels|visibility:simplified|&style=feature:road.local|element:geometry|visibility:simplified|&style=feature:road.highway|element:labels|visibility:simplified|&style=feature:poi|element:labels|visibility:off|&style=feature:road.arterial|element:labels|visibility:off|&style=feature:water|element:all|hue:0xa1cdfc|saturation:30|lightness:49|&style=feature:road.highway|element:geometry|hue:0xf49935|&style=feature:road.arterial|element:geometry|hue:0xfad959|',
    'mapbox' => 'style=feature:water|element:all|saturation:43|lightness:-11|hue:0x0088ff|&style=feature:road|element:geometry.fill|hue:0xff0000|saturation:-100|lightness:99|&style=feature:road|element:geometry.stroke|color:0x808080|lightness:54|&style=feature:landscape.man_made|element:geometry.fill|color:0xece2d9|&style=feature:poi.park|element:geometry.fill|color:0xccdca1|&style=feature:road|element:labels.text.fill|color:0x767676|&style=feature:road|element:labels.text.stroke|color:0xffffff|&style=feature:poi|element:all|visibility:off|&style=feature:landscape.natural|element:geometry.fill|visibility:on|color:0xb8cb93|&style=feature:poi.park|element:all|visibility:on|&style=feature:poi.sports_complex|element:all|visibility:on|&style=feature:poi.medical|element:all|visibility:on|&style=feature:poi.business|element:all|visibility:simplified|',
    'paper' => 'style=feature:landscape|element:all|hue:0xF1FF00|saturation:-27.4|lightness:9.4|gamma:1|&style=feature:road.highway|element:all|hue:0x0099FF|saturation:-20|lightness:36.4|gamma:1|&style=feature:road.arterial|element:all|hue:0x00FF4F|saturation:0|lightness:0|gamma:1|&style=feature:road.local|element:all|hue:0xFFB300|saturation:-38|lightness:11.2|gamma:1|&style=feature:water|element:all|hue:0x00B6FF|saturation:4.2|lightness:-63.4|gamma:1|&style=feature:poi|element:all|hue:0x9FFF00|saturation:0|lightness:0|gamma:1|',
    'midnight' => 'style=feature:water|element:all|color:0x021019|&style=feature:landscape|element:all|color:0x08304b|&style=feature:poi|element:geometry|color:0x0c4152|lightness:5|&style=feature:road.highway|element:geometry.fill|color:0x000000|&style=feature:road.highway|element:geometry.stroke|color:0x0b434f|lightness:25|&style=feature:road.arterial|element:geometry.fill|color:0x000000|&style=feature:road.arterial|element:geometry.stroke|color:0x0b3d51|lightness:16|&style=feature:road.local|element:geometry|color:0x000000|&style=feature:all|element:labels.text.fill|color:0xffffff|&style=feature:all|element:labels.text.stroke|color:0x000000|lightness:13|&style=feature:transit|element:all|color:0x146474|&style=feature:administrative|element:geometry.fill|color:0x000000|&style=feature:administrative|element:geometry.stroke|color:0x144b53|lightness:14|weight:1.4|');
} // GMW_styles