<?php

/**
 *
 * The "Configuration" page in the WP Admin area is registered with this call.
 * If you do not register here, then there will be no menu item to edit the
 * configuration, and the Configuration Fields will not have a page to attach.
 *
 */
if (function_exists('acf_add_options_page')) {
    /* ACF Based Configuration page */
    acf_add_options_page(array(
        'page_title'    => __('Configuration', 'OCTheme'),
        'menu_title'    => __('Configuration', 'OCTheme'),
        'menu_slug'     => 'oc-configuration',
        'capability'    => 'edit_posts',
        'redirect'      => false,
        'position'      => 22,
        'icon_url'      => 'dashicons-admin-settings',
        ));
}

/**
 *
 * Add your calls to acf_add_local_field_group() that you get from
 * ACF Pro's tools menu. You select the field groups, they click on
 *the "Generate PHP" button to get the code.
 *
 */
if (function_exists('acf_add_local_field_group')) :
    acf_add_local_field_group(array(
        'key' => 'group_5c444f3cacc98',
        'title' => 'Configuration',
        'fields' => array(
            array(
                'key' => 'field_5c444f47498c9',
                'label' => 'Email',
                'name' => '',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'placement' => 'top',
                'endpoint' => 0,
            ),
            array(
                'key' => 'oc_config_phone',
                'label' => '電話番号',
                'name' => 'phone',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '50',
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
                'key' => 'oc_config_phone_show',
                'label' => '電話番号の表示',
                'name' => 'phone_show',
                'type' => 'true_false',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '50%',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => '表示する',
                'ui_off_text' => '表示しない',
            ),
            array(
                'key' => 'oc_config_phone_time',
                'label' => '電話受付時間',
                'name' => 'phone_time',
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
                'key' => 'field_5c444f5a498ca',
                'label' => '管理者通知メール',
                'name' => 'config_email_admin',
                'aria-label' => '',
                'type' => 'text',
                'instructions' => '複数のメールに送るには「,」(カンマ)で列挙してください。',
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
                'key' => 'oc_config_email_from',
                'label' => '送信元メールアドレス設定',
                'name' => 'email_from',
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
                'key' => 'oc_config_email_name',
                'label' => 'メール送信表示名',
                'name' => 'email_from_name',
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
                'key' => 'oc_config_contact_subject',
                'label' => 'Contact Subject Template',
                'name' => 'contact_subject',
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
                'key' => 'oc_config_contact_template',
                'label' => 'Contact Email Template',
                'name' => 'contact_email_template',
                'type' => 'wysiwyg',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'delay' => 0,
            ),
            array(
                'key' => 'field_5c444fa0498cd',
                'label' => 'Email Problem Message',
                'name' => 'email_problem_message',
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
                'key' => 'field_620366afeb176',
                'label' => 'Email Required Message',
                'name' => 'email_required_message',
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
                'key' => 'field_5c444fb4498ce',
                'label' => 'Email reCaptcha Message',
                'name' => 'email_recaptcha_message',
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
                'key' => 'field_5c444fd5498cf',
                'label' => 'Email Success Message',
                'name' => 'email_success_message',
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
                'key' => 'field_620358fceb172',
                'label' => 'Autoreply',
                'name' => '',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'placement' => 'top',
                'endpoint' => 0,
            ),
            array(
                'key' => 'field_620365f2eb174',
                'label' => 'Autoreply Subject',
                'name' => 'autoreply_subject',
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
                'key' => 'field_62036607eb175',
                'label' => 'Autoreply Content',
                'name' => 'autoreply_content',
                'type' => 'wysiwyg',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'delay' => 0,
            ),
            array(
                'key' => 'field_5c444fe9498d0',
                'label' => 'Google reCAPTCHA',
                'name' => '',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'placement' => 'top',
                'endpoint' => 0,
            ),
            array(
                'key' => 'field_5c444ff8498d1',
                'label' => 'Site Key',
                'name' => 'google_recaptcha_sitekey',
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
                'key' => 'field_5c445011498d2',
                'label' => 'Secret Key',
                'name' => 'google_recaptcha_secret',
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
                'key' => 'field_632a84e1aca50',
                'label' => 'サイト表示',
                'name' => '',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'placement' => 'top',
                'endpoint' => 0,
            ),
            array(
                'key' => 'field_632a92c5aca51',
                'label' => 'アーカイブデフォルト画像',
                'name' => 'archive_image',
                'type' => 'image',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
                'min_width' => '',
                'min_height' => '',
                'min_size' => '',
                'max_width' => '',
                'max_height' => '',
                'max_size' => '',
                'mime_types' => '',
            ),
            array(
                'key' => 'oc_use_image_for_header_cover',
                'label' => '共通ヘッダーに背景画像を使用する',
                'name' => 'use_image_for_header_cover',
                'type' => 'true_false',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 1,
                'ui' => 1,
                'ui_on_text' => '',
                'ui_off_text' => '',
            ),
            array(
                'key' => 'oc_header_cover_image',
                'label' => '共通ヘッダーに設定する背景画像',
                'name' => 'header_cover_image',
                'type' => 'image',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'oc_use_image_for_header_cover',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
                'min_width' => '',
                'min_height' => '',
                'min_size' => '',
                'max_width' => '',
                'max_height' => '',
                'max_size' => '',
                'mime_types' => '',
            ),
            array(
                'key' => 'oc_header_cover_color',
                'label' => '共通ヘッダーに設定する背景色',
                'name' => 'header_cover_color',
                'type' => 'color_picker',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'oc_use_image_for_header_cover',
                            'operator' => '!=',
                            'value' => '1',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'enable_opacity' => 1,
                'return_format' => 'string',
            ),
            array(
                'key' => 'field_5c96181915907',
                'label' => 'Global',
                'name' => '',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'placement' => 'top',
                'endpoint' => 0,
            ),
            array(
                'key' => 'field_5c9619aee978b',
                'label' => 'Page Title Prefix',
                'name' => 'page_title_prefix',
                'type' => 'text',
                'instructions' => 'This will be the prefix for all page titles.',
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
                'key' => 'field_5ffee83a5464a',
                'label' => 'Developer',
                'name' => '',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'placement' => 'top',
                'endpoint' => 0,
            ),
            array(
                'key' => 'field_5ffee85a5464b',
                'label' => '注意 !',
                'name' => '',
                'type' => 'message',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => 'acf-caution-message',
                    'id' => '',
                ),
                'message' => 'これらの設定は、開発者のみが変更する必要があります。',
                'new_lines' => 'wpautop',
                'esc_html' => 0,
            ),
            array(
                'key' => 'field_5c96182e15908',
                'label' => 'Production',
                'name' => 'is_production',
                'type' => 'true_false',
                'instructions' => 'Controls enqueued CSS and JS scripts, and admin controls.',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Use Production Settings',
                'ui_off_text' => 'Use Development Settings',
            ),
            array(
                'key' => 'oc_config_is_front',
                'label' => 'トップページのみ公開',
                'name' => 'only_front',
                'type' => 'true_false',
                'instructions' => 'トップページだけ公開の場合だけの設定',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'トップページのみ公開',
                'ui_off_text' => '全ページ公開',
            ),
            array(
                'key' => 'field_5c96779b8b244',
                'label' => 'Include IE Shiv',
                'name' => 'ie_include_shiv',
                'type' => 'true_false',
                'instructions' => 'Controls whether or not to include the standard IE "shiv" scripts',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Include IE Shiv',
                'ui_off_text' => 'Do Not Include IE Shiv',
            ),
            array(
                'key' => 'field_5ffee4bd71033',
                'label' => 'Asset Version',
                'name' => 'asset_version',
                'type' => 'text',
                'instructions' => 'This is appended to the OC javascript and css file links to control cache busting. If you set this to a new value, it will bust the current browser cached files. If this is left empty, it will be set to the timestamp of the most recent css or javascript file.',
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
                'key' => 'field_60ac07d2ef30e',
                'label' => 'Fontawesome URL',
                'name' => 'fontawesome_url',
                'type' => 'text',
                'instructions' => 'This is the URL that will be used to build the FontAwesome link in the head of the page. Leave this empty if you do not want Fontawesome to be loaded.',
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
                'key' => 'field_60ac0810ef30f',
                'label' => 'Fontawesome Integrity Checksum',
                'name' => 'fontawesome_sum',
                'type' => 'text',
                'instructions' => 'This is the integrity checksum that will be included in the Fontawesome link. Leave this empty if you do not want the integrity attribute to be set in the link.',
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
                'key' => 'field_60ac087def310',
                'label' => 'Fontawesome Cross Origin',
                'name' => 'fontawesome_crs',
                'type' => 'text',
                'instructions' => 'This is the crossorigin value that will be included in the Fontawesome link. Leave this empty if you do not want the crossorigin attribute to be set in the link.',
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
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'oc-configuration',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
        'show_in_rest' => false,
    ));
endif;
