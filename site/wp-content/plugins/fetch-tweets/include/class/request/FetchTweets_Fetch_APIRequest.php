<?php
/**
 * Handles Twitter API requests.
 * 
 * @package			Fetch Tweets
 * @subpackage		
 * @copyright		Michael Uno
 * @since			1.3.4
 */
abstract class FetchTweets_Fetch_APIRequest extends FetchTweets_Fetch_Cache {
	
	/**
	 * Performs the Twitter API request by the given URI.
	 * 
	 * This checks the existent caches and if it's not expired it uses the cache.
	 * 
	 * @since			1.2.0
	 * @since			2.2.0			Changed the scope to public to let extension plugins to use this method.
     * @since           2.3.5           Added the $aRateLimitKey parameter.
	 * @access			public
	 * @param			string			$strRequestURI				The GET request URI with the query.
	 * @param			string			$strArrayKey				The key name of the result tweet array. The search results holds the tweets in the "status" array; in that case "status" needs to be passed.
	 * @param			integer			$intCacheDuration			The cache duration in seconds.
	 * @param			array			$aRateLimitKey			    One dimensional array that represents the key of the rate limit status array.
	 * @return			array
	 */ 
	public function doAPIRequest_Get( $strRequestURI, $strArrayKey=null, $intCacheDuration=600, $aRateLimitKey=array() ) {

		// Create an ID from the URI.
		$strRequestID = FetchTweets_Commons::TransientPrefix . "_" . md5( trim( $strRequestURI ) );

		// Retrieve the cache, and if there is, use it.
		$arrTransient = $this->getTransient( $strRequestID );
		if ( 
			false !== $arrTransient
			&& is_array( $arrTransient ) 
			&& isset( $arrTransient['mod'], $arrTransient['data'] )
		) {
			
			// Check the cache expiration.
			if ( ( $arrTransient['mod'] + ( ( int ) $intCacheDuration ) ) < time() ) { 	// expired

                // these keys will be checked in the cache renewal events.
				$this->arrExpiredTransientsRequestURIs[ $this->_sanitizeRequstURI( $strRequestURI ) ] = array( 
					'URI'	                => $strRequestURI, 	
					'key'	                => $strArrayKey,
                    'rate_limit_status_key' => $aRateLimitKey,
				);
                
			}
            
			return ( array ) $this->oBase64->decode( $arrTransient['data'] );
			
		} 
		
		return $this->_isTwitterAPIRequest( $strRequestURI )
			? $this->setAPIGETRequestCache( $strRequestURI, $strArrayKey, $aRateLimitKey )	// Twitter API request
			: $this->setGETRequestCache( $strRequestURI );	// not an API request
		
	}	
	
		/**
		 * Checks if the given URI is for Twitter API.
		 * 
		 * @since			2.1
		 */
		protected function _isTwitterAPIRequest( $sURL ) {
			return ( 'api.twitter.com' == parse_url( $sURL,  PHP_URL_HOST ) );
		}

	
}