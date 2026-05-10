<?php
$id = get_the_ID();
$title = get_the_title($id);
$description = get_field('description', $id);
?>

<div class="single-about">
    <div class="container">
        <div class="single-about__block">
            <h1 class="h1-400 single-about__title">
                <?= $title ?>
            </h1>
            <?php if ($description) : ?>
                <div class="content-text single-about__text">
                    <?= $description; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>