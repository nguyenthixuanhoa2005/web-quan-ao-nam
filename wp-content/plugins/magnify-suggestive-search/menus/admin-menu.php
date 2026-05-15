<?php
add_action('admin_menu', 'mnssp_register_admin_menu');
add_action('admin_menu', function () {
    remove_submenu_page('mnssp_dashboard', 'mnssp_dashboard');
});

function mnssp_register_admin_menu()
{
    add_menu_page(
        'Suggestive Search',
        'Suggestive Search',
        'manage_options',
        'mnssp_dashboard',
        'mnssp_dashboard_page',
        'dashicons-search',
        20
    );

    add_menu_page(
        'TM Templates',
        'TM Templates',
        'manage_options',
        'mnssp_templates',
        'mnssp_dashboard_page',
        'dashicons-images-alt',
        20
    );

    add_submenu_page(
        'mnssp_dashboard',
        'Add New',
        'Add New',
        'manage_options',
        'mnssp_create_search_bar',
        'mnssp_create_search_bar_page'
    );

    add_submenu_page(
        'mnssp_dashboard',
        'All Searches',
        'All Searches',
        'manage_options',
        'mnssp_display_search_bar',
        'mnssp_display_search_bar_page'
    );

    add_submenu_page(
        'mnssp_dashboard',
        'Edit Search',
        'Edit Search',
        'manage_options',
        'mnssp_edit_search_bar',
        'mnssp_edit_search_bar_page'
    );

    add_submenu_page(
        'mnssp_dashboard',
        'Settings',
        'Settings',
        'manage_options',
        'mnssp_settings_search_bar',
        'mnssp_settings_search_bar_page'
    );

    add_submenu_page(
        'mnssp_dashboard',
        'Guide',
        'Guide',
        'manage_options',
        'mnssp_guide_search_bar',
        'mnssp_guide_search_bar_page'
    );

    add_submenu_page(
        'mnssp_dashboard',
        'Templates',
        'Templates',
        'manage_options',
        'mnssp_templates',
        'mnssp_dashboard_page'
    );
}

function mnssp_dashboard_page()
{ ?>

    <div class="wrap mnssp-templates-wrap">
        <div class="mnssp-loader"></div>
        <div class="mnssp-loader-overlay"></div>
        <header>
            <div class="mnssp-main-content-row">
                <div class="mnssp-left-content">
                    <div class="mnssp-templates-logo">
                        <div>
                            <img src="<?php echo esc_url(MNSSP_URL . 'assets/images/logo.png'); ?>">
                        </div>
                    </div>

                    <div class="mnssp-templates-collections-search">
                        <input type="text" name="mnssp-templates-search" autocomplete="off"
                            placeholder="Search Templates...">
                        <span class="dashicons dashicons-search"></span>
                    </div>

                    <div class="mnssp-templates-collections-filter">
                        <?php $collections_arr = mnssp_get_collections(); ?>
                        <select name="mnssp-collections" id="mnssp-collections">
                            <?php foreach ($collections_arr as $collection) { ?>
                                <option value="<?php echo esc_attr($collection->handle); ?>">
                                    <?php echo esc_html($collection->title); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mnssp-offer-wrap">Use Code <span class="mnssp-offer-code"> MEGA30 </span> to get <span
                            class="mnssp-offer-code"> 30% OFF </span> On Every WordPress Template </div>
                </div>
                <div class="mnssp-right-content">
                    <div class="mnsp-feat">
                        <h1><?php echo esc_html('WordPress Theme Bundle - 120+ Templates'); ?></h1>
                        <ol>
                            <li><?php echo esc_html('Access to all themes, both Free and Premium (120+).'); ?></li>
                            <li><?php echo esc_html('Includes 1 year of free updates for all themes.'); ?></li>
                            <li><?php echo esc_html('One-time purchase with no hidden costs.'); ?></li>
                            <li><?php echo esc_html('Complimentary installation with expert guidance.'); ?></li>
                            <li><?php echo esc_html('Fully responsive designs for all devices.'); ?></li>
                            <li><?php echo esc_html('SEO-optimized themes to boost your SERP rankings.'); ?></li>
                            <li><?php echo esc_html('Professional, fast, and user-friendly customer support.'); ?></li>
                        </ol>

                        <div class="mnsp-btn">
                            <a class="mnsp-buy-btn" target="_blank"
                                href="<?php echo esc_url(MNSSP_MAIN_URL . 'products/wordpress-theme-bundle'); ?>"><?php echo esc_html('Buy Now'); ?></a>
                        </div>
                    </div>

                    <div class="mnsp-feat-img">
                        <img src="<?php echo esc_url(MNSSP_URL . 'assets/images/feat-img.png'); ?>">
                    </div>
                </div>
            </div>
        </header>
        <div class="mnssp-templates-grid mnssp-main-grid">
            <?php $get_filtered_products = mnssp_get_filtered_products();
            if (isset($get_filtered_products['products']) && !empty($get_filtered_products['products'])) {
                foreach ($get_filtered_products['products'] as $product) {

                    $product_obj = $product->node;

                    if (isset($product_obj->inCollection) && !$product_obj->inCollection) {
                        continue;
                    }

                    $demo_url = isset($product->node->metafield->value) ? $product->node->metafield->value : '';
                    $product_url = isset($product->node->onlineStoreUrl) ? $product->node->onlineStoreUrl : '';
                    $image_src = isset($product->node->images->edges[0]->node->src) ? $product->node->images->edges[0]->node->src : '';
                    $price = isset($product->node->variants->edges[0]->node->price) ? '$' . $product->node->variants->edges[0]->node->price : ''; ?>

                    <div class="mnssp-grid-item">
                        <div class="mnssp-image-wrap">
                            <img src="<?php echo esc_url($image_src); ?>" alt="<?php echo esc_attr($product_obj->title); ?>"
                                loading="lazy">
                            <div class="mnssp-image-overlay">
                                <a class="mnssp-demo-url mnssp-btn" href="<?php echo esc_attr($demo_url); ?>" target="_blank"
                                    rel="noopener noreferrer"><?php echo esc_html('Demo'); ?></a>
                                <a class="mnssp-buy-now mnssp-btn" href="<?php echo esc_attr($product_url); ?>" target="_blank"
                                    rel="noopener noreferrer"><?php echo esc_html('Buy Now'); ?></a>
                            </div>
                        </div>
                        <footer>
                            <h3><?php echo esc_html($product_obj->title); ?></h3>
                        </footer>
                        <div class="mnssp-grid-item-price">Price: <?php echo esc_html($price); ?></div>
                    </div>
                <?php }
            }
            ?>
        </div>
        <?php if (isset($get_filtered_products['pagination']->hasNextPage) && $get_filtered_products['pagination']->hasNextPage) { ?>
            <input type="hidden" name="mnssp-end-cursor"
                value="<?php echo esc_attr(isset($get_filtered_products['pagination']->endCursor) ? $get_filtered_products['pagination']->endCursor : '') ?>">
        <?php } ?>
    </div>
<?php }

function mnssp_guide_search_bar_page()
{

    $searches = [
        [
            'title' => 'Default Search',
            'description' => 'A standard search bar with a button or icon that allows users to enter a query and submit it to get results.',
            'image' => MNSSP_URL . 'assets/images/default.png'
        ],
        [
            'title' => 'Click-to-Open Search',
            'description' => 'This search type is hidden until the user clicks on an icon. Once clicked, the search bar appears, allowing users to enter their query.',
            'image' => MNSSP_URL . 'assets/images/click-icon.png'
        ],
        [
            'title' => 'Hover-to-Open Search',
            'description' => 'This search bar becomes visible when the user hovers over a specific icon. It’s useful for saving space while still providing quick access to search functionality.',
            'image' => MNSSP_URL . 'assets/images/hover-icon.png'
        ],
        [
            'title' => 'Overlay Search',
            'description' => 'An overlay search appears on top of the current page content, usually occupying the entire screen. This allows for an immersive search experience without distractions.',
            'image' => MNSSP_URL . 'assets/images/overlay.png'
        ],
        [
            'title' => 'Autocomplete Search',
            'description' => 'As users type their query, this search type suggests possible matches, helping users find what they are looking for more quickly and efficiently.',
            'image' => MNSSP_URL . 'assets/images/autocomplete.png'
        ],
    ];

    ?>

    <div class="wrap mnssp-wrap">
        <h1 class="mnssp-title">Suggestive Search</h1>
        <div class="mnssp-search-types">
            <?php foreach ($searches as $search): ?>
                <div class="mnssp-search-item">
                    <img class="mnssp-search-image" src="<?php echo esc_url($search['image']); ?>"
                        alt="<?php echo esc_attr($search['title']); ?>" />
                    <h2 class="mnssp-search-title"><?php echo esc_html($search['title']); ?></h2>
                    <p class="mnssp-search-description"><?php echo esc_html($search['description']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

function mnssp_create_search_bar_page()
{

    $post_types = get_post_types(array('public' => true), 'objects');

    $exclude_post_types = array('attachment', 'magnify_search');

    foreach ($exclude_post_types as $post_type) {
        if (isset($post_types[$post_type])) {
            unset($post_types[$post_type]);
        }
    }

    ?>
    <div class="wrap mnssp-add-new">
        <h1>Create a New Search Bar</h1>
        <form id="mnssp-form" method="post" action="">
            <?php wp_nonce_field('mnssp_create_search_bar_nonce_action', 'mnssp_search_bar_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="mnssp_form_name">Bar Name</label>
                    </th>
                    <td>
                        <input name="mnssp_form_name" type="text" id="mnssp_form_name" class="regular-text" required />
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="mnssp_template_type">Template Type</label>
                    </th>
                    <td>
                        <select name="mnssp_template_type" id="mnssp_template_type" required>
                            <option value="default">Default</option>
                            <option value="hover-icon">Hover Icon</option>
                            <option value="click-icon">Click Icon</option>
                            <option value="icon-overlay">Icon Overlay</option>
                            <option value="autocomplete">Autocomplete</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="mnssp_icon_picker">Select Icon</label>
                    </th>
                    <td>
                        <input name="mnssp_icon_picker" type="text" id="mnssp_icon_picker" class="regular-text" />
                    </td>
                </tr>


                <tr>
                    <th scope="row">
                        <label for="mnssp_posttypes">Post Types</label>
                    </th>
                    <td>
                        <select name="mnssp_posttypes[]" id="mnssp_posttypes" multiple="multiple" required class="select2">
                            <?php foreach ($post_types as $post_type): ?>
                                <option value="<?php echo esc_attr($post_type->name); ?>">
                                    <?php echo esc_html($post_type->label); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="description">Hold down the Ctrl (Windows) / Command (Mac) button to select multiple
                            options.</p>
                    </td>
                </tr>

                <!-- new  start -->
                <tr>
                    <th scope="row"><label for="mnssp_search_scope">Search In</label></th>
                    <td>
                        <select name="mnssp_search_scope" id="mnssp_search_scope">
                            <option value="title" <?php selected($search_scope ?? '', 'title'); ?>>Title</option>
                            <option value="excerpt" <?php selected($search_scope ?? '', 'excerpt'); ?>>Excerpt</option>
                            <option value="content" <?php selected($search_scope ?? '', 'content'); ?>>Content</option>
                            <option value="all" <?php selected($search_scope ?? '', 'all'); ?>>All</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th><label for="mnssp_priority">Sort Results By</label></th>
                    <td>
                        <select name="mnssp_priority" id="mnssp_priority">
                            <option value="relevance" <?php selected($priority ?? '', 'relevance'); ?>>Relevance (default)
                            </option>
                            <option value="date" <?php selected($priority ?? '', 'date'); ?>>Newest First</option>
                            <option value="views" <?php selected($priority ?? '', 'views'); ?>>Most Viewed</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th><label for="mnssp_exclude_ids">Exclude Post IDs</label></th>
                    <td><input type="text" name="mnssp_exclude_ids" id="mnssp_exclude_ids" class="regular-text"
                            placeholder="e.g. 5,10,20" value="<?php echo esc_attr($exclude_ids ?? ''); ?>"></td>
                </tr>

                <tr>
                    <th><label for="mnssp_exclude_categories">Exclude Categories (IDs)</label></th>
                    <td><input type="text" name="mnssp_exclude_categories" id="mnssp_exclude_categories"
                            class="regular-text" placeholder="e.g. 3,7"
                            value="<?php echo esc_attr($exclude_categories ?? ''); ?>"></td>
                </tr>

                <tr>
                    <th><label for="mnssp_limit_per_page">Limit Per Page</label></th>
                    <td><input type="text" name="mnssp_limit_per_page" id="mnssp_limit_per_page"
                            class="regular-text" placeholder="e.g. 5"
                            value="<?php echo esc_attr($limit_per_page ?? ''); ?>"></td>
                </tr>

                <!-- end -->


            </table>
            <?php submit_button('Save'); ?>
        </form>
    </div>
    <?php
}

function mnssp_display_search_bar_page()
{

    $paged = 0;
    if (isset($_GET['paged']) && isset($_GET['nonce'])) {
        $nonce = sanitize_text_field(wp_unslash($_GET['nonce']));
        if (wp_verify_nonce($nonce, 'mnssp_pagination_nonce')) {
            $paged = intval($_GET['paged']);
        }
    }

    $paged = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
    $posts_per_page = 10;

    $args = array(
        'post_type' => 'magnify_search',
        'post_status' => 'publish',
        'posts_per_page' => $posts_per_page,
        'paged' => $paged,
    );
    $query = new WP_Query($args);

    ?>
    <div class="wrap">
        <h1><?php esc_html_e('All Searches', 'magnify-suggestive-search'); ?></h1>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php esc_html_e('Bar Name', 'magnify-suggestive-search'); ?></th>
                    <th><?php esc_html_e('Template Type', 'magnify-suggestive-search'); ?></th>
                    <th><?php esc_html_e('Post Types', 'magnify-suggestive-search'); ?></th>
                    <th><?php esc_html_e('Icon Picker', 'magnify-suggestive-search'); ?></th>
                    <th><?php esc_html_e('Shortcode', 'magnify-suggestive-search'); ?></th>
                    <th><?php esc_html_e('Actions', 'magnify-suggestive-search'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($query->have_posts()): ?>
                    <?php while ($query->have_posts()):
                        $query->the_post(); ?>
                        <?php
                        $template_type = get_post_meta(get_the_ID(), 'template_type', true);
                        $posttypes = get_post_meta(get_the_ID(), 'posttypes', true);
                        $icon_picker = get_post_meta(get_the_ID(), 'icon_picker', true);
                        $shortcode = sprintf('[mnssp-bar bar-id="%d"]', esc_attr(get_the_ID()));

                        $icon_class = '';
                        if ($icon_picker) {
                            $icon_class = sprintf('<i class="%s"></i>', esc_attr($icon_picker));
                        }
                        $allowed_html = array(
                            'i' => array(
                                'class' => array()
                            )
                        );
                        $edit_nonce = wp_create_nonce('edit_search_bar_' . get_the_ID());
                        ?>
                        <tr>
                            <td><?php the_title(); ?></td>
                            <td><?php echo esc_html($template_type); ?></td>
                            <td><?php echo esc_html(implode(', ', $posttypes)); ?></td>
                            <td><?php echo isset($icon_class) ? wp_kses($icon_class, $allowed_html) : ''; ?></td>
                            <td class="shortcode-column"><?php echo esc_html($shortcode); ?></td>
                            <td>
                                <a
                                    href="<?php echo esc_url(admin_url('admin.php?page=mnssp_edit_search_bar&post_id=' . get_the_ID() . '&nonce=' . $edit_nonce)); ?>"><?php esc_html_e('Edit', 'magnify-suggestive-search'); ?></a>
                                |
                                <a
                                    href="<?php echo esc_url(get_delete_post_link(get_the_ID())); ?>"><?php esc_html_e('Delete', 'magnify-suggestive-search'); ?></a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style=""><?php esc_html_e('No searches found.', 'magnify-suggestive-search'); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="tablenav bottom">
            <div class="tablenav-pages">
                <?php
                $pagination_nonce = wp_create_nonce('mnssp_pagination_nonce');

                if ($query->max_num_pages) {

                    $pagination_links = paginate_links(array(
                        'total' => isset($query->max_num_pages) ? intval($query->max_num_pages) : 1,
                        'current' => isset($paged) ? intval($paged) : 1,
                        'prev_text' => esc_html__('&laquo; Previous', 'magnify-suggestive-search'),
                        'next_text' => esc_html__('Next &raquo;', 'magnify-suggestive-search'),
                        'add_args' => array(
                            'nonce' => $pagination_nonce
                        )
                    ));

                    if (!is_null($pagination_links)) {
                        echo wp_kses_post($pagination_links);
                    }
                }

                ?>
            </div>
        </div>
    </div>
    <?php
    wp_reset_postdata();
}

function mnssp_edit_search_bar_page()
{

    $post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;

    if (isset($_GET['nonce'])) {
        $nonce = sanitize_text_field(wp_unslash($_GET['nonce']));

        if (!wp_verify_nonce($nonce, 'edit_search_bar_' . $post_id)) {
            echo '<div class="error"><p>Nonce error.</p></div>';
            return;
        }
    }



    if (!$post_id) {
        echo '<div class="error"><p>No post ID specified.</p></div>';
        return;
    }

    $search_bar = mnssp_get_search_bar_data($post_id);

    if (!$search_bar) {
        echo '<div class="error"><p>No data found for the specified ID.</p></div>';
        return;
    }

    $post_types = get_post_types(array('public' => true), 'objects');
    $exclude_post_types = array('attachment', 'magnify_search');
    foreach ($exclude_post_types as $post_type) {
        if (isset($post_types[$post_type])) {
            unset($post_types[$post_type]);
        }
    }

    $template_type = isset($search_bar['template_type']) ? $search_bar['template_type'] : '';
    $icon_picker = isset($search_bar['icon_picker']) ? $search_bar['icon_picker'] : '';
    $post_types_arr = isset($search_bar['post_types']) ? $search_bar['post_types'] : array();
    $search_scope = isset($search_bar['search_scope']) ? $search_bar['search_scope'] : array();
    $priority = isset($search_bar['priority']) ? $search_bar['priority'] : array();
    $priority = isset($search_bar['priority']) ? $search_bar['priority'] : array();
    $exclude_ids = isset($search_bar['exclude_ids']) ? $search_bar['exclude_ids'] : '';
    $exclude_categories = isset($search_bar['exclude_categories']) ? $search_bar['exclude_categories'] : '';
    $limit_per_page = isset($search_bar['limit_per_page']) ? $search_bar['limit_per_page'] : '';

    ?>
    <div class="wrap mnssp-add-new">
        <h1>Edit Search Bar</h1>
        <form id="mnssp-form" method="post" action="">
            <input type="hidden" name="post_id" value="<?php echo esc_attr($post_id); ?>" />
            <?php wp_nonce_field('mnssp_create_search_bar_nonce_action', 'mnssp_search_bar_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="mnssp_form_name">Bar Name</label>
                    </th>
                    <td>
                        <input name="mnssp_form_name" type="text" id="mnssp_form_name" class="regular-text"
                            value="<?php echo esc_attr(isset($search_bar['form_name']) ? $search_bar['form_name'] : ''); ?>"
                            required />
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="mnssp_template_type">Template Type</label>
                    </th>
                    <td>
                        <select name="mnssp_template_type" id="mnssp_template_type" required>
                            <option value="default" <?php selected($template_type, 'default'); ?>>Default</option>
                            <option value="hover-icon" <?php selected($template_type, 'hover-icon'); ?>>Hover Icon</option>
                            <option value="click-icon" <?php selected($template_type, 'click-icon'); ?>>Click Icon</option>
                            <option value="icon-overlay" <?php selected($template_type, 'icon-overlay'); ?>>Icon Overlay
                            </option>
                            <option value="autocomplete" <?php selected($template_type, 'autocomplete'); ?>>Autocomplete
                            </option>
                        </select>
                    </td>
                </tr>


                <tr>
                    <th scope="row">
                        <label for="mnssp_icon_picker">Select Icon</label>
                    </th>
                    <td>
                        <input name="mnssp_icon_picker" type="text" id="mnssp_icon_picker" class="regular-text"
                            value="<?php echo esc_attr($icon_picker); ?>" />
                    </td>
                </tr>


                <tr>
                    <th scope="row">
                        <label for="mnssp_posttypes">Post Types</label>
                    </th>
                    <td>
                        <select name="mnssp_posttypes[]" id="mnssp_posttypes" multiple="multiple" required class="select2">
                            <?php foreach ($post_types as $post_type): ?>
                                <option value="<?php echo esc_attr($post_type->name); ?>" <?php echo in_array($post_type->name, $post_types_arr) ? 'selected' : ''; ?>><?php echo esc_html($post_type->label); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="description">Hold down the Ctrl (Windows) / Command (Mac) button to select multiple
                            options.</p>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="mnssp_search_scope">Search In</label></th>
                    <td>
                        <select name="mnssp_search_scope" id="mnssp_search_scope">
                            <option value="title" <?php selected($search_scope ?? '', 'title'); ?>>Title</option>
                            <option value="excerpt" <?php selected($search_scope ?? '', 'excerpt'); ?>>Excerpt</option>
                            <option value="content" <?php selected($search_scope ?? '', 'content'); ?>>Content</option>
                            <option value="all" <?php selected($search_scope ?? '', 'all'); ?>>All</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th><label for="mnssp_priority">Sort Results By</label></th>
                    <td>
                        <select name="mnssp_priority" id="mnssp_priority">
                            <option value="relevance" <?php selected($priority ?? '', 'relevance'); ?>>Relevance (default)
                            </option>
                            <option value="date" <?php selected($priority ?? '', 'date'); ?>>Newest First</option>
                            <option value="views" <?php selected($priority ?? '', 'views'); ?>>Most Viewed</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th><label for="mnssp_exclude_ids">Exclude Post IDs</label></th>
                    <td><input type="text" name="mnssp_exclude_ids" id="mnssp_exclude_ids" class="regular-text"
                            placeholder="e.g. 5,10,20" value="<?php echo esc_attr($exclude_ids ?? ''); ?>"></td>
                </tr>
                <tr>
                    <th><label for="mnssp_exclude_categories">Exclude Categories (IDs)</label></th>
                    <td><input type="text" name="mnssp_exclude_categories" id="mnssp_exclude_categories"
                            class="regular-text" placeholder="e.g. 3,7"
                            value="<?php echo esc_attr($exclude_categories ?? ''); ?>"></td>
                </tr>

                <tr>
                    <th><label for="mnssp_limit_per_page">Limit Per Page</label></th>
                    <td><input type="text" name="mnssp_limit_per_page" id="mnssp_limit_per_page"
                            class="regular-text" placeholder="e.g. 5"
                            value="<?php echo esc_attr($limit_per_page ?? ''); ?>"></td>
                </tr>




            </table>
            <?php submit_button('Update'); ?>
        </form>
    </div>
    <?php
}

function mnssp_settings_search_bar_page()
{

    $options = get_option('mnssp_settings');
    ?>
    <div class="wrap mnssp-settings-main">
        <h1><?php esc_html_e('Magnify - Suggestive Search Settings', 'magnify-suggestive-search'); ?></h1>
        <h2 class="nav-tab-wrapper">
            <a href="#general"  class="nav-tab nav-tab-active"><?php esc_html_e('General', 'magnify-suggestive-search'); ?></a>
            <a href="#appearance" class="nav-tab"><?php esc_html_e('Style', 'magnify-suggestive-search'); ?></a>
        </h2>
        <form method="post" action="options.php">
            <?php
                settings_fields('mnssp_settings_group');
            ?>
            <div id="general" class="tab-content active">
                <h3><?php esc_html_e('General Settings', 'magnify-suggestive-search'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label
                                for="mnssp_submit_button_label"><?php esc_html_e('Submit Button Label', 'magnify-suggestive-search'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="mnssp_submit_button_label" name="mnssp_settings[submit_button_label]"
                                value="<?php echo esc_attr($options['submit_button_label'] ?? ''); ?>"
                                class="regular-text" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label
                                for="mnssp_placeholder_text"><?php esc_html_e('Placeholder Text', 'magnify-suggestive-search'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="mnssp_placeholder_text" name="mnssp_settings[placeholder_text]"
                                value="<?php echo esc_attr($options['placeholder_text'] ?? ''); ?>" class="regular-text" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="mnssp_limit"><?php esc_html_e('Limit', 'magnify-suggestive-search'); ?></label>
                        </th>
                        <td>
                            <input type="number" id="mnssp_limit" min="1" max="100" name="mnssp_settings[limit]"
                                value="<?php echo esc_attr($options['limit'] ?? ''); ?>" class="small-text" />
                            <p class="description">Show Search Result</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label
                                for="mnssp_minimum_character"><?php esc_html_e('Minimum Character', 'magnify-suggestive-search'); ?></label>
                        </th>
                        <td>
                            <input type="number" id="mnssp_minimum_character" min="0" max="10"
                                name="mnssp_settings[minimum_character]"
                                value="<?php echo esc_attr($options['minimum_character'] ?? 2); ?>" class="small-text" />
                            <p class="description">Min characters to show autocomplete, Search start showing the results
                                after the minimum character value you set here</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label
                                for="mnssp_no_result_label"><?php esc_html_e('No Result Label', 'magnify-suggestive-search'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="mnssp_no_result_label" name="mnssp_settings[no_result_label]"
                                value="<?php echo esc_attr($options['no_result_label'] ?? ''); ?>" class="regular-text" />
                            <p class="description">This text will display at the search result dropdown.</p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label
                                for="mnssp_show_submit_button"><?php esc_html_e('Show Submit Button', 'magnify-suggestive-search'); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" id="mnssp_show_submit_button" name="mnssp_settings[show_submit_button]"
                                value="1" <?php checked(1, $options['show_submit_button'] ?? 0); ?> />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label
                                for="mnssp_search_bar_width"><?php esc_html_e('Search Bar Width', 'magnify-suggestive-search'); ?></label>
                        </th>
                        <td>
                            <select id="mnssp_search_bar_width" name="mnssp_settings[search_bar_width]"
                                class="regular-text">
                                <option value="auto" <?php selected($options['search_bar_width'] ?? 'auto', 'auto'); ?>>
                                    <?php esc_html_e('Auto (Default)', 'magnify-suggestive-search'); ?></option>
                                <option value="full" <?php selected($options['search_bar_width'] ?? 'auto', 'full'); ?>>
                                    <?php esc_html_e('Full Width', 'magnify-suggestive-search'); ?></option>
                                <option value="custom" <?php selected($options['search_bar_width'] ?? 'auto', 'custom'); ?>>
                                    <?php esc_html_e('Custom Width', 'magnify-suggestive-search'); ?></option>
                            </select>
                            <p class="description">
                                <?php esc_html_e('Choose the width style for your search bars.', 'magnify-suggestive-search'); ?>
                            </p>
                        </td>
                    </tr>

                    <tr id="mnssp_custom_width_row"
                        style="<?php echo ($options['search_bar_width'] ?? 'auto') === 'custom' ? '' : 'display: none;'; ?>">
                        <th scope="row">
                            <label
                                for="mnssp_custom_width"><?php esc_html_e('Custom Width', 'magnify-suggestive-search'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="mnssp_custom_width" name="mnssp_settings[custom_width]"
                                value="<?php echo esc_attr($options['custom_width'] ?? '400px'); ?>" class="small-text"
                                placeholder="400px" pattern="^\d+(\.\d+)?(px|%|rem)$"
                                title="Enter a valid width like 400px, 50%, or 20rem" />
                            <p class="description">
                                <?php esc_html_e('Enter custom width (e.g., 400px, 50%, 20rem)', 'magnify-suggestive-search'); ?>
                            </p>

                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label
                                for="mnssp_search_bar_height"><?php esc_html_e('Search Bar Height', 'magnify-suggestive-search'); ?></label>
                        </th>
                        <td>
                            <select id="mnssp_search_bar_height" name="mnssp_settings[search_bar_height]"
                                class="regular-text">
                                <option value="medium" <?php selected($options['search_bar_height'] ?? 'medium', 'medium'); ?>>
                                    <?php esc_html_e('Medium (Default)', 'magnify-suggestive-search'); ?></option>
                                <option value="small" <?php selected($options['search_bar_height'] ?? 'medium', 'small'); ?>>
                                    <?php esc_html_e('Small', 'magnify-suggestive-search'); ?></option>
                                <option value="large" <?php selected($options['search_bar_height'] ?? 'medium', 'large'); ?>>
                                    <?php esc_html_e('Large', 'magnify-suggestive-search'); ?></option>
                                <option value="custom" <?php selected($options['search_bar_height'] ?? 'medium', 'custom'); ?>>
                                    <?php esc_html_e('Custom Height', 'magnify-suggestive-search'); ?></option>
                            </select>
                            <p class="description">
                                <?php esc_html_e('Choose the height style for your search bars.', 'magnify-suggestive-search'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr id="mnssp_custom_height_row"
                        style="<?php echo ($options['search_bar_height'] ?? 'medium') === 'custom' ? '' : 'display: none;'; ?>">
                        <th scope="row">
                            <label
                                for="mnssp_custom_height"><?php esc_html_e('Custom Height', 'magnify-suggestive-search'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="mnssp_custom_height" name="mnssp_settings[custom_height]"
                                value="<?php echo esc_attr($options['custom_height'] ?? '40px'); ?>" class="small-text"
                                placeholder="40px" pattern="^\d+(\.\d+)?(px|rem)$"
                                title="Enter a valid height like 40px or 2.5rem" />
                            <p class="description">
                                <?php esc_html_e('Enter custom height (e.g., 40px, 2.5rem)', 'magnify-suggestive-search'); ?>
                            </p>

                        </td>
                    </tr>
                </table>
            </div>

            <div id="appearance" class="tab-content">
                <h3><?php esc_html_e('Appearance Settings', 'magnify-suggestive-search'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label
                                for="mnssp_border_color"><?php esc_html_e('Border Color', 'magnify-suggestive-search'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="mnssp_border_color" name="mnssp_settings[border_color]"
                                value="<?php echo esc_attr($options['border_color'] ?? ''); ?>"
                                class="regular-text color-field" />
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="mnssp_border_width"><?php esc_html_e('Border Width', 'magnify-suggestive-search'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="mnssp_border_width"
                                name="mnssp_settings[border_width]"
                                value="<?php echo esc_attr($options['border_width'] ?? ''); ?>"
                                class="small-text" placeholder="1px" />
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="mnssp_border_style"><?php esc_html_e('Border Style', 'magnify-suggestive-search'); ?></label>
                        </th>
                        <td>
                            <select id="mnssp_border_style" name="mnssp_settings[border_style]">
                                <?php
                                $styles = ['solid', 'dashed', 'dotted', 'double', 'none'];
                                foreach ($styles as $style) {
                                    printf(
                                        '<option value="%1$s" %2$s>%1$s</option>',
                                        esc_attr($style),
                                        selected($options['border_style'] ?? 'solid', $style, false)
                                    );
                                }
                                ?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="mnssp_border_radius"><?php esc_html_e('Border Radius', 'magnify-suggestive-search'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="mnssp_border_radius"
                                name="mnssp_settings[border_radius]"
                                value="<?php echo esc_attr($options['border_radius'] ?? ''); ?>"
                                class="small-text" placeholder="6px" />
                        </td>
                    </tr>


                    <tr>
                        <th scope="row">
                            <label><?php esc_html_e('Search Bar Padding', 'magnify-suggestive-search'); ?></label>
                        </th>
                        <td>
                            <input type="text" name="mnssp_settings[bar_padding]"
                                value="<?php echo esc_attr($options['bar_padding'] ?? ''); ?>"
                                class="regular-text" placeholder="8px 12px" />
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label><?php esc_html_e('Search Bar Margin', 'magnify-suggestive-search'); ?></label>
                        </th>
                        <td>
                            <input type="text" name="mnssp_settings[bar_margin]"
                                value="<?php echo esc_attr($options['bar_margin'] ?? ''); ?>"
                                class="regular-text" placeholder="0" />
                        </td>
                    </tr>


                    <tr>
                        <th scope="row">
                            <label
                                for="mnssp_placeholder_color"><?php esc_html_e('Placeholder Color', 'magnify-suggestive-search'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="mnssp_placeholder_color" name="mnssp_settings[placeholder_color]"
                                value="<?php echo esc_attr($options['placeholder_color'] ?? ''); ?>"
                                class="regular-text color-field" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label
                                for="mnssp_icon_color"><?php esc_html_e('Icon Color', 'magnify-suggestive-search'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="mnssp_icon_color" name="mnssp_settings[icon_color]"
                                value="<?php echo esc_attr($options['icon_color'] ?? ''); ?>"
                                class="regular-text color-field" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label
                                for="mnssp_icon_bg_color"><?php esc_html_e('Icon BG Color', 'magnify-suggestive-search'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="mnssp_icon_bg_color" name="mnssp_settings[icon_bg_color]"
                                value="<?php echo esc_attr($options['icon_bg_color'] ?? ''); ?>"
                                class="regular-text color-field" />
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label><?php esc_html_e('Icon Padding', 'magnify-suggestive-search'); ?></label>
                        </th>
                        <td>
                            <input type="text" name="mnssp_settings[icon_padding]"
                                value="<?php echo esc_attr($options['icon_padding'] ?? ''); ?>"
                                class="regular-text" placeholder="8px" />
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label><?php esc_html_e('Icon Margin', 'magnify-suggestive-search'); ?></label>
                        </th>
                        <td>
                            <input type="text" name="mnssp_settings[icon_margin]"
                                value="<?php echo esc_attr($options['icon_margin'] ?? ''); ?>"
                                class="regular-text" placeholder="0" />
                        </td>
                    </tr>


                    <tr>
                        <th scope="row">
                            <label
                                for="mnssp_submit_button_bg_color"><?php esc_html_e('Submit Button BG Color', 'magnify-suggestive-search'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="mnssp_submit_button_bg_color"
                                name="mnssp_settings[submit_button_bg_color]"
                                value="<?php echo esc_attr($options['submit_button_bg_color'] ?? ''); ?>"
                                class="regular-text color-field" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label
                                for="mnssp_submit_button_text_color"><?php esc_html_e('Submit Button Text Color', 'magnify-suggestive-search'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="mnssp_submit_button_text_color"
                                name="mnssp_settings[submit_button_text_color]"
                                value="<?php echo esc_attr($options['submit_button_text_color'] ?? ''); ?>"
                                class="regular-text color-field" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label
                                for="mnssp_submit_button_bg_hover_color"><?php esc_html_e('Submit Button BG Hover Color', 'magnify-suggestive-search'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="mnssp_submit_button_bg_hover_color"
                                name="mnssp_settings[submit_button_bg_hover_color]"
                                value="<?php echo esc_attr($options['submit_button_bg_hover_color'] ?? ''); ?>"
                                class="regular-text color-field" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label
                                for="mnssp_submit_button_text_hover_color"><?php esc_html_e('Submit Button Text Hover Color', 'magnify-suggestive-search'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="mnssp_submit_button_text_hover_color"
                                name="mnssp_settings[submit_button_text_hover_color]"
                                value="<?php echo esc_attr($options['submit_button_text_hover_color'] ?? ''); ?>"
                                class="regular-text color-field" />
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label><?php esc_html_e('Submit Button Padding', 'magnify-suggestive-search'); ?></label>
                        </th>
                        <td>
                            <input type="text" name="mnssp_settings[submit_padding]"
                                value="<?php echo esc_attr($options['submit_padding'] ?? ''); ?>"
                                class="regular-text" placeholder="8px 16px" />
                            
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label><?php esc_html_e('Submit Button Margin', 'magnify-suggestive-search'); ?></label>
                        </th>
                        <td>
                            <input type="text" name="mnssp_settings[submit_margin]"
                                value="<?php echo esc_attr($options['submit_margin'] ?? ''); ?>"
                                class="regular-text" placeholder="0" />
                        </td>
                    </tr>

                </table>
                <p class="description">
                    <?php esc_html_e(
                        'Use CSS padding and margin format. Examples: 10px (all sides), 10px 15px (top/bottom left/right), 10px 15px 8px 15px (top right bottom left).',
                        'magnify-suggestive-search'
                    ); ?>
                </p>
            </div>

            

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
