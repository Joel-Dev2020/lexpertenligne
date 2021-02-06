
/**
 * @Property {HTMLFormElement} form
 */
export default class Login {

    constructor(form, Routing){
        alertify.set('notifier','position', 'top-right');
        this.form = form
        this.login(this.form, Routing)
    }

    login(form, Routing) {
        if (form){
            form.addEventListener('submit', function (e) {
                e.preventDefault()
                const email = document.getElementById('email')
                const password = document.getElementById('password')
                const messageError = document.getElementById('message-error')
                const messageEmail = document.getElementById('message-email')
                const messagePassword = document.getElementById('message-password')
                const btn = document.getElementById('btnLogin')
                if (email.value === ''){
                    email.classList.add('is-invalid')
                    messageEmail.innerText = 'Veuillez saisir votre adresse email svp!'
                    password.classList.remove('is-invalid')
                }else if (password.value === ''){
                    email.classList.remove('is-invalid')
                    email.classList.add('is-valid')
                    messagePassword.innerText = 'Veuillez saisir votre mot de passe svp!'
                    password.classList.add('is-invalid')
                }else {
                    alert('ok')
                    console.log(btn)
                    /*email.classList.remove('is-invalid')
                    password.classList.remove('is-invalid')
                    email.classList.add('is-valid')
                    password.classList.add('is-valid')
                    const url = e.currentTarget.getAttribute('action')
                    const data = $(this).serialize();
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: data,
                        beforeSend: function() {
                            btn.disabled = true;
                            btn.innerText = 'Connexion en cours...';
                            btn.classList.add('is-loading');
                        },
                        complete: function() {
                            btn.disabled = false;
                            btn.classList.remove('is-loading');
                        },
                        success: function(data) {
                            if(data.message === 'error'){
                                email.classList.add('is-invalid')
                                password.classList.add('is-invalid')
                                messageError.innerHTML = '<h6 class="alert alert-danger border-1 border-left-4 border-left-danger">Erreur de connexion, veuillez réssauer</h6>'
                            }else{
                                window.location.href = Routing.generate('home', {}, true);
                                email.classList.remove('is-invalid')
                                password.classList.remove('is-invalid')
                                messageError.innerHTML = '<h6 class="alert alert-success border-1 border-left-4 border-left-success">Connexion réuissie, redirection en cours...</h6>'
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });*/
                }
            })
        }
    }
}
