<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="<?=$idTabla; ?>">
	<thead>
  	<tr>
			<?php  //se ve si se habilitan los checkbox para eliminar
				if ($permisos["delete"] !== null) {
					echo '<th><a href="javascript:;" onclick="delete_selected()" title="Eliminar seleccionadas">
					<i class="fa fa-trash-o"></i><input type="hidden" name="" class="search_init" /></a></th>';
				}
			?>
			<?php foreach($columns as $column){ ?>
			<th><label><input type="text" name="" class="search_init" /></label></th>
			<?php } ?>
			<?php
				if($permisos["view"] !== null || $permisos["update"] !== null || $permisos["delete"] !== null || isset($botonesExtra)) {
					echo "<th>&nbsp;</th>";
				}
			?>
      
		</tr>
    <tr>
			<?php if ($permisos["delete"] !== null) { ?>
			<th>&nbsp;</th>
			<?php } ?>
			<?php
			//se define el titulo del campo
			for($c=0;$c<count($columns);$c++){ // si el contenido se define por un metodo usamos como titulo el nombre del campo
				if (isset($columns[$c]["campo"]["referencia"]["metodo"])){
					echo '<th>'.$columns[$c]['campo'].'</th>';
				} else {
					echo "<th>".$claseModelo::model()->getAttributeLabel($columns[$c]["campo"])."</th>";
				}
			}?>
			<?php
				if($permisos["view"] !== null || $permisos["update"] !== null || $permisos["delete"] !== null || isset($botonesExtra)) {
					echo "<th>&nbsp;</th>";
				}
			?>
		</tr>
	</thead>
	<tbody>
  	<?php
			if (count($model)>0) {
				foreach($model as $mod){  ?>
  	<tr>
			<?php if ($permisos["delete"] !== null) { ?>
			<td>
        <div class="checkbox">
  				<label>
  					<input type="checkbox" name="datatable_checkbox" class="datatable_checkbox<?=$idTabla; ?>" data-href="<?php echo Yii::app()->createAbsoluteUrl($modulo."/".$actions['delete'].$mod[$mod->tableSchema->primaryKey]) ?>" /><i class="fa fa-square-o small"></i>
  				</label>
  			</div>
			</td>
			<?php } ?>
      <?php
			for($c=0;$c<count($columns);$c++){
					if(isset($columns[$c]["referencia"])){   /// se usa para leer un campo de una tabla relacionada
							if (isset($columns[$c]["referencia"]["Ref"])) {
									echo "<td>".$mod[$columns[$c]["referencia"]["Ref"]][
									$columns[$c]["referencia"]["Fkey"]
									]."</td>";
							} elseif (isset($columns[$c]["referencia"]["metodo"])) { //se usa para ejecutar un metodo del modelo
									echo "<td>".$mod->{$columns[$c]["referencia"]["metodo"]}()."</td>";
								}
							} else {
									echo "<td>".$mod[$columns[$c]["campo"]]."</td>";
							}
			}
			?>
			<?php if($permisos["view"] !== null || $permisos["update"] !== null || $permisos["delete"] !== null || isset($botonesExtra)) { ?>
			<td class="button-column">
        <?php
          if($permisos["view"] !== null && $condiciones["view"] && Yii::app()->user->checkAccess($permisos["view"]))
            echo CHtml::link('<i class="fa fa-eye"></i>',
						[$actions["view"].$mod[$mod->tableSchema->primaryKey]],
						["rel"=>"tooltip","title"=>"Ver","class"=>"view"]);

          if($permisos["update"] !== null && $condiciones["update"] && Yii::app()->user->checkAccess($permisos["update"]))
            echo CHtml::link('<i class="fa fa-pencil"></i>',
						[$actions["update"].$mod[$mod->tableSchema->primaryKey]],
						["rel"=>"tooltip","title"=>"Actualizar","class"=>"update"]);

          if($permisos["delete"] !== null && $condiciones["delete"] && Yii::app()->user->checkAccess($permisos["delete"]))
            echo CHtml::link('<i class="fa fa-trash-o"></i>',
						[$actions["delete"].$mod[$mod->tableSchema->primaryKey]],
						["rel"=>"tooltip","title"=>"Borrar","class"=>"delete".$idTabla]);

					if(isset($botonesExtra)){
						for ($c=0;$c<count($botonesExtra);$c++) {
							echo $mod->{$botonesExtra[$c]["metodo"]}();
						}
					}
        ?>
      </td>
			<?php }  ?>
  	</tr>
    <?php } } ?>
	</tbody>
</table>

<script type="text/javascript">
function MakeSelect2(){
	$('select').select2();
	$('.dataTables_filter').each(function(){
		$(this).find('label input[type=text]').attr('placeholder', 'Search');
	});
}
var oTable;
$(document).ready(function() {
    // Load Datatables and run plugin on tables
	var asInitVals = [];
	oTable = $('#<?=$idTabla; ?>').dataTable( {
		"aaSorting": [[ 0, "asc" ]],
		"sDom": "T<'box-content'<'col-sm-6'f><'col-sm-6 text-right'l><'clearfix'>>rt<'box-content'<'col-sm-6'i><'col-sm-6 text-right'p><'clearfix'>>",
		"sPaginationType": "bootstrap",
		"oLanguage": {
            "sUrl": "//cdn.datatables.net/plug-ins/f2c75b7247b/i18n/Spanish.json"
		},
        "oTableTools": {
			"sSwfPath": "/rac/to/themes/oops/plugins/datatables/copy_csv_xls_pdf.swf",
			"aButtons": [
				"copy",
				"print",
				{
					"sExtends":    "collection",
					"sButtonText": 'Guardar como <span class="caret" />',
					"aButtons":    [ "csv", "xls", "pdf" ]
				}
			]
		},
        bAutoWidth: false
	});
	var header_inputs = $("#<?=$idTabla; ?> thead input");
	header_inputs.on('keyup', function(){
		/* Filter on the column (the index) of this element */
		oTable.fnFilter( this.value, header_inputs.index(this) );
	});
});
</script>


<?php if ($permisos["delete"] !== null) { ?>
<script type="text/javascript">
function delete_selected(){
    if(!confirm('Confirma eliminar el/los registro/s seleccionado/s?')) return false;
    var selected = [];
    $("#preloader").show();
    var checkedItems = $('.datatable_checkbox<?=$idTabla; ?>:checked').length;
    var countItems = 0;
    $('.datatable_checkbox<?=$idTabla; ?>:checked').each(function() {
        var $this = $(this);
        $.ajax({
            url: jQuery(this).data('href')+"?ajax=true",
            type: "POST",
            success: function(data){
                row = $this.closest("tr").get(0);
                oTable.fnDeleteRow(oTable.fnGetPosition(row));
                countItems++;
                if(checkedItems==countItems){
                    $("#preloader").hide();
                }
            },
            error:  function (response, status) {
    		  alert(response.responseText);return false;
              $("#preloader").hide();
            },
        });

    });
    return false;
}
/*<![CDATA[*/
jQuery(function($) {

jQuery(document).on('click','a.delete<?=$idTabla; ?>',function() {
	if(!confirm('Confirma eliminar el/los registro/s seleccionado/s?')) return false;
    var $this = $(this);
    $("#preloader").show();
    var checkedItems = 1;
    var countItems = 0;
    $.ajax({
        url: jQuery(this).attr('href')+"?ajax=true",
        type: "POST",
        success: function(data){
						data = JSON.parse(data);
						if (data['success']){
            	row = $this.closest("tr").get(0);
							oTable.fnDeleteRow(oTable.fnGetPosition(row));
            	countItems++;
							console.log(row);
							console.log(oTable);
						} else {
							alert(data['msg']);
						}
						$("#preloader").hide();
        },
        error:  function (response, status) {
		  alert(response.responseText);return false;
          $("#preloader").hide();
        },
    });

	return false;
});
});
/*]]>*/
</script>
<?php } ?>
