<?php

/**
 * This is the model class for table "categorias_campos".
 *
 * The followings are the available columns in table 'categorias_campos':
 * @property integer $categoria_campo_id
 * @property integer $categoria_id
 * @property string $nombre
 * @property string $slug
 * @property integer $orden
 * @property integer $filtro
 * @property integer $tipo_garantia
 */
class CategoriasCampos extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return CategoriasCampos the static model class
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
        return 'categorias_campos';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('nombre, slug, categoria_id', 'required'),
            array('categoria_id, orden, filtro, tipo_garantia', 'numerical', 'integerOnly'=>true),
            array('nombre, slug', 'length', 'max'=>255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('categoria_campo_id, categoria_id, nombre, slug, orden, filtro, tipo_garantia', 'safe', 'on'=>'search'),
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
            'categoria' => array(self::BELONGS_TO, 'Categorias', 'categoria_id'),
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
            'categoria_campo_id' => '#',
            'categoria_id' => 'Categoría',
            'nombre' => 'Nombre',
            'slug' => 'Slug',
            'orden' => 'Orden',
            'filtro' => 'Filtro',
            'tipo_garantia' => 'Es de tipo garantía?',
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

        $criteria->compare('categoria_campo_id',$this->categoria_campo_id);
        $criteria->compare('categoria_id',$this->categoria_id);
        $criteria->compare('nombre',$this->nombre,true);
        $criteria->compare('slug',$this->slug,true);
        $criteria->compare('orden',$this->orden);
        $criteria->compare('filtro',$this->filtro);
        $criteria->compare('tipo_garantia',$this->tipo_garantia);

        return new CActiveDataProvider($this, array(
            'pagination' => array(
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
        ));
    }

    /**
    * Recupero los campos de una categoria
    * @return array de campos de categoria.
    */
    public static function getCamposCategorias($categoria_id, $filtro = null)
    {
        $model = $filtro !== null ?
            CategoriasCampos::model()->findAllByAttributes(array('categoria_id'=>$categoria_id, 'filtro'=>$filtro), array('order'=>'orden'))
            :
            CategoriasCampos::model()->findAllByAttributes(array('categoria_id'=>$categoria_id), array('order'=>'orden'));
        return $model;
    }

    /**
     * Chequeo la existencia del campo para la categoria dada
     *
     **/
    public static function checkCampoExist($slug, $categoria_id)
    {
    	$sqlCheck = "SELECT * FROM categorias_campos
    			WHERE slug = '".$slug."'
    			AND categoria_id = ".$categoria_id;

    	return count(Yii::app()->db->createCommand($sqlCheck)->queryAll())==0 ? true : false;
    }

}