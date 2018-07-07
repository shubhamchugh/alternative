<?php
if(!defined('WSALTERNATIVEPATH')){die("404 Not Found");}

//making the alternative meta box (Note: meta box != custom meta field)
function alternative_add_custom_meta_box() {
   add_meta_box(
       'alternative_custom_meta_box_1',       // $id
       'Alternative Post',                  // $title
       'alternative_post_custom_meta_box_callback',  // $callback
       'alternate',                 // $page
       'normal',                  // $context
       'high'                     // $priority
   );
   add_meta_box(
       'alternative_custom_meta_box_2',       // $id
       'Alternative Information ',                  // $title
       'alternative_prefix_custom_meta_box_callback',  // $callback
       'alternate',                 // $page
       'normal',                  // $context
       'high'                     // $priority
   );
    add_meta_box( 'alternative_image_gallery', __( 'Image Gallery', 'easy-image-gallery' ), 'alternative_image_gallery_metabox', 'alternate', 'side',  'low' );
}
add_action('add_meta_boxes', 'alternative_add_custom_meta_box');

//ALTERNATIVE IMAGE GALLERY META BOX
function alternative_image_gallery_metabox() {
    global $post;
	?>

    <div id="gallery_images_container">

        <ul class="gallery_images">
			<?php
			$image_gallery = get_post_meta( $post->ID, '_wstheme_image_gallery', true );
			$attachments = array_filter( explode( ',', $image_gallery ) );
			if ( $attachments )
			    foreach ( $attachments as $attachment_id ) {
			        echo '<li class="image attachment details" data-attachment_id="' . $attachment_id . '"><div class="attachment-preview"><div class="thumbnail">
			    ' . wp_get_attachment_image( $attachment_id, 'thumbnail' ) . '</div>
			    <a href="#" class="delete check" title="' . __( 'Remove image', 'easy-image-gallery' ) . '"><div class="media-modal-icon"></div></a>

				</div></li>';
			}
			?>
        </ul>


        <input type="hidden" id="image_gallery" name="image_gallery" value="<?php echo esc_attr( $image_gallery ); ?>" />
        <?php wp_nonce_field( basename( __FILE__ ), 'alternative_our_nonce' ); ?>

    </div>

    <p class="add_gallery_images hide-if-no-js"><a href="#"><?php _e( 'Add New Gallery Images', 'wsthemey' ); ?></a></p>

    <script type="text/javascript">
        jQuery(document).ready(function($){

            // Uploading files
            var image_gallery_frame;
            var $image_gallery_ids = $('#image_gallery');
            var $gallery_images = $('#gallery_images_container ul.gallery_images');

            jQuery('.add_gallery_images').on( 'click', 'a', function( event ) {

                var $el = $(this);
                var attachment_ids = $image_gallery_ids.val();

                event.preventDefault();

                // If the media frame already exists, reopen it.
                if ( image_gallery_frame ) {
                    image_gallery_frame.open();
                    return;
                }

                // Create the media frame.
                image_gallery_frame = wp.media.frames.downloadable_file = wp.media({
                    // Set the title of the modal.
                    title: '<?php _e( 'Add Images to Gallery', 'easy-image-gallery' ); ?>',
                    button: {
                        text: '<?php _e( 'Add to gallery', 'easy-image-gallery' ); ?>',
                    },
                    multiple: true
                });

                // When an image is selected, run a callback.
                image_gallery_frame.on( 'select', function() {

                    var selection = image_gallery_frame.state().get('selection');

                    selection.map( function( attachment ) {

                        attachment = attachment.toJSON();

                        if ( attachment.id ) {
                            attachment_ids = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;

                             $gallery_images.append('\
                                <li class="image attachment details" data-attachment_id="' + attachment.id + '">\
                                    <div class="attachment-preview">\
                                        <div class="thumbnail">\
                                            <img src="' + attachment.url + '" />\
                                        </div>\
                                       <a href="#" class="delete check" title="<?php _e( 'Remove image', 'easy-image-gallery' ); ?>"><div class="media-modal-icon"></div></a>\
                                    </div>\
                                </li>');

                        }

                    } );

                    $image_gallery_ids.val( attachment_ids );
                });

                // Finally, open the modal.
                image_gallery_frame.open();
            });

            // Image ordering
            $gallery_images.sortable({
                items: 'li.image',
                cursor: 'move',
                scrollSensitivity:40,
                forcePlaceholderSize: true,
                forceHelperSize: false,
                helper: 'clone',
                opacity: 0.65,
                placeholder: 'eig-metabox-sortable-placeholder',
                start:function(event,ui){
                    ui.item.css('background-color','#f6f6f6');
                },
                stop:function(event,ui){
                    ui.item.removeAttr('style');
                },
                update: function(event, ui) {
                    var attachment_ids = '';

                    $('#gallery_images_container ul li.image').css('cursor','default').each(function() {
                        var attachment_id = jQuery(this).attr( 'data-attachment_id' );
                        attachment_ids = attachment_ids + attachment_id + ',';
                    });

                    $image_gallery_ids.val( attachment_ids );
                }
            });

            // Remove images
            $('#gallery_images_container').on( 'click', 'a.delete', function() {

                $(this).closest('li.image').remove();

                var attachment_ids = '';

                $('#gallery_images_container ul li.image').css('cursor','default').each(function() {
                    var attachment_id = jQuery(this).attr( 'data-attachment_id' );
                    attachment_ids = attachment_ids + attachment_id + ',';
                });

                $image_gallery_ids.val( attachment_ids );

                return false;
            } );

        });
    </script>
    <?php
}

//Alternative post meta box callback
function alternative_post_custom_meta_box_callback() {
    global $post, $wpdb;


	$alt_posts = $wpdb->get_results( "SELECT alt.aid,  alt.alt_post_id, p.post_title FROM {$wpdb->prefix}alternative_post as alt, {$wpdb->prefix}posts p WHERE alt.post_id=".$post->ID." AND alt.alt_post_id=p.ID AND p.post_type='alternate' AND p.post_status='publish'", OBJECT);

    // Use nonce for verification to secure data sending
    wp_nonce_field( basename( __FILE__ ), 'alternative_our_nonce' );

    ?>
    <style type="text/css">

	.full-width {width: 100% !important; padding: 5px;  height: 45px !important;   font-size: 18px;}
	</style>
    <table style="width: 100%">
    	<tr>
    		<th style="width: 200px;" align="left">Search</th>
    		<th style="width: 200px;" align="left">Group</th>
    	</tr>
    	<tr>
    		<td style="width: 200px" align="left">

				<input type="text" id="search_alternative" class="full-width regular-text">
				<span class="spinner altpostsearch"></span>
				<ul id="alternative-search-checklist" class="autocomplete-items"></ul>

	    	</td>

    		<td style="width: 200px" align="left">

				<?php
				$groups = get_terms( array(
				'taxonomy' => 'group',
				'hide_empty' => false
				) );

				if ( !empty($groups) ) :
				$output = '<select class="regular-text full-width" id="alternative-group" style="width:100%;">';
				$output.= '<option value="">Select</option>';
				foreach( $groups as $group ) {
				$output.= '<option value="'. esc_attr( $group->term_id ) .'">
				'. esc_html( $group->name ) .'</option>';
				}
				$output.='</select>';
				echo $output;
				endif;
				?>
				<span class="spinner altgroupsearch"></span>



    		</td>
    	</tr>
    	<tr>
	    	<td>

	    	</td>
    	</tr>
    </table>

	<div id="search-alternative-list" style="display:none; margin-top:20px;"
	 class="ui-widget-content"></div>

	<?php
	if(!empty($alt_posts))
	{
	?>
	<h3><?php _e('All Alternative'); ?> (<?php echo count($alt_posts); ?>)</h3>
	<div style="margin-top:10px;" class="added-alternative-list">

		<input type="hidden" name="alt_post_id" value="<?php if(isset($aid) && !empty($aid))echo $aid; ?>">
		<table class="wp-list-table widefat fixed striped users">
		<thead><tr>
		<th><?php _e('S. No.'); ?></th>
		<th><?php _e('Tital'); ?></th>
		<th><?php _e('Action'); ?></th>
		</tr></thead>
			<?php
			foreach($alt_posts as $key => $alt_post)
			{
				$key++;
			?>
			<tr id="del-<?php echo $alt_post->aid; ?>">
				<td>
					<?php echo $key; ?>
				</td>
				<td>
					<?php echo $alt_post->post_title; ?>
				</td>
				<td>
					<a href="javascript:void(0)" class="alter-delete" data-id="<?php echo $alt_post->aid; ?>" data-pid="<?php echo $post->ID; ?>" onclick="return confirm('<?php _e('Are you sure, you want to delete alternative?'); ?>');" ><?php _e('Delete'); ?><span class="spinner altpostdel altpostdel-<?php echo $alt_post->aid; ?> "></a>

				</td>
			</tr>
			<?php
			}
			?>
		</table>
	</div>
    <?php
	}
}

//Alternative prefix meta box callback
function alternative_prefix_custom_meta_box_callback() {
    global $post;

	$alt_perfix = get_post_meta($post->ID, '_alt_perfix', true);
	$alt_postfix = get_post_meta($post->ID, '_alt_postfix', true);
	$alt_subheading = get_post_meta($post->ID, '_alt_subheading', true);
	$alt_features = get_post_meta($post->ID, '_alt_features', true);
	$links = get_post_meta($post->ID, 'links', true);
	//print_r($links);
    // Use nonce for verification to secure data sending
    wp_nonce_field( basename( __FILE__ ), 'alternative_our_nonce' );

    ?>
    <!-- my custom value input -->

	<div class="inside">
		<table class="meta-table">
			<tr>
				<th>Perfix</th>
				<td><input type="text" class="regular-text" name="alt_perfix" value="<?php echo $alt_perfix; ?>"></td>
				<th class="center-text">Postfix</th>
				<td><input type="text" class="regular-text" name="alt_postfix" value="<?php echo $alt_postfix; ?>"></td>
			</tr>
			<tr><td colspan="4">&nbsp;</td></tr>

			<tr>
				<th>Sub Heading</th>
				<td colspan="3"><input type="text" class="regular-text alt_subheading" name="alt_subheading" value="<?php echo $alt_subheading; ?>"></td>
			</tr>
		<tr><td colspan="4">&nbsp;</td></tr>
			<?php

			$link_array = array('android'=>'Android', 'download'=>'Download', 'facebook'=>'Facebook', 'itune'=>'iTunes',
				'website'=>'Web Site','youtube'=>'You tube','game_streaming'=>'Game Streaming');

			foreach ($link_array as  $value=>$keyvalue) { ?>
				<tr>
					<th><?php echo ucfirst($keyvalue); ?> Label</th>
					<td><input type="text" class="regular-text" name="links[<?php echo $value ?>][label]" value="<?php echo @$links[$value]['label']; ?>"></td>
					<th class="center-text">Link URL</th>
					<td><input type="text" class="regular-text" name="links[<?php echo $value ?>][url]" value="<?php echo @$links[$value]['url']; ?>"></td>
				</tr>
				<tr><td colspan="4">&nbsp;</td></tr>
			<?php
			}
			 ?>
		</table>
	</div>
    <?php
}

//Now we are saving the alternative data
function alternative_save_meta_fields( $post_id ) {

	// verify nonce
	if (!isset($_POST['alternative_our_nonce']) || !wp_verify_nonce($_POST['alternative_our_nonce'], basename(__FILE__)))
		return 'nonce not verified';

	// check autosave
	if ( wp_is_post_autosave( $post_id ) )
		return 'autosave';

	//check post revision
	if ( wp_is_post_revision( $post_id ) )
		return 'revision';

	// check permissions
	if (isset($_POST['post_type']) && $_POST['post_type'] == 'alternate') {

		if ( ! current_user_can( 'edit_page', $post_id ) )
			return 'cannot edit page';

	} elseif ( ! current_user_can( 'edit_post', $post_id ) ) {
		return 'cannot edit post';
	}

	//so our basic checking is done, now we can grab what we've passed from our newly created form
	$alt_perfix = sanitize_text_field($_POST['alt_perfix']);
	$alt_postfix = sanitize_text_field($_POST['alt_postfix']);
	$alt_subheading = sanitize_text_field($_POST['alt_subheading']);

	if(isset($alt_perfix)){
		update_post_meta($post_id, '_alt_perfix', $alt_perfix);
	}
	if(isset($alt_postfix)){
		update_post_meta($post_id, '_alt_postfix', $alt_postfix);
	}
	if(isset($alt_subheading)){
		update_post_meta($post_id, '_alt_subheading', $alt_subheading);
	}

	if(isset($_POST['links'])){
		update_post_meta($post_id, 'links', $_POST['links']);
	}


	//GALLERY META BOX
	if ( isset( $_POST[ 'image_gallery' ] ) && !empty( $_POST[ 'image_gallery' ] ) ) {

        $attachment_ids = sanitize_text_field( $_POST['image_gallery'] );

        // turn comma separated values into array
        $attachment_ids = explode( ',', $attachment_ids );

        // clean the array
        $attachment_ids = array_filter( $attachment_ids  );

        // return back to comma separated list with no trailing comma. This is common when deleting the images
        $attachment_ids =  implode( ',', $attachment_ids );

        update_post_meta( $post_id, '_wstheme_image_gallery', $attachment_ids );
    } else {
        delete_post_meta( $post_id, '_wstheme_image_gallery' );
    }

	//simply we have to save the data alternative post now
	global $wpdb;

	$table = $wpdb->base_prefix . 'alternative_post';

	if(isset($_POST['alt_posts']) && !empty($_POST['alt_posts']))
	{
		$alt_posts = $_POST['alt_posts'];

		foreach($alt_posts as $alt_post)
		{
			$check_alternative = $wpdb->get_var( "SELECT aid FROM {$wpdb->prefix}alternative_post WHERE post_id=".$post_id." AND alt_post_id = ".$alt_post." ");

			if(empty($check_alternative))
			{
				$wpdb->insert(
				$table,
					array(
						'post_id' => $post_id, //as we are having it by default with this function
						'alt_post_id' => $alt_post //assuming we are passing numerical value
					),
					array(
						'%d', //%s - string, %d - integer, %f - float
						'%d', //%s - string, %d - integer, %f - float
					)
				);
			}

			$child_alternative = $wpdb->get_var( "SELECT aid FROM {$wpdb->prefix}alternative_post WHERE post_id=".$alt_post." AND alt_post_id = ".$post_id." ");

			if(empty($child_alternative))
			{
				$wpdb->insert(
				$table,
					array(
						'post_id' => $alt_post, //as we are having it by default with this function
						'alt_post_id' => $post_id //assuming we are passing numerical value
					),
					array(
						'%d', //%s - string, %d - integer, %f - float
						'%d', //%s - string, %d - integer, %f - float
					)
				);
			}
		}
	}

	
}
add_action( 'save_post', 'alternative_save_meta_fields' );


//SEARCH ALTERNATIVE POST
add_action( 'wp_ajax_search_alternative_post', 'alternative_ajax_post_search' );
function alternative_ajax_post_search(){
	global $post, $wpdb;
	$search = $_POST['search'];

	$pid = $_POST['post_id'];

	 $alt_posts = $wpdb->get_results( "SELECT ID, post_title FROM {$wpdb->prefix}posts WHERE post_type='alternate' AND post_status='publish' AND post_title LIKE '%$search%' AND ID !=$pid LIMIT 0,40 ", OBJECT );


	 $items = '';
	 if(!empty($alt_posts)){
		 foreach($alt_posts as $alt_post)
		 {
			 $total_alternative = $wpdb->get_var( "SELECT count(alt.post_id) FROM {$wpdb->prefix}alternative_post as alt WHERE alt.post_id=".$alt_post->ID." AND  alt.alt_post_id != $pid ");

			 $items .= '<li><a class="alt-post" data-pid="'.$alt_post->ID.'">'.$alt_post->post_title.' -  See All Alternative ('.$total_alternative.')</a></li>';
		 }

	 }else{
		 $items .= '<li class"no-result">No results found. </li>';
	 }
	 echo $items;
	 die;
}

//SEARCH POST ALTERNATIVEs
add_action( 'wp_ajax_search_post_alternatives', 'alt_search_post_alternatives' );
function alt_search_post_alternatives(){

	 global $post, $wpdb;
	 $pid = $_POST['pid'];
	 $cpost_id =  $_POST['cpost_id'];
	 $uselect = $_POST['uselect'];
	 $added_alts = $_POST['added_alts'];

	 $data = '';
	 if(!empty($pid)){

	 	$data .=  '<table class="wp-list-table widefat fixed striped alternatives">';
			$data .=  '<thead><tr>
			<th>S. No.</th>
			<th>Tital</th>
			<th> <input type="checkbox"  id="alt-select-all"> Select All  | <a class="clear-all" href="javascript:void(0)"> Clear All</a></th>
			</tr></thead><tbody>';

		$selected_alternatives =array();

		if(isset($added_alts) && !empty($added_alts)){
			$selected_alternatives = explode(',', $added_alts);
			$selected_alternatives [] = $pid;
		}else{
			$selected_alternatives =array($pid);
		}

		$sql = "SELECT DISTINCT(p.ID), p.post_title FROM {$wpdb->prefix}alternative_post as alt, {$wpdb->prefix}posts p WHERE (alt.post_id=".$pid." AND alt.alt_post_id=p.ID AND p.post_type='alternate' AND p.post_status='publish' AND p.ID != '$cpost_id') ";

		if(!empty($selected_alternatives)){

			$search = implode(',', $selected_alternatives);

			$sql .=" || p.ID IN ($search)";
		}

		$sql .=" ORDER BY p.post_title ASC";



		$alt_posts = $wpdb->get_results($sql, OBJECT);

		if(!empty($alt_posts)){
			 foreach($alt_posts as $key => $alt_post){
				 $key++;
				 $data .=  '<tr>
				 <td>'.$key.'</td>
				 <td>'.$alt_post->post_title.'</td>
				 <td><input type="checkbox" class="alt-posts"  value="'.$alt_post->ID.'" name="alt_posts[]"></td>
				 </tr>';
			 }

		}elseif(!empty($pid)){
			$postdata = get_post($pid);
			$data .=  '<tr>
			<td>1</td>
			<td>'.$postdata->post_title.'</td>
			<td><input type="checkbox" class="alt-posts"  value="'.$postdata->ID.'" name="alt_posts[]"></td>
			</tr>';
		 }else{
			$data = '<p style="margin-top:10px;padding: 10px;">No results found.<p>';
		 }
     $data .=  '</tbody></table>';
	 }

	 echo $data;
	 die;
}

//SEARCH GROUP ALTERNATIVEs
add_action( 'wp_ajax_search_group_alternatives', 'alt_search_group_alternatives' );
function alt_search_group_alternatives(){
	 global $post, $wpdb;
	 $tid = $_POST['tid'];
	 $pid = $_POST['post_id'];
	 $added_alts = $_POST['added_alts'];


	$add_alts = '';

	if(isset($added_alts) && !empty($added_alts)){
		$add_alts  = " AND (p.ID IN ($added_alts) OR ( tr.term_taxonomy_id IN ($tid) ))";
	}else{
		$add_alts  = " AND  tr.term_taxonomy_id IN ($tid)  ";
	}


	$permfix = "{$wpdb->prefix}";
	$sql = "SELECT DISTINCT(p.ID), p.post_title FROM {$wpdb->prefix}posts AS p LEFT JOIN {$wpdb->prefix}term_relationships AS tr ON (p.ID = tr.object_id) WHERE 1=1 $add_alts  AND p.post_type = 'alternate' AND p.post_status = 'publish' AND p.ID != '$pid' GROUP BY p.ID ORDER BY p.post_title ASC ";

	$alt_posts = $wpdb->get_results($sql, OBJECT);
	//print_r($alt_posts);



	if(!empty($alt_posts)){
		$data .=  '<table class="wp-list-table widefat fixed striped alternatives">';
			$data .=  '<thead><tr>
			<th>S. No.</th>
			<th>Tital</th>
			<th> <input type="checkbox"  id="alt-select-all"> Select All | <a class="clear-all" href="javascript:void(0)"> Clear All</a></th>
			</tr></thead><tbody>';

		 foreach($alt_posts as $key => $alt_post){
			 $key++;
			 $data .=  '<tr>
			 <td>'.$key.'</td>
			 <td>'.$alt_post->post_title.'</td>
			 <td><input type="checkbox" class="alt-posts"  value="'.$alt_post->ID.'" name="alt_posts[]"></td>
			 </tr>';
		 }

		$data .=  '</tbody></table>';
	 }else{
		$data = '<p style="margin-top:10px;padding: 10px;">No results found.<p>';
	 }

	 echo $data;
	 die;
}

//DELETE POST ALTERNATIVE
add_action( 'wp_ajax_delete_post_alternative', 'delete_post_alternative_callback' );
function delete_post_alternative_callback(){
	global $post, $wpdb;
	$aid = $_POST['aid'];
	$pid = $_POST['pid'];

	$alt_posts = $wpdb->query( "DELETE FROM {$wpdb->prefix}alternative_post WHERE aid =".$aid." AND post_id=".$pid."");
	if($alt_posts) echo 'Delete';
	die;
}
