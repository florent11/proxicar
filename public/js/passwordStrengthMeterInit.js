class passwordStrengthMeterInit
{
  initPassStrengthMeter()
  {
    $('.myPassword').password({
      shortPass: "Mot de passe trop petit",
      badPass: "Faible - essayez de combiner lettres majucules/minuscules et chiffres",
      goodPass: "Moyen - essayez d'utiliser des caractères spéciaux",
      strongPass: "Mot de passe fort",
      containsField: "Le mot de Passe contient votre nom",
      enterPass: "Saisissez votre mot de passe",
      showPercent: true,
    });   
  }  
}
const password = new passwordStrengthMeterInit;
password.initPassStrengthMeter()