<div class="item">
    <div class="product-preview">
        <?php if ($imagen != '') {?>
        <div class="preview animate scale">
            <a href="<?= $urlDetail ?>" class="preview-image">
                <img
                    class="img-responsive animate scale"
                    src="<?php echo Commons::image('repository/'.$imagen, 270, 328); ?>"
                    width="270" height="328" alt="">
            </a>
        </div>
        <?php } ?>
        <h3 class="title">
            <a href="<?= $urlDetail ?>">
                <?= $etiqueta ?>
            </a>
        </h3>
    </div>
</div>
