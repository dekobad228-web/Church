<?php

/** dev or prod */
define('THEME_ENV', 'dev');

require_once __DIR__ . '/vendor/autoload.php';

require_once 'blocks/init.php';

\App\Init::init();


function remove_assets()
{
	wp_deregister_script('jquery');
	wp_deregister_style('wp-block-library');
	wp_deregister_style('classic-theme-styles');
}
remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
remove_action('wp_footer', 'wp_enqueue_global_styles', 1);

remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'wp_oembed_add_host_js');

function disable_emojis_tinymce($plugins)
{
	if (is_array($plugins)) {
		return array_diff($plugins, array('wpemoji'));
	}

	return array();
}
function disable_emojis_remove_dns_prefetch($urls, $relation_type)
{

	if ('dns-prefetch' == $relation_type) {

		// Strip out any URLs referencing the WordPress.org emoji location
		$emoji_svg_url_bit = 'https://s.w.org/images/core/emoji/';
		foreach ($urls as $key => $url) {
			if (strpos($url, $emoji_svg_url_bit) !== false) {
				unset($urls[$key]);
			}
		}
	}

	return $urls;
}

function pami_image_meta_first($my_image_title, $encoding = 'UTF-8')
{

	$my_image_title = mb_ereg_replace('^[\ ]+', '', $my_image_title);
	$my_image_title = mb_strtoupper(mb_substr($my_image_title, 0, 1, $encoding), $encoding) . mb_substr($my_image_title, 1, mb_strlen($my_image_title), $encoding);

	return $my_image_title;
}

function pami_set_image_meta_upon_upload($post_ID)
{

	if (!wp_attachment_is_image($post_ID))
		return;

	$my_image_title = get_post($post_ID)->post_title;

	// Sanitize the title: remove hyphens, underscores & extra spaces:
	$my_image_title = preg_replace('%\s*[-_\s]+\s*%', ' ', $my_image_title);

	// Sanitize the title: capitalize first letter of every word (other letters lower case):
	$my_image_title = str_replace('"', '', $my_image_title);
	$my_image_title = str_replace('«', '', $my_image_title);
	$my_image_title = str_replace('»', '', $my_image_title);
	$my_image_title = str_replace('—', '', $my_image_title);
	$my_image_title = str_replace(':', '', $my_image_title);
	$my_image_title = str_replace('  ', ' ', $my_image_title);
	$my_image_title = str_replace('   ', ' ', $my_image_title);

	$my_image_title = pami_image_meta_first(mb_strtolower($my_image_title));

	// Set the image Alt-Text
	update_post_meta($post_ID, '_wp_attachment_image_alt', $my_image_title);

	$my_image_title = mb_strtolower($my_image_title);

	$my_image_meta = [
		'ID' => $post_ID,
		'post_title' => 'Изображение — ' . $my_image_title, // Set image Title to sanitized title
	];

	// Set the image meta (e.g. Title, Excerpt, Content)
	wp_update_post($my_image_meta);
}

add_action('add_attachment', 'pami_set_image_meta_upon_upload');

add_theme_support('menus');
add_theme_support('title-tag');
add_theme_support('post-thumbnails');

function get_file_info($file_info)
{

	$mime_types = array(
		'application/msword' => 'doc',
		'image/jpeg' => 'jpg',
		'application/pdf' => 'pdf',
		'image/png' => 'png',
		'application/vnd.ms-powerpoint' => 'ppt',
		'application/x-rar-compressed' => 'rar',
		'image/tiff' => 'tiff',
		'text/plain' => 'txt',
		'application/vnd.ms-excel' => 'xls',
		'application/zip' => 'zip',
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
		'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
		'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
	);

	$file_size = array('b', 'kb', 'Mb');
	$file_info_output = array();
	$file_info_output['size'] = filesize(get_attached_file($file_info['id']));

	$i = 0;

	while ($file_info_output['size'] > 1024) {
		$file_info_output['size'] = $file_info_output['size'] / 1024;
		$i++;
	}

	$file_info_output['url'] = $file_info['url'];
	$file_info_output['size'] = round($file_info_output['size'], 2) . " " . $file_size[$i]; // Размер файла                           
	$file_info_output['mime'] = $mime_types[$file_info['mime_type']]; // Расширение файла

	if (is_null($file_info_output['mime']))
		$file_info_output['mime'] = 'none';

	$file_info_output['pathinfo'] = pathinfo(get_attached_file($file_info['id']));

	return $file_info_output;
}

add_action('init', function () {
	if (function_exists('acf_add_options_page')) {
		acf_add_options_page([
			'page_title' => 'Общие настройки сайта',
			'menu_title' => 'Общие настройки сайта',
			'menu_slug'  => 'general-settings',
			'capability' => 'edit_posts',
			'icon_url'   => 'dashicons-admin-generic',
			'redirect'   => true
		]);
	}
});

function register_menu()
{
	register_nav_menus([
		'header_menu_left' => 'Меню шапки слева',
		'header_menu_right' => 'Меню шапки справа',
		'footer_col_one' => 'Меню подвала (колонка 1)',
		'footer_col_two' => 'Меню подвала (колонка 2)',
		'footer_col_three' => 'Меню подвала (колонка 3)',
	]);
}
register_menu();
