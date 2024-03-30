<?php
namespace OC_QA {

    if (! function_exists('OC_QA\oc_question_and_answer')) {

        /**
         * Control post content append of Q&A.
         *   TRUE  - If post has Q&A taxonomies, append the Q&A in those taxonomies...
         *   FALSE - Do not append Q&A, even if post has Q&A taxonomies.
         *
         * NOTE Sometimes the post page does not accommodate placing the Q&As directly
         *      after the post content. For example, maybe there are post details we want
         *      to code after the content and before the Q&As. Or maybe we want the Q&As
         *      to come after pagination. In this case, call the oc_question_and_answer()
         *      function with the 'post' argument to get the Q&As related to that specific
         *      post.
         */
        $add_after_content = true;

        /**
         * Post Type: Q&As.
         */
        function cptui_register_my_cpts_oc_cpt_qa()
        {
            $labels = [
            "name" => __("Q&As", "OCTheme"),
            "singular_name" => __("Q&A", "OCTheme"),
            ];

            $args = [
            "label" => __("Q&As", "OCTheme"),
            "labels" => $labels,
            "description" => "",
            "public" => true,
            "publicly_queryable" => true,
            "show_ui" => true,
            "show_in_rest" => true,
            "rest_base" => "",
            "rest_controller_class" => "WP_REST_Posts_Controller",
            "rest_namespace" => "wp/v2",
            "has_archive" => false,
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "delete_with_user" => false,
            "exclude_from_search" => false,
            "capability_type" => "post",
            "map_meta_cap" => true,
            "hierarchical" => false,
            "can_export" => false,
            "rewrite" => [ "slug" => "oc_cpt_qa", "with_front" => true ],
            "query_var" => true,
            "menu_icon" => "dashicons-editor-help",
            "supports" => [ "title" ],
            "show_in_graphql" => false,
            ];

            register_post_type("oc_cpt_qa", $args);
        }
        add_action('init', 'OC_QA\cptui_register_my_cpts_oc_cpt_qa');

        /**
         * Taxonomy: Q&Aカテゴリ
         */
        function cptui_register_my_taxes_oc_ctx_qa()
        {
            $labels = [
            "name" => __("Q&Aカテゴリ", "OCTheme"),
            "singular_name" => __("Q&Aカテゴリ", "OCTheme"),
            ];

            $args = [
            "label" => __("Q&Aカテゴリ", "OCTheme"),
            "labels" => $labels,
            "public" => true,
            "publicly_queryable" => true,
            "hierarchical" => true,
            "show_ui" => true,
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "query_var" => true,
            "rewrite" => [ 'slug' => 'oc_ctx_qa', 'with_front' => true, ],
            "show_admin_column" => false,
            "show_in_rest" => true,
            "show_tagcloud" => false,
            "rest_base" => "oc_ctx_qa",
            "rest_controller_class" => "WP_REST_Terms_Controller",
            "rest_namespace" => "wp/v2",
            "show_in_quick_edit" => false,
            "sort" => false,
            "show_in_graphql" => false,
            ];
            register_taxonomy("oc_ctx_qa", [ "oc_cpt_qa", "post" ], $args);
        }
        add_action('init', 'OC_QA\cptui_register_my_taxes_oc_ctx_qa');

        /**
         * Custom Fields for PostType: Q&As.
         */
        acf_add_local_field_group(array(
        'key' => 'group_62e26a7431777',
        'title' => 'Q&A Fields',
        'fields' => array(
            array(
                'key' => 'field_62e26a87f0d18',
                'label' => 'Question',
                'name' => 'question',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_62e26a8ff0d19',
                'label' => 'Answer',
                'name' => 'answer',
                'type' => 'wysiwyg',
                'instructions' => '利用が可能なタグはこちらです。h1 ー h6, br, ol, ul, li, a, p, div, b, strong, i, em.',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'maxlength' => '',
                'rows' => 4,
                'new_lines' => 'br',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'oc_cpt_qa',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'acf_after_title',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
        'show_in_rest' => 0,
        ));

        /**
         * This function returns the HTML to produce a Q&A element to be used stand-alone,
         * or as the result of the shortcode procided for it, as well as the JSON for an
         * for an ld+json schema element on your page.
         *
         * @param $attrs (Array)   -  Contains the parameters to control the ouput:
         *
         *    ['id']    (mixed)    -  Display the specific Q&A post identified by 'id'.
         *                         -     Use (integer) for single id, use array[(integer)] for multiple ids.
         *    ['cat']   (mixed)    -  Display all Q&As in the category identified by the category slug 'cat'.
         *                         -     Use (string) for single category, use array[(string)] for multiple categories.
         *    ['num']   (integer)  -  If greater than zero, limit the nubmber of Q&A's displayed to 'num'.
         *    ['wrap']  (string)   -  If the value is "wrap", the wrap all of the Q&As in a div, as well as
         *                         -     display the "+/-" control to expand and shrink the answers.
         *    ['title'] (string)   -  If not empty, will display string as the title of the Q&As
         *    ['post']  (integer)  -  If not empty, will display Q&As for the taxonomies attached to
         *                         -     the post with the ID matching this value.
         *
         * There are four typical uses of this function:
         *   (1) You do not provide ['id'], ['cat'] or ['post'] and all Q&As are displayed.
         *   (2) You provide ['id'] to select the specific Q&A(s) you want to display.
         *   (3) You provide ['cat'] to select the Q&As with the provided taxonomy slug.
         *   (4) You provide ['post'] to select Q&As attached to the post by Q&A taxonomies.
         *
         * @return (array) -
         *    [0] (string) - The HTML to display the Q&As.
         *    [1] (string) - The <script type="ld+json"> for the schema
         *
         *   For the special case where you want to include the Q&As for a specific post,
         *   (for example on the post page single.php), because you do not want it appended to the
         *   post content (because you want it somehere else on the page), use this:
         *
         *     <?php
         *        $qa = OC_QA\oc_question_and_answer( array('post' => $post->ID) );
         *        echo $qa[0];
         *        echo $qa[1];
         *     ?>
         *
         */
        function oc_question_and_answer($attrs = array())
        {
            global $post;

            $qa_num = 0;
            $qa_cat = null;
            $qa_ids = null;
            $qa_wrap = true;
            $qa_title = '';

            // NOTE When the user is specifying a specific post, then they want
            //      ONLY the Q&As attached to that post by taxonomy. If we allow
            //      the code to fall through to the code below, then they will
            //      (in most cases) get ALL of the Q&A's defined. Thus, we use
            //      two special cases of returning empty content to avoid this.
            //
            if (isset($attrs['post']) && ! empty($attrs['post'])) {
                $qa_post = get_post($attrs['post']);
                if (! empty($qa_post)) {
                    $terms = get_the_terms($qa_post->ID, 'oc_ctx_qa');
                    if (is_array($terms) && count($terms) > 0) {
                        $qa_cat = array();
                        foreach ($terms as $term) {
                            $qa_cat[] = $term->slug;
                        }
                    } else {
                        return array( '', '' );
                    }
                } else {
                    return array( '', '' );
                }
            } elseif (isset($attrs['id'])) {
                if (is_array($attrs['id'])) {
                    $qa_ids = $attrs['id'];
                } else {
                    $qa_ids = array( $attrs['id'] );
                }
            } elseif (isset($attrs['cat']) && ! empty($attrs['cat'])) {
                $qa_cat = $attrs['cat'];
            }

            if (isset($attrs['num']) && ! empty($attrs['num'])) {
                $qa_num = sanitize_text_field($attrs['num']);
            }
            if (isset($attrs['title'])) {
                $qa_title = sanitize_text_field($attrs['title']);
            }
            if (isset($attrs['wrap']) && ! empty($attrs['wrap'])) {
                if ($attrs['wrap'] == 'nowrap') {
                    $qa_wrap = false;
                }
            }

            $args = array(
            'post_type'    => 'oc_cpt_qa',
            'post_status'  => 'publish',
            );

            if ($qa_num > 0) {
                $args['posts_per_page'] = $qa_num;
            } else {
                $args['nopaging'] = true;
            }

            if (! is_null($qa_ids)) {
                // Using the QA ID overrides...
                $args['post__in'] = $qa_ids;
            } elseif (! is_null($qa_cat)) {
                // If we have category(s) spec, show all QA's in category(s)
                $args['tax_query'] = array(
                array(
                    'taxonomy' => 'oc_ctx_qa',
                    'field'    => 'slug',
                    'terms'    => $qa_cat,
                )
                );
            } else {
                // If there is no ID or category, then all QA's
            }

            $qa_result = '';
            $qa_items = array();
            $qa_posts = new \WP_Query($args);
            $num_posts = $qa_posts->found_posts;

            if ($qa_posts->have_posts()) {
                while ($qa_posts->have_posts()) {
                    $qa_posts->the_post();
                    $post_id = $post->ID;
                    $qa_items[] = array(
                    'question' => get_field('question', $post_id),
                    'answer'   => get_field('answer', $post_id),
                    );
                }
            }

            wp_reset_postdata();

            if (count($qa_items) > 0) {
                if ($qa_wrap) {
                    $qa_result .= '<div class="questions-answers';
                    if (isset($attrs['wrap-class']) && ! empty($attrs['wrap-class'])) {
                        $qa_result .= ' ' . $attrs['wrap-class'];
                    }
                    $qa_result .= '">';
                }

                if (! empty($qa_title)) {
                    $qa_result .= '<p class="questions-answers-title">' . $qa_title . '</p>';
                }

                $first = true;
                foreach ($qa_items as $qa_item) {
                    $open_class = $first ? " open" : "";
                    $qa_result .= '<div class="question-answer' . $open_class . '">';
                    $qa_result .= '<div class="qa-wrap">';
                    $qa_result .= '<div class="question">' . $qa_item['question'] . '</div>';
                    $qa_result .= '<div class="answer">' . $qa_item['answer'] . '</div>';
                    $qa_result .= '</div>';
                    $qa_result .= '<span class="control"></span>';
                    $qa_result .= '</div>';
                    $first = false;
                }

                if ($qa_wrap) {
                    $qa_result .= '</div>';
                }

                /* Output scheme script */
                $schema_result  = '';
                $schema_result .= '<script type="application/ld+json">';
                $schema_result .= '{';
                $schema_result .= '"@context": "https://schema.org",';
                $schema_result .= '"@type": "FAQPage",';
                $schema_result .= '"mainEntity": [';
                $first = true;
                foreach ($qa_items as $qa_item) {
                    if (! $first) {
                        $schema_result .= ',';
                    }
                    $schema_result .= '{';
                    $schema_result .=    '"@type": "Question",';
                    $schema_result .=    '"name": "' . str_replace('"', '\\"', $qa_item['question']) . '",';
                    $schema_result .=    '"acceptedAnswer": {';
                    $schema_result .=       '"@type": "Answer",';
                    $schema_result .=       '"text": "' . str_replace('"', '\\"', $qa_item['answer']) . '"';
                    $schema_result .=    '}';
                    $schema_result .= '}';
                    $first = false;
                }
                $schema_result .= ",";
                $first = true;
                foreach ($qa_items as $qa_item) {
                    if (! $first) {
                        $schema_result .= ',';
                    }
                    $schema_result .= '{';
                    $schema_result .=    '"@type": "Question",';
                    $schema_result .=    '"name": "' . str_replace('"', '\\"', $qa_item['question']) . '",';
                    $schema_result .=    '"acceptedAnswer": {';
                    $schema_result .=       '"@type": "Answer",';
                    $schema_result .=       '"text": "' . str_replace('"', '\\"', $qa_item['answer']) . '"';
                    $schema_result .=    '}';
                    $schema_result .= '}';
                    $first = false;
                }
                $schema_result .= ']';
                $schema_result .= '}';
                $schema_result .= '</script>';
            }

            return array( $qa_result, $schema_result );
        }

        /**
         * This is a WordPress shortcode to inject Q&A output into the page. It is used like so:
         *
         *    [question_and_answer id=x cat="slug" num=n wrap=]
         *
         * @param $attrs (Array) contains the parameters to control the ouput.
         *    ['id'] (integer) Display the specific Q&A post identified by 'id'.
         *    ['cat'] (String) Display all Q&As in the category identified by the category slug 'cat'.
         *        If 'cat' includes commas, string will be exploded for multiple categories.
         *    ['num'] (integer) If greater than zero, limit the nubmber of Q&A's displayed to 'num'.
         *    ['title'] (string) If not empty, will display string as the title of the Q&As
         *    ['wrap'] (string) If the value is "wrap", the wrap all of the Q&As in a div, as well
         *        as display the "+/-" control to expand and shrink the answers. If the value is
         *        "nowrap", the the selected Q&As will be put out as individual "question-answer"
         *        divs so you can wrap then youself.
         *
         * @return (string) The HTML to display the Q&As.
         *
         */
        function oc_question_and_answer_shortcode($attrs = array())
        {
            // Accommodate multiple IDs as "id1,id2,id3"
            if (isset($attrs['id'])) {
                if (is_string($attrs['id']) && strpos($attrs['id'], ',') !== false) {
                    $ids = explode(',', $attrs['id']);
                    if (is_array($ids) && count($ids) > 0) {
                        $attrs['id'] = $ids;
                    }
                }
            }

            // Accommodate multiple categories as "cat1,cat2,cat3"
            if (isset($attrs['cat'])) {
                if (is_string($attrs['cat']) && strpos($attrs['cat'], ',') !== false) {
                    $cats = explode(',', $attrs['cat']);
                    if (is_array($cats) && count($cats) > 0) {
                        $attrs['cat'] = $cats;
                    }
                }
            }

            $attrs['wrap-class'] = 'oc-question-and-answer-shortcode';

            $qa = oc_question_and_answer($attrs);

            return $qa[0] . $qa[1];
        }
        add_shortcode('OC_QA', 'OC_QA\oc_question_and_answer_shortcode');

        if ($add_after_content) {
            function oc_qa_content_after($content)
            {
                global $post;
                if (! empty($post)) {
                    $attrs = array('post' => $post->ID);
                    $attrs['title'] = 'よくある質問';
                    $attrs['wrap-class'] = 'oc-qa-content-after';
                    $qa = oc_question_and_answer($attrs);
                    $result = $content . $qa[0] . $qa[1];
                }
                return $result;
            }
            add_filter('the_content', 'OC_QA\oc_qa_content_after', 99, 1);
        }

        /* The styles requred on Admin pages injected using wp_add_inline_style() */
        $qa_admin_style = <<<QAADMINSTYLE
	th.column-shortcode {
		width: 220px
	}
	td.shortcode.column-shortcode {
	}
QAADMINSTYLE;

        /* The styles requred on front pages injected using wp_add_inline_style() */
        $q_color = "#959FB1";
        $border_color = "#9a8c6c";
        $a_color = "#f19086";
        $font_color = "#555";
        $title_color = "#233B68";
        $qa_style = <<<QASTYLE
	.questions-answers {
		width: 100%;
	}
	.questions-answers .questions-answers-title {
		width: 100%;
		color: $title_color;
		font-size: 25px;
		font-weight: bold;
		text-align: center;
		margin-bottom: 1em;
		font-family: "Noto Sans JP", "Sans-Serif";
	}
	.questions-answers .question-answer {
		width: 100%;
		display: flex;
		position: relative;
		border-bottom: 1px solid $border_color;
	}
	.questions-answers .question-answer .qa-wrap {
		width: 100%;
		position: relative;
	}
	.questions-answers .question-answer .control {
		color: $q_color;
		font-size: 40px;
		font-weight: 500;
		line-height: 60px;
		cursor: pointer;
		font-family: "Futura", "Noto Sans JP", "Sans-Serif";
	}
	.questions-answers .question-answer .control:after {
		content: "+";
	}
	.questions-answers .question-answer.open .control:after {
		content: "\\2013";
	}
	.questions-answers .question-answer .question {
		display: flex;
		align-items: center;
		color: $font_color;
		font-size: 15px;
		font-weight: normal;
		margin: 0;
		font-family: "Noto Sans JP", "Sans-Serif";
	}
	.questions-answers .question-answer .question:before {
		content: "Q";
		width: 1em;
		color: $q_color;
		font-size: 40px;
		font-weight: 500;
		line-height: 60px;
		text-align: center;
		margin-right: 0.5em;
		font-family: "Futura", "Noto Sans JP", "Sans-Serif";
	}
	.questions-answers .question-answer .answer {
		
		align-items: center;
		max-height: 0;
		color: $font_color;
		font-size: 15px;
		font-weight: normal;
		line-height: 2em;
		margin: 0;
		overflow: hidden;
		transition: max-height 0.3s;
		font-family: "Noto Sans JP", "Sans-Serif";
	}
	.questions-answers .question-answer.open .answer {
		max-height: unset;
	}
	.questions-answers .question-answer .answer *:first-child:before {
		content: "A";
		width: 1em;
		color: $a_color;
		font-size: 40px;
		font-weight: 500;
		line-height: 60px;
		text-align: center;
		margin-right: 0.5em;
		font-family: "Futura", "Noto Sans JP", "Sans-Serif";
	}
QASTYLE;

        /* The javascript requred on front pages injected using wp_add_inline_style() */
        $qa_script = <<<QASCRIPT
	jQuery(document).ready( function($) {
		$('.question-answer').on('click', function(ev) {
			var qaControl = $(this);
			var qa = qaControl.closest('.question-answer');
			if ( ! qa.hasClass('open') ) {
				qa.siblings().each( function() { $(this).removeClass('open'); } );
			}
			qa.toggleClass('open');
		});
	});
QASCRIPT;

        if (is_admin()) {
            //
            // ADD SHORTCODE COLUMN TO QANDA POSTS PAGE
            //   o Add shortcode hint with post ID before date column
            //
            function oc_qanda_columns($columns)
            {
                $new_columns = array();
                foreach ($columns as $key => $title) {
                    if ($key == 'date') {
                        $new_columns['shortcode'] = 'ショートコード';
                    }
                    $new_columns[$key] = $title;
                }
                return $new_columns;
            }
            add_filter("manage_oc_cpt_qa_posts_columns", 'OC_QA\oc_qanda_columns');

            function oc_ocp_qa_custom_columns($column, $post_id)
            {
                $template_uri = get_template_directory_uri();

                switch ($column) {
                    case 'shortcode':
                        echo '[OC_QA id=' . $post_id . ']';
                        break;
                    default:
                        break;
                }
            }
            add_filter("manage_oc_cpt_qa_posts_custom_column", 'OC_QA\oc_ocp_qa_custom_columns', 10, 3);

            // Load up the Admin pages inline styles.
            function oc_qanda_enqueue_admin()
            {
                global $qa_admin_style;
                wp_register_style('qanda-admin-css', false);
                wp_enqueue_style('qanda-admin-css');
                wp_add_inline_style('qanda-admin-css', $qa_admin_style);
            }
            add_action('admin_enqueue_scripts', 'OC_QA\oc_qanda_enqueue_admin');
        } else {
            function oc_qanda_enqueue_front()
            {
                global $qa_style, $qa_script;

                // Load up the front pages inline styles.
                wp_register_style('qanda-css', false);
                wp_enqueue_style('qanda-css');
                wp_add_inline_style('qanda-css', $qa_style);

                // Load up the front pages inline javascripts.
                wp_register_script('qanda-js', '', array('jquery'), 'v1', true);
                wp_enqueue_script('qanda-js');
                wp_add_inline_script('qanda-js', $qa_script);
            }
            add_action('wp_enqueue_scripts', 'OC_QA\oc_qanda_enqueue_front');
        }
    }

}
