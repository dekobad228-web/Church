<?php
$id = get_the_ID();
$title = get_the_title($id);
$thumbnail_id = get_post_thumbnail_id($id);
$image_url = wp_get_attachment_image_url($thumbnail_id, 'full');
$image_alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
$description = get_field('description', $id);
$content = get_field('content', $id);
?>
<section class="single-activity">
    <div class="container">
        <div class="single-activity__block">
            <div class="single-activity__top">
                <div class="single-activity__left">
                    <h1 class="h1-400 single-activity__title">
                        <?= $title ?>
                    </h1>
                    <?php if ($description) : ?>
                        <div class="content-text single-activity__description">
                            <?= $description ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if ($image_url) : ?>
                    <img src="<?= $image_url ?>" class="single-activity__image" alt="<?= $image_alt ?>">
                <?php endif; ?>
            </div>
            <?php if ($content) : ?>
                <div class="content-text single-activity__content">
                    <?= $content ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>