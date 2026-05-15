<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_shortcode( 'mnssp-bar', 'mnssp_render_search_bar_shortcode' );

function mnssp_render_search_bar_shortcode( $atts ) {

    global $mnssp_template_types;

    $atts = shortcode_atts(
        array(
            'bar-id' => 0,
        ),
        $atts,
        'mnssp-bar'
    );

    $bar_id = intval($atts['bar-id']);

    if ($bar_id) {
        $post = get_post($bar_id);
        if ($post && $post->post_type === 'magnify_search') {

            $template_type = get_post_meta($post->ID, 'template_type', true);
            $posttypes = get_post_meta($post->ID, 'posttypes', true);
            $icon_picker = get_post_meta($post->ID, 'icon_picker', true);
            $search_scope = get_post_meta($post->ID, 'search_scope', true);
            $priority = get_post_meta($post->ID, 'priority', true);
            $exclude_ids = get_post_meta($post->ID, 'exclude_ids', true);
            $exclude_categories = get_post_meta($post->ID, 'exclude_categories', true);
            $limit_per_page = get_post_meta($post->ID, 'limit_per_page', true);


            $post_types_string = implode(',', array_map('esc_attr', $posttypes));
            $options = get_option('mnssp_settings');

            // Style settings (with defaults)
            $border_width   = $options['border_width']   ?? '';
            $border_style   = $options['border_style']   ?? '';
            $border_radius  = $options['border_radius']  ?? '';
            $border_color   = $options['border_color']   ?? '';

            $bar_padding    = $options['bar_padding']    ?? '';
            $bar_margin     = $options['bar_margin']     ?? '';

            $icon_padding   = $options['icon_padding']   ?? '';
            $icon_margin    = $options['icon_margin']    ?? '';

            $submit_padding = $options['submit_padding'] ?? '';
            $submit_margin  = $options['submit_margin']  ?? '';


            $search_bar_data = array(
                'form_name' => $post->post_title,
                'template_type' => $template_type,
                'post_types' => $post_types_string,
                'icon_picker' => $icon_picker,
                'mnssp_settings' => $options,
                'search_scope' => $search_scope,
                'priority' => $priority,
                'exclude_ids'       => $exclude_ids,
                'exclude_categories'=> $exclude_categories,
                'limit_per_page'    => $limit_per_page,
                'search_bar_width'  => isset($options['search_bar_width']) ? $options['search_bar_width'] : 'auto',
                'custom_width'      => isset($options['custom_width']) ? $options['custom_width'] : '400px',
                'search_bar_height' => isset($options['search_bar_height']) ? $options['search_bar_height'] : 'medium',
                'custom_height'     => isset($options['custom_height']) ? $options['custom_height'] : '40px',
            );

            $template = in_array($template_type, ['hover-icon', 'click-icon', 'icon-overlay', 'autocomplete'])
            ? $template_type
            : 'default';

            $custom_css = "";

            $show_submit_button = isset($search_bar_data['mnssp_settings']['show_submit_button']) ? $search_bar_data['mnssp_settings']['show_submit_button'] : false;
            $submit_button_bg_color = isset($search_bar_data['mnssp_settings']['submit_button_bg_color']) ? $search_bar_data['mnssp_settings']['submit_button_bg_color'] : '#000000';
            $submit_button_bg_hover_color = isset($search_bar_data['mnssp_settings']['submit_button_bg_hover_color']) ? $search_bar_data['mnssp_settings']['submit_button_bg_hover_color'] : '#000000';
            $submit_button_text_hover_color = isset($search_bar_data['mnssp_settings']['submit_button_text_hover_color']) ? $search_bar_data['mnssp_settings']['submit_button_text_hover_color'] : '#ffffff';

            $custom_css .= "
                .mnssp-search-bar {
                    " . mnssp_css_prop('border-width',  $border_width) . "
                    " . mnssp_css_prop('border-style',  $border_style) . "
                    " . mnssp_css_prop('border-color',  $border_color) . "
                    " . mnssp_css_prop('border-radius', $border_radius) . "
                    " . mnssp_css_prop('padding',       $bar_padding) . "
                    " . mnssp_css_prop('margin',        $bar_margin) . "
                }

                .mnssp-search-bar .mnssp-search-icon {
                    " . mnssp_css_prop('padding', $icon_padding) . "
                    " . mnssp_css_prop('margin',  $icon_margin) . "
                }

                .mnssp-search-bar .search-button.mnssp-btn {
                    " . mnssp_css_prop('padding', $submit_padding) . "
                    " . mnssp_css_prop('margin',  $submit_margin) . "
                }
            ";



            if ( $template_type == 'autocomplete' ) {
                
                
                $custom_css .= "#mnssp-autocomplete-form .search-button.mnssp-btn:hover {
                    color: " . esc_attr($show_submit_button ? $submit_button_text_hover_color : $submit_button_bg_color) . " !important;
                    background-color: " . esc_attr($show_submit_button ? $submit_button_bg_hover_color : $submit_button_bg_color) . " !important;
                }";
            } elseif ( $template_type == 'default' ) {

                $custom_css .= "#default-serach .search-button.mnssp-btn:hover {
                    color: " . esc_attr($show_submit_button ? $submit_button_text_hover_color : $submit_button_bg_color) . " !important;
                    background-color: " . esc_attr($show_submit_button ? $submit_button_bg_hover_color : $submit_button_bg_color) . " !important;
                }";

            }

            add_action('wp_enqueue_scripts', function() use ($custom_css) {
                wp_add_inline_style('mnssp-inline-styles', $custom_css);
            });

            if (!isset($mnssp_template_types)) {
                $mnssp_template_types = array();
            }
            $mnssp_template_types[] = $template_type;

            ob_start();

            include MNSSP_PATH . "templates/{$template}.php";

            $output = ob_get_clean();
            return $output;

        }
    }
}



function mnssp_css_prop( $property, $value ) {
    if ( isset($value) && trim($value) !== '' ) {
        return "{$property}: {$value} !important;";
    }
    return '';
}