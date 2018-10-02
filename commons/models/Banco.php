<?php

/**
* This is the model class for table "banco".
*
* The followings are the available columns in table 'banco':
* @property integer $banco_id
* @property string $tipo_banco
* @property integer $nro_sucursal
* @property string $calle
* @property string $numero
* @property string $piso
* @property string $depto
* @property string $telefono
* @property string $prefijo
* @property integer $provincia_id
* @property integer $ciudad_id
* @property string $cpostal
* @property string $nombre_contacto
* @property string $apellido_contacto
* @property string $telefono_contacto
* @property string $observaciones
*/
class Banco extends CActiveRecord
{
   /**
    * Returns the static model of the specified AR class.
    * @param string $className active record class name.
    * @return Banco the static model class
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
       return 'banco';
   }

   /**
    * @return array validation rules for model attributes.
    */
   public function rules()
   {
       // NOTE: you should only define rules for those attributes that
       // will receive user inputs.
       return array(
           ['nombre, tipo_banco', 'required'],
           ['banco_id, nro_sucursal, provincia_id, ciudad_id', 'numerical', 'integerOnly'=>true],
           ['tipo_banco', 'length', 'max'=>2],
           ['calle, cpostal, nombre_contacto, apellido_contacto, observaciones', 'length', 'max'=>100],
           ['numero', 'length', 'max'=>20],
           ['piso, depto, prefijo', 'length', 'max'=>10],
           ['telefono, telefono_contacto', 'length', 'max'=>50],
           // The following rule is used by search().
           // Please remove those attributes that should not be searched.
           ['banco_id, tipo_banco, nro_sucursal, calle, numero, piso, depto, telefono, prefijo, provincia_id, ciudad_id, cpostal, nombre_contacto, apellido_contacto, telefono_contacto, observaciones','safe', 'on'=>'search'],
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
       return [
           'banco_id'          => 'Banco',
           'nombre'            => 'Nombre',
           'tipo_banco'        => 'Tipo Banco',
           'nro_sucursal'      => 'Nro Sucursal',
           'calle'             => 'Calle',
           'numero'            => 'Numero',
           'piso'              => 'Piso',
           'depto'             => 'Depto',
           'telefono'          => 'Telefono',
           'prefijo'           => 'Prefijo',
           'provincia_id'      => 'Provincia',
           'ciudad_id'         => 'Ciudad',
           'cpostal'           => 'Codigo postal',
           'nombre_contacto'   => 'Nombre Contacto',
           'apellido_contacto' => 'Apellido Contacto',
           'telefono_contacto' => 'Telefono Contacto',
           'observaciones'     => 'Observaciones',
       ];
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

       $criteria->compare('banco_id',$this->banco_id);
       $criteria->compare('tipo_banco',$this->tipo_banco,true);
       $criteria->compare('nro_sucursal',$this->nro_sucursal);
       $criteria->compare('calle',$this->calle,true);
       $criteria->compare('numero',$this->numero,true);
       $criteria->compare('piso',$this->piso,true);
       $criteria->compare('depto',$this->depto,true);
       $criteria->compare('telefono',$this->telefono,true);
       $criteria->compare('prefijo',$this->prefijo,true);
       $criteria->compare('provincia_id',$this->provincia_id);
       $criteria->compare('ciudad_id',$this->ciudad_id);
       $criteria->compare('cpostal',$this->cpostal,true);
       $criteria->compare('nombre_contacto',$this->nombre_contacto,true);
       $criteria->compare('apellido_contacto',$this->apellido_contacto,true);
       $criteria->compare('telefono_contacto',$this->telefono_contacto,true);
       $criteria->compare('observaciones',$this->observaciones,true);

       return new CActiveDataProvider($this, array(
           'pagination' => array(
               'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
           ),
           'criteria'=>$criteria,
       ));
   }

   public static function getTiposBanco(){
     return [
       '0' => 'Interno',
       '1' => 'Externo'
     ];
   }

   public function getTipoBancoText(){
     return self:: getTiposBanco()[$this->tipo_banco];
   }

   public static function getList(){
     return CHtml::listData(Banco::model()->findAll(), 'banco_id', 'nombre');
   }

   public static function getListPropios(){

      $criteria = new CDbCriteria;
      $criteria->addCondition('t.tipo_banco = 0');
      $criteria->order = 'nombre ASC';

     return CHtml::listData(Banco::model()->findAll($criteria), 'banco_id', 'nombre');
   }

   public static function getListExternos(){

      $criteria = new CDbCriteria;
      $criteria->addCondition('t.tipo_banco = 1');
      $criteria->order = 'nombre ASC';

     return CHtml::listData(Banco::model()->findAll($criteria), 'banco_id', 'nombre');
   }
}
