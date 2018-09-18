<?php

/**
 * This is the model class for table "clientes".
 *
 * The followings are the available columns in table 'clientes':
 * @property integer $cliente_id
 * @property integer $grupo_id
 * @property string $apellido
 * @property string $nombre
 * @property string $domicilio
 * @property string $email
 * @property string $telefono1
 * @property string $telefono2
 * @property integer $ciudad_id
 * @property integer $provincia_id
 * @property string $cpostal
 * @property string $empresa
 * @property string $cuit
 */
class Clientes extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Clientes the static model class
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
        return 'clientes';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            //array('apellido, nombre, domicilio, email, telefono1, ciudad_id, provincia_id, cpostal', 'required'),
            array('apellido, nombre, user_id', 'required'),
            array('grupo_id, ciudad_id, provincia_id', 'numerical', 'integerOnly'=>true),
            array('apellido, nombre, domicilio, email, telefono1, telefono2, empresa', 'length', 'max'=>100),
            array('cpostal', 'length', 'max'=>20),
            array('cuit', 'length', 'max'=>15),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('cliente_id, grupo_id, apellido, nombre, domicilio, email, telefono1, telefono2, ciudad_id, provincia_id, cpostal, empresa, cuit', 'safe', 'on'=>'search'),
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
            'ciudad' => array(self::BELONGS_TO, 'Ciudades', 'ciudad_id'),
            'provincia' => array(self::BELONGS_TO, 'Provincias', 'provincia_id'),
            'grupo' => array(self::BELONGS_TO, 'ClientesGrupos', 'grupo_id'),
            'ventas' => array(self::HAS_MANY, 'Ventas', 'cliente_id'),
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
            'cliente_id' => '#',
            'grupo_id' => 'Grupo',
            'apellido' => 'Apellido',
            'nombre' => 'Nombre',
            'domicilio' => 'Domicilio',
            'email' => 'Email',
            'telefono1' => 'Teléfono 1',
            'telefono2' => 'Teléfono 2',
            'ciudad_id' => 'Ciudad',
            'provincia_id' => 'Provincia',
            'cpostal' => 'C. Postal',
            'empresa' => 'Empresa',
            'cuit' => 'CUIT',
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

        $criteria->compare('cliente_id',$this->cliente_id);
        $criteria->compare('grupo_id',$this->grupo_id);
        $criteria->compare('apellido',$this->apellido,true);
        $criteria->compare('nombre',$this->nombre,true);
        $criteria->compare('domicilio',$this->domicilio,true);
        $criteria->compare('email',$this->email,true);
        $criteria->compare('telefono1',$this->telefono1,true);
        $criteria->compare('telefono2',$this->telefono2,true);
        $criteria->compare('ciudad_id',$this->ciudad_id);
        $criteria->compare('provincia_id',$this->provincia_id);
        $criteria->compare('cpostal',$this->cpostal,true);
        $criteria->compare('empresa',$this->empresa,true);
        $criteria->compare('cuit',$this->cuit,true);

        return new CActiveDataProvider($this, array(
            'pagination' => array(
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
        ));
    }

    /**
     * Recupero una lista de clientes para un dropDownList
     *
     * @return elemento listData con los clientes.
     *
     * */
    public static function listClientes()
    {
        $clientes = Clientes::arrayClientes();
        return CHtml::listData($clientes,'cliente_id','apellidoYNombre');
    }

    /**
     * @Devuelve el apellido y el nombre de un cliente todo en uno.
     * */
    public function getApellidoYNombre()
    {
        return trim(implode(' ', array($this->nombre, $this->apellido)));
    }

    /**
     * Recupero la lista de clientes
     *
     * @return array de clientes.
     *
     * */
    public static function arrayClientes()
    {
        $criteria = new CDbCriteria();
        $criteria->order = "apellido ASC, nombre ASC";

        return Clientes::model()->findAll($criteria);
    }
}