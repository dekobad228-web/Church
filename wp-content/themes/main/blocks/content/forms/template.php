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
    case "two":
    ?>
        <section class="section-form-two">
            <div class="container">
                <div class="section-form-two__block">
                    <div class="section-form-two__content">
                        <h2 class="h1-400 section-form-two__title">
                            <?= $group['title']; ?>
                        </h2>
                        <p class="p2-400 section-form-two__text">
                            <?= $group['text']; ?>
                        </p>
                        <?php if ($group['btn_text']) : ?>
                            <button class="btn btn--dark section-form-two__btn">
                                <?= $group['btn_text']; ?>
                            </button>
                        <?php endif; ?>

                    </div>
                    <?php if ($group['image']) : ?>
                        <img src="<?= $group['image']['url']; ?>" alt="<?= $group['image']['alt']; ?>" class="section-form-two__image">
                    <?php endif; ?>
                </div>
            </div>
        </section>
<?php
        break;
endswitch;
?>