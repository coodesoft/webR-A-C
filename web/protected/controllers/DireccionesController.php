<?php

class DireccionesController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout='//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions'=>array('index','create','update', 'getCiudadesByProvincia'),
                'users'=>array('@'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'update' page.
     */
    public function actionCreate()
    {
        $model=new ClientesDirecciones;

        $model->user_id = Yii::app()->user->id;
        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if(isset($_POST['ClientesDirecciones']))
        {
            $model->attributes=$_POST['ClientesDirecciones'];
            if ($model->predeterminada) {
                ClientesDirecciones::unsetPredeterminadas();
            }
            if($model->save()){
                Yii::app()->user->setFlash('success','');
                if (isset($_GET['returnTo'])) {
                    $this->redirect(array($_GET['returnTo']));
                } else {
                    $this->redirect(array('update','id'=>$model->cliente_direccion_id));
                }
            }else{
                Yii::app()->user->setFlash('error','');
            }
        }

        $this->render('create',array(
            'model'=>$model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'update' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model=$this->loadModel($id);

        $model->user_id = Yii::app()->user->id;
        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if(isset($_POST['ClientesDirecciones']))
        {
            $model->attributes=$_POST['ClientesDirecciones'];
            if ($model->predeterminada) {
                ClientesDirecciones::unsetPredeterminadas();
            }
            if($model->save()){
                Yii::app()->user->setFlash('success','');
                if (isset($_GET['returnTo'])) {
                    $this->redirect(array($_GET['returnTo']));
                } else {
                    $this->redirect(array('update','id'=>$model->cliente_direccion_id));
                }
            }else{
                Yii::app()->user->setFlash('error','');
            }
        }

        $this->render('update',array(
            'model'=>$model,
        ));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {

        $direcciones = ClientesDirecciones::model()->findAllByAttributes(
            array(
                'user_id' => Yii::app()->user->id
            )
        );


        $this->render('index',array(
            'direcciones' => $direcciones,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model=ClientesDirecciones::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='clientes-direcciones-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Filtrar las ciudades dada una provincia
     */
    public function actionGetCiudadesByProvincia(){
        extract($_POST);

        $ciudades = Ciudades::listCiudades($provincia_id);

        echo CJSON::encode(array("ciudades" => $ciudades));

    }
}
