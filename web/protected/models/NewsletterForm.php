<?php

/**
 * NewsletterForm class.
 * NewsletterForm is the data structure for keeping
 * newsletter form data. It is used by the 'newsletter' action of 'SocialNav'.
 */
class NewsletterForm extends CFormModel
{
    public $email;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            // name, email, subject and body are required
            array('email', 'required'),
            // email has to be a valid email address
            array('email', 'email'),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels()
    {
        return array(
            'email' => 'Email',
        );
    }
}