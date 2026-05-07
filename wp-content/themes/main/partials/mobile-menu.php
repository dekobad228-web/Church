<?php
$info = get_field('info', 'option');
?>
<div class="mobile-menu">
    <div class="container mobile-menu__container">
        <div class="mobile-menu__block">
            <div class="mobile-menu__menu">
                <?php
                wp_nav_menu([
                    'container'      => false,
                    'theme_location' => 'header_menu_left',
                    'menu_class'     => 'mobile-menu__nav',
                    'fallback_cb'    => false
                ]); ?>
                <?php
                wp_nav_menu([
                    'container'      => false,
                    'theme_location' => 'header_menu_right',
                    'menu_class'     => 'mobile-menu__nav',
                    'fallback_cb'    => false
                ]); ?>
            </div>
            <?php foreach ($info['tels'] as $tel) : ?>
                <a href="tel:<?= $tel['tel']; ?>" class="h4-400 mobile-menu__tel">
                    <?= $tel['tel']; ?>
                </a>
            <?php endforeach; ?>
            <div class="mobile-menu__socials">
                <?php foreach ($info['socials'] as $value) : ?>
                    <a href="<?= $value['link']; ?>" class="ui-icon mobile-menu__social">
                        <?php if ($value['icon']) : ?>
                            <img src="<?= $value['icon']['url'] ?>" class="ui-icon__image mobile-menu__social" alt="<?= $value['name']; ?>">
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
</div>