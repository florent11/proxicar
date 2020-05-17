class ajaxCallImages
{
    deleteImg()
    {
        $(".delete-image").click(function(event){
            event.preventDefault();  // On bloque le rechargement de la page lors du clic sur le bouton
            $(this).text('Suppression en cours');  // On remplace le texte du bouton 'Supprimer'
            $.ajax({
            url : $(this).data('url'),  // La ressource ciblée ; 'this' représente le clic sur le bouton
            type : 'DELETE'  // Le type de la requête HTTP
            }).done((response) => {
                $(this).parent().fadeOut("3000", () => {
                    $(this).parent().remove();  // Après le 'fadeOut' de 3 sec, on supprime la <div> dans laquelle le bouton se trouve
                });  
            });
        }); 
    }   
}
const ajaxDeleteImg = new ajaxCallImages;
ajaxDeleteImg.deleteImg();
