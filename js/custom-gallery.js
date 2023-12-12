jQuery(document).ready(function($) {
    console.log('Le script custom-gallery.js est chargé.');

    // Attacher l'événement click avec délégation sur un élément parent existant
    // 'body' peut être remplacé par un autre conteneur plus spécifique si nécessaire
    $('body').on('click', '.category-item', function(e) {
        e.preventDefault(); // Empêcher la navigation par défaut du lien
        console.log('Élément de catégorie cliqué.');

        var selectedCategory = $(this).text();
        console.log('Catégorie sélectionnée : ', selectedCategory);

        // Envoyer une requête AJAX au serveur
        $.ajax({
            url: ajax_object.ajax_url, // L'URL pour la requête AJAX
            type: 'POST',
            data: {
                action: 'load_gallery_by_category', // L'action AJAX côté serveur
                category: selectedCategory // La catégorie sélectionnée
            },
            beforeSend: function() {
                console.log('Envoi de la requête AJAX pour la catégorie : ', selectedCategory);
            },
            success: function(response) {
                // Mettre à jour le conteneur de la galerie avec la réponse
                $('#gallery-container').html(response);
                console.log('HTML de la galerie mis à jour.');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Afficher un message d'erreur en cas d'échec de la requête
                console.error('Erreur AJAX : ', textStatus, errorThrown);
            }
        });
    });
});
