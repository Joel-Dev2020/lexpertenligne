export default class Contacts {

    constructor(){
        alertify.set('notifier','position', 'top-right');
        this.contact()
    }

    contact() {
        $('#contact-form').on('submit',function (e) {
            e.preventDefault();
            let url = $(this).attr('action');
            let robot = $(this).find('#contacts_robot');
            let email = $(this).find('#contacts_email');
            let nomprenoms = $(this).find('#contacts_nomprenoms');
            let telephone = $(this).find('#contacts_telephone');
            let message = $(this).find('#contacts_message');
            let resultat = $('#js-contact-message');
            let btn = document.getElementById('js-contact-button-send');

            //Traitement des erreurs
            if(robot.val() !== ""){
                toastr.error('Une erreur est survenue', 'Erreur', 1000)
                resultat.empty().append(flashinfo("danger", "Une erreur est survenue"));
                btn.classList.remove('is-loading')
            }else if(
                nomprenoms.val() === "" ||
                email.val() === "" ||
                telephone.val() === "" ||
                message.val() === ""
            ){
                toastr.error('Tous les champs sont réquis', 'Erreur', 1000)
                resultat.empty().append(flashinfo("danger", "Tous les champs sont réquis"));
                btn.classList.remove('is-loading')
            }else{
                let data = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    beforeSend: function () {
                        btn.classList.add('is-loading')
                    },
                    success: function (response) {
                        if(response.resultat === "OK"){
                            toastr.success('Votre email a bien été envoyé', 'Succès', 1000)
                            resultat.empty().append(flashinfo("success", "Votre email a bien été envoyé, Nous vous contacterons bientôt"));
                            nomprenoms.val("");
                            email.val("");
                            telephone.val("");
                            message.val("");
                            btn.classList.remove('is-loading')
                        }else if(response.resultat === "NONOK"){
                            toastr.warning('Une erreur survenue', 'Erreur', 1000)
                            btn.classList.remove('is-loading')
                        }else{
                            //console.error(response);
                        }
                    },
                    complete: function(response) {btn.classList.remove('is-loading')},
                });
            }
        })
    }
}
