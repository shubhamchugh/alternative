<?php
/**
 * Template part for displaying posts
 *
  */
 global $post, $wpdb;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		
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
		 
				
							?>

	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->

	<?php 	
	$post_table = $wpdb->prefix.'posts';
	$alternative_post_table = $wpdb->prefix.'alternative_post';
	$terms_table = $wpdb->prefix.'terms';
	$term_taxonomy_table = $wpdb->prefix.'term_taxonomy';
	$term_relationships_table = $wpdb->prefix.'term_relationships';

	//GET POST ALTERNATIVE POST HERE.
	$count_posts = $wpdb->get_var( "SELECT count(alt_post_id) FROM {$alternative_post_table} AS altp INNER JOIN {$post_table} as p ON p.ID = altp.alt_post_id  WHERE post_id = ".$post->ID." AND p.post_type ='alternate' AND p.post_status='publish' ");

	$alternate_id = $post->ID;
	$taxonomy = 'platform';

	$sql = "SELECT distinct(trm.term_id),trm.name,trm.slug FROM {$alternative_post_table} as altp INNER JOIN {$post_table} as p ON p.ID=altp.alt_post_id INNER JOIN {$term_relationships_table} as tr ON tr.object_id=p.ID INNER JOIN {$term_taxonomy_table} as tx ON tx.term_taxonomy_id=tr.term_taxonomy_id INNER JOIN {$terms_table} as trm ON trm.term_id=tx.term_id WHERE altp.post_id='{$alternate_id}' AND p.post_type ='alternate' AND p.post_status='publish' AND tx.taxonomy='{$taxonomy}'";

	$platform_terms = $wpdb->get_results( $sql);
	//print_r( $platform_terms );
	$pname = '';
	if (isset($_REQUEST['platform']) && !empty($_REQUEST['platform'])) {
		$platform_details = get_term_by('slug', $_REQUEST['platform'], 'platform');
		$pname = $platform_details->name;
	}
	?>
	<div class="list-platform-filters">
		<h2><div class="platform-filters-text"> Alternatives to <?php echo $post->post_title; ?> for </div>
		<div class="platform-filters-dropdown">
			<div class="platform-dropdown-button"><?php if(!empty($pname)){  echo $pname; }else{ echo 'all platform'; } ?> <span class="platform-dropdown-caret"></span></div>
			<div class="platform-dropdown-content" style="display: none;">
				<a href="<?php echo get_permalink($post->ID); ?>" title="<?php the_title(); ?>"><span class=""></span> All (<?php echo $count_posts; ?>)</a>
				<?php 
				if(!empty($platform_terms))
				{ 
					foreach ($platform_terms as $key => $platform_term) 
					{
						$plink = '';
						if(isset($_GET['license']) && !empty($_GET['license']))
						{
							$plink = add_query_arg(array('platform' => $platform_term->slug, 'license' => $_GET['license']), get_permalink($post->ID));
						}else{
							$plink = add_query_arg('platform',  $platform_term->slug, get_permalink($post->ID));
						}
						?>
						<a href="<?php echo $plink;  ?>" title="<?php the_title(); ?> for <?php echo $platform_term->name; ?>"><span class="icon-android"></span> <?php echo $platform_term->name; ?> (<?php echo get_total_alternative_by_term($alternate_id,$taxonomy,$platform_term->term_id); ?>)</a>
					<?php 
					}				
				} 
				?>				
			</div>
		</div>


		With 
		
		<?php 
		
		$taxonomy = 'license';
		$sql = "SELECT distinct(trm.term_id),trm.name,trm.slug FROM {$alternative_post_table} as altp INNER JOIN {$post_table} as p ON p.ID=altp.alt_post_id INNER JOIN {$term_relationships_table} as tr ON tr.object_id=p.ID INNER JOIN {$term_taxonomy_table} as tx ON tx.term_taxonomy_id=tr.term_taxonomy_id INNER JOIN {$terms_table} as trm ON trm.term_id=tx.term_id WHERE altp.post_id='{$alternate_id}' AND p.post_type ='alternate' AND p.post_status='publish' AND tx.taxonomy='{$taxonomy}'";

		$license_terms = $wpdb->get_results( $sql);

		$lname = '';
		if (isset($_REQUEST['license']) && !empty($_REQUEST['license'])) {
			$license_details = get_term_by('slug', $_REQUEST['license'], 'license');
			$lname = $license_details->name;
		}
		//print_r( $license_terms );
		?>
		<div class="license-filters-dropdown">
			<div class="license-dropdown-button"><?php if(!empty($lname)){  echo $lname; }else{ echo 'any lincense'; } ?> <span class="license-dropdown-caret"></span></div>
			<div class="license-dropdown-content" style="display: none;">
				<a href="<?php echo get_permalink($post->ID); ?>" title="<?php the_title(); ?>"><span class=""></span> All (<?php echo $count_posts; ?>)</a>
				<?php 
				if(!empty($license_terms))
				{ 
					foreach ($license_terms as $key => $license_term) 
					{
						$llink = '';
						if(isset($_GET['platform']) && !empty($_GET['platform']))
						{
							$llink = add_query_arg(array('license' => $license_term->slug, 'platform' => $_GET['platform']), get_permalink($post->ID));
						}else{
							$llink = add_query_arg('license',  $license_term->slug, get_permalink($post->ID));
						}
						?>
						<a href="<?php echo $llink; ?>" title="<?php the_title(); ?> for <?php echo $license_term->name; ?>"><span class="icon-android"></span> <?php echo $license_term->name; ?> (<?php echo get_total_alternative_by_term($alternate_id,$taxonomy,$license_term->term_id); ?>)</a>
					<?php 
					}				
				} 
				?>
			</div> 
		</div>
		</h2>
	</div>	
</article><!-- #post-## -->
