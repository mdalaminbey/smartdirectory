/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!***********************************!*\
  !*** ./resources/js/admin/app.js ***!
  \***********************************/
jQuery(function ($) {
  let uploaded_image;
  $('.smart-directory .create-directory-form').on('submit', function (e) {
    e.preventDefault();
    let $form = $(this);
    let $submit_button = $form.find('.submit-button');
    let $notice_success = $form.find('.notice-success');
    let $notice_error = $form.find('.notice-error');
    $submit_button.attr('disabled', true);
    $submit_button.css('cursor', 'not-allowed');
    $submit_button.find('svg').show();
    $.ajax({
      url: SmartDirectorySettings.root + $form.attr('data-api'),
      method: $form.attr('data-method'),
      data: $form.serialize(),
      beforeSend: function (xhr) {
        xhr.setRequestHeader('X-WP-Nonce', SmartDirectorySettings.nonce);
      },
      success: function () {
        if ($form.find('input[name="ID"]').length == 0) {
          $form.trigger("reset");
        }
        if (uploaded_image?.changed !== undefined) {
          $form.find('.preview-image').attr('src', uploaded_image.changed.url);
        }
        $form.find('.preview_image_title').html('');
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
    });
  });
  $('.smart-directory #preview_image, .smart-directory .preview_image').on('click', function (event) {
    event.preventDefault();
    var frame = wp.media({
      title: 'Upload Preview Image',
      multiple: false,
      accept: "image/png, image/jpeg"
    }).open();
    frame.on('select', function () {
      document.getElementById('preview_image').setCustomValidity('');
      uploaded_image = frame.state().get('selection').first();
      $('.smart-directory .preview_image_title').html(uploaded_image.changed.filename);
      $('.smart-directory #preview_image').val(uploaded_image.id);
      $('.smart-directory #preview_image').trigger('change');
    });
  });
  $('.tablenav #doaction').on('click', function (e) {
    let value = $('#bulk-action-selector-top').val();
    if ('delete_all_with_attachment' === value) {
      return confirm("Are you want to delete directories with attachment?");
    }
    if ('delete_all' === value) {
      return confirm("Are you want to delete directories?");
    }
    return true;
  });
  $('.row-actions .delete a').on('click', function (e) {
    let confirm_status = false;
    e.preventDefault();
    confirm_status = confirm("Are you want to delete the directory?");
    if (confirm_status) {
      location.href = this.href;
    }
  });
  $('.row-actions .delete-with-attachment a').on('click', function (e) {
    let confirm_status = false;
    e.preventDefault();
    confirm_status = confirm("Are you want to delete directory with attachment?");
    if (confirm_status) {
      location.href = this.href;
    }
  });
});
/******/ })()
;
//# sourceMappingURL=app.js.map