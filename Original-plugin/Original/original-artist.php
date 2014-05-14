<?php
/*
 * @package Original
 * @version 1.0
 *  *Script Name: original-artist.php
 * Description: Renders the Artist page with all types
  * Plugin URI:  http://widewalls.ch
 * Version: 1.0
*/ 
	wp_enqueue_style('original_front_css', WP_PLUGIN_URL.'/Original/css/original-front.css');
	$slug = get_query_var(ORIGINAL_QUERY_VAR_ARTISTSLUG);
	function remove_thumbnail_dimensions( $html, $post_id, $post_image_id ) {
		$html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
		return $html;
	}
	
	
	add_filter( 'post_thumbnail_html', 'remove_thumbnail_dimensions', 10, 3 );
	$posts = get_artist_originals($slug);
	if(!$posts){
		echo "No originals added for this artist";
	}
	
	
?>
<input id = "artist_slug" type = "hidden" value = "<?php _e($slug); ?>">
<script type="text/javascript">
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
var no_of_col_original_page = <?php echo NO_OF_COL_ORIGINAL_PAGE; ?>;
</script>
<div id = "artist_column_detail" class = "row col-lg-12 database_article">
	<?php
		$column_class = "col-lg-".(12/NO_OF_COL_ORIGINAL_PAGE);
		for($i = 0;$i < NO_OF_COL_ORIGINAL_PAGE;$i++){
			$class = ($i == NO_OF_COL_ORIGINAL_PAGE-1)?"lastdiv":"";	
	?>
			<div id = "artist_original_col_<?php _e($i+1); ?>" class = "<?php _e($column_class.' '.$class); ?>">
				<?php echo get_artist_original_html($posts,$i); ?>
			</div>
	<?php 
		}
	?>
	<script>
		var last_column = <?php _e(PAGINATION_ORIGINAL_PAGE-1); ?>;
		if(last_column >= (no_of_col_original_page-1)){
			last_column = 0;
		} else {
			last_column++;
		}
		
	</script>
</div>
<div style="clear: both;"></div>
<div id = "content_loading" style = "display:none">
	<img  src = "<?php _e(WP_PLUGIN_URL.'/Original/images/loader.gif'); ?>">
</div>



<?php 
wp_enqueue_script('artist_original_js', WP_PLUGIN_URL.'/Original/js/artist_original.js');

