class hideTelNum
{
    hideNumber()
    {
        var captureUserNumTel = $(".numtel-button").attr("num-user");
        $(".numtel-button").removeAttr("num-user");

        $(".numtel-button").click(function() {
            $(this).after('<p>Téléphone: ' + captureUserNumTel + '</p>');
            $(".numtel-button").remove(); 
        })
    }
}
const initHide = new hideTelNum;
initHide.hideNumber();
