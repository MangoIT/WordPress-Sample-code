<?php
/*
 * @package Original
 * @version 1.0
 * Plugin Name: Original
 * Description: It will allow admin to manage content for original section 
 * Author: 
 * Plugin URI:  http://widewalls.ch/
 * Version: 1.0
*/
ob_start();
require_once('macros.php');
add_action('generate_rewrite_rules', 'original_work_list',100);
function original_work_list($wp_rewrite) {
	
	$reg_exps = array(
					'category/(.+?)/?$'
					,'(.+?)(/[0-9]+)?/?$'
					,'(.?.+?)(/[0-9]+)?/?$'
					,'[^/]+/([^/]+)/?$'
				);	
	$temp_rule = array();
	foreach($reg_exps as $exps){
		$temp_rule[$exps] = $wp_rewrite->rules[$exps];
		unset($wp_rewrite->rules[$exps]);
	}
	//------------------------------------------------------

	$wp_rewrite->rules['original/([^/]+)/?$'] = 'index.php?pagename='.sanitize_title(PAGE_TITLE_ORIGINAL_ARTIST).'&'.ORIGINAL_QUERY_VAR_ARTISTSLUG.'=$matches[1]';
	
	//------------------------------------------------------
	foreach($temp_rule as $key => $rule){
		$wp_rewrite->rules[$key] = $rule;
	}
}

add_action( 'init', 'original_create_post_type' );
function original_create_post_type() {
	register_post_type( ORIGINAL_POST_TYPE,
		array(
			'labels' => array(
				'name' => __( ORIGINAL_POST_TYPE_LABEL ),
				'singular_name' => __( ORIGINAL_POST_TYPE_LABEL_SINGULAR )
			),
			'singular_label' => ORIGINAL_POST_TYPE_LABEL_SINGULAR,
		'public' => true,
		 'supports' => array(
					'title',
					'editor',
					'comments',
					'thumbnail'
				),
		'has_archive' => true,
		'menu_icon' => WP_PLUGIN_URL.'/Original/images/original-16.png' ,
		'rewrite' => true
		)
	);
}

add_action( 'init', 'original_build_taxonomies',0 );
function original_build_taxonomies() {
	register_taxonomy( 'original-category', ORIGINAL_POST_TYPE, array( 'hierarchical' => true, 'label' => 'Manage Category', 'query_var' => true, 'rewrite' => true ) );
	register_taxonomy( 'original-tag', ORIGINAL_POST_TYPE, array( 'label' => 'Manage Tags', 'query_var' => true, 'rewrite' => true ) );
}


// Change the columns for the edit CPT screen
function original_change_columns( $cols ) {
  $cols = array(
    'cb'       => '<input type="checkbox" />',
    'title'     =>  __( ORIGINAL_ADMIN_COLUMN_TITLE,      'trans' ),
    'Categories'      => __( ORIGINAL_ADMIN_COLUMN_CATEGORY,      'trans' )
  );
  return $cols;
}
add_filter( "manage_".ORIGINAL_POST_TYPE."_posts_columns", "original_change_columns");

function original_custom_columns( $column, $post_id ) {
  switch ( $column ) {
    case ORIGINAL_ADMIN_COLUMN_CATEGORY :
		$cat_array = array();
		$categories = wp_get_post_terms($post_id, 'original-category');
		foreach($categories as $category){
			$cat_array[] = $category->name;
		}
		if(!empty($cat_array)){
			echo implode(', ',$cat_array);
	    }
    break;
 }
 
}

add_action( "manage_posts_custom_column", "original_custom_columns", 10, 2 );
// Make these columns sortable
function original_sortable_columns() {
  return array(
    'title' => 'title'
  );
}

add_filter( "manage_edit-".ORIGINAL_POST_TYPE."_sortable_columns", "original_sortable_columns" );

add_action( 'admin_init', 'original_dt_custom_fields' );
function original_dt_custom_fields() {
	$post_types = get_post_types( array('public' => true,'exclude_from_search' => false) );
	foreach($post_types as $post_type) {
		if($post_type=ORIGINAL_POST_TYPE){
			add_meta_box( 'dt-custom-fields_creation_year',ORIGINAL_METABOX_HEADER_CREATION_YEAR, 'original_metabox_creation_year', $post_type, 'normal', 'high' );
			add_meta_box( 'dt-custom-fields_marks',ORIGINAL_METABOX_HEADER_MARKS, 'original_metabox_marks', $post_type, 'normal', 'high' );
			add_meta_box( 'dt-custom-fields_medium',ORIGINAL_METABOX_HEADER_MEDIUM, 'original_metabox_medium', $post_type, 'normal', 'high' );
			add_meta_box( 'dt-custom-fields_artist_sugg', ORIGINAL_METABOX_HEADER_ARTIST, 'original_metabox_artist_sugg', $post_type, 'normal', 'high' );
			
			add_meta_box( 'dt-custom-fields_dimensions',ORIGINAL_METABOX_HEADER_DIMENSIONS, 'original_metabox_dimensions', $post_type, 'normal', 'high');
			
			add_meta_box( 'dt-custom-fields_auction_result',ORIGINAL_METABOX_HEADER_AUCTION, 'original_metabox_auction_result', $post_type, 'normal', 'high');
		}
	}
}

function original_metabox_dimensions() {
global $post;
	$dimension = get_post_meta($post->ID,"original_dimension",true);
	$dimension_unit = get_post_meta($post->ID,"original_dimension_unit",true);
	$units = array(ORIGINAL_METABOX_DIMENSIONS_INCHES_SHORT => ORIGINAL_METABOX_DIMENSIONS_INCHES_FULL,
					ORIGINAL_METABOX_DIMENSIONS_CENTIMETER_SHORT => ORIGINAL_METABOX_DIMENSIONS_CENTIMETER_FULL);
	?>
	<label class = "default-field" ><?php _e(ORIGINAL_METABOX_LABEL_DIMENSIONS); ?> : </label>
	<input type="text" name="original_dimension" id="original_dimension" value="<?php echo $dimension; ?>" />
	<select id = "original_dimension_unit" class="large-text field-align" name = "original_dimension_unit">
		<?php 
			foreach($units as $key => $unit){
				?>
					<option <?php echo ($key == $dimension_unit)?'selected':''; ?> value = "<?php _e($key); ?>"><?php _e($unit); ?></option>
				<?php
			}
		?>
	</select>
	<label for="original_dimension" generated="true" class="error"></label>
	<?php
}

function original_metabox_creation_year() {
global $post;
	$creation_year = get_post_meta($post->ID,"original_creation_year",true);
	?>
	<label class = "default-field" ><?php _e(ORIGINAL_METABOX_CREATION_YEAR_LABEL); ?> : </label>
	<input type="text" name="original_creation_year" id="original_creation_year" value="<?php echo $creation_year; ?>" />
	<?php
}

function original_metabox_artist_sugg(){
	global $post,$wpdb;
	$selected_artists = array();
	$posts = $wpdb->prefix.'posts';
	$post_to_artist = $wpdb->prefix . "post_to_artist"; 
	
	$query = "select `ID`,`post_title` from ".$posts." where `post_type` = 'artist' and `post_status` = 'publish'";
	$query_selected_artist = "select `artist_id` from ".$post_to_artist." where `post_id` = ".$post->ID;
	
	$selected_artists_result = $wpdb->get_results($query_selected_artist,ARRAY_A);
	foreach($selected_artists_result as $artist){
		$selected_artists[] = $artist['artist_id'];
	}
	$artists = $wpdb->get_results($query,ARRAY_A);
?>
		<label class = "default-field"><?php _e(ORIGINAL_METABOX_ARTIST_LABEL); ?> :</label>
		<select multiple id = "artist_sugg" class="large-text field-align" name = "artist_sugg[]">
			<?php 
				foreach($artists as $artist){
					?>
						<option <?php echo (in_array($artist['ID'],$selected_artists))?'selected':''; ?> value = "<?php _e($artist['ID']); ?>"><?php _e($artist['post_title']); ?></option>
					<?php
				}
			?>
		</select>
<?php 

}

function original_metabox_marks(){
	global $post;
	$marks = get_post_meta($post->ID,"original_marks",true);
	?>
	<label class = "default-field" ><?php _e(ORIGINAL_METABOX_MARKS_LABEL); ?> : </label>
	<input type="text" name="original_marks" id="original_marks" value="<?php echo $marks; ?>" />
	<?php
}

function original_metabox_medium(){
	global $post;
	$medium = get_post_meta($post->ID,"original_medium",true);
	?>
	<label class = "default-field" ><?php _e(ORIGINAL_METABOX_MEDIUM_LABEL); ?> : </label>
	<input type="text" name="original_medium" id="original_medium" value="<?php echo $medium; ?>" />
	<?php
}

function original_metabox_auction_result(){
	global $post,$wpdb;
	$auction = $wpdb->prefix."auction";
	$auction_results = $wpdb->get_results("SELECT `hammer_price`,`low_estimate`,`high_estimate`,`sales_date`,`auction_house`,`lot_number`,`category` FROM $auction WHERE `post_id` = ".$post->ID,ARRAY_A);

	$count = empty($auction_results)?1:count($auction_results); 
	//$remove_button_style = ($count < 2)?'display:none':'';
	$remove_button_style = '';
	for($i = 0;$i <= $count;$i++){
		$container_style = "";
		if($i == 0){
			$container_style = "display:none;";
		}
	?>
		<div id = "auction<?php _e($i); ?>_container" style = "<?php _e($container_style); ?>">
			<div>
				<!--<h2>Auction <?php _e($i); ?></h2>-->
				<input type="button" class="remove_auction_<?php _e($i); ?>" value="X" style = "<?php _e($remove_button_style); ?>" />
				<input type="hidden" class="auction_no" value="<?php _e($i); ?>" />
			</div>
			<label class = "default-field" ><?php _e(ORIGINAL_METABOX_AUCTION_HAMMER_PRICE_LABEL.' ('.ORIGINAL_CURRENCY.')'); ?> :</label>
			<input type="text" name="original_auction<?php _e($i); ?>_hammer_price" id="original_auction<?php _e($i); ?>_hammer_price" value="<?php _e($auction_results[$i-1]['hammer_price']); ?>" />
			<br />
			
			<label class = "default-field" ><?php _e(ORIGINAL_METABOX_AUCTION_LOW_ESTIMATE_LABEL.' ('.ORIGINAL_CURRENCY.')'); ?> : </label>
			<input type="text" name="original_auction<?php _e($i); ?>_low_estimate" id="original_auction<?php _e($i); ?>_low_estimate" value="<?php _e($auction_results[$i-1]['low_estimate']); ?>" />
			<br />
			
			<label class = "default-field" ><?php _e(ORIGINAL_METABOX_AUCTION_HIGH_ESTIMATE_LABEL.' ('.ORIGINAL_CURRENCY.')'); ?> : </label>
			<input type="text" name="original_auction<?php _e($i); ?>_high_estimate" id="original_auction<?php _e($i); ?>_high_estimate" value="<?php _e($auction_results[$i-1]['high_estimate']); ?>" />
			<br />
			
			<label class = "default-field" ><?php _e(ORIGINAL_METABOX_AUCTION_SALES_DATE_LABEL); ?><span class="required_field">*</span>: </label>
			<input type="text" name="original_auction<?php _e($i); ?>_sales_date" id="original_auction<?php _e($i); ?>_sales_date" value="<?php _e($auction_results[$i-1]['sales_date']); ?>" readonly  = "true" />
			<br />
			
			<label class = "default-field" ><?php _e(ORIGINAL_METABOX_AUCTION_AUCTION_HOUSE_LABEL); ?> : </label>
			<input type="text" name="original_auction<?php _e($i); ?>_auction_house" id="original_auction<?php _e($i); ?>_auction_house" value="<?php _e($auction_results[$i-1]['auction_house']); ?>" />
			<br />
			
			<label class = "default-field" ><?php _e(ORIGINAL_METABOX_AUCTION_LOT_NUMBER_LABEL); ?> : </label>
			<input type="text" name="original_auction<?php _e($i); ?>_lot_number" id="original_auction<?php _e($i); ?>_lot_number" value="<?php _e($auction_results[$i-1]['lot_number']); ?>" />
			<br />
			
			<label class = "default-field" ><?php _e(ORIGINAL_METABOX_AUCTION_CATEGORY_LABEL); ?> : </label>
			<input type="text" name="original_auction<?php _e($i); ?>_category" id="original_auction<?php _e($i); ?>_category" value="<?php _e($auction_results[$i-1]['category']); ?>" />
		</div>
	<?php } ?>
	
	
	<input class = "button button-primary" type="button" id="add_new_auction" value="<?php _e(ORIGINAL_BUTTON_ADD_NEW_AUCTION); ?>" />	
	<input id = "no_of_auctions" name = "no_of_auctions" type="hidden" value="<?php _e($count); ?>" />	
	<script>
		no_of_auction = <?php _e($count); ?>
	</script>
	<?php

}

add_action('save_post', 'original_dt_save_custom_fields');
 function original_dt_save_custom_fields(){
	
	global $post,$wpdb;
	if($post->post_type == ORIGINAL_POST_TYPE){
	
		$data = $_POST;
		$auction = $wpdb->prefix."auction";
		$insert_query = "INSERT INTO $auction VALUES ";
		$insert_query_array = array();
		for($i = 1;$i <= $data['no_of_auctions'];$i++){
			if(array_key_exists('original_auction'.$i.'_sales_date',$data) && !empty($data['original_auction'.$i.'_sales_date']) && $data['original_auction'.$i.'_sales_date'] != '0000-00-00' ){
				$data['original_auction'.$i.'_hammer_price'] = empty($data['original_auction'.$i.'_hammer_price'])?0:$data['original_auction'.$i.'_hammer_price'];
				
				$data['original_auction'.$i.'_low_estimate'] = empty($data['original_auction'.$i.'_low_estimate'])?0:$data['original_auction'.$i.'_low_estimate'];
				
				$data['original_auction'.$i.'_high_estimate'] = empty($data['original_auction'.$i.'_high_estimate'])?0:$data['original_auction'.$i.'_high_estimate'];
				
				$data['original_auction'.$i.'_sales_date'] = empty($data['original_auction'.$i.'_sales_date'])?'0000-00-00':$data['original_auction'.$i.'_sales_date'];
				
				$data['original_auction'.$i.'_auction_house'] = empty($data['original_auction'.$i.'_auction_house'])?'':$data['original_auction'.$i.'_auction_house'];
				
				$data['original_auction'.$i.'_lot_number'] = empty($data['original_auction'.$i.'_lot_number'])?0:$data['original_auction'.$i.'_lot_number'];
				
				$data['original_auction'.$i.'_category'] = empty($data['original_auction'.$i.'_category'])?'':$data['original_auction'.$i.'_category'];
				
				$insert_query_array[] = '("",'.$data['original_auction'.$i.'_hammer_price'].'
									,'.$data['original_auction'.$i.'_low_estimate'].'
									,'.$data['original_auction'.$i.'_high_estimate'].'
									,"'.$data['original_auction'.$i.'_sales_date'].'"
									,"'.$data['original_auction'.$i.'_auction_house'].'"
									,'.$data['original_auction'.$i.'_lot_number'].'
									,"'.$data['original_auction'.$i.'_category'].'"
									,'.$post->ID.'
									)';
			}
		}
		$insert_query .= implode(',',$insert_query_array).';';
		$wpdb->delete($auction,array('post_id' => $post->ID),array('%d'));
		$wpdb->query($insert_query);
		
		update_post_meta($post->ID, "original_dimension", $_POST["original_dimension"]);
		update_post_meta($post->ID, "original_dimension_unit", $_POST["original_dimension_unit"]);
		
		update_post_meta($post->ID, "original_creation_year", $_POST["original_creation_year"]);
		update_post_meta($post->ID, "original_medium", $_POST["original_medium"]);
		update_post_meta($post->ID, "original_marks", $_POST["original_marks"]);
		$post_to_artist = $wpdb->prefix . "post_to_artist"; 
		$artist_ids = $_POST['artist_sugg'];
		$wpdb->delete($post_to_artist,array('post_id' => $post->ID),array('%d') );
		if($artist_ids){
			foreach($artist_ids as $id){
				$wpdb->replace( $post_to_artist,array('post_id' => $post->ID,'artist_id' => $id), array('%d','%d'));
			}
		}
	}
}
	 
function original_change_post_object_label() {
	global $wp_post_types;
	$labels = &$wp_post_types[ORIGINAL_POST_TYPE]->labels;
	$labels->add_new = _x('Add New', ORIGINAL_POST_TYPE);
	$labels->add_new_item = 'Add new Original';
	$labels->edit_item = 'Edit Original';
	$labels->new_item = 'New Original';
	$labels->view_item = 'View Original';
	$labels->search_items = 'Search Original';
	$labels->not_found = 'No Originals found';
	$labels->not_found_in_trash = 'No originals found in trash.';
}
add_action( 'init', 'original_change_post_object_label' );

add_action('admin_enqueue_scripts', 'original_add_my_js_css');   
function original_add_my_js_css(){

   global $post;

  if($post->post_type == ORIGINAL_POST_TYPE){
	  wp_enqueue_script('my_validate', WP_PLUGIN_URL.'/Original/js/jquery.validate.js', array('jquery'));
	  wp_enqueue_script('alpha_numeric_pack', WP_PLUGIN_URL.'/Original/js/alphanumeric.pack.js', array('jquery'));
	  wp_enqueue_script('original_jquery_ui_js', WP_PLUGIN_URL.'/Original/js/jquery-ui.min.js');
	  wp_enqueue_script('jquery_chosen_js', WP_PLUGIN_URL.'/Original/js/chosen.jquery.min.js');
	  wp_enqueue_script('my_script_js', WP_PLUGIN_URL.'/Original/js/validpost.js');
	
	  
	  wp_enqueue_style('jquery-ui_css', WP_PLUGIN_URL.'/Original/css/jquery-ui/jquery-ui.min.css');
	  wp_enqueue_style('original_css', WP_PLUGIN_URL.'/Original/css/original.css');
	  wp_enqueue_style('chosen_css', WP_PLUGIN_URL.'/Original/css/chosen/chosen.min.css');
  }
}

add_action('admin_head', 'original_header');

function original_header() {
    global $post_type;
    ?>
    <style>
    <?php if (($_GET['post_type'] == ORIGINAL_POST_TYPE) || ($post_type == ORIGINAL_POST_TYPE)) : ?>
    #icon-edit { background:transparent url('<?php echo WP_PLUGIN_URL .'/Original/images/original-32.png';?>') no-repeat; }    
    <?php endif; ?>
        </style>
        <?php
}




//---------------------------page shorcode start--------------------------------------
function original_artist_shortcode(){
	require_once(plugin_dir_path(__FILE__) . "original-artist.php");
}
add_shortcode("original_artist", "original_artist_shortcode");

//---------------------------page shortcode end--------------------------------------------

//--------------------------------------------create menu start-------------------------------

//--------------------------------------------create menu end------------------------------

//---------------------------create original pages start----------------------------------------------

function create_original_pages($page_title,$page_code){
	global $wpdb;
	
	$the_page_title = $page_title;
	$the_page_name = $page_title;

	// the menu entry...
	delete_option($page_code."_title");
	add_option($page_code."_title", $the_page_title, '', 'yes');
	// the slug...
	delete_option($page_code."_name");
	add_option($page_code."_name", $the_page_name, '', 'yes');
	// the id...
	delete_option($page_code."_id");
	add_option($page_code."_id", '0', '', 'yes');

	$the_page = get_page_by_title( $the_page_title );

	if ( ! $the_page ) {

	// Create post object
	$_p = array();
	$_p['post_title'] = $the_page_title;
	$_p['post_content'] = "[" . $page_code . "]";
	$_p['post_status'] = 'publish';
	$_p['post_type'] = 'page';
	$_p['comment_status'] = 'closed';
	$_p['ping_status'] = 'closed';
	$_p['post_category'] = array(1); // the default 'Uncatrgorised'

	// Insert the post into the database
	$the_page_id = wp_insert_post( $_p );

	}
	else {
	// the plugin may have been previously active and the page may just be trashed...

	$the_page_id = $the_page->ID;

	//make sure the page is not trashed...
	$the_page->post_status = 'publish';
	$the_page_id = wp_update_post( $the_page );

	}

	delete_option( $page_code."_id" );
	add_option($page_code."_id", $the_page_id );
}

function remove_original_pages($page_code){
	global $wpdb;
	$the_page_title = get_option( $page_code."_title" );
	$the_page_name = get_option( $page_code."_name" );
	//  the id of our page...
	$the_page_id = get_option( $page_code.'_id' );			/*get the page id*/
	if( $the_page_id ) {
	        wp_delete_post( $the_page_id ); // this will trash, not delete
	}
	delete_option($page_code."_title");
	delete_option($page_code."_name");
	delete_option($page_code."_id");
	
}

function original_create_tables(){
	global $wpdb;
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	$sql = array();

	$sql[] = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."auction` (
			  `auction_id` int(11) NOT NULL AUTO_INCREMENT,
			  `hammer_price` float NOT NULL,
			  `low_estimate` float NOT NULL,
			  `high_estimate` float NOT NULL,
			  `sales_date` date NOT NULL,
			  `auction_house` varchar(255) NOT NULL,
			  `lot_number` int(11) NOT NULL,
			  `category` varchar(255) NOT NULL,
			  `post_id` int(11) NOT NULL,
			  PRIMARY KEY (auction_id)
			);";

	foreach($sql as $query){
		dbDelta( $query );
	}
}

/*function original_activate
 *This function is hooked to the plugin activation hook
 */
function original_activate() {
	create_original_pages(PAGE_TITLE_ORIGINAL_ARTIST,'original_artist');
	original_create_tables();
	flush_rewrite_rules();
}

register_activation_hook(__FILE__,'original_activate'); 

/*function original_deactivate
 *This function is hooked to the plugin deactivation hook
 */
function original_deactivate() {
	remove_original_pages('original_artist');
	flush_rewrite_rules(); 
}

register_deactivation_hook( __FILE__, 'original_deactivate' );

//----------------------------------------------create original pages end----------------------------


//-----------------------------------------Original fetch functions start ----------------------------

function get_original_meta_details($post){
 $string = '';
 ob_start();
 ?>
 <p class="meta">
   <?php echo get_the_time(get_option('date_format'),$post->ID); ?> 
   <?php $categories = wp_get_post_terms($post->ID, 'original-category');
   $cat_array = array();
   foreach($categories as $category){
    $link = get_term_link($category);
    $cat_array[] = '<a href = "'.$link.'">'.$category->name.'</a>';
   }
   if(!empty($cat_array))
   echo '&bull; '.implode(',',$cat_array);
   
  ?> 
  
   <?php 
  $author_link = get_author_posts_url($post->post_author);
  if(!empty($author_link))
  echo '&bull; <a href = "'.$author_link.'">'.get_the_author_meta('display_name',$post->post_author ).'</a>';
   ?> &bull; 
   <?php 
 
   if(!is_single()){
	   $comments_count = wp_count_comments($post->ID);
	   
	  $comment_string = '<a href = "'.get_permalink($post->ID).'#respond">Comments ('.$comments_count->approved.')</a>';
	   
	   echo $comment_string;
   }
   ?>
   <?php if(vrg_ratings()){ ?><br/><?php echo vrg_ratings(); } else { }?>
 </p>
 <?php
 $html = ob_get_contents();
 ob_end_clean();
 return $html;
}


function original_clip_content($content,$post_id,$max_content_length = ORIGINAL_MAX_CONTENT_LENGTH){
	$new_content = $content;
	if(strlen($content) > $max_content_length){
		$new_content = substr($content,0,$max_content_length) ;
	}
	return $new_content.'  <a class = "read_more" href = "'.get_permalink($post_id).'">read more <i class="icon-circle-arrow-right"></i></a>';
}

function add_original_var($public_query_vars) {
		$public_query_vars[] = ORIGINAL_QUERY_VAR_ARTISTSLUG;
		return $public_query_vars;
}
add_filter('query_vars', 'add_original_var');

function get_artist_originals($slug){
	global $wpdb;
	
	$page = $_POST['page_no']?$_POST['page_no']:1;
	$ajax_request = $_POST['original_ajax']?$_POST['original_ajax']:0;
	$slug = $_POST['artist_slug']?$_POST['artist_slug']:$slug;
	$pagination_limit_per_type = PAGINATION_ORIGINAL_PAGE;
	
	$start = $pagination_limit_per_type * ($page-1);
	
	$posts = $wpdb->prefix.'posts';
	$post_meta = $wpdb->prefix.'postmeta';
	$wwl_post_to_artist = $wpdb->prefix.'post_to_artist';
	$is_result_empty = 1;
	
	$artist_id = mural_get_artist_id_from_slug($slug);
	$query = $wpdb->prepare("SELECT `ID`,`post_author`,`post_title`,`post_content` FROM `$posts` 
							JOIN `$wwl_post_to_artist` as `pta` ON `$posts`.ID = `pta`.post_id 
							WHERE `post_status` = 'publish' AND `post_type` = '".ORIGINAL_POST_TYPE."' AND `pta`.`artist_id` = %d order by `post_date` DESC limit %d,%d",$artist_id,$start,$pagination_limit_per_type);
							
	$result = $wpdb->get_results($query);
	if(!empty($result)){
		$is_result_empty = 0;
	}
	if(!$ajax_request){
		return $result;
	} else {
		for($i = 0;$i < NO_OF_COL_ORIGINAL_PAGE;$i++){
			$column_htmls['col_'.$i] = get_artist_original_html($result,$i);
		}	
		echo json_encode(array('column_html' => $column_htmls,'is_empty' => $is_result_empty));
		die;
	}
	
}

add_action('wp_ajax_get_artist_originals','get_artist_originals');
add_action('wp_ajax_nopriv_get_artist_originals','get_artist_originals');

function get_artist_original_html($posts,$col_no){
	ob_start();
	$i = 0;
	if(!empty($posts)){
		foreach($posts as $post){ 
			if( (($i+(NO_OF_COL_ORIGINAL_PAGE - $col_no)) % NO_OF_COL_ORIGINAL_PAGE) == 0){
			?>
				<div class = "content_block">
					<div class = "featured_image">
						<?php  
						$detail_page_url = get_permalink($post->ID);
						
						echo '<a href = "'.$detail_page_url.'">'.get_the_post_thumbnail($post->ID,THUMBNAIL_MEDIUM).'</a>'; ?>
					</div>
					<div class = "magazine_type_desc">
						<h4>
							<?php
								_e('<a href = "'.$detail_page_url.'">'.$post->post_title.'</a>');
							?>
						</h4>
						<?php 
							
							if(function_exists ('get_original_meta_details')){
								
								get_original_meta_details($post); 
								
							}
							
						?>
						<p>	
							<?php  _e(original_clip_content($post->post_content,$post->ID,ORIGINAL_MAX_ARTICLE_CONTENT_LENGTH)); 
							?>
						</p>
					</div>
				</div>
			<?php 
				
			}
			$i++;
		} 
	}
	$html = ob_get_contents();
	ob_end_clean();
	return $html;
}


//-----------------------------------------Original fetch functions end -----------------------------------------

