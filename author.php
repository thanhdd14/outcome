<?php
    $author_id = get_queried_object_id();
    $user_key = 'user_' . $author_id;
    $template_uri = get_template_directory_uri();
    $author_name            = get_the_author_meta('display_name');
    $user_public_title      = get_field('oc_user_public_title', $user_key);
    $user_portait_img       = get_field('oc_user_portait_img', $user_key);
    $user_clinic_title      = get_field('oc_user_clinic_title', $user_key);
    $user_cover_img         = get_field('oc_user_cover_img', $user_key);
    $user_introduction      = get_field('oc_user_introduction', $user_key);
    $user_clinic_title      = get_field('oc_user_clinic_title', $user_key);
    $user_family_name_en    = get_field('oc_user_family_name_en', $user_key);
    $user_given_name_en     = get_field('oc_user_given_name_en', $user_key);
    $user_career            = get_field('oc_user_career', $user_key);
    $user_qualification     = get_field('oc_user_qualification', $user_key);
    $user_scholarly_paper   = get_field('oc_user_scholarly_paper', $user_key);
    $user_conference        = get_field('oc_user_conference', $user_key);
    $user_hobby             = get_field('oc_user_hobby', $user_key);

if (! $user_cover_img) {
    $user_cover_img['url'] = $template_uri . '/img/chiba-default-image.jpg';
}

    get_header();
?>
<section class="blog-heading-container d-none d-sm-block">
    <div class="blog-heading-text">
        <div class="intro-title d-flex flex-column justify-content-start align-items-start">
            <h2 class="intro-text font-futura text-blue">スタッフ紹介</h2>
            <h1 class="intro-subtext font-noto text-dark"><p class="mb-0"><?php echo $user_clinic_title; ?> <?php echo $author_name;?></p>(<?php echo $user_public_title; ?>)</h1>
        </div>
    </div>
</section>
<section class="blog-mobile-intro d-block d-sm-none">
    <div class="section-title d-flex flex-column justify-content-center align-items-center">
        <div class="mb-0 section-label">スタッフ紹介</div>
        <h1 class="intro-subtext font-noto text-dark"><?php echo $author_name;?></h1>
    </div>
</section>
<section class="wrapp-blog-page">
    <div class="wrapp-blog-container">
        <div class="blog-container">
            <div class="blog-content-wrapper author-page">
                <div class="author-main-img">
                    <img src="<?php echo $user_cover_img['url']; ?>">
                </div>
                <div class="author-intro px-2 my-2">
                    <?php echo $user_introduction; ?>
                </div>
                <div class="user-detail">
<?php if ($user_career) { ?>
                    <div class="user-group-detail">
                        <!-- <p class="text-blue author-label font-noto mb-3">経歴</p> -->
                        <?php echo $user_career; ?>
                    </div>
<?php } ?>
<?php if ($user_qualification) { ?>
                    <div class="user-group-detail">
                        <!-- <p class="text-blue author-label font-noto mb-3">資格</p> -->
                        <?php echo $user_qualification; ?>
                    </div>
<?php } ?>
<?php if ($user_scholarly_paper) { ?>
                    <div class="user-group-detail">
                        <!-- <p class="text-blue author-label font-noto mb-3">論文</p> -->
                        <?php echo $user_scholarly_paper; ?>
                    </div>
<?php } ?>
<?php if ($user_conference) { ?>
                    <div class="user-group-detail">
                        <!-- <p class="text-blue author-label font-noto mb-3">論文</p> -->
                        <?php echo $user_conference; ?>
                    </div>
<?php } ?>
<?php if ($user_hobby) { ?>
                    <div class="user-group-detail">
                        <!-- <p class="text-blue author-label font-noto mb-3">論文</p> -->
                        <?php echo $user_hobby; ?>
                    </div>
<?php } ?>
                </div>
<?php
$args = array(
    'post_type'      => 'post',
    'posts_per_page' => 4,
    'author'         => $author_id
);
$articles = new WP_Query($args);
if ($articles->have_posts()) :
    ?>
                <div class="wrapp-article-list wow animate__fadeIn" data-wow-duration="1s">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <h3 class="articles-heading text-blue font-noto">コラム一覧</h3>
    <?php
    while ($articles->have_posts()) :
        $articles->the_post();
        $article_id = $post->ID;
        $article_date = get_the_date('Y年m月d日', $article_id);
        $article_title = get_the_title($article_id);
        ?>
                                <a class="author-article article-link d-flex  flex-row justify-content-start align-items-start align-items-sm-center mb-3" href="<?php echo get_permalink($article_id); ?>">
                                    <span class="article-date text-dark"><?php echo $article_date; ?></span>
                                    <p class="mb-0 text-blue article-title"><?php echo $article_title; ?></p>
                                </a>
        <?php
    endwhile;
    ?>
                            </div>
                        </div>
                    </div>
                </div>
    <?php
endif;
wp_reset_postdata();
?>
                <div class="blog-btn d-flex justify-content-center align-items-center">
                    <a class="back-btn blue-btn">記事一覧へ</a>
                </div>
            </div>
        </div>
        <div class="blog-sidebar">
            <?php dynamic_sidebar('article-sidebar'); ?>
        </div>
    </div>
</section>


<?php
get_footer();
?>