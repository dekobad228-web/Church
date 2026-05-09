<?php
$info = get_field('info', 'option');
$type = get_field('type');
$group = get_field($type);
switch ($type):
    case "one":
?>
        <?php
        $zoom = $group['zoom'] ?? 12;
        ?>
        <section class="section-contacts-one">
            <div class="container">
                <div class="section-contacts-one__block">
                    <div class="section-contacts-one__left">
                        <?php if ($group['title']) : ?>
                            <h2 class="h1-400 section-contacts-one__title">
                                <?= $group['title'] ?>
                            </h2>
                        <?php endif; ?>
                        <div class="section-contacts-one__info">
                            <?php if ($info['address'] || $info['work-time']) : ?>
                                <div class="section-contacts-one__box">
                                    <?php if ($info['address']) : ?>
                                        <p class="p1-400 section-contacts-one__address">
                                            <?= $info['address'] ?>
                                        </p>
                                    <?php endif; ?>
                                    <?php if ($info['work-time']) : ?>
                                        <p class="p3-400 section-contacts-one__work-time">
                                            <?= $info['work-time'] ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <?php foreach ($info['tels'] as $tel) : ?>
                                <a href="tel:<?= $tel['tel'] ?>" class="h4-400 section-contacts-one__tel">
                                    <?= $tel['tel'] ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        <div class="section-contacts-one__socials">
                            <?php foreach ($info['socials'] as $value) : ?>
                                <a href="<?= $value['link']; ?>" class="ui-icon section-contacts-one__social">
                                    <?php if ($value['icon']) : ?>
                                        <img src="<?= $value['icon']['url'] ?>" class="ui-icon__image section-contacts-one__social" alt="<?= $value['name']; ?>">
                                    <?php endif; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php if ($group['coords']) : ?>
                        <?php $coords = explode(',', trim($group['coords'])); ?>
                        <div x-data="Contacts([[<?= (float) $coords[1] ?>, <?= (float) $coords[0] ?>]], <?= $zoom ?>)" class="section-contacts-one__map">
                            <div id="map" x-ref="mapRoot"></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
<?php
        break;
endswitch;
?>