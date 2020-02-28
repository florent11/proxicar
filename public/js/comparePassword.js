class comparePassword
{
    constructor() 
    {
      this.formExit = $("#registration_form_plainPassword_second, #modif_password_plainPassword_second, #resetting_plainPassword_second");  // Attribut qui selectionne le deuxième champ du formulaire
    }

    comparePass()
    {
        this.formExit.keyup(function(){
            if ($("#registration_form_plainPassword_first, #modif_password_plainPassword_first, #resetting_plainPassword_first").val() == $ ("#registration_form_plainPassword_second, #modif_password_plainPassword_second, #resetting_plainPassword_second").val()) { 
                $("#registration_form_plainPassword_first, #modif_password_plainPassword_first, #resetting_plainPassword_first").addClass("is-valid").removeClass("is-invalid");
                $("#registration_form_plainPassword_second, #modif_password_plainPassword_second, #resetting_plainPassword_second").addClass("is-valid").removeClass("is-invalid");
                $("#mdp-invalid").remove();
            } 
            else { 
                $("#registration_form_plainPassword_first, #modif_password_plainPassword_first, #resetting_plainPassword_first").addClass("is-invalid").removeClass("is-valid");
                $("#registration_form_plainPassword_second, #modif_password_plainPassword_second, #resetting_plainPassword_second").addClass("is-invalid").removeClass("is-valid");
            }
        })
    }
}
const compare = new comparePassword;
compare.comparePass()