<?php
/**
 * Created by PhpStorm.
 * User: pablo
 * Date: 03/07/16
 * Time: 10:47
 */

class Commons
{
    /**
     * @return array
     */
    public static function getCategoriasForTree()
    {
        $categorias = Categorias::model()->findAll(array('order' => 'categoria_padre_id, codigo'));
        $arr = array();
        foreach ($categorias as $categoria) {
            array_push($arr, $categoria->attributes);
        }

        return $arr;
    }

    /**
     * @param array $elements
     * @param array $selected
     * @param int $parentId
     * @param bool $all_levels
     * @param int $max_levels
     * @param int $level
     *
     * @return array
     */
    public static function buildTree(array $elements, array $selected, $parentId = 0, $all_levels = false, $max_levels = 0, $level = 0)
    {
        $branch = array();

        foreach ($elements as $element) {
            if ($element['categoria_padre_id'] == $parentId && ($all_levels || $level <= $max_levels)) {
                $element['level'] = $level;
                $children = self::buildTree($elements, $selected, $element['categoria_id'], $all_levels, $max_levels, $level+1);
                if ($children) {
                    $element['hijos'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }

    /**
     * Devuelvo el precio formateado
     * @param $precio
     * @return float
     */
    public static function formatPrice($precio, $decimals = 0,$thousandSeparator = '.') {
        return number_format($precio, $decimals, ',', $thousandSeparator);
    }

    /**
     * Devuelvo la imagen cropeada
     * @param $path
     * @param int $width
     * @param int $height
     * @return string
     */
    public static function image($path, $width = 270, $height = 328, $zc = 1, $web=true)
    {

        $upLevel = '/../';
        if (!$web)
            $upLevel = '/../../';
        if (!file_exists(Yii::app()->basePath . $upLevel . $path)) {
            $path = 'repository/default-prod.png';
        }

        return Yii::app()->baseUrl . '/assets/timthumb.php?src='.$path.'&w='.$width.'&h='.$height.'&zc='.$zc;
    }

    public static function generateClaveCart($length)
    {
        $c= "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        srand((double)microtime()*1000000);
        for ($i=0; $i<$length; $i++) {
            $rand .= $c[rand()%strlen($c)];
        }

        return $rand;
    }

    public static function getUserIP()
    {
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif(filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        return $ip;
    }

    public static function dump()
    {
        echo "<pre>";
        for ($i = 0; $i < func_num_args(); $i++) {
            var_dump(func_get_arg($i));
            echo "<br>\n";
        }
        die;
    }

    public static function renderError($errors){
      return reset($errors )[0] ;
    }

    public static function roundMonto($number){
        return round($number,2);
    }

}