$(document).ready(function() {
    // On récupère la balise <div> en question qui contient l'attribut « data-prototype » qui nous intéresse.
    var $container = $('div#appbundle_animal_animalStates');
    var $container2 = $('div#appbundle_animal_descriptions');
    var $container3 = $('div#appbundle_animal_images');

    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find(':input').length;
    var index2 = $container2.find(':input').length;
    var index3 = $container3.find(':input').length;

    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $('#add_state').click(function(e) {
      addState($container);

      e.preventDefault(); // évite qu'un # apparaisse dans l'URL
      return false;
    });

    $('#add_description').click(function(e) {
      addDescription($container2);

      e.preventDefault(); // évite qu'un # apparaisse dans l'URL
      return false;
    });
      
    $('#add_image').click(function(e) {
      addImage($container3);

      e.preventDefault(); // évite qu'un # apparaisse dans l'URL
      return false;
    });

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'un nouvel animal par exemple).
    if (index == 0) {
      addState($container);
    } else {
      // S'il existe déjà des statuts, on ajoute un lien de suppression pour chacuns d'entre eux
      $container.children('div').each(function() {
        addDeleteLink($(this));
      });
    }

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'un nouvel animal par exemple).
    if (index2 == 0) {
      addDescription($container2);
    } else {
      // S'il existe déjà des statuts, on ajoute un lien de suppression pour chacuns d'entre eux
      $container2.children('div').each(function() {
        addDeleteLink($(this));
      });
    }
    
        // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'un nouvel animal par exemple).
    if (index3 == 0) {
      addImage($container3);
    } else {
      // S'il existe déjà des statuts, on ajoute un lien de suppression pour chacuns d'entre eux
      $container3.children('div').each(function() {
        addDeleteLink($(this));
      });
    }

    // La fonction qui ajoute un formulaire CategoryType
    function addState($container) {
      // Dans le contenu de l'attribut « data-prototype », on remplace :
      // - le texte "__name__label__" qu'il contient par le label du champ
      // - le texte "__name__" qu'il contient par le numéro du champ
      var template = $container.attr('data-prototype')
        .replace(/__name__label__/g, 'Statut n°' + (index+1))
        .replace(/__name__/g,        index)
      ;

      // On crée un objet jquery qui contient ce template
      var $prototype = $(template);

      // On ajoute au prototype un lien pour pouvoir supprimer le statut
      addDeleteLink($prototype);

      // On ajoute le prototype modifié à la fin de la balise <div>
      $container.append($prototype);

      // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
      index++;
    }

    // La fonction qui ajoute un formulaire CategoryType
    function addDescription($container2) {
      // Dans le contenu de l'attribut « data-prototype », on remplace :
      // - le texte "__name__label__" qu'il contient par le label du champ
      // - le texte "__name__" qu'il contient par le numéro du champ
      var template = $container2.attr('data-prototype')
        .replace(/__name__label__/g, 'Description n°' + (index2+1))
        .replace(/__name__/g,        index2)
      ;

      // On crée un objet jquery qui contient ce template
      var $prototype = $(template);

      // On ajoute au prototype un lien pour pouvoir supprimer le statut
      addDeleteLink($prototype);

      // On ajoute le prototype modifié à la fin de la balise <div>
      $container2.append($prototype);

      // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
      index2++;
    }

    // La fonction qui ajoute un formulaire CategoryType
    function addImage($container3) {
      // Dans le contenu de l'attribut « data-prototype », on remplace :
      // - le texte "__name__label__" qu'il contient par le label du champ
      // - le texte "__name__" qu'il contient par le numéro du champ
      var template = $container3.attr('data-prototype')
        .replace(/__name__label__/g, 'Photo n°' + (index3+1))
        .replace(/__name__/g,        index3)
      ;

      // On crée un objet jquery qui contient ce template
      var $prototype = $(template);

      // On ajoute au prototype un lien pour pouvoir supprimer le statut
      addDeleteLink($prototype);

      // On ajoute le prototype modifié à la fin de la balise <div>
      $container3.append($prototype);

      // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
      index3++;
    }

    // La fonction qui ajoute un lien de suppression d'un statut
    function addDeleteLink($prototype) {
      // Création du lien
      var $deleteLink = $('<a href="#" class="btn btn-danger">Supprimer</a>');

      // Ajout du lien
      $prototype.append($deleteLink);

      // Ajout du listener sur le clic du lien pour effectivement supprimer le statut
      $deleteLink.click(function(e) {
        $prototype.remove();

        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
      });
    }
});