<?php

if (! function_exists('oc_sanetize_textarea_input')) {
    function oc_sanetize_textarea_input($value)
    {
        $lines = mb_split("/\r\n|\n|\r/", $value);
        $newlines = array();
        foreach ($lines as $line) {
            if (mb_strlen($line) < 128) {
                $newlines[] = $line;
            } else {
                while (mb_strlen($line) > 128) {
                    $sub = mb_substr($line, 0, 128);
                    $newlines[] = $sub;
                    $line = mb_substr($line, 128);
                }
                if (mb_strlen($line) > 0) {
                    $newlines[] = $line;
                }
            }
        }
        return nl2br(implode("\r\n", $newlines));
    }
}

// This will send Blog Date Archive requests for dates that have no articles
// to continue to go to our date.php template, as opposed to the 404 page.
// Note that the browser still receives a 404 status code!
//
if (! function_exists('oc_blog_date_404_template')) {
    function oc_blog_date_404_template($template = '')
    {
        global $wp_query;
        if (isset($wp_query->query['year']) || isset($wp_query->query['monthnum']) || isset($wp_query->query['day'])) {
            $template = locate_template('date.php', false);
        } elseif (isset($wp_query->query['tag'])) {
            $template = locate_template('tag.php', false);
        } elseif (isset($wp_query->query['category_name'])) {
            $template = locate_template('category.php', false);
        }
        return $template;
    }
    add_filter('404_template', 'oc_blog_date_404_template');
}
