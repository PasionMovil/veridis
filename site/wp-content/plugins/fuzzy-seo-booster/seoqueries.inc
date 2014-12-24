<?php

/**
 * 
 * @param $ref - string - current search engine referrer
 * @return string - delemiter for current search engine referrer
 */
function seoqueries_get_delim($ref) {
    // Search engine match array
    // Used for fast delimiter lookup for single host search engines.
    // Non .com Google/MSN/Yahoo referrals are checked for after this array is checked

    $search_engines = array(
							'google.com.mx' => 'q',
							'google.com' => 'q',
							'search.yahoo.com' => 'p',
							'fr.search.yahoo.com' => 'p',
							'search.msn.com' => 'q',
							'search.live.com' => 'q',
							'rechercher.aliceadsl.fr' => 'qs',
							'vachercher.lycos.fr' => 'query',
							'search.lycos.com' => 'query',
							'alltheweb.com' => 'q',
							'search.aol.com' => 'query',
							'search.ke.voila.fr' => 'rdata',
							'recherche.club-internet.fr' => 'q',
							'ask.com' => 'q',
							'hotbot.com' => 'query',
							'overture.com' => 'Keywords',
							'search.netscape.com' => 'query',
							'search.looksmart.com' => 'qt',
							'search.earthlink.net' => 'q',
							'search.viewpoint.com' => 'k',
							'mamma.com' => 'query'
					  );

    $delim = FALSE;

    // Check to see if we have a host match in our lookup array
    if (isset($search_engines[$ref])) {
        $delim = $search_engines[$ref];
    }
    else {
	// Lets check for referrals for international TLDs and sites with strange formats        
		if (strpos($ref, 'google.') !== FALSE && strpos($ref, 'reader') === FALSE)
			$delim = 'q';
		elseif (strpos($ref, 'search.msn.') !== FALSE)
			$delim = 'q';
		elseif (strpos($ref, '.search.yahoo.') !== FALSE)
			$delim = 'q';
	    elseif (strpos($ref, 'exalead.') !== FALSE)
			$delim = 'q';
		elseif (strpos($ref, 'search.aol.') !== FALSE)
			$delim = 'query';
		elseif (strpos($ref, '.ask.com') !== FALSE)
			$delim = 'q';
		elseif (strpos($ref, 'recherche.aol.fr') !== FALSE)
			$delim = (strpos($_SERVER['HTTP_REFERER'], 'query')!==FALSE)?'query':'q';
	}

    return $delim;
}


/**
 * 
 * @param $d - delimiter in referrer string
 * @return string - search keywords
 */
function seoqueries_get_terms($d) {
    $terms       = null;
    $query_array = array();
    $query_terms = null;

    // Get raw query
    $query = explode($d.'=', $_SERVER['HTTP_REFERER']);
    $query = explode('&', $query[1]);
    $query = urldecode($query[0]);

    // Remove quotes, split into words, and format for HTML display
    $query = str_replace("'", '', $query);
    $query = str_replace('"', '', $query);
    $query_array = preg_split('/[\s,\+\.]+/',$query);
    $query_terms = implode(' ', $query_array);
    $terms = htmlspecialchars(urldecode($query_terms));

    return $terms;
}

/**
 * This function looks for referrer variable and return it in special format (without "www." prefix)
 * @return string - referrer value
 */
function seoqueries_get_refer() {
    // Break out quickly so we don't waste CPU cycles on non referrals
    if (!isset($_SERVER['HTTP_REFERER']) || ($_SERVER['HTTP_REFERER'] == '')) return FALSE;

    $referer_info = parse_url($_SERVER['HTTP_REFERER']);
    $referer = $referer_info['host'];

    // Remove www. is it exists
    if (substr($referer, 0, 4) == 'www.')
        $referer = substr($referer, 4);

    return $referer;
}

/**
 * Return true if the referer is a search engine
 * @param $what - what info you are going to get
 * @return mixed
 */
function seoqueries_getinfo($what) {

    // Did we come from a search engine? 
    $referer = seoqueries_get_refer();
    if (!$referer) return FALSE;
    $delimiter = seoqueries_get_delim($referer);

    if ($delimiter) 
    { 
        $terms = seoqueries_get_terms($delimiter);

        if ($what == 'isref') { 
			return ($terms != ''?true:false);
		}
        if ($what == 'referrer') {
            $parsed = parse_url($_SERVER['HTTP_REFERER']);
            echo '<a href="http://'.$parsed['host'].'">'.$parsed['host'].'</a>';
        }
        if ($what == 'terms') { echo $terms; }
        
    } 
}

/**
 * Gets type and id for current loading page
 * It may be homepage, single post page, static page, category page, tag page or archive page 
 * @return array with two keys: 'type' and 'id'
 */
function seoqueries_get_type_id(){
	global $wp,$wpdb;
	
	if (is_home()){
		return array(
			'type' => 'home',
			'id' => 0
		);
	}elseif (is_single()) {
		if ($wp->did_permalink){
			$post_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '". $wp->query_vars['name'] ."'");
		}else{
			$post_id = $wp->query_vars['p'];
		}
		return array(
			'type' => 'post',
			'id' =>  $post_id
		);
	}elseif (is_page()){
		if ($wp->did_permalink){
			$page_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '". $wp->query_vars['pagename'] ."'");
		}else{
			$page_id = $wp->query_vars['page_id'];
		}
		return array(
			'type' => 'page',
			'id' =>  $page_id
		);
	}elseif (is_category()){
		if ($wp->did_permalink){
			$cat_id = get_cat_id($wp->query_vars['category_name']);
		}else{
			$cat_id = $wp->query_vars['cat'];
		}
		return array(
			'type' => 'category',
			'id' =>  $cat_id
		);
	}elseif (is_tag()){

		$tag_name = $wp->query_vars['tag'];
		$sql = "SELECT t.term_id FROM ". $wpdb->terms ." t ".
			   "INNER JOIN ".  $wpdb->term_taxonomy ." tt ON t.term_id= tt.term_id".
			   " WHERE tt.taxonomy = 'post_tag' AND t.slug = '". $tag_name ."'  ";
		$tag_id = $wpdb->get_var($sql);
		return array(
			'type' => 'tag',
			'id' =>  $tag_id
		);
	}elseif (is_archive()){
		if ($wp->did_permalink){
			$archive_id = $wp->query_vars['year'] . $wp->query_vars['monthnum'];
		}else{
			$archive_id = $wp->query_vars['m'];
		}
		return array(
			'type' => 'archive',
			'id' =>  $archive_id
		);
	}
}

/**
 * 
 * @param $term
 * @param $min_founded - int, minimal number of 'founded' value for terms
 * @param $max_founded - int, max number of 'founded' value for terms 
 * @return string - tag value (by default one from 'strong','h6'-'h2' You can edit tag list in setting in the wp-admin)
 */
function seoqueries_get_tag($term,$min_founded,$max_founded){
	$tags_str = get_option('seoqueries_tags','strong,h6,h5,h4,h3,h2');
	$tags = explode(',',$tags_str);
	foreach($tags as $key=>$value){
		$tags[$key] = trim($value);
	}
	$step = ($max_founded - $min_founded)/count($tags);
	if ($step == 0){
		return 'h2';
	}
	$number = (int)($term->founded - $min_founded)/$step;
	if ($number == count($tags)){
		$number--;	
	}
	return $tags[$number]; 
}

function seoqueries_get_item_title($type,$id){
	switch ($type){
		case 'post':
			$post = get_post($id);
			return $post->post_title;
		case 'page':
			$page = get_page($id);
			return $page->post_title;
		case 'category':
			$cat = get_category($id);
			return $cat->name;
		case 'archive':
			return $id;
		case 'tag':
			$tag = get_tag($id);
			return $tag->name;
	}
}

function seoqueries_get_google_position($stid){
	global $wpdb;
	$sql = "SELECT position FROM {$wpdb->searchterms_stats} WHERE position IS NOT NULL AND stid=$stid ORDER BY date_clicked DESC LIMIT 1";
	return $wpdb->get_var($sql);	
}
function seoqueries_last_clicked_date($stid,$page_type = false, $page_id = false){
	global $wpdb;
	$sql = "SELECT date_clicked FROM {$wpdb->searchterms_stats} WHERE stid=$stid ";
	if (!empty($page_type) && !empty($page_id)){
		$sql .=" AND page_type = '$page_type' AND page_id='$page_id' ";
	}
	$sql .=" ORDER BY date_clicked DESC LIMIT 1";
	return $wpdb->get_var($sql);	
}