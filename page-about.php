<?php
    phpinfo();
    dd();
    /* Template Name: About */
    $template_uri = get_template_directory_uri();

    get_header();
?>
<section class="text-bg-top img-cover-white"
    style="background-image: url('<?php echo $template_uri; ?>/img/top-main.jpg');">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="caption">
                    <h2 class="text-jp">お問い合わせ</h2>
                    <h2 class="text-eng">&lt;theme&gt;/p_About.php</h2>
                </div>
            </div>
        </div>
    </div>
</section>
<section style="padding: 100px 0;">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php the_content(); ?>
            </div>
        </div>
    </div>
</section>
<?php
    get_footer();
?>
