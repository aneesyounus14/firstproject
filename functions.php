<?php

/*
* Creating a function to create our CPT
*/
  
function custom_post_type() {
  
        $labels = array(
            'name'                => _x( 'students', 'Post Type General Name', 'twentytwentyone' ),
            'singular_name'       => _x( 'students', 'Post Type Singular Name', 'twentytwentyone' ),
            'menu_name'           => __( 'students', 'twentytwentyone' ),
            'parent_item_colon'   => __( 'Parent students', 'twentytwentyone' ),
            'all_items'           => __( 'All students', 'twentytwentyone' ),
            'view_item'           => __( 'View students', 'twentytwentyone' ),
            'add_new_item'        => __( 'Add New students', 'twentytwentyone' ),
            'add_new'             => __( 'Add New', 'twentytwentyone' ),
            'edit_item'           => __( 'Edit students', 'twentytwentyone' ),
            'update_item'         => __( 'Update students', 'twentytwentyone' ),
            'search_items'        => __( 'Search students', 'twentytwentyone' ),
            'not_found'           => __( 'Not Found', 'twentytwentyone' ),
            'not_found_in_trash'  => __( 'Not found in Trash', 'twentytwentyone' ),
        );
          
    // Set other options for Custom Post Type
          
        $args = array(
            'label'               => __( 'students', 'twentytwentyone' ),
            'description'         => __( 'students news and reviews', 'twentytwentyone' ),
            'labels'              => $labels,
            // Features this CPT supports in Post Editor
            'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
            // You can associate this CPT with a taxonomy or custom taxonomy. 
            'taxonomies'          => array( 'genres' ),
            'taxonomies'          => array('topics', 'category','post_tag'),
            /* A hierarchical CPT is like Pages and can have
            * Parent and child items. A non-hierarchical CPT
            * is like Posts.
            */
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
            'show_in_rest' => true,
      
        );
          
        // Registering your Custom Post Type
        register_post_type( 'students', $args );
      
    }
      
    add_action( 'init', 'custom_post_type', 0 );

/*
* CLOSE CPT
*/

// METABOX ADDING

function register_metabox(){

	add_meta_box( "cpt-id", "Additional Data For Students", "call_metabox","students","normal","high");

}

add_action("add_meta_boxes","register_metabox");

function call_metabox($post){
    ?>
      <p>
    
        <div class="meta-student-name">
            <label> Student Name </label>	
            <?php  $name = get_post_meta($post->ID,"post_name",true) ?>
            <input type="text" value="<?php echo $name ?>" name="textName" placeholder="Name"/><br>
        </div>
    
        <div class="meta-student-name">
            <label> Student Date of Enrollment </label>
            <?php  $sdate = get_post_meta($post->ID,"post_date",true) ?>
            <input type="date" id="birthday" name="sbirthday" value="<?php echo $sdate ?>">
           </div>
        
      </p>
    
    
    <?php
    
    }
    add_action("save_post","save_values",10,2);

    function save_values($post_id, $post){

        $textName = isset($_POST['textName'])?$_POST['textName']:"";
        $sbirthday = isset($_POST['sbirthday'])?$_POST['sbirthday']:"";
     
     
        update_post_meta( $post_id,"post_name",$textName);
        update_post_meta( $post_id,"post_date",$sbirthday);
     
    }

    function diwp_create_shortcode_movies_post_type(){
  
        $args = array(
                        'post_type'      => 'students',
                        'posts_per_page' => '3',
                        'publish_status' => 'published',
                     );
      
        $query = new WP_Query($args);
      
        if($query->have_posts()) :
      
            while($query->have_posts()) :
      
                $query->the_post() ;
                          
            $result .= '<div class="movie-item">';
                $result .= '<div class="movie-poster">' . get_the_post_thumbnail() . '</div>';
                $result .= '<div class="movie-name">' . get_the_title() . '</div>';
                $result .= '<div class="movie-desc">' . get_the_content() . '</div>'; 
            $result .= '</div>';
      
            endwhile;
      
            wp_reset_postdata();
      
        endif;    
      
        return $result;            
    }
      
    add_shortcode( 'students_list', 'diwp_create_shortcode_movies_post_type' ); 

//Equeue Styles And Js Enqueue

function themeslug_enqueue() {
    
	wp_enqueue_script( 'custom_js', get_stylesheet_directory_uri() . "/js/script.js" ,array ('jquery') );
    wp_localize_script('custom_js', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ));

		wp_enqueue_style('parent',get_template_directory_uri().'/style.css');
		wp_enqueue_style( 'child',get_stylesheet_uri());
}
add_action( 'wp_enqueue_scripts', 'themeslug_enqueue');

// SEARCHING WORK START

// the ajax function

add_action('wp_ajax_data_fetch' , 'data_fetch');
add_action('wp_ajax_nopriv_data_fetch','data_fetch');

function data_fetch(){

	$curentpage = get_query_var('paged');
    $the_query = new WP_Query( array( 'posts_per_page' => 3, 's' => esc_attr( $_POST['keyword'] ), 'post_type' => 'students','post' => $curentpage,'publish_status' => 'published', ) );
    
    if( $the_query->have_posts() ) :
        while( $the_query->have_posts() ): $the_query->the_post(); ?>

    <center>

    <div class="template_data">
     
        <h2><?php the_title(); ?></h2>
        <p><?php the_excerpt(); ?></p>
        <h5> <?php  $name = get_post_meta(get_the_ID(),'post_name',true); ?>
        <?php echo $name ?> </h5>
        <h6> <?php  $date = get_post_meta(get_the_ID(),"post_date",true) ?>
        <?php echo $date ?> </h6>
        
   
    </div>

    </center>

    <?php endwhile;
		wp_reset_postdata();

    
	else: 
		echo '<h3>No Results Found</h3>';
    endif;

}


// ORDER WORK

add_action('wp_ajax_data_drop' , 'data_drop');
add_action('wp_ajax_nopriv_data_drop' , 'data_drop');

function data_drop() {

    $curentpage = get_query_var('paged');

    $args= array( 
    	'paged' => $curentpage,
        'posts_per_page' => 3, 
        'orderby' => 'meta_value ID',
        'order' => 'ASC',
        'post_type' => array('students') );

    if ($_POST['keyword'] == 'id') {

        $args['order'] = 'ASC';

    }
    elseif ($_POST['keyword'] == 'asc') {

    	$args['order'] = 'ASC';

    }
    elseif ($_POST['keyword'] == 'desc') {

    	$args['order'] = 'DESC';

    }
    
    if ($_POST['keyword'] == 'old') {

    	$args['orderby'] = 'date';$args['order'] = 'ASC';

    }
    elseif ($_POST['keyword'] == 'new') {

   		$args['orderby'] = 'date';$args['order'] = 'DESC';

    }


	$the_query = new WP_Query($args);

    if( $the_query->have_posts() ) :

        ob_start();

        while( $the_query->have_posts() ): $the_query->the_post();  ?>

       <center>

        <div class="template_data">
 
            <h2><?php the_title(); ?></h2>
            <p><?php the_excerpt(); ?></p>
            <h5> <?php  $name = get_post_meta(get_the_ID(),'post_name',true); ?>
            <?php echo $name ?> </h5>
            <h6> <?php  $date = get_post_meta(get_the_ID(),'post_date',true) ?>
            <?php echo $date ?> </h6>

        </div>

    </center>

    <?php  
        endwhile; 

        $output_string = ob_get_contents();

        ob_end_clean();

        wp_die($output_string); 

        wp_reset_postdata(); 

        echo paginate_links(array(
            'total' => $query->max_num_pages
        ));

    endif;


}