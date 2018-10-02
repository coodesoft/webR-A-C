<?php

/**
* This is the model class for table "log_pedido_online".
*
* The followings are the available columns in table 'log_pedido_online':
* @property integer $id_log
* @property integer $id_pedido
* @property string $log
*/
class LogPedidoOnline extends CActiveRecord
{
   public static function model($className=__CLASS__)
   {
       return parent::model($className);
   }

   public function tableName()
   {
       return 'log_pedido_online';
   }

   public function rules()
   {
       return [
           ['id_pedido, log', 'required'],
           ['id_pedido', 'numerical', 'integerOnly'=>true],
          // ['log', 'length', 'max'=>2048],
           // The following rule is used by search().
           // Please remove those attributes that should not be searched.
           ['id_log, id_pedido, log', 'safe', 'on'=>'search'],
       ];
   }

   public function relations()
   {
       return [];
   }

   public function behaviors(){
       return [
           'PFechas' => [
               'class' => 'ext.fechas.PFechas',
           ],
       ];
   }

   public function attributeLabels()
   {
       return [
           'id_log'    => 'Id Log',
           'id_pedido' => 'Id Pedido',
           'log'       => 'Log',
       ];
   }

   public function search()
   {
       // Warning: Please modify the following code to remove attributes that
       // should not be searched.

       $criteria=new CDbCriteria;

       $criteria->compare('id_log',$this->id_log);
       $criteria->compare('id_pedido',$this->id_pedido);
       $criteria->compare('log',$this->log,true);

       return new CActiveDataProvider($this, [
           'pagination' => [
               'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
           ],
           'criteria'=>$criteria,
       ]);
   }
}
