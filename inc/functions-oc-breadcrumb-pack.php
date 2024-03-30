<?php
if (! function_exists('oc_breadcrumbs')) {
    // We encapsulate the logic of how we present a post's categories in the breadcrumbs:
    //   o If there is a Parent article, use the categories of the Parent
    //   o If Yoast is active, it gives us the concept of "primary category" - return it
    //   o If there are more than two categories, return two as:
    //     - If we have primary, return that at the first category of the list
    //     - If we do not have primary, return the first two categories of the list
    //
    function bc_get_post_categories($post_id, $limit = 2)
    {
        $result = array();
        $post_parent = get_field('post_parent', $post_id);
        if (! empty($post_parent)) {
            $post_id = $post_parent;
        }
        $categories = get_the_terms($post_id, 'category');

        if (is_array($categories) && count($categories) > 0) {
            $parent_cat = null;

            $child_cats = array();
            foreach ($categories as $category) {
                if (! empty($category->parent)) {
                    $child_cats[$category->term_id] = $category->parent;
                }
            }

            $primary_cat = null;
            if (class_exists('WPSEO_Primary_Term')) {
                // Show Primary category by Yoast if it is enabled & set
                $wpseo_primary_term = new WPSEO_Primary_Term('category', $post_id);
                $cat = get_category($wpseo_primary_term->get_primary_term());
                if (! is_wp_error($cat) && ! is_null($cat)) {
                    $primary_cat = $cat;
                }
            }

            if (is_null($primary_cat)) {
                $primary_cat = $categories[0];
            }

            $child_cat = null;
            if (count($child_cats) > 0) {
                // Check to see if the primary has a child...
                $child_id = array_search($primary_cat->term_id, $child_cats);
                if ($child_id !== false) {
                    $child_cat = get_category($child_id);
                    if (is_wp_error($child_cat)) {
                        $child_cat = null;
                    }
                }

                // If not, check to see if primary _is_ the child
                if (is_null($child_cat)) {
                    if (array_key_exists($primary_cat->term_id, $child_cats)) {
                        $parent_cat = get_category($child_cats[$primary_cat->term_id]);
                        if (! is_wp_error($parent_cat) && ! is_null($parent_cat)) {
                            $child_cat = $primary_cat;
                            $primary_cat = $parent_cat;
                        }
                    }
                }
            }

            if (! is_null($primary_cat)) {
                $result[] = $primary_cat;
            }

            if (! is_null($child_cat)) {
                $result[] = $child_cat;
            }
        }

        return $result;
    }

    function oc_breadcrumbs($container_class = '')
    {
        global $wp, $post, $wp_post_types;
        $template_uri = get_template_directory_uri();

        $post_id     = $post->ID;
        $blog_id     = get_option('page_for_posts');
        $home_url    = home_url();
        $blog_url    = get_permalink( $blog_id );
        $blog_title  = get_the_title( $blog_id );

        $result = '';
        $result .= '<ul class="bread-crumbs ' . $container_class . '"';
        $result .= ' itemscope itemtype ="https://schema.org/BreadcrumbList">';
        $result .=   '<meta itemprop="name" content="Page Breadcrumbs">';

        // Home link is always present...
        $result .=   '<li class="bc-item bc-home" ';
        $result .=      'itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        $result .=     '<a class="bc-link" href="' . $home_url . '" itemprop="item">';
        $result .=       '<img class="bc-home-icon" src="' . $template_uri . '/img/bc-home-icon.svg">';
        $result .=       '<span itemprop="name">HOME</span>';
        $result .=     '</a>';
        $result .=     '<meta itemprop="position" content="1">';
        $result .=   '</li>';

        $parents = null;
        $categories = null;
        $post_parent = null;
        $endpoint_link = null;
        $endpoint_title = null;

        $position = 2;
        if (is_single()) {
            // This is a single blog post
            if ($post->post_type == 'post') {
                // Include the blog index page link
                $result .= '<li class="bc-item bc-parent" ';
                $result .=    'itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                $result .=   '<a class="bc-link" href="' . $blog_url . '" itemprop="item">';
                $result .=     '<span itemprop="name">' . $blog_title . '</span>';
                $result .=   '</a>';
                $result .=   '<meta itemprop="position" content="' . $position . '">';
                $result .= '</li>';
                $position++;

                // Output the categories for the article
                $categories = bc_get_post_categories($post_id, 2);
                foreach ($categories as $cat) {
                    $result .= '<li class="bc-item bc-category" ';
                    $result .=    'itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                    $result .=   '<a class="bc-link" href="' . get_term_link($cat) . '" itemprop="item">';
                    $result .=     '<span itemprop="name">' . $cat->name . '</span>';
                    $result .=   '</a>';
                    $result .=   '<meta itemprop="position" content="' . $position . '">';
                    $result .= '</li>';
                    $position++;
                }

                // If the article has a parent article (per post meta field), add parent
                $post_parent = get_field('post_parent', $post_id);
                if (! empty($post_parent)) {
                    $result .= '<li class="bc-item bc-parent" ';
                    $result .=    'itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                    $result .=   '<a class="bc-link" href="' . get_permalink($post_parent) . '" itemprop="item">';
                    $result .=     '<span itemprop="name">' . get_the_title($post_parent) . '</span>';
                    $result .=   '</a>';
                    $result .=   '<meta itemprop="position" content="' . $position . '">';
                    $result .= '</li>';
                    $position++;
                }
            } else {
                // NOTE
                // We must leverage the WordPress template map in order for this bread crumb
                // link here to work properly:
                //   1) Mark the custom post as "Has Archive"
                //   2) Set the Archive Slug to the slug you want for the archive page.
                //   3) Set the Archives Label to the label you want for _this_ link.
                //   3) Create the "archive-cpt_xxxx.php" page to display the archive.
                //   4) Use a "Custom Link" in Appearance -> Menus menu items pointing
                //      to the archive page using the Archive Slug. WP Menus do not have
                //      any other way to point to a custom post archive page.
                //   5) Do _not_ use a custom template to display the archive.
                //
                // Our old approach of using a "p_ArchiveSlug.php" does not work, as WP has no way
                // to know which of our templates is being used to display the custom post archive.
                // Using the archive mechanism solves this problem. Furthermore, if we do not use
                // the archive mechanism, the call to get_post_type_archive_link() returns an empty
                // string.
                //
                // error_log( "CUSTOM POST: " . var_export($wp_post_types[$post->post_type], true) );
                if (isset($wp_post_types[$post->post_type]) && ! empty($wp_post_types[$post->post_type])) {
                    $cpt = $wp_post_types[$post->post_type];
                    $cpt_name = $cpt->labels->archives;
                    $cpt_url = get_post_type_archive_link($post->post_type);

                    $result .= '<li class="bc-item bc-parent" ';
                    $result .=    'itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                    $result .=   '<a class="bc-link" href="' . $cpt_url . '" itemprop="item">';
                    $result .=     '<span itemprop="name">' . $cpt_name . '</span>';
                    $result .=   '</a>';
                    $result .=   '<meta itemprop="position" content="' . $position . '">';
                    $result .= '</li>';
                    $position++;
                }
            }

            // Finally output the current page
            $endpoint_link = get_permalink($post_id);
            $endpoint_title = get_the_title($post_id);
            $result .= '<li class="bc-item bc-title" ';
            $result .=    'itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
            $result .=   '<span itemprop="name">' . $endpoint_title . '</span>';
            $result .=   '<link itemprop="url" href="' . $endpoint_link . '" />';
            $result .=   '<meta itemprop="position" content="' . $position . '">';
            $result .= '</li>';
        } elseif (is_category()) {
            // This is a posts category page
            // We currently only support categories on normal posts.
            // This code does not support custom post taxonomies.

            // Include the blog index page link
            $result .= '<li class="bc-item bc-parent" ';
            $result .=    'itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
            $result .=   '<a class="bc-link" href="' . $blog_url . '" itemprop="item">';
            $result .=     '<span itemprop="name">' . $blog_title . '</span>';
            $result .=   '</a>';
            $result .=   '<meta itemprop="position" content="' . $position . '">';
            $result .= '</li>';
            $position++;

            // Spool up the parent chain, so we can reverse it for displaying in proper order
            $parents = array();
            $the_cat = get_category(get_query_var('cat'));
            for ($cat = $the_cat; ! is_null($cat) && ! empty($cat->parent);) {
                $parent = get_category($cat->parent);
                if (is_wp_error($parent) || is_null($parent)) {
                    break;
                } else {
                    $parents[] = $parent;
                    $cat = $parent;
                }
            }

            // If there were parents, output them.
            if (count($parents) > 0) {
                foreach (array_reverse($parents) as $cat) {
                    $result .= '<li class="bc-item bc-category" ';
                    $result .=    'itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                    $result .=   '<a class="bc-link" href="' . get_term_link($cat) . '" itemprop="item">';
                    $result .=     '<span itemprop="name">' . $cat->name . '</span>';
                    $result .=   '</a>';
                    $result .=   '<meta itemprop="position" content="' . $position . '">';
                    $result .= '</li>';
                    $position++;
                }
            }

            // Finally, output the category being displayed by the page
            $endpoint_link = get_term_link($the_cat);
            $endpoint_title = single_cat_title('', false);
            $result .= '<li class="bc-item bc-category" ';
            $result .=    'itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
            $result .=   '<span itemprop="name">' . $endpoint_title . '</span>';
            $result .=   '<link itemprop="url" href="' . $endpoint_link . '" />';
            $result .=   '<meta itemprop="position" content="' . $position . '">';
            $result .= '</li>';
        } elseif (is_home()) {
            // This is the blogs home page
            $endpoint_link = $blog_url;
            $endpoint_title = $blog_title;
            $result .= '<li class="bc-item bc-title" ';
            $result .=   'itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
            $result .=   '<span itemprop="name">' . $endpoint_title . '</span>';
            $result .=   '<link itemprop="url" href="' . $endpoint_link . '" />';
            $result .=   '<meta itemprop="position" content="' . $position . '">';
            $result .= '</li>';
        } elseif (is_archive()) {
            $tax_name = get_queried_object()->name;
            $tax_slug = get_queried_object()->slug;
            $taxonomy = get_queried_object()->taxonomy;
            $endpoint_title = $tax_name;
            $endpoint_link = get_term_link( $tax_slug, $taxonomy );
            $result .= '<li class="bc-item bc-title" ';
            $result .=    'itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
            $result .=   '<span itemprop="name">' . $endpoint_title . '</span>';
            $result .=   '<link itemprop="url" href="' . $endpoint_link . '" />';
            $result .=   '<meta itemprop="position" content="' . $position . '">';
            $result .= '</li>';
        } elseif (is_page()) {
            // This is a normal site page
            // If the article has a parent article (per post meta field), add parent
            $post_parent = get_field('post_parent', $post_id);
            if (! empty($post_parent)) {
                $result .= '<li class="bc-item bc-parent" ';
                $result .=    'itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                $result .=   '<a class="bc-link" href="' . get_permalink($post_parent) . '" itemprop="item">';
                $result .=     '<span itemprop="name">' . get_the_title($post_parent) . '</span>';
                $result .=   '</a>';
                $result .=   '<meta itemprop="position" content="' . $position . '">';
                $result .= '</li>';
                $position++;
            }

            // Finally, output the page being displayed
            $endpoint_link = get_permalink($post_id);
            $endpoint_title = get_the_title($post_id);
            $result .= '<li class="bc-item bc-title" ';
            $result .=    'itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
            $result .=   '<span itemprop="name">' . $endpoint_title . '</span>';
            $result .=   '<link itemprop="url" href="' . $endpoint_link . '" />';
            $result .=   '<meta itemprop="position" content="' . $position . '">';
            $result .= '</li>';
        } else {
            error_log("function-oc-breadcrumbs-pack UNKNOWN Post Type '" . $post->post_type . "'");
        }

        $result .= '</ul>';

        return $result;
    }

    // Define the fields necessary to support the concept of a Post or Page Parent.
    if (function_exists('acf_add_local_field_group')) {
        acf_add_local_field_group(array(
            'key' => 'group_62ebace4db252',
            'title' => '親記事設定',
            'fields' => array(
                array(
                    'key' => 'field_62ebacfbdbcc2',
                    'label' => '親記事',
                    'name' => 'post_parent',
                    'type' => 'post_object',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'post_type' => array(
                        0 => 'post',
                        1 => 'page',
                    ),
                    'taxonomy' => '',
                    'allow_null' => 1,
                    'multiple' => 0,
                    'return_format' => 'id',
                    'ui' => 1,
                ),
                array(
                    'key' => 'no_sidebar',
                    'label' => 'フルページ表示',
                    'name' => 'no_sidebar',
                    'type' => 'true_false',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'post_type' => array(
                        0 => 'post',
                        1 => 'page',
                    ),
                    'default_value' => 0,
                    'ui' => 1,
                    'ui_on_text' => 'フルページ表示',
                    'ui_off_text' => 'サイドバー表示',
                ),
                array(
                    'key' => 'show_readtime',
                    'label' => '記事読了時間表示',
                    'name' => 'show_readtime',
                    'type' => 'true_false',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'post_type' => array(
                        0 => 'post',
                        1 => 'page',
                    ),
                    'default_value' => 0,
                    'ui' => 1,
                    'ui_on_text' => '表示',
                    'ui_off_text' => '非表示',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'post',
                    ),
                ),
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'page',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 0,
        ));
    }

    $breadcrumbs_style = <<<BCSTYLE
	.bread-crumbs {
		width: 100%;
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		font-size: 0.75rem;
		margin: 0;
		padding: 10px 1.5rem 10px 1.5rem;
		list-style: none;
        flex-wrap: wrap;
	}
	.bread-crumbs .bc-home-icon {
		margin-right: 3px;
		vertical-align: baseline;
	}
	.bread-crumbs .bc-link {
		color: #707070;
		text-decoration: underline;
	}
	.bread-crumbs .bc-item {
		display: inline-block;
		color: #707070;
		font-size: 0.75rem;
		font-weight: 500;
		margin: 0 0.75em 0 0;
        white-space: nowrap;
	}
	.bread-crumbs .bc-item:not(.bc-home):before {
		content: "\\f054";
		display: inline-block;
		color: #707070;
		margin: 0 0.75em 0 0;
		font-weight: 900;
		font-family: "Font Awesome 5 Free";
	}
BCSTYLE;

	function oc_breadcrumb_pack_enqueue_scripts() {
		global $breadcrumbs_style;
		wp_register_style('breadcrumbs-css', false);
		wp_enqueue_style('breadcrumbs-css');
		wp_add_inline_style('breadcrumbs-css', $breadcrumbs_style);
	}
	add_action('wp_enqueue_scripts', 'oc_breadcrumb_pack_enqueue_scripts');
}
