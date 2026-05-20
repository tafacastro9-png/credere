<?php 
include "../includes/configSession.php";
require_once "../includes/permisos.php";
require_once "../includes/db.php";
include "../includes/header.php";

if (!isset($_SESSION['permisos']) || 
    !in_array('usuarios.ver', $_SESSION['permisos'])) {

    echo '
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="card shadow-lg border-0 text-center p-5" style="max-width: 500px; border-radius: 15px;">
            
            <div class="mb-4">
                <i class="bi bi-shield-lock-fill" style="font-size: 60px; color: #dc3545;"></i>
            </div>

            <h3 class="mb-3 fw-bold text-danger">Acceso Restringido</h3>
            <p class="text-muted mb-4">
                No tienes permisos para acceder a este módulo.
            </p>

            <a href="index.php" class="btn btn-primary px-4">
                Volver al inicio
            </a>
        </div>
    </div>
    ';
    exit;
}


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
                    <h2 class="mb-10 text-center">USUARIOS</h2>
                    <br>
                   <?php if(in_array('usuarios.crear', $_SESSION['permisos'])): ?>
<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
    Agregar <i class="fa fa-plus"></i>
</button>
<?php endif; ?>

                    <?php include("./forms/form_user.php"); ?>
                    <br>
                    <br>
                    <div class="table-wrapper table-responsive">
                        <table class="table" id="datatable">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Correo</th>
                                    <th>Rol</th>
                                    <th>FechaRegistro</th>
                                    <th>Action</th>
                                </tr>
                                <!-- end table row-->
                            </thead>
                            <tbody>

                                <?php

                                require_once("../includes/db.php");
                                $result = mysqli_query($conexion, "SELECT * FROM users WHERE id_rol <> 3");
                                while ($fila = mysqli_fetch_assoc($result)) :

                                ?>
                                    <tr>
                                        <td><?php echo $fila['usuario']; ?></td>
                                        <td><?php echo $fila['correo']; ?></td>
                                        <td><?php echo $fila['id_rol']; ?></td>
                                        <td><?php echo $fila['fecha']; ?></td>
                                        <td>
                                            <?php if(in_array('usuarios.editar', $_SESSION['permisos'])): ?>
<button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editar<?php echo $fila['id']; ?>">
    <i class="fa fa-edit"></i>
</button>
<?php endif; ?>
                                           <?php if(in_array('usuarios.password', $_SESSION['permisos'])): ?>
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#change<?php echo $fila['id']; ?>">
    <i class="fa fa-key"></i>
</button>
<?php endif; ?>
                                            <?php if(in_array('usuarios.eliminar', $_SESSION['permisos'])): ?>
<a href="../includes/delete_user.php?id=<?php echo $fila['id'] ?>" class="btn btn-danger btn-del">
    <i class="fa fa-trash"></i>
</a>
<?php endif; ?>
                                        </td>
                                    </tr>

                                    <?php include "./forms/editar_user.php"; ?>
                                    <?php include "./forms/change_password.php"; ?>
                                <?php endwhile; ?>

                            </tbody>
                        </table>
                        <!-- end table -->
                    </div>
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

<?php include "../includes/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).on("submit", "#formUser", function(e){

    e.preventDefault();

    var formData = new FormData(this);

    $.ajax({
        url: "../includes/functions.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",

        success: function(response){

            console.log(response);

            if(response.status === "success"){

                Swal.fire({
                    icon: 'success',
                    title: '¡Usuario creado!',
                    text: 'Se guardó correctamente.',
                    confirmButtonColor: '#0d6efd'
                }).then(() => {
                    location.reload();
                });

            } 
            else if(response.status === "user"){

                Swal.fire({
                    icon: 'warning',
                    title: 'Usuario existente',
                    text: 'El usuario ya existe.',
                    confirmButtonColor: '#ffc107'
                });

            } 
            else if(response.status === "password"){

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Las contraseñas no coinciden.',
                    confirmButtonColor: '#dc3545'
                });

            } 
            else {

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al guardar el usuario.',
                    confirmButtonColor: '#dc3545'
                });

            }
        },

        error: function(xhr){

            console.log(xhr.responseText);

            Swal.fire({
                icon: 'error',
                title: 'Error AJAX',
                text: 'Hubo un problema en la petición.',
                confirmButtonColor: '#dc3545'
            });
        }
    });
});
</script>