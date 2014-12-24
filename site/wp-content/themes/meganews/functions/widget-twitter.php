<?php
/*
	Plugin Name: Twitter Widget
	Description: Twitter widget.
	Version: 1.0 
*/

class twitter_widget extends WP_Widget {
/*-----------------------------------------------------------------------------------*/
/*	Widget Setup
/*-----------------------------------------------------------------------------------*/
	
function twitter_Widget() {
	$widget_options = array(
		'classname' => 'twitter_widget',
		'description' => __('Custom twitter widget.','meganews')
	);
		
	$control_options = array(    //dodama svoje incializirane mere
		'width' => 200,
		'height' => 400,
		'id_base' => 'twitter_widget'
	);
	$this->WP_Widget( 'twitter_widget', __('Pego - Twitter Widget','meganews'), $widget_options, $control_options );	
}
function widget( $args, $instance ) {
	
	
			extract($args);
				if(!empty($instance['title'])){ $title = apply_filters( 'widget_title', $instance['title'] ); }
				
				echo $before_widget;				
				if ( ! empty( $title ) ){ echo $before_title . $title . $after_title; }

				
					//check settings and die if not set
						if(empty($instance['username'])){
							echo '<strong>Please fill all widget settings!</strong>' . $after_widget;
							return;
						}		
						if ( function_exists( 'ot_get_option' ) ) {
							$consumerkey = '';
							if (ot_get_option('meganews_twitter_consumer_key')) {
								$consumerkey = ot_get_option('meganews_twitter_consumer_key');
							}
							$consumersecret = '';
							if (ot_get_option('meganews_twitter_consumer_secret')) {
								$consumersecret = ot_get_option('meganews_twitter_consumer_secret');
							}
							$accesstoken = '';
							if (ot_get_option('meganews_twitter_access_token')) {
								$accesstoken = ot_get_option('meganews_twitter_access_token');
							}
							$accesstokensecret = '';
							if (ot_get_option('meganews_twitter_access_secret_token')) {
								$accesstokensecret = ot_get_option('meganews_twitter_access_secret_token');
							}
							$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
							$tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$instance['username']."&count=10") or die('Couldn\'t retrieve tweets! Wrong username?');
						}	
														
							if(!empty($tweets->errors)){
								if($tweets->errors[0]->message == 'Invalid or expired token'){
									echo '<strong>'.$tweets->errors[0]->message.'!</strong><br />You\'ll need to regenerate it <a href="https://dev.twitter.com/apps" target="_blank">here</a>!' . $after_widget;
								}else{
									echo '<strong>'.$tweets->errors[0]->message.'</strong>' . $after_widget;
								}
								return;
							}
							
							for($i = 0;$i <= count($tweets); $i++){
								if(!empty($tweets[$i])){
									$tweets_array[$i]['created_at'] = $tweets[$i]->created_at;
									$tweets_array[$i]['text'] = $tweets[$i]->text;			
									$tweets_array[$i]['status_id'] = $tweets[$i]->id_str;			
								}	
							}							
							
							//save tweets to wp option 		
								update_option('tp_twitter_plugin_tweets',serialize($tweets_array));							
								update_option('tp_twitter_plugin_last_cache_time',time());
								
							echo '<!-- twitter cache has been updated! -->';
						
						
						
						
						
					
					
					
										
					
					
					$tp_twitter_plugin_tweets = maybe_unserialize(get_option('tp_twitter_plugin_tweets'));
					if(!empty($tp_twitter_plugin_tweets)){
						print '
						<div class="twitter_updates">
							<ul class="tweet_list">';
							$fctr = '1';
							foreach($tp_twitter_plugin_tweets as $tweet){	
						
							
								print '<li>'.convert_links($tweet['text']).'<br /><span class="tweet_time"><a target="_blank" href="http://twitter.com/'.$instance['username'].'/statuses/'.$tweet['status_id'].'">'.relative_time($tweet['created_at']).'</a></span></li>';
								if($fctr == $instance['tweetstoshow']){ break; }
								$fctr++;
							}
						
						print '
							</ul>
						</div>';
					}
				
				
				
				echo $after_widget;
}
	//widget settings form	
			public function form($instance) {
				$defaults = array( 'title' => '',  'username' => '', 'tweetstoshow' => '' );
				$instance = wp_parse_args( (array) $instance, $defaults );
						
				echo '
				<p><label>Title:</label>
					<input type="text" name="'.$this->get_field_name( 'title' ).'" id="'.$this->get_field_id( 'title' ).'" value="'.esc_attr($instance['title']).'" class="widefat" /></p>													
																					
				<p><label>Twitter Username:</label>
					<input type="text" name="'.$this->get_field_name( 'username' ).'" id="'.$this->get_field_id( 'username' ).'" value="'.esc_attr($instance['username']).'" class="widefat" /></p>																			
				<p><label>Tweets to display:</label>
					<select type="text" name="'.$this->get_field_name( 'tweetstoshow' ).'" id="'.$this->get_field_id( 'tweetstoshow' ).'">';
					$i = 1;
					for(i; $i <= 10; $i++){
						echo '<option value="'.$i.'"'; if($instance['tweetstoshow'] == $i){ echo ' selected="selected"'; } echo '>'.$i.'</option>';						
					}
					echo '
					</select></p>';		
			}


			}
/*     Adding widget to widgets_init and registering flickr widget    */
add_action( 'widgets_init', 'twitter_widgets' );

function twitter_widgets() {
	register_widget( 'twitter_Widget' );
}
?>