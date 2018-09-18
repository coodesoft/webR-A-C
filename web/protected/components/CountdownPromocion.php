<?php
/**
 * Created by PhpStorm.
 * User: pablo
 * Date: 12/11/16
 * Time: 13:24
 */
class CountdownPromocion extends CWidget
{
    public $extraClass;
    public $promocion;

    public function run()
    {
        $this->render('CountdownPromocion',
            array(
                'extraClass' => $this->extraClass,
                'promocion' => $this->promocion
            )
        );
    }
}