<?php
$template_uri = get_template_directory_uri();
$img_id   = get_post_thumbnail_id();
$img_url  = wp_get_attachment_url($img_id);

$author_id = $post->post_author;

$author_intro = '';
$author_clinic_title = '';
$author_public_title = '';
$author_img_url = '';
if ( ! empty($author_id) ) {
    $author_key = 'user_' . $author_id;
    $author_intro = get_field('oc_user_short_introduction', $author_key);
    $author_clinic_title = get_field('oc_user_clinic_title', $author_key);
    $author_public_title = get_field('oc_user_public_title', $author_key);
    $author_image = get_field('oc_user_avatar', $author_key);
    if (is_array($author_image)) {
        $author_img_url = $author_image['url'];
    }
}
$author_url = get_author_posts_url($author_id);
// Get user object
$author_obj = get_user_by( 'ID',  $author_id);
// Get user display name
$author_display_name = $author_obj->display_name;

get_header();

if (have_posts()) :
    while (have_posts()) :
        the_post();
        $cat_name = '';
        $cats = get_the_category($post->ID);
        if (count($cats) > 0 && $cats[0]->name != 'Uncategorized') {
            $cat_name = $cats[0]->name;
            $cat_link = get_category_link($cats[0]);
        }
        if (get_field('no_sidebar', $post->ID)) {
            $no_sidebar = true;
        }
?>

<section class="wrapp-blog-page px-3">
    <div class="wrapp-blog-container <?php echo $no_sidebar? 'no-sidebar':''; ?>">
        <div class="blog-container">
            <div class="blog-content-wrapper">
                <div class="blog-bc">
                    <?php echo oc_breadcrumbs(); ?>
                </div>
                <div class="blog-header">
                    <h1 class="title-name text-primary-color mb-3"><?php the_title(); ?></h1>
<?php
                    if ( ! empty($cat_name) ) {
?>
                    <a href="<?php echo $cat_link; ?>"
                        title="Category <?php echo $cat_name; ?>" class="link-cate blog-cate mb-0 mb-sm-3"
                        ><span><?php echo $cat_name; ?></span></a>
<?php
                    }
                    // the_time('Y-m-d')で得られる日付と、the_modified_date('Y-m-d')で得られる日付で1ヶ月以上差がある場合に更新日を表示
                    $post_date = get_the_time('Y-m-d');
                    $modified_date = get_the_modified_date('Y-m-d');
                    $post_date = new DateTime($post_date);
                    $modified_date = new DateTime($modified_date);
                    $interval = $post_date->diff($modified_date);
?>
                   <div class="d-flex justify-content-end align-items-center align-self-end">
                        <time class="d-flex justify-content-center align-items-center"
                            datetime="<?php the_time('Y-m-d') ?>"
                            itemprop="datepublished"
                        ><img class="me-1" src="<?php echo $template_uri; ?>/img/ico-publish.png"
                            /><?php the_time('Y\年m\月d\日')
                        ?></time>
<?php
                    if ($interval->m >= 1) {
?>
                        <time class="ms-2 d-flex justify-content-center align-items-center"
                            datetime="<?php the_modified_date('Y-m-d') ?>"
                            itemprop="datemodified"
                        ><img class="me-1" src="<?php echo $template_uri; ?>/img/ico-update.png"
                            /><?php the_modified_date('Y\年m\月d\日')
                        ?></time>
<?php
                    }
?>
                    </div>
                </div>
                <div class="blog-content">
<?php
                if ( ! empty($img_url) ) {
?>
                    <div class="blog-main-img my-4">
                        <img src="<?php echo $img_url; ?>">
                    </div>
<?php
                }
                the_content();
                // コンテンツが300文字以上の場合、続きを読むボタンを表示
                $content = get_the_content();
                $content = strip_tags($content);
                $content = mb_substr($content, 0, 300);
                $content = rtrim($content, ".,!?…");
                $content = $content . '...';
                if (strlen($content) > 300) {
                    
?>
                </div>
<?php
                if ($author_img_url) {
?>
                <div class="blog-author">
                    <div class="author-heading">
                        <p class="author-heading-text mb-0 text-primary-color font-noto">著者紹介</p>
                    </div>
                    <a href="<?php echo $author_url; ?>"
                        class="author-content d-flex justify-content-between align-items-start text-dark"
                    >
                        <div class="author-img-wrap">
                            <div class="author-img">
                                <img src="<?php echo $author_img_url; ?>">
                            </div>
                            <div class="author-title mobile">
                                <div class="author-title-wrap fw-bold font-noto font-18">
                                    <span class="public"><?php echo $author_public_title; ?></span>
                                    <span class="clinic font-12"><?php echo $author_clinic_title; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="author-detail">
                            <div class="author-title desktop">
                                <p class="fw-bold font-noto font-18">
                                    <?php echo $author_public_title; ?>
                                    <span class="ms-3 font-12"><?php echo $author_clinic_title; ?></span>
                                    <span class="ms-3 font-12"><?php echo $author_display_name; ?></span>
                                </p>
                            </div>
                            <div class="author-desc">
                                <p class="font-15"><?php echo $author_intro; ?></p>
                            </div>
                        </div>
                    </a>
                </div>
<?php
                }
?>
                <div class="article-pagination">
<?php
                $prev_post = get_adjacent_post(false, '', true);
                if ( ! empty($prev_post) ) {
                    echo '<a href="' . get_permalink($prev_post->ID) .
                        '" class="pagination-link prev" title="' . $prev_post->post_title . '">';
                    echo '<img class="arrow prev" src="' . $template_uri . '/img/long-arrow-left.png">';
                    echo '前の記事へ';
                    echo '</a>';
                } else {
                    echo '<span class="pagination-empty"></span>';
                }

                $next_post = get_adjacent_post(false, '', false);
                if (! empty($next_post)) {
                    echo '<a href="' . get_permalink($next_post->ID) .
                        '" class="pagination-link next" title="' . $next_post->post_title . '">';
                    echo '次の記事へ';
                    echo '<img class="arrow next" src="' . $template_uri . '/img/long-arrow-right.png">';
                    echo '</a>';
                } else {
                    echo '<span class="pagination-empty"></span>';
                }
?>
                </div>
                <div class="blog-btn d-flex justify-content-center align-items-center">
                    <?php
                        $blog_id  = get_option('page_for_posts');
                        $blog_url = get_permalink( $blog_id );
                    ?>
                    <a href="<?php echo $blog_url; ?>" class="back-btn blue-btn">記事一覧へ</a>
                </div>
            </div>
        </div>
<?php
        if ( !$no_sidebar ) {
?>
        <div class="blog-sidebar">
            <?php dynamic_sidebar('article-sidebar'); ?>
        </div>
<?php
        }
?>
    </div>
</section>

<?php
    endwhile;
endif;
get_footer();
?>