<?php
/*
Plugin Name:  Latest Posts with Thumbnails Widget
Description:  Widget for displaying latest posts with thumbnails in sidebar.
Version:      1.0.0
Author:       Andrew Blane
*/

// Register and load the widget
function nhsl_load_widget() {
  register_widget( 'nhsl_latest_posts_widget' );
}
add_action( 'widgets_init', 'nhsl_load_widget' );

// Create the widget 
class nhsl_latest_posts_widget extends WP_Widget {

  function __construct() {
    parent::__construct(

    // Base ID of widget
    'nhsl_latest_posts_widget', 

    // Widget name that will appear in dashboard
    __('Latest Posts with Thumbnails', 'nhsl_latest_posts_widget_domain'), 

    // Widget description that will appear in dashboard
    array( 'description' => __( 'Displays latest posts with thumbnails', 'nhsl_latest_posts_widget_domain' ), ) 
  );
}

// Create widget front-end
public function widget( $args, $instance ) {
  $title = apply_filters( 'widget_title', $instance['title'] );

  // before and after widget arguments are defined by themes
  echo $args['before_widget'];

  if ( ! empty( $title ) )
  echo $args['before_title'] . $title . $args['after_title'];

  // Loop though posts displaying excerpts
  $array_args = array(
    'posts_per_page'      => 2,
    'post_type' 					=> 'post',
    'post_status'         => 'publish',
    'ignore_sticky_posts' => true,
    'orderby' 						=> 'post_date',
    'order' 							=> 'DESC',
    'nopaging'   					=> false,
    'no_found_rows'       => true,
  );
  $recent_posts = new WP_query($array_args);
  if ( $recent_posts->have_posts() ) {
    while ($recent_posts->have_posts()) : $recent_posts->the_post();
      get_template_part( 'template-parts/content', 'excerpt' );
    endwhile;
  }
  wp_reset_postdata();
  
  echo $args['after_widget'];
}
         
// Widget Backend 
public function form( $instance ) {
  if ( isset( $instance[ 'title' ] ) ) {
    $title = $instance[ 'title' ];
  }
  else {
    $title = __( 'Latest Posts', 'nhsl_latest_posts_widget_domain' );
  }

  // Widget admin form
  ?>
  <p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
  </p>
  <?php
  }

  // Update widget replacing old instances with new
  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
    return $instance;
  }
}