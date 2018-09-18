/**
 * Created by pablo on 10/07/16.
 */

var Checkout = {

    init: function() {
        var self = this;

        self.loadInteractions();
    },

    obtenerCostoEnvio: function () {
        var value = $('#collapseFour .formas_envio .radio input:checked').val();
        if ( value == 1 )
            return parseFloat($('#costo_envio').val());
        if ( value == 2 )
            return 0;
        if ( value == 3 )
            return parseFloat($('#costo_envio_rapido').val());
    },

    obtenerMedioPago: function () {
        var value = $('#collapseOne .radio input:checked').val();
        //Mercado pago
        if (value == 1)
            mediopago = 'mercadopago';
        //Todo Pago
        if (value == 2)
             mediopago ='todopago';
        //Transferencia
        if (value == 3)
             mediopago ='transferencia';

         return mediopago;
    },

    refreshPreciosCarrito: function(){
        var self = this;
        var mediopago = self.obtenerMedioPago();
        
        var total = 0;
        
        $('.cart-item-row').each(function (index){
            _this = $(this);
            if (_this.find('.cart-item-unitario').length > 0 ) {

                var precioUnitario = parseFloat(_this.find('.cart-item-unitario-'+mediopago).val());
                _this.find('.cart-item-unitario').html(Commons.formatPrice(precioUnitario));
                var cantidad = parseInt(_this.find('.quantity-control a').html());
                //Precio unitario ya viene formateado desde el input
                var subTotal = precioUnitario*cantidad;

                _this.find('.cart-item-total').html(Commons.formatPrice(subTotal));
                total = total + subTotal;
            }
        });

        
        $('.cartTotal').html(Commons.formatPrice(total+self.obtenerCostoEnvio())) ;
    	Commons.notify($,'Se actualizo el precio del pedido.', 'info');
    },

    refreshFormasEnvio: function(){
        var localidad_entrega = $('.localidad_entrega_id').val();
        
        //Si es rosario habilito envio rapido
        if (localidad_entrega == 9978) {
        	$('.formas_envio .radio input[value=3]').parent().parent().show();
		}
        else {
        	$('.formas_envio .radio input[value=3]').parent().parent().hide();
        	$('.formas_envio .radio input[value=1]').prop( "checked", true );
        }
    },

    refreshEnvioCarrito: function(){
        var self = this;
        var texto = 'Gratis';
        var costoEnvio = self.obtenerCostoEnvio();

        if (costoEnvio != 0)
            texto = '$'+Commons.formatPrice(costoEnvio);

        $('#costo_envio_text').html(texto) ;
        self.refreshPreciosCarrito();
        Commons.notify($,'Se actualizo el costo de envio.', 'info');
    },

    loadInteractions: function() {
        var self = this;

        $(document).on('click', '.selectFacturacion', function(e) {
            e.preventDefault();

            var $this          = $(this);
            var id             = $this.data('id');
            var identificacion = $this.data('identificacion');
            var fullname       = $this.data('fullname');
            var domicilio      = $this.data('domicilio');
            var provincia      = $this.data('provincia');
            var ciudad         = $this.data('ciudad');
            var cpostal        = $this.data('cpostal');

            $('.facturacion_id').val(id);
            $('.facturacion_identificacion').text(identificacion);
            $('.facturacion_fullname').text(fullname);
            $('.facturacion_domicilio').text(domicilio);
            $('.facturacion_provincia').text(provincia);
            $('.facturacion_ciudad').text(ciudad);
            $('.facturacion_cpostal').text(cpostal);

            Commons.notify($, 'Nueva dirección de facturación definida.', 'success');
        });

        //
        self.refreshFormasEnvio();

        $(document).on('click', '.selectEntrega', function(e) {
            e.preventDefault();

            var $this          = $(this);
            var id             = $this.data('id');
            var identificacion = $this.data('identificacion');
            var fullname       = $this.data('fullname');
            var domicilio      = $this.data('domicilio');
            var provincia      = $this.data('provincia');
            var ciudad         = $this.data('ciudad');
            var cpostal        = $this.data('cpostal');
            var localidad_entrega_id        = $this.data('localidad_id');

            $('.localidad_entrega_id').val(localidad_entrega_id);
            $('.entrega_id').val(id);
            $('.entrega_identificacion').text(identificacion);
            $('.entrega_fullname').text(fullname);
            $('.entrega_domicilio').text(domicilio);
            $('.entrega_provincia').text(provincia);
            $('.entrega_ciudad').text(ciudad);
            $('.entrega_cpostal').text(cpostal);

            self.refreshFormasEnvio();
            Commons.notify($, 'Nueva dirección de entrega definida.', 'success');
        });

        $(document).on('ready',function(){
            $('#collapseOne .radio input:first').prop( "checked", true );
        });

        //Cambio de precios
        $(document).on('change','#collapseOne .radio',function(){
            var value = $('#collapseOne .radio input:checked').val();
            self.refreshPreciosCarrito();
        });

        $(document).on('ready',function(){
            $('#collapseFour .formas_envio .radio input:first').prop( "checked", true );
        });

        $(document).on('ready',function(){
            $('#collapseFour .bodegas').hide();
            $('#collapseFour .bodegas .radio input:first').prop( "checked", true );
        });

        //Costo envio
        $(document).on('change','#collapseFour .radio',function(){
            var value = $('#collapseFour .formas_envio .radio input:checked').val();
            //Envio a domicilio
            if (value == 1){
                $('#collapseFour .bodegas').hide();
                self.refreshEnvioCarrito();
            }
            //Retiro por sucursal
            if (value == 2){
                $('#collapseFour .bodegas').show();
                self.refreshEnvioCarrito();
            }
            //Envio rapido
            if (value == 3){
                $('#collapseFour .bodegas').hide();
                self.refreshEnvioCarrito();
            }

        });

        //Popup transferencia
        $(document).on('click','.checkout form input.btn-mega',function(e){
            e.preventDefault();
            var value = $('#collapseOne .radio input:checked').val();
            var form =  $('.checkout form');
            //Transferencia
            if (value == 3){
                //Abro popup transferencia
                $.fancybox(
                    '<h2 class="bg-danger">IMPORTANTE!!!</h2>'
                    +'<p>Requisitos para realizar una compra por transferencia bancaria o deposito.</p>' 
                    +'<p>En caso de retiro en sucursal:</p>' 
                    + '<ul>'
                    + '<li>Por favor NO PRESENTARSE antes de la recepción via mail de la confirmación de la compra.</li>'
                    + '<li>Presentar comprobante original de deposito o tranferencia bancaria.</li>'
                    + '</ul>'
                    +'<p>En caso de envio a domicilio:</p>' 
                    + '<ul>'
                    + '<li>Enviar via mail a <a href="mailto:webonline@rosarioalcosto.com.ar">webonline@rosarioalcosto.com.ar</a> el comprobante de transferencia o deposito bancario. También nos puede enviar una foto del mismo por whatsapp al 3416656995. Aclarar nombre y apellido de quien realizo la operación</li>'
                    + '</ul>'
                    + '<input style="margin-right:5px;font-weight: bold;" type="checkbox" class="checkbox_transferencia">He leido los requisitos y estoy de acuerdo</input><br/>'
                    + '<button style="display:block;margin: 0 auto;" id="aceptar_transferencia" disabled="disabled" class="btn btn-mega btn-md" type="submit">Aceptar</button>',
                    {
                        maxWidth    : 800,
                        fitToView   : false,
                        width       : '70%',
                        height      : 'auto',
                        autoSize    : false,
                        'afterLoad' : function() {
                             $(document).on('change','.checkbox_transferencia',function(){
                                if ($(this).is(':checked'))
                                    $('#aceptar_transferencia').removeAttr('disabled');
                                else
                                    $('#aceptar_transferencia').attr('disabled','disabled');

                             });

                             $(document).on('click','#aceptar_transferencia',function(){
                                form.submit();
                             });
                                
                            },
                    }
                );
            }
            else
                form.submit();
        });


    }
};

$(function() {
    Checkout.init();
});