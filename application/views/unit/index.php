<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Unit List & Create</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        h2 {
            color: #4CAF50;
            margin-left: 50px;
        }

        table {
            width: 90%;
            margin-left: 50px;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        form {
            max-width: 600px;
            margin-left: 50px;
            margin-top: 40px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        label {
            font-size: 14px;
            color: #333;
            display: block;
            margin-top: 10px;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 6px 0 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        .message {
            margin-left: 50px;
            margin-top: 10px;
            padding: 10px;
            background-color: #eaf7e2;
            color: #4CAF50;
            border: 1px solid #4CAF50;
            border-radius: 5px;
            max-width: 600px;
        }
    </style>
</head>
<body>

<h2>Unit List</h2>

<?php if ($this->session->flashdata('message')): ?>
    <div class="message"><?php echo $this->session->flashdata('message'); ?></div>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>Unit Name</th>
            <th>Code</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($units as $unit): ?>
            <tr>
                <td><?= htmlspecialchars($unit->name) ?></td>
                <td><?= htmlspecialchars($unit->code) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2>Add New Unit</h2>

<form method="post" action="<?= site_url('unit/save') ?>">
    <label for="name">Unit Name:</label>
    <input type="text" name="name" id="name" required>

    <label for="code">Unit Code:</label>
    <input type="text" name="code" id="code" required>
    <?= form_error('code', '<div class="error-message" style="color: red;">', '</div>'); ?>

    <button type="submit">Save Unit</button>
</form>

</body>
</html>
