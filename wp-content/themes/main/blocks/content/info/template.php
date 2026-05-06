<?php
$type = get_field('type');
$group = get_field($type);
switch ($type):
    case "one":
?>
        <section class="section-info-one">
            <div class="container">
                <div class="section-info-one__block">
                    <?php if ($group['title']) : ?>
                        <h2 class="h1-400 section-info-one__title">
                            <?= $group['title']; ?>
                        </h2>
                    <?php endif; ?>
                    <div class="section-info-one__content">
                        <?php if ($group['image']) : ?>
                            <img src="<?= $group['image']['url']; ?>" alt="<?= $group['image']['alt']; ?>" class="section-info-one__image">
                        <?php endif; ?>
                        <div class="section-info-one__events">
                            <?php foreach ($group['events'] as $event) : ?>
                                <div class="section-info-one__card">
                                    <?php if ($event['event_image']) : ?>
                                        <img src="<?= $event['event_image']['url']; ?>" alt="<?= $event['event_image']['alt']; ?>" class="section-info-one__card-image">
                                    <?php endif; ?>
                                    <div class="section-info-one__box">
                                        <?php if ($event['event_name']) : ?>
                                            <p class="p1-400 section-info-one__box-name">
                                                <?= $event['event_name'] ?>
                                            </p>
                                        <?php endif; ?>
                                        <?php if ($event['event_text']) : ?>
                                            <p class="p3-400 section-info-one__box-text">
                                                <?= $event['event_text'] ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
<?php
        break;
endswitch;
?>