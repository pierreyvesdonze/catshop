

var app = {

    init: function () {

        // Activate dropdown nav
        $(".dropdown-trigger").dropdown();

        /**
       * *****************************
       * L I S T E N E R S
       * *****************************
       */
        
        $('.add-to-cart-btn').click(app.addToCart);
        $('.remove-from-cart-btn').click(app.removeFromCart)
    },

    addToCart: function (e) {

        e.preventDefault();
        let articleId = $(this).data('id');
        $.ajax(
            {
                url: Routing.generate('add_to_cart'),
                method: "POST",
                dataType: "json",
                data: JSON.stringify(articleId),
            }).done(function (response) {
                console.log(response)
            }).fail(function (jqXHR, textStatus, error) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(error);
            });
    },

    removeFromCart: function (e) {
        e.preventDefault();
        let articleId = $(this).data('id');
        let rowToDelete = $(this).parent().parent();

        console.log(rowToDelete.find('.total-item').val())

        //rowToDelete.remove();

        $.ajax(
            {
                url: Routing.generate('cart_remove'),
                method: "POST",
                dataType: "json",
                data: JSON.stringify(articleId),
            }).done(function (response) {
        
            }).fail(function (jqXHR, textStatus, error) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(error);
            });
    },
}

document.addEventListener('DOMContentLoaded', app.init)
