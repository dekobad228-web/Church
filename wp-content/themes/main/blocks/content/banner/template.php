<?php
$type = get_field('type');
$group = get_field($type);
switch ($type):
    case "one":
?>
        <section class="banner section-banner-one">
            <div class="container">
                <div class="section-banner-one__block">
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
            </div>
            <div class="section-banner-one__image">
                <?php if ($group['image'] || $group['image_mob']) : ?>
                    <?php if ($group['image']) : ?>
                        <img src="<?= $group['image']['url']; ?>" alt="<?= $group['image']['alt']; ?>" class="section-banner-one__image section-banner-one__image--desk">
                    <?php endif; ?>
                    <?php if ($group['image_mob']) : ?>
                        <img src="<?= $group['image_mob']['url']; ?>" alt="<?= $group['image_mob']['alt']; ?>" class="section-banner-one__image section-banner-one__image--mob">
                    <?php elseif ($group['image']) : ?>
                        <img src="<?= $group['image']['url']; ?>" alt="<?= $group['image']['alt']; ?>" class="section-banner-one__image section-banner-one__image--mob">
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </section>
<?php
        break;
endswitch;
?>