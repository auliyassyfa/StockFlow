<?php

// Memulai session
session_start();

if (isset($_SESSION['user_id'])) {

    header("Location: ../dashboard/index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — StockFlow</title>
    <meta name="description" content="Login ke sistem manajemen inventaris StockFlow">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f0ff 0%, #f5f7fa 50%, #eef2ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Decorative circles */
        body::before {
            content: '';
            position: fixed;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(108,99,255,0.1), transparent 60%);
            top: -200px;
            right: -100px;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: fixed;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(139,92,246,0.08), transparent 60%);
            bottom: -150px;
            left: -100px;
            pointer-events: none;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .login-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            padding: 20px;
            animation: fadeInUp 0.45s ease-out;
        }

        /* Logo */
        .login-logo {
            text-align: center;
            margin-bottom: 28px;
        }

        .logo-icon {
            width: 58px;
            height: 58px;
            border-radius: 16px;
            background: linear-gradient(135deg, #6c63ff, #8b5cf6);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.7rem;
            color: #fff;
            margin-bottom: 14px;
            box-shadow: 0 8px 24px rgba(108,99,255,0.35);
        }

        .login-logo h1 {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1e293b;
            letter-spacing: -0.02em;
            margin-bottom: 4px;
        }

        .login-logo p {
            font-size: 0.85rem;
            color: #64748b;
        }

        /* Card */
        .login-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 10px 40px rgba(108,99,255,0.08), 0 4px 16px rgba(0,0,0,0.06);
        }

        .form-group { margin-bottom: 18px; }

        label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 7px;
        }

        .input-wrap { position: relative; }

        .input-wrap i {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.95rem;
            pointer-events: none;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 11px 14px 11px 38px;
            color: #1e293b;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            transition: all 0.22s ease;
            outline: none;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #6c63ff;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(108,99,255,0.12);
        }

        input::placeholder { color: #94a3b8; }

        /* Submit button */
        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #6c63ff, #8b5cf6);
            color: #fff;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            font-weight: 700;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.22s ease;
            box-shadow: 0 4px 14px rgba(108,99,255,0.4);
            margin-top: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(108,99,255,0.5);
        }

        .btn-login:active { transform: translateY(0); }

        /* Error */
        .error-box {
            background: #fee2e2;
            border: 1px solid #fecaca;
            border-left: 3px solid #ef4444;
            border-radius: 8px;
            padding: 11px 14px;
            color: #991b1b;
            font-size: 0.85rem;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Success */
        .success-box {
            background: #d1fae5;
            border: 1px solid #a7f3d0;
            border-left: 3px solid #10b981;
            border-radius: 8px;
            padding: 11px 14px;
            color: #065f46;
            font-size: 0.85rem;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Switch auth */
        .switch-auth {
            text-align: center;
            margin-top: 20px;
            padding-top: 18px;
            border-top: 1px solid #f1f5f9;
            font-size: 0.85rem;
            color: #64748b;
        }

        .switch-auth a {
            color: #6c63ff;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.15s ease;
        }

        .switch-auth a:hover {
            color: #8b5cf6;
            text-decoration: underline;
        }

        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.78rem;
            color: #94a3b8;
        }
    </style>

</head>

<body>

<div class="login-wrapper">

    <!-- Logo & Title -->
    <div class="login-logo">
        <div class="logo-icon">
            <i class="bi bi-box-seam-fill"></i>
        </div>
        <h1>StockFlow</h1>
        <p>Sistem Manajemen Inventaris</p>
    </div>

    <!-- Card -->
    <div class="login-card">

        <?php if(isset($_GET['registered'])) : ?>
        <div class="success-box">
            <i class="bi bi-check-circle-fill"></i>
            Registrasi berhasil! Silakan masuk dengan akun Anda.
        </div>
        <?php endif; ?>

        <?php if(isset($_GET['error'])) : ?>
        <div class="error-box">
            <i class="bi bi-exclamation-circle-fill"></i>
            Username atau Password salah!
        </div>
        <?php endif; ?>

        <form action="proses_login.php" method="POST">

            <!-- Username -->
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-wrap">
                    <i class="bi bi-person"></i>
                    <input
                        id="username"
                        type="text"
                        name="username"
                        placeholder="Masukkan username"
                        required
                        autocomplete="username">
                </div>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <i class="bi bi-lock"></i>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        placeholder="Masukkan password"
                        required
                        autocomplete="current-password">
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right"></i>
                Masuk ke Sistem
            </button>

            <!-- Tautan ke Register -->
            <div class="switch-auth">
                Belum memiliki akun? <a href="register.php">Daftar sekarang</a>
            </div>

        </form>

    </div>

    <div class="login-footer">
        &copy; <?= date('Y') ?> StockFlow — All rights reserved
    </div>

</div>

</body>
</html>