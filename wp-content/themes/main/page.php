<?php
get_header();
if (!is_front_page()) {
    get_template_part("components/breadcrumbs");
}
the_content();
get_footer();
