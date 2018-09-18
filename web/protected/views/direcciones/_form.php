<?php $form=$this->beginWidget('CActiveForm',array(
    'id'=>'clientes-direcciones-form',
    'enableAjaxValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
    'htmlOptions'=>array(
        'class' => 'form-horizontal',
        'role' => 'form'
    )
)); ?>

<?php if(Yii::app()->user->hasFlash('error')): ?><div class="alert alert-error" style="display: block;">
      Error al procesar el formulario.
</div>
<?php endif; ?><?php if(Yii::app()->user->hasFlash('success')): ?><div class="alert alert-success" style="display: block;">
      Datos almacenados.
</div>
<?php endif; ?>

<div class='form-group'>
    <?php echo $form->labelEx($model,'identificacion',array('class'=>'col-sm-2 control-label')); ?>
    <div class='col-sm-4'>
        <?php echo $form->textField($model,'identificacion',array('class'=>'form-control','maxlength'=>100)); ?>
        <?php echo $form->error($model,'identificacion'); ?>
    </div>
</div>

<div class='form-group'>
    <?php echo $form->labelEx($model,'nombre_destinatario',array('class'=>'col-sm-2 control-label')); ?>
    <div class='col-sm-4'>
        <?php echo $form->textField($model,'nombre_destinatario',array('class'=>'form-control','maxlength'=>100)); ?>
        <?php echo $form->error($model,'nombre_destinatario'); ?>
    </div>

    <?php echo $form->labelEx($model,'apellido_destinatario',array('class'=>'col-sm-2 control-label')); ?>
    <div class='col-sm-4'>
        <?php echo $form->textField($model,'apellido_destinatario',array('class'=>'form-control','maxlength'=>100)); ?>
        <?php echo $form->error($model,'apellido_destinatario'); ?>
    </div>
</div>

<div class='form-group'>
    <?php echo $form->labelEx($model,'dni_tipo',array('class'=>'col-sm-2 control-label')); ?>
    <div class='col-sm-2'>
        <?php echo ZHtml::enumDropDownList($model, 'dni_tipo',array('class'=>'form-control','maxlength'=>100)); ?>
        <?php echo $form->error($model,'dni_tipo'); ?>
    </div>

    <?php echo $form->labelEx($model,'dni',array('class'=>'col-sm-2 control-label')); ?>
    <div class='col-sm-4'>
        <?php echo $form->textField($model,'dni',array('class'=>'form-control','maxlength'=>100)); ?>
        <?php echo $form->error($model,'dni'); ?>
    </div>
</div>

<div class='form-group'>
    <?php echo $form->labelEx($model,'domicilio',array('class'=>'col-sm-2 control-label')); ?>
    <div class='col-sm-3'>
        <?php echo $form->textField($model,'domicilio',array('class'=>'form-control','maxlength'=>100)); ?>
        <?php echo $form->error($model,'domicilio'); ?>
    </div>

    <?php echo $form->labelEx($model,'numero',array('class'=>'col-sm-1 control-label')); ?>
    <div class='col-sm-1'>
        <?php echo $form->textField($model,'numero',array('class'=>'form-control','maxlength'=>100)); ?>
        <?php echo $form->error($model,'numero'); ?>
    </div>

    <?php echo $form->labelEx($model,'piso',array('class'=>'col-sm-1 control-label')); ?>
    <div class='col-sm-1'>
        <?php echo $form->textField($model,'piso',array('class'=>'form-control','maxlength'=>100)); ?>
        <?php echo $form->error($model,'piso'); ?>
    </div>

    <?php echo $form->labelEx($model,'depto',array('class'=>'col-sm-1 control-label')); ?>
    <div class='col-sm-1'>
        <?php echo $form->textField($model,'depto',array('class'=>'form-control','maxlength'=>100)); ?>
        <?php echo $form->error($model,'depto'); ?>
    </div>
</div>

<div class='form-group'>
    <?php echo $form->labelEx($model,'prefijo',array('class'=>'col-sm-2 control-label')); ?>
    <div class='col-sm-1'>
        <?php echo $form->textField($model,'prefijo',array('class'=>'form-control','maxlength'=>50)); ?>
        <?php echo $form->error($model,'prefijo'); ?>
    </div>
    <?php echo $form->labelEx($model,'telefono',array('class'=>'col-sm-2 control-label')); ?>
    <div class='col-sm-4'>
        <?php echo $form->textField($model,'telefono',array('class'=>'form-control','maxlength'=>50)); ?>
        <?php echo $form->error($model,'telefono'); ?>
    </div>
</div>

<div class='form-group'>
    <?php echo $form->labelEx($model, 'provincia_id', array('class'=>'col-sm-2 control-label')); ?>
    <div class='col-sm-4'>
        <?php echo $form->dropDownList($model,'provincia_id', Provincias::listProvincias(), array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'provincia_id'); ?>
    </div>

    <?php echo $form->labelEx($model, 'ciudad_id', array('class'=>'col-sm-2 control-label')); ?>
    <div class='col-sm-4'>
        <?php echo $form->dropDownList($model,'ciudad_id', Ciudades::listCiudades($model->provincia_id), array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'ciudad_id'); ?>
    </div>
</div>

<div class='form-group'>
    <?php echo $form->labelEx($model,'cpostal',array('class'=>'col-sm-2 control-label')); ?>
    <div class='col-sm-1'>
        <?php echo $form->textField($model,'cpostal',array('class'=>'form-control','maxlength'=>100)); ?>
        <?php echo $form->error($model,'cpostal'); ?>
    </div>
</div>

<div class='form-group'>
    <?php echo $form->labelEx($model,'predeterminada',array('class'=>'col-sm-2 control-label')); ?>
    <div class='col-sm-1'>
        <?php echo $form->checkBox($model,'predeterminada'); ?>
    </div>
</div>

    <div class="form-group">
        <div class="col-sm-offset-6 col-sm-2">
            <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Crear') : Yii::t('app','Guardar'), array('class' => 'btn btn-mega')); ?>
        </div>
    </div>

<?php $this->endWidget(); ?>
