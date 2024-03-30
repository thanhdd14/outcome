<?php

$is_production = function_exists('get_field')
    ? get_field('is_production', 'options')
    : false;


/*
-------------------------------------------
  Load Module
-------------------------------------------
*/


require_once('inc/functions-init.php');
require_once('inc/functions-admin.php');

// OC CONFIGURATION ACF Definition
require_once('inc/functions-acf-oc_configuration.php');
// OC Author ACF Definition
require_once('inc/functions-oc-author-acf.php');
// OC CLINIC ACF Definition
require_once('inc/functions-oc-clinic-acf.php');

// OC Theme Functions
require_once('inc/functions-oc.php');

// OC BC PACK
require_once('inc/functions-oc-breadcrumb-pack.php');
// OC QA PACK
require_once('inc/functions-oc-qa-pack.php');
// OC CTA PACK
require_once('inc/functions-oc-cta-pack-cpt.php');
require_once('inc/functions-oc-cta-pack-acf.php');
require_once('inc/functions-oc-cta-pack.php');
// END of OC CTA PACK

function isBKAdmin()
{
    $curruser = wp_get_current_user();
    return ! empty($curruser) && $curruser->user_login == 'admin_bk';
}

function isOCAdmin()
{
    $curruser = wp_get_current_user();
    return ! empty($curruser) && $curruser->user_login == 'admin_oc';
}

function oc_custom_sender_email($original_email_address)
{
    $email_from = get_field('email_from', 'option');
    if ($email_from) {
        return $email_from;
    }
}

/**
 * Automatically show TOC
 * Ref: https://xakuro.com/blog/wordpress/277/
 * @version 4.1.0
 */
function add_toc_content($content)
{
    if (is_single()) {
        $shortcode = '[toc showcount="4"]';

        $pattern = '/<h2.*?>/i';
        if (preg_match($pattern, $content, $matches)) {
            $content = preg_replace($pattern, $shortcode . $matches[0], $content, 1);
        }
    }
    return $content;
}
// Please remove this for enabling automatic TOC
// add_filter( 'the_content', 'add_toc_content', 10 );


/**
 * Table Of Contents Shortcode
 * 目次ショートコードです。
 * Ref: https://xakuro.com/blog/wordpress/277/
 * @version 4.1.0
 */
class Toc_Shortcode
{
    private $add_script = false;
    private $atts = array();

    public function __construct()
    {
        add_shortcode('toc', array( $this, 'shortcode_content' ));
        add_action('wp_footer', array( $this, 'add_script' ), 999999);
        add_filter('the_content', array( $this, 'change_content' ));
    }

    function change_content($content)
    {
        $elements = wp_html_split($content);
        $id = 1;
        foreach ($elements as &$element) {
            if (0 === strpos($element, '<h')) {
                if (! preg_match('/<h[1-6](.*?) id="([^"]*)"/u', $element)) {
                    $s = preg_replace('/<(h[1-6])(.*?)>/u', '<${1} id="toc' . $id . '" ${2}>', $element);
                    if ($element !== $s) {
                        $element = $s;
                        $id++;
                    }
                }
            }
        }
        return join($elements);
    }

    public function shortcode_content($atts)
    {
        global $post;

        if (! isset($post)) {
            return '';
        }

        $this->atts = shortcode_atts(array(
            'id' => '',
            'class' => 'toc',
            'title' => '目次',
            'toggle' => true,
            'opentext' => '開く',
            'closetext' => '閉じる',
            'close' => false,
            'showcount' => 2,
            'depth' => 0,
            'toplevel' => 2,
            'scroll' => 'smooth',
        ), $atts);

        $this->atts['toggle'] = ( false !== $this->atts['toggle'] && 'false' !== $this->atts['toggle'] ) ? true : false;
        $this->atts['close'] = ( false !== $this->atts['close'] && 'false' !== $this->atts['close'] ) ? true : false;

        $content = $post->post_content;
        $content = function_exists('do_blocks') ? do_blocks($content) : $content;

        $split = preg_split('/<!--nextpage-->/msuU', $content);
        $pages = array();
        $permalink = get_permalink($post);

        if (is_array($split)) {
            $page = 0;
            $counter = 0;
            $counters = array( 0, 0, 0, 0, 0, 0 );
            $current_depth = 0;
            $prev_depth = 0;
            $top_level = intval($this->atts['toplevel']);
            if ($top_level < 1) {
                $top_level = 1;
            }
            if ($top_level > 6) {
                $top_level = 6;
            }
            $this->atts['toplevel'] = $top_level;
            $max_depth = ( ( $this->atts['depth'] == 0 ) ? 6 : intval($this->atts['depth']) );

            $toc_list = '';

            foreach ($split as $content) {
                $headers = array();
                preg_match_all('/<(h[1-6])(.*?)>(.*?)<\/h[1-6].*?>/u', $content, $headers);
                $header_count = count($headers[0]);
                $id = 1;
                $page++;

                for ($i = 0; $i < $header_count; $i++) {
                    $depth = 0;
                    switch ($headers[1][$i]) {
                        case 'h1':
                            $depth = 1 - $top_level + 1;
                            break;
                        case 'h2':
                            $depth = 2 - $top_level + 1;
                            break;
                        case 'h3':
                            $depth = 3 - $top_level + 1;
                            break;
                        case 'h4':
                            $depth = 4 - $top_level + 1;
                            break;
                        case 'h5':
                            $depth = 5 - $top_level + 1;
                            break;
                        case 'h6':
                            $depth = 6 - $top_level + 1;
                            break;
                    }
                    if ($depth >= 1 && $depth <= $max_depth) {
                        if ($current_depth == $depth) {
                            $toc_list .= '</li>';
                        }
                        while ($current_depth > $depth) {
                            $toc_list .= '</li></ul>';
                            $current_depth--;
                            $counters[$current_depth] = 0;
                        }
                        if ($current_depth != $prev_depth) {
                            $toc_list .= '</li>';
                        }
                        if ($current_depth < $depth) {
                            $class = $current_depth == 0 ? ' class="toc-list"' : '';
                            $style = $current_depth == 0 && $this->atts['close'] ? ' style="display: none;"' : '';
                            $toc_list .= "<ul{$class}{$style}>";
                            $current_depth++;
                        }
                        $counters[$current_depth - 1]++;
                        $number = $counters[0];
                        for ($j = 1; $j < $current_depth; $j++) {
                            $number .= '.' . $counters[$j];
                        }
                        $counter++;

                        if (preg_match('/.*? id="([^"]*)"/u', $headers[2][$i], $m)) {
                            $href = '#' . $m[1];
                        } else {
                            $href = '#toc' .  $id;
                            $id++;
                        }

                        if (1 < $page) {
                            $href = trailingslashit($permalink) . $page . '/' . $href;
                        }

                        $toc_list .= '<li><a href="' . esc_url($href) . '"><span class="contentstable-number">' . $number . '</span> ' . strip_shortcodes($headers[3][$i]) . '</a>';

                        $prev_depth = $depth;
                    }
                }
            }

            while ($current_depth >= 1) {
                $toc_list .= '</li></ul>';
                $current_depth--;
            }
        }

        $html = '';
        if ($counter >= $this->atts['showcount']) {
            $this->add_script = true;

            $toggle = '';
            if ($this->atts['toggle']) {
                $toggle = ' <span class="toc-toggle">[<a class="internal" href="javascript:void(0);">' . ( $this->atts['close'] ? $this->atts['opentext'] : $this->atts['closetext'] ) . '</a>]</span>';
            }

            $html .= '<div' . ( $this->atts['id'] != '' ? ' id="' . $this->atts['id'] . '"' : '' ) . ' class="' . $this->atts['class'] . '">';
            $html .= '<p class="toc-title">' . $this->atts['title'] . $toggle . '</p>';
            $html .= $toc_list;
            $html .= '</div>' . "\n";
        }

        return $html;
    }

    public function add_script()
    {
        if (! $this->add_script) {
            return false;
        }

        $var = wp_json_encode(array(
            'open_text' => isset($this->atts['opentext']) ? $this->atts['opentext'] : '開く',
            'close_text' => isset($this->atts['closetext']) ? $this->atts['closetext'] : '閉じる',
            'scroll' => isset($this->atts['scroll']) ? $this->atts['scroll'] : 'smooth',
        ));

        ?>
<script<?php echo current_theme_supports('html5', 'script') ? '' : " type='text/javascript'"; ?>>
var xo_toc = <?php echo $var; ?>;
let xoToc = () => {
  /**
   * スムーズスクロール関数
   */
  let smoothScroll = (target, offset) => {
    const targetRect = target.getBoundingClientRect();
    const targetY = targetRect.top + window.pageYOffset - offset;
    window.scrollTo({left: 0, top: targetY, behavior: xo_toc['scroll']});
  };

  /**
   * アンカータグにイベントを登録
   */
  const wpadminbar = document.getElementById('wpadminbar');
  const smoothOffset = (wpadminbar ? wpadminbar.clientHeight : 0) + 2;
  const links = document.querySelectorAll('.toc-list a[href^="#"]');
  for (let i = 0; i < links.length; i++) {
    links[i].addEventListener('click', function (e) {
      const href = e.currentTarget.getAttribute('href');
      const splitHref = href.split('#');
      const targetID = splitHref[1];
      const target = document.getElementById(targetID);

      if (target) {
        e.preventDefault();
        smoothScroll(target, smoothOffset);
      } else {
        return true;
      }
      return false;
    });
  }

  /**
   * 目次項目の開閉
   */
  const tocs = document.getElementsByClassName('toc');
  for (let i = 0; i < tocs.length; i++) {
    const toggle = tocs[i].getElementsByClassName('toc-toggle')[0].getElementsByTagName('a')[0];
    toggle.addEventListener('click', function (e) {
      const target = e.currentTarget;
      const tocList = tocs[i].getElementsByClassName('toc-list')[0];
      if (tocList.hidden) {
        target.innerText = xo_toc['close_text'];
      } else {
        target.innerText = xo_toc['open_text'];
      }
      tocList.hidden = !tocList.hidden;
    });
  }
};
xoToc();
</script><?php
    }
}

$toc = new Toc_Shortcode();

// Function to change sender name
function oc_custom_sender_name($original_email_from)
{
    $email_from_name = get_field('email_from_name', 'option');
    if ($email_from_name) {
        return $email_from_name;
    }
}

// Hooking up our functions to WordPress filters
add_filter('wp_mail_from', 'oc_custom_sender_email');
add_filter('wp_mail_from_name', 'oc_custom_sender_name');


// Register our tweaked Category Archives widget
function myprefix_widgets_init()
{
    register_widget('WP_Widget_Categories_custom');
}
add_action('widgets_init', 'myprefix_widgets_init');

/**
 * Duplicated and tweaked WP core Categories widget class
 */
class WP_Widget_Categories_Custom extends WP_Widget
{

    function __construct()
    {
        $widget_ops = array( 'classname' => 'widget_categories widget_categories_custom', 'description' => __("A list of categories, with slightly tweaked output.", 'mytextdomain') );
        parent::__construct('categories_custom', __('Categories Custom', 'mytextdomain'), $widget_ops);
    }

    function widget($args, $instance)
    {
        extract($args);

        $title = apply_filters('widget_title', empty($instance['title']) ? __('Categories Custom', 'mytextdomain') : $instance['title'], $instance, $this->id_base);

        echo $before_widget;
        $before_title = '<h3 class="cate-title-sidebar">';
        $after_title = '</h3>';
        if ($title) {
            echo $before_title . $title . $after_title;
        }
        ?>
        <ul class="cate-sidebar">
            <?php
            // Get the category list, and tweak the output of the markup.
            $pattern = '#<li([^>]*)><a([^>]*)>(.*?)<\/a>\s*\(([0-9]*)\)\s*<\/li>#i';  // removed ( and )

            // $replacement = '<li$1><a$2>$3</a><span class="cat-count">$4</span>'; // no link on span
            // $replacement = '<li$1><a$2>$3</a><span class="cat-count"><a$2>$4</a></span>'; // wrap link in span
            $replacement = '<li$1><a$2><span class="cat-name">$3</span> <span class="cat-count">$4記事</span></a>'; // give cat name and count a span, wrap it all in a link


            $args = array(
                'orderby'       => 'name',
                'order'         => 'ASC',
                'show_count'    => 1,
                'title_li'      => '',
                'echo'          => 0,
                'depth'         => 1,
            );

            $subject      = wp_list_categories($args);
            echo preg_replace($pattern, $replacement, $subject);
            ?>
        </ul>
        <?php
        echo $after_widget;
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['count'] = 1;
        $instance['hierarchical'] = 0;
        $instance['dropdown'] = 0;

        return $instance;
    }

    function form($instance)
    {
        //Defaults
        $instance = wp_parse_args((array) $instance, array( 'title' => ''));
        $title = esc_attr($instance['title']);
        $count = true;
        $hierarchical = false;
        $dropdown = false;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title', 'mytextdomain'); ?>"><?php _e('Title:', 'mytextdomain'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" <?php checked($count); ?> disabled="disabled" />
        <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Show post counts', 'mytextdomain'); ?></label>
        <br />
        <?php
    }
}

if (! function_exists('oc_excerpt')) {
    function oc_excerpt($post_id, $excerpt_length = 0)
    {
        if (empty($excerpt_length)) {
            // $excerpt_length = get_field('excerpt_length', 'option');
            $excerpt_length = 30;
        }
        $content = get_post_field('post_content', $post_id);
        $content = strip_tags($content);
        $excerpt = mb_substr($content, 0, $excerpt_length);
        if (mb_strlen($content) > $excerpt_length) {
            // $excerpt_suffix = get_field('excerpt_suffix', 'option');
            $excerpt_suffix = '...';
            $excerpt .= $excerpt_suffix;
        }
        return $excerpt;
    }
}
// Set rivision count as 5
function set_revision_store_number($num)
{
    return 5;
}
  add_filter('wp_revisions_to_keep', 'set_revision_store_number');
  
// 「何分で読めます」を表示する
function countdown( $content ){
    // show_readtimeがfalseの場合は表示しない
    if ( ! get_field('show_readtime', $post->ID) ) {
        return $content;
    }
    $count = round(mb_strlen(strip_tags($content)) / 600) + 1;
    $header = "<p class='countdown'>この記事は約 <strong>{$count}</strong> 分で読めます。</p>";
    return $header.$content;
}
add_action('the_content', 'countdown'); 
?>
