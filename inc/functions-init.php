<?php

// Starting with WordPress 5, Site Health began reporting a problem with
// "active open session". I believe (based on several issue reports) that
// this was a new problem due to WP's heavy use of AJAX calls in the new
// Block Editor, which would cause timeout issues (I presume) due to the
// session semaphore being locked during the AJAX call. Extensive research
// showed no real solution to this problem other than to stop opening the
// session here and leaving it open. In other words, sessions must be
// opened, used, and closed within a block of execution. So, here we will
// open our session, ensure that 'form' is available, then close it. In
// our Contact processing and Contact feedback, we will likewise open the
// session, get our 'form' data, then close the session, thus avoiding
// leaving the session open and active.
//
if (! function_exists('oc_init_sessions')) {
    function oc_init_sessions()
    {
        session_start();
        if (! isset($_SESSION['form'])) {
            $_SESSION['form'] = array();
        }
        session_write_close();
    }
    add_action('init', 'oc_init_sessions', 1);
}

// This function is provided as a convenient method of setting session
// variables using the "start, write, and close" mechanism that we now
// need to use with WP 5+. You can see an example of it's usage in the
// code in php/process-contact.php
//
if (! function_exists('oc_session_write')) {
    function oc_session_write($idx, $val)
    {
        session_start();
        $_SESSION[$idx] = $val;
        session_write_close();
    }
}

add_theme_support('post-thumbnails');

/*
 * Switch default core markup for search form, comment form, and comments
 * to output valid HTML5.
 */
add_theme_support(
    'html5',
    array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    )
);

/*
 * Register the header and footer menus.
 */
register_nav_menus(array(
    'primary'   => __('Primary Menu', 'OCTheme'),
    'secondary' => __('Secondary Menu', 'OCTheme')
    ));

/*
 * Register the Article sidebar used on Blog pages.
 * If the website does not include a sidebar, or if you need to
 * custom code the sidebar, then comment out this registration.
 *
 */
register_sidebar(
    array (
        'name'          => 'article-sidebar',
        'id'            => 'article_sidebar',
        'before_widget' => '',
        'after_widget'  => ''
    )
);

show_admin_bar(false);

remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

/**
 * Remove indicators of the WordPress version that is running
 * to make the site less hackable.
 */
remove_action('wp_head', 'wp_generator');
if (! function_exists('oc_remove_wp_ver')) {
    function oc_remove_wp_ver($src)
    {
        if (strpos($src, 'ver=')) {
            $wp_vers = get_bloginfo('version');
            $query_str = parse_url($src, PHP_URL_QUERY);
            parse_str($query_str, $query_params);
            if (isset($query_params['ver']) && $query_params['ver'] == $wp_vers) {
                $src = remove_query_arg('ver', $src);
            }
        }
        return $src;
    }
    add_filter('style_loader_src', 'oc_remove_wp_ver', 9999);
    add_filter('script_loader_src', 'oc_remove_wp_ver', 9999);
}

/**
 * Enqueue scripts and styles.
 */
if (! function_exists('oc_enqueue')) {
    function oc_enqueue()
    {
        global $is_production;
        $uri = get_template_directory_uri();

        $asset_version = get_field('asset_version', 'options');
        if (empty($asset_version)) {
            $template_path = get_template_directory();
            $style_mtime = filemtime($template_path . '/css/oc-theme.css');
            $script_mtime = filemtime($template_path . '/js/oc-theme.js');
            $asset_version = date('YmdHis', ($style_mtime > $script_mtime ? $style_mtime : $script_mtime));
        }

        /* CSS */
        if ($is_production) {
            // This should be the ONLY CSS file loaded, with all of the development CSS files
            // included into it and then minified.
            wp_enqueue_style('oc-min-css', ($uri . '/css/oc-theme.min.css'));
        } else {
            // Enqueue each of your development CSS files below...
            wp_enqueue_style('boostrap-css', ($uri . '/css/bootstrap.min.css'), array(), $asset_version, 'all');
            wp_enqueue_style('oc-blog-css', ($uri . '/css/oc-blog-style.css'), array('boostrap-css'), $asset_version, 'all');
            wp_enqueue_style('oc-contact-css', ($uri . '/css/oc-contact-style.css'), array('boostrap-css'), $asset_version, 'all');
            wp_enqueue_style('oc-theme-css', ($uri . '/css/oc-theme.css'), array('boostrap-css'), $asset_version, 'all');
        }

        /* JS */
        // If we need to install a different version of JQuery than the one provided by WordPress...
        // wp_deregister_script( 'jquery' );
        // wp_enqueue_script( 'jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js' );

        /* Load the html5 shiv scripts. */
        if (get_field('ie_include_shiv', 'option')) {
            $html5shiv_script = '//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js';
            wp_enqueue_script('oc-html5shiv', $html5shiv_script, array(), false, true);
            wp_script_add_data('oc-html5shiv', 'conditional', 'lt IE 9');

            $html5respond_script = '//cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js';
            wp_enqueue_script('oc-respond', $html5respond_script, array(), false, true);
            wp_script_add_data('oc-respond', 'conditional', 'lt IE 9');
        }

        if ($is_production) {
            // This should be the ONLY JS file loaded, with all of the development JS files
            // included into it and then minified.
            wp_enqueue_script('oc-min-js', ($uri . '/js/oc-theme.min.js'), array('jquery'), false, true);
        } else {
            // Enqueue each of your development JS files below...
            // Be sure to setup the dependencies correctly.
            wp_enqueue_script('boostrap-js', ($uri . '/js/bootstrap.min.js'), array('jquery'), $asset_version, true);
            wp_enqueue_script('oc-theme-js', ($uri . '/js/oc-theme.js'), array('jquery', 'boostrap-js'), $asset_version, true);
        }

        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }

    add_action('wp_enqueue_scripts', 'oc_enqueue');
}

if (! function_exists('oc_admin_enqueue')) {
    function oc_admin_enqueue()
    {
        $uri = get_template_directory_uri();
        wp_enqueue_style('oc-theme-admin', ($uri . '/css/oc-theme-admin.css'));
    }

    add_action('admin_enqueue_scripts', 'oc_admin_enqueue');
}

/**
 * Hide Admin menu items from non-BK and non-OC admins.
 */
if (! function_exists('oc_adjust_admin_menus')) {
    function oc_adjust_admin_menus()
    {
        remove_submenu_page('index.php', 'update-core.php');                    // Update
        remove_menu_page('edit-comments.php');                                  // Comments
        remove_submenu_page('themes.php', 'themes.php');                        // Appearance
        remove_submenu_page('themes.php', 'widgets.php');                       // Appearance
        remove_submenu_page('themes.php', 'theme-editor.php');                  // Appearance
        remove_menu_page('plugins.php');                                        // Plugins
        remove_menu_page('tools.php');                                          // Tools
        remove_menu_page('options-general.php');                                // Settings
        remove_menu_page('users.php');                                          // Users
        // remove_submenu_page( 'options-general.php', 'cp_calculated_fields_form' ); // Appearance

        remove_menu_page('cptui_main_menu');                                    // CPT UI
        // remove_menu_page( 'sitepress-multilingual-cms/menu/languages.php' );     // WPML
    }

    if (! isBKAdmin() && ! isOCAdmin()) {
        add_action('admin_menu', 'oc_adjust_admin_menus', 999);
        add_filter('acf/settings/show_admin', '__return_false');
    }
}

function oc_pre_get_avatar_data($args, $id_or_email)
{
    $user = false;
    if (is_numeric($id_or_email)) {
        $user = get_user_by('id', absint($id_or_email));
    } elseif (is_string($id_or_email)) {
        $user = get_user_by('email', $id_or_email);
    }

    if ($user !== false) {
        $avatar = function_exists('get_field') ? get_field('oc_user_avatar', 'user_'.$user->ID) : null;
        if (! empty($avatar)) {
            $args['url'] = $avatar['url'];
            if (isset($avatar['sizes']) && ! empty($avatar['sizes']['thumbnail'])) {
                $args['url'] = $avatar['sizes']['thumbnail'];
            }
        }
        // if ( ! empty($user->roles) && is_array($user->roles) && in_array('subscriber', $user->roles) ) {
        // if ( $user->user_login == 'admin_oc' ) {
    }

    return $args;
}
add_filter('pre_get_avatar_data', 'oc_pre_get_avatar_data', 10, 2);

/*
 * Add support for WEBP images
 */

//WebPのアップロードを許可
function add_upload_mines( $mimes ) {
    $mimes['webp'] = 'image/webp';
    return $mimes;
}
add_filter( 'mime_types', 'add_upload_mines' );

//メディア画面のWebPサムネイルを表示する
function enable_webp_thumbnail ( $result, $path ) {
    if ( $result === true ) return $result;
    //ファイルがWebPかどうか判定する
    $img_type = exif_imagetype($path);
    if ( $img_type === IMAGETYPE_WEBP ) {
        $result = true;
    }
    return $result;
}
add_filter( 'file_is_displayable_image', 'enable_webp_thumbnail', 10, 2 );



if (false) {
    function oc_kses_allowed_html($tags)
    {
        error_log("OC_KSES_ALLOW_HTML: [--> " . var_export($tags, true) . " ]");
        $tags = array();
        $tags['svg'] = array(
            'class' => array(),
            'xmlns' => array(),
            'fill' => array(),
            'viewbox' => array(),
            'role' => array(),
            'aria-hidden' => array(),
            'focusable' => array(),
        );
        $tags['g'] = array(
            'transform' => array(),
        );
        $tags['path'] = array(
            'd' => array(),
            'fill' => array(),
        );
        return $tags;
    }
    add_filter('init', 'oc_kses_allowed_html', 10, 2);
}
