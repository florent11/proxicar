class AnnDialogDelete
{
  deleteRequest()
  {
    $("#dialog-confirm").hide();
    $(".delete-annonce").click(function(){
        $("#dialog-confirm").dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
                "Oui": function() {
                    $(location).attr('href', $(".delete-annonce").data('url'));
                    $(this).dialog("close");
                },
                Non: function() {
                    $(this).dialog("close");
                }
            }
        });
    });  
  }  
}
const confirmAnnRequest = new AnnDialogDelete;
confirmAnnRequest.deleteRequest();