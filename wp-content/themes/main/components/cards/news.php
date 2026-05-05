<?php
$id = get_the_ID();
$link = get_permalink($id);
$title = get_the_title($id);
$thumbnail_id = get_post_thumbnail_id($id);
$image_url = wp_get_attachment_image_url($thumbnail_id, 'medium');
$image_alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
$post_date = get_the_date('j F', $id);
?>
<a href="<?= $link ?>" class="card-news">
    <img src="<?= $image_url ?>" alt="<?= $image_alt ?>" class="card-news__img">
    <div class="card-news__box">
        <p class="p1-400 card-news__title">
            <?= $title; ?>
        </p>
        <p class="p3-400 card-news__date"><?= $post_date; ?></p>
    </div>
</a>