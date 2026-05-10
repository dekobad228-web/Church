<?php
$type = get_field('type');
$group = get_field($type);
switch ($type):
    case "history":
?>
        <section class="section-activity-history">
            <div class="container">
                <div class="section-activity-history__block">

                </div>
            </div>
        </section>
    <?php
        break;
    case "shrines": ?>
        <section class="section-activity-shrines">
            <div class="container">
                <div class="section-activity-shrines__block">
                    <?php foreach ($group as $shrine) : ?>
                        <div class="section-activity-shrines__card">
                            <img src="<?= $shrine['image']['url'] ?>" class="section-activity-shrines__image" alt="<?= $shrine['image']['alt'] ?>">
                            <div class="section-activity-shrines__box">
                                <h4 class="h4-400 section-activity-shrines__name">
                                    <?= $shrine['name'] ?>
                                </h4>
                                <div class="content-text section-activity-shrines__text">
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