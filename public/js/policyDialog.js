class policyDialog
{
  dialogBox()
  {
    $("#dialog-policy").hide(); 
    $(".policy").click(function(){
        $("#dialog-policy").dialog({
            resizable: false,
            height: "auto",
            width: "auto",
            position: ["top"],
            modal: true,
            buttons: {
                Ok: function() { 
                 $(this).dialog("close");
                }
            }
        });
        $(".ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset").css({
            'text-align': 'center',
            'float': 'none'
        });
        $(".ui-button").css({
            'outline': 'none'
        });
        $('html').animate({scrollTop:0});
    });  
  }  
}
const policy = new policyDialog;
policy.dialogBox()