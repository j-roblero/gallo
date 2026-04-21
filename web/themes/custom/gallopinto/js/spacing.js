(function ($) {
  Drupal.behaviors.updateSpacing = {
    attach: function (context, settings) {

      let sizes = ['lg', 'md', 'sm'];

      $('.field--name-field-value-lg').css('display', 'none');
      $('.field--name-field-value-md').css('display', 'none');
      $('.field--name-field-value-sm').css('display', 'none');

      sizes.forEach(function (size) {
        ['top', 'right', 'bottom', 'left'].forEach(function (direction) {
          var $select = $('[data-drupal-selector="edit-field-spacings-0-subform-field-' + direction + '-' + size + '"]');
          $select.find('option[value="_none"]').remove();
        });
      });
    }
  };
})(jQuery, Drupal);