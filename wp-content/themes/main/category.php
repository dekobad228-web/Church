<?php
get_header();
get_template_part("components/breadcrumbs");
$name = get_queried_object()->name;
$slug = get_queried_object()->slug;
$nbsp = html_entity_decode("&nbsp;");
$description = str_replace($nbsp, ' ', get_queried_object()->description);
?>
<section class="category">
    <div class="container">
        <div class="category__block">
            <div class="category__top">
                <h1 class="h1-400 category__title">
                    <?= $name ?>
                </h1>
                <?php if ($description) : ?>
                    <p class="p1-400 category__text">
                        <?= $description; ?>
                    </p>
                <?php endif; ?>
            </div>
            <?php get_template_part("templates/category/$slug"); ?>
        </div>
    </div>
</section>
<?php get_footer() ?>