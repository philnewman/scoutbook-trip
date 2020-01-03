<?php 
  require_once("../../../../wp-load.php");

  $event_id = $_GET['event_id'];
  get_header();

  echo scoutbook_trip_form($event_id);

  get_sidebar();
  get_footer();

?>