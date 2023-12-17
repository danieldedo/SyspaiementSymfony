

$(document).ready(function () {

    $('.supprimer').on('click', function() {
        let etudiantId = $(this).data('id');
        console.log(etudiantId)
        $('#confirmDelete').attr('href', "/etudiant/__id__/delete".replace('__id__', etudiantId))
    });
});