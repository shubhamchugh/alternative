<?php
/**
 * Template part for displaying alternavite posts
 *
 */
 global $post, $wpdb;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php 
	//GET POST ALTERNATIVE POST HERE.
	
	$post_table = $wpdb->prefix.'posts';
	$alternative_post_table = $wpdb->prefix.'alternative_post';
	$terms_table = $wpdb->prefix.'terms';
	$term_taxonomy_table = $wpdb->prefix.'term_taxonomy';
	$term_relationships_table = $wpdb->prefix.'term_relationships';

	$check_rating_sql = "SHOW TABLES LIKE '{$wpdb->prefix}gdrts_items'";
	$check_rating_table = $wpdb->get_var($check_rating_sql);
	$gd_rating_sql = '';
	$order_by_sql = '';
	if(isset($check_rating_table) && !empty($check_rating_table)){
		$gd_rating_sql = " , (SELECT itemmeta.meta_value as total_rating FROM {$wpdb->prefix}gdrts_items as item INNER JOIN {$wpdb->prefix}gdrts_itemmeta as itemmeta On item.item_id=itemmeta.item_id WHERE itemmeta.meta_key='stars-rating_rating' AND p.ID = item.id) AS rating, (SELECT itemmeta.meta_value as total_rating FROM {$wpdb->prefix}gdrts_items as item INNER JOIN {$wpdb->prefix}gdrts_itemmeta as itemmeta On item.item_id=itemmeta.item_id WHERE itemmeta.meta_key='stars-rating_votes' AND p.ID = item.id) AS rating_votes	";
		$order_by_sql = "ORDER BY rating DESC, rating_votes DESC";
	}else{
		$order_by_sql = ' ORDER BY p.ID DESC';
	}
	
	
	if((isset($_REQUEST['platform']) && !empty($_REQUEST['platform'])) && (isset($_REQUEST['license']) && !empty($_REQUEST['license']))) 
	{
		$platform = $_REQUEST['platform'];
		$taxonomy_platform = 'platform';
		$license = $_REQUEST['license'];
		$taxonomy_license = 'license';

		$sql = "SELECT 
		p.* 
		{$gd_rating_sql}
		FROM 
		{$alternative_post_table} as altp, 
		{$post_table} as p, 
		{$term_relationships_table} as tr, 
		{$term_taxonomy_table} as tx, 
		{$terms_table} as trm, 
		{$term_relationships_table} as tr2, 
		{$term_taxonomy_table} as tx2, 
		{$terms_table} as trm2

		WHERE 
		p.ID=altp.alt_post_id
		AND tr.object_id=p.ID
		AND tx.term_taxonomy_id=tr.term_taxonomy_id 
		AND trm.term_id=tx.term_id
		AND tr2.object_id=p.ID
		AND tx2.term_taxonomy_id=tr2.term_taxonomy_id 
		AND trm2.term_id=tx2.term_id
		AND altp.post_id='{$id}' 
		AND p.post_type ='alternate' 
		AND p.post_status='publish' 
		AND tx.taxonomy='{$taxonomy_platform}' 
		AND tx2.taxonomy='{$taxonomy_license}'
		AND trm.slug='{$platform}' 
		AND trm2.slug='{$license}'
		$order_by_sql
		";


	}elseif(isset($_REQUEST['platform']) && !empty($_REQUEST['platform'])){

		$platform = $_REQUEST['platform'];
		$taxonomy = 'platform';
		$sql = "SELECT p.* {$gd_rating_sql} FROM {$alternative_post_table} as altp INNER JOIN {$post_table} as p ON p.ID=altp.alt_post_id INNER JOIN {$term_relationships_table} as tr ON tr.object_id=p.ID INNER JOIN {$term_taxonomy_table} as tx ON tx.term_taxonomy_id=tr.term_taxonomy_id INNER JOIN {$terms_table} as trm ON trm.term_id=tx.term_id WHERE altp.post_id='{$id}' AND p.post_type ='alternate' AND p.post_status='publish' AND tx.taxonomy='{$taxonomy}' AND trm.slug='{$platform}' $order_by_sql";

	}elseif(isset($_REQUEST['license']) && !empty($_REQUEST['license'])){

		$license = $_REQUEST['license'];
		$taxonomy = 'license';
		$sql = "SELECT p.* {$gd_rating_sql} FROM {$alternative_post_table} as altp INNER JOIN {$post_table} as p ON p.ID=altp.alt_post_id INNER JOIN {$term_relationships_table} as tr ON tr.object_id=p.ID INNER JOIN {$term_taxonomy_table} as tx ON tx.term_taxonomy_id=tr.term_taxonomy_id INNER JOIN {$terms_table} as trm ON trm.term_id=tx.term_id WHERE altp.post_id='{$id}' AND p.post_type ='alternate' AND p.post_status='publish' AND tx.taxonomy='{$taxonomy}' AND trm.slug='{$license}' $order_by_sql ";
	}else{
		 $sql = "SELECT p.*  {$gd_rating_sql} FROM  {$alternative_post_table} as altp INNER JOIN {$post_table} as p ON p.ID=altp.alt_post_id WHERE altp.post_id={$id} AND p.post_type ='alternate' AND p.post_status='publish' GROUP BY p.ID   $order_by_sql ";

	}

	//echo $sql;
	

	$posts = $wpdb->get_results($sql);

	
	if(!empty($posts))
	{
	?>
		<div class="list-content">
			<?php $i=1; 

			foreach ($posts as  $post):

			
				setup_postdata($post);

				global $post;
				
				$alt_perfix = get_post_meta($post->ID, '_alt_perfix', true);
				$alt_postfix = get_post_meta($post->ID, '_alt_postfix', true);
				
				$check_alt_post = $wpdb->get_var( "SELECT alt_post_id FROM {$wpdb->prefix}alternative_post WHERE post_id = ".$post->ID." ");		
				
				?>
				<article class="list-entry td-pb-span12">

					<div class="list-entry td-pb-span2 left-section desktop-view">
						<div class="list-entry-head-icon alternate-logo-image">
							<a href="<?php the_permalink() ?>"><?php 
							the_post_thumbnail('full'); 
							?></a>
						</div>
						<?php 
						$alt_features = wp_get_post_terms($post->ID, 'alt_features', array("fields" => "all"));
						if(!empty($alt_features)){
							echo "<ul class='alt-features-list'>";
							foreach($alt_features as $feature) {
								echo '<li class="alt-feature-item">'.$feature->name.'</li>'; //do something here
							}
								echo "</ul>";
						}							
						?>
					</div>

					<div class="list-entry td-pb-span10 right-section">
						<div class="list-entry-head"> 
							<div class="list-entry-head-title">
								<h2> <a href="<?php the_permalink() ?>"> <div class="mobile-view img-mobile"><?php the_post_thumbnail('thumbnail'); ?></div> <?php the_title(); ?> </a></h2>
								<div class="alt-rating"><?php echo gd_rating_function($post);?></div>
								<div class="mobile-view mobile-features">
								<?php
									if(!empty($alt_features)){
										echo "<ul class='alt-features-list'>";
										foreach($alt_features as $feature) {
										echo '<li class="alt-feature-item">'.$feature->name.'</li>'; //do something here
										}
										echo "</ul>";
									}
									?>
								</div>	
							</div>
						</div>
						<div class="list-entry-content">
							<div class="list-entry-thumbnail alternate-image">		 
							<?php 
							$image_gallery = get_post_meta( $post->ID, '_wstheme_image_gallery', true );

							if(!empty($image_gallery)){

								$attachments = array_filter( explode( ',', $image_gallery ) );  

								if(isset( $attachments['0']) && !empty($attachments['0'])){
									$attachment_id = $attachments['0']; 
									echo '<a href="'.get_the_permalink().'"><img src="'.wp_get_attachment_image_url($attachment_id, 'full' ).'" /> </a>';   
								}  
							}
							?>
							<?php 
							
							$links = get_post_meta($post->ID, 'links', true); 
							if(!empty($links)){
								
								echo "<ul class='alt-link-list'>"; 
								$counter=0;
								foreach($links as $key => $link) { 
									
									if(isset($link['label']) && isset($link['url']) && !empty($link['url'])){

										$label = $link['label'];

									    $url = $link['url'];
										
										$icon = '';
										if($key == 'website'){
											$icon = '<i class="fa fa-globe"></i>';
										}elseif($key == 'youtube'){
											$icon = '<i class="fa fa-youtube-play" aria-hidden="true"></i>';
										}elseif($key == 'android'){
											$icon = '<i class="fa fa-android" aria-hidden="true"></i>';
										}elseif($key == 'download'){
											$icon = '<i class="fa fa-download" aria-hidden="true"></i>';
										}elseif($key == 'facebook'){
											$icon = '<i class="fa fa-facebook-square" aria-hidden="true"></i>';
										}elseif($key == 'itune'){
											$icon = '<i class="fa fa-apple" aria-hidden="true"></i>';
										}elseif($key == 'game_streaming'){
				                            $icon = '<i class="fa fa-steam-square" aria-hidden="true"></i>';
				                        }

										echo '<li class="alt-link-item"><a href="'.$url.'" target="_blank" class="alt-link-item '.$key.'-icon" > '.$icon.'</a></li>';
										
									}	
								
								
								}
								echo "</ul>";
							}	 

							?>
										
							</div>
							
							<?php 

							$post_licenses = wp_get_post_terms($post->ID, 'license', array("fields" => "all"));

							
							 if(!empty($post_licenses)){
								echo "<ul class='alt-platform-list'>";
								foreach($post_licenses as $license) {  
									echo '<li class="alt-license-item">'.$license->name.'</li>';  
								}
								echo "</ul>";
							}	 

							$post_platforms = wp_get_post_terms($post->ID, 'platform', array("fields" => "all")); 

							if(!empty($post_platforms)){
								echo "<ul class='alt-platform-list'>";
								foreach($post_platforms as $platform) { 
									$get_platform_icon = get_term_meta($platform->term_id, "platform_icon", true);  
									$platform_icon = '';
									if(isset($get_platform_icon) && !empty($get_platform_icon)){
										$platform_icon = $get_platform_icon.'&nbsp;';
									}
									echo '<li class="alt-platform-item">'.$platform_icon.$platform->name.'</li>';  
								}
								echo "</ul>";
							}	 
							 $content = $post->post_excerpt == '' ? $post->post_content : $post->post_excerpt;
							 $word_limit = 250;
							 

 							?>			
							<div class="list-entry-description alt-excerpt"><p><?php echo wp_html_excerpt($content, $word_limit); ?>...</p></div>	
							
						</div>
					</div>
				</article>
				 
			<?php $i++;
			 endforeach; 
			 wp_reset_postdata(); ?>
		</div>
	<?php } ?>
</article><!-- #post-## -->
