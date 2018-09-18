<?php
/**
 * CActiveForm class file.
 */

class UActiveForm extends CActiveForm
{

    public $disableAjaxValidationAttributes = array();

    public function run()
    {
        if(is_array($this->focus))
            $this->focus="#".CHtml::activeId($this->focus[0],$this->focus[1]);

        echo CHtml::endForm();
        $cs=Yii::app()->clientScript;
        if(!$this->enableAjaxValidation && !$this->enableClientValidation || empty($this->attributes))
        {
            if($this->focus!==null)
            {
                $cs->registerCoreScript('jquery');
                $cs->registerScript('CActiveForm#focus',"
                    if(!window.location.hash)
                        $('".$this->focus."').focus();
                ");
            }
            return;
        }

        $options=$this->clientOptions;
        if(isset($this->clientOptions['validationUrl']) && is_array($this->clientOptions['validationUrl']))
            $options['validationUrl']=CHtml::normalizeUrl($this->clientOptions['validationUrl']);

        $options['attributes']=array();
        foreach ($this->attributes as $attr => $item) {
            if (in_array($attr,$this->disableAjaxValidationAttributes)===false) {
                array_push($options['attributes'],$item);
            }
        }

        if($this->summaryID!==null)
            $options['summaryID']=$this->summaryID;

        if($this->focus!==null)
            $options['focus']=$this->focus;

        $options=CJavaScript::encode($options);
        $cs->registerCoreScript('yiiactiveform');
        $id=$this->id;
        $cs->registerScript(__CLASS__.'#'.$id,"\$('#$id').yiiactiveform($options);");
    }

    /**
     * Displays a summary of validation errors for one or several models.
     * This method is very similar to {@link CHtml::errorSummary} except that it also works
     * when AJAX validation is performed.
     * @param mixed $models the models whose input errors are to be displayed. This can be either
     * a single model or an array of models.
     * @param string $header a piece of HTML code that appears in front of the errors
     * @param string $footer a piece of HTML code that appears at the end of the errors
     * @param array $htmlOptions additional HTML attributes to be rendered in the container div tag.
     * @return string the error summary. Empty if no errors are found.
     * @see CHtml::errorSummary
     */
    public function errorSummary($models,$header=null,$footer=null,$htmlOptions=array())
    {
        if(!$this->enableAjaxValidation && !$this->enableClientValidation)
            return CHtml::errorSummary($models,$header,$footer,$htmlOptions);

        if(!isset($htmlOptions['id']))
            $htmlOptions['id']=$this->id.'_es_';
        $html=CHtml::errorSummary($models,$header,$footer,$htmlOptions);
        if($html==='')
        {
            if($header===null)
                $header='<div class="infobox"><div class="inside">' . '<p>'.Yii::t('yii','Please fix the following input errors:').'</p>';
            if(!isset($htmlOptions['class']))
                $htmlOptions['class']=CHtml::$errorSummaryCss;
            $htmlOptions['style']=isset($htmlOptions['style']) ? rtrim($htmlOptions['style'],';').';display:none' : 'display:none';
            $html =
                CHtml::tag('div',$htmlOptions,$header."\n<ul class='styled-list arrow'><li>dummy</li></ul>".$footer . '</div></div>');
        }

        $this->summaryID=$htmlOptions['id'];
        return $html;
    }
}

