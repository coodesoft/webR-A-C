<?php

/**
 * This is the model class for table "configuraciones_web".
 *
 * The followings are the available columns in table 'configuraciones_web':
 * @property integer $configuracion_web_id
 * @property integer $mostrar_pedidos_incompletos
 * @property string $menu_keys
 * @property integer $mercado_pago_enabled
 * @property integer $todo_pago_enabled
 */
class ConfiguracionesWeb extends CActiveRecord
{
    const CUOTAS_SOBRE_PRECIO_NO = 'No';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ConfiguracionesWeb the static model class
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
        return 'configuraciones_web';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('mostrar_pedidos_incompletos, comparador_enabled, mercado_pago_enabled, todo_pago_enabled', 'numerical', 'integerOnly'=>true),
            array('email_recibe_notificaciones_ventas, menu_keys, cuotas_sobre_precio', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('configuracion_web_id, menu_keys, mostrar_pedidos_incompletos, mercado_pago_enabled, todo_pago_enabled', 'safe', 'on'=>'search'),
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
            'configuracion_web_id' => 'Configuracion Web',
            'menu_keys' => 'Menu Keys',
            'mercado_pago_enabled' => 'Mercado Pago Enabled',
            'todo_pago_enabled' => 'Todo Pago Enabled',
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

        $criteria->compare('configuracion_web_id',$this->configuracion_web_id);
        $criteria->compare('menu_keys',$this->menu_keys,true);
        $criteria->compare('mercado_pago_enabled',$this->mercado_pago_enabled);
        $criteria->compare('todo_pago_enabled',$this->todo_pago_enabled);

        return new CActiveDataProvider($this, array(
            'pagination' => array(
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
        ));
    }

    public static function getConfigWeb()
    {
        return ConfiguracionesWeb::model()->findByPk(1);
    }
}