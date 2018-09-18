<?php
class EmptyShopCart extends CWidget {
  public $class = '';

  public function run() {
      echo '<div class="'; echo $this->class; echo '">
          <div class="col-sm-9 col-md-9 col-lg-9">
              <div class="icon-circle-sm active"><span class="icon icon-cart"></span></div>
              <h5>No tienes items en el carrito</h5>
              <p>Empieza a disfrutar de nuestras promociones ahora mismo.</p>
          </div>
          <div class="col-lg-3">
              <a class="btn btn-mega btn-lg" href="'; echo Yii::app()->createAbsoluteUrl('/'); echo '">IR A HOME</a>
          </div>
          <h4 class="text-center"></h4>
      </div>';
  }

}
