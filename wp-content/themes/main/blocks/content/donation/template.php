<?php
$type = get_field('type');
$group = get_field($type);
switch ($type):
    case "one":
?>
        <section class="section-donation-one">
            <div class="container">
                <div class="section-donation-one__block">
                    <div class="section-donation-one__content">
                        <?php if ($group['title']) : ?>
                            <h2 class="h1-400 section-donation-one__title">
                                <?= $group['title']; ?>
                            </h2>
                        <?php endif; ?>
                        <?php if ($group['text']) : ?>
                            <p class="p2-400 section-donation-one__text">
                                <?= $group['text']; ?>
                            </p>
                        <?php endif; ?>
                        <?php if ($group['btn_text']) : ?>
                            <button class="btn btn--dark section-donation-one__btn">
                                <?= $group['btn_text']; ?>
                            </button>
                        <?php endif; ?>

                    </div>
                    <?php if ($group['image']) : ?>
                        <img src="<?= $group['image']['url']; ?>" alt="<?= $group['image']['alt']; ?>" class="section-donation-one__image">
                    <?php endif; ?>
                </div>
            </div>
        </section>
<?php
        break;
endswitch;
?>