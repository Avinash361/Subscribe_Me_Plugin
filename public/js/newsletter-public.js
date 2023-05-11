jQuery(document).ready(function ($) {

	$('#sendmail-email-form').submit(function(){
		event.preventDefault(); 
		let email = $('#sendmail-email-form :input').val();

	$.ajax({
		type: 'post',
		dataType: 'json',
		url:newsletter_ajax.ajax_url,
		data :{
			action: 'email_form_submit',
			_ajax_nonce: newsletter_ajax.nonce,
			email: email
		},
		success: function (response) {
			$('#form-response').html(response.message);
		},
	})
	});
});

