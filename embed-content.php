<?php
/**
 * Contains the post embed content template part
 *
 * When a post is embedded in an iframe, this file is used to create the content template part
 * output if the active theme does not include an embed-content.php template.
 *
 * @package WordPress
 * @subpackage Theme_Compat
 * @since 4.5.0
 */
?>
<style>
.wp-embed {
    border: 1px solid #959FB1;
    background-color: #fff;
    padding: 0;
    border-radius: 3px;
    box-sizing: border-box;
}
.wp-embed > a.embded-link{
    padding: 12px;
    display: block;
}
.wp-embed > a.embded-link:hover{
    text-decoration: none;
}

/* 画像を正方形にクロップ */
.wp-embed-featured-image {
    width: 20%;
    position: relative;
    overflow: hidden;
    margin: 0 0 0 15px;
    padding: 0;
    border-radius: 3px;
}
/* 画像を正方形に保つ */
.wp-embed-featured-image::before{
    content: "";
    display: block;
    padding-top: 100%;
}
.wp-embed-featured-image img {
    position: absolute;
    width: auto;
    height: 100%;
    top: 50%;
    left: 50%;
    -webkit-transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
}
.wp-embed-featured-image a:hover img{
    filter: alpha(opacity=75);
    opacity: 0.75;
}

/* タイトル */
p.wp-embed-heading{
    font-size: 1.2rem;
    color: black;
}
.insert-cate {
    display: inline-flex;
    padding: 0.2em 0.6em;
    background-color: #E6EBF5;
    color: #0a1325;;
    margin-bottom: 0.5rem !important;
    font-size: 1rem;
}
/* 概要 */
.wp-embed-excerpt{
    overflow: hidden; /* 画像への回り込み防止*/
}

/* フッター */
.wp-embed-footer{
    clear: both;
    padding-top: 8px;
}
.wp-embed-site-icon {
    position: unset;
    transform: unset;

    margin-right: 7px
}
.site-name-wrap{
    display: flex;
    align-items: center;
}
</style>
    
    <div <?php post_class('wp-embed'); ?>>
        <a href="<?php the_permalink(); ?>" target="_top" class="embded-link">
        <?php
        $thumbnail_id = 0;

        if (has_post_thumbnail()) {
            $thumbnail_id = get_post_thumbnail_id();
        }

        if ('attachment' === get_post_type() && wp_attachment_is_image()) {
            $thumbnail_id = get_the_ID();
        }

        /**
         * Filters the thumbnail image ID for use in the embed template.
         *
         * @since 4.9.0
         *
         * @param int $thumbnail_id Attachment ID.
         */
        $thumbnail_id = apply_filters('embed_thumbnail_id', $thumbnail_id);

        if ($thumbnail_id) {
            $aspect_ratio = 1;
            $measurements = array( 1, 1 );
            $image_size   = 'full'; // Fallback.

            $meta = wp_get_attachment_metadata($thumbnail_id);
            if (! empty($meta['sizes'])) {
                foreach ($meta['sizes'] as $size => $data) {
                    if ($data['height'] > 0 && $data['width'] / $data['height'] > $aspect_ratio) {
                        $aspect_ratio = $data['width'] / $data['height'];
                        $measurements = array( $data['width'], $data['height'] );
                        $image_size   = $size;
                    }
                }
            }

            /**
             * Filters the thumbnail image size for use in the embed template.
             *
             * @since 4.4.0
             * @since 4.5.0 Added `$thumbnail_id` parameter.
             *
             * @param string $image_size   Thumbnail image size.
             * @param int    $thumbnail_id Attachment ID.
             */
            $image_size = apply_filters('embed_thumbnail_image_size', $image_size, $thumbnail_id);

            //$shape = $measurements[0] / $measurements[1] >= 1.75 ? 'rectangular' : 'square';
            // 画像のアスペクト比に関係なく全て square（正方形）として扱う
            $shape = 'square';

            /**
             * Filters the thumbnail shape for use in the embed template.
             *
             * Rectangular images are shown above the title while square images
             * are shown next to the content.
             *
             * @since 4.4.0
             * @since 4.5.0 Added `$thumbnail_id` parameter.
             *
             * @param string $shape        Thumbnail image shape. Either 'rectangular' or 'square'.
             * @param int    $thumbnail_id Attachment ID.
             */
            $shape = apply_filters('embed_thumbnail_image_shape', $shape, $thumbnail_id);
        }

        if ($thumbnail_id && 'rectangular' === $shape) :
            ?>
            <div class="wp-embed-featured-image rectangular">
                <!-- <a href="<?php the_permalink(); ?>" target="_top"> -->
                    <?php echo wp_get_attachment_image($thumbnail_id, $image_size); ?>
                <!-- </a> -->
            </div>
        <?php endif; ?>

        <!--
        <p class="wp-embed-heading">
            <a href="<?php the_permalink(); ?>" target="_top">
                <?php the_title(); ?>
            </a>
        </p>
        ↓ 画像とタイトルの表示順番を統一するため、タイトル部分を下に移動
        -->

        <?php if ($thumbnail_id && 'square' === $shape) : ?>
            <div class="wp-embed-featured-image square">
                <!-- <a href="<?php the_permalink(); ?>" target="_top"> -->
                    <?php echo wp_get_attachment_image($thumbnail_id, $image_size); ?>
                <!-- </a> -->
            </div>
        <?php endif; ?>
        <p class="insert-cate mb-2 text-primary-color">関連記事</p>
        <p class="wp-embed-heading">
            
                <?php the_title(); ?>
            
        </p>

        <!-- <div class="wp-embed-excerpt"><?php the_excerpt_embed(); ?></div> -->

        <?php
        /**
         * Prints additional content after the embed excerpt.
         *
         * @since 4.4.0
         */
        // do_action( 'embed_content' );
        ?>
            <div class="site-name-wrap">
                <img src="<?php echo  get_site_icon_url(32, includes_url('images/w-logo-blue.png'));?>" srcset="<?php echo  get_site_icon_url(64, includes_url('images/w-logo-blue.png'));?> 2x" width="32" height="32" alt="" class="wp-embed-site-icon">
                <span><?php echo  get_bloginfo('name');?></span>
            </div>
        </a>
    </div>
    
<?php