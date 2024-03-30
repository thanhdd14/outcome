<?php
    $template_uri = get_template_directory_uri();
?>

<section class="footer">
    <h2>&lt;theme&gt;/footer.php</h2>
<!--
    This is: <theme>/footer.php
    *** PLACE COMMON FOOTER AND NAVIGATION CODE HERE ***

    $secondary_menu_args = array(
        'theme_location' => 'secondary',
        'menu_id'        => 'secondary',
        'container'      => false
    ));
    wp_nav_menu( $secondary_menu_args );

    IF the page being displayed has a Google Map, then include the API...
< ?php
    $slug = get_post_field( 'post_name', get_post() );
    if ( $slug == 'company' ) {
? >
    <script async defer
        src="//maps.googleapis.com/maps/api/js?key=XXXX&callback=google_maps_callback"
        ></script>
< ?php
    }
? >
-->
</section>
<?php wp_footer(); ?>
</body>
</html>
