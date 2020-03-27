class AnnDialogDelete
{
  deleteRequest()
  {
    $("#dialog-confirm").hide();
    $(".delete-annonce").click(function(){
        let that = this
        $("#dialog-confirm").dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
                "Oui": function() {
                    $(location).attr('href', $(that).data('url'));
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
$(function(){
    const confirmAnnRequest = new AnnDialogDelete;
    confirmAnnRequest.deleteRequest();
})