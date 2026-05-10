<?php
get_header();
get_template_part("components/breadcrumbs");
$categories = get_the_category();
$category = $categories[0]->slug;
get_template_part("templates/single/$category");
the_content();
get_footer();
