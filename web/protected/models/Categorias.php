<?php

/**
 * This is the model class for table "categorias".
 *
 * The followings are the available columns in table 'categorias':
 * @property integer $categoria_id
 * @property integer $categoria_id_padre
 * @property string $nombre
 */
class Categorias extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'categorias';
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
            array('categoria_id_padre', 'numerical', 'integerOnly'=>true),
            array('nombre, imagen', 'length', 'max'=>255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('categoria_id, categoria_id_padre, nombre, imagen', 'safe', 'on'=>'search'),
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
            'padre' => array(self::BELONGS_TO, 'Categorias', 'categoria_id_padre'),
            'hijos' => array(self::HAS_MANY, 'Categorias', 'categoria_id_padre'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'categoria_id' => 'Categoria',
            'categoria_id_padre' => 'Categoria Id Padre',
            'nombre' => 'Nombre',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('categoria_id',$this->categoria_id);
        $criteria->compare('categoria_id_padre',$this->categoria_id_padre);
        $criteria->compare('nombre',$this->nombre,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Categorias the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function getCategoriasFromCategoria($element, $parentId = 0)
    {
        $branch = array();

        $elements = Categorias::model()->findAllByAttributes(
            array(
                'categoria_padre_id' => $element->categoria_id,
                'tiene_atributos' => 1
            )
        );
        foreach ($elements as $e) {
            if ($e->categoria_padre_id == $parentId) {
                $children = Categorias::getCategoriasFromCategoria($e, $e->categoria_id);
                if ($children) {
                    foreach ($children as $child) {
                        $branch[] = $child;
                    }
                    $e->hijos = $children;
                }
                $branch[] = $e->categoria_id;
            }
        }

        return $branch;
    }
}
