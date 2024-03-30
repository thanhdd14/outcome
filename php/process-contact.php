<?php
define('WP_USE_THEMES', false);
require('../../../../wp-load.php');

/**
 * Refer to the comment in functions.php regarding the reason we now
 * utilize the function oc_session_write() for all operations that
 * set the 'form' variable in the $_SESSION.
 */

$redirect_path = home_url("/contact#feedback");

$email_problem_message    = get_field('email_problem_message', 'options');
$email_recaptcha_message  = get_field('email_recaptcha_message', 'options');
$email_success_message    = get_field('email_success_message', 'options');
$email_required_message   = get_field('email_required_message', 'options');

$google_recaptcha_sitekey = get_field('google_recaptcha_sitekey', 'options');
$google_recaptcha_secret  = get_field('google_recaptcha_secret', 'options');

/**
 * Save all form fields for re-display in form
 */
$form_fields = array();
foreach ($_POST as $key => $value) {
    if (strpos($key, 'fld_') === 0) {
        $form_fields[$key] = $value;
    }
}

/**
 * reCAPTCHA
 */
$use_google_recaptcha = false;
if (! empty($google_recaptcha_sitekey) && ! empty($google_recaptcha_secret)) {
    $use_google_recaptcha = true;
}
// $use_google_recaptcha = false; // UNCOMMENT TO TEST WITHOUT RECAPTCHA

if ($use_google_recaptcha &&
    ( ! isset($_POST['g-recaptcha-response']) || empty($_POST['g-recaptcha-response']) )) {
    $form_fields['result'] = false;
    $form_fields['msg']    = __($email_recaptcha_message, 'OCTheme');
    oc_session_write('form', $form_fields);
    wp_redirect($redirect_path);
    exit;
}

if ($use_google_recaptcha) {
    $rcaptcha_url =
        "https://www.google.com/recaptcha/api/siteverify" .
        "?secret=" . $google_recaptcha_secret .
        "&response=" . $_POST['g-recaptcha-response'];

    if (isset($_SERVER['REMOTE_ADDR']) && ! empty($_SERVER['REMOTE_ADDR'])) {
        $rcaptcha_url .= "&remoteip=" . $_SERVER['REMOTE_ADDR'];
    }

    $captcha_contents = file_get_contents($rcaptcha_url);
    $captcha_response = json_decode($captcha_contents, true);

    if (! isset($captcha_response["success"]) || ! $captcha_response["success"]) {
        $form_fields['result'] = false;
        $form_fields['msg']    = __($email_recaptcha_message, 'OCTheme');
        oc_session_write('form', $form_fields);
        wp_redirect($redirect_path);
        exit;
    }
}

/**
 * Process Contact Form Inputs
 */
$email_addr  = "";
$arr_find    = array();
$arr_replace = array();
array_push($arr_find, "{{site_name}}");
array_push($arr_replace, get_bloginfo( 'name' ));
foreach ($_POST as $key => $value) {
    if (strpos($key, 'fld_') === 0) {
        array_push($arr_find, "{{" . $key . "}}");
        // If the fld_name doesn't contain Japanese characters, then exit
        if ($key == "fld_name") {
            if (preg_match("/^[ぁ-んァ-ヶｱ-ﾝﾞﾟ一-龠]+[ 　]?[ぁ-んァ-ヶｱ-ﾝﾞﾟ一-龠]+$/u", $value)) {
                // 日本語文字列が含まれているのでOK
            } else {
                $form_fields['result'] = false;
                $form_fields['msg']    = __("氏名は日本語で入力ください。", 'OCTheme');
                oc_session_write('form', $form_fields);
                wp_redirect($redirect_path);
                exit;
            }
            array_push($arr_replace, sanitize_text_field($value));
        } elseif ($key == "fld_email") {
            $email_addr = sanitize_text_field($value);
            array_push($arr_replace, sanitize_text_field($value));
        } elseif (substr($key, 0, strlen('fld_ta_')) === 'fld_ta_') {
            // NOTE All <textarea> inputs MUST use THIS specific sanetizer!
            // Any field whose key starts with 'fld_ta_' will be processed
            // as a textarea input.
            array_push($arr_replace, oc_sanetize_textarea_input($value));
        } elseif (is_array($value)) {
            // When the input value is an array, it is typically an input that
            // is a series of checkboxes. We need to convert this array into a
            // string that can be substituted in the email...
            $str_value = '';
            foreach ($value as $item) {
                if (strlen($str_value) > 0) {
                    $str_value .= '、';
                }
                $str_value .= sanitize_text_field($item);
            }
            array_push($arr_replace, $str_value);
        } else {
            array_push($arr_replace, sanitize_text_field($value));
        }
    }
}

/**
 * Check that we have the user's email address
 */
if (empty($email_addr)) {
    $form_fields['result'] = false;
    $form_fields['msg']    = __($email_required_message, 'OCTheme');
    oc_session_write('form', $form_fields);
    wp_redirect($redirect_path);
    exit;
}

$headers  = "";
$headers .= "From: " . $email_addr . "\r\n";
$headers .= "Reply-To: " . $email_addr . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Transfer-Encoding: 8bit\r\n";
$headers .= "Content-Type: text/html; charset=\"UTF-8\"\r\n";

$contact_email_template   = get_field('contact_email_template', 'options');
$contact_subject_template = get_field('contact_subject', 'options');
$message = str_replace($arr_find, $arr_replace, $contact_email_template);
$subject = str_replace($arr_find, $arr_replace, $contact_subject_template);

/**
 * Send Contact Email
 */
$emails = get_field('config_email_admin', 'options');
$email_array = explode(',', $emails);

foreach ($email_array as $email) {
    $email = trim($email);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mail_check = wp_mail($email, $subject, $message, $headers);
    } else {
        error_log("Invalid Email: {$email}");
    }
}

if ($mail_check) {
    $auto_from    = get_field('autoreply_from', 'options');
    $auto_content = get_field('autoreply_content', 'options');
    $auto_subject = get_field('autoreply_subject', 'options');
    $message = str_replace($arr_find, $arr_replace, $auto_content);
    $subject = str_replace($arr_find, $arr_replace, $auto_subject);

    $headers  = "";
    $headers .= "From: " . $auto_from . "\r\n";
    $headers .= "Reply-To: " . $auto_from . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Transfer-Encoding: 8bit\r\n";
    $headers .= "Content-Type: text/html; charset=\"UTF-8\"\r\n";

    /**
     * Send Autoreply Email
     */
    $mail_check = wp_mail($email_addr, $subject, $message, $headers);

    $redirect_path = home_url('/thanks');
    $form_fields = array();
} else {
    $form_fields['result'] = false;
    $form_fields['msg']    = __($email_problem_message, 'OCTheme');
}

oc_session_write('form', $form_fields);
wp_redirect($redirect_path);
exit;
