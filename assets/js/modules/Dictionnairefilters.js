
/**
 * @Property {HTMLElement} searchelement
 */
export default class Dictionnairefilters {

    /**
     *
     * @param {HTMLElement|null} element
     * @param {HTMLElement|null} container
     * @param {HTMLElement|null} pagination
     * @param {HTMLElement|null} abcds
     * @param {HTMLElement|null} feedback
     */
    constructor(element){
        if (element === null){
            return;
        }
        this.searchelement = element
        this.container = document.getElementById('js-dictionnaires-filter')
        this.pagination = document.getElementById('js-dictionnaires-pagination')
        this.abcds = document.getElementById('js-dictionnaires-abcds')
        this.feeback = document.getElementById('dictionnaire-feedback')
        if (this.feeback){
            this.feeback.classList.add('feedback-none')
        }
        this.bindEvent(this.searchelement, this.container, this.feeback, this.pagination, this.abcds);

        this.inputText = document.getElementById('searchInput')
        this.bindEventSearchText(this.inputText, this.container, this.feeback, this.pagination, this.abcds);
    }

    bindEvent (element, container, feedback, pagination, abcds) {
        element.forEach((el) => {
            el.addEventListener('click', function (e) {
                e.preventDefault()
                const result = document.getElementById('result')
                const url = e.currentTarget.dataset.action
                const search = e.currentTarget.dataset.label
                feedback.classList.remove('feedback-none')
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {'search': search},
                    beforeSend: function() {
                        feedback.classList.remove('feedback-none')
                    },
                    success: function(data) {
                        if (data.status === 'success'){
                            container.innerHTML = data.resultat
                            pagination.innerHTML = data.pagination
                            abcds.innerHTML = data.abcds
                            result.innerText = 'Resultat(s) trouv(é) pour "'+ search +'"'
                        }else if (data.status === 'error'){
                            container.innerHTML = '<div class="list-group-item p-3">\n' +
                                '        <div class="row align-items-start">\n' +
                                '            <div class="col mb-8pt mb-md-0">\n' +
                                '                <p class="mb-8pt text-center">\n' +
                                '                    <a href="javascript:" class="text-body">\n' +
                                '                        <strong>Aucun resultat trouvé, veuillez réessayer svp!</strong>\n' +
                                '                    </a>\n' +
                                '                </p>\n' +
                                '            </div>\n' +
                                '        </div>\n' +
                                '    </div>'
                            feedback.classList.add('feedback-none')
                            return null;
                        }
                        feedback.classList.add('feedback-none')
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        feedback.classList.add('feedback-none')
                    }
                });
            })
        })
    }

    bindEventSearchText (input, container, feedback, pagination, abcds) {
        if (input){
            input.addEventListener('blur', function (e) {
                e.preventDefault()
                const result = document.getElementById('result')
                const url = e.currentTarget.dataset.action
                const search = e.currentTarget.value;
                feedback.classList.remove('feedback-none')
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {'search': search},
                    beforeSend: function() {
                        feedback.classList.remove('feedback-none')
                    },
                    success: function(data) {
                        if (data.status === 'success'){
                            container.innerHTML = data.resultat
                            pagination.innerHTML = data.pagination
                            abcds.innerHTML = data.abcds
                            result.innerText = 'Resultat(s) trouv(é) pour "'+ search +'"'
                        }else if (data.status === 'error'){
                            container.innerHTML = '<div class="list-group-item p-3">\n' +
                                '        <div class="row align-items-start">\n' +
                                '            <div class="col mb-8pt mb-md-0">\n' +
                                '                <p class="mb-8pt text-center">\n' +
                                '                    <a href="javascript:" class="text-body">\n' +
                                '                        <strong>Aucun resultat trouvé, veuillez réessayer svp!</strong>\n' +
                                '                    </a>\n' +
                                '                </p>\n' +
                                '            </div>\n' +
                                '        </div>\n' +
                                '    </div>'
                            feedback.classList.add('feedback-none')
                            return null;
                        }
                        feedback.classList.add('feedback-none')
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        feedback.classList.add('feedback-none')
                    }
                });
            })
        }
    }
}
