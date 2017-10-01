var $type = $('#appbundle_animal_type');
var $breed = $('#breed-form');
// When type gets selected ...
$type.change(function() {
  // ... retrieve the corresponding form.
  var $form = $(this).closest('form');
  // Simulate form data, but only include the selected type value.
  var data = {};
  data[$type.attr('name')] = $type.val();
  // Submit data via AJAX to the form's action path.
  $.ajax({
    url : $form.attr('action'),
    type: $form.attr('method'),
    data : data,
    success: function(html) {
      // Replace current position field ...
      $('#appbundle_animal_breed').replaceWith(
        // ... with the returned one from the AJAX response.
        $(html).find('#appbundle_animal_breed')
      );
      $breed.show();
      // breed field now displays the appropriate breeds.
    }
  });
});