<?php
include "../includes/header.php";
include "../includes/db.php";

// Consulta para obtener los datos de la empresa con id=1
$consulta = mysqli_query($conexion, "SELECT * FROM datos WHERE id = 1 LIMIT 1");
$datos = mysqli_fetch_assoc($consulta); // Al ser un solo registro, usamos fetch_assoc()
?>
<!-- ========== table components start ========== -->
<section class="table-components">
    <div class="container-fluid">
        <!-- ========== title-wrapper start ========== -->
        <br>
        <br>
        <div class="row">
            <div class="col-lg-12">
                <div class="card-style mb-30">
                    <h2 class="mb-10 text-center">CONFIGURACION DE DATOS DE EMPRESA</h2>
                    <br>
                    <br>
                    <br>
                    <form id="form" enctype="multipart/form-data">
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label for="empresa" class="form-label">Nombre de la Empresa</label>
                                <input name="empresa" type="text" data-id="<?php echo $datos['id']; ?>" class="form-control" id="empresa" placeholder="Ej.Creditos de confianza"
                                    value="<?php echo $datos['empresa']; ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" name="telefono" id="telefono" class="form-control"
                                    value="<?php echo $datos['telefono'] ?? ''; ?>" placeholder="Ej.+52 9999538996" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="cp" class="form-label">Código Postal</label>
                                <input type="text" name="cp" id="cp" class="form-control"
                                    value="<?php echo $datos['cp'] ?? ''; ?>" placeholder="Ej.97000" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="calles" class="form-label">Calles</label>
                                <input type="text" name="calles" id="calles" class="form-control"
                                    value="<?php echo $datos['calles'] ?? ''; ?>" placeholder="Ej. C.50 x 49 y 51" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="direccion" class="form-label">Ciudad o Localidad</label>
                                <input type="text" name="direccion" id="direccion" class="form-control"
                                    value="<?php echo $datos['direccion'] ?? ''; ?>" placeholder="Ej.Merida, Yucatan, Mexico" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="representante" class="form-label">Nombre del dueño o Representante</label>
                                <input type="text" name="representante" id="representante" class="form-control"
                                    value="<?php echo $datos['representante'] ?? ''; ?>" placeholder="Ej. Lic.Benito Juarez" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="imagenEmpresa" class="form-label">Logo de la Empresa</label><br>
                                <img src="<?= $datos['imagenEmpresa'] ?>" width="100px" alt="logo" class="mb-2"><br>
                                <input type="file" class="form-control" name="imagenEmpresa" id="imagenEmpresa">
                            </div>

                        </div>

                        <br>
                        <div class="text-center">
                            <button type="submit" id="editDatos" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
                <!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- ========== tables-wrapper end ========== -->
    </div>
    <!-- end container -->
</section>
<!-- ========== table components end ========== -->


<script src="../js/editSettingsEmpr.js"></script>

<?php include "../includes/footer.php"; ?>