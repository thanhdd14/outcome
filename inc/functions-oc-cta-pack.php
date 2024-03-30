<?php

/**
 * Function to add columns in oc_cpt_cta post type index page
 *    o Add cta_status
 *    o Add cta_position
 *    o Add cta_start_date
 *    o Add cta_end_date
 *    o Add shortcode
 * @since 20220703 by KNISHIT
 *
 *
 */
add_filter("manage_oc_cpt_cta_posts_columns", 'oc_course_manage_columns');
function oc_course_manage_columns($columns)
{
    $new_columns = array();
    foreach ($columns as $key => $title) {
        $new_columns[$key] = $title;
        if ($key == 'title') {
            $new_columns['cta_status'] = 'ステータス';
            $new_columns['cta_position'] = '共通表示先';
            $new_columns['cta_start_date'] = '開始日';
            $new_columns['cta_end_date'] = '終了日';
            $new_columns['shortcode'] = 'ショートコード';
        }
    }
    return $new_columns;
}
add_filter("manage_oc_cpt_cta_posts_custom_column", 'oc_course_custom_columns', 10, 3);
function oc_course_custom_columns($column, $post_id)
{
    $template_uri = get_template_directory_uri();

    switch ($column) {
        case 'cta_status':
            $field_val = get_field('cta_status', $post_id);
            ;
            echo $field_val? "有効" : "無効";
            break;
        case 'cta_position':
            $field_val = get_field('cta_display_position', $post_id);
            ;
            echo $field_val;
            break;
        case 'cta_start_date':
            echo get_field('cta_start_date', $post_id);
            break;
        case 'cta_end_date':
            echo get_field('cta_end_date', $post_id);
            break;
        case 'shortcode':
            echo '[OC_CTA cta_id=' . $post_id . ']';
            break;
        default:
            break;
    }
}


/**
 * Function to get a cta content defined in oc_cpt_cta
 * (OC CTA PACK)
 *
 * @since 20220703 by KNISHIT
 * @param string $postion key name to query
 * Available keys:
 *  post
 *  page
 * @param integer $postid for specifying the oc_cpt_cta type post id
 * @return array of cta content
 *
 */
if (! function_exists('oc_get_ctapost')) {
    function oc_get_ctapost($position = 'post', $postid = null)
    {
        $today = new DateTime();
        $today->setTimeZone(new DateTimeZone('Asia/Tokyo'));
        $result = null;
        if ($postid) {
            $cta_status         = get_field('cta_status', $postid);
            $cta_start_date     = get_field('cta_start_date', $postid, false);
            $cta_end_date       = get_field('cta_end_date', $postid, false);
            $cta_html           = get_field('cta_html', $postid);
            try {
                $cta_start_date     = new DateTime($cta_start_date);
                $cta_end_date       = new DateTime($cta_end_date);
            } catch (Exception $e) {
                error_log($e->getMessage());
                return '';
            }
            if ($today >= $cta_start_date && $cta_status && $today <=  $cta_end_date) {
                $result     = array(
                    'cta_html'         => $cta_html,
                );
                return $result;
            }
        } else {
            $args = array(
                'post_type'  => 'oc_cpt_cta',
                'meta_key'   => 'cta_display_position',
                'meta_value' => $position,
                'nopaging'     => true,
                'meta_query' => array(
                    array(
                        'key'     => 'cta_status',
                        'value'   => 1,
                        'compare' => '=',
                    ),
                    array(
                        'key'     => 'cta_start_date',
                        'value'   => $today->format('Y-m-d'),
                        'compare' => '<=',
                        'type'    => 'DATE'
                    ),
                    array(
                        'key'     => 'cta_end_date',
                        'value'   => $today->format('Y-m-d'),
                        'compare' => '>=',
                        'type'    => 'DATE'
                    ),
                )
            );
            $posts = get_posts($args);
            if (is_array($posts)) {
                $cta_html   = get_field('cta_html', $posts[0]->ID);
                $result     = array(
                    'cta_html'         => $cta_html,
                );
            }
            return $result;
        }
    }
}


/**
 * Function to replace shortcode with cta contents
 * (OC CTA PACK)
 *
 * Show CTA content where the short code '[]' is placed
 * The user will use this shortcode with specifying cta post id or cta postion id
 * [OC_CTA cta_id=123], [OC_CTA cta_position="post"]
 * @since 20220703 by KNISHIT
 * @param array $atts should look like below
 *  [
 *      'cta_id' => 123,
 *      'cta_position' => 'post'
 *  ]
 */
function oc_cta_shortcode($atts = array())
{
    if (array_key_exists('cta_id', $atts)) {
        $cta_content = oc_get_ctapost('', $atts['cta_id']);
        return $cta_content['cta_html'];
    } elseif (array_key_exists('cta_position', $atts)) {
        $cta_content = oc_get_ctapost($atts['cta_position']);
        return $cta_content['cta_html'];
    } else {
        return "";
    }
}
add_shortcode('OC_CTA', 'oc_cta_shortcode');
