<?php

// Because Page titles are often changed to Japanese language when
// the project is close to delivery, it can be difficult for us to
// recognize the pages we need to work with. So we add a column to
// show the page slug for pages.

if ( ! function_exists('oc_admin_pages_slug_column') ) {
	function oc_admin_pages_slug_column( $columns ) {
		$result = array();
		foreach ( $columns as $key => $name ) {
			if ( $key == 'author' ) {
				$result['page-slug'] = "Slug";
			}
			$result[$key] = $name;
		}
		return $result;
	}

	function oc_admin_pages_slug_value( $column_name, $page_id ) {
		if ( 'page-slug' == $column_name ) {
			echo get_post_field( 'post_name', $page_id );
		}
	}

	// for pages
	add_filter( 'manage_pages_columns',       'oc_admin_pages_slug_column' );
	add_action( 'manage_pages_custom_column', 'oc_admin_pages_slug_value', 10, 2 );
}
