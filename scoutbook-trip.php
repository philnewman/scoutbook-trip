<?php
/**
 * Plugin Name: Scoutbook-Camping Trip 
 * Plugin URI: http://troop351.org/plugin/scoutbook-camping-trip
 * Description:
 * Version: 0.1
 * Author: Phil Newman
 * Author URI: http://getyourphil.net
 * License: GPL3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.en.html
 **/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
define( 'WP_DEBUG', true );
include_once plugin_dir_path(__FILE__)."includes/scoutbook-trip.inc";
//include_once plugin_dir_path(__FILE__)."includes/shortcodes.php";

function add_trip_js_and_css() {
		wp_enqueue_script( 'trip_js', plugin_dir_url(__FILE__).'js/trip.js');
    wp_enqueue_style('trip_css',plugin_dir_url(__FILE__).'styles/trip.css');
}
add_action( 'wp_enqueue_scripts', 'add_trip_js_and_css' );

/****************************************************************************/
/* Create custom post type of Camping Trip                                  */
/****************************************************************************/
function create_camping_trip() {
    register_post_type( 'trip',
        array(
            'labels' => array(
                'name' => 'Camping Trip',
                'singular_name' => 'Camping Trip',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Camping Trip',
                'edit' => 'Edit',
                'edit_item' => 'Edit Camping Trip',
                'new_item' => 'New Camping Trip',
                'view' => 'View',
                'view_item' => 'View Camping Trip',
                'search_items' => 'Search Camping Trip',
                'not_found' => 'No Camping Trip found',
                'not_found_in_trash' => 'No Camping Trip found in Trash',
                'parent' => 'Parent'
            ),
            'public' => true,
            'menu_position' => 15,
            'supports' => array(  'editor'),
            'taxonomies' => array( '' ),
            'menu_icon' => plugins_url( 'assets/icon-20x20.png', __FILE__ ),
            'has_archive' => true,
          'publicly_queryable' => true,
        )
    );
	remove_post_type_support( 'trip', 'editor' );
}
add_action( 'init', 'create_camping_trip' );

function scoutbook_trip_activate() {
    // register taxonomies/post types here
    flush_rewrite_rules(FALSE);
}

register_activation_hook( __FILE__, 'scoutbook_trip_activate' );

function add_camping_trip_meta_box(){
 
  global $post, $wpdb;
     
	$trip_name = get_post_meta($post->ID, 'trip_name', true);
	$trip_return_date = get_post_meta($post->ID, 'return_date', true);
	$trip_attendees = get_post_meta($post->ID, 'attendees', true);
	$trip_adults = get_post_meta($post->ID, 'adults', true);
  $trip_scout = get_post_meta($post->ID, 'scout', true);  
	$trip_training_req = get_post_meta($post->ID, 'training', true);
	$trip_equip_req = get_post_meta($post->ID, 'equip', true);
	$permits_req = get_post_meta($post->ID, 'permits_req', true);
	$cook_type = get_post_meta($post->ID, 'cook_type', true);  
  
   echo '<input type="hidden" id="trip_event_id" name="trip_event_id" value="'.$post->trip_event_id.'"/>';

   if ($post->trip_event_id){
    $trip_name = get_the_title($post->trip_event_id);
    echo '<input type="hidden" id="trip_name" name="trip_name" value="'.$trip_name.'"/>';
    echo '<input type="hidden" id="trip_event_id" name="trip_event_id" value="'.$post->trip_event_id.'"/>';
    echo '</br>'.$trip_name;
   } else {
       $qry = "
    SELECT ID, post_title
    FROM wp_posts
    WHERE post_type = 'ai1ec_event'
    ORDER BY post_title
    ";
    $ai1ec_events = $wpdb->get_results( $qry );
    $ai1ec_events = wp_list_pluck($ai1ec_events, 'post_title');
	  echo '<p>Trip Name: <select id="trip_name" name="trip_name">';
	  foreach ($ai1ec_events as $the_event){
		  echo '<option value="'.$the_event.'"';
		  if ( trim($the_event) == trim($trip_name)){
			  echo ' selected';
		  }
		  echo '>'.$the_event.'</option>';
	  }
	  echo '</select></p>';
   }     
  
?>
	<p>Attendees: 
    <input type="radio" name="trip_attendees" value="troop" <?php if ($trip_attendees == 'troop'){echo 'checked ';} ?>>Troop
<input type="radio" name="trip_attendees" value="patrol" <?php if ($trip_attendees == 'patrol'){echo 'checked';} ?>>Patrol</br>
</p>

	<p>	Adult Leader:
		<select multiple name="trip_adults[]" style="overflow-y:scroll; width: 175px; overflow-x:hidden; height:250px;">
  <!--  <select name="trip_adults[]" multiple> -->
		<?php 
      $adult_names = scoutbook_get_by_sb_role('Adult');
     foreach ($adult_names as $adult_name){ 
     echo '<option value="'.$adult_name->display_name.'"';
	//		if ($adult_name->display_name == $trip_adults){
       if (in_array($adult_name->display_name, $trip_adults)){
				echo ' selected ';
			}  
			echo '>'.$adult_name->display_name.'</option>';
			}
		?>
		</select>
    
    Scout Leader:
    		<select multiple name="trip_scout[]" style="overflow-y:scroll; width: 175px; overflow-x:hidden; height:250px;">
 <!--   <select name="trip_scout[]" multiple> -->
		<?php 
      $scout_names = scoutbook_get_by_sb_role('youth');
     foreach ($scout_names as $scout_name){ 
     echo '<option value="'.$scout_name->display_name.'"';
      if (in_array($scout_name->display_name, $trip_scout)){
				echo ' selected ';
			}  
			echo '>'.$scout_name->display_name.'</option>';
			}
		?>
		</select>
</p>
<p>
	WHAT TYPE OF COOKING
        <input type="radio" name="cook_type" value="troop" <?php if ($cook_type == 'troop'){echo 'checked ';} ?>>Troop
        <input type="radio" name="cook_type" value="patrol" <?php if ($cook_type == 'patrol'){echo 'checked';} ?>>Patrol
        <input type="radio" name="cook_type" value="individual" <?php if ($cook_type == 'individual'){echo 'checked';} ?>>Individual
</p>
<p>
	PERMITS REQUIRED: <textarea rows="5" cols="100" name="permits_req" ><?php echo $permits_req; ?></textarea>
</p>
<p>
	Training Required: <textarea rows="5" cols="100" name="trip_training_reqd" ><?php echo $trip_training_req; ?></textarea>
</p>
<p>
	Equipment Required: <textarea rows="5" cols="100" name="trip_equip_reqd" ><?php echo $trip_equip_req; ?></textarea>
</p>
	<?php
}

function add_camping_trip_meta_boxes(){
	add_meta_box('Camping_trip_meta', 'Camping Trip Data', 'add_camping_trip_meta_box', 'trip', 'normal', 'low');
}
add_action('admin_init', 'add_camping_trip_meta_boxes');


function save_camping_trip_fields( $trip_id, $trip) {
  
    global $wpdb;
     
    // Check post type for trip_event
    if ( $trip->post_type == 'trip' ) {      
 
        // Store data in post meta table if present in post data
        if ( isset( $_POST['trip_event_id'] ) && $_POST['trip_event_id'] != '' ) {
            update_post_meta( $trip_id, 'trip_event_id', $_POST['trip_event_id'] );
        }
        if ( isset( $_POST['trip_start_date'] ) && $_POST['trip_start_date'] != '' ) {
            update_post_meta( $trip_id, 'start_date', $_POST['trip_start_date'] );
        }
        if ( isset( $_POST['trip_return_date'] ) && $_POST['trip_return_date'] != '' ) {
            update_post_meta($trip_id, 'return_date', $_POST['trip_return_date'] );
        }
			  if ( isset( $_POST['trip_attendees'] ) && $_POST['trip_attendees'] != '' ) {
            update_post_meta($trip_id, 'attendees', $_POST['trip_attendees'] );
        }
				if ( isset( $_POST['trip_name'] ) && $_POST['trip_name'] != '' ) {
            update_post_meta($trip_id, 'trip_name', $_POST['trip_name'] );
        }
				if ( isset( $_POST['cook_type'] ) && $_POST['cook_type'] != '') {
						update_post_meta($trip_id, 'cook_type', $_POST['cook_type'] );
				}
				if ( isset ($_POST['permits_req'] ) && $_POST['permits_req'] != ''){
						update_post_meta($trip_id, 'permits_req', $_POST['permits_req'] );
				}
				if ( isset( $_POST['trip_training_reqd'] ) && $_POST['trip_training_reqd'] != '' ) {
            update_post_meta($trip_id, 'training', $_POST['trip_training_reqd'] );
        }			
				if ( isset( $_POST['trip_equip_reqd'] ) && $_POST['trip_equip_reqd'] != '' ) {
            update_post_meta($trip_id, 'equip', $_POST['trip_equip_reqd'] );
        }		
				if ( isset( $_POST['trip_adults'] ) && $_POST['trip_adults'] != '' ) {
            update_post_meta($trip_id, 'adults', $_POST['trip_adults'] );
        }	
        if ( isset( $_POST['trip_scout'] ) && $_POST['trip_scout'] != '' ) {
            update_post_meta($trip_id, 'scout', $_POST['trip_scout'] );
        }	
      
        $wpdb->update( $wpdb->posts, array( 'post_title' =>  $_POST['trip_name']), array( 'ID' => $trip_id ) ); 
     
        if ($_POST['trip_event_id']){
          $url = home_url().'/trip/'.$trip_id;
          wp_redirect($url);
          exit;
        }
    }
}
add_action( 'save_post', 'save_camping_trip_fields', 10, 2 );


/****************************************************************************/
/* End - Create custom post type of Camping Trip Event                      */
/****************************************************************************/

function metaboxes_on_top() {
    global $post;
    if ( get_post_type($post) == 'trip') {
        ?>
        <script type="text/javascript">
        jQuery('#normal-sortables').insertBefore('#postdivrich');
        </script>
        <?php
    }
};
add_action( 'admin_footer', 'metaboxes_on_top' );

/* Filter the single_template with our custom function*/
add_filter('single_template', 'scoutbook_trip_single_template');
add_filter('archive_template', 'scoutbook_trip_archive_template');

function scoutbook_trip_single_template($single) {
    global $wp_query, $post;
    /* Checks for single template by post type */
    if ( $post->post_type == 'trip' ) {
				$trip_plugin_dir = plugin_dir_path(__FILE__)."/templates";
        if ( file_exists( $trip_plugin_dir . '/single-trip.php' ) ) {
            return $trip_plugin_dir . '/single-trip.php';
        }
    }
    return $single;
}
function scoutbook_trip_archive_template($archive_template) {
    global $wp_query, $post;
    /* Checks for single template by post type */
    if ( $post->post_type == 'trip' ) {
				$trip_plugin_dir = plugin_dir_path(__FILE__)."/templates";
        if ( file_exists( $trip_plugin_dir . '/archive-trip.php' ) ) {
            return $trip_plugin_dir . '/archive-trip.php';
        }
    }
    return $archive_template;
}





