<?php
/**
 * Defines the Setting page.
 * 
 * @action	fetch_tweets_action_updated_credentials - triggered when the main credentials are updated.
 */
abstract class FetchTweets_AdminPage_Page_Setting extends FetchTweets_AdminPage_Page_Template {
			
	/*
	 * Settings Page
	 */
	public function do_before_fetch_tweets_settings() {	// do_before_ + page slug
		$this->setPageTitleVisibility( false );
	}
            
	// The connect page
	public function do_form_fetch_tweets_settings_twitter_connect() {	// do_form_ + page slug + _ + tab slug
		FetchTweets_TwitterAPI_Verification::renderStatus( $this->_getVerificationStatus() );				
	}
	public function do_form_fetch_tweets_settings_authentication() {	// do_form_ + page slug + _ + tab slug
		FetchTweets_TwitterAPI_Verification::renderStatus( $this->_getVerificationStatus() );		
	}

        /**
         * Retrieves the verification status with the saved access keys.
         * 
         * This method first checks with the manually set authentication keys and if it fails, it checks with the automatically set authentication keys.
         * 
         * @since			1.3.0
         * @return			array			The array which contains the verification status.
         */
        protected function _getVerificationStatus() {

            // If it is disconnected, return an empty array.
            if ( ! $this->oOption->isConnected() ) {			
                return array();
            }
            
            // If the access token and access secret keys have been manually set,
            $_aStatus = $this->oOption->isAuthKeysManuallySet()
                ? $this->_getAuthenticationStatus( $this->oOption->getConsumerKey(), $this->oOption->getConsumerSecret(), $this->oOption->getAccessToken(), $this->oOption->getAccessTokenSecret() )
                : array();
                
            if ( ! empty( $_aStatus ) ) return $_aStatus;
                
            // If the access token and secret keys have been automatically set,
            if ( $this->oOption->isAuthKeysAutomaticallySet() ) {
                $_aStatus = $this->_getAuthenticationStatus( FetchTweets_Commons::ConsumerKey, FetchTweets_Commons::ConsumerSecret, $this->oOption->getAccessTokenAuto(), $this->oOption->getAccessTokenSecretAuto() );
            }
        
            return $_aStatus;
        
        }
            /**
             * Checks the API credential is valid or not.
             * 	 
             * @since			1.3.0
             * @return			array			the retrieved data.
             * @remark			The returned data is a merged result of 'account/verify_credientials' and 'rate_limit_status'.
             */
            private function _getAuthenticationStatus( $sConsumerKey, $sConsumerSecret, $sAccessToken, $sAccessSecret ) {
                
                $oTwitterOAuth_Verification = new FetchTweets_TwitterAPI_Verification( $sConsumerKey, $sConsumerSecret, $sAccessToken, $sAccessSecret );
                $_aStatuses = $oTwitterOAuth_Verification->getStatus();
// FetchTweets_Debug::log( $_aStatuses );
                return $_aStatuses;
                
            }        

	/**
	 * Redirects to the twitter to get authenticated.
	 * 
	 * @since			1.3.0
	 * @remark			This is redirected from the "Connect to Twitter" button.
	 */
	public function load_fetch_tweets_settings_twitter_redirect() {	// load_ + page slug + tab
	
		/* Build TwitterOAuth object with client credentials. */
		$_oConnect = new FetchTweets_TwitterOAuth( FetchTweets_Commons::ConsumerKey, FetchTweets_Commons::ConsumerSecret );
		 
		/* Get temporary credentials - Requesting authentication tokens, the parameter is the URL we will be redirected to. */
		$_aRequestToken = $_oConnect->getRequestToken( 
			add_query_arg( 
				array(
					'post_type' => 'fetch_tweets',
					'page' => 'fetch_tweets_settings',
					'tab' => 'twitter_callback'
				),
				admin_url( $GLOBALS['pagenow'] ) 
			)
		);
		
		/* Save temporary credentials to transient. */
		$_aTemporaryTokens = array();
		$_aTemporaryTokens['oauth_token'] = $_aRequestToken['oauth_token'];
		$_aTemporaryTokens['oauth_token_secret'] = $_aRequestToken['oauth_token_secret'];
		set_transient( FetchTweets_Commons::TransientPrefix . '_oauth', $_aTemporaryTokens, 60*10 );	// 10 minutes
		
		/* If last connection failed don't display authorization link. */
		switch ( $_oConnect->http_code ) {
			case 200:	/* Build authorize URL and redirect user to Twitter. */
				wp_redirect( $_oConnect->getAuthorizeURL( $_aTemporaryTokens['oauth_token'] ) );	// goes to twitter.com
			break;
			default:	/* Show notification if something went wrong. */
				die( __( 'Could not connect to Twitter. Refresh the page or try again later.', 'fetch-tweets' ) );
		}
		exit;
	
	}	
	
	/**
	 * Receives the callback from Twitter authentication and saves the access token.
	 * 
	 * @remark			This method is triggered when the user get redirected back to the admin page
	 */
	public function load_fetch_tweets_settings_twitter_callback() { // load + page slug + tab slug
				
		/* If the oauth_token is old redirect to the authentication page. */
		$_aTemporaryTokens = get_transient( FetchTweets_Commons::TransientPrefix . '_oauth' );
		if ( false === $_aTemporaryTokens || ! isset( $_aTemporaryTokens['oauth_token'], $_aTemporaryTokens['oauth_token_secret'] )) {
			die( 
				wp_redirect( 
					add_query_arg( 
						array( 
							'post_type' => 'fetch_tweets', 
							'page' => 'fetch_tweets_settings', 
							'tab' => 'authentication',
						), 
						admin_url( $GLOBALS['pagenow'] ) 
					) 
				)
			);
		}		
		
		$oOption = & $GLOBALS['oFetchTweets_Option'];

		/* Create TwitterOAuth object with app key/secret and token key/secret from default phase */
		$_oConnect = new FetchTweets_TwitterOAuth( 
			FetchTweets_Commons::ConsumerKey, 
			FetchTweets_Commons::ConsumerSecret, 
			$_aTemporaryTokens['oauth_token'],
			$_aTemporaryTokens['oauth_token_secret'] 
		);

		/* Request access tokens from twitter */
		$_aAccessTokens = $_oConnect->getAccessToken( $_REQUEST['oauth_verifier'] );
		  /* $_aAccessTokens Looks like this
		  'oauth_token' => string 'asxxx-sxxx...' (length=50)
		  'oauth_token_secret' => string 'xxx....' (length=41)
		  'user_id' => string '132.....' (length=10)
		  'screen_name' => string 'my_screen_name' (length=9) */
  
		/* Save the access tokens. Normally these would be saved in a database for future use. */
		$oOption->saveCredentials( 
			array(	
				'access_token' => $_aAccessTokens['oauth_token'],
				'access_secret' => $_aAccessTokens['oauth_token_secret'],
				'screen_name'	=>	$_aAccessTokens['screen_name'],
				'user_id'	=>	$_aAccessTokens['user_id'],
				'is_connected'	=>	true,
				'connect_method' => 'oauth',
			)
		);
				
		/* If HTTP response is 200 continue otherwise send to connect page to retry */
		if ( 200 == $_oConnect->http_code ) {
			
			/* The user has been verified */
			$_sRediretURL = add_query_arg( 
				array(
					'post_type' => 'fetch_tweets',
					'page' => 'fetch_tweets_settings',
					'tab' => 'twitter_connect'
				), 
				admin_url( $GLOBALS['pagenow'] ) 
			);
			
		  
		} else {
			
		  /* Save HTTP status for error dialogue on authentication page.*/
		  // Let the user set authentication keys manually		  
			$_sRediretURL = add_query_arg( 
				array( 
					'post_type' => 'fetch_tweets',
					'page' => 'fetch_tweets_settings',
					'tab' => 'authentication' 
				), 
				admin_url( $GLOBALS['pagenow'] ) 
			);
	  
		}
		die( wp_redirect( $_sRediretURL ) );
	
	}	            
					
}