<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Invoice</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        h1 {
            color: #4CAF50;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        label {
            font-size: 14px;
            color: #333;
            margin-bottom: 5px;
            display: inline-block;
        }

        input[type="text"], select, input[type="number"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
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
            width: 100%;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        .message {
            padding: 10px;
            margin-bottom: 20px;
            background-color: #eaf7e2;
            color: #4CAF50;
            border: 1px solid #4CAF50;
            border-radius: 5px;
        }

        .error-message {
            padding: 10px;
            margin-bottom: 20px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
        }

        table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>

    <h1 style="margin-left: 50px;">Create Invoice</h1>

    <?php if ($this->session->flashdata('message')): ?>
        <div class="message">
            <p><?php echo $this->session->flashdata('message'); ?></p>
        </div>
    <?php endif; ?>

    <form method="POST" style="margin-left: 50px;" action="<?php echo site_url('invoice/save_invoice'); ?>">
        <label for="customer_name">Customer Name:</label>
        <input type="text" name="customer_name" id="customer_name" required><br><br>

        <label for="item_id">Item:</label>
        <select name="item_id" id="item_id" required>
            <option value="">Select Item</option>
            <?php foreach ($items as $item): ?>
                <option value="<?php echo $item->id; ?>"><?php echo $item->name; ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="price">Item Price:</label>
        <input type="text" id="price" disabled><br><br>

        <label for="description">Item Description:</label>
        <input type="text" id="description" disabled><br><br>

        <button type="submit">Save Invoice</button>
    </form>

    <h2>View Invoices</h2>

    <!-- Table for displaying invoices -->
    <table>
        <tr>
            <th>Invoice ID</th>
            <th>Customer Name</th>
            <th>Item</th>
            <th>Price</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
        <?php foreach ($invoices as $invoice): ?>
        <tr>
            <td><?php echo $invoice->id; ?></td>
            <td><?php echo $invoice->customer_name; ?></td>
            <td><?php echo $invoice->item_name; ?></td>
            <td><?php echo $invoice->price; ?></td>
            <td><?php echo $invoice->description; ?></td>
            <td>
                <a href="<?php echo site_url('invoice/view/'.$invoice->id); ?>">View</a> | 
                <a href="<?php echo site_url('invoice/delete/'.$invoice->id); ?>" onclick="return confirm('Are you sure you want to delete this invoice?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#item_id').change(function() {
                var item_id = $(this).val();
                
                $.ajax({
                    url: '<?php echo site_url("invoice/get_item_details"); ?>',
                    method: 'POST',
                    data: { item_id: item_id },
                    dataType: 'json',
                    success: function(response) {
                        if (response) {
                            $('#price').val(response.price);
                            $('#description').val(response.description);
                        }
                    }
                });
            });
        });
    </script>

</body>
</html>
