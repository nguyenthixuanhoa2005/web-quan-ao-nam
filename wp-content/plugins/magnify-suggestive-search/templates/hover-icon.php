<?php
if (!defined('ABSPATH'))
    exit;

$icon_picker = isset($search_bar_data['icon_picker']) ? $search_bar_data['icon_picker'] : 'fas fa-search';
$post_types = isset($search_bar_data['post_types']) ? $search_bar_data['post_types'] : 'post';
$placeholder_text = isset($search_bar_data['mnssp_settings']['placeholder_text']) ? $search_bar_data['mnssp_settings']['placeholder_text'] : 'Search...';
$icon_color = isset($search_bar_data['mnssp_settings']['icon_color']) ? $search_bar_data['mnssp_settings']['icon_color'] : '#ffffff';
$placeholder_color = isset($search_bar_data['mnssp_settings']['placeholder_color']) ? $search_bar_data['mnssp_settings']['placeholder_color'] : '';

$border_color = isset($search_bar_data['mnssp_settings']['border_color']) ? $search_bar_data['mnssp_settings']['border_color'] : '#e7f5ff';
$search_bar_width = isset($search_bar_data['search_bar_width']) ? $search_bar_data['search_bar_width'] : 'auto';
$custom_width = isset($search_bar_data['custom_width']) ? $search_bar_data['custom_width'] : '400px';
$limit_per_page = isset($search_bar_data['limit_per_page']) ? $search_bar_data['limit_per_page'] : 10;


$hover_width = '200px';
if ($search_bar_width === 'full') {
    $hover_width = '100%';
} elseif ($search_bar_width === 'custom') {
    $hover_width = esc_attr($custom_width);
}

$custom_css = "#hover-icon .search-box:hover > .search-input { width: {$hover_width} !important; }";
$search_scope = isset($search_bar_data['search_scope']) ? $search_bar_data['search_scope'] : 'title';
$priority = isset($search_bar_data['priority']) ? $search_bar_data['priority'] : 'relevance';
$exclude_ids = isset($search_bar_data['exclude_ids']) ? $search_bar_data['exclude_ids'] : '';
$exclude_categories = isset($search_bar_data['exclude_categories']) ? $search_bar_data['exclude_categories'] : '';

add_action('wp_enqueue_scripts', function () use ($custom_css) {
    wp_add_inline_style('mnssp-hover-icon', $custom_css);
});
?>


<form id="hover-icon" role="search" method="get" class="search-form serach-page d-flex mnssp-search-bar"
    action="<?php echo esc_url(home_url('/')); ?>">
    <div class="search-box">
        <input type="search" class="search-field search-input" placeholder="<?php echo esc_html($placeholder_text); ?>"
            value="<?php echo esc_attr(the_search_query()); ?>" name="s"
            style="color: <?php echo esc_attr($placeholder_color); ?>;border-bottom-color: <?php echo esc_attr($border_color); ?>;"
            required>
        <input type="hidden" name="post_type" value="<?php echo esc_attr($post_types); ?>">

        <input type="hidden" name="search_scope" value="<?php echo esc_attr($search_scope); ?>">
        <input type="hidden" name="priority" value="<?php echo esc_attr($priority); ?>">
        <input type="hidden" name="exclude_ids" value="<?php echo esc_attr($exclude_ids); ?>">
        <input type="hidden" name="exclude_categories" value="<?php echo esc_attr($exclude_categories); ?>">
        <input type="hidden" name="source" value="<?php echo esc_attr('magnify-suggestive-search'); ?>">
        <input type="hidden" name="limit_per_page" value="<?php echo esc_attr($limit_per_page); ?>">


        <?php wp_nonce_field('mnssp_search_nonce'); ?>
        <button type="submit" name="button" class="search-btn mnssp-search-icon" style="color: <?php echo esc_attr($icon_color); ?>;">
            <i class="<?php echo esc_attr($icon_picker); ?>" aria-hidden="true"></i>
        </button>
    </div>
</form>