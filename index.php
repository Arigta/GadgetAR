<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gadget Online Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            color: #fff;
            font-family: 'Arial', sans-serif;
            overflow-x: hidden;
        }
        .hero {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
            position: relative;
        }
        .hero img {
            max-width: 100%;
            height: auto;
            animation: float 2.5s ease-in-out infinite;
        }
        .btn-custom {
            background: linear-gradient(90deg, #ff004c, #6a0dad, #001eff);
            border: none;
            color: #fff;
            padding: 0.75rem 1.5rem;
            font-size: 1.25rem;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            transform: scale(1.1);
            box-shadow: 0 0 15px rgba(255, 0, 76, 0.8);
        }
        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-20px);
            }
        }
        .neon-text {
            font-size: 3rem;
            font-weight: bold;
            color: #fff;
            text-shadow: 0 0 5px #ff004c, 0 0 10px #ff004c, 0 0 20px #6a0dad, 0 0 30px #001eff;
        }
    </style>
</head>
<body>
    <div class="container hero">
        <div class="row">
            <div class="col-lg-6 text-center text-lg-start d-flex flex-column justify-content-center">
                <h1 class="neon-text">Welcome to Gadget Online Store</h1>
                <p class="mt-4">Discover the latest gadgets with unbeatable deals! Start your journey with us today.</p>
                <div class="mt-4">
                    <a href="login.php" class="btn btn-custom me-3">Masuk</a>
                    <a href="register.php" class="btn btn-custom">Mendaftar</a>
                    
                </div>
            </div>
            <div class="col-lg-6 text-center mt-5 mt-lg-0">
                <img src="asset/img/gadget.png" alt="Gadget" class="img-fluid">
            </div>
        </div>
    </div>
    
    <footer class="bg-dark text-center text-white py-3 mt-5">
        <div class="container">
            <p>Copyright © GadgetAR Online Shop</p>
            <a href="https://github.com/Arigta?tab=repositories" target="_blank">
                <img src="https://cdn-icons-png.flaticon.com/512/25/25231.png" alt="GitHub Logo" width="30" height="30" style="filter: invert(1);">
            </a>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
