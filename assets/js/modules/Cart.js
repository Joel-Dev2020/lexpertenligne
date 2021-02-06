
/**
 * @Property {HTMLElement} addToCart
 * @Property {HTMLFormElement} addToCartWithQty
 * @Property {HTMLFormElement} addToCartWithQtyDetails
 * @Property {HTMLElement} deleteToCart
 */
export default class Cart {

    constructor(){
        alertify.set('notifier','position', 'top-right');
        this.addToCart = document.querySelectorAll('.addCart')
        this.addToCartWithQty = document.querySelectorAll('.addToCartWithQty')
        this.addToCartWithQtyDetails = document.getElementById('form-addwithqty')
        this.deleteToCart = document.querySelectorAll('.deleteCart')
        this.add()
        addwithqty(this.addToCartWithQty)
        //addwithqtyDetails(this.addToCartWithQtyDetails)
        this.update()
        this.delete()
    }

    add() {
        this.addToCart.forEach(link => {
            link.addEventListener('click', e => {
                e.preventDefault()
                const url = e.currentTarget;
                let countCart = $(".cart_count");
                let floatCart = $("#floatCart");
                let floatCartWrap = $(".mini_cart");
                let feedback = $(".off_canvars_overlay ");
                $.ajax({
                    type: "GET",
                    url: url,
                    beforeSend: function () {},
                    success: function (response) {
                        if(response.resultat === "OK"){
                            countCart.empty().append(response.countCart);
                            floatCart.empty().append(response.floatCart);
                            floatCartWrap.addClass('active');
                            feedback.addClass('active');
                            alertify.alert('Ajout de produit','Le produit a été ajouté au panier', function(){ alertify.success('Produit ajouté au panier') });
                        }else if(response.resultat === "NONOK"){
                            alertify.notify('Une erreur survenue', 'error');
                        }else{
                            alertify.notify('Une erreur survenue', 'error');
                        }
                    },
                    error: function(error) {
                        if(error.status === 301){
                            alertify.notify('Stock du produit atteint', 'error');
                        }
                    },
                });
            })
        })
    }

    update() {
        $('.updateQty').on('submit',function (e) {
            e.preventDefault()
            const url = $(this).attr('action');
            let floatCart = $("#floatCart");
            let tableCart = $("#tableCart");
            let data = $(this).serialize();
            $.ajax({
                type: "GET",
                url: url,
                data: data,
                beforeSend: function () {},
                success: function (response) {
                    if(response.resultat === "OK"){
                        if (response.floatCart){
                            floatCart.empty().append(response.floatCart);
                        }
                        if (response.tableCart){
                            tableCart.empty().append(response.tableCart);
                        }
                        alertify.alert('Information','La quantité du produit a été modifiée', function(){ alertify.success('Quantité du produit modifiée') });
                    }else if(response.resultat === "NONOK"){
                        alertify.notify('Une erreur survenue', 'error');
                    }else{
                        alertify.notify('Une erreur survenue', 'error');
                    }
                },

                error: function(error) {
                    if(error.status === 301){
                        alertify.notify('Stock du produit atteint', 'error');
                    }
                },
            });
        })
    }

    delete() {
        this.deleteToCart.forEach(link => {
            link.addEventListener('click', e => {
                e.preventDefault()
                const url = e.currentTarget;
                let countCart = $("#countCart");
                let floatCart = $("#floatCart");
                let tableCart = $("#tableCart");
                alertify.confirm('Suppression de produit', 'Etes vous sûr de supprimer ce produit du panier?', function () {
                        $.ajax({
                            type: "GET",
                            url: url,
                            beforeSend: function () {},
                            success: function (response) {
                                if(response.resultat === "OK"){
                                    countCart.empty().append(response.countCart);
                                    floatCart.empty().append(response.floatCart);
                                    tableCart.empty().append(response.tableCart);
                                    alertify.notify('Produit supprimé au panier', 'success');
                                }else if(response.resultat === "NONOK"){
                                    alertify.notify('Une erreur survenue', 'error');
                                }else{
                                    alertify.notify('Une erreur survenue', 'error');
                                }
                            },
                            complete: function(response) {},
                        });
                    }
                    , function () {
                        alertify.error('Action annulée')
                    });
            })
        })
    }
}

function addwithqty(form) {
    form.forEach(link => {
        link.addEventListener('submit', e => {
            e.preventDefault()
            const url = e.currentTarget.getAttribute('action');
            let countCart = $("#countCart");
            let floatCart = $("#floatCart");
            let floatCartWrap = $(".mini_cart");
            let feedback = $(".off_canvars_overlay ");
            let form = new FormData(e.currentTarget);
            $.ajax({
                type: "POST",
                url: url,
                data: {qty: form.get('qty')},
                beforeSend: function () {},
                success: function (response) {
                    if(response.resultat === "OK"){
                        countCart.empty().append(response.countCart);
                        floatCart.empty().append(response.floatCart);
                        alertify.success('Produit ajouté au panier');
                        floatCartWrap.addClass('active');
                        feedback.addClass('active');
                    }else if(response.resultat === "NONOK"){
                        alertify.notify('Une erreur survenue', 'error');
                    }else{
                        alertify.notify('Une erreur survenue', 'error');
                    }
                },
                error: function(error) {
                    if(error.status === 301){
                        alertify.notify('Stock du produit atteint', 'error');
                    }
                },
            });
        })
    })
}

/*function addwithqtyDetails(form) {
    form.on('submit',function (e) {
        e.preventDefault();
        let url = $(this).attr('action');
        let countCart = $("#countCart");
        let floatCart = $("#floatCart");
        let form = new FormData(e.currentTarget);
        $.ajax({
            type: "POST",
            url: url,
            data: {qty: form.get('qty')},
            beforeSend: function () {},
            success: function (response) {
                if(response.resultat === "OK"){
                    countCart.empty().append(response.countCart);
                    floatCart.empty().append(response.floatCart);
                    alertify.alert('Ajout de produit','Le produit a été ajouté au panier', function(){ alertify.success('Produit ajouté au panier') });
                }else if(response.resultat === "NONOK"){
                    alertify.notify('Une erreur survenue', 'error');
                }else{
                    alertify.notify('Veuillez sélectionner une couleur svp!', 'error');
                }
            },
            error: function(error) {
                if(error.status === 301){
                    alertify.notify('Stock du produit atteint', 'error');
                }
            },
        });
    })
}*/
