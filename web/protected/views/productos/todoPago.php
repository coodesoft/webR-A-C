<?php
/**
 * Created by PhpStorm.
 * User: pablo
 * Date: 10/07/16
 * Time: 00:53
 */
echo "<pre>";

$tp = new TodoPago();

$form = [];
foreach ($items as $item) {
    if ($item->producto) {
        $form['descripciones'][] = TextHelper::character_limiter(str_replace('#', '', $item->producto['descripcion']), 50) ?: 'Descripción del producto no disponible.';
        $form['unitarios'][] = $item->producto->precio[ProductosPrecios::PRECIO_ONLINE_ID]['precio'];
        $form['cantidades'][] = $item->cantidad;
        $form['etiquetas'][] = $item->producto->etiqueta;
        $form['ids'][] = $item->producto->categoria->categoria_id.'_'.$item->producto->producto_id;
    }
}

var_dump(implode('#', $form['unitarios']));
var_dump(implode('#', $form['cantidades']));
var_dump(implode('#', $form['etiquetas']));
var_dump(implode('#', $form['ids']));
var_dump(implode('#', $form['descripciones']));
var_dump($items['total']);
var_dump($tp);

?>
<script>
//location.href = 'sarasa';
</script>

<form action="">

</form>