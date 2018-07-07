<?php 
if(!defined('WSALTERNATIVEPATH')){die("404 Not Found");}

//ADD FILES HERE
require_once ('alernative-meta-box.php');


/*
* WS ALTERNATIVE Plugin activate  Callback
*/
register_activation_hook( __FILE__, 'ws_alternative_activation'); 
function ws_alternative_activation(){
	 register_alternative_init();
	 flush_rewrite_rules();
}

/*
* WS ALTERNATIVE Plugin Deactivate  Callback
*/
register_deactivation_hook( __FILE__, 'ws_alternative_deactivate' ); 
function ws_alternative_deactivate() {

    // Deactivate code here...
}

/*
* WS ALTERNATIVE Plugin Uninstall Callback
*/
register_uninstall_hook(__FILE__, 'ws_alternative_uninstall' );
function ws_alternative_uninstall() {

    // Uninstall code here...
}


/**
 * Enqueue custom scripts and styles for ALTERNATIVES
 */
function alternative_custom_enque_scripts() {
	wp_register_style( 'alternative-front-css', WSALTERNATIVEURL. 'assets/css/alternative-front.css?time='.time(), false, '1.0.0' );
	wp_enqueue_style( 'alternative-front-css' );

	wp_register_script('alternative-front-custome-script', WSALTERNATIVEURL. 'assets/js/front-custom.js?time='.time(), array('jquery'), time(),true  );
	wp_enqueue_script('alternative-front-custome-script' );

}
add_action( 'wp_enqueue_scripts', 'alternative_custom_enque_scripts' );

function alternative_custom_admin_enque_scripts() {
	
	//alternative-admin-css	
	wp_register_style( 'alternative-admin-css', WSALTERNATIVEURL. 'assets/css/alternative-admin.css', false, '1.0.0' );
	wp_enqueue_style( 'alternative-admin-css' );
		
	wp_register_script('alternative-custome-script', WSALTERNATIVEURL. 'assets/js/admin-custom.js?time='.time(), array('jquery'), time(),true  );

	wp_enqueue_script('alternative-custome-script' );

	wp_localize_script( 'alternative-custome-script', 'alternative_ajax',
		array( 
		'ajaxurl' => admin_url( 'admin-ajax.php' ) 
		)
	);
}
add_action( 'admin_enqueue_scripts', 'alternative_custom_admin_enque_scripts' );

/*
* WS ALTERNATIVE POST REGISTER
*/
add_action( 'init', 'register_alternative_init' );
function register_alternative_init() {
	global $wpdb;
	
	//CREATE TABLE ALTERNATE POST 
	//$table_name = $wpdb->prefix . 'alternative_post';
	//$wpdb->query( "DROP TABLE IF EXISTS $table_name" );

    $create_table_query = "
            CREATE TABLE IF NOT EXISTS {$wpdb->prefix}alternative_post (
              aid int(11) NOT NULL AUTO_INCREMENT,
              post_id int(11) NOT NULL,
			  alt_post_id int(11) NOT NULL,
			  PRIMARY KEY  (aid)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
    ";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $create_table_query );
	
	//Register alternative post type.
	$labels = array(
        'name'                  => _x( 'Alternative', 'Post type general name', WS_ALTERNATIVE_TEXT_DOMAIN),
        'singular_name'         => _x( 'Alternate', 'Post type singular name', WS_ALTERNATIVE_TEXT_DOMAIN ),
        'menu_name'             => _x( 'Alternative', 'Admin Menu text', WS_ALTERNATIVE_TEXT_DOMAIN ),
        'name_admin_bar'        => _x( 'Alternate', 'Add New on Toolbar', WS_ALTERNATIVE_TEXT_DOMAIN ),
        'add_new'               => __( 'Add New', WS_ALTERNATIVE_TEXT_DOMAIN),
        'add_new_item'          => __( 'Add New Alternate', WS_ALTERNATIVE_TEXT_DOMAIN ),
        'new_item'              => __( 'New Alternate', WS_ALTERNATIVE_TEXT_DOMAIN ),
        'edit_item'             => __( 'Edit Alternate', WS_ALTERNATIVE_TEXT_DOMAIN ),
        'view_item'             => __( 'View Alternate', WS_ALTERNATIVE_TEXT_DOMAIN ),
        'all_items'             => __( 'All Alternative', WS_ALTERNATIVE_TEXT_DOMAIN ),
        'search_items'          => __( 'Search Alternative', WS_ALTERNATIVE_TEXT_DOMAIN ),
        'parent_item_colon'     => __( 'Parent Alternative:', WS_ALTERNATIVE_TEXT_DOMAIN ),
        'not_found'             => __( 'No Alternative found.', WS_ALTERNATIVE_TEXT_DOMAIN ),
        'not_found_in_trash'    => __( 'No Alternative found in Trash.', WS_ALTERNATIVE_TEXT_DOMAIN ),
        'featured_image' 		=> __( 'Logo' , WS_ALTERNATIVE_TEXT_DOMAIN ),
		'set_featured_image' 	=> __( 'Set Logo', WS_ALTERNATIVE_TEXT_DOMAIN ),
		'remove_featured_image' => __( 'Remove Logo', WS_ALTERNATIVE_TEXT_DOMAIN ),
		'use_featured_image' 	=> __( 'Use as Logo', WS_ALTERNATIVE_TEXT_DOMAIN )
    );
 
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'alternative' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-networking',
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
    );
 
    register_post_type( 'alternate', $args );
	
	//CREATE GROUP TAXONOMY
	$labels = array(
		'name'              => _x( 'Groups', 'taxonomy general name', 'textdomain' ),
		'singular_name'     => _x( 'Group', 'taxonomy singular name', 'textdomain' ),
		'search_items'      => __( 'Search Groups', 'textdomain' ),
		'all_items'         => __( 'All Groups', 'textdomain' ),
		'parent_item'       => __( 'Parent Group', 'textdomain' ),
		'parent_item_colon' => __( 'Parent Group:', 'textdomain' ),
		'edit_item'         => __( 'Edit Group', 'textdomain' ),
		'update_item'       => __( 'Update Group', 'textdomain' ),
		'add_new_item'      => __( 'Add New Group', 'textdomain' ),
		'new_item_name'     => __( 'New Group Name', 'textdomain' ),
		'menu_name'         => __( 'Groups', 'textdomain' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'group' ),
	);
	register_taxonomy( 'group', array( 'alternate' ), $args );
	
	//CREATE PLATEFORM TAXONOMY
	$labels = array(
		'name'              => _x( 'Platforms', 'taxonomy general name', 'textdomain' ),
		'singular_name'     => _x( 'Platform', 'taxonomy singular name', 'textdomain' ),
		'search_items'      => __( 'Search Platforms', 'textdomain' ),
		'all_items'         => __( 'All Platforms', 'textdomain' ),
		'parent_item'       => __( 'Parent Platform', 'textdomain' ),
		'parent_item_colon' => __( 'Parent Platform:', 'textdomain' ),
		'edit_item'         => __( 'Edit Platform', 'textdomain' ),
		'update_item'       => __( 'Update Platform', 'textdomain' ),
		'add_new_item'      => __( 'Add New Platform', 'textdomain' ),
		'new_item_name'     => __( 'New Platform Name', 'textdomain' ),
		'menu_name'         => __( 'Platforms', 'textdomain' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'platform' ),
	);

	register_taxonomy( 'platform', array( 'alternate' ), $args );
	
	//CREATE CATEGORY TAXONOMY
	$labels = array(
		'name'              => _x( 'Categories', 'taxonomy general name', 'textdomain' ),
		'singular_name'     => _x( 'Category', 'taxonomy singular name', 'textdomain' ),
		'search_items'      => __( 'Search Categories', 'textdomain' ),
		'all_items'         => __( 'All Categories', 'textdomain' ),
		'parent_item'       => __( 'Parent Category', 'textdomain' ),
		'parent_item_colon' => __( 'Parent Category:', 'textdomain' ),
		'edit_item'         => __( 'Edit Category', 'textdomain' ),
		'update_item'       => __( 'Update Category', 'textdomain' ),
		'add_new_item'      => __( 'Add New Category', 'textdomain' ),
		'new_item_name'     => __( 'New Category Name', 'textdomain' ),
		'menu_name'         => __( 'Categories', 'textdomain' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'alt-cat' ),
	);

	register_taxonomy( 'alt-cat', array( 'alternate' ), $args );
	
	//CREATE License TAXONOMY
	$labels = array(
		'name'              => _x( 'Licenses', 'taxonomy general name', 'textdomain' ),
		'singular_name'     => _x( 'License', 'taxonomy singular name', 'textdomain' ),
		'search_items'      => __( 'Search Licenses', 'textdomain' ),
		'all_items'         => __( 'All Licenses', 'textdomain' ),
		'parent_item'       => __( 'Parent License', 'textdomain' ),
		'parent_item_colon' => __( 'Parent License:', 'textdomain' ),
		'edit_item'         => __( 'Edit License', 'textdomain' ),
		'update_item'       => __( 'Update License', 'textdomain' ),
		'add_new_item'      => __( 'Add New License', 'textdomain' ),
		'new_item_name'     => __( 'New License Name', 'textdomain' ),
		'menu_name'         => __( 'Licenses', 'textdomain' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'license' ),
	);

	register_taxonomy( 'license', array( 'alternate' ), $args );

	//CREATE ALT TAGS
	$labels = array(
		'name'              => _x( 'Features', 'taxonomy general name', 'textdomain' ),
		'singular_name'     => _x( 'Feature', 'taxonomy singular name', 'textdomain' ),
		'search_items'      => __( 'Search Features', 'textdomain' ),
		'all_items'         => __( 'All Features', 'textdomain' ),
		'parent_item'       => __( 'Parent Feature', 'textdomain' ),
		'parent_item_colon' => __( 'Parent Feature:', 'textdomain' ),
		'edit_item'         => __( 'Edit Feature', 'textdomain' ),
		'update_item'       => __( 'Update Feature', 'textdomain' ),
		'add_new_item'      => __( 'Add New Feature', 'textdomain' ),
		'new_item_name'     => __( 'New Feature Name', 'textdomain' ),
		'menu_name'         => __( 'Features', 'textdomain' ),
	);

	$args = array(
		'hierarchical'      => false,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => false,
		'rewrite'           => array( 'slug' => 'alt_features' ),
	);

	register_taxonomy( 'alt_features', array( 'alternate' ), $args );
}

//CALL SINGLE ALTERNATIVE FILE FROM PLUGIN
function alternate_custom_post_type_template($single_template) {
     global $post;

     if ($post->post_type == 'alternate') {
          $single_template = WSALTERNATIVEPATH . '/single-alternate.php';
     }
     return $single_template;
}
add_filter( 'single_template', 'alternate_custom_post_type_template' );

//ADD ALTERNATIVE POST TITLE PERFIX AND POSTFIX
function alternative_post_title_add_perfix_and_postfix( $title, $id = null ) {
	global $wp_query, $wpdb, $post;

	if (!is_admin() && get_post_type( $id ) == 'alternate' && is_single() && $wp_query->is_main_query()) {

		$global_pid = $wp_query->queried_object->ID;

		if($global_pid==$id ){

			$changed_title = $title;
			
			$post_table = $wpdb->prefix.'posts';
			$alternative_post_table = $wpdb->prefix.'alternative_post';
			$terms_table = $wpdb->prefix.'terms';
			$term_taxonomy_table = $wpdb->prefix.'term_taxonomy';
			$term_relationships_table = $wpdb->prefix.'term_relationships';

			if((isset($_REQUEST['platform']) && !empty($_REQUEST['platform'])) 
				&& (isset($_REQUEST['license']) && !empty($_REQUEST['license']))) 
			{
				$platform = $_REQUEST['platform'];
				$taxonomy_platform = 'platform';
				$license = $_REQUEST['license'];
				$taxonomy_license = 'license';

				$sql = "SELECT 
				count(distinct(p.ID)) as total 
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
				AND trm2.slug='{$license}'";


			}elseif(isset($_REQUEST['platform']) && !empty($_REQUEST['platform'])){
				$platform = $_REQUEST['platform'];
				$taxonomy = 'platform';
				$sql = "SELECT count(distinct(p.ID)) as total FROM {$alternative_post_table} as altp INNER JOIN {$post_table} as p ON p.ID=altp.alt_post_id INNER JOIN {$term_relationships_table} as tr ON tr.object_id=p.ID INNER JOIN {$term_taxonomy_table} as tx ON tx.term_taxonomy_id=tr.term_taxonomy_id INNER JOIN {$terms_table} as trm ON trm.term_id=tx.term_id WHERE altp.post_id='{$id}' AND p.post_type ='alternate' AND p.post_status='publish' AND tx.taxonomy='{$taxonomy}' AND trm.slug='{$platform}'";
			}elseif(isset($_REQUEST['license']) && !empty($_REQUEST['license'])){
				$license = $_REQUEST['license'];
				$taxonomy = 'license';
				$sql = "SELECT count(distinct(p.ID)) as total FROM {$alternative_post_table} as altp INNER JOIN {$post_table} as p ON p.ID=altp.alt_post_id INNER JOIN {$term_relationships_table} as tr ON tr.object_id=p.ID INNER JOIN {$term_taxonomy_table} as tx ON tx.term_taxonomy_id=tr.term_taxonomy_id INNER JOIN {$terms_table} as trm ON trm.term_id=tx.term_id WHERE altp.post_id='{$id}' AND p.post_type ='alternate' AND p.post_status='publish' AND tx.taxonomy='{$taxonomy}' AND trm.slug='{$license}'";
			}else{
				$sql = "SELECT COUNT(DISTINCT(altp.alt_post_id)) as total FROM  {$alternative_post_table} as altp INNER JOIN {$post_table} as p ON p.ID=altp.alt_post_id WHERE altp.post_id={$id} AND p.post_type ='alternate' AND p.post_status='publish'";

			}
			//echo $sql;

			$total = $wpdb->get_var($sql);

			

			$alt_perfix = get_post_meta($id, '_alt_perfix', true);

			if(!empty($alt_perfix)){
				$changed_title = $alt_perfix.' '.$changed_title;
			}

			if(!empty($total)){
				$changed_title = $total.' '.$changed_title;
			}

			$alt_postfix = get_post_meta($id, '_alt_postfix', true);

			if(!empty($alt_postfix)){
				$changed_title = $changed_title.' '.$alt_postfix;
			} 

			if(isset($_REQUEST['platform']) && !empty($_REQUEST['platform'])){
				$term_details = get_term_by('slug', $_REQUEST['platform'], 'platform');

				if(isset($term_details)){

					$changed_title = $changed_title.' for '.$term_details->name;

				}
			}

			if(isset($_REQUEST['license']) && !empty($_REQUEST['license'])){
				$license_details = get_term_by('slug', $_REQUEST['license'], 'license');

				if(isset($license_details)){

					$changed_title = $changed_title.' with '.$license_details->name;

				}
			}

			return $changed_title;
		}
	}    
    return $title;
}
add_filter( 'the_title', 'alternative_post_title_add_perfix_and_postfix', 10, 2 );

function alternative_wp_title_add_perfix_and_postfix( $title, $sep ) {
    global $paged, $page, $wp_query, $wpdb, $post;
 
    $id = $post->ID;
	
	if (!is_admin() && get_post_type( $id ) == 'alternate' && is_single() && $wp_query->is_main_query()) {

		$title = $post->post_title;

		$global_pid = $wp_query->queried_object->ID;

		if($global_pid==$id ){

			$changed_title = $title;
			
			$post_table = $wpdb->prefix.'posts';
			$alternative_post_table = $wpdb->prefix.'alternative_post';
			$terms_table = $wpdb->prefix.'terms';
			$term_taxonomy_table = $wpdb->prefix.'term_taxonomy';
			$term_relationships_table = $wpdb->prefix.'term_relationships';

			if((isset($_REQUEST['platform']) && !empty($_REQUEST['platform'])) 
				&& (isset($_REQUEST['license']) && !empty($_REQUEST['license']))) 
			{
				$platform = $_REQUEST['platform'];
				$taxonomy_platform = 'platform';
				$license = $_REQUEST['license'];
				$taxonomy_license = 'license';

				$sql = "SELECT 
				count(distinct(p.ID)) as total 
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
				AND trm2.slug='{$license}'";


			}elseif(isset($_REQUEST['platform']) && !empty($_REQUEST['platform'])){
				$platform = $_REQUEST['platform'];
				$taxonomy = 'platform';
				$sql = "SELECT count(distinct(p.ID)) as total FROM {$alternative_post_table} as altp INNER JOIN {$post_table} as p ON p.ID=altp.alt_post_id INNER JOIN {$term_relationships_table} as tr ON tr.object_id=p.ID INNER JOIN {$term_taxonomy_table} as tx ON tx.term_taxonomy_id=tr.term_taxonomy_id INNER JOIN {$terms_table} as trm ON trm.term_id=tx.term_id WHERE altp.post_id='{$id}' AND p.post_type ='alternate' AND p.post_status='publish' AND tx.taxonomy='{$taxonomy}' AND trm.slug='{$platform}'";
			}elseif(isset($_REQUEST['license']) && !empty($_REQUEST['license'])){
				$license = $_REQUEST['license'];
				$taxonomy = 'license';
				$sql = "SELECT count(distinct(p.ID)) as total FROM {$alternative_post_table} as altp INNER JOIN {$post_table} as p ON p.ID=altp.alt_post_id INNER JOIN {$term_relationships_table} as tr ON tr.object_id=p.ID INNER JOIN {$term_taxonomy_table} as tx ON tx.term_taxonomy_id=tr.term_taxonomy_id INNER JOIN {$terms_table} as trm ON trm.term_id=tx.term_id WHERE altp.post_id='{$id}' AND p.post_type ='alternate' AND p.post_status='publish' AND tx.taxonomy='{$taxonomy}' AND trm.slug='{$license}'";
			}else{
				$sql = "SELECT COUNT(DISTINCT(altp.alt_post_id)) as total FROM  {$alternative_post_table} as altp INNER JOIN {$post_table} as p ON p.ID=altp.alt_post_id WHERE altp.post_id={$id} AND p.post_type ='alternate' AND p.post_status='publish'";

			}
			//echo $sql;

			$total = $wpdb->get_var($sql);

			

			$alt_perfix = get_post_meta($id, '_alt_perfix', true);

			if(!empty($alt_perfix)){
				$changed_title = $alt_perfix.' '.$changed_title;
			}

			if(!empty($total)){
				$changed_title = $total.' '.$changed_title;
			}

			$alt_postfix = get_post_meta($id, '_alt_postfix', true);

			if(!empty($alt_postfix)){
				$changed_title = $changed_title.' '.$alt_postfix;
			} 

			if(isset($_REQUEST['platform']) && !empty($_REQUEST['platform'])){
				$term_details = get_term_by('slug', $_REQUEST['platform'], 'platform');

				if(isset($term_details)){

					$changed_title = $changed_title.' for '.$term_details->name;

				}
			}

			if(isset($_REQUEST['license']) && !empty($_REQUEST['license'])){
				$license_details = get_term_by('slug', $_REQUEST['license'], 'license');

				if(isset($license_details)){

					$changed_title = $changed_title.' with '.$license_details->name;

				}
			}
			 $site_title = get_bloginfo( 'name' );
			return $changed_title.' - '.$site_title;
		}
	}    
    return $title;
}
add_filter( 'wp_title', 'alternative_wp_title_add_perfix_and_postfix', 99, 2 );


//REMOVE ALTERNATIVE POST TYPE FROM PERMALINK
function alternative_remove_post_type_slug( $post_link, $post, $leavename ) {

    if ( 'alternate' == $post->post_type && 'publish' == $post->post_status ) {
		$post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );
		//$post_link = $post_link.'/';
        return $post_link;
    }
	
    return $post_link;
}
//add_filter( 'post_type_link', 'alternative_remove_post_type_slug', 10, 3 );

//ADD ALTERNATIVE POST TYPE IN QUERY SET
function alternative_post_parse_request( $query ) {

	
	//$query->set( 'platform');
    if ( ! $query->is_main_query() || 2 != count( $query->query ) || ! isset( $query->query['page'] ) ) {
        return;
    }
	
    if ( ! empty( $query->query['name'] ) ) {
		$query->set( 'post_type', array( 'post', 'alternate', 'page' ) );
    }
	
		
}
//add_action( 'pre_get_posts', 'alternative_post_parse_request' );

// Replaces the excerpt "Read More" text by a link
function alternative_excerpt_more( $link ) {
	if ( is_admin() ) {
		return $link;
	}
	global $post;
	if($post->post_type == 'alternate')
	{
		return '...';
	}
	$link = sprintf( '<p class="link-more"><a href="%1$s" class="more-link">%2$s</a></p>',
		esc_url( get_permalink( get_the_ID() ) ),
		/* translators: %s: Name of current post */
		sprintf( __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentyseventeen' ), get_the_title( get_the_ID() ) )
	);
	return ' &hellip; ' . $link;
}
add_filter( 'excerpt_more', 'alternative_excerpt_more', 99 );

function alter_the_query( $request ) {
	  
  $urlparam = explode('/', $_SERVER['REQUEST_URI']);
	 print_r($urlparam);	

    // this is the actual manipulation; do whatever you need here
    
	//if(is_single($urlparam[2])){
		 //echo $urlparam[2];
		 unset( $request['attachment']);		 
		 $request['page'] = '';
		 $request['name'] = $urlparam[2];
		 //$request['platform'] = $urlparam[3];		 
	//}	
    return $request;
}
//add_filter( 'request', 'alter_the_query' );


function get_total_alternative_by_term($alternate_id,$taxonomy,$term_id){
	
	global $wpdb; 

	$post_table = $wpdb->prefix.'posts';
	$alternative_post_table = $wpdb->prefix.'alternative_post';
	$terms_table = $wpdb->prefix.'terms';
	$term_taxonomy_table = $wpdb->prefix.'term_taxonomy';
	$term_relationships_table = $wpdb->prefix.'term_relationships';

	$sql = "SELECT count(distinct(p.ID)) as total FROM {$alternative_post_table} as altp INNER JOIN {$post_table} as p ON p.ID=altp.alt_post_id INNER JOIN {$term_relationships_table} as tr ON tr.object_id=p.ID INNER JOIN {$term_taxonomy_table} as tx ON tx.term_taxonomy_id=tr.term_taxonomy_id INNER JOIN {$terms_table} as trm ON trm.term_id=tx.term_id WHERE altp.post_id='{$alternate_id}' AND p.post_type ='alternate' AND p.post_status='publish' AND tx.taxonomy='{$taxonomy}' AND trm.term_id='$term_id'";

	return $total = $wpdb->get_var($sql);
}


add_action('wp_head', 'alternative_add_meta_index');
function alternative_add_meta_index()
{
	global $post;
	if(!is_admin() && get_post_type($post->ID ) == 'alternate' && is_single() && isset($_REQUEST['platform'])
	 && !empty($_REQUEST['platform']) && isset($_REQUEST['license']) && !empty($_REQUEST['license'])) 
	{
		echo '<meta name="robots" content="noindex, nofollow" />';
	}
}

function alternative_post_thumbnail_html( $html ) {
	global $post;
    // If there is no post thumbnail,
    // Return a default image
    if ( '' == $html && get_post_type($post->ID )) {
        return '<img src="' . WSALTERNATIVEURL. '/assets/images/no_logo.png" width="150px" height="100px" class="image-size-name" />';
    }
    // Else, return the post thumbnail
    return $html;
}
add_filter( 'post_thumbnail_html', 'alternative_post_thumbnail_html' );



add_action('platform_add_form_fields','alt_pf_category_edit_form_fields');
add_action('platform_edit_form_fields','alt_pf_category_edit_form_fields');
function alt_pf_category_edit_form_fields ($tobject) {
   // Read in the order from the options db
 
  


   if(isset($_GET['tag_ID']) && !empty($_GET['tag_ID']))
   {
   	 	$platform_icon = '';
	   if(isset($tobject) && !empty($tobject)){
	   		$platform_icon = get_term_meta($tobject->term_id, "platform_icon", true);
	   }

	?>

	<tr class="form-field term-slug-wrap">
		<th scope="row"><label for="slug"><?php _e('Platform icon', ''); ?></label></th>
			<td>
					<?php
					$content = $platform_icon;
					$editor_id = 'platform_icon';
					$settings = array( 'media_buttons' => false );
					wp_editor( $content, $editor_id, $settings );
					?>			
		</td>
	</tr>

	<?php }else{ ?>

	<div class="form-field term-platform-wrap">
		<label for="tag-platform"><?php _e('Icon', ''); ?></label>
		<textarea name="platform_icon" id="tag-platform_icon" rows="5" cols="40"></textarea>
		<p>Add your platform icon.</p>
	</div>

	<?php
	}
}


// save extra platform extra fields hook
add_action('edited_platform', 'alt_pf_category_save_form_fields', 10, 2);
add_action('created_platform', 'alt_pf_category_save_form_fields', 10, 2);
// save extra category extra fields callback function
function alt_pf_category_save_form_fields( $term_id ) {
    if ( isset( $_POST['platform_icon'] ) ) {
           
       //save the option array
       update_term_meta($term_id, "platform_icon", $_POST['platform_icon'] );
    }
}


function gd_rating_function ($post){
	global $post, $_gdrts_addon_posts;

	$content = '';
	if(isset($_gdrts_addon_posts)){

		$item = gdrts_get_rating_item_by_post($post);
	
	    if ($item !== false) {
	        $post_type = $post->post_type;
	        $location = $item->get('posts-integration_location', 'default');
	        $method = $item->get('posts-integration_method', 'default');

	        if ($location == 'default') {
	            $location = $_gdrts_addon_posts->get($post_type.'_auto_embed_location');
	        }

	        if ($method == 'default') {
	            $method = $_gdrts_addon_posts->get($post_type.'_auto_embed_method');
	        }

	        $location = apply_filters('gdrts_posts_auto_embed_location', $location);
	        $_method = apply_filters('gdrts_posts_auto_embed_method', $method);
	        $_parts = explode('::', $_method, 2);
	        $method = $_parts[0];
	        $series = null;

	        if (isset($_parts[1])) {
	            $series = $_parts[1];
	        }

	        if (gdrts_is_method_loaded($method)) {
	            if (!empty($location) && is_string($location) && in_array($location, array('top', 'bottom', 'both'))) {
	                $rating = gdrts_posts_render_rating(array(
	                    'name' => $post_type, 
	                    'id' => $post->ID, 
	                    'method' => $method, 
	                    'series' => $series
	                ));

	                if ($location == 'top' || $location == 'both') {
	                    $content = $rating.$content;
	                }

	                if ($location == 'bottom' || $location == 'both') {
	                    $content.= $rating;
	                }
	            }
	        }
	    }
	}
	
    return $content;
}


add_action( 'before_delete_post', 'alt_before_delete_post' );
function alt_before_delete_post( $postid ){

    // We check if the global post type isn't ours and just return
    global $post_type;   
    
    if ( $post_type != 'alternate' ) return;

    global $wpdb; 
  
	$alternative_post_table = $wpdb->prefix.'alternative_post';
	$wpdb->delete( $alternative_post_table, array( 'post_id' => $postid ), array( '%d' ) );

}



