<?php
session_start();

if(isset($_SESSION['admin_id']))
{
    header("Location: forms.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
     <style>

        body{
            margin:0;
            min-height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            background:linear-gradient(135deg, #ffffff, #ffffff);
            font-family:"Segoe UI",sans-serif;
        }

        .login-wrapper{
            width:100%;
            max-width:420px;
            padding:20px;
        }

        .login-card{
            background:#fff;
            border-radius:24px;
            width:40%;
            padding:40px;
            box-shadow:
                0 20px 40px rgba(0,0,0,.15);
        }

        .login-logo{
            width:80px;
            height:80px;
            margin:0 auto 20px;
            border-radius:50%;
            background:#2563eb;
            display:flex;
            justify-content:center;
            align-items:center;
            color:#fff;
            font-size:30px;
            font-weight:700;
        }

        .login-title{
            text-align:center;
            margin-bottom:10px;
            color:#0f172a;
        }

        .login-subtitle{
            text-align:center;
            color:#64748b;
            margin-bottom:30px;
            font-size:14px;
        }

        .form-group{
            margin-bottom:18px;
        }

        .form-group label{
            display:block;
            margin-bottom:8px;
            font-weight:600;
            color:#334155;
        }

        .form-group input{
            width:100%;
            padding:14px;
            border:1px solid #cbd5e1;
            border-radius:12px;
            outline:none;
            transition:.3s;
            box-sizing:border-box;
        }

        .form-group input:focus{
            border-color:#2563eb;
            box-shadow:0 0 0 4px rgba(37,99,235,.15);
        }

        .login-btn{
            width:100%;
            border:none;
            padding:14px;
            border-radius:12px;
            background:#2563eb;
            color:white;
            font-size:15px;
            font-weight:600;
            cursor:pointer;
            transition:.3s;
        }

        .login-btn:hover{
            background:#1d4ed8;
            transform:translateY(-2px);
        }

        .error{
            background:#fee2e2;
            color:#b91c1c;
            padding:12px;
            border-radius:10px;
            margin-bottom:20px;
            border-left:4px solid #dc2626;
        }

        .footer-text{
            text-align:center;
            margin-top:20px;
            color:#94a3b8;
            font-size:13px;
        }

    </style>
</head>
<body>

<div class="login-card">

    <h2>Admin Login</h2>

    <?php if(isset($_GET['error'])): ?>

        <div class="error">
            <?= htmlspecialchars($_GET['error']) ?>
        </div>

    <?php endif; ?>

    <form action="loginQuery.php" method="POST">

        <div class="form-group">
            <label>Email</label>

            <input
                type="email"
                name="email"
                required
            >
        </div>

        <div class="form-group">
            <label>Password</label>

            <input
                type="password"
                name="password"
                required
            >
        </div>

        <button class="login-btn">
            Login
        </button>

    </form>

</div>

</body>
</html>