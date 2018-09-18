<!-- Shop by categories -->
<section>
    <h3>Categorías</h3>
    <ul class="expander-list">
        <?php
        foreach ($menu as $item) {
            $link = $item['hijos'] ? '#' : Yii::app()->createAbsoluteUrl('/productos/categoria/' . $item['categoria_id']);
            ?>
            <li>
                <span class="name">
                    <?php if ($item['hijos']) { ?>
                    <span class="expander">-</span>
                    <?php } ?>
                    <a href="<?php echo $link ?>"><?php echo $item['etiqueta_web'] ?></a>
                </span>
            <?php
            if ($item['hijos']) {
                ?>
                <ul>
                    <?php
                    foreach ($item['hijos'] as $n1) {
                        $link = Yii::app()->createAbsoluteUrl('/productos/categoria/' . $n1['categoria_id']);
                        ?>
                        <li>
                            <span class="name">
                                <?php if ($n1['hijos']) { ?>
                                <span class="expander">-</span>
                                <?php } ?>
                                <a href="<?php echo $link ?>"><?php echo $n1['etiqueta_web'] ?></a>
                            </span>
                        <?php
                        if ($n1['hijos']) {
                            ?>
                            <ul>
                                <?php
                                foreach ($n1['hijos'] as $n2) {
                                    $link = Yii::app()->createAbsoluteUrl('/productos/categoria/' . $n2['categoria_id']);
                                    ?>
                                    <li>
                                        <span class="name">
                                            <a href="<?php echo $link ?>"><?php echo $n2['etiqueta_web'] ?></a>
                                        </span>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                            <?php
                        }
                        ?>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
                <?php
            }
            ?>
            </li>
            <?php
        }
        ?>
    </ul>
</section>
<!-- //end Shop by categories -->