<div class="menu-toggle-btn mr-15">

    <button id="menu-toggle"
        class="main-btn primary-btn btn-hover">

        <i class="lni lni-chevron-left me-2"></i>
        Menu

    </button>

</div>

<div class="profile-box ml-15">

    <button class="dropdown-toggle bg-transparent border-0"
        type="button"
        id="profile"
        data-bs-toggle="dropdown"
        aria-expanded="false">

        <div class="profile-info">

            <div class="info">

                <div class="image">
                    <img src="<?php echo $ruta_imagen; ?>" alt="" />
                </div>

                <div>
                    <h6 class="fw-500"><?php echo $usuario; ?></h6>
                    <p><?php echo $user['rol']; ?></p>
                </div>

            </div>

        </div>

    </button>

    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profile">

        <li>
            <a href="../views/perfilUser.php">
                Perfil
            </a>
        </li>

    </ul>

</div>