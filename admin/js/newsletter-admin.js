jQuery().ready(function($){
	$('#setting_form').submit(function(){
		event.preventDefault();
		let no_of_posts = $('#setting_form :input').val();
	

		$.ajax({
			type: 'post',
			dataType: 'json',
			url:newsletter_setting_ajax.ajax_url,
			data: {
				action: 'setting_form_submit',
				_ajax_nonce: newsletter_setting_ajax.nonce,
				no_of_posts: no_of_posts,
			},
			success: function(response){
				console.log(response);
			}
		})
	});
})