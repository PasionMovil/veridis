jQuery(document).ready(function(){
	initKeywordsSearch();
});
function initKeywordsSearch(){
	$form = jQuery("#seoqueries-keywords-search");
	if ($form.size() == 0){
		return;
	}
	$form.submit(function(e){
		e.preventDefault();
		seoqueriesProcessSearch();
	});
}

function seoqueriesProcessSearch(){
	var params = {
		seoqueries_keyword_search: 1,
		keyword : jQuery("#keyword").val(),
		startDate : jQuery("#start-date").val(),
		endDate : jQuery("#end-date").val(),
		exactDate : jQuery("#exact-date").val()
	};
	if (jQuery("#exact-day:checked").size()){
		params.exactDay = 1;
	}else{
		params.exactDay = 0;
	}
	
	jQuery.ajax({
		url: baseUrl,
		type: "POST",
		data: params,
		dataType: "json",
		success : function(data){
			if (data.result == 'terms_listing'){
				if (data.not_found){
					termsListingNotFound();
				}else{
					showTermsListing(data.items)
				}	
			}
			if (data.result == 'term_info'){
				seoqueriesShowTermInfo(data);
			}
		}
	});
}

function showTermsListing(items){
	var $block = jQuery("#seoqueries-search-result");
	var html = "<p>There is no exact keyword you searched. Maybe theseone will be useful for you.</p>";
	html += "<ul>";
	for (var i in items){
		html +='<li><a class="seoqueries-term-link" href="#" rel="'+ items[i]['stid'] +'">'+ items[i]['term_value'] +'</a></li>';
	}
	html +="</ul>";
	$block.html(html);
	initTermsLinks();
}

function termsListingNotFound(){
	var $block = jQuery("#seoqueries-search-result").html("<p>There is no such terms in the database</p>");
}
function initTermsLinks(){
	var $links = jQuery("a.seoqueries-term-link");
	$links.click(function(e){
		e.preventDefault();
		jQuery("#keyword").attr("value",jQuery(this).text());
		seoqueriesProcessSearch();
	});
}

function seoqueriesShowTermInfo(data){
	var $block = jQuery("#seoqueries-search-result");
	$block.html(data.itemsHtml);
}