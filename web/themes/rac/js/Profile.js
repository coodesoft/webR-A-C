/**
 * Created by pablo on 10/07/16.
 */

var Profile = {

    init: function() {
        var self = this;

        self.loadInteractions();
    },
    loadInteractions: function() {
        var self = this;

        $('.toggleDetailPedidos').on('click', function(e) {
            e.preventDefault();

            var $this = $(this);
            var id = $this.data('id');

            $('.pedidoDetailRow_' + id).slideToggle(0);
            $this.parent().find('.toggleDetailPedidos').toggle();
        });

        $(document).on('change', '#ClientesDirecciones_provincia_id', function(e) {
            e.preventDefault();

            var $this = $(this);
            var provincia_id = $this.val();

            if(provincia_id) {
                $.ajax({
                    url: BASE_URL + '/direcciones/getCiudadesByProvincia',
                    data: {
                        provincia_id: provincia_id
                    },
                    dataType: "JSON",
                    type: "POST",
                    beforeSend: function (objeto) {
                        Commons.showLoader();
                        $('#ClientesDirecciones_ciudad_id').val('').prop("disabled", false);
                    },
                    complete: function (objeto, exito) {
                        Commons.hideLoader();
                    },
                    success: function (response, status) {
                        //$("#Proveedores_ciudad_id").select2('data',response.ciudades);
                        var data = response.ciudades;
                        $('#ClientesDirecciones_ciudad_id')
                            .empty()
                            .append(
                                $.map(data, function(v, i){
                                    return $('<option>', { val: i, text: v });
                                })
                            );
                        return false;
                    },
                    error: function (response, status) {
                        return false;
                    }
                });
            }else{
                $('#ClientesDirecciones_ciudad_id').val('').prop("disabled", true);
            }
        });

    }
};

$(function() {
    Profile.init();
});