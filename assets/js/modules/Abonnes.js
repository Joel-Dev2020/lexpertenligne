export default class Abonnes {

    constructor(){
        alertify.set('notifier','position', 'top-right');
        this.abonnes()
    }

    abonnes() {
        $('#subscribenewsletterform').on('submit',function (e) {
            e.preventDefault();
            let url = $(this).attr('action');
            let email = $(this).find('#abonnes_email');
            let resultat = $('#resultat');
            
            //Traitement des erreurs
            if(email.val() === ""){
                alertify.notify('Adresse email requise', 'error');
                resultat.empty().append(flashinfo("Adresse email requise", "Attention", 'danger'));
            }else{
                let data = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    beforeSend: function () {

                    },
                    success: function (response) {
                        if(response.resultat === "OK"){
                            alertify.notify('Votre email a bien été enregistré', 'success');
                            resultat.empty().append(flashinfo("Votre email a bien été enregistré", "Félicitation"));
                            email.val("");
                        }else if(response.resultat === "email_exist"){
                            alertify.notify('Cet abonné existe déja, veuillez réessayer', 'error');
                            resultat.empty().append(flashinfo("Cet abonné existe déja, veuillez réessayer", "Erreur", "danger"));
                        }else if(response.resultat === "NONOK"){
                            alertify.notify('Une erreur survenue', 'error');
                        }else{
                            //console.error(response);
                        }
                    },
                    complete: function(response) {},
                });
            }
        })
    }
}
