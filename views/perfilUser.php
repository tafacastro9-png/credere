<?php
include_once "../includes/header.php";
include_once "../includes/db.php";

$sql = "SELECT  u.*, r.rol FROM users u LEFT JOIN roles r ON u.id_rol= r.id  WHERE usuario ='$usuario'";
$usuarios = mysqli_query($conexion, $sql);
if ($usuarios->num_rows > 0) {
    foreach ($usuarios as $key => $fila) {
        $ruta_imagen = $fila["imagenPerfil"];
    }
}
?>

<?php
$consulta = "SELECT * FROM datos";
$sql = mysqli_query($conexion, $consulta);
if ($sql->num_rows > 0) {
    foreach ($sql as $key => $filas) {
    }
}
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/profile.css">
</head>

<body>
    <div class="container">
        <main id="main" class="main">
            <div class="pagetitle">
                <h1>Perfil</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </nav>
            </div><!-- End Page Title -->

            <section class="section profile">
                <div class="row">
                    <div class="col-xl-4">

                        <div class="card">
                            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                                <img src="<?php echo $ruta_imagen; ?>" alt="Profile" class="rounded-circle">
                                <h2><?php echo $fila['usuario']; ?></h2>
                                <h3><?php echo $fila['rol']; ?></h3>
                                <div class="social-links mt-2">


                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-xl-8">

                        <div class="card">
                            <div class="card-body pt-3">
                                <!-- Bordered Tabs -->
                                <ul class="nav nav-tabs nav-tabs-bordered">

                                    <li class="nav-item">
                                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Perfil</button>
                                    </li>

                                    <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Editar Perfil</button>
                                    </li>



                                </ul>
                                <div class="tab-content pt-2">

                                    <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                        <br>
                                        <h5 class="card-title">Informacion</h5>
                                        <p class="small " style="text-align: justify;"><h3 class="mb-3">¡Bienvenido a PrestApp - Sistema de Préstamos!</h3>

<p>
    Aquí encontrarás la solución ideal para gestionar tus créditos de manera rápida,
    segura y eficiente. Nuestro sistema te permitirá administrar préstamos, clientes,
    pagos, reportes, estadísticas y mucho más desde una sola plataforma.
</p>

<p>
    Simplifica tus procesos diarios, optimiza el control de tu cartera y lleva una
    administración más organizada de tu negocio financiero. Con PrestApp podrás
    mejorar la experiencia de tus clientes y potenciar el crecimiento de tu empresa.
</p>

<p>
    Descubre todas las herramientas que hemos diseñado para ayudarte a alcanzar una
    gestión más ágil, profesional y efectiva.
</p></p>
                                        <br>
                                        <h5 class="card-title">Detalles de Perfil</h5>

                                        <div class="row">
                                            <div class="col-lg-3 col-md-4 label ">Nombre:</div>
                                            <div class="col-lg-9 col-md-8"><?php echo $fila['usuario']; ?></div>
                                        </div>


                                        <div class="row">
                                            <div class="col-lg-3 col-md-4 label">Correo:</div>
                                            <div class="col-lg-9 col-md-8"><?php echo $fila['correo']; ?></div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-3 col-md-4 label">Trabaja en:</div>
                                            <div class="col-lg-9 col-md-8"><?php echo $filas['empresa']; ?></div>
                                        </div>


                                        <div class="row">
                                            <div class="col-lg-3 col-md-4 label">Tipo de usuario:</div>
                                            <div class="col-lg-9 col-md-8"><?php echo $fila['rol']; ?></div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-3 col-md-4 label">Fecha de Registro:</div>
                                            <div class="col-lg-9 col-md-8"><?php echo $fila['fecha']; ?></div>
                                        </div>




                                    </div>

                                    <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                                        <!-- Profile Edit Form -->

                                        <form id="form" enctype="multipart/form-data">


                                            <div class="row mb-3">
                                                <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Imagen de perfil</label>
                                                <div class="col-md-8 col-lg-9">
                                                    <img src="<?php echo $ruta_imagen; ?>" alt="Profile">
                                                    <div class="pt-2">
                                                        <input type="file" class="form-control" name="imagenPerfil" id="imagenPerfil">
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row mb-3">
                                                <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Nombre</label>
                                                <div class="col-md-8 col-lg-9">
                                                    <input name="usuario" type="text" data-id="<?php echo $fila['id']; ?>" class="form-control" id="usuario" value="<?php echo $fila['usuario']; ?>">
                                                </div>
                                            </div>


                                            <div class="row mb-3">
                                                <label for="company" class="col-md-4 col-lg-3 col-form-label">Correo</label>
                                                <div class="col-md-8 col-lg-9">
                                                    <input name="correo" type="text" class="form-control" id="correo" value="<?php echo $fila['correo']; ?>">
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="Job" class="col-md-4 col-lg-3 col-form-label">Tipó de Usuario</label>
                                                <div class="col-md-8 col-lg-9">
                                                    <input name="id_rol" type="text" class="form-control" id="id_rol" disabled value="<?php echo $fila['rol']; ?>">
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <button type="button" id="editPerfil" class="btn btn-primary">Guardar Cambios</button>
                                            </div>
                                        </form><!-- End Profile Edit Form -->

                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
    </div>

</body>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="../js/editProfileUser.js"></script>

<?php include_once "../includes/footer.php";; ?>

</html>