<section>
    <h3>Ofertas</h3>
    <!-- Products list -->
    <div class="">
        <div class="product-carousel">
            <?php
            foreach ($promociones as $oferta)
              $this->widget('ProductCardMin',['item'=>$oferta]);

            foreach ($ofertas as $oferta)  //comprar btn false
              $this->widget('ProductCardMin',['item'=>$oferta,'btnComprarEnabled'=>false,'debug'=>true]);
            ?>
      </div>
      <!-- product view ajax container -->
      <div class="product-view-ajax-container"></div>
  </div>
</section>
