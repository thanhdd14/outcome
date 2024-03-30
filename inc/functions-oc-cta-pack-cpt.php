<?php
function cptui_register_my_cpts()
{

    /**
     * Post Type: 共通CTA.
     */

    $labels = [
        "name" => __("共通CTA", "custom-post-type-ui"),
        "singular_name" => __("共通CTA", "custom-post-type-ui"),
    ];

    $args = [
        "label" => __("共通CTA", "custom-post-type-ui"),
        "labels" => $labels,
        "description" => "",
        "public" => false,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => true,
        "rest_base" => "",
        "rest_controller_class" => "WP_REST_Posts_Controller",
        "rest_namespace" => "wp/v2",
        "has_archive" => false,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "delete_with_user" => false,
        "exclude_from_search" => false,
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "can_export" => false,
        "rewrite" => [ "slug" => "oc_cpt_cta", "with_front" => true ],
        "query_var" => true,
        "menu_icon" => "dashicons-pressthis",
        "supports" => [ "title" ],
        "show_in_graphql" => false,
    ];

    register_post_type("oc_cpt_cta", $args);
}

add_action('init', 'cptui_register_my_cpts');
