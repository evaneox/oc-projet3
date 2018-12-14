/**
 Script JS pour blog
 auteur : Jérémy BOUHOUR
 **/
jQuery(function($) {

    /** Initialisation des variables  **/
    var replyAction         = $('.reply');
    var cancelReplyAction   = $('.cancel-reply');
    var reportAction        = $('.report');
    var contentForm         = $('#content-form');
    var commentForm         = $('.comment-form');
    var confirm             = $('.confirm');

    /** Configuration de tinyMCE **/
    tinymce.init({
        selector: '#tiny-mce',
        height: 400,
        menubar: false,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table contextmenu paste code'
        ],
        toolbar: 'styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image',
        content_css: '//www.tinymce.com/css/codepen.min.css'
    });

    /**
     * Actions associés à la reponse à un commentaire
     */
    replyAction.on('click', function(e){
        e.preventDefault();
        var currentReply = $(this);

        // 1. Affichage du bouton annuler pour l'élément en cours
        cancelReplyAction.hide();
        replyAction.show();
        currentReply.hide();
        currentReply.parent().find('.cancel-reply').show();

        // 2. Suppression du formulaire principal
        contentForm.hide();

        // 3. Supression des message d'erreurs
        $('.alert').hide();

        // 4. Fermeture de tout les sous formulaire et remise à zéro des champs
        $('.sub-content-form').hide();
        $('.sub-content-form').find('[type=text][name=name]').val('');
        $('.sub-content-form').find('[name=content]').val('');

        // 5. Affichage d'un sous formulaire en dessous du parent
        currentReply.parent().parent().parent().parent().find('.sub-content-form').show();

    });

    /**
     * Actions associés à l'annulation d'une réponse à un sous commentaire
     */
    cancelReplyAction.on('click' , function(e){
        e.preventDefault();
        var currentCancelReply = $(this);

        // 1. Affichage du bouton repondre à la place d'annulation
        currentCancelReply.hide();
        replyAction.show();

        // 2. Supression des messages d'erreurs
        $('.alert').hide();

        // 3. Fermeture de tout les sous formulaire
        $('.sub-content-form').hide();

        // 4. Affichage du formulaire principal
        contentForm.show();
    });

    /**
     * Actions associés au signalement d'un commentaire
     */
    reportAction.on('click', function(e){
        e.preventDefault();
        var thisElement     = $(this);
        var action          = thisElement.attr('href');

        swal({
            title: 'Signaler?',
            text: "Le retour en arrière sera impossible !",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#525564',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, Bien sûr !',
            cancelButtonText: 'Annuler'
        }).then(function () {

            // Envoi des informations par get
            $.get(action)
                .done(function( data ) {
                    // On parse les données reçut et on supprime les messages antérieur
                    data = JSON.parse( data );

                    // Dans le cas d'erreurs, on affiche les message d'erreur apres chaques
                    // élément du formulaire qui sont concernés par ces erreurs
                    if(data.isFalse){
                        swal({
                            title: 'Erreur !',
                            text: "un problème est survenu.",
                            type: 'error',
                            confirmButtonColor: '#525564'
                        });
                    }else{
                        thisElement.remove();

                        swal({
                         title: 'Fait!',
                         text: "Ce commentaire a été signaler.",
                         type: 'success',
                         confirmButtonColor: '#525564'
                         });
                    }
                });
        })
    });

    /**
     * Actions associés à la soumission d'un commentaire principal
     */
    commentForm.on('submit', function(e){
        e.preventDefault();

        // Sauvegarde des elements qui ne seront plus accessible dans la fonction callback
        var thisForm    = $(this);
        var action      = thisForm.attr('action');

        // Envoi des informations par post
        $.post( action, $(this).serialize())
            .done(function( data ) {
                // On parse les données reçut et on supprime les messages antérieur
                data = JSON.parse( data );
                $('.alert').hide();

                // Dans le cas d'erreurs, on affiche les message d'erreur apres chaques
                // élément du formulaire qui sont concernés par ces erreurs
                if(data.isFalse){
                    $.each( data.errors, function( name, valeur ) {
                        thisForm.find('[name='+name+']').before('<div class="alert alert-danger" role="alert">'+ valeur +'</div>');
                    });
                }else{
                    location.reload(true);
                }
            });
    });

    /**
     * Cette fonction permet d'afficher un message d'alert pour confirmer les actions importantes
     */
    confirm.on('click', function(e){
        e.preventDefault();
        var thisElement     = $(this);
        var action          = thisElement.attr('href');
        swal({
            title: 'Confirmation',
            text: "Voulez-vous vraiment effectuer cette action ?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#525564',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, Bien sûr !',
            cancelButtonText: 'Annuler'
        }).then(function () {
            $(location).attr('href',action);
        })
    });



});
