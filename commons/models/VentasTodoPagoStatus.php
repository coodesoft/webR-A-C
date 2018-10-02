<?php

/**
 * This is the model class for table "ventas_todo_pago_status".
 *
 * The followings are the available columns in table 'ventas_todo_pago_status':
 * @property integer $venta_todo_pago_status_id
 * @property integer $operationid
 * @property integer $statusCode
 * @property string $statusMessage
 * @property string $authorizationKey
 * @property string $encodingMethod
 * @property string $payload
 */
class VentasTodoPagoStatus extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return VentasTodoPagoStatus the static model class
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
		return 'ventas_todo_pago_status';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('operationid, statusCode, statusMessage, authorizationKey, encodingMethod, payload', 'required'),
			array('operationid, statusCode', 'numerical', 'integerOnly'=>true),
			array('statusMessage, authorizationKey, encodingMethod', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('venta_todo_pago_status_id, operationid, statusCode, statusMessage, authorizationKey, encodingMethod, payload', 'safe', 'on'=>'search'),
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
			'venta_todo_pago_status_id' => 'Venta Todo Pago Status',
			'operationid' => 'Operationid',
			'statusCode' => 'Status Code',
			'statusMessage' => 'Status Message',
			'authorizationKey' => 'Authorization Key',
			'encodingMethod' => 'Encoding Method',
			'payload' => 'Payload',
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

		$criteria->compare('venta_todo_pago_status_id',$this->venta_todo_pago_status_id);
		$criteria->compare('operationid',$this->operationid);
		$criteria->compare('statusCode',$this->statusCode);
		$criteria->compare('statusMessage',$this->statusMessage,true);
		$criteria->compare('authorizationKey',$this->authorizationKey,true);
		$criteria->compare('encodingMethod',$this->encodingMethod,true);
		$criteria->compare('payload',$this->payload,true);

		return new CActiveDataProvider($this, array(
			'pagination' => array(  
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
		));
	}
}