<?php
$type = get_field('type');
$group = get_field($type);
switch ($type):
    case "one":
?>
        <section class="section-notes-one">
            <div class="section-notes-one__background">
                <?php if ($group['image']) : ?>
                    <img src="<?= $group['image']['url']; ?>" alt="<?= $group['image']['alt']; ?>" class="section-notes-one__image">
                <?php endif; ?>
            </div>
            <div class="container">
                <div class="section-notes-one__block">
                    <div class="section-notes-one__content">
                        <?php if ($group['title']) : ?>
                            <h2 class="h1-400 section-notes-one__title">
                                <?= $group['title']; ?>
                            </h2>
                        <?php endif; ?>
                        <?php if ($group['text']) : ?>
                            <div class="p1-400 section-notes-one__text">
                                <?= $group['text']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if ($group['btn_text']) : ?>
                        <a href="<?= $group['btn_link']; ?>" class="btn btn--light section-notes-one__btn">
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