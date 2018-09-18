/**
 * Created by pablo on 08/07/16.
 */

var Cart = {

    settings: {
        addToCartUrl: BASE_URL + "/productos/addToCart",
        removeItemFromCartUrl: BASE_URL + "/productos/removeItemFromCart",
        updateCantidadUrl: BASE_URL + "/productos/updateCantidad"
    },
    cartRightControlList: $('.product-controls-list').find('.cart'),
    shoppingCartBox: $('.shoppingcart-box-desktop'),
    shoppingCartBoxMobile: $('.shoppingcart-box-mobile'),
    menuCartItemsCount: $('.menuCartItemsCount'),
    menuCartTotal: $('.menuCartTotal'),

    init: function() {
        var self = this;

        self.loadInteractions();
        self.checkCart();
    },

    checkCart: function() {
        var self = this;

        if ($('.cart-item-row').length == 0) {
            $('.shopping_cart_empty').removeClass('hidden');
            $('.shopping_cart_detail').addClass('hidden');
        } else {
            $('.shopping_cart_empty').addClass('hidden');
            $('.shopping_cart_detail').removeClass('hidden');
        }
    },

    loadInteractions: function() {
        var self = this;

        /*$(document).on('click', '.cart', function(e) {
            e.preventDefault();

            var $this = $(this);
            var categoria_id = $this.data('categoria');
            var producto_id = $this.data('producto');

            self.addToCart(categoria_id, producto_id)
                .done(function(data){
                    //console.log(data);
                    Commons.notify($, 'El producto fue agregado al carrito!', 'success');
                });
        });*/

        $(document).on('click', '.shopping_cart .cart-add-one', function(e) {
            e.preventDefault();

            self.processCartQuantity($(this), 1);
        });

        $(document).on('click', '.shopping_cart .cart-remove-one', function(e) {
            e.preventDefault();

            self.processCartQuantity($(this), -1);
        });

        $(document).on('click', '.detail-add-to-cart', function(e) {
            e.preventDefault();

            var $this = $(this);
            var categoria_id = $this.data('categoria');
            var producto_id = $this.data('producto');
            var cantidad = $('.detail-input-cantidad').is(':visible') ? $('.detail-input-cantidad').val() : 1;

            debugger;
            if (categoria_id == '' || producto_id == '') { //|| cantidad <= 0
                bootbox.alert("Hay un error en el formulario. Por favor, recarga la página.");
                return false;
            } else {
                self.addToCart(categoria_id, producto_id, cantidad);
            }
        });

        $(document).on('click', '.shopping_cart .remove-button', function(e) {
            e.preventDefault();

            var $this = $(this);
            var categoria_id = $this.data('categoria');
            var producto_id = $this.data('producto');

            bootbox.confirm("Estás seguro de eliminar el item del carrito?", function(result) {
                if (result) {
                    self.removeItemFromCart(categoria_id, producto_id)
                        .done(function(data){
                            $this.parents('.cart-item-row').remove();
                            if ($('.shopping_cart').find('.cartTotal').length) {
                                $('.shopping_cart').find('.cartTotal').html(data.total)
                            }
                            self.checkCart();
                        });
                }
            });
        });

        $(document).on('change', '.cart-item-cantidad', function(e) {
            e.preventDefault();

            var $this = $(this);
            var categoria_id = $this.data('categoria');
            var producto_id = $this.data('producto');
            var cantidad_original = $this.data('cantidad');
            var cantidad = $this.val();

            if (cantidad < 1) {
                $this.val(cantidad_original);
                return false;
            }

            self.updateCantidad(categoria_id, producto_id, cantidad)
                .done(function(data) {
                    if ($('.shopping_cart').find('.cartTotal').length) {
                        $('.shopping_cart').find('.cartTotal').html(data.total)
                    }
                    Commons.notify($, 'El producto fue actualizado!', 'success');
                });

        });

    },

    addToCart: function(categoria_id, producto_id, cantidad) {
        var self = this;
        var defer = new $.Deferred();
        cantidad = cantidad && cantidad != 0 && cantidad != '0' ? cantidad : 1;

        debugger;

        Commons.showLoader();
        defer = $.ajax({
            url: self.settings.addToCartUrl,
            type: 'POST',
            dataType: 'json',
            data: {categoria_id: categoria_id, producto_id: producto_id, cantidad: cantidad},
            parseError: true
        }).done(function(data){
            self.shoppingCartBox.html(data.menuCart);
            self.shoppingCartBoxMobile.html(data.menuCartMobile);
            self.menuCartItemsCount.html(data.itemsCount);
            self.menuCartTotal.html(data.total);
            Commons.notify($, 'El producto fue agregado al carrito!', 'success');
        })
        .fail(function(jqXHR, status, errorMSG){
            //console.log(jqXHR, status, errorMSG);
            Commons.notify($, 'Hubo un error. Vuelva a intentar.', 'error');
        })
        .always(function() {
            Commons.hideLoader();
        });

        return defer;
    },

    updateCantidad: function(categoria_id, producto_id, cantidad) {
        var self = this;
        var defer = new $.Deferred();
        cantidad = cantidad ? cantidad : 1;

        Commons.showLoader();
        defer = $.ajax({
            url: self.settings.updateCantidadUrl,
            type: 'POST',
            dataType: 'json',
            data: {categoria_id: categoria_id, producto_id: producto_id, cantidad: cantidad},
            parseError: true
        })
        .done(function(data){
            self.shoppingCartBox.html(data.menuCart);
            self.shoppingCartBoxMobile.html(data.menuCartMobile);
            self.menuCartItemsCount.html(data.itemsCount);
            self.menuCartTotal.html(data.total);
        })
        .fail(function(jqXHR, status, errorMSG){
            //console.log(jqXHR, status, errorMSG);
            Commons.notify($, 'Hubo un error. Vuelva a intentar.', 'error');
        })
        .always(function() {
            Commons.hideLoader();
        });

        return defer;
    },

    processCartQuantity: function (element, cantidad) {
        var self = this;
        var $this = element;
        var categoria_id = $this.data('categoria');
        var producto_id = $this.data('producto');
        cantidad = cantidad ? cantidad : 1;

        if ($this.parent().find('.cart-item-cantidad').val() == 0 && cantidad < 0) {
            return false;
        }

        self.addToCart(categoria_id, producto_id, cantidad)
            .done(function(data){
                var cantidad_actual = parseInt($this.parent().find('.cart-item-cantidad').val()) + cantidad;
                var unitario = parseInt($this.parents('.cart-item-row').find('.cart-item-unitario-h').val());
                var total = cantidad_actual * unitario;
                $this.parents('.cart-item-row').find('.cart-item-total').text(Commons.formatPrice(total));
                $this.parent().find('.cart-item-cantidad').val(cantidad_actual);
                if ($('.shopping_cart').find('.cartTotal').length) {
                    $('.shopping_cart').find('.cartTotal').html(data.total);
                }
                if (cantidad_actual == 0) {
                    $this.parents('.cart-item-row').fadeOut(function(){
                        var $this = $(this);
                        $this.remove();
                        self.checkCart();
                        Commons.notify($, 'El producto fue removido del carrito!', 'success');
                    });
                }
                Commons.notify($, 'El producto fue actualizado!', 'success');
            });
    },

    removeItemFromCart: function(categoria_id, producto_id) {
        var self = this;
        var defer = new $.Deferred();

        Commons.showLoader();
        defer = $.ajax({
            url: self.settings.removeItemFromCartUrl,
            type: 'POST',
            dataType: 'json',
            data: {categoria_id: categoria_id, producto_id: producto_id},
            parseError: true
        })
        .done(function(data){
            self.shoppingCartBox.html(data.menuCart);
            self.shoppingCartBoxMobile.html(data.menuCartMobile);
            self.menuCartItemsCount.html(data.itemsCount);
            self.menuCartTotal.html(data.total);
            self.checkCart();
            Commons.notify($, 'Producto eliminado del carrito!', 'success');
        })
        .fail(function(jqXHR, status, errorMSG){
            //console.log(jqXHR, status, errorMSG);
            Commons.notify($, 'Hubo un error y el producto no fue removido del carrito.', 'error');
        })
        .always(function() {
            Commons.hideLoader();
        });

        return defer;
    }
};

$(function(){
    Cart.init();
});