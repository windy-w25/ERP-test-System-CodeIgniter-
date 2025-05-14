<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #e9f7ef;
        }
        .login-container {
            margin-top: 100px;
        }
        .card {
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
            border-radius: 10px;
        }
        .card-header {
            background-color: #28a745;
            color: white;
            text-align: center;
        }
        .btn-primary {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-primary:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        a {
            color: #28a745;
        }
        a:hover {
            color: #1e7e34;
        }
    </style>
</head>
<body>

<div class="container login-container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h4>User Login</h4>
                </div>
                <div class="card-body">
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
                    <?php endif; ?>

                    <?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>

                    <?= form_open('auth/login'); ?>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= set_value('email'); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="<?= base_url('index.php/auth/register'); ?>">Don't have an account? Register</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
