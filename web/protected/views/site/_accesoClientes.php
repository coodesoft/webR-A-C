<div class="acceso-clientes">
    <div class="container">
		<div class="row">

            <div class="row animated"
                 data-animtype="fadeInUp"
                 data-animrepeat="0"
                 data-animspeed="1s"
                 data-animdelay="0.2s">
               
                <div class="col-md-4 col-sm-3"></div>
            
                 <div class="col-md-4 col-sm-3 animated">
                      <h1 class="h1-section-title white-text" <?php if($clientesPage){ ?>style="font-size: 16px;"<?php } ?>><br>
                        <?php 
                        if($clientesPage){
                            echo "Visualice nuestras colecciones";
                        }else{
                            echo "Acceso Clientes";
                        }
                        ?>
                 
                    <input name="" type="text" placeholder="Usuario" id="usuario" class="acceso-form">
        			<input name="" type="password" placeholder="Contraseña" id="password" class="acceso-form" style="margin-bottom:5px;">
                    <a href="javascript:;" onclick="doAccesoCliente()" class="button large button-main">
                        <h4 style="color:#ffffff !important; padding-top:7px;">Ingresar</h4>
                    </a>
                    <br>
                    <?php 
                    if(!$clientesPage){
                        ?>
                        <h6 style="text-align:center; color:#fff;"><a href="<?php echo Yii::app()->createAbsoluteUrl('/site/clientes') ?>" style="color:#fff;">Solicitar contraseña</a></h6></h1>
                        <?php
                    }
                    ?>
                </div>
               
                <div class="col-md-4 col-sm-3 animated"></div>
                   
            </div>
        </div>

    </div>
</div>

<script type="text/javascript">
function doAccesoCliente(){
    var usuario = $("#usuario").val();
    var password = $("#password").val();

    var hasError = false;
    var strError = "";

    if(!usuario){
        hasError = true;
        strError += "Ingrese el nombre de usuario.\n";
    }

    if(!password){
        hasError = true;
        strError += "Ingrese el password.\n";
    }

    if(hasError) {
        alert("Tiene los siguientes errores:\n\n"+strError);
        return false;
    }

    $.ajax({
        url: '<?php echo Yii::app()->createAbsoluteUrl('/clientes/doAccesoCliente'); ?>',
        data: {usuario:usuario, password:password},
        type: "POST",
        dataType: "JSON",
        success: function(data){
            if (data.ok) {
                location.href = '<?php echo Yii::app()->createAbsoluteUrl("/productos") ?>';
            }else{
                alert("Los datos ingresados son inválidos. Vuelva a intentarlo.");
                return false;
            }
        },
        error:  function (response, status) {
          alert(response.responseText);return false;
        },
    });

}
</script>