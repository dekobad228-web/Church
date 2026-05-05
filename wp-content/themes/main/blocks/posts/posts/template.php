<?php
$type = get_field('type');
$group = get_field($type);

switch ($type):
    case "one":
        $number = $group['number'];

        $args = array(
            'post_type' => 'post',
            'category_name' => 'news',
            'posts_per_page' => $number ?? 3,
            'post_status' => 'publish',
            'orderby' => 'date',
            'order'   => 'DESC',
        );

        $query = new WP_Query($args);
?>

        <div class="section-post-one">
            <div class="container">
                <div class="section-post-one__block">
                    <h2 class="h1-400 section-post-one__title">
                        <?= $group['title']; ?>
                    </h2>
                    <div class="section-post-one__list">
                        <?php if ($query->have_posts()) :
                            while ($query->have_posts()) : $query->the_post(); ?>
                                <?php get_template_part("components/cards/news"); ?>
                        <?php endwhile;
                            wp_reset_postdata();
                        else :
                            echo '<h3 class="h3 section-post-one__empty">Нет записей</h3>';
                        endif;
                        ?>
                    </div>
                </div>
            </div>
        </div>

<?php
        break;
endswitch;
?>