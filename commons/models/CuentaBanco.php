<?php

/**
 * This is the model class for table "cuenta_banco".
 *
 * The followings are the available columns in table 'cuenta_banco':
 * @property integer $cuenta_banco_id
 * @property integer $banco_id
 * @property string $tipo_cuenta
 * @property integer $nro_cuenta
 * @property string $moneda_id
 * @property double $saldo
 * @property string $observaciones
 * @property string $codigo
 * @property integer $es_cupon_credito
 * @property integer $es_cupon_debito
 * @property integer $es_cupon_online
 *
 * The followings are the available model relations:
 * @property Cupon[] $cupons
 */
class CuentaBanco extends CActiveRecord
{

    const TIPO_CUENTA_CUENTACORRIENTE = 'CC';
    const TIPO_CUENTA_CAJAAHORRO= 'CA';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return CuentaBanco the static model class
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
        return 'cuenta_banco';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('banco_id, tipo_cuenta, moneda_id', 'required'),
            array('banco_id, nro_cuenta, es_cupon_credito, es_cupon_debito, es_cupon_online, es_cupon_posnet_air, es_chequera, transferencia_online', 'numerical', 'integerOnly'=>true),
            array('saldo', 'numerical'),
            array('tipo_cuenta', 'length', 'max'=>2),
            array('moneda_id, observaciones', 'length', 'max'=>100),
            array('cbu', 'length', 'max'=>22),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('cuenta_banco_id, banco_id, tipo_cuenta, nro_cuenta, moneda_id, saldo, observaciones, es_cupon_credito, es_cupon_debito, es_cupon_online, es_cupon_posnet_air, es_chequera, cbu,alias_cbu, transferencia_online', 'safe', 'on'=>'search'),
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
            'banco' => array(self::BELONGS_TO, 'Banco', 'banco_id'),
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
            'cuenta_banco_id' => 'Cuenta Banco',
            'banco_id' => 'Banco',
            'tipo_cuenta' => 'Tipo Cuenta',
            'nro_cuenta' => 'Nro Cuenta',
            'moneda_id' => 'Moneda',
            'saldo' => 'Saldo',
            'observaciones' => 'Observaciones',
            'es_cupon_credito' => 'Maneja Cupon Credito',
            'es_cupon_debito' => 'Maneja Cupon Debito',
            'es_cupon_posnet_air' => 'Maneja Cupon Posnet Air',
            'es_cupon_online' => 'Maneja Cupon Online',
            'es_chequera' => 'Maneja Cheques',
            'transferencia_online' => 'Acepta Transferencias Pedidos Online'

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

        $criteria->compare('cuenta_banco_id',$this->cuenta_banco_id);
        $criteria->compare('banco_id',$this->banco_id);
        $criteria->compare('tipo_cuenta',$this->tipo_cuenta,true);
        $criteria->compare('nro_cuenta',$this->nro_cuenta);
        $criteria->compare('moneda_id',$this->moneda_id,true);
        $criteria->compare('saldo',$this->saldo);
        $criteria->compare('observaciones',$this->observaciones,true);
        $criteria->compare('codigo',$this->codigo,true);
        $criteria->compare('es_cupon_credito',$this->es_cupon_credito);
        $criteria->compare('es_cupon_debito',$this->es_cupon_debito);
        $criteria->compare('es_cupon_online',$this->es_cupon_online);

        return new CActiveDataProvider($this, array(
            'pagination' => array(  
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
        ));
    }

    public static function getTiposCuenta(){
       return [
         self::TIPO_CUENTA_CAJAAHORRO => 'Caja Ahorro',
         self::TIPO_CUENTA_CUENTACORRIENTE => 'Cuenta Corriente'
       ];
     }

     public function getTipoText(){
      return self::getTiposCuenta()[$this->tipo_cuenta];
     }

    public static function getCuentaCuponCredito(){
      $ret = self::model()->findAllByAttributes(
            array(
                'es_cupon_credito' => 1,
                ),
            array(
                'limit' => 1,
            )
        );

        return $ret;
    }

    public static function getCuentaCuponOnline(){
      $ret = self::model()->findAllByAttributes(
            array(
                'es_cupon_online' => 1,
                ),
            array(
                'limit' => 1,
            )
        );

        return $ret;
    }

    public static function getCuentaCuponDebito(){
      $ret = self::model()->findAllByAttributes(
            array(
                'es_cupon_debito' => 1,
                ),
            array(
                'limit' => 1,
            )
        );

        return $ret;
    }

    public static function getCuentaCuponPosnetAir(){
      $ret = self::model()->findAllByAttributes(
            array(
                'es_cupon_posnet_air' => 1,
                ),
            array(
                'limit' => 1,
            )
        );

        return $ret;
    }

    public function getNombre(){
      return $this->banco->nombre.'_'.$this->nro_cuenta;
    }

  public static function getList(){
     return CHtml::listData(CuentaBanco::model()->findAll(), 'cuenta_banco_id', 'nombre');
   }

   public static function getChequeras(){
     
     return CHtml::listData(CuentaBanco::model()->findAllByAttributes(
            array(
                'es_chequera' => 1,
                )), 'cuenta_banco_id', 'nombre');
   }

   public static function getCuentasBancoTransferenciaOnline (){

    return CuentaBanco::model()->findAllByAttributes(
            array(
                'transferencia_online' => 1,
                ));
   }

   public function renderMailOutput(){
    $ret  = '<span style="font-size:0.7em;display:block"><b>Razon Social:</b> Rosario Al Costo </span>';
    $ret .= '<span style="font-size:0.7em;display:block"><b>Banco Emisor:</b> '.$this->banco->nombre.'</span>';
    $ret .= '<span style="font-size:0.7em;display:block"><b>Tipo y Numero de Cuenta:</b> '.$this->getTipoText().' '.$this->nro_cuenta.'</span>';
    $ret .= '<span style="font-size:0.7em;display:block"><b>CBU:</b> '.$this->cbu.'</span>';
    return $ret;
   }

    protected function beforeSave(){

   
    if (!$this->isNewRecord){
      $where = " WHERE cuenta_banco_id <> ".$this->cuenta_banco_id;
    }
    else
      $where = "";
    
     //Solo una cuenta puede tener check cupon online
    if ($this->es_cupon_online == 1){
      $sql = "UPDATE cuenta_banco SET es_cupon_online = 0".$where;
      Yii::app()->db->createCommand($sql)->execute();
    }

     //Solo una cuenta puede tener check cupon credito
    if ($this->es_cupon_credito == 1){
      $sql = "UPDATE cuenta_banco SET es_cupon_credito = 0".$where;
      Yii::app()->db->createCommand($sql)->execute();
    }

     //Solo una cuenta puede tener check cupon debito
    if ($this->es_cupon_debito == 1){
      $sql = "UPDATE cuenta_banco SET es_cupon_debito = 0".$where;
      Yii::app()->db->createCommand($sql)->execute();
    }

    //Solo una cuenta puede tener check cupon posnet air
    if ($this->es_cupon_posnet_air == 1){
      $sql = "UPDATE cuenta_banco SET es_cupon_posnet_air = 0".$where;
      Yii::app()->db->createCommand($sql)->execute();
    }

    return parent::beforeSave();
  }
}