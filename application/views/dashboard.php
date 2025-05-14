<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #e9f7ef;
        }
        .dashboard-container {
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
        .btn-logout {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }
        .btn-logout:hover {
            background-color: #c82333;
        }
        .dashboard-buttons .btn {
            margin: 5px;
            width: 200px;
        }
    </style>
</head>
<body>

<div class="container dashboard-container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Welcome!</h4>
                </div>
                <div class="card-body text-center">
                    <!-- <p>You have successfully logged in.</p> -->

                    <div class="dashboard-buttons">
                        <a href="<?= base_url('index.php/item'); ?>" class="btn btn-success">Item</a>
                        <a href="<?= base_url('index.php/customer'); ?>" class="btn btn-success">Customer</a>
                        <a href="<?= base_url('index.php/unit'); ?>" class="btn btn-success">Unit</a>
                        <a href="<?= base_url('index.php/invoice'); ?>" class="btn btn-success">Invoice</a>
                        <a href="<?= base_url('index.php/return_invoice'); ?>" class="btn btn-success">Return Invoice</a>
                    </div>

                    <hr>
                    <a href="<?= base_url('index.php/auth/logout'); ?>" class="btn btn-logout">Logout</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
