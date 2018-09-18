<?php

/**
 * This is the model class for table "bodegas".
 *
 * The followings are the available columns in table 'bodegas':
 * @property integer $bodega_id
 * @property string $codigo
 * @property string $nombre
 * @property string $direccion
 * @property string $telefono
 */
class Bodegas extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Bodegas the static model class
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
        return 'bodegas';
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
            array('codigo, nombre, direccion, telefono', 'length', 'max'=>50),
            array('predeterminada, suma_stock, orden', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('bodega_id, codigo, nombre, direccion, telefono', 'safe', 'on'=>'search'),
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
            'bodega_id' => '#',
            'codigo' => 'Código',
            'nombre' => 'Nombre',
            'direccion' => 'Dirección',
            'telefono' => 'Teléfono',
            'suma_stock' => 'Suma productos al stock general?',
            'predeterminada' => 'Bodega predeterminada',
            'orden' => 'Orden',
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

        $criteria->compare('bodega_id',$this->bodega_id);
        $criteria->compare('predeterminada',$this->predeterminada);
        $criteria->compare('codigo',$this->codigo,true);
        $criteria->compare('nombre',$this->nombre,true);
        $criteria->compare('direccion',$this->direccion,true);
        $criteria->compare('telefono',$this->telefono,true);

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
    public function getCodigoYNombre()
    {
        return trim(implode(' ', array($this->codigo, $this->nombre)));
    }

    /**
     * Recupero un array de bodegas
     */
    public static function listBodegas($excluded=null, $all=false)
    {
        $bodegas = Bodegas::arrayBodegas($excluded, $all);

        return CHtml::listData($bodegas,'bodega_id','codigoYNombre');
    }

    /**
     * Recupero la lista de bodegas
     *
     * en $excluded defino las IDs de bodegas que no quiero que salgan el el resultado.
     * en $all defino si muestro todas las bodegas incluyendo las que no suman stock
     *
     * @return array de bodegas.
     *
     * */
    public static function arrayBodegas($excluded=null, $all=false)
    {
        $criteria = new CDbCriteria();
        if(isset($excluded) && count($excluded)>0)
            $criteria->addNotInCondition("bodega_id", $excluded);
        if ($all == false)
            $criteria->addCondition("suma_stock", 1);

        $criteria->order = "codigo ASC, nombre ASC";

        return Bodegas::model()->findAll($criteria);
    }

    public static function getBodegaPredeterminada()
    {
        $bodega = Bodegas::model()->findByAttributes(array('predeterminada'=>1));
        return $bodega->bodega_id;
    }
}