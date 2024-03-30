<?php
$default_image = get_field('archive_image', 'option');

if (have_posts()) :
    while (have_posts()) :
        the_post();
        $cat_name = '';
        $cats = get_the_category($post->ID);
        if (count($cats) > 0 && $cats[0]->name != 'Uncategorized') {
            $cat_name = $cats[0]->name;
            $cat_link = get_category_link($cats[0]);
        }

        if (isset($default_image)) {
            $img_url  = $default_image["url"];
        }

        $img_id   = get_post_thumbnail_id($post->ID);
        if (! empty($img_id)) {
            $img_url = wp_get_attachment_url($img_id);
        }
        $bgstyle = '';
        if (! empty($img_url)) {
            $bgstyle = ' style="background-image: url(' . "'" . $img_url . "'" . ');"';
        }
        ?>
            <a class="blog-item" href="<?php echo get_permalink($post->ID) ?>">
                <div class="blog-img">
                    <div class="img-url" <?php echo $bgstyle; ?> ></div>
                </div>
                <div class="blog-content">
                    <h3 class="mb-2 blog-title text-primary-color"><?php echo get_the_title($post->ID); ?></h3>
        <?php
        if (! empty($cat_name)) {
            ?>
                    <p class="mb-0 blog-cate"><?php echo $cat_name; ?></p>
            <?php
        }
        ?>
                </div>
                <div class="blog-date">
                    <p class="mb-0 text-end text-dark"><?php echo get_the_time('Y年m月d日', $post->ID);?></p>
                </div>
            </a>
        <?php
    endwhile;
endif;
?>
