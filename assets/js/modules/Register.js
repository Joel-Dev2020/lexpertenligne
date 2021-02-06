
/**
 * @Property {HTMLFormElement} form
 */
export default class Register {

    constructor(form, Routing){
        alertify.set('notifier','position', 'top-right');
        this.form = form
        this.register(this.form, Routing)
    }

    register(form, Routing) {
        if (form){
            const btn = document.getElementById('btnRegister')
            const terms = document.getElementById('user_registration_terms')
            btn.disabled = true;
            terms.addEventListener('change', function (e) {
                e.preventDefault()
                btn.disabled = terms.checked !== true;
            })
            form.addEventListener('submit', function (e) {
                e.preventDefault()
                const username = document.getElementById('user_registration_username')
                const email = document.getElementById('user_registration_email')
                const password = document.getElementById('user_registration_password_first')
                const confirmPassword = document.getElementById('user_registration_password_second')
                const messageErrorUsername = document.getElementById('message-error-username')
                const messageErrorEmail = document.getElementById('message-error-email')
                const messageErrorPasswordFirst = document.getElementById('message-error-password-first')
                const messageErrorPasswordSecond = document.getElementById('message-error-password-second')
                const messageError = document.getElementById('message-error')
                if (username.value === ''){
                    username.classList.add('is-invalid')
                    messageErrorUsername.innerText = 'Entrer un username svp!'
                }else if (email.value === ''){
                    email.classList.add('is-invalid')
                    messageErrorEmail.innerText = 'Entrer votre adresse email svp!'
                }else if (password.value === ''){
                    password.classList.add('is-invalid')
                    messageErrorPasswordFirst.innerText = 'Entrer un mot de passe svp!'
                }else if (confirmPassword.value === ''){
                    confirmPassword.classList.add('is-invalid')
                    messageErrorPasswordSecond.innerText = 'Confirmer votre mot de passe svp!'
                }else {
                    username.classList.remove('is-invalid')
                    email.classList.remove('is-invalid')
                    password.classList.remove('is-invalid')
                    confirmPassword.classList.remove('is-invalid')
                    messageErrorUsername.innerHTML =  messageErrorUsername.innerText = messageErrorUsername.innerText = messageErrorUsername.innerText =''
                    const url = e.currentTarget.getAttribute('action')
                    const data = $(this).serialize();
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: data,
                        beforeSend: function() {
                            btn.disabled = true;
                            btn.classList.add('is-loading');
                        },
                        complete: function() {
                            btn.disabled = false;
                            btn.classList.remove('is-loading');
                        },
                        success: function(data) {
                            if(data.message === 'error'){
                                messageError.innerHTML = "<h6 class='alert alert-danger border-1 border-left-4 border-left-danger'>Une erreur s'est produite, veuillez réssauer</h6>"
                            }else if(data.message === 'success'){
                                window.location.href = data.urlRedirect
                                messageError.innerHTML = ''
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
                }
            })
        }
    }
}
