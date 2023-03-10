jQuery(function ($) {
	/**
	 * Fires after preview image on change
	 */
	$('.smart-directory #preview_image').on('change', function (event) {

		let src = URL.createObjectURL(event.target.files[0]);
		let $image_body = $(this).siblings('.preview_body');

		$image_body.find('.preview-title').html(event.target.files[0].name);
		$image_body.find('img').attr('src', src);

		let $preview_content = $image_body.find('.preview-content');

		if ($preview_content.is(":hidden")) {
			$preview_content.slideToggle();
		}
	});

	/**
	 * Fires after click preview image remove button
	 */
	$('.smart-directory .preview_image_remove').on('click', function () {
		let $image_body = $(this).closest('.preview_body');
		$image_body.find('.preview-content').slideToggle();
		$image_body.siblings('input').val('');
		$image_body.find('img').attr('src', null);
	});

	/**
	 * Fires before submit directory form
	 */
	$('.smart-directory .create-directory-form').on('submit', function (e) {
		e.preventDefault();

		let $form = $(this)
		let $submit_button = $form.find('.submit-button');
		let $notice_success = $form.find('.notice-success');
		let $notice_error = $form.find('.notice-error');

		$submit_button.attr('disabled', true);
		$submit_button.css('cursor', 'not-allowed');
		$submit_button.find('svg').show();

		$.ajax({
			url: SmartDirectorySettings.root + 'smart-directory/v1/directories',
			method: 'POST',
			processData: false,
			contentType: false,
			data: new FormData(this),
			beforeSend: function (xhr) {
				xhr.setRequestHeader('X-WP-Nonce', SmartDirectorySettings.nonce);
			},
			success: function () {
				$form.trigger("reset");
				$form.find('.preview-content').slideToggle();

				if (!$notice_error.is(":hidden")) {
					$notice_error.slideToggle();
				}
				$notice_success.slideToggle();
				let myTimeout = setTimeout(function () {
					$notice_success.slideToggle();
					clearTimeout(myTimeout);
				}, 5000);
			},
			error: function (data) {
				let messages = JSON.parse(data.responseText).messages;
				let notices = '';
				for (const key in messages) {
					if (Object.hasOwnProperty.call(messages, key)) {
						const element = messages[key];
						let notice = '<ul class="bg-danger/10 mb-2 rounded-md px-3 py-2">';
						element.forEach(message => {
							notice += '<li>' + message + '</li>';
						});
						notice += '</ul>';
						notices += notice;
					}
				}
				$notice_error.html(notices);

				if ($notice_error.is(":hidden")) {
					$notice_error.slideToggle();
				}
			},
			complete: function () {
				$submit_button.removeAttr('disabled');
				$submit_button.css('cursor', 'pointer');
				$submit_button.find('svg').hide();
			}
		})
	});

	function pageUrl(page = '') {
		let url = new URL(window.location.href);
		url.searchParams.delete('directory-page');
		url.searchParams.append('directory-page', page);
		return url.toString();
	}

	let $directory_list = $('.directory-list');
	let url = new URL(window.location.href);
	let pagination = $('.directory-list-body .pagination');
	let total_directory = $directory_list.attr('data-total');
	let directory_per_page = 12;

	if (parseInt(total_directory) > directory_per_page) {
		pagination.each(item => {
			let $pagination = $(pagination[item]);
			$pagination.pagination({
				currentPage: url.searchParams.get('directory-page'),
				items: total_directory,
				itemsOnPage: directory_per_page,
				cssStyle: 'light-theme',
				hrefTextPrefix: pageUrl(),
				number: false,
				onPageClick: function (page, event) {
					event.preventDefault();

					let $directory_body = $pagination.closest('.directory-list-body');

					/**
					 * Show preloader
					 */
					$directory_body.find('.preloader').show();

					/**
					 * Replace current url
					 */
					window.history.pushState('', '', pageUrl(page))

					$.ajax({
						url: SmartDirectorySettings.root + $directory_body.attr('data-api'),
						data: { 'directory-page': page },
						beforeSend: function (xhr) {
							xhr.setRequestHeader('X-WP-Nonce', SmartDirectorySettings.nonce);
						},
						success: function (data) {
							/**
							 * Set html content and hide preloader
							 */
							$directory_body.find('.directory-list').html(data.html);
							$directory_body.find('.preloader').hide();
						}
					});
				}
			});
		})
	}
});