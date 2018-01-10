<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<input type="hidden" name="id" value="{ROW.id}" />
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td> {LANG.location_name} </td>
				<td><input class="w300 form-control pull-left" type="text" name="location_name" value="{ROW.location_name}"/></td>
			</tr>
            <tr>
				<td> {LANG.location_code} </td>
				<td><input class="w300 form-control pull-left" type="text" name="location_code" value="{ROW.location_code}"/></td>
			</tr>			
		</tbody>
	</table>
	<div class="row text-center"><input type="submit" name="submit" value="{LANG.save}" class="btn btn-primary"/></div>
</form>
<!-- END: main -->