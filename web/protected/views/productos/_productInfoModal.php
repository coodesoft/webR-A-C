<div class="modal fade" id="myProductInfoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-xlg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <br>
      </div>
      <div class="modal-body">
        
        <div class="row">
          <div class="col-md-6">
            <img src="<?php echo Yii::app()->baseUrl.'/../repository/'.$imgPredet ?>" class="img-responsive imgDestacada" />
          </div>
          <div class="col-md-6">
            <h3 class="modal-title nombreProd" id="myModalLabel" data-nombre="<?php echo $model->nombre ?>"><?php echo $model->nombre ?></h3>
            <?php if($model->descripcion != ""): ?>
            <br>
            <h6 class="modal-title">Descripción</h6>
            <?php echo $model->descripcion; ?>
            <?php endif ?>

            <?php if($model->numeracion != ""): ?>
            <br><br>
            <h6 class="modal-title">Numeración</h6>
            <?php echo $model->numeracion; ?>
            <?php endif ?>

            <?php if(count($model->fotos)): ?>
            <br><br>
            <h6 class="modal-title">Colores</h6>
            <?php foreach ($model->fotos as $foto){ ?>
              <?php
              $bg = $foto->foto != "" ? "url('" . Yii::app()->baseUrl . '/../repository/' . $foto->foto . "') scroll center center / contain no-repeat" : "#" . $foto->color->hexa;
              ?>
              <div class="colorRecuadro" data-foto="<?php echo $foto->foto ?>" data-hexa="<?php echo $foto->color->hexa ?>" style="background: <?php echo $bg; ?>"><span><?php echo $foto->color->etiqueta ?></span></div>
            <?php } ?>
            <?php endif ?>
          </div>
        </div>
        
        
        <div class="clearfix"></div>
      </div>     
    </div>
  </div>
</div>

<script type="text/javascript">
$(function(){
  var $nombre = $('.nombreProd');
  var titulo = $('.nombreProd').data('nombre');
  var textoColor = $('.colorRecuadro').eq(0).text();

  $nombre.text(titulo + ' ' + textoColor);

  $(".colorRecuadro").click(function(){
    var $this = $(this);
    var $nombre = $('.nombreProd');
    var titulo = $('.nombreProd').data('nombre');
    var textoColor = $this.text();

    $nombre.text(titulo + ' ' + textoColor);

    if($this.data('foto') == ""){
      return false;
    }

    $(".imgDestacada").attr({
      src: '<?php echo Yii::app()->baseUrl . "/../repository/" ?>' + '/' + $this.data('foto'),
    });

  });
});
</script>