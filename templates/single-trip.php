<?php get_header(); ?>

<?php

$event_id = $_GET['event_id'];
$action = $_GET['action'];
global $post;
$trip_meta = get_post_meta($post->ID);
$current_user = wp_get_current_user();
$adults = unserialize($trip_meta['adults'][0]);
$scouts = unserialize($trip_meta['scout'][0]);

if ((!in_array($current_user->display_name, $adults)) && (!in_array($current_user->display_name, $scouts))) {

//if (strtolower($action) != 'edit') {
    wp_head();
  ?>
  <body <?php body_class(); ?>>
   <div id="content" class="site-content">
      <div class="container main-content-area">
          <div class="row side-pull-left">
            <div class="main-content-inner col-sm-12 col-md-8">
              <div class="content-area" id="primary">
                <main id="main" class="site-main" role="main"> 
                  <article>
                                      <div class="post-inner-content">

       <?php        
       ?> <div id="printArea">
       <?php     
                echo '<h1 class="entry-title">'.$post->post_title.'</h1>';
                echo '<h3>Attendees type:</h3>'.$trip_meta["attendees"][0].'</p>';

                echo '<h3>Adult Leader(s): </h3>';
                foreach ($adults as $adult){
                   echo '<p>'.str_repeat("&nbsp;", 10) . $adult,'</p>';
                }
                echo '<h3>Scout Leader(s):</h3>';
                foreach ($scouts as $scout){
                   echo '<p>'.str_repeat("&nbsp;", 10) . $scout,'</p>';
                }
                echo '<h3>Cook type:</h3>'.$trip_meta["cook_type"][0].'</p>';
                echo '<h3>Permits:</h3><p class="trip_info">'.$trip_meta["permits_req"][0].'</p>';
                echo '<h3>Training:</h3><p class="trip_info">'.$trip_meta["training"][0].'</p>';
                echo '<h3>Equipment:</h3><p class="trip_info">'.$trip_meta["equip"][0].'</p>';
       ?></div><?php
  echo '<button onclick="trip_print();">Print this Trip Plan</button>';  
        ?>
     </div> </article></main></div></div></div></div></div></body> 
  <?php
} else {
  $url =  "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
  $event_id = basename($url);
  echo scoutbook_trip_form($event_id, 'update');
}

?>
<?php get_sidebar(); ?>
<?php get_footer(); ?>