<?php
$type = get_field('type');
$group = get_field($type);
switch ($type):
    case "one":
?>
        <section class="section-banner-one">
            <div class="section-banner-one__block">
                <div class="section-banner-one__content">
                    <?php if ($group['title']) : ?>
                        <h1 class="h0-400 section-banner-one__title">
                            <?= $group['title']; ?>
                        </h1>
                    <?php endif; ?>
                    <?php if ($group['text']) : ?>
                        <div class="p1-400 section-banner-one__text">
                            <?= $group['text']; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="section-banner-one__img">
                    <?php if ($group['media_img_desk'] || $group['media_img_mob']) : ?>
                        <?php if ($group['media_img_desk']) : ?>
                            <img src="<?= $group['media_img_desk']['url']; ?>" alt="<?= $group['media_img_desk']['alt']; ?>" class="section-banner-one__img section-banner-one__img--desk">
                        <?php endif; ?>
                        <?php if ($group['media_img_mob']) : ?>
                            <img src="<?= $group['media_img_mob']['url']; ?>" alt="<?= $group['media_img_mob']['alt']; ?>" class="section-banner-one__img section-banner-one__img--mob">
                        <?php elseif ($group['media_img_desk']) : ?>
                            <img src="<?= $group['media_img_desk']['url']; ?>" alt="<?= $group['media_img_desk']['alt']; ?>" class="section-banner-one__img section-banner-one__img--mob">
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>
<?php
        break;
endswitch;
?>