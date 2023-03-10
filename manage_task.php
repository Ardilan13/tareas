<?php
include 'db_connect.php';
if (isset($_GET['id'])) {
	$qry = $conn->query("SELECT * FROM task_list where id = " . $_GET['id'])->fetch_array();
	foreach ($qry as $k => $v) {
		$$k = $v;
	}
}
?>
<div class="container-fluid">
	<form action="" id="manage-task">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="col-lg-12">
			<div class="row">
				<div class="col-md-5">
					<div class="form-group">
						<label for="">Tarea</label>
						<input type="text" class="form-control form-control-sm" name="task" value="<?php echo isset($task) ? $task : '' ?>" required>
					</div>
					<div class="form-group">
						<label for="">Asignar a</label>
						<select name="employee_id" id="employee_id" class="form-control form-control-sm" required="">
							<option value=""></option>
							<?php
							$employees = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM employee_list order by concat(lastname,', ',firstname,' ',middlename) asc");
							while ($row = $employees->fetch_assoc()) :
							?>
								<option value="<?php echo $row['id'] ?>" <?php echo isset($employee_id) && $employee_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['name'] ?></option>
							<?php endwhile; ?>
						</select>
					</div>
					<div class="form-group">
						<label for="">Fecha de Vencimiento</label>
						<input type="date" class="form-control form-control-sm" name="due_date" value="<?php echo isset($due_date) ? $due_date : date("Y-m-d") ?>" required>
					</div>
					<div class="form-group">
						<label for="">Ultimo Seguimiento</label>
						<input type="text" class="form-control form-control-sm" name="ultimo_seguimiento" value="<?php echo isset($ultimo_seguimiento) ? $ultimo_seguimiento : '' ?>" required>
					</div>
					<div class="form-group">
						<label for="">Codigo</label>
						<input type="text" class="form-control form-control-sm" name="codigo" value="<?php echo isset($codigo) ? $codigo : '' ?>" required>
					</div>
					<div class="form-group">
						<label for="">Tipo</label>
						<select name="tipo" id="tipo" class="form-control form-control-sm" required="">
							<option value=""></option>
							<option value="1" <?php echo isset($tipo) && $tipo == 1 ? 'selected' : '' ?>>Retasacion</option>
							<option value="2" <?php echo isset($tipo) && $tipo == 2 ? 'selected' : '' ?>>T. nueva</option>
						</select>
					</div>
					<div class="form-group">
						<label for="">Honorarios</label>
						<input type="number" min="0" class="form-control form-control-sm" name="honorarios" value="<?php echo isset($honorarios) ? $honorarios : '' ?>" required>
					</div>
					<input type="text" hidden class="form-control form-control-sm" name="ultima_consulta" value="<?php echo date('Y-m-d H:i:s') ?>" required>
					<div class="form-group">
						<label for="">Ubicacion</label>
						<input type="text" class="form-control form-control-sm" name="ubicacion" value="<?php echo isset($ubicacion) ? $ubicacion : '' ?>" required>
					</div>
					<div class="form-group">
						<label for="">Factura</label>
						<input type="text" class="form-control form-control-sm" name="factura" value="<?php echo isset($factura) ? $factura : '' ?>" required>
					</div>
					<div class="form-group">
						<label for="">Oficial</label>
						<input type="text" class="form-control form-control-sm" name="oficial" value="<?php echo isset($oficial) ? $oficial : '' ?>" required>
					</div>
				</div>
				<div class="col-md-7">
					<div class="form-group">
						<label for="">Cliente</label>
						<input type="text" class="form-control form-control-sm" name="cliente" value="<?php echo isset($cliente) ? $cliente : '' ?>" required>
					</div>
					<div class="form-group">
						<label for="">Solicitante</label>
						<input type="text" class="form-control form-control-sm" name="solicitante" value="<?php echo isset($solicitante) ? $solicitante : '' ?>" required>
					</div>
					<div class="form-group">
						<label for="">Sucursal</label>
						<input type="text" class="form-control form-control-sm" name="sucursal" value="<?php echo isset($sucursal) ? $sucursal : '' ?>" required>
					</div>
					<div class="form-group">
						<label for="">Entidad</label>
						<input type="text" class="form-control form-control-sm" name="entidad" value="<?php echo isset($entidad) ? $entidad : '' ?>" required>
					</div>

					<div class="form-group">
						<label for="">Descripción</label>
						<textarea name="description" id="" cols="30" rows="10" class="summernote form-control">
							<?php echo isset($description) ? $description : '' ?>
						</textarea>
					</div>
				</div>
			</div>
		</div>


	</form>
</div>

<script>
	$(document).ready(function() {

		$('#employee_id').select2({
			placeholder: 'Elija empleado',
			width: '100%'
		})

		$('.summernote').summernote({
			height: 200,
			toolbar: [
				['style', ['style']],
				['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
				['fontname', ['fontname']],
				['fontsize', ['fontsize']],
				['color', ['color']],
				['para', ['ol', 'ul', 'paragraph', 'height']],
				['table', ['table']],
				['view', ['undo', 'redo', 'fullscreen', 'codeview', 'help']]
			]
		})
	})

	$('#manage-task').submit(function(e) {
		e.preventDefault()
		start_load()
		$.ajax({
			url: 'ajax.php?action=save_task',
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success: function(resp) {
				if (resp == 1) {
					alert_toast('Datos grabados satisfactoriamente', "Proceso Exitóso");
					setTimeout(function() {
						location.reload()
					}, 1500)
				}
			}
		})
	})
</script>