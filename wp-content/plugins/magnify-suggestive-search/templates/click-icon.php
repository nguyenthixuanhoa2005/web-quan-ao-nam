<?php
if (!defined('ABSPATH'))
    exit;

$icon_picker = isset($search_bar_data['icon_picker']) ? $search_bar_data['icon_picker'] : 'fas fa-search';
$post_types = isset($search_bar_data['post_types']) ? $search_bar_data['post_types'] : 'post';
$placeholder_text = isset($search_bar_data['mnssp_settings']['placeholder_text']) ? $search_bar_data['mnssp_settings']['placeholder_text'] : 'Search...';
$icon_color = isset($search_bar_data['mnssp_settings']['icon_color']) ? $search_bar_data['mnssp_settings']['icon_color'] : '#ffffff';

$icon_bg_color = isset($search_bar_data['mnssp_settings']['icon_bg_color']) ? $search_bar_data['mnssp_settings']['icon_bg_color'] : '#000000';
$placeholder_color = isset($search_bar_data['mnssp_settings']['placeholder_color']) ? $search_bar_data['mnssp_settings']['placeholder_color'] : '';

$search_bar_width = isset($search_bar_data['search_bar_width']) ? $search_bar_data['search_bar_width'] : 'auto';
$custom_width = isset($search_bar_data['custom_width']) ? $search_bar_data['custom_width'] : '400px';
$limit_per_page = isset($search_bar_data['limit_per_page']) ? $search_bar_data['limit_per_page'] : 10;

$active_width = '250px';
if ($search_bar_width === 'full') {
    $active_width = '100%';
} elseif ($search_bar_width === 'custom') {
    $active_width = esc_attr($custom_width);
}

$custom_css = "#click-icon.mnssp-wrap.active { width: {$active_width} !important; }";

$search_bar_height = isset($search_bar_data['mnssp_settings']['search_bar_height']) ? $search_bar_data['mnssp_settings']['search_bar_height'] : 'medium';
$custom_height = isset($search_bar_data['mnssp_settings']['custom_height']) ? $search_bar_data['mnssp_settings']['custom_height'] : '40px';

$height_style = '';
if ($search_bar_height === 'small') {
    $height_style = 'height: 30px;';
} elseif ($search_bar_height === 'medium') {
    $height_style = 'height: 50px;';
} elseif ($search_bar_height === 'large') {
    $height_style = 'height: 60px;';
} elseif ($search_bar_height === 'custom') {
    $height_style = 'height: ' . esc_attr($custom_height) . ';';
} else {
    $height_style = 'height: 50px;';
}

$search_scope = isset($search_bar_data['search_scope']) ? $search_bar_data['search_scope'] : 'title';
$priority = isset($search_bar_data['priority']) ? $search_bar_data['priority'] : 'relevance';
$exclude_ids = isset($search_bar_data['exclude_ids']) ? $search_bar_data['exclude_ids'] : '';
$exclude_categories = isset($search_bar_data['exclude_categories']) ? $search_bar_data['exclude_categories'] : '';

add_action('wp_enqueue_scripts', function () use ($custom_css) {
    wp_add_inline_style('mnssp-click-icon', $custom_css);
});
?>
<div class="mnssp-wrap  mnssp-search-bar" id="click-icon"
    style="background: <?php echo esc_attr($icon_bg_color); ?>; <?php echo esc_attr($height_style); ?>">


    <form id="click-icon" role="search" method="get" class="search-form click-icon"
        action="<?php echo esc_url(home_url('/')); ?>">
        <input type="search" class="search-input" placeholder="<?php echo esc_html($placeholder_text); ?>"
            value="<?php echo get_search_query(); ?>" name="s"
            style="color: <?php echo esc_attr($placeholder_color); ?>;">
        <input type="hidden" name="post_type" value="<?php echo esc_attr($post_types); ?>">

        <input type="hidden" name="search_scope" value="<?php echo esc_attr($search_scope); ?>">
        <input type="hidden" name="priority" value="<?php echo esc_attr($priority); ?>">
        <input type="hidden" name="exclude_ids" value="<?php echo esc_attr($exclude_ids); ?>">
        <input type="hidden" name="exclude_categories" value="<?php echo esc_attr($exclude_categories); ?>">
        <input type="hidden" name="source" value="<?php echo esc_attr('magnify-suggestive-search'); ?>">
        <input type="hidden" name="limit_per_page" value="<?php echo esc_attr($limit_per_page); ?>">


        <?php wp_nonce_field('mnssp_search_nonce'); ?>
    </form>
    <i class="<?php echo esc_attr($icon_picker); ?> mnssp-icon mnssp-search-icon" aria-hidden="true"
        style="color: <?php echo esc_attr($icon_color); ?>;"></i>
</div>