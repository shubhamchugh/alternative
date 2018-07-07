/*
* Custom Script
*/
 var mailformat = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;  
 var regularExpression = /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,50}$/;
 //var regularExpression = /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-z0-9!@#$%^&*]{8,16}$/;
		 

jQuery(document).ready(function(){

	/*
	* AUTOCOMPLETE SEARCH
	*/
	var lastSearch  = '';
	jQuery( "#search_alternative" ).keyup(function (){
		
		var minSearchLength = 3, q = jQuery(this).val();
		//alert(q);
		if ( q.length < minSearchLength || lastSearch == q ) {
			return;
		}
		
		lastSearch = q;
		
		jQuery( '.altpostsearch').addClass( 'is-active' );
		var post_id  = jQuery('#post_ID').val();
		jQuery.post(alternative_ajax.ajaxurl, { search: q, action: 'search_alternative_post', post_id : post_id }, function(data) {
			jQuery('#alternative-search-checklist').show();
			jQuery('#alternative-search-checklist').html(data);
			jQuery( '.altpostsearch').removeClass( 'is-active' );
		});      
    });
	
	
	
	jQuery(document).on("click",".clear-all",function() {

		jQuery('.alternatives tbody').hide(); 
	});	

	jQuery(document).on("click",".no-result",function() {
		jQuery('#alternative-search-checklist').hide();
	});	

	jQuery(document).on("click",".alt-post",function() {
		var pid = jQuery(this).data('pid');
		jQuery( '.altpostsearch').addClass( 'is-active' );
		jQuery('#alternative-search-checklist').hide();
		var post_id  = jQuery('#post_ID').val();

		var added_alts_arr = [];
        jQuery.each(jQuery("input[name='alt_posts[]']"), function(){
        	console.log(jQuery(this).val());            
            added_alts_arr.push(jQuery(this).val());
        });

        var alts_list = '';

        alts_list = added_alts_arr.join(", ");

		jQuery.post(alternative_ajax.ajaxurl, {action: 'search_post_alternatives', pid: pid, uselect : 'post', cpost_id : post_id, added_alts: alts_list }, function(data) {	
			jQuery( "#search-alternative-list" ).html( data );
			jQuery( '.altpostsearch').removeClass( 'is-active' );
			jQuery( "#search-alternative-list" ).show();
		});
	});	
	
	jQuery( "#alternative-group" ).change(function (){
		
		jQuery('#alternative-search-checklist').hide();
		var termid = jQuery(this).val();
		//alert(termid);
		jQuery( this ).addClass( "ui-autocomplete-loading" );
		
		jQuery( '.altgroupsearch').addClass( 'is-active' );

		var post_id  = jQuery('#post_ID').val();

		var added_alts_arr = [];
        jQuery.each(jQuery("input[name='alt_posts[]']"), function(){
        	console.log(jQuery(this).val());            
            added_alts_arr.push(jQuery(this).val());
        });

        var alts_list = '';

        alts_list = added_alts_arr.join(", ");

		jQuery.post(alternative_ajax.ajaxurl, {action: 'search_group_alternatives', tid: termid, post_id : post_id, added_alts: alts_list }, function(data) {
			
			jQuery( "#search-alternative-list" ).html( data );
			jQuery( "#search-alternative-list" ).show();
			jQuery( '.altgroupsearch').removeClass( 'is-active' );
			//alert(data);
		});
    });
	
	jQuery(document).on("click","#alt-select-all",function() {
		jQuery('input.alt-posts').not(this).prop('checked', this.checked);
	});
	
	jQuery(document).on("click",".alter-delete",function() {
		var aid = jQuery(this).data('id');
		var pid = jQuery(this).data('pid');
				
		jQuery( '.altpostdel-'+aid).addClass( 'is-active' );
		jQuery.post(alternative_ajax.ajaxurl, {action: 'delete_post_alternative', aid:aid,  pid: pid}, function(data) {
			
			jQuery( '.altpostdel-'+aid).removeClass( 'is-active' );
			jQuery( '#del-'+aid).remove();
			
			//alert(data);
		});
	});

});