<?php
$id = get_the_ID();
$link = get_permalink($id);
$title = get_the_title($id);
$thumbnail_id = get_post_thumbnail_id($id);
$image_url = wp_get_attachment_image_url($thumbnail_id, 'medium');
$image_alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
?>
<a href="<?= $link ?>" class="card-about">
    <img src="<?= $image_url ?>" alt="<?= $image_alt ?>" class="card-about__image">
    <h4 class="h4-400 card-about__title">
        <?= $title; ?>
    </h4>
</a>