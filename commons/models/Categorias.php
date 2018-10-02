<?php

/**
 * This is the model class for table "categorias".
 *
 * The followings are the available columns in table 'categorias':
 * @property integer $categoria_id
 * @property integer $categoria_padre_id
 * @property string $codigo
 * @property integer $tiene_atributos
 * @property integer $tiene_numeros_serie
 * @property string $nombre
 * @property string $slug
 * @property integer $iva_id
 */
class Categorias extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Categorias the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'categorias';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('nombre', 'required'),
            array('codigo','unique'),
            array('categoria_padre_id, tiene_atributos, tiene_numeros_serie, iva_id, es_accesorios', 'numerical', 'integerOnly'=>true),
            array('codigo', 'length', 'max'=>50),
            array('nombre, slug, etiqueta_web', 'length', 'max'=>100),
            array('imagen', 'length', 'max'=>255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('categoria_id, categoria_padre_id, codigo, imagen, tiene_atributos, tiene_numeros_serie, nombre, slug, iva_id, es_accesorios,etiqueta_web', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'padre' => array(self::BELONGS_TO, 'Categorias', 'categoria_padre_id'),
            'hijos' => array(self::HAS_MANY, 'Categorias', 'categoria_padre_id'),
            'caracteristicas' => array(self::HAS_MANY, 'CategoriasCampos', 'categoria_id'),
            'iva' => array(self::BELONGS_TO, 'Iva', 'iva_id'),
        );
    }

    /**
     * Función para formatear las fechas.
     * Todo lo que sea de tipo date y datetime serán formateados para ser legibles y antes de ser validados para almacanarse
     */
    public function behaviors(){
        return array(
            'PFechas' => array(
                'class' => 'ext.fechas.PFechas',
            ),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'categoria_id' => '#',
            'categoria_padre_id' => 'Categoría superior',
            'codigo' => 'Código',
            'tiene_atributos' => 'Tiene características?',
            'tiene_numeros_serie' => 'Tiene nº de serie?',
            'nombre' => 'Nombre',
            'slug' => 'Slug',
            'iva_id' => 'IVA',
            'es_accesorios' => 'Categoría accesorios?',
            'imagen' => 'Imagen',
            'etiqueta_web' => 'Etiqueta web'
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('categoria_id',$this->categoria_id);
        $criteria->compare('categoria_padre_id',$this->categoria_padre_id);
        $criteria->compare('codigo',$this->codigo,true);
        $criteria->compare('tiene_atributos',$this->tiene_atributos);
        $criteria->compare('tiene_numeros_serie',$this->tiene_numeros_serie);
        $criteria->compare('nombre',$this->nombre,true);
        $criteria->compare('slug',$this->slug,true);
        $criteria->compare('iva_id',$this->iva_id);
        $criteria->compare('es_accesorios',$this->es_accesorios);
        $criteria->compare('imagen',$this->imagen);

        return new CActiveDataProvider($this, array(
            'pagination' => array(
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
        ));
    }

    /**
     * Recupero una lista de categorias para un dropDownList
     *
     * en $excluded defino las IDs de categorías que no quiero que salgan el el resultado.
     * en $parametros defino si las categorias que voy a buscar tienen el campo tiene_atributos en 1
     *
     * @return elemento listData con las categorías.
     *
     * */
    public static function listCategorias($excluded=null, $parametros=false)
    {
        $categorias = Categorias::arrayCategorias($excluded, $parametros);

        return CHtml::listData($categorias,'categoria_id','codigoYNombre');
    }

    /**
     * Recupero una lista de categorias para un dropDownList
     *
     * en $excluded defino las IDs de categorías que no quiero que salgan el el resultado.
     * en $parametros defino si las categorias que voy a buscar tienen el campo tiene_atributos en 1
     *
     * @return elemento listData con las categorías.
     *
     * */
    public static function listCategoriasSlug($excluded=null, $parametros=false)
    {
        $categorias = Categorias::arrayCategorias($excluded, $parametros);

        return CHtml::listData($categorias,'slug','codigoYNombre');
    }

    /**
     * @Devuelve la el codigo de la categoria y el nombre todo en uno.
     * */
    public function getCodigoYNombre()
    {
        return trim(implode(' ', array($this->codigo, $this->nombre)));
    }

    /**
     * Recupero la lista de categorias
     *
     * en $excluded defino las IDs de categorías que no quiero que salgan el el resultado.
     * en $parametros defino si las categorias que voy a buscar tienen el campo tiene_atributos en 1
     *
     * @return array de categorias.
     *
     * */
    public static function arrayCategorias($excluded=null, $parametros=false)
    {
        $criteria = new CDbCriteria();
        if(isset($excluded) && count($excluded)>0)
            $criteria->addNotInCondition("categoria_id", $excluded);
        if(isset($parametros) && $parametros !== false)
            $criteria->addCondition("tiene_atributos", 1);

        $criteria->order = "codigo ASC, nombre ASC";

        return Categorias::model()->findAll($criteria);
    }

    public static function printMenu($menu, array $selected)
    {
        foreach ($menu as $op) {
            ?>
            <ul class="nav">
                <li>
                    <?php
                    $checked = in_array($op['categoria_id'], $selected) ? 'checked="checked"' : '';
                    ?>
                    <input type="checkbox" <?php echo $checked ?> data-level0="1" name="checkWebMenu" value="<?php echo $op['categoria_id'] . '_' . $op['categoria_padre_id'] ?>" data-padre="0" data-categoria="<?php echo $op['categoria_id'] ?>" />
                    <?php echo $op['nombre'] ?>
                    <?php
                    if (isset($op['hijos'])) {
                        self::calculaHijos($op, $selected);
                    }
                    ?>
                </li>
            </ul>
            <?php
        }
    }

    public static function calculaHijos($menu, $selected)
    {
        if (count($menu['hijos'])) {
            ?>
            <ul class="nav">
                <?php
                $hijos = $menu['hijos'];
                foreach($hijos as $hijo){
                ?>
                <li>
                    <?php
                    $checked = in_array($hijo['categoria_id'], $selected) ? 'checked="checked"' : '';
                    ?>
                    <input type="checkbox" <?php echo $checked ?> data-level0="0" name="checkWebMenu" value="<?php echo $hijo['categoria_id'] . '_' . $hijo['categoria_padre_id'] ?>" data-padre="<?php echo $hijo['categoria_padre_id'] ?>" data-categoria="<?php echo $hijo['categoria_id'] ?>" />
                    <?php
                    echo $hijo['nombre'];
                    self::calculaHijos($hijo, $selected);
                    }
                    ?>
                </li>
            </ul>
            <?php
        }

    }

    public static function getAllCategoriasAccesorios()
    {
        $categoriasAccesorios = Categorias::model()->findAllByAttributes(
            array(
                'tiene_atributos' => 1
            )
        );

        $arrAccesorios = array();
        foreach ($categoriasAccesorios as $ca) {
            $splits = str_split($ca->codigo, 2);
            foreach ($splits as $split) {
                if ($split == 99) {
                    array_push($arrAccesorios, $ca);
                }
            }
        }

        return $arrAccesorios;
    }

    public static function getAllCategoriasProductos()
    {
        $categorias = Categorias::model()->findAllByAttributes(
            array(
                'tiene_atributos' => 1
            )
        );

        $arr = array();
        foreach ($categorias as $ca) {
            /*$splits = str_split($ca->codigo, 2);
            $hasAccesorio = false;
            foreach ($splits as $split) {
                if ($split == 99) {
                    $hasAccesorio = true;
                }
            }
            if (!$hasAccesorio) {*/
                array_push($arr, $ca);
            //}
        }

        return $arr;
    }

    public static function refreshProductsView()
    {
        $categorias = self::getAllCategoriasProductos();
        $sql .= " CREATE OR REPLACE VIEW v_productos as";
        //PRODUCTOS
        foreach ($categorias as $categoria) {
            $sql .= " SELECT
                    '$categoria->slug' as categoria_slug,
                    '$categoria->codigo' as categoria_codigo,
                    $categoria->categoria_id as categoria_id,
                    producto_id,
                    codigo,
                    etiqueta,
                    descripcion,
                    stock_minimo,
                    disponibilidad,
                    fecha_borrado_logico,
                    fecha_creacion,
                    activo_web,
                    borrado_logico,
                    peso,
                    porcentaje_coseguro,
                    '$categoria->nombre' as categoria_nombre
                    FROM productos_" . $categoria->slug;
                    $sql .=" UNION ALL";
        }

        $sql = substr($sql, 0, -9);
        $connection=Yii::app()->db;

        $command=$connection->createCommand($sql);
        $rowCount=$command->execute(); // execute the non-query SQL
        return 'success: '.$rowCount;
    }

        public static function updateProductsTablesCoseguro()
    {
        $categorias = self::getAllCategoriasProductos();
        $sql .= "";
        $i = 0;
        //PRODUCTOS
        $connection=Yii::app()->db;
        foreach ($categorias as $categoria) {
            $sql = " ALTER TABLE productos_".$categoria->slug." ADD COLUMN porcentaje_coseguro decimal(4,2) default 0;";
            $command=$connection->createCommand($sql);
            $command->execute();
            $i++;
        }
        return 'success: '.$i;
    }

    public static function getCategoriasFromCategoria($element, $parentId = 0)
    {
        $branch = array();

        $elements = Categorias::model()->findAllByAttributes(
            array(
                'categoria_padre_id' => $element->categoria_id,
                'tiene_atributos' => 1
            )
        );
        foreach ($elements as $e) {
            if ($e->categoria_padre_id == $parentId) {
                $children = Categorias::getCategoriasFromCategoria($e, $e->categoria_id);
                if ($children) {
                    foreach ($children as $child) {
                        $branch[] = $child;
                    }
                    $e->hijos = $children;
                }
                $branch[] = $e->categoria_id;
            }
        }

        return $branch;
    }

    protected function beforeSave(){

        //Si no tiene etiqueta web entonces copio el nombre como etiqueta
        if (empty($this->etiqueta_web)){
            $this->etiqueta_web = $this->nombre;
        }

        return parent::beforeSave();
    }
}
