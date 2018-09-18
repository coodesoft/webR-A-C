<?php
/**
 * Created by PhpStorm.
 * User: pablo
 * Date: 08/07/16
 * Time: 16:24
 */
class PHtml extends CHtml
{
    /**
     * Displays a summary of validation errors for one or several models.
     * @param mixed $model the models whose input errors are to be displayed. This can be either
     * a single model or an array of models.
     * @param string $header a piece of HTML code that appears in front of the errors
     * @param string $footer a piece of HTML code that appears at the end of the errors
     * @param array $htmlOptions additional HTML attributes to be rendered in the container div tag.
     * A special option named 'firstError' is recognized, which when set true, will
     * make the error summary to show only the first error message of each attribute.
     * If this is not set or is false, all error messages will be displayed.
     * This option has been available since version 1.1.3.
     * @return string the error summary. Empty if no errors are found.
     * @see CModel::getErrors
     * @see errorSummaryCss
     */
    public static function errorSummary($model,$header=null,$footer=null,$htmlOptions=array())
    {
        $content='';
        if(!is_array($model))
            $model=array($model);
        if(isset($htmlOptions['firstError']))
        {
            $firstError=$htmlOptions['firstError'];
            unset($htmlOptions['firstError']);
        }
        else
            $firstError=false;
        foreach($model as $m)
        {
            foreach($m->getErrors() as $errors)
            {
                foreach($errors as $error)
                {
                    if($error!='')
                        $content.="<li>$error</li>\n";
                    if($firstError)
                        break;
                }
            }
        }
        if($content!=='')
        {
            if($header===null)
                $header='<div class="infobox"><div class="inside"><p>'.Yii::t('yii','Please fix the following input errors:').'</p>';
            if(!isset($htmlOptions['class']))
                $htmlOptions['class']=self::$errorSummaryCss;
            return self::tag('div',$htmlOptions,$header."\n<ul class='styled-list arrow'>\n$content</ul>".$footer . '</div></div>');
        }
        else
            return '';
    }
}