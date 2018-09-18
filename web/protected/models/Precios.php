<?php

/**
 * This is the model class for table "precios".
 *
 * The followings are the available columns in table 'precios':
 * @property integer $precio_id
 * @property string $codigo
 * @property string $nombre
 * @property string $descripcion
 * @property integer $es_porcentaje
 * @property double $porcentaje
 * @property integer $porcentaje_precio_id
 * @property integer $orden
 * @property integer $mostrar_en_listado
 * @property integer $tamano_fuente
 * @property integer $es_mayorista
 * @property enum $tipo
 */
class Precios extends CActiveRecord
{
	const NINGUNO = -1;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Precios the static model class
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
		return 'precios';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('codigo, nombre', 'required'),
			array('codigo, nombre', 'length', 'max'=>255),
			array('es_porcentaje, es_mayorista, porcentaje_precio_id, orden, mostrar_en_listado, tamano_fuente', 'numerical', 'integerOnly'=>true),
			array('porcentaje', 'numerical'),
            array('descripcion','length'),
            array('tipo', 'length', 'max'=>1),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('precio_id, codigo, nombre, descripcion, es_porcentaje, es_mayorista, mostrar_en_listado, porcentaje, tipo, porcentaje_precio_id, tamano_fuente', 'safe', 'on'=>'search'),
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
			'padre' => array(self::BELONGS_TO, 'Precios', 'porcentaje_precio_id'),
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
			'precio_id' => '#',
			'codigo' => 'Código',
			'nombre' => 'Nombre',
			'descripcion' => 'Descripción',
			'es_porcentaje' => 'Es porcentaje de otro precio definido',
			'porcentaje' => 'Porcentaje (%)',
			'porcentaje_precio_id' => 'Precio sobre el que aplicar el porcentaje',
			'tipo' => 'Tipo',
			'orden' => 'Orden',
			'mostrar_en_listado' => 'Mostrar en listado de productos?',
			'tamano_fuente' => 'Tamaño de la fuente',
			'es_mayorista' => 'Es precio mayorista?',
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

		$criteria->compare('precio_id',$this->precio_id);
		$criteria->compare('codigo',$this->codigo,true);
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('descripcion',$this->descripcion,true);
		$criteria->compare('es_porcentaje',$this->es_porcentaje);
		$criteria->compare('porcentaje',$this->porcentaje);
		$criteria->compare('porcentaje_precio_id',$this->porcentaje_precio_id);
		$criteria->compare('mostrar_en_listado',$this->mostrar_en_listado);
		$criteria->compare('tamano_fuente',$this->tamano_fuente);
		$criteria->compare('es_mayorista',$this->es_mayorista);
		$criteria->compare('tipo',$this->tipo,true);
		$criteria->compare('orden',$this->orden,true);

		return new CActiveDataProvider($this, array(
			'pagination' => array(
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
		));
	}

	/**
     * Recupero una lista de precios para un dropDownList
     *
     * en $excluded defino las IDs de precios que no quiero que salgan el el resultado.
     *
     * @return elemento listData con los precios.
     *
     * */
    public static function listPrecios($excluded=null)
    {
        $criteria = new CDbCriteria();
        if(isset($excluded) && count($excluded)>0)
            $criteria->addNotInCondition("precio_id", $excluded);

		$criteria->order = "orden ASC, codigo ASC, nombre ASC";

        return CHtml::listData(Precios::model()->findAll($criteria),'precio_id','codigoYNombre');
    }

    /**
     * @Devuelve la el codigo de la categoria y el nombre todo en uno.
     * */
    public function getCodigoYNombre()
    {
        return trim(implode(' ', array($this->codigo, $this->nombre)));
    }

    public static function getArrayHijos($precio_id)
    {
    	$arrPrecios = array();

    	$precios = Precios::model()->findAllByAttributes(array('porcentaje_precio_id'=>$precio_id));
    	foreach ($precios as $precio) {
    		$arrPrecios[] = $precio->precio_id;
    	}

    	return $arrPrecios;
    }
}
