<?php

// Template Name: Task 

get_header();   ?>

<div class="pagination-work-center">

    <?php 
        // Pagination
        
        $curentpage = get_query_var('paged');
        $args = array
        (
            'post_type'      => 'students',
            'posts_per_page' => '3',
            'publish_status' => 'published',
            'paged'          => $curentpage
        );

    $query = new WP_Query($args);
        
    ?>  

</div>

<center>
    
<form action="/" method="get"  autocomplete="on" class="form_template">

    <input type="text" name="s" placeholder="Search ..." id="keyword" class="input_search" >

</form>

<select class="custom-select" id="mySelection">
    <option value="">-- Select --</option>
    <option value="id">Order ID</option>
    <option value="new">New Date</option>
    <option value="old">Old Date</option>
    <option value="asc">Order A to Z</option>
    <option value="desc">Order Z to A</option>
    
    

</select>

</center>

<?php

// Loop For Post

if($query->have_posts()) ?>

    <div class="search_result" id="datafetch">
    
    <?php
    
    while ( $query->have_posts()){
        $query->the_post();
        
    ?>

    <center>

    <div class="template_data">
     
        <h2><?php the_title(); ?></h2>
        <p><?php the_excerpt(); ?></p>
        <h5> <?php  $name = get_post_meta($post->ID,"post_name",true) ?>
        <?php echo $name ?> </h5>
        <h6> <?php  $date = get_post_meta($post->ID,"post_date",true) ?>
        <?php echo $date ?> </h6>

    </div>

    </center>
   
<?php             
}
?>
<br>

<center>
<div class="pagination">
    
    <?php
    echo paginate_links( array(
         
        'total' => $query->max_num_pages
    ) );

    ?>
    
</div>
</center>

</div>

</div>

<?php 

get_footer();

?>
