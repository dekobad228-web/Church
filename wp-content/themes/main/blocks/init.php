<?php
function sort_blocks($a, $b)
{
  $a_path = $a['path'] . "/block.json";
  $b_path = $b['path'] . "/block.json";

  if (file_exists($a_path) && file_exists($b_path)) {
    $a_json = json_decode(file_get_contents($a_path), true);
    $b_json = json_decode(file_get_contents($b_path), true);

    if (isset($a_json['title']) && isset($b_json['title'])) {
      return strcasecmp($a_json['title'], $b_json['title']);
    }
  }

  return 0;
}

function get_blocks_array()
{
  $blocks = [];
  
  $all_items = array_diff(scandir(__DIR__), array(".", ".."));
  
  foreach ($all_items as $item) {
    $item_path = __DIR__ . "/" . $item;
    
    if (is_dir($item_path)) {
      $blocks_items = array_diff(scandir($item_path), array(".", ".."));
      
      foreach ($blocks_items as $block_item) {
        $block_path = $item_path . "/" . $block_item;
        $block_name = $item . "-" . $block_item;
        if (is_dir($block_path)) {
          $blocks[] = [
            'name' => $block_name,
            'path' => $block_path,
            'category' => $item 
          ];
        }
      }
    }
  }

  usort($blocks, 'sort_blocks');

  return $blocks;
}

function register_blocks()
{
  foreach (get_blocks_array() as $block) {
    $dir = $block['path'];
    $block_name = $block['name'];
    $json_file = "$dir/block.json";

    $title = "Без названия";
    $category = $block['category'];
    $icon = '';

    if (file_exists($json_file)) {
      $json = json_decode(file_get_contents($json_file), true);
      if (isset($json['title'])) {
        $title = $json['title'];
      }
      if (isset($json['icon'])) {
        $icon = $json['icon'];
      }
    }
    
    acf_register_block_type([
      'name'            => $block_name,
      'title'           => $title,
      'category'        => $category,
      'icon'            => $icon,
      'mode'            => 'edit',
      'supports'        => ['align' => false],
      'render_template' => $dir . "/template.php",
      'example'         => [
        'attributes' => [
          'mode' => 'preview',
          'data' => [
            '_sidebar_preview' => true,
          ],
        ],
      ],
    ]);
  }
}

add_action('acf/init', 'register_blocks');

function allowed_blocks()
{
  return array_map(function ($block) {
    return 'acf/' . $block['name'];
  }, get_blocks_array());
}
add_filter('allowed_block_types_all', 'allowed_blocks');

function filter_block_categories($categories)
{
  $categories = array(
    [
      'slug' => 'content',
      'title' => 'Основные блоки',
      'icon' => 'block-default'
    ],
    [
      'slug' => 'posts',
      'title' => 'Записи',
      'icon' => 'admin-post'
    ],
    [
      'slug' => 'editor',
      'title' => 'Визуальный редактор',
      'icon' => 'edit'
    ]
  );

  return $categories;
}
add_filter('block_categories_all', 'filter_block_categories', 100);

function load_style()
{
  wp_register_style('admin-fixes', get_bloginfo('template_url') . '/static/admin-fixes.css');
  wp_enqueue_style('admin-fixes');
}
add_action('enqueue_block_editor_assets', 'load_style', 100);