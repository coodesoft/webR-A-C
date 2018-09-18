<div class="countdown_box <?php echo $extraClass ?>">
    <div class="countdown_inner">
        <div class="title">La oferta termina en:</div>
        <div class="countdown1"
             data-year="<?php echo date('Y', strtotime($promocion->fecha_hasta)); ?>"
             data-month="<?php echo date('m', strtotime($promocion->fecha_hasta)); ?>"
             data-day="<?php echo date('d', strtotime($promocion->fecha_hasta)); ?>"
             data-hour="<?php echo date('H', strtotime($promocion->fecha_hasta)); ?>"
             data-minute="<?php echo date('i', strtotime($promocion->fecha_hasta)); ?>"
             data-second="<?php echo date('s', strtotime($promocion->fecha_hasta)); ?>"
        ></div>
    </div>
</div>