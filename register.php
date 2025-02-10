<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Gadget Online Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            color: #fff;
            font-family: 'Arial', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            overflow: hidden;
        }

        .card {
            background: rgba(0, 0, 0, 0.76);
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(255, 0, 76, 0.6);
        }

        .form-control {
            background: transparent;
            border: 1px solid #6a0dad;
            color: #fff;

        }

        .form-control:focus {
            box-shadow: 0 0 10px #6a0dad;
            border-color: #6a0dad;
        }

        .btn-custom {
            background: linear-gradient(90deg, #ff004c, #6a0dad, #001eff);
            border: none;
            color: #fff;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            transform: scale(1.1);
            box-shadow: 0 0 15px rgba(255, 0, 76, 0.8);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card p-4">
                    <h2 class="text-center neon-text" style="color:white;">Register</h2>
                    <form action="register_process.php" method="POST">
                        <div class="mb-3">
                            <label for="nama" class="form-label" style="color:white;">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label" style="color:white;">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label" style="color:white;">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <input type="hidden" name="role" value="user">
                        <button type="submit" class="btn btn-custom w-100">Daftar</button>
                    </form>
                    <p style="color:white;" class="text-center mt-3">Sudah punya akun? <a href="login.php" class="text-decoration-none" style="color:rgb(6, 226, 255);">Masuk</a></p>
                    <div class="text-center text-white">
                        <p>Copyright © GadgetAR Online Shop</p>
                        <a href="https://github.com/Arigta?tab=repositories" target="_blank">
                            <img src="https://cdn-icons-png.flaticon.com/512/25/25231.png" alt="GitHub Logo" width="30" height="30" style="filter: invert(1);">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>