<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Credere</title>

<link rel="shortcut icon" type="image/x-icon" href="../../images/logo_circular.png" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
*{
    box-sizing: border-box;
}
body{
    margin:0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg,#0f2027,#203a43,#2c5364);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.login-card{
    background: #ffffff;
    width:100%;
    max-width:420px;
    padding:45px 35px;
    border-radius:20px;
    box-shadow:0 30px 60px rgba(0,0,0,0.25);
   animation: fadeInUp 0.8s cubic-bezier(.23,1.02,.64,1) forwards;
}

@keyframes fadeIn{
    from{opacity:0; transform:translateY(20px);}
    to{opacity:1; transform:translateY(0);}
}

.logo{
    text-align:center;
    margin-bottom:25px;
}

.logo img{
    width:240px;
    max-width:100%;
    margin-bottom:10px;
}

.logo h4{
    margin-top:15px;
    font-weight:600;
}

.form-group{
    position:relative;
    margin-bottom:20px;
}

.form-group i{
    position:absolute;
    top:14px;
    left:12px;
    color:#888;
}

.form-control{
    width:100%;
    padding:12px 15px 12px 40px;
    border-radius:12px;
    border:1px solid #ddd;
    outline:none;
    transition:0.3s;
    box-sizing:border-box;
}

.form-control:focus{
    border-color:#2c5364;
    box-shadow:0 0 0 3px rgba(44,83,100,0.2);
}

.btn-login{
    width:100%;
    padding:12px;
    border:none;
    border-radius:12px;
    background:linear-gradient(135deg,#2c5364,#203a43);
    color:white;
    font-weight:600;
    transition:0.3s;
}

.btn-login:hover{
    opacity:0.9;
    transform:translateY(-2px);
}

.show-pass{
    font-size:14px;
    cursor:pointer;
    color:#2c5364;
    margin-top:5px;
    display:inline-block;
}

.footer-text{
    text-align:center;
    font-size:13px;
    margin-top:20px;
    color:#777;
}
#alert {
    display: none;
    padding: 12px 15px;
    border-radius: 12px;
    font-size: 14px;
    margin-bottom: 15px;
    animation: fadeAlert 0.3s ease;
}

.alert-error {
    background: #fff5f5;
    border: 1px solid #ffc9c9;
    color: #c92a2a;
}

@keyframes fadeAlert {
    from { opacity: 0; transform: translateY(-6px); }
    to { opacity: 1; transform: translateY(0); }
}
.btn-login.loading {
    opacity: 0.8;
    pointer-events: none;
}

.spinner {
    width: 18px;
    height: 18px;
    border: 2px solid #ffffff;
    border-top: 2px solid transparent;
    border-radius: 50%;
    display: inline-block;
    animation: spin 0.6s linear infinite;
    vertical-align: middle;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>
</head>

<body>

<div class="login-card">

    <div class="logo">
        <img src="/CrederePruebas/images/logo.png">
       
        <p style="color:#666;font-size:14px;">Accede a tu panel financiero</p>
    </div>

    <form id="loginForm" name="loginData" novalidate>
	
	  <div id="alert" style="margin-bottom:15px;"></div>

        <div class="form-group">
            <i class="fa fa-user"></i>
            <input type="email" name="usuario" id="usuario" class="form-control" placeholder="Correo electrónico" required>
        </div>

        <div class="form-group">
            <i class="fa fa-lock"></i>
            <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña" required>
        </div>

        <span class="show-pass" onclick="togglePassword()">
            <i class="fa fa-eye"></i> Mostrar contraseña
        </span>

        <br><br>

<button type="submit" class="btn-login" id="loginBtn">
    <span id="btnText">Iniciar Sesión</span>
</button>

    </form>

    <div class="footer-text">
        © 2026 Ing. Fabian Castro
    </div>

</div>

<script>
function togglePassword(){
    const pass = document.getElementById("password");
    pass.type = pass.type === "password" ? "text" : "password";
}


const usuario = document.getElementById("usuario");
const password = document.getElementById("password");
const alertBox = document.getElementById("alert");

usuario.addEventListener("input", () => {
    alertBox.style.display = "none";
});

password.addEventListener("input", () => {
    alertBox.style.display = "none";
});

</script>

<script src="/CrederePruebas/js/jsloginNuevo.js"></script>

</body>
</html>