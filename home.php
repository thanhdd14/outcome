<?php
    $template_uri = get_template_directory_uri();

    get_header();

    $use_image_for_header_cover = get_field('use_image_for_header_cover', 'option');
    $header_cover_image = get_field('header_cover_image', 'option');
if ($use_image_for_header_cover && isset($header_cover_image)) {
    $header_cover_image_url = $header_cover_image["url"];
}
    $header_cover_color = get_field('header_cover_color', 'option');
?>
<section class="text-bg-top img-cover-white" 
    style="<?php if ($use_image_for_header_cover) {
                echo "background-image: url('{$header_cover_image_url}');";
                echo "background-position: center;";
                echo "background-repeat: no-repeat;";
                echo "background-size: cover;";
                } else {
                echo "background: {$header_cover_color};";
                }
            ?>">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="caption">
                    <h2 class="text-jp"><?php single_post_title();?></h2>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="py-5 px-4 wrapp-blog-list">
    <div class="blog-container">
        <div class="blog-list">
            <div class="d-flex flex-wrap col-gap">
                <?php get_template_part('template-parts/article', 'list'); ?>
            </div>
        </div>
        <?php get_template_part('template-parts/article', 'pagination'); ?>
    </div>
    <div class="blog-sidebar">
        <?php dynamic_sidebar('article-sidebar'); ?>
    </div>
</section>
<?php
    get_footer();
?>