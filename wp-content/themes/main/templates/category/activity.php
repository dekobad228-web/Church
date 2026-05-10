<?php
$slug = get_queried_object()->slug;
$args = [
    'post_type'      => 'post',
    'category_name'  => $slug,
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'ASC',
];
$query = new WP_Query($args);
?>
<div class="category-activity">
    <?php if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
    ?>
            <?php get_template_part("components/cards/$slug"); ?>
    <?php
        endwhile;
        wp_reset_postdata();
    else :
        echo 'В рубрике пока что нет записей';
    endif;
    ?>
</div>