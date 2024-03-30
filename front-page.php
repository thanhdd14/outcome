<?php
    get_header();
    $template_uri = get_template_directory_uri();
?>

<section class="text-bg-top bg-image"
    style="background-image: url('<?php echo $template_uri; ?>/img/top-main.jpg');">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="caption">
                    <p>&lt;theme&gt;/front-page.php</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
    get_footer();
?>