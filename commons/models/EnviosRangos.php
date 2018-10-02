<?php

/**
 * This is the model class for table "envios_rangos".
 *
 * The followings are the available columns in table 'envios_rangos':
 * @property integer $envio_rango_id
 * @property integer $valor_inferior
 * @property integer $valor_superior
 * @property integer $costo_envio
 * @property integer $predeterminada
 */
class EnviosRangos extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return EnviosRangos the static model class
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
		return 'envios_rangos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('valor_inferior, valor_superior, costo_envio', 'required'),
			array('valor_inferior, valor_superior, costo_envio', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('envio_rango_id, valor_inferior, valor_superior, costo_envio', 'safe', 'on'=>'search'),
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
			'envio_rango_id' => 'Envio Rango',
			'valor_inferior' => 'Valor Inferior (gr)',
			'valor_superior' => 'Valor Superior (gr)',
			'costo_envio' => 'Costo Envio ($)',
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

		$criteria->compare('envio_rango_id',$this->envio_rango_id);
		$criteria->compare('valor_inferior',$this->valor_inferior);
		$criteria->compare('valor_superior',$this->valor_superior);
		$criteria->compare('costo_envio',$this->costo_envio);
		$criteria->compare('predeterminada',$this->predeterminada);

		return new CActiveDataProvider($this, array(
			'pagination' => array(  
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
		));
	}

	/**
     * @Devuelve la el codigo de la categoria y el nombre todo en uno.
     * */
    public function getNombre()
    {
        return trim(implode('_', array($this->valor_inferior, $this->valor_superior)));
    }


	/**
     * Recupero una lista de rangos para un dropDownList
     * 
     * @return elemento listData con los rangos.
     * 
     * */
    public static function listRangos()
    {    
        $criteria = new CDbCriteria();
        if(isset($excluded) && count($excluded)>0)
            $criteria->addNotInCondition("costo_id", $excluded);
        
		$criteria->order = "orden ASC, codigo ASC, nombre ASC";

        return CHtml::listData($self::obtenerRangos(),'envio_rango_id','nombre');
    }

    /**
     * Recupero una lista de rangos
     * 
     * 
     * @return array de rangos.
     * 
     * */
    public static function obtenerRangos()
    {    
        $criteria = new CDbCriteria();
		$criteria->order = "valor_inferior ASC";

        return self::model()->findAll($criteria);
    }

    public static function obtenerRango($valor){

    	$criteria = new CDbCriteria();
    	$criteria->condition .= '(valor_inferior <= '.$valor.' AND valor_superior >= '.$valor.')';

    	$aux = self::model()->findAll($criteria);

		if (sizeof($aux) < 1)
			$ret = null;
		else
			$ret = $aux[0];


		return $ret;

    }

    protected function beforeSave(){


    	//Chequeo que el valor_superior sea mayor al valor_inferior
    	if ($this->valor_inferior >= $this->valor_superior) {
    		$this->addError('EnviosRango.rango', 'El valor superior debe ser mayor al valor inferior');
			return false;
    	}
    	//Chequeo que el rango no se superponga con uno existente
    	$criteria = new CDbCriteria();
    	//Qee no sea yo mismo
    	if (!$this->isNewRecord)
			$criteria->condition = 'envio_rango_id != '.$this->envio_rango_id. ' AND ';
	
		//Mi rango se superpone con un valor_inferior
		$criteria->condition .= '((valor_inferior >= '.$this->valor_inferior.' AND valor_inferior <= '.$this->valor_superior.') OR ';

		//$criteria->addBetweenCondition('valor_inferior',$this->valor_inferior,$this->valor_superior, 'AND');

		//Mi rango se superpone con un valor_superior
		$criteria->condition .= '(valor_superior >= '.$this->valor_inferior.' AND valor_superior <= '.$this->valor_superior.'))';

		//$criteria->addBetweenCondition('valor_superior',$this->valor_inferior,$this->valor_superior, 'OR');

		$aux = self::model()->findAll($criteria);

		if (sizeof($aux) >= 1){
			$this->addError('EnviosRango.rango', 'El rango ingresado se superpone con uno existente:'.$aux[0]->getNombre());
			return false;
		}

		return parent::beforeSave();
	}
}