<?php get_header(); ?>

<?php
  
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$args = array(
  'posts_per_page' => 4,
  'paged' => $paged,
  'post_type' => 'trip', 
  'orderby' => 'post_date',
  'order' => 'DESC'
);
$custom_query = new WP_Query( $args );

?>
<!----start-------->
<div class="wrap">
<div id="primary" class="content-area">
<main id="main" class="site-main" role="main">
 
<?php
   while($custom_query->have_posts()) :
      $custom_query->the_post();
    // Get post meta info here
  /*
	$trip_name = get_post_meta($post->ID, 'trip_name', true);
	$trip_return_date = get_post_meta($post->ID, 'return_date', true);
	$trip_attendees = get_post_meta($trip->ID, 'attendees', true);
	$trip_adults = get_post_meta($post->ID, 'adults', true);
	$trip_training_req = get_post_meta($post->ID, 'training', true);
	$trip_equip_req = get_post_meta($post->ID, 'equip', true);
	$permits_req = get_post_meta($post->ID, 'permits_req', true);
	$cook_type = get_post_meta($post->ID, 'cook_type', true);  
  */
  
?>
       <div>
        <ul>
         <li>
           <h3><a href="<?php echo the_permalink().'?action=view'; ?>" ><?php the_title(); ?></a> <?php the_ID(); ?></h3>
        <div>
          <ul>
        <div><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a></div>
          </ul>
          <ul>
        <p><?php echo the_content(); ?></p>
          </ul>
        </div>
        <div>
          </li>
        </ul>
          </div> <!-- end blog posts -->
       <?php endwhile; ?>
      <?php if (function_exists("pagination")) {
          pagination($custom_query->max_num_pages);
      } ?>
</main><!-- #main -->
</div><!-- #primary -->
</div><!-- .wrap -->
          <!----end-------->
<?php 
get_footer();?>

<!-- 
  Foreach post of type trip
    get post meta
    print paginated list
      trip name," ", cook type, " " trip type
    
https://www.wpblog.com/use-wp_query-to-create-pagination/

-->