

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
        $('.articles-quantity').change(app.updateCartLine)
        $('.to-top-btn').click(app.scrollToTop)
    },

    addToCart: function (e) {

        M.toast({
            html: 'Cat shopped !', classes: 'rounded'})
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

        rowToDelete.remove();

        $.ajax(
            {
                url: Routing.generate('cart_remove'),
                method: "POST",
                dataType: "json",
                data: JSON.stringify(articleId),
            }).done(function (response) {
                app.updateTotalCart();
            }).fail(function (jqXHR, textStatus, error) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(error);
            });
    },

    updateCartLine: function (e) {
        let quantity = $(this).val();
        let unitPrice = $(this).parent().prev('.item-price').text();
        let unitPriceToFloat = parseFloat(unitPrice.replace(' €', ''));
        let currentTotal = $(this).parent().next('.total-item').text()
        let currentToFloat = parseFloat(currentTotal.replace(' €', ''));
        let newTotalLine = unitPriceToFloat * quantity;
        
        $(this).parent().next('.total-item').html(newTotalLine + ' €');

        app.updateTotalCart();

    },

    updateTotalCart: function () {
        let currentTotal = $('.total-item'),
            newTotalArray = []

        currentTotal.each(function (index) {
            newTotalArray[index] = $(this).text().replace(' €', '');
        });

        let total = 0;
        let newArray = $.map(newTotalArray, function (i) {
            return parseFloat(i, 10);
        });

        total = newArray.reduce(function (a, b) {
            return a + b
        });

        // Replace net total
        $('.net-total').html(total + ' €');
    },

    scrollToTop: function (params) {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }
}

document.addEventListener('DOMContentLoaded', app.init)
