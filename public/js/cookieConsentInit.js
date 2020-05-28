class cookieConsentInit
{
    cookieConsentBox()
    {
        window.cookieconsent.initialise({
            "palette": {
                "popup": {
                "background": "#252e39"
                },
                "button": {
                "background": "transparent",
                "text": "#14a7d0",
                "border": "#14a7d0"
                }
            },
            "type": "opt-out",
            elements: {
                messagelink: '<span id="cookieconsent:desc" class="cc-message">Ce site web utilise des cookies pour améliorer votre expérience de navigation.<a aria-label="learn more about cookies" tabindex="0" class="cc-link class policy" href="javascript:void(0)">En savoir plus</a></span>',
                allow: '<a aria-label="allow cookies" tabindex="0" class="cc-btn cc-allow">Accepter</a>',
                deny: '<a aria-label="deny cookies" tabindex="0" class="cc-btn cc-deny">Refuser</a>',
               }
        });  
    }
}
const cookieConsent = new cookieConsentInit;
cookieConsent.cookieConsentBox();