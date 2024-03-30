<?php
	/*
	 * NOTES
	 *
	 * ARGS
	 *    This template expects to be passed two arguments:
	 *
	 *       $args['confirm-form-id'] (string)  - Element ID of form to confirm
	 *       $args['submit-button-id'] (string) - Element ID of element that submits the form
	 *
	 * DISPLAY
	 *    This template does not actually "display" anything on the page. The modal dialog is
	 *    hidden (display: none) until it is invoked by (it's own) JavaScript to display the
	 *    modal dialog. You do not need to do anything to get this functionality as long as
	 *    you follow the rules set out below. You only need to add this template to a page.
	 *    that has a form you wish to confirm.
	 *
	 * LOADING
	 *    Load this template using get_template_part() as follows:
	 *
	 *    get_template_part('template-parts/confirm', 'modal', array(
	 *        'confirm-form-id'   => 'contact',
	 *        'submit-button-id'  => 'contact_submit_btn'
	 *    ));
	 *
	 *    where:
	 *       'confirm-form-id'  - is the id attribute of the <form> you wish to confirm
	 *       'submit-button-id' - is the id attribute of the element that submits that form.
	 *                            the element is typically a <button>, but it can be anything
	 *
	 * All of the inputs are handled for you automatically, however, your form must follow certain
	 * patterns for this code to work. The patterns are as follows:
	 *
	 *   o All inputs require an "id" attribute
	 *
	 *   o All inputs require a label that has a "for" attribute that matches the input's "id"
	 *
	 *   o Checkboxes require a specific structure:
	 *     . All of the checkboxes must follow the "id" and label[for="id"] pattern as above
	 *     . The checkboxes must be grouped inside a tag with the class "checkbox-group"
	 *     . The "checkbox-group" tag must contain a label with the class "group-label"
	 *
	 *   o Radio buttons require a specific structure:
	 *     . All of the radio buttons must follow the "id" and label[for="id"] pattern as above
	 *     . The radio buttons must be grouped inside a tag with the class "radio-group"
	 *     . The "radio-group" tag must contain a label with the class "group-label"
	 *
	 *   o Giving any <input> a class of "no-confirm" will prevent that input from being included
	 *
	 *   o Giving any parent of an <input> a class of "no-cofirm" will prevent that input from being included
	 *
	 * This code will not (currently) support more than one form on a given page. This is due to ID
	 * overlap. Fixing this is a minor update of the code to intgrate some method of making the
	 * modal and dialog IDs unique for each form.
	 *
	 * Please note that when you see an error on the console, the most common reason for this is that
	 * you have not provided the 'confirm-form-id' argument to the template, or you provided the wrong id.
	 * Also, if you do not get the confirmation modal, but instead your form is submitted as normal, this
	 * is because you have not provided the 'submit-button-id' or have provided an incorrect id.
	 *
	 * REMEMBER This code expects the "submitting spinner" image at "<theme>/img/confirm-spinner.svg"
	 * You can, of course, change that name below (search for: confirm-inputs-confirm-btn-spinner) if
	 * you need to use a GIF file (for example).
	 *
	 */
	$template_uri = get_template_directory_uri();
?>
<style>
	.confirm-inputs-modal {
		z-index: 999;
		display: none;
		position: fixed;
		top: 0;
		left: 0;
		width: 100vw;
		height: 100vh;
		background-color: rgba(0 0 0 / 50%);
	}
	.confirm-inputs-modal.show-modal {
		display: block;
	}
	.confirm-inputs-modal .confirm-inputs-dialog {
		position: absolute;
		top: 50%;
		left: 50%;
		min-width: 50%;
		max-height: 80vh;
		display: flex;
		flex-direction: column;
		padding: 20px;
		background-color: #fff;
		box-shadow: 0 15px 30px -5px rgba(0 0 0 / 90%);
		transition: transform 0.5s;
		transform: translate(-50%,-200vh);
	}
	.confirm-inputs-modal .confirm-inputs-dialog.show-dialog {
		transform: translate(-50%,-50%);
	}
	.confirm-inputs-modal .confirm-inputs-dialog .confirm-inputs-header {
		flex: 0 0 auto;
		display: flex;
		justify-content: center;
		padding: 0 0 20px 0;
	}
	.confirm-inputs-modal .confirm-inputs-dialog .confirm-inputs-header .confirm-inputs-title {
		position: relative;
		display: inline-block;
		height: 40px;
		color: white;
		font-size: 23px;
		font-weight: 500;
		line-height: 40px;
		padding: 0 0.75em;
		margin: 0;
		background-color: var(--oc-primary-color);
	}

	.confirm-inputs-modal .confirm-inputs-dialog .confirm-inputs-footer {
		flex: 0 0 auto;
		padding: 20px 0 0 0;
		overflow: hidden;
	}
	.confirm-inputs-modal .confirm-inputs-dialog .confirm-inputs-footer .confirm-inputs-confirm-btn {
		width: 50%;
		min-width: 240px;
		min-height: 50px;
		display: block;
		color: #fff;
		font-size: 21px;
		font-weight: 600;
		margin: 0 auto;
		padding: 0.5em 0.5em;
		outline: none;
		border: none;
		border-radius: 10px;
		background-color: var(--oc-btn-primary-color);
	}
	.confirm-inputs-modal .confirm-inputs-dialog  .confirm-inputs-confirm-btn:disabled {
		cursor: not-allowed;
		background-color: #c8c8c8;
	}
	.confirm-inputs-modal .confirm-inputs-dialog  .confirm-inputs-confirm-btn:disabled .confirm-inputs-confirm-btn-text {
		margin-right: 14px;
	}
	.confirm-inputs-modal .confirm-inputs-dialog  .confirm-inputs-confirm-btn .confirm-inputs-confirm-btn-spinner {
		display: none;
	}
	.confirm-inputs-modal .confirm-inputs-dialog  .confirm-inputs-confirm-btn:disabled .confirm-inputs-confirm-btn-spinner {
		display: inline-block;
		width: 32px;
		height: 32px;
		animation: confirm-spinner 1.5s linear infinite;
	}
	@keyframes confirm-spinner {
		0%   { transform: rotate(0deg); }
		50%  { transform: rotate(180deg); }
		100% { transform: rotate(360deg); }
	}
	.confirm-inputs-modal .confirm-inputs-dialog .confirm-inputs-back-link {
		display: inline-block;
		color: #000;
		font-size: 18px;
		font-weight: 700;
		text-align: left;
		text-decoration: none;
		margin: 0;
		transition: margin 0.3s;
	}
	.confirm-inputs-modal .confirm-inputs-dialog .confirm-inputs-back-link.disabled {
		margin-left: -100px;
	}
	.confirm-inputs-modal .confirm-inputs-dialog .confirm-inputs-body {
		flex-grow: 1;
		overflow: auto;
		padding: 0 40px;
	}
	.confirm-inputs-modal .confirm-inputs-dialog .confirm-inputs-body .confirm-inputs-group {
		display: flex;
		align-items: center;
		padding: 15px 0;
		border-bottom: 1px solid #e6e6e6;
	}
	.confirm-inputs-modal .confirm-inputs-dialog .confirm-inputs-body .confirm-inputs-group .confirm-inputs-label {
		flex: 0 0 30%;
		display: block;
		color: #555;
		font-size: 18px;
		font-weight: 500;
		line-height: 27px;
		text-align: right;
		padding-right: 10px;
		margin: 0;
	}
	.confirm-inputs-modal .confirm-inputs-dialog .confirm-inputs-body .confirm-inputs-group .confirm-inputs-value {
		flex: 0 0 70%;
		display: block;
		color: #0f0d0d;
		font-size: 14px;
		font-weight: 500;
		line-height: 27px;
		text-align: left;
		padding-left: 10px;
		margin: 0;
	}
	.confirm-inputs-modal .confirm-inputs-dialog .confirm-inputs-body .confirm-inputs-group .confirm-inputs-checkboxes {
		color: #0f0d0d;
		font-size: 14px;
		font-weight: 500;
		padding-left: 10px;
	}
	.confirm-inputs-modal .confirm-inputs-dialog .confirm-inputs-body .confirm-inputs-group .confirm-inputs-checkbox:not(:last-child) {
		margin-bottom: 5px;
	}
	.confirm-inputs-modal .confirm-inputs-dialog .confirm-inputs-body .confirm-inputs-group .confirm-inputs-checkbox-check {
		width: 1em;
		color: #5d2a56;
		font-size: 18px;
	}
	.confirm-inputs-modal .confirm-inputs-dialog .confirm-inputs-body .confirm-inputs-group .confirm-inputs-checkbox-label {
		display: inline-block;
		color: #0f0d0d;
		font-size: 14px;
		font-weight: 500;
		margin-left: 7px;
	}
	.confirm-inputs-modal .confirm-inputs-dialog .confirm-inputs-body .confirm-inputs-group .confirm-inputs-radios {
		color: #0f0d0d;
		font-size: 14px;
		font-weight: 500;
		padding-left: 10px;
	}
	.confirm-inputs-modal .confirm-inputs-dialog .confirm-inputs-body .confirm-inputs-group .confirm-inputs-radio:not(:last-child) {
		margin-bottom: 5px;
	}
	.confirm-inputs-modal .confirm-inputs-dialog .confirm-inputs-body .confirm-inputs-group .confirm-inputs-radio-button {
		width: 1em;
		color: #5d2a56;
		font-size: 18px;
	}
	.confirm-inputs-modal .confirm-inputs-dialog .confirm-inputs-body .confirm-inputs-group .confirm-inputs-radio-label {
		display: inline-block;
		color: #0f0d0d;
		font-size: 14px;
		font-weight: 500;
		margin-left: 7px;
	}
</style>

<div id="confirm_inputs_modal" class="confirm-inputs-modal">
	<div id="confirm_inputs_dialog" class="confirm-inputs-dialog">
		<div class="confirm-inputs-header">
			<p class="confirm-inputs-title">入力内容のご確認</p>
		</div>
		<div id="confirm_inputs_body" class="confirm-inputs-body">
		</div>
		<div class="confirm-inputs-footer">
			<button id="confirm_inputs_submit_btn" class="confirm-inputs-confirm-btn">
				<span class="confirm-inputs-confirm-btn-text">送信する</span>
				<img class="confirm-inputs-confirm-btn-spinner"
					src="<?php echo $template_uri; ?>/img/confirm-spinner.svg" />
			</button>
			<a id="confirm_inputs_back_btn" class="confirm-inputs-back-link" href="#">←戻る</a>
		</div>
	</div>
</div>

<div id="confirm_inputs_text" style="display: none;">
	<div class="confirm-inputs-group">
		<label class="confirm-inputs-label text">_LABEL_</label>
		<p class="confirm-inputs-value text">_VALUE_</p>
	</div>
</div>
<div id="confirm_inputs_textarea" style="display: none;">
	<div class="confirm-inputs-group">
		<label class="confirm-inputs-label textarea">_LABEL_</label>
		<p class="confirm-inputs-value textarea">_VALUE_</p>
	</div>
</div>
<div id="confirm_inputs_select" style="display: none;">
	<div class="confirm-inputs-group">
		<label class="confirm-inputs-label select">_LABEL_</label>
		<p class="confirm-inputs-value select">_VALUE_</p>
	</div>
</div>
<div id="confirm_inputs_checkbox" style="display: none;">
	<div class="confirm-inputs-group">
		<label class="confirm-inputs-label checkbox">_LABEL_</label>
		<div class="confirm-inputs-checkboxes">_CHECKBOXES_</div>
	</div>
</div>
<div id="confirm_inputs_radio" style="display: none;">
	<div class="confirm-inputs-group">
		<label class="confirm-inputs-label radio">_LABEL_</label>
		<div class="confirm-inputs-radios">_RADIOS_</div>
	</div>
</div>
<script type="text/javascript">
(function($) {
	console.log( "Confirm Modal Loaded" );

	function confirm_form_inputs( formId ) {
		var form = $(formId);
		if ( form.length > 0 ) {
			var confirmModal = $('#confirm_inputs_modal');
			var confirmDialog = $('#confirm_inputs_dialog');
			var confirmDialogBody = $('#confirm_inputs_body');
			confirmDialogBody.html('');

			form.find('input[type="text"]').each(function(idx) {
				var input = $(this);
				if ( ! input.hasClass('no-confirm') && input.parents('.no-confirm').length == 0 ) {
					var inputId = input.attr('id');
					var inputLabel = form.find('label[for="' + inputId + '"]');
					var label = inputLabel.html();
					var value = input.val();
					var templateHtml = $('#confirm_inputs_text').html();
					var insertHtml = templateHtml.replace(/_LABEL_/g, label).replace(/_VALUE_/g, value);
					confirmDialogBody.append(insertHtml);
				}
			});

			form.find('textarea').each(function(idx) {
				var input = $(this);
				if ( ! input.hasClass('no-confirm') && input.parents('.no-confirm').length == 0 ) {
					var inputId = input.attr('id');
					var inputLabel = form.find('label[for="' + inputId + '"]');
					var label = inputLabel.html();
					var value = input.val();
					var templateHtml = $('#confirm_inputs_textarea').html();
					var insertHtml = templateHtml.replace(/_LABEL_/g, label).replace(/_VALUE_/g, value);
					confirmDialogBody.append(insertHtml);
				}
			});

			form.find('select').each(function(idx) {
				var input = $(this);
				if ( ! input.hasClass('no-confirm') && input.parents('.no-confirm').length == 0 ) {
					var inputId = input.attr('id');
					var inputLabel = form.find('label[for="' + inputId + '"]');
					var label = inputLabel.html();
					var value = input.val();
					var valueLabel = input.find("option[value='" + value + "']").text();
					var templateHtml = $('#confirm_inputs_select').html();
					var insertHtml = templateHtml.replace(/_LABEL_/g, label).replace(/_VALUE_/g, valueLabel);
					confirmDialogBody.append(insertHtml);
				}
			});

			form.find('.checkbox-group').each(function(idx) {
				var group = $(this);
				if ( ! group.hasClass('no-confirm') && group.parents('.no-confirm').length == 0 ) {
					var groupLabel = '';
					var label = group.find('label.group-label');
					if ( label.length > 0 ) {
						groupLabel = label.html();
					}

					var checkboxHtml = '';
					group.find('input[type="checkbox"]').each(function(idx) {
						var checkbox = $(this);
						if ( checkbox.is(':checked') ) {
							var checkboxId = checkbox.attr('id');
							var checkboxLabel = group.find('label[for="' + checkboxId + '"]');
							checkboxHtml += '<div class="confirm-inputs-checkbox">';
							checkboxHtml += '<span class="confirm-inputs-checkbox-check far fa-check-square"></span>';
							checkboxHtml += '<span class="confirm-inputs-checkbox-label">' + checkboxLabel.text() + '</span>';
							checkboxHtml += '</div>';
						}
					});

					var templateHtml = $('#confirm_inputs_checkbox').html();
					var insertHtml = templateHtml.replace(/_LABEL_/g, groupLabel).replace(/_CHECKBOXES_/g, checkboxHtml);
					confirmDialogBody.append(insertHtml);
				}
			});

			form.find('.radio-group').each(function(idx) {
				var group = $(this);
				if ( ! group.hasClass('no-confirm') && group.parents('.no-confirm').length == 0 ) {
					var groupLabel = '';
					var label = group.find('label.group-label');
					if ( label.length > 0 ) {
						groupLabel = label.html();
					}

					var radioHtml = '';
					group.find('input[type="radio"]').each(function(idx) {
						var radio = $(this);
						if ( radio.is(':checked') ) {
							var radioId = radio.attr('id');
							var radioLabel = group.find('label[for="' + radioId + '"]');
							radioHtml += '<div class="confirm-inputs-radio">';
							radioHtml += '<span class="confirm-inputs-radio-button far fa-dot-circle"></span>';
							radioHtml += '<span class="confirm-inputs-radio-label">' + radioLabel.text() + '</span>';
							radioHtml += '</div>';
						}
					});

					var templateHtml = $('#confirm_inputs_radio').html();
					var insertHtml = templateHtml.replace(/_LABEL_/g, groupLabel).replace(/_RADIOS_/g, radioHtml);
					confirmDialogBody.append(insertHtml);
				}
			});

			$('body').addClass('locked');

			confirmDialog.removeClass('show-dialog');
			confirmModal.addClass('show-modal');
			setTimeout(function() {
				confirmDialog.addClass('show-dialog');
			}, 100);
		} else {
			console.log( "oc-theme.js/confirm_form_inputs() ERROR No form with id='" + formId + "'" );
		}
	}

	$( document ).ready(function() {
		console.log( "Confirm Modal Ready" );
		var contactFormId = "#<?php echo $args['confirm-form-id']; ?>";
		var contactForm = $(contactFormId);

		$('#<?php echo $args['submit-button-id']; ?>').on('click', function(ev) {
			ev.preventDefault();
			$('#confirm_inputs_back_btn').removeClass('disabled');
			if ( contactForm[0].checkValidity() ) {
				confirm_form_inputs(contactFormId);
			} else {
				console.log( "Form inputs are not valid." );
				if ( contactForm[0].reportValidity ) {
					contactForm[0].reportValidity();
				} else {
					//warn IE users somehow
				}
			}
		});

		$('#confirm_inputs_back_btn').on('click', function(ev) {
			ev.preventDefault();
			var confirmModal = $('#confirm_inputs_modal');
			var confirmDialog = $('#confirm_inputs_dialog');
			$('body').removeClass('locked');
			confirmDialog.removeClass('show-dialog');
			setTimeout(function() {
				confirmModal.removeClass('show-modal');
			}, 500);
		});

		$('#confirm_inputs_submit_btn').on('click', function(ev) {
			ev.preventDefault();
			var submitBtn = $(this);
			submitBtn.prop('disabled', true);
			$('#confirm_inputs_back_btn').addClass('disabled');
			setTimeout(function() {
				contactForm.submit();
			}, 200);
		});
	});
}(jQuery));
	
</script>
