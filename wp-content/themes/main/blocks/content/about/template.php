<?php
$type = get_field('type');
$group = get_field($type);
switch ($type):
    case "history":
        $nbsp = html_entity_decode("&nbsp;");
?>
        <section class="section-about-history">
            <div class="container">
                <div class="section-about-history__block">
                    <?php foreach ($group as $history) : ?>
                        <?php
                        $text = str_replace($nbsp, ' ', $history['text']);
                        $image_sign = str_replace($nbsp, ' ', $history['image_sign']);
                        ?>
                        <div class="section-about-history__card">
                            <?php if ($history['years']) : ?>
                                <h2 class="h2-400 section-about-history__years">
                                    <?= $history['years'] ?>
                                </h2>
                            <?php endif; ?>
                            <div class="section-about-history__marker"></div>
                            <div class="section-about-history__content">
                                <?php if ($history['title']) : ?>
                                    <h4 class="h3-400 section-about-history__title">
                                        <?= $history['title'] ?>
                                    </h4>
                                <?php endif; ?>
                                <div class="section-about-history__box section-about-history__box--<?= $history['image_position'] ?>">
                                    <?php if ($text) : ?>
                                        <div class="content-text section-about-history__text">
                                            <?= $text ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($history['image']) : ?>
                                        <div class="section-about-history__picture">
                                            <img src="<?= $history['image']['url'] ?>" class="section-about-history__image" alt="<?= $history['image']['alt'] ?>">
                                            <?php if ($image_sign) : ?>
                                                <p class="p3-400 section-about-history__sign">
                                                    <?= $image_sign ?>
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
    case "clerics":
    ?>
        <section class="section-clerics">
            <div class="container">
                <div class="section-clerics__block" x-data="{ active: 0 }">
                    <div class="section-clerics__sidebar">
                        <?php foreach ($group as $index => $cleric) : ?>
                            <div class="section-clerics__sidebar-card"
                                :class="{ 'is-active': active === <?= $index ?> }"
                                @click="active = <?= $index ?>">
                                <?php if ($cleric['image']) : ?>
                                    <img src="<?= $cleric['image']['sizes']['thumbnail'] ?>" class="section-clerics__sidebar-image" alt="<?= $cleric['image']['alt'] ?>">
                                <?php endif; ?>
                                <?php if ($cleric['name'] || $cleric['post']) : ?>
                                    <div class="section-clerics__sidebar-box">
                                        <?php if ($cleric['name']) : ?>
                                            <h4 class="h4-400 section-clerics__sidebar-name">
                                                <?= $cleric['name'] ?>
                                            </h4>
                                        <?php endif; ?>
                                        <?php if ($cleric['post']) : ?>
                                            <p class="p2-400 section-clerics__sidebar-post">
                                                <?= $cleric['post'] ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <svg class="section-clerics__sidebar-icon" width="13" height="20" viewBox="0 0 13 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0.668945 18.7433L10.6689 9.74329L0.668946 0.743286" stroke="#EEBC6B" stroke-width="2" />
                                </svg>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="section-clerics__tabs">
                        <?php foreach ($group as $index => $cleric) : ?>
                            <div class="section-clerics__tab"
                                x-show="active === <?= $index ?>"
                                x-transition>
                                <div class="section-clerics__tab-top">
                                    <?php if ($cleric['image']) : ?>
                                        <img src="<?= $cleric['image']['url'] ?>" class="section-clerics__tab-image" alt="<?= $cleric['image']['alt'] ?>">
                                    <?php endif; ?>
                                    <?php if ($cleric['name'] || $cleric['post'] || !empty($cleric['dates'])) : ?>
                                        <div class="section-clerics__tab-box">
                                            <?php if ($cleric['name'] || $cleric['post']) : ?>
                                                <div class="section-clerics__tab-info">
                                                    <?php if ($cleric['name']) : ?>
                                                        <h3 class="h3-400 section-clerics__tab-name">
                                                            <?= $cleric['name'] ?>
                                                        </h3>
                                                    <?php endif; ?>
                                                    <?php if ($cleric['post']) : ?>
                                                        <p class="p2-400 section-clerics__tab-post">
                                                            <?= $cleric['post'] ?>
                                                        </p>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($cleric['dates'])) : ?>
                                                <div class="section-clerics__tab-dates">
                                                    <?php foreach ($cleric['dates'] as $date) : ?>
                                                        <p class="p2-400 section-clerics__tab-date">
                                                            <?= $date['date'] ?>
                                                        </p>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php if ($cleric['content']): ?>
                                    <div class="content-text section-clerics__tab-content">
                                        <?= $cleric['content'] ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>
<?php
        break;
endswitch;
?>