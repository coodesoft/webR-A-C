<?php

/**
 * This is the model class for table "productos_modelos".
 *
 * The followings are the available columns in table 'productos_modelos':
 * @property integer $producto_modelo_id
 * @property integer $producto_marca_id
 * @property string $nombre
 * @property string $descripcion
 * @property string $imagen
 */
class ProductosModelos extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ProductosModelos the static model class
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
		return 'productos_modelos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('producto_marca_id, nombre', 'required'),
			array('producto_marca_id', 'numerical', 'integerOnly'=>true),
			array('nombre', 'length', 'max'=>100),
			array('descripcion, imagen', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('producto_modelo_id, producto_marca_id, nombre, descripcion, imagen', 'safe', 'on'=>'search'),
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
            'marca' => array(self::BELONGS_TO, 'ProductosMarcas', 'producto_marca_id'),
            'productos' => array(self::HAS_MANY, 'ProductosModelosProductos', 'producto_modelo_id'),
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
			'producto_modelo_id' => 'Producto Modelo',
			'producto_marca_id' => 'Producto Marca',
			'nombre' => 'Nombre',
			'descripcion' => 'Descripcion',
			'imagen' => 'Imagen',
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

		$criteria->compare('producto_modelo_id',$this->producto_modelo_id);
		$criteria->compare('producto_marca_id',$this->producto_marca_id);
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('descripcion',$this->descripcion,true);
		$criteria->compare('imagen',$this->imagen,true);

		return new CActiveDataProvider($this, array(
			'pagination' => array(  
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
		));
	}

	public function getImagen($canDelete = false, $showEmpty = false){

		if ( ($this->imagen == '' && $showEmpty) || $this->imagen != '')
			$ret = CHtml::image(Yii::app()->request->baseUrl."/../repository/".$this->imagen,"Imagen",array("id"=>"foto","style"=>"max-height: 270px; max-width: 320px;"));
			
		if ($canDelete) {
			$ret .= '<br />
			    	<a  href="#"
			        class="deleteImagen"
			        data-modelo="'.$this->producto_modelo_id.'">borrar</a>';
		}

		return $ret;
	}

	public function nombreCompleto(){
		return $this->marca->nombre.' - '.$this->nombre;
	}

	public static function obtenerModelosParaCategoria($categoria_id) {
		$criteria=new CDbCriteria;

		$criteria->addCondition('producto_modelo_id IN (select producto_modelo_id from productos_modelos_productos where categoria_id ='.$categoria_id.')');

		return ProductosModelos::model()->findAll($criteria);
	}

	public static function obtenerModelosParaCategoriasYMarca($categoria_ids,$marca_id) {
		$criteria=new CDbCriteria;

		$cats = "";
		foreach($categoria_ids as $categoria_id){
			$cats .= $categoria_id.',';
		}

		$cats = substr($cats, 0, strlen($cats)-1 );

		$criteria->addCondition('producto_marca_id = '.$marca_id);
		$criteria->addCondition('producto_modelo_id IN (select producto_modelo_id from productos_modelos_productos where categoria_id in ('.$cats.') )');

		return ProductosModelos::model()->findAll($criteria);
	}

	public static function obtenerModelosParaMarca($marca_id) {
		$criteria=new CDbCriteria;

		$criteria->addCondition('producto_marca_id ='.$marca_id);

		return ProductosModelos::model()->findAll($criteria);
	}

	protected function beforeDelete() {

		foreach ($this->productos as $productoModelo)  {
			if (!$productoModelo->delete())
				return false;
		}

        return parent::beforeDelete();

    }
}