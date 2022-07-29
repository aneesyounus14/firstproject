<?php

    get_header();

    $curentpage = get_query_var('paged');
    $args = array
    (
        'post_type'      => 'students',
        'posts_per_page' => '3',
        'publish_status' => 'published',
        'paged' => $curentpage
    );


    $query = new WP_Query($args);

    if ($query->have_posts() ) :

        while ( $query->have_posts() ) : $query->the_post(); ?>

            <center>
            <div class="cptcontainer">

                <h1><?php the_title(); ?></h1>
                <h6><?php the_content(); ?></h6>
                <p><?php  the_excerpt(); ?></p>
                <h5> <?php  $name = get_post_meta($post->ID,"post_name",true) ?>
                <?php echo $name ?> </h5>
                <h5> <?php  $date = get_post_meta($post->ID,"post_date",true) ?>
                <?php echo $date ?> </h5>

            </div>
            </center>

        <?php endwhile;
        
        echo "<div class='containerPagination'>";
        echo "<center>";
        echo paginate_links(array(
            'total' => $query->max_num_pages
        ));
        echo "</div>";

    endif;

   

    get_footer();
?>
