<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<style>
.edit_invoice_btn {
    cursor: pointer;
}

table tr td{
    text-align: right;
}

</style>
<h2>Invoice Form</h2>

<!-- Customer Select -->
<label>Customer Name</label>
<select id="customer_select">
    <option value="">Select Customer</option>
    <?php foreach($customers as $cust): ?>
        <option value="<?= $cust->id ?>"><?= $cust->name ?></option>
    <?php endforeach; ?>
</select>

<label style="margin-left: 30%">Invoice No</label>
<input type="text" value="<?= $invoice_no ?>">

<br><br>
<label>Address</label><br>
<textarea  id="customer_address" rows="3" cols="30" readonly></textarea>

<label style="margin-left: 30%">Date</label>
<input type="date" id="invoice_date" value="<?= date('Y-m-d') ?>">

<br><br>

<table border="1" cellpadding="5" cellspacing="0" id="invoice_table">
    <thead>
        <tr>
            <th>Item</th>
            <th>Description</th>
            <th>Unit</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php for($i=0;$i<5;$i++): ?>
        <tr>
            <td>
                <select class="item_select">
                    <option value="">Select Item</option>
                    <?php foreach($items as $item): ?>
                        <option value="<?= $item->id ?>"><?= $item->name ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td><input type="text" class="description" readonly></td>
            <td><input type="text" class="unit" readonly></td>
            <td><input type="number" class="price" style="text-align:right;" readonly></td>
            <td><input type="number" class="qty" style="text-align:right;" min="0"></td>
            <td><input type="number" class="amount" style="text-align:right;" readonly></td>
        </tr>
        <?php endfor; ?>
    </tbody>
</table>

<br>
<label>Total Qty:</label> <span id="total_qty">0</span><br>
<label>Total Amount:</label> <span id="total_amount">0.00</span>

<br><br>
<button type="button" id="add_row">Add Row</button>
<br><br>
<button id="save_invoice">Save Invoice</button>

<hr>
<br><br><br><br>
<h3>Invoice List</h3>

<table border="1" cellpadding="5" cellspacing="0" width="80%">
    <thead>
        <tr>
            <th>Invoice No</th>
            <th>Customer</th>
            <th>Date</th>
            <th>Total Qty</th>
            <th>Total Amount</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($invoices as $inv): ?>
        <tr>
            <td><?= $inv->invoice_no ?></td>
            <td><?= $inv->customer_name ?></td>
            <td><?= $inv->invoice_date ?></td>
            <td><?= $inv->total_qty ?></td>
            <td><?= number_format($inv->total_amount, 2) ?></td>
            <td>
                <button class="edit_invoice_btn" data-id="<?= $inv->id ?>" style="margin-right: 10px;">Edit</button>
                <a href="<?= site_url('invoice/delete_invoice/'.$inv->id) ?>"  onclick="return confirm('Are you sure you want to delete this invoice?')" style="color: red;">Delete</a>
                <!-- <button id="print_invoice">Print Invoice</button> -->
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<br><br><br><br>

<script>
$(document).ready(function(){

    $('#customer_select').change(function(){
        var customer_id = $(this).val();
        if(customer_id != ''){
            $.ajax({
                url: '<?= site_url("invoice/get_customer_address") ?>',
                type: 'POST',
                data: {customer_id: customer_id},
                dataType: 'json',
                success: function(data){
                    $('#customer_address').val(data.address);
                }
            });
        } else {
            $('#customer_address').val('');
        }
    });

    // Load item 
    $(document).on('change', '.item_select', function() {
        var row = $(this).closest('tr');
        var item_id = $(this).val();
        if(item_id != ''){
            $.ajax({
                url: '<?= site_url("invoice/get_item_details") ?>',
                type: 'POST',
                data: {item_id: item_id},
                dataType: 'json',
                success: function(data){
                    row.find('.description').val(data.description);
                    row.find('.unit').val(data.unit_name);
                    row.find('.price').val(data.price);
                    row.find('.qty').val('');
                    row.find('.amount').val('');
                    calculate_totals();
                }
            });
        } else {
            row.find('.description, .unit, .price, .qty, .amount').val('');
            calculate_totals();
        }
    });

    // qty changes- cal amount
    $('#invoice_table').on('input', '.qty', function(){
        var row = $(this).closest('tr');
        var price = parseFloat(row.find('.price').val());
        var qty = parseFloat($(this).val());
        if(!isNaN(price) && !isNaN(qty)){
            row.find('.amount').val((price * qty).toFixed(2));
        } else {
            row.find('.amount').val('');
        }
        calculate_totals();
    });

    // Cal tot
    function calculate_totals(){
        var total_qty = 0;
        var total_amount = 0;
        $('.qty').each(function(){
            var qty = parseFloat($(this).val());
            if(!isNaN(qty)) total_qty += qty;
        });
        $('.amount').each(function(){
            var amt = parseFloat($(this).val());
            if(!isNaN(amt)) total_amount += amt;
        });
        $('#total_qty').text(total_qty);
        $('#total_amount').text(total_amount.toFixed(2));
    }

    $('#save_invoice').click(function(){
        var customer_id = $('#customer_select').val();
        var invoice_date = $('#invoice_date').val();

        var items = [];
        $('#invoice_table tbody tr').each(function(){
            var row = $(this);
            var item_id = row.find('.item_select').val();
            var description = row.find('.description').val();
            var unit = row.find('.unit').val();
            var price = row.find('.price').val();
            var qty = row.find('.qty').val();
            var amount = row.find('.amount').val();

            if(item_id && qty > 0){
                items.push({
                    item_id: item_id,
                    description: description,
                    unit: unit,
                    price: price,
                    qty: qty,
                    amount: amount
                });
            }
        });

        $.ajax({
            url: '<?= site_url("invoice/save_invoice") ?>',
            type: 'POST',
            data: {
                customer_id: customer_id,
                invoice_date: invoice_date,
                items: items
            },
            dataType: 'json',
            success: function(response){
                alert(response.message);
                if(response.status == 'success'){
                    location.reload();
                }
            }
        });

    });


    $(document).on('click', '.edit_invoice_btn', function(){
        var invoice_id = $(this).data('id');
        window.location.href = '<?= site_url("invoice/edit_invoice/") ?>' + invoice_id;
    });
    

    $('#add_row').click(function() {
        var lastRow = $('#invoice_table tbody tr:last');
        var newRow = lastRow.clone();

        newRow.find('select').val('');
        newRow.find('input').val('');
        newRow.find('select option:selected').prop('selected', false);

        $('#invoice_table tbody').append(newRow);
    });

});
</script>

</body>
</html>
