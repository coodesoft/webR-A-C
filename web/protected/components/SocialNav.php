<?php
class SocialNav extends CWidget {

    public function run() {

        $model = new NewsletterForm();
        $this->performAjaxValidation($model);

        if (isset($_POST['NewsletterForm'])) {
            $model->attributes = $_POST['NewsletterForm'];
            if ($model->validate()) {
                $ne = new NewsletterEmails();
                $ne->email = $_POST['NewsletterForm']['email'];
                $ne->fecha = date('Y-m-d H:i:s');
                $ne->ip = Commons::getUserIP();
                if ($ne->save()) {
                    Yii::app()->user->setFlash('success','');
                }
            }
        }

        $this->render('SocialNav',
            array(
                'model' => $model,
            )
        );
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax']==='newsletter-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}