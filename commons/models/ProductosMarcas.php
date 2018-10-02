<?php

/**
 * This is the model class for table "productos_marcas".
 *
 * The followings are the available columns in table 'productos_marcas':
 * @property integer $producto_marca_id
 * @property string $nombre
 * @property string $descripcion
 * @property string $logo
 */
class ProductosMarcas extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ProductosMarcas the static model class
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
		return 'productos_marcas';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nombre, logo', 'required'),
			array('nombre', 'length', 'max'=>100),
			array('descripcion, logo', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('producto_marca_id, nombre, descripcion, logo', 'safe', 'on'=>'search'),
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
			'modelos' => array(self::HAS_MANY, 'ProductosModelos', 'producto_marca_id'),
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
			'producto_marca_id' => 'Producto Marca',
			'nombre' => 'Nombre',
			'descripcion' => 'Descripcion',
			'logo' => 'Logo',
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

		$criteria->compare('producto_marca_id',$this->producto_marca_id);
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('descripcion',$this->descripcion,true);
		$criteria->compare('logo',$this->logo,true);

		return new CActiveDataProvider($this, array(
			'pagination' => array(  
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
		));
	}

	public function getLogo(){
		return CHtml::image(Yii::app()->request->baseUrl."/../repository/".$this->logo,"Logo",array("id"=>"foto","style"=>"max-height: 250px; max-width: 150px;"));
	}

	/**
     * Recupero una lista de marcas para un dropDownList
     * @return elemento listData con las categorías.
     *
     * */
    public static function listMarcas()
    {
        $marcas = ProductosMarcas::model()->findAll();

        return CHtml::listData($marcas,'producto_marca_id','nombre');
    }

    public static function obtenerMarcasParaCategoria($categoria_id) {
		$criteria=new CDbCriteria;

		$criteria->addCondition('producto_marca_id in (select producto_marca_id from productos_modelos where producto_modelo_id
			IN (select producto_modelo_id from productos_modelos_productos where categoria_id ='.$categoria_id.'))');

		return ProductosMarcas::model()->findAll($criteria);
	}

	public static function obtenerMarcasParaCategorias($categoria_ids) {
		$criteria=new CDbCriteria;

		$cats = "";
		foreach($categoria_ids as $categoria_id){
			$cats .= $categoria_id.',';
		}

		$cats = substr($cats, 0, strlen($cats)-1 );
		
		$criteria->addCondition('producto_marca_id in (select producto_marca_id from productos_modelos where producto_modelo_id
			IN (select producto_modelo_id from productos_modelos_productos where categoria_id in ('.$cats.') ) )');

		return ProductosMarcas::model()->findAll($criteria);
	}

	protected function beforeDelete() {

		//Elimino todas las marcas asociadas
		foreach ($this->modelos as $modelo)  {
			if (!$modelo->delete())
				return false;
		}

        return parent::beforeDelete();

    }
}