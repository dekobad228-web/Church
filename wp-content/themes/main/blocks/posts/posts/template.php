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

        <section class="section-post-one">
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
        </section>
    <?php
        break;
    case "two":
        $args = array(
            'post_type' => 'post',
            'category_name' => 'about',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'date',
            'order'   => 'ASC',
        );

        $query = new WP_Query($args); ?>
        <section class="section-post-two">
            <div class="container">
                <div class="section-post-two__block">
                    <h2 class="h1-400 section-post-two__title">
                        <?= $group['title']; ?>
                    </h2>
                    <div class="section-post-two__list">
                        <?php if ($query->have_posts()) :
                            while ($query->have_posts()) : $query->the_post(); ?>
                                <?php get_template_part("components/cards/about"); ?>
                        <?php endwhile;
                            wp_reset_postdata();
                        else :
                            echo '<h3 class="h3 section-post-two__empty">Нет записей</h3>';
                        endif;
                        ?>
                    </div>
                </div>
            </div>
        </section>

<?php
        break;
endswitch;
?>