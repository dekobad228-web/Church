<?php
$type = get_field('type');
$group = get_field($type);
switch ($type):
    case "one":
?>
        <section class="section-form-one">
            <div class="section-form-one__background">
                <?php if ($group['img']) : ?>
                    <img src="<?= $group['img']['url']; ?>" alt="<?= $group['img']['alt']; ?>" class="section-form-one__image">
                <?php endif; ?>
            </div>
            <div class="container">
                <div class="section-form-one__block">
                    <div class="section-form-one__content">
                        <?php if ($group['title']) : ?>
                            <h2 class="h1-400 section-form-one__title">
                                <?= $group['title']; ?>
                            </h2>
                        <?php endif; ?>
                        <?php if ($group['text']) : ?>
                            <div class="p1-400 section-form-one__text">
                                <?= $group['text']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if ($group['btn_text']) : ?>
                        <a href="<?= $group['btn_link']; ?>" class="btn btn--light section-form-one__btn">
                            <?= $group['btn_text']; ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </section>
<?php
        break;
endswitch;
?>