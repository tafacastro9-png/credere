<?php 
include "../includes/configSession.php";
require_once "../includes/permisos.php";
require_once("../includes/db.php"); 
include "../includes/header.php";

// 🔒 Permiso
//if (!isset($_SESSION['permisos']) || 
    //!in_array('caja.ver', $_SESSION['permisos'])) {
?>

<!--<div class="container text-center mt-5">
   <h3 class="text-danger">Acceso restringido</h3>
</div>-->



<style>
.card-caja {
    border-radius: 15px;
    padding: 25px;
    background: linear-gradient(135deg, #6424ff, #249cff);
    color: #fff;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}
.card-caja h1 {
    font-size: 38px;
    font-weight: bold;
}
.table thead {
    background: #f4f7ff;
}
.badge-ingreso {
    background: #28a745;
}
.badge-egreso {
    background: #dc3545;
}
</style>

<div class="container-fluid mt-4">

    <h3 class="mb-4 fw-bold">
        💰 Módulo de Caja
    </h3>

    <?php
    // ============================
    // 🔍 FILTROS
    // ============================
    $tipo = $_GET['tipo'] ?? '';
    $desde = $_GET['desde'] ?? '';
    $hasta = $_GET['hasta'] ?? '';

    $where = "WHERE 1=1";

    if($tipo != ''){
        $where .= " AND tipo = '$tipo'";
    }

    if($desde != ''){
        $where .= " AND DATE(fecha) >= '$desde'";
    }

    if($hasta != ''){
        $where .= " AND DATE(fecha) <= '$hasta'";
    }

    // ============================
    // 💰 SALDO
    // ============================
    $qSaldo = mysqli_query($conexion,"
        SELECT IFNULL(SUM(
            CASE 
                WHEN tipo='INGRESO' THEN valor
                ELSE -valor
            END
        ),0) as saldo
        FROM movimientos_caja
    ");

    $saldo = mysqli_fetch_assoc($qSaldo)['saldo'];
    ?>

    <!-- 💰 TARJETA SALDO -->
    <div class="card-caja mb-4">
        <h5>Saldo actual en caja</h5>
        <h1>$<?= number_format($saldo,0,',','.'); ?></h1>
    </div>

    <!-- 🔍 FILTROS -->
    <form method="GET" class="row mb-4">

        <div class="col-md-3">
            <label>Tipo</label>
            <select name="tipo" class="form-control">
                <option value="">Todos</option>
                <option value="INGRESO" <?= $tipo=='INGRESO'?'selected':''; ?>>Ingreso</option>
                <option value="EGRESO" <?= $tipo=='EGRESO'?'selected':''; ?>>Egreso</option>
            </select>
        </div>

        <div class="col-md-3">
            <label>Desde</label>
            <input type="date" name="desde" value="<?= $desde; ?>" class="form-control">
        </div>

        <div class="col-md-3">
            <label>Hasta</label>
            <input type="date" name="hasta" value="<?= $hasta; ?>" class="form-control">
        </div>

        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-primary w-100">Filtrar</button>
        </div>

    </form>

    <?php
    // ============================
    // 📊 MOVIMIENTOS
    // ============================
    $qMov = mysqli_query($conexion,"
        SELECT *
        FROM movimientos_caja
        $where
        ORDER BY fecha DESC
    ");
    ?>

    <!-- 📊 TABLA -->
    <div class="card-style p-3">
        <div class="table-responsive">
            <table class="table table-hover">

                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Concepto</th>
                        <th>Origen</th>
                        <th>Referencia</th>
                        <th>Valor</th>
                    </tr>
                </thead>

                <tbody>

                <?php if(mysqli_num_rows($qMov) > 0): ?>
                    <?php while($m = mysqli_fetch_assoc($qMov)): ?>
                        <tr>
                            <td><?= $m['fecha']; ?></td>

                            <td>
                                <span class="badge <?= $m['tipo']=='INGRESO'?'badge-ingreso':'badge-egreso'; ?>">
                                    <?= $m['tipo']; ?>
                                </span>
                            </td>

                            <td><?= $m['concepto']; ?></td>

                            <td><?= $m['origen']; ?></td>

                            <td>#<?= $m['referencia_id']; ?></td>

                            <td>
                                <strong style="color:<?= $m['tipo']=='INGRESO'?'green':'red'; ?>">
                                    <?= $m['tipo']=='INGRESO' ? '+' : '-' ?>
                                    $<?= number_format($m['valor'],0,',','.'); ?>
                                </strong>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Sin movimientos</td>
                    </tr>
                <?php endif; ?>

                </tbody>

            </table>
        </div>
    </div>

</div>

<?php include "../includes/footer.php"; ?>