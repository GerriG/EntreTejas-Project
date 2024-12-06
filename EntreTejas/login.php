<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./Assets/css/bootstrap.min.css">
    <link id="favicon" rel="icon" href="./Assets/Logo/logoE.png" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert -->
    <title>Inicia Sesión</title>
    <style>
    /* Estilo global para todos los botones de SweetAlert */
    .swal2-confirm, .swal2-cancel {
        border-radius: 20px !important;
    }

    .swal2-popup {
    border-radius: 20px !important;
    }
    </style>
</head>
<body>
    <section class="bg-dark">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col col-xl-10">
                    <div class="card" style="border-radius: 1rem;">
                        <div class="row g-0">
                            <div class="col-md-6 col-lg-5 d-none d-md-block">
                                <img src="./Assets/Fondos/Delivery-short.PNG" alt="login form"
                                    class="img-fluid" style="height:100%; border-radius: 1rem 0 0 1rem;" />
                            </div>
                            <div class="col-md-6 col-lg-7 d-flex align-items-center">
                                <div class="card-body p-4 p-lg-5 text-black">
                                    <form method="POST" action="./config/login.php">
                                    <div class="d-flex align-items-center mb-3 pb-1">
                                        <img src="./Assets/Logo/logo.png" alt="Logo Entre Tejas" style="height: 70px;">
                                    </div>
                                        <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Accede a tu cuenta</h5>
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="email">Correo</label>
                                            <input type="email" id="email" name="email" class="form-control form-control-lg" required />
                                        </div>
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="password">Contraseña</label>
                                            <input type="password" id="password" name="password" class="form-control form-control-lg" required />
                                        </div>
                                        <div class="pt-1 mb-4">
                                            <button class="btn btn-dark btn-lg btn-block" type="submit">Login</button>
                                        </div>
                                        <a class="small text-muted" href=".\pages\ResetPass.html">¿Olvidaste tu contraseña?</a>
                                        <p class="mb-5 pb-lg-2" style="color: #393f81;">¿Aún no tienes cuenta? <a href="./pages/signup.php" style="color: #393f81;">Regístrate ahora</a></p>                                        
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    session_start();
    if (isset($_SESSION['error_message'])) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{$_SESSION['error_message']}',
            });
        </script>";
        unset($_SESSION['error_message']); // Elimina el mensaje después de mostrarlo
    }
    ?>
</body>
</html>
