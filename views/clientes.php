<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "1 - INICIO<br>";

include "../includes/configSession.php";

echo "2 - SESSION OK<br>";

require_once "../includes/permisos.php";

echo "3 - PERMISOS OK<br>";

require_once "../includes/header.php";

echo "4 - HEADER OK<br>";

require_once "../includes/db.php";

echo "5 - DB OK<br>";

?>

<h1 style="color:red;">
PRUEBA CLIENTES
</h1>

<?php include "../includes/footer.php"; ?>