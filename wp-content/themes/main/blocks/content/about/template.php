<?php
$type = get_field('type');
$group = get_field($type);
switch ($type):
    case "history":
?>
        <section class="section-about-history">
            <div class="container">
                <div class="section-about-history__block">
                    <?php foreach ($group as $history) : ?>
                        <div class="section-about-history__card">
                            <?php if ($history['years']) : ?>
                                <h2 class="h2-400 section-about-history__years">
                                    <?= $history['years'] ?>
                                </h2>
                            <?php endif; ?>
                            <span class="section-about-history__marker"></span>
                            <div class="section-about-history__content">
                                <?php if ($history['title']) : ?>
                                    <h4 class="h3-400 section-about-history__title">
                                        <?= $history['title'] ?>
                                    </h4>
                                <?php endif; ?>
                                <div class="section-about-history__box section-about-history__box--<?= $history['image_position'] ?>">
                                    <?php if ($history['text']) : ?>
                                        <div class="content-text section-about-history__text">
                                            <?= $history['text'] ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($history['image']) : ?>
                                        <div class="section-about-history__picture">
                                            <img src="<?= $history['image']['url'] ?>" class="section-about-history__image" alt="<?= $history['image']['alt'] ?>">
                                            <?php if ($history['image_sign']) : ?>
                                                <p class="p3-400 section-about-history__sign">
                                                    <?= $history['image_sign'] ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php
        break;
    case "shrines": ?>
        <section class="section-about-shrines">
            <div class="container">
                <div class="section-about-shrines__block">
                    <?php foreach ($group as $shrine) : ?>
                        <div class="section-about-shrines__card">
                            <img src="<?= $shrine['image']['url'] ?>" class="section-about-shrines__image" alt="<?= $shrine['image']['alt'] ?>">
                            <div class="section-about-shrines__box">
                                <h4 class="h4-400 section-about-shrines__name">
                                    <?= $shrine['name'] ?>
                                </h4>
                                <div class="content-text section-about-shrines__text">
                                    <?= $shrine['text'] ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
<?php
        break;
endswitch;
?>