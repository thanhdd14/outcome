<?php
    /* Template Name: Thankyou */
    $template_uri = get_template_directory_uri();

    get_header();
?>

<div class="header-space"></div>
<section class="section-thanks" style="padding: 100px 0;">
    <div class="container">
        
        <div class="cover-button text-center">
            <h2>お問い合わせありがとうございました。</h2>
            <a href="<?php echo get_home_url(); ?>" class="back-button" 
               style="margin-top: 30px; display: inline-block; padding: 15px; border:2px solid #111; color:#111;">
                トップページへ戻る
            </a>
        </div>

    </div>
</section>
<?php
    get_footer();
?>