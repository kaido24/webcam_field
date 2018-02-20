(function ($, Drupal) {
  Drupal.behaviors.webcam_field = {
    attach: function (context, settings) {
    $('.webcam-content', context).each(function() {
      var elem = this;
      var timeout = $(elem).data('timeout');
      var dataUrl = $(elem).data('url');
      $.ajax({
        url: dataUrl,
        cache: false
      })
        .done(function(html) {
          $(elem).find('.webcam-image').append('<span class="webcam-popup-close">&times;</span><img src="' + html + '"><button class="webcam-popup-open btn btn-primary">'+ Drupal.t('Open modal') + '</button>');
          $(elem).find('.webcam-popup-open').on('click', function() {
            $(elem).addClass('webcam-modal');
            $(elem).find('.webcam-popup-close').show();
            $(elem).find('.webcam-image').addClass('webcam-modal-content');

          });
          $(elem).find('.webcam-popup-close').on('click', function() {
            $(elem).removeClass('webcam-modal');
            $(elem).find('.webcam-popup-close').hide();
            $(elem).find('.webcam-image').removeClass('modal-content');
          });
        });

      setInterval(function(){
        $.ajax({
          url: dataUrl,
          cache: false
        })
          .done(function(html) {
            $(elem).find('img').attr('src', html);
          });
      }, timeout);
    });
    }
  };
})(jQuery, Drupal);

