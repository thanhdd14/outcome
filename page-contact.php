<?php
    /* Template Name: Contact */
    $template_uri = get_template_directory_uri();

    // Refer to comment in functions.php regarding the reason we now perform a
    // 'read_and_close' operation here to get the 'form' session variable.
    session_start(['read_and_close' => true]);
    $form_fields = isset($_SESSION['form']) ? $_SESSION['form'] : array();

    $form_result = null;
    $form_msg = '不明なエラーが発生しました。';
    if ( isset($form_fields['result']) ) {
        $form_result = $form_fields['result'];
        if ( isset($form_fields['msg']) && ! empty($form_fields['msg']) ) {
            $form_msg = $form_fields['msg'];
        }
        unset($form_fields['msg']);
        unset($form_fields['result']);
        oc_session_write('form', array());
    }

    $header_cover_color = get_field('header_cover_color', 'option');
    $use_image_for_header_cover = get_field('use_image_for_header_cover', 'option');
    $header_cover_image = get_field('header_cover_image', 'option');
    if ( $use_image_for_header_cover && isset($header_cover_image) ) {
        $header_cover_image_url = $header_cover_image["url"];
    }

    $bg_style = '';
    if ( $use_image_for_header_cover ) {
        $bg_style  = 'style="background-image: url(' . "'" . $header_cover_image_url . "'" . '); ';
        $bg_style .= 'background-position: center; background-repeat: no-repeat; background-size: cover;"';
    } else {
        $bg_style = 'style="background-color: ' . $header_cover_color . ';"';
    }

    get_header();
?>
<script src='https://www.google.com/recaptcha/api.js'></script>

<section class="text-bg-top img-cover-white" <?php echo $bg_style; ?>>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="caption">
                    <h1 class="text-jp">お問い合わせ</h1>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
if (get_field('phone_show', 'option')) {
?>
<section id="contact_top" class="contact-top wow animate__fadeIn" data-wow-duration="1.2s">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="contact-top-box">
                    <p class="top-line">お問い合わせはお電話または下記フォームよりお願いいたします。</p>
                    <div class="phone-number">
                        <span class="icon fas fa-phone"></span>
                        <span class="number"><a href="tel:<?php echo get_field('phone', 'option');?>"
                            ><?php echo get_field('phone', 'option');?></a></span>
                    </div>
<?php
                    if ( get_field('phone_time', 'option') ) {
                        echo '<p class="btm-line">' . get_field('phone_time', 'option') . '</p>';
                    }
?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
}
?>

<section id="contact_form" class="contact-form-container wow animate__fadeIn" data-wow-duration="1.2s">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
<?php
            if (! is_null($form_result)) {
                echo '<div class="contact-result ' . ($form_result === false ? 'failed' : 'success') . '">';
                echo   '<p>' . $form_msg . '</p>';
                echo '</div>';
            }
            // NOTE
            // All fields beginning with "fld_ta_" are expected to be <textarea> inputs, and their
            // content will be processed with the special oc_sanetize_textarea_input() before being
            // replaced into to the email template. It is important to use this mechanism due to the
            // problems of UTF-8 data that is longer than the allowable line length in "quoted-printable"
            // emails, which are generated in WP mail. https://en.wikipedia.org/wiki/Quoted-printable
?>
                <form class="contact-form" id="contact" method="POST"
                    action="<?php echo $template_uri; ?>/php/process-contact.php"
                >
                    <div class="field-group">
                        <div class="label-wrap">
                            <label for="fld_name" class="group-label">お名前</label>
                            <span class="req">必須</span>
                        </div>
                        <div class="input-wrap">
                            <input type="text" id="fld_name" name="fld_name" class="text-input" required
                                value="<?php echo ( isset($form_fields['fld_name']) ?
                                    $form_fields['fld_name'] : '' ); ?>">
                        </div>
                    </div>
                    <div class="field-group">
                        <div class="label-wrap">
                            <label for="fld_phone" class="group-label">お電話番号</label>
                            <span class="req">必須</span>
                        </div>
                        <div class="input-wrap">
                            <input type="text" id="fld_phone" name="fld_phone" class="text-input" required
                                value="<?php echo ( isset($form_fields['fld_phone']) ?
                                    $form_fields['fld_phone'] : '' ); ?>">
                        </div>
                    </div>
                    <div class="field-group">
                        <div class="label-wrap">
                            <label for="fld_email" class="group-label">メールアドレス</label>
                            <span class="req">必須</span>
                        </div>
                        <div class="input-wrap">
                            <input type="text" id="fld_email" name="fld_email" class="text-input" required
                                value="<?php echo ( isset($form_fields['fld_email']) ?
                                    $form_fields['fld_email'] : '' ); ?>">
                        </div>
                    </div>
                    <div class="field-group">
                        <div class="label-wrap">
                            <label for="fld_email_cfm" class="group-label">メールアドレス確認</label>
                            <span class="req">必須</span>
                        </div>
                        <div class="input-wrap">
                            <input type="text" id="fld_email_cfm" name="fld_email_cfm" class="text-input" required>
                        </div>
                    </div>
                    <div class="field-group">
                        <div class="label-wrap">
                            <label for="fld_inquiry" class="group-label">お問い合わせ項目</label>
                            <span class="req">必須</span>
                        </div>
                        <div class="input-wrap radio">
                            <div class="contact-radio">
                                <input type="radio" id="inquiry_one" name="fld_inquiry" value="項目1"<?php
                                    echo ( isset($form_fields['fld_inquiry'])
                                        && is_array($form_fields['fld_inquiry'])
                                        && in_array('項目1', $form_fields['fld_inquiry']) )
                                        ? ' checked' : '' ?> checked>
                                <label for="inquiry_one">項目1</label>
                            </div>
                            <div class="contact-radio">
                                <input type="radio" id="inquiry_two" name="fld_inquiry" value="項目2"<?php
                                    echo ( isset($form_fields['fld_inquiry'])
                                        && is_array($form_fields['fld_inquiry'])
                                        && in_array('項目2', $form_fields['fld_inquiry']) )
                                        ? ' checked' : '' ?>>
                                <label for="inquiry_two">項目2</label>
                            </div>
                            <div class="contact-radio">
                                <input type="radio" id="inquiry_three" name="fld_inquiry" value="その他"<?php
                                    echo ( isset($form_fields['fld_inquiry'])
                                        && is_array($form_fields['fld_inquiry'])
                                        && in_array('その他', $form_fields['fld_inquiry']) )
                                        ? ' checked' : '' ?>>
                                <label for="inquiry_three">その他</label>
                            </div>
                        </div>
                    </div>
                    <div class="field-group" style="height:200px;">
                        <div class="label-wrap">
                            <label for="fld_ta_message" class="group-label">お問合せ内容</label>
                            <span class="req">必須</span>
                        </div>
                        <div class="input-wrap">
                            <textarea id="fld_ta_message" name="fld_ta_message" class="text-input" required
                                value="<?php echo ( isset($form_fields['fld_ta_message']) ? $form_fields['fld_ta_message'] : '' ); ?>"
                            ></textarea>
                        </div>
                    </div>

                    <div class="confirm-group">
                        <p class="agree-text"><a href="<?php echo home_url('privacy');?>" target=”_blank”>プライバシポリシー</a>に同意の上、送信ください。</p>
                        <div class="agree-btn">
                            <input type="checkbox" class="agree-chk" id="agree_chk" required>
                            <label for="agree_chk" class="agree-label">
                                <span class="chk-box"></span>プライバシポリシーに同意する
                            </label>
                        </div>
                    </div>
<?php
                    $google_recaptcha_sitekey = get_field('google_recaptcha_sitekey', 'option');
                    if (! empty($google_recaptcha_sitekey)) :
                        echo '<div class="g-recaptcha no-confirm" data-sitekey="' . $google_recaptcha_sitekey. '"></div>';
                    endif;
?>
                    <div class="submit-group">
                        <button id="contact_submit_btn" class="site-btn grey-txt lined" type="submit">確認画面へ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php
    get_template_part('template-parts/confirm', 'modal', array(
        'confirm-form-id'   => 'contact',
        'submit-button-id'  => 'contact_submit_btn'
    ));

    get_footer();
?>