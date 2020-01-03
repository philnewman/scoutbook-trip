<?php

function ptn_scoutbook_add_trip_to_event( $content ) {
  global $post;
  if( ! $post instanceof WP_Post ) return $content;
  switch( $post->post_type ) {
    case 'ai1ec_event':
      $post_id = $post->ID;
      $data = get_userdata( get_current_user_id() );
      $trip_id = scoutbook_trip_exists();
      
      if (!$trip_id){
        if (in_array('edit_posts', $data->allcaps)){
          $status = 'Create';
          $trip_plan_link = 
            '<p><a href="'.plugin_dir_url().'scoutbook-trip/templates/create-trip.php?event_id='.$post_id.'">'.$status.' Trip Plan</a></p>';
        }
      } else {
          if (in_array('edit_posts', $data->allcaps)){
            // Could clean this up some...  ## PTN ##
            $status = 'View';
          }else{
            $status = 'Edit';
          }
             $trip_plan_link = '<p><a href="'.home_url().'/trip/'.$trip_id.'">'.$status.' Trip Plan</a></p>';
      }
        $content .= $trip_plan_link;
    default:
      return $content;
  }
}
add_filter( 'the_content', 'ptn_scoutbook_add_trip_to_event' );

function scoutbook_trip_show_form($event_id = null){
  global $post;

      echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
      echo '<input type="hidden" id="ID" name="ID" value="'.$trip.'">';
      add_camping_trip_meta_box();
      if ($post->trip_action == 'update'){
        $button_value = 'Update Trip';
      }else {
        $button_value = 'Create Trip';
      }
      echo '<p><input type="submit" name="trip-submitted" value="'.$button_value.'"/></p>';
      echo '</form>';
}

function scoutbook_trip_post_form(){
  
  global $post;
  
 if ($post->trip_action == 'update') {
    $the_ID = $post->trip_event_id;
  }else{
    $the_ID = $_POST['ID'];
  }
 
  
  if ( isset( $_POST['trip-submitted'] ) ) {
    $post_arr = array(
 //     'ID'            => $_POST['ID'],
      'ID' => $the_ID,
      'post_title' => $post->post_title,
      'post_status'   => 'publish',
      'post_author'   => get_current_user(),
      'post_type'     => 'trip',
    );
    
    $post_id = wp_update_post( $post_arr, $wp_error );
  }
}
function scoutbook_trip_form($event_id = null, $action = 'post'){
    
  global $post;
  $post->trip_event_id = $event_id;
  ob_start();
  $post->trip_event_id = $event_id;
  $post->trip_action = $action;
  
  scoutbook_trip_post_form();
  scoutbook_trip_show_form();

  return ob_get_clean();
}

function scoutbook_trip_exists(){
  global $post;
  global $wpdb;
  
  $qry = "
  SELECT *
  FROM wp_postmeta
  WHERE `meta_key` = 'trip_event_id'
  AND `meta_value` = $post->ID
  ";
  $trip_id = $wpdb->get_results($qry);

  return $trip_id[0]->post_id;
}
function scoutbook_get_by_sb_role($meta_value){
  
 $list =  get_users(
  array(
   'meta_key' => 'MemberType',
   'meta_value' => $meta_value,
   'count_total' => false,
   'fields' => array(
      'display_name'),
  )
 );
 
  return $list;
    
}

