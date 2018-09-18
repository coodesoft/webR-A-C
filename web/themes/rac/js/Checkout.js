/**
 * Created by pablo on 10/07/16.
 */

var Checkout = {

    init: function() {
        var self = this;

        self.loadInteractions();
    },
    loadInteractions: function() {
        var self = this;

        $(document).on('click', '.selectFacturacion', function(e) {
            e.preventDefault();

            var $this = $(this);
            var id = $this.data('id');
            var identificacion = $this.data('identificacion');
            var fullname = $this.data('fullname');
            var domicilio = $this.data('domicilio');
            var provincia = $this.data('provincia');
            var ciudad = $this.data('ciudad');
            var cpostal = $this.data('cpostal');

            $('.facturacion_id').val(id);
            $('.facturacion_identificacion').text(identificacion);
            $('.facturacion_fullname').text(fullname);
            $('.facturacion_domicilio').text(domicilio);
            $('.facturacion_provincia').text(provincia);
            $('.facturacion_ciudad').text(ciudad);
            $('.facturacion_cpostal').text(cpostal);

            Commons.notify($, 'Nueva dirección de facturación definida.', 'success');
        });

        $(document).on('click', '.selectEntrega', function(e) {
            e.preventDefault();

            var $this = $(this);
            var id = $this.data('id');
            var identificacion = $this.data('identificacion');
            var fullname = $this.data('fullname');
            var domicilio = $this.data('domicilio');
            var provincia = $this.data('provincia');
            var ciudad = $this.data('ciudad');
            var cpostal = $this.data('cpostal');

            $('.entrega_id').val(id);
            $('.entrega_identificacion').text(identificacion);
            $('.entrega_fullname').text(fullname);
            $('.entrega_domicilio').text(domicilio);
            $('.entrega_provincia').text(provincia);
            $('.entrega_ciudad').text(ciudad);
            $('.entrega_cpostal').text(cpostal);

            Commons.notify($, 'Nueva dirección de entrega definida.', 'success');
        });

    }
};

$(function() {
    Checkout.init();
});