<!-- Modal -->
<div class="modal fade" id="editar<?php echo $fila['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Registro</h5>
                <button type="button" class="close btn btn-light" data-bs-dismiss="modal" aria-label="Close">
                    <span class="mdi mdi-window-close"></span>
                </button>
            </div>

            <div class="modal-body">
                <form id="editarUser<?php echo $fila['id']; ?>">

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Usuario</label>
                                <input type="text" name="usuario" class="form-control"
                                    value="<?php echo $fila['usuario']; ?>" required>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Correo</label>
                                <input type="email" name="correo" class="form-control"
                                    value="<?php echo $fila['correo']; ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Rol de Usuario</label>
                        <select name="id_rol" class="form-control" required>
                            <?php
                            $sql = "SELECT * FROM roles";
                            $resultado = mysqli_query($conexion, $sql);
                            while ($rol = mysqli_fetch_assoc($resultado)) {
                                $selected = ($rol['id'] == $fila['id_rol']) ? "selected" : "";
                                echo "<option value='{$rol['id']}' $selected>{$rol['rol']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- 🔥 SECCIÓN PERMISOS -->
					
					<!-- 🔥 SECCIÓN PERMISOS ORGANIZADOS POR MÓULOS -->
<div class="mt-4">
    <label><strong>Permisos por Módulo</strong></label>

    <?php
    // 1️⃣ Obtener todos los módulos
    $sqlModulos = "SELECT * FROM modulos ORDER BY nombre ASC";
    $resultadoModulos = mysqli_query($conexion, $sqlModulos);

    // 2️⃣ Permisos actuales del usuario
    $sqlUsuarioPermisos = "
        SELECT permiso_id 
        FROM users_permisos 
        WHERE user_id = {$fila['id']}
    ";
    $resultadoUsuarioPermisos = mysqli_query($conexion, $sqlUsuarioPermisos);

    $permisosUsuario = [];
    while ($perm = mysqli_fetch_assoc($resultadoUsuarioPermisos)) {
        $permisosUsuario[] = $perm['permiso_id'];
    }

    // 3️⃣ Recorrer módulos
    while ($modulo = mysqli_fetch_assoc($resultadoModulos)) {

        echo "<hr>";
        echo "<h6 class='mt-3 text-primary'><strong>" . ucfirst($modulo['nombre']) . "</strong></h6>";
        echo "<div class='row'>";

        // 4️⃣ Obtener permisos del módulo actual
        $sqlPermisos = "
            SELECT * 
            FROM permisos 
            WHERE id_modulo = {$modulo['id']}
            ORDER BY nombre ASC
        ";
        $resultadoPermisos = mysqli_query($conexion, $sqlPermisos);

        while ($permiso = mysqli_fetch_assoc($resultadoPermisos)) {

            $checked = in_array($permiso['id'], $permisosUsuario) ? "checked" : "";

            echo "
            <div class='col-md-4'>
                <div class='form-check mb-2'>
                    <input class='form-check-input'
                           type='checkbox'
                           name='permisos[]'
                           value='{$permiso['id']}'
                           id='edit_perm_{$permiso['id']}'
                           $checked>

                    <label class='form-check-label' for='edit_perm_{$permiso['id']}'>
                        {$permiso['nombre']}
                    </label>
                </div>
            </div>
            ";
        }

        echo "</div>";
    }
    ?>
</div>
  

                    <input type="hidden" name="accion" value="editUser">
                    <input type="hidden" name="id" value="<?php echo $fila['id']; ?>">

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary"
                    onclick="editarUser(<?php echo $fila['id']; ?>)">Guardar</button>
                <button type="button" class="btn btn-danger"
                    data-bs-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>

<script>
window.editarUser = function(id) {

    var datosFormulario = $("#editarUser" + id).serialize();

    $.ajax({
        url: '../includes/functions.php',
        type: "POST",
        data: datosFormulario,
        dataType: "json",
        success: function(response) {

            if (response === "correcto") {

                Swal.fire({
                    icon: 'success',
                    title: 'Datos Modificados',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });

            } else {

                Swal.fire({
                    icon: "error",
                    title: "Error al actualizar"
                });

            }
        },
        error: function() {
            Swal.fire({
                icon: "error",
                title: "Error de servidor"
            });
        }
    });
}
</script>