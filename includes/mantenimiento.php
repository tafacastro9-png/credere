<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sistema en Mantenimiento</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{

            font-family:'Poppins', sans-serif;

            background:
            linear-gradient(
                135deg,
                #0f172a 0%,
                #1e293b 100%
            );

            min-height:100vh;

            display:flex;

            justify-content:center;

            align-items:center;

            overflow:hidden;

            position:relative;
        }

        /* =========================================
           EFECTOS FONDO
        ========================================= */

        .circle{

            position:absolute;

            border-radius:50%;

            background:rgba(255,255,255,0.05);

            animation:float 8s infinite ease-in-out;
        }

        .circle1{
            width:250px;
            height:250px;
            top:-60px;
            left:-60px;
        }

        .circle2{
            width:180px;
            height:180px;
            bottom:-40px;
            right:-40px;
            animation-delay:2s;
        }

        .circle3{
            width:120px;
            height:120px;
            top:50%;
            left:10%;
            animation-delay:4s;
        }

        @keyframes float{

            0%{
                transform:translateY(0px);
            }

            50%{
                transform:translateY(-20px);
            }

            100%{
                transform:translateY(0px);
            }
        }

        /* =========================================
           CARD
        ========================================= */

        .container{

            width:90%;
            max-width:600px;

            background:rgba(255,255,255,0.08);

            backdrop-filter:blur(12px);

            border:1px solid rgba(255,255,255,0.1);

            border-radius:25px;

            padding:60px 50px;

            text-align:center;

            box-shadow:
            0 15px 40px rgba(0,0,0,0.35);

            z-index:10;
        }

        /* =========================================
           ICONO
        ========================================= */

        .icon{

            width:120px;
            height:120px;

            margin:auto;

            margin-bottom:30px;

            border-radius:50%;

            display:flex;

            justify-content:center;

            align-items:center;

            background:
            linear-gradient(
                135deg,
                #f59e0b,
                #f97316
            );

            font-size:55px;

            color:white;

            box-shadow:
            0 10px 30px rgba(249,115,22,0.4);

            animation:pulse 2s infinite;
        }

        @keyframes pulse{

            0%{
                transform:scale(1);
            }

            50%{
                transform:scale(1.05);
            }

            100%{
                transform:scale(1);
            }
        }

        /* =========================================
           TEXTOS
        ========================================= */

        h1{

            color:white;

            font-size:38px;

            font-weight:700;

            margin-bottom:20px;
        }

        p{

            color:#cbd5e1;

            font-size:17px;

            line-height:1.8;

            margin-bottom:15px;
        }

        .badge{

            margin-top:30px;

            display:inline-block;

            padding:12px 22px;

            border-radius:50px;

            background:
            rgba(255,255,255,0.08);

            border:1px solid rgba(255,255,255,0.1);

            color:#f8fafc;

            font-size:14px;

            letter-spacing:1px;
        }

        /* =========================================
           RESPONSIVE
        ========================================= */

        @media(max-width:768px){

            .container{

                padding:40px 25px;
            }

            h1{

                font-size:30px;
            }

            p{

                font-size:15px;
            }

            .icon{

                width:95px;
                height:95px;
                font-size:42px;
            }
        }

    </style>

</head>

<body>

    <!-- EFECTOS -->

    <div class="circle circle1"></div>

    <div class="circle circle2"></div>

    <div class="circle circle3"></div>

    <!-- CARD -->

    <div class="container">

        <div class="icon">
            🛠️
        </div>

        <h1>
            Sistema en Mantenimiento
        </h1>

        <p>

            Estamos realizando mejoras y actualizaciones
            para brindarte una experiencia más rápida,
            segura y estable.

        </p>

        <p>

            Por favor intenta nuevamente en unos minutos.

        </p>

        <div class="badge">

            SISPRE PLUS · ACTUALIZACIÓN EN PROGRESO

        </div>

    </div>

</body>

</html>