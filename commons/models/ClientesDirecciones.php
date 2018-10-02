<?php
/**
 * This is the model class for table "clientes_direcciones".
 *
 * The followings are the available columns in table 'clientes_direcciones':
 * @property integer $cliente_direccion_id
 * @property integer $user_id
 * @property string $identificacion
 * @property string $nombre_destinatario
 * @property string $apellido_destinatario
 * @property string $domicilio
 * @property integer $provincia_id
 * @property integer $ciudad_id
 * @property string $cpostal
 */
class ClientesDirecciones extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ClientesDirecciones the static model class
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
        return 'clientes_direcciones';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, identificacion, nombre_destinatario, apellido_destinatario, dni_tipo, dni, domicilio, numero, prefijo, telefono, provincia_id, ciudad_id, cpostal', 'required'),
            array('user_id, predeterminada, provincia_id, ciudad_id', 'numerical', 'integerOnly'=>true),
            array('identificacion, nombre_destinatario, apellido_destinatario, domicilio, piso, depto, cpostal, telefono, prefijo', 'length', 'max'=>100),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('cliente_direccion_id, user_id, predeterminada, identificacion, dni_tipo, dni, nombre_destinatario, apellido_destinatario, domicilio, provincia_id, ciudad_id, cpostal', 'safe', 'on'=>'search'),
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
            'user' => array(self::BELONGS_TO, 'User', 'user_id')

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
            'cliente_direccion_id' => '#',
            'user_id' => 'User',
            'identificacion' => 'Identificación',
            'nombre_destinatario' => 'Nombre de destinatario',
            'apellido_destinatario' => 'Apellido de destinatario',
            'dni_tipo' => 'Doc. Tipo',
            'dni' => 'Doc. Nro',
            'domicilio' => 'Calle',
            'numero' => 'Nro',
            'piso' => 'Piso',
            'depto' => 'Depto',
            'telefono' => 'Teléfono',
            'prefijo' => 'Prefijo',
            'provincia_id' => 'Provincia',
            'ciudad_id' => 'Ciudad',
            'cpostal' => 'C. Postal',
            'predeterminada' => 'Predeterminada',
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

        $criteria->compare('cliente_direccion_id',$this->cliente_direccion_id);
        $criteria->compare('user_id',$this->user_id);
        $criteria->compare('identificacion',$this->identificacion,true);
        $criteria->compare('nombre_destinatario',$this->nombre_destinatario,true);
        $criteria->compare('apellido_destinatario',$this->apellido_destinatario,true);
        $criteria->compare('domicilio',$this->domicilio,true);
        $criteria->compare('provincia_id',$this->provincia_id);
        $criteria->compare('ciudad_id',$this->ciudad_id);
        $criteria->compare('cpostal',$this->cpostal,true);

        return new CActiveDataProvider($this, array(
            'pagination' => array(
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
        ));
    }

    public function getFullName()
    {
        return trim(implode(' ', array($this->nombre_destinatario, $this->apellido_destinatario)));
    }

    public function getFullDomicilio()
    {
        return trim(implode(' ', array($this->domicilio, $this->numero, $this->piso, $this->depto)));
    }

    public function getTelefono()
    {
        return trim(implode(' ', array($this->prefijo, $this->telefono)));
    }

    public static function getDireccionesByUser()
    {
        $direcciones = ClientesDirecciones::model()->findAllByAttributes(
            array(
                'user_id' => Yii::app()->user->id
            ),
            array(
                'order' => 'predeterminada DESC'
            )
        );

        return $direcciones;
    }

    public static function unsetPredeterminadas()
    {
        $sql = "UPDATE clientes_direcciones
                SET predeterminada = 0
                WHERE user_id = " . Yii::app()->user->id;
        Yii::app()->db->createCommand($sql)->execute();
    }

    /**
     * @param $cliente_direccion_id
     * @return mixed
     * @throws Exception
     */
    public static function findBy($cliente_direccion_id = 0)
    {
        $cd = self::model()->findByPk($cliente_direccion_id);

        return $cd;
    }

    public function obtenerCliente(){

        return Clientes::obtenerClientePorUserID($this->user_id);
    }

    protected function beforeSave(){

        // Solo una direccion puede ser la predeterminada
        if ($this->predeterminada) {
            $sql = "UPDATE clientes_direcciones
                SET predeterminada = 0
                WHERE user_id = " . $this->user_id;
            if (!$this->isNewRecord)
                $sql .= " AND cliente_direccion_id <>".$this->cliente_direccion_id;
            Yii::app()->db->createCommand($sql)->execute();

        }

        // Cuando una direccion se marca como predeterminada se sobreescriben los campos de cliente.
        if ($this->predeterminada) {
            $cliente = $this->obtenerCliente();
            $cliente->provincia_id = $this->provincia_id;
            $cliente->ciudad_id = $this->ciudad_id;
            $cliente->domicilio = $this->getFullDomicilio();
            $cliente->cpostal = $this->cpostal;
            $cliente->telefono1 = $this->prefijo . ' ' . $this->telefono;

            if (!$cliente->save()){
                $this->addError('ClientesDirecciones.predeterminada','Error al actualizar datos principales de direcion del cliente');
                return false;
            }
        }

        return parent::beforeSave();
    }

}
