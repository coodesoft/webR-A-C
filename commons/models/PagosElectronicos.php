<?php

/**
* This is the model class for table "pagos_electronicos".
*
* The followings are the available columns in table 'pagos_electronicos':
* @property integer $id
* @property integer $id_pedido
* @property integer $estado
* @property double $transaction_amount
* @property double $total_paid_amount
*/
class PagosElectronicos extends CActiveRecord
{
  const CANCELADO  = -1;
  const PENDIENTE  = 0;
  const PAGADO     = 1; //aproved
  const ERROR      = 3;
  const IN_PROCESS = 4;
  const REJECTED   = 5;
  const REFUNDED   = 6;
  const CANCELLED  = 7;
  const IN_MEDIATION = 8;
  const CHARGED_BACK = 9;
  const RP_PENDING   = 10;
  
   public static function model($className=__CLASS__)
   {
       return parent::model($className);
   }

   /**
    * @return string the associated database table name
    */
   public function tableName()
   {
       return 'pagos_electronicos';
   }

   /**
    * @return array validation rules for model attributes.
    */
   public function rules()
   {
       // NOTE: you should only define rules for those attributes that
       // will receive user inputs.
       return [
           ['id_pedido, estado, transaction_amount, total_paid_amount', 'required'],
           ['id_pedido, estado', 'numerical', 'integerOnly'=>true],
           ['transaction_amount, total_paid_amount', 'numerical'],
           // The following rule is used by search().
           // Please remove those attributes that should not be searched.
           ['id, id_pedido, estado, transaction_amount, total_paid_amount, transferencia_id', 'safe', 'on'=>'search'],
       ];
   }

   /**
    * @return array relational rules.
    */
   public function relations()
   {
       // NOTE: you may need to adjust the relation name and the related
       // class name for the relations automatically generated below.
       return array(
          'pedido' => array(self::BELONGS_TO, 'PedidoOnline', 'id_pedido'),
          'transferencia' => array(self::BELONGS_TO, 'Transferencia', 'transferencia_id'),
        );
   }

   /**
    * Función para formatear las fechas.
    * Todo lo que sea de tipo date y datetime serán formateados para ser legibles y antes de ser validados para almacanarse
    */
   public function behaviors(){
       return [
           'PFechas' => [
               'class' => 'ext.fechas.PFechas',
           ],
       ];
   }

   /**
    * @return array customized attribute labels (name=>label)
    */
   public function attributeLabels()
   {
       return [
           'id' => 'ID',
           'id_pedido' => 'Id Pedido',
           'estado' => 'Estado',
           'transaction_amount' => 'Transaction Amount',
           'total_paid_amount' => 'Total Paid Amount',
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

       $criteria->compare('id',$this->id);
       $criteria->compare('id_pedido',$this->id_pedido);
       $criteria->compare('estado',$this->estado);
       $criteria->compare('transaction_amount',$this->transaction_amount);
       $criteria->compare('total_paid_amount',$this->total_paid_amount);

       return new CActiveDataProvider($this, array(
           'pagination' => array(
               'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
           ),
           'criteria'=>$criteria,
       ));
   }

  public static function crearTransferenciaOnline($id_pago_electronico,$nro_comprobante,$fecha,$cuenta_banco_id) {

    $transaction=Yii::app()->db->beginTransaction();

    try {
        $pagoElectronico = PagosElectronicos::model()->findByPK($id_pago_electronico);

      $transferencia = new Transferencia();

      //Creo Transferencia
      $transferencia->numero = $nro_comprobante;
      $transferencia->cuenta_banco_id = $cuenta_banco_id;
      $transferencia->fecha = $fecha;
      $transferencia->estado = Transferencia::ESTADO_PROCESADA;
      $transferencia->tipo = Transferencia::TIPO_RECIBIDA;
      $transferencia->monto = $pagoElectronico->transaction_amount;
      $transferencia->cliente_id =  Clientes::obtenerClientePorUserID($pagoElectronico->pedido->id_user)->cliente_id;

      if (!$transferencia->save())
        throw new Exception("Error al generar transferencia online: ".Commons::renderError($transferencia->getErrors()));

      //Actualizo Pago
      $pagoElectronico->transferencia_id = $transferencia->transferencia_id;
      $pagoElectronico->estado = self::PAGADO;

      if (!$pagoElectronico->save())
        throw new Exception("Error al actualizar el pago electronico: ".Commons::renderError($pagoElectronico->getErrors()));

      $pedido = $pagoElectronico->pedido;

      //proceso pedido a estado CERRADO
      $pedido->procesar(null,PedidoOnline::ORDER_CLOSED);

    } catch (Exception $e) {
        $transaction->rollBack();
        return ['success'=>false,'msg'=> $e->getMessage()];
    }

    $transaction->commit();

    return ['success'=>true];
  }

  public static function expirarTransferencia($id_pago_electronico) {

    try {
      
      $pagoElectronico = PagosElectronicos::model()->findByPK($id_pago_electronico);

      //Actualizo Pago -> A cancelado
      $pagoElectronico->estado = self::CANCELLED;

      if (!$pagoElectronico->save())
        throw new Exception("Error al actualizar el pago electronico: ".Commons::renderError($pagoElectronico->getErrors()));

      $pedido = $pagoElectronico->pedido;

      //Reabro el pedido
      $pedido->reabrir();

    } catch (Exception $e) {
        return ['success'=>false,'msg'=> $e->getMessage()];
    }

    return ['success'=>true];
  }
}
