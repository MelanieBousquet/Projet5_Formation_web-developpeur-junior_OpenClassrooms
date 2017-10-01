$(document).ready(function() {
    
    // Select all the <div> which contain « data-prototype » attribute
    var selectors = ['div#appbundle_animal_animalStates', 'div#appbundle_animal_images', 'div#appbundle_publication_images'];
    // Select the label of precedent selectors
    var labelForms = ['div#appbundle_animal_animalStates div:first-child label', 'div#appbundle_animal_images div:first-child label', 'div#appbundle_publication_images div:first-child label' ]
    // Select the adding form links corresponding
    var buttons = ['#add_state', '#add_image', '#add_image'];
    // Numbers of existings collection
    var selectorsLength = selectors.length;
    
    // For each collection
    for (i = 0; i < selectorsLength; i++) {
        if($(selectors[i]).length) {
            var $container = $(selectors[i]);
            var index = $container.find('input').length;
            var $button = $(buttons[i]);

            // When edit an animal, remove label from existing forms
            $(labelForms[i]).remove();

            // Add new form on click on the addform button  
            addLinkClickAction($button, $container);

            // If existing form, add a delete link 
            handleCheckingExistingForms(index, $container);

            // Add a new form
            function addNewForm($container) {
              // In attribute « data-prototype », replace :
              // -  "__name__label__" text 
              // -  "__name__" text by the field number
              var template = $container.attr('data-prototype')
                .replace(/__name__label__/g, ' ')
                .replace(/__name__/g,        index)
              ;

              // Create a jquery object with this template
              var $prototype = $(template);

              // Add a delete link to the prototype so we can remove this form
              addDeleteLink($prototype);

              // Add the modified prototype to the <div> end
              $container.append($prototype);

              // Increment index for the next form
              index++;
            }

            // Add new form on click on the addform button  
            function addLinkClickAction($button, $container) {
                $button.click(function(e) {
                    addNewForm($container);
                    e.preventDefault() // avoid # in URL
                    return false;
                });
            }

            
            function handleCheckingExistingForms(index, $container) {
                if (index !== 0) {
                  // For existing forms, add a remove link
                  $container.children('div').each(function() {
                    addDeleteLink($(this));
                  });
                }
            }


            // A link button for deleting the form
            function addDeleteLink($prototype) {
              // Link creation
              var $deleteLink = $('<a href="#" class="btn btn-danger">Supprimer</a>');

              // Add the link
              $prototype.append($deleteLink);

              // Add listener for effective remove of the form
              $deleteLink.click(function(e) {
                $prototype.remove();

                e.preventDefault(); // avoid # in the URL'
                return false;
              });
            }

        }
    }
});
  