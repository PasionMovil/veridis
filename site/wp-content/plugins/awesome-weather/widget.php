<?php
	
// AWESOME WEATHER WIDGET, WIDGET CLASS, SO MANY WIDGETS
class AwesomeWeatherWidget extends WP_Widget 
{
	function AwesomeWeatherWidget() { parent::__construct(false, $name = 'Awesome Weather Widget'); }

    function widget($args, $instance) 
    {	
        extract( $args );
        
        $location 					= isset($instance['location']) ? $instance['location'] : false;
        $owm_city_id 				= isset($instance['owm_city_id']) ? $instance['owm_city_id'] : false;
        $override_title 			= isset($instance['override_title']) ? $instance['override_title'] : false;
        $widget_title 				= isset($instance['widget_title']) ? $instance['widget_title'] : false;
        $units 						= isset($instance['units']) ? $instance['units'] : false;
        $size 						= isset($instance['size']) ? $instance['size'] : false;
        $forecast_days 				= isset($instance['forecast_days']) ? $instance['forecast_days'] : false;
        $hide_stats 				= (isset($instance['hide_stats']) AND $instance['hide_stats'] == 1) ? 1 : 0;
        $show_link 					= (isset($instance['show_link']) AND $instance['show_link'] == 1) ? 1 : 0;
        $background_by_weather 		= (isset($instance['background_by_weather']) AND $instance['background_by_weather'] == 1) ? 1 : 0;
        $background					= isset($instance['background']) ? $instance['background'] : false;
        $custom_bg_color			= isset($instance['custom_bg_color']) ? $instance['custom_bg_color'] : false;
        $text_color					= isset($instance['text_color']) ? $instance['text_color'] : "#ffffff";
		$hide_attribution 			= (isset($instance['hide_attribution']) AND $instance['hide_attribution'] == 1) ? 1 : 0;
		
		echo $before_widget;
		if($widget_title != "") echo $before_title . $widget_title . $after_title;
		echo awesome_weather_logic( array( 
											'location' => $location, 
											'owm_city_id' => $owm_city_id,
											'override_title' => $override_title, 
											'size' => $size, 
											'units' => $units, 
											'forecast_days' => $forecast_days, 
											'hide_stats' => $hide_stats, 
											'show_link' => $show_link, 
											'background' => $background, 
											'custom_bg_color' => $custom_bg_color,
											'background_by_weather' => $background_by_weather,
											'text_color' => $text_color,
											'hide_attribution' => $hide_attribution
										));
		echo $after_widget;
    }
 
    function update($new_instance, $old_instance) 
    {		
		$instance = $old_instance;
		$instance['location'] 					= strip_tags($new_instance['location']);
		$instance['owm_city_id'] 				= strip_tags($new_instance['owm_city_id']);
		$instance['override_title'] 			= strip_tags($new_instance['override_title']);
		$instance['widget_title'] 				= strip_tags($new_instance['widget_title']);
		$instance['units'] 						= strip_tags($new_instance['units']);
		$instance['size'] 						= strip_tags($new_instance['size']);
		$instance['forecast_days'] 				= strip_tags($new_instance['forecast_days']);
		$instance['background'] 				= strip_tags($new_instance['background']);
		$instance['custom_bg_color'] 			= strip_tags($new_instance['custom_bg_color']);
		$instance['text_color'] 				= strip_tags($new_instance['text_color']);
		$instance['background_by_weather'] 		= (isset($new_instance['background_by_weather']) AND $new_instance['background_by_weather'] == 1) ? 1 : 0;
		$instance['hide_stats'] 				= (isset($new_instance['hide_stats']) AND $new_instance['hide_stats'] == 1) ? 1 : 0;
		$instance['hide_attribution'] 			= (isset($new_instance['hide_attribution']) AND $new_instance['hide_attribution'] == 1) ? 1 : 0;
		$instance['show_link'] 					= (isset($new_instance['show_link']) AND $new_instance['show_link'] == 1) ? 1 : 0;
        return $instance;
    }
 
    function form($instance) 
    {	
    	global $awesome_weather_sizes;
    	
        $location 					= isset($instance['location']) ? esc_attr($instance['location']) : "";
        $owm_city_id 				= isset($instance['owm_city_id']) ? esc_attr($instance['owm_city_id']) : "";
        $override_title 			= isset($instance['override_title']) ? esc_attr($instance['override_title']) : "";
        $widget_title 				= isset($instance['widget_title']) ? esc_attr($instance['widget_title']) : "";
        $selected_size 				= isset($instance['size']) ? esc_attr($instance['size']) : "wide";
        $units 						= (isset($instance['units']) AND strtoupper($instance['units']) == "C") ? "C" : "F";
        $forecast_days 				= isset($instance['forecast_days']) ? esc_attr($instance['forecast_days']) : 5;
        $hide_stats 				= (isset($instance['hide_stats']) AND $instance['hide_stats'] == 1) ? 1 : 0;
        $hide_attribution 			= (isset($instance['hide_attribution']) AND $instance['hide_attribution'] == 1) ? 1 : 0;
		$background_by_weather 		= (isset($instance['background_by_weather']) AND $instance['background_by_weather'] == 1) ? 1 : 0;
        $show_link 					= (isset($instance['show_link']) AND $instance['show_link'] == 1) ? 1 : 0;
        $background					= isset($instance['background']) ? esc_attr($instance['background']) : "";
        $custom_bg_color			= isset($instance['custom_bg_color']) ? esc_attr($instance['custom_bg_color']) : "";
        $text_color					= isset($instance['text_color']) ? esc_attr($instance['text_color']) : "#ffffff";
        
        $appid = apply_filters( 'awesome_weather_appid', awe_get_appid() );

        $wp_theme = wp_get_theme();
		$wp_theme = $wp_theme->get('TextDomain');
	?>
	
		<style>
			.awe-suggest { font-size: 0.9em; border-bottom: solid 1px #ccc; padding: 5px 1px; font-weight: bold; }
			.awe-size-options { padding: 1px 10px; background: #efefef; }
		</style>
	
	
		<?php if(!$appid) { ?>
		<div style="background: #dc3232; color: #fff; padding: 10px; margin: 10px;">
			<?php
				echo __("As of October 2015, OpenWeatherMap requires an APP ID key to access their weather data.", 'awesome-weather');
				echo " <a href='http://openweathermap.org/appid' target='_blank' style='color: #fff;'>";
				echo __('Get your APPID', 'awesome-weather');
				echo "</a> ";
				echo __("and add it to the new settings page.");
				?>
		</div>
		<?php } ?>
	
        <p>
          <label for="<?php echo $this->get_field_id('location'); ?>">
          	<?php _e('Search for Your Location:', 'awesome-weather'); ?><br />
          	<small><?php _e('(i.e: London or New York City)', 'awesome-weather'); ?></small>
          </label> 
          <input data-cityidfield="<?php echo $this->get_field_id('owm_city_id'); ?>" data-unitsfield="<?php echo $this->get_field_id('units'); ?>" class="widefat  awe-location-search-field-openweathermaps" style="margin-top: 4px;" id="<?php echo $this->get_field_id('location'); ?>" name="<?php echo $this->get_field_name('location'); ?>" type="text" value="<?php echo $location; ?>" />
        </p>
        
		<p>
			<label for="<?php echo $this->get_field_id('owm_city_id'); ?>">
				<?php _e('OpenWeatherMap City ID:', 'awesome-weather-pro'); ?><br>
				<small><?php _e('(use the field above to find the ID for your city)', 'awesome-weather'); ?></small>
			</label>
			<input class="widefat" style="margin-top: 4px; line-height: 1.5em;" id="<?php echo $this->get_field_id('owm_city_id'); ?>" name="<?php echo $this->get_field_name('owm_city_id'); ?>" type="text" value="<?php echo $owm_city_id; ?>" />
		</p>
	
		<span id="awe-owm-spinner-<?php echo $this->get_field_id('location'); ?>" class="hidden"><img src="/wp-admin/images/spinner.gif"></span>
		<div id="owmid-selector-<?php echo $this->get_field_id('location'); ?>"></div>

		<?php if( !$owm_city_id ) { ?>
			<script>jQuery('#<?php echo $this->get_field_id('location'); ?>').trigger('keyup');</script>
		<?php } ?>
      
        <p>
          <label for="<?php echo $this->get_field_id('override_title'); ?>"><?php _e('Override Title:', 'awesome-weather'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('override_title'); ?>" name="<?php echo $this->get_field_name('override_title'); ?>" type="text" value="<?php echo $override_title; ?>" />
        </p>
                
        <p>
          <label for="<?php echo $this->get_field_id('units'); ?>"><?php _e('Units:', 'awesome-weather'); ?></label>  &nbsp;
          <input id="<?php echo $this->get_field_id('units'); ?>" name="<?php echo $this->get_field_name('units'); ?>" type="radio" value="F" <?php if($units == "F") echo ' checked="checked"'; ?> /> F &nbsp; &nbsp;
          <input id="<?php echo $this->get_field_id('units'); ?>" name="<?php echo $this->get_field_name('units'); ?>" type="radio" value="C" <?php if($units == "C") echo ' checked="checked"'; ?> /> C
        </p>
        
        <div class="awe-size-options">

        <?php if( $wp_theme == "twentytwelve") { ?><div class="awe-suggest"> Suggested settings: Wide, 5 Days</div><?php } ?>
        <?php if( $wp_theme == "twentythirteen") { ?><div class="awe-suggest"> Suggested settings: Tall, 4 Days</div><?php } ?>
        <?php if( $wp_theme == "twentyfourteen") { ?><div class="awe-suggest"> Suggested settings: Tall, 3 Days</div><?php } ?>
        <?php if( $wp_theme == "twentyfifteen") { ?><div class="awe-suggest"> Suggested settings: Tall, 4 Days</div><?php } ?>
        <?php if( $wp_theme == "twentysixteen") { ?><div class="awe-suggest"> Suggested settings: Wide, 5 Days</div><?php } ?>

		<p>
          <label for="<?php echo $this->get_field_id('size'); ?>"><?php _e('Size:', 'awesome-weather'); ?></label> 
          <select class="widefat" id="<?php echo $this->get_field_id('size'); ?>" name="<?php echo $this->get_field_name('size'); ?>">
          	<?php foreach($awesome_weather_sizes as $size) { ?>
          	<option value="<?php echo $size; ?>"<?php if($selected_size == $size) echo " selected=\"selected\""; ?>><?php echo $size; ?></option>
          	<?php } ?>
          </select>
		</p>
        
		<p>
          <label for="<?php echo $this->get_field_id('forecast_days'); ?>"><?php _e('Forecast:', 'awesome-weather'); ?></label> 
          <select class="widefat" id="<?php echo $this->get_field_id('forecast_days'); ?>" name="<?php echo $this->get_field_name('forecast_days'); ?>">
          	<option value="5"<?php if($forecast_days == 5) echo " selected=\"selected\""; ?>>5 Days</option>
          	<option value="4"<?php if($forecast_days == 4) echo " selected=\"selected\""; ?>>4 Days</option>
          	<option value="3"<?php if($forecast_days == 3) echo " selected=\"selected\""; ?>>3 Days</option>
          	<option value="2"<?php if($forecast_days == 2) echo " selected=\"selected\""; ?>>2 Days</option>
          	<option value="1"<?php if($forecast_days == 1) echo " selected=\"selected\""; ?>>1 Days</option>
          	<option value="hide"<?php if($forecast_days == 'hide') echo " selected=\"selected\""; ?>>Don't Show</option>
          </select>
		</p>
		
        </div>
		
        <p>
          <label for="<?php echo $this->get_field_id('background'); ?>"><?php _e('Background Image:', 'awesome-weather'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('background'); ?>" name="<?php echo $this->get_field_name('background'); ?>" type="text" value="<?php echo $background; ?>" />
        </p>
        
        <p>
          <input id="<?php echo $this->get_field_id('background_by_weather'); ?>" name="<?php echo $this->get_field_name('background_by_weather'); ?>" type="checkbox" value="1" <?php if($background_by_weather) echo ' checked="checked"'; ?> />
          <label for="<?php echo $this->get_field_id('background_by_weather'); ?>"><?php _e('Use Different Background Images Based on Weather', 'awesome-weather'); ?></label>  <a href="https://halgatewood.com/docs/plugins/awesome-weather-widget/creating-different-backgrounds-for-different-weather" target="_blank">(?)</a> &nbsp;
        </p>
        
        <p>
          <label for="<?php echo $this->get_field_id('custom_bg_color'); ?>"><?php _e('Custom Background Color:', 'awesome-weather'); ?></label><br />
          <small><?php _e('overrides color changing', 'awesome-weather'); ?>: #7fb761 or rgba(0,0,0,0.5)</small>
          <input class="widefat" id="<?php echo $this->get_field_id('custom_bg_color'); ?>" name="<?php echo $this->get_field_name('custom_bg_color'); ?>" type="text" value="<?php echo $custom_bg_color; ?>" />
        </p>
        
		<p>
		    <label for="<?php echo $this->get_field_id( 'text_color' ); ?>" style="display:block;"><?php _e( 'Text Color', 'awesome-weather' ); ?></label> 
		    <input class="widefat color-picker" id="<?php echo $this->get_field_id( 'text_color' ); ?>" name="<?php echo $this->get_field_name( 'text_color' ); ?>" type="text" value="<?php echo esc_attr( $text_color ); ?>" />
		</p>
		
		<script type="text/javascript">
		    jQuery(document).ready(function($) 
		    { 
		            jQuery('#<?php echo $this->get_field_id( 'text_color' ); ?>').on('focus', function(){
		                var parent = jQuery(this).parent();
		                jQuery(this).wpColorPicker()
		                parent.find('.wp-color-result').click();
		            }); 
		            
		            jQuery('#<?php echo $this->get_field_id( 'text_color' ); ?>').wpColorPicker()
		    }); 
		</script>
		
        <p>
          <input id="<?php echo $this->get_field_id('hide_stats'); ?>" name="<?php echo $this->get_field_name('hide_stats'); ?>" type="checkbox" value="1" <?php if($hide_stats) echo ' checked="checked"'; ?> />
          <label for="<?php echo $this->get_field_id('hide_stats'); ?>"><?php _e('Hide Current Condition Stats', 'awesome-weather'); ?></label> 
          
        </p>
		
		<p>
        	<input id="<?php echo $this->get_field_id('hide_attribution'); ?>" name="<?php echo $this->get_field_name('hide_attribution'); ?>" type="checkbox" value="1" <?php if($hide_attribution) echo ' checked="checked"'; ?> />
			<label for="<?php echo $this->get_field_id('hide_attribution'); ?>"><?php _e('Hide Weather Attribution', 'awesome-weather-pro'); ?></label>
		</p>
		
        <p>
          <input id="<?php echo $this->get_field_id('show_link'); ?>" name="<?php echo $this->get_field_name('show_link'); ?>" type="checkbox" value="1" <?php if($show_link) echo ' checked="checked"'; ?> />
		  <label for="<?php echo $this->get_field_id('show_link'); ?>"><?php _e('Link to Extended Forecast', 'awesome-weather'); ?></label>  &nbsp;
        </p> 
                
        <p>
          <label for="<?php echo $this->get_field_id('widget_title'); ?>"><?php _e('Widget Title: (optional)', 'awesome-weather'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('widget_title'); ?>" name="<?php echo $this->get_field_name('widget_title'); ?>" type="text" value="<?php echo $widget_title; ?>" />
        </p>
        <?php 
    }
}

add_action( 'widgets_init', create_function('', 'return register_widget("AwesomeWeatherWidget");') );