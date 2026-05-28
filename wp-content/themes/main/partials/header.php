<!-- header -->
<?php
$info = get_field("info", "option");
?>
<header class="header">

    <div class="header__block">
        <?php
        wp_nav_menu([
            'container'      => false,
            'theme_location' => 'header_menu_left',
            'menu_class'     => 'header__nav header__nav--left',
            'fallback_cb'    => false
        ]); ?>
        <?php if ($info['logo']) : ?>
            <a href="/" class="header__link">
                <?php if ($info['logo']) : ?>
                    <img src="<?= $info['logo']['url'] ?>"
                        alt="<?= $info['logo']['alt'] ?>"
                        class="header__logo">
                <?php endif; ?>
            </a>
        <?php endif; ?>
        <?php
        wp_nav_menu([
            'container'      => false,
            'theme_location' => 'header_menu_right',
            'menu_class'     => 'header__nav header__nav--right',
            'fallback_cb'    => false
        ]); ?>
        <div class="burger header__burger">
            <span></span>
            <span></span>
        </div>
    </div>

</header>