
/**
 * @Property {HTMLElement} deleteToCart
 */
export default class Products {

    constructor(){
        alertify.set('notifier','position', 'top-right');
        this.actionDelete = document.getElementById('actionDelete')
        this.delete()
    }

    delete() {
        console.log('ok')

    }
}
