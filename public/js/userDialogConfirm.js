class userDialogConfirm 
{
  deleteRequest()
  {
    $("#dialog-confirm").hide();
    $(".delete-account").click(function(){
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
const confirmRequest = new userDialogConfirm;
confirmRequest.deleteRequest()