<?php
    get_header();
    $template_uri = get_template_directory_uri();
?>
    <section class="text-bg-top img-cover-white" style="background-image: url('<?php echo $template_uri; ?>/img/top-main.jpg');">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="caption">
                        <h2 class="text-jp">の検索結果</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="wrapp-blog-list">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
<?php
if (have_posts()) :
    ?>
                    <div class="blog-list">
                        <h1 class="title-text"><?php single_cat_title(); ?></h1>
    <?php
    while (have_posts()) :
        the_post();
        get_template_part('template-parts/content', 'search');
    endwhile;
    ?>
                    </div>
    <?php
else :
                    get_template_part('template-parts/content', 'none');
endif;
?>
                </div>
            </div>
        </div>
    </section>

<?php
    get_footer();
?>