<?php
$content = get_field('content');
$nbsp = html_entity_decode("&nbsp;");
$content = str_replace($nbsp, ' ', $content);
?>
<section class="section-content">
    <div class="container">
        <div class="content-text section-content__block">
            <?= $content; ?>
        </div>
    </div>
</section>