<?php
    /* Template Name: Company */
    $template_uri = get_template_directory_uri();

    get_header();
?>
<section class="text-bg-top img-cover-white" style="background-image: url('<?php echo $template_uri; ?>/img/top-main.jpg');">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="caption">
                    <h2 class="text-jp">法人概要</h2>
                    <h2 class="text-eng">&lt;theme&gt;/p_Company.php</h2>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
    get_footer();
?>