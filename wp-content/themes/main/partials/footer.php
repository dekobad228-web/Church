<?php
$info = get_field("info", "option");
?>

<footer class="footer">
    <div class="footer-top">
        <div class="container">
            <div class="footer-top__block">
                <?php if ($info['logo_second']) : ?>
                    <a href="/" class="header-first__logo">
                        <img class="logo" src="<?= $info['logo_second']['url'] ?>" alt="<?= $info['logo_second']['alt'] ?>">
                    </a>
                <?php endif; ?>
                <div class="footer-top__columns">
                    <?php
                    wp_nav_menu([
                        'container'      => false,
                        'theme_location' => 'footer_col_one',
                        'menu_class'     => 'footer-top__col footer-top__col--one',
                        'fallback_cb'    => false
                    ]); ?>

                    <?php
                    wp_nav_menu([
                        'container'      => false,
                        'theme_location' => 'footer_col_two',
                        'menu_class'     => 'footer-top__col footer-top__col--two',
                        'fallback_cb'    => false
                    ]); ?>

                    <?php
                    wp_nav_menu([
                        'container'      => false,
                        'theme_location' => 'footer_col_three',
                        'menu_class'     => 'footer-top__col footer-top__col--three',
                        'fallback_cb'    => false
                    ]); ?>
                </div>
                <div class="footer-top__right">
                    <p class="p2-400 footer-top__title">Контакты</p>
                    <div class="footer-top__info">
                        <div class="footer-top__contacts">
                            <div class="footer-top__box">
                                <?php if ($info['address']) : ?>
                                    <div class="p3-400 footer-top__address">
                                        <?= $info['address']; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($info['work-time']) : ?>
                                    <div class="footer-top__clock">
                                        <?= $info['work-time']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="footer-top__tels">
                                <?php foreach ($info['tels'] as $tel) : ?>
                                    <a href="tel:<?= $tel['tel']; ?>" class=" p1-400 footer-top__tel">
                                        <?= $tel['tel']; ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php if (!empty($info['socials'])) : ?>
                            <div class="footer-top__socials">
                                <?php foreach ($info['socials'] as $value) : ?>
                                    <a href="<?= $value['link']; ?>" class="ui-icon footer-top__social">
                                        <?php if ($value['icon']) : ?>
                                            <img src="<?= $value['icon']['url'] ?>" class="ui-icon__image footer-top__icon" alt="<?= $value['name']; ?>">
                                        <?php endif; ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom__block">
                <p class="footer-bottom__copyright">
                    © 2026 Храм Живоначальной Троицы в Остафьеве
                </p>
                <div class="footer-bottom__right">
                    <a href="/politika-konfidenczialnosti/" class="footer-bottom__link">
                        Политика конфиденциальности
                    </a>
                    <a href="/politika-oferty/" class="footer-bottom__link">
                        Политика оферты
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>