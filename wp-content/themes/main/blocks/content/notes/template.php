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
        break; ?>
    <?php
    case "two":
        $title = $group['title'];
        $icon = $group['icon'];
        $notes = $group['notes'];
    ?>
        <section class="section-notes-two">
            <div class="container">
                <div class="section-notes-two__block">
                    <?php if ($icon) : ?>
                        <img src="<?= $icon['url'] ?>" class="section-notes-two__icon" alt="<?= $icon['alt'] ?>">
                    <?php endif; ?>
                    <?php if ($title) : ?>
                        <h2 class="h2-400 section-notes-two__title">
                            <?= $title; ?>
                        </h2>
                    <?php endif; ?>
                    <form action="" class="section-notes-two__form" hx-post="<?= AJAX_PATH ?>" method="POST" hx-indicator="find .btn-submit">
                        <div class="section-notes-two__selects">
                            <div class="section-notes-two__select">
                                <input
                                    type="hidden"
                                    value="<?= $notes[0]['type'] ?>"
                                    class="section-notes-two__select-input"
                                    data-select-input=""
                                    name="type">
                                <p class="p2-400 section-notes-two__select-name">
                                    Тип поминовения
                                </p>
                                <div class="section-notes-two__dropdown">
                                    <div class="section-notes-two__dropdown-head"
                                        data-type="<?= $notes[0]['type'];  ?>">
                                        <p class="p2-400 section-notes-two__dropdown-name">
                                            <?= $notes[0]['type']; ?>
                                        </p>
                                        <img src="<?= get_template_directory_uri(); ?>/src/media/icons/arrow.svg"
                                            class="section-notes-two__dropdown-icon"
                                            alt="arrow">
                                    </div>
                                    <div class="section-notes-two__dropdown-body">
                                        <div class="section-notes-two__dropdown-content">
                                            <?php foreach ($notes as $value) : ?>
                                                <div class="p2-400 section-notes-two__dropdown-item"
                                                    data-color="<?= $value['color']; ?>"
                                                    data-value="<?= $value['type'] ?>"
                                                    data-text="<?= $value['type']; ?>">
                                                    <p class="p2-400 section-notes-two__dropdown-text">
                                                        <?= $value['type']; ?>
                                                    </p>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="section-notes-two__select">
                                <input
                                    type="hidden"
                                    value="<?= $notes[0]['commemorations'][0]['commemoration'] ?>" class="section-notes-two__select-input"
                                    data-select-input=""
                                    name="commemoration">
                                <p class="p2-400 section-notes-two__select-name">
                                    Поминовение
                                </p>
                                <div class="section-notes-two__dropdown">
                                    <div class="section-notes-two__dropdown-head"
                                        data-commemoration="<?= $notes[0]['commemorations'][0]['commemoration'];  ?>">
                                        <p class="p2-400 section-notes-two__dropdown-name">
                                            <?= $notes[0]['commemorations'][0]['commemoration']; ?>
                                        </p>
                                        <img src="<?= get_template_directory_uri(); ?>/src/media/icons/arrow.svg"
                                            class="section-notes-two__dropdown-icon"
                                            alt="arrow">
                                    </div>
                                    <div class="section-notes-two__dropdown-body">
                                        <div class="section-notes-two__dropdown-content">
                                            <?php foreach ($notes[0]['commemorations'] as $value) : ?>
                                                <div class="section-notes-two__dropdown-item"
                                                    data-value="<?= $value['commemoration'] ?>"
                                                    data-parent="<?= $notes[0]['type'];  ?>"
                                                    data-text="<?= $value['commemoration']; ?>">
                                                    <p class="p2-400 section-notes-two__dropdown-text">
                                                        <?= $value['commemoration']; ?>
                                                    </p>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span class="section-notes-two__line"></span>
                        <div class="section-notes-two__box">
                            <h1 class="h1-400 section-notes-two__type"
                                style="color: <?= $notes[0]['color'] ?>;">
                                <?= $notes[0]['type'] ?>
                            </h1>
                            <p class="p2-400 section-notes-two__commemoration">
                                <?= $notes[0]['commemorations'][0]['commemoration']; ?>
                            </p>
                        </div>
                        <div class="section-notes-two__columns">
                            <?php
                            $count = 1;
                            for ($i = 0; $i < 2; $i++) : ?>
                                <div class="section-notes-two__fields">
                                    <?php for ($j = 0; $j < 5; $j++) : ?>
                                        <label class="section-notes-two__field">
                                            <span class="p2-400 section-notes-two__field-number">
                                                <?= $count++ . '.'; ?>
                                            </span>
                                            <input
                                                type="text"
                                                placeholder="Имя" name="names-array[]"
                                                class="p2-400 section-notes-two__field-input">
                                        </label>
                                    <?php endfor; ?>
                                </div>
                            <?php endfor; ?>
                        </div>
                        <div class="personal section-notes-two__personal section-notes-two__personal--desk">
                            <div class="personal__checkbox">
                                <input type="checkbox" id="checkbox" name="checkbox" class="personal__input" required>
                                <svg class="personal__icon" width="23" height="19" viewBox="0 0 23 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 7.5L8.5 16.5L22 1" stroke="white" stroke-width="2" stroke-linecap="round" />
                                </svg>
                            </div>
                            <p class="p3 personal__text">
                                Я согласен(на)
                                <a target="_blank" href="/obrabotka-personalnyh-dannyh/" class="p3 personal__link">
                                    на обработку моих персональных данных
                                </a>
                            </p>
                        </div>
                        <div class="section-notes-two__donation">
                            <input type="hidden" name="donate" value="<?= $notes[0]['commemorations'][0]['donation']; ?>">
                            <div class="personal section-notes-two__personal section-notes-two__personal--mob">
                                <div class="personal__checkbox">
                                    <input type="checkbox" id="checkbox" name="checkbox" class="personal__input" required>
                                    <svg class="personal__icon" width="23" height="19" viewBox="0 0 23 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1 7.5L8.5 16.5L22 1" stroke="white" stroke-width="2" stroke-linecap="round" />
                                    </svg>
                                </div>
                                <p class="p3 personal__text">
                                    Я согласен(на)
                                    <a target="_blank" href="/obrabotka-personalnyh-dannyh/" class="p3 personal__link">
                                        на обработку моих персональных данных
                                    </a>
                                </p>
                            </div>
                            <h4 id="donation-value" class="h4-400 section-notes-two__donation-price">
                                <?= $notes[0]['commemorations'][0]['donation']; ?> рублей
                            </h4>
                            <button type="submit" class="btn btn--dark btn-submit section-notes-two__donation-btn" disabled>
                                Пожертвовать
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <?php break; ?>
<?php
endswitch;
?>