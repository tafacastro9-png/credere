<!-- Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Formulario Usuarios</h5>
                <button type="button"
        class="btn-close"
        data-bs-dismiss="modal"
        aria-label="Close">
</button>
            </div>

            <div class="modal-body">
                <form id="formUser" method="POST" enctype="multipart/form-data">

                    <!-- ================= DATOS USUARIO ================= -->
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label>Usuario</label>
                                <input type="text" name="usuario" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label>Correo</label>
                                <input type="email" name="correo" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label>Confirmar Password</label>
                                <input type="password" name="password2" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Rol de Usuario</label>
                        <select name="id_rol" class="form-control" required>
                            <option value="">Selecciona una opción</option>
                            <?php
                            $roles = mysqli_query($conexion, "SELECT * FROM roles");
                            while($rol = mysqli_fetch_assoc($roles)){
                                echo "<option value='{$rol['id']}'>{$rol['rol']}</option>";
                            }
                            ?>
                        </select>
                    </div>
					
					<div class="mb-3">
    <label>Foto de Perfil</label>

    <input type="file"
           name="foto_perfil"
           class="form-control"
           accept="image/*">
</div>

                    <!-- ================= PERMISOS POR MÓDULO ================= -->
                    <hr>
                    <h5 class="mb-3">Permisos por Módulo</h5>

                    <?php
                    $modulos = mysqli_query($conexion, "SELECT * FROM modulos ORDER BY nombre ASC");

                    while($modulo = mysqli_fetch_assoc($modulos)){

                        echo "
                        <div class='card mb-3 shadow-sm'>
                            <div class='card-header bg-light'>
                                <strong>{$modulo['nombre']}</strong>
                            </div>
                            <div class='card-body'>
                                <div class='row'>
                        ";

                        $permisos = mysqli_query($conexion,"
                            SELECT * FROM permisos
                            WHERE id_modulo = {$modulo['id']}
                        ");

                        while($permiso = mysqli_fetch_assoc($permisos)){

                            echo "
                            <div class='col-md-4'>
                                <div class='form-check mb-2'>
                                    <input class='form-check-input'
                                           type='checkbox'
                                           name='permisos[]'
                                           value='{$permiso['id']}'
                                           id='permiso{$permiso['id']}'>

                                    <label class='form-check-label'
                                           for='permiso{$permiso['id']}'>
                                        {$permiso['nombre']}
                                    </label>
                                </div>
                            </div>
                            ";
                        }

                        echo "
                                </div>
                            </div>
                        </div>
                        ";
                    }
                    ?>

                    <input type="hidden" name="accion" value="SaveUser">

                </form>
            </div>

            <div class="modal-footer">
                <button type="submit" form="formUser" class="btn btn-primary">
                    Guardar
                </button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    Cancelar
                </button>
            </div>

        </div>
    </div>
</div>