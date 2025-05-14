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
<h2>Add Return Invoice</h2>

<!-- Customer Select -->
<label>Customer Name</label>
<select id="customer_select">
    <option value="">Select Customer</option>
    <?php foreach($customers as $cust): ?>
        <option value="<?= $cust->id ?>"><?= $cust->name ?></option>
    <?php endforeach; ?>
</select>

<button type="button" id="load_invoice_btn">Load Invoice</button>

<label style="margin-left: 23%"> Return Invoice No</label>
<input type="text" value="<?= $invoice_no ?>">

<br><br>
<label>Address</label><br>
<textarea  id="customer_address" rows="3" cols="30" readonly></textarea>

<label style="margin-left: 30%">Return Date</label>
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

<button id="save_invoice">Save Return Invoice</button>


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
            <td><?= $inv->return_no ?></td>
            <td><?= $inv->customer_name ?></td>
            <td><?= $inv->return_date ?></td>
            <td><?= $inv->total_qty ?></td>
            <td><?= number_format($inv->total_amount, 2) ?></td>
            <td>
                <button class="edit_invoice_btn" data-id="<?= $inv->id ?>" style="margin-right: 10px;">Edit</button>
                <a href="<?= site_url('return_invoice/delete/'.$inv->id) ?>"  onclick="return confirm('Are you sure you want to delete this invoice?')" style="color: red;">Delete</a>
   
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<br><br><br><br>
<input type="hidden" id="invoice_id" value=""> 
<!-- popup model -->
<div id="invoice_modal" style="display:none; position:fixed; top:20%; left:30%; background:#fff; border:1px solid #000; padding:20px; z-index:999;">
    <h3>Customer Invoices</h3>
    <table border="1" cellpadding="5" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Invoice No</th>
                <th>Amount</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="invoice_list">
            <!-- ajax data load -->
        </tbody>
    </table>
    <button style="margin-top: 10px;" onclick="$('#invoice_modal').hide();">Close</button>
</div>


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
    $('.item_select').change(function(){
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
        var invoice_id = $('#invoice_id').val();

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
            url: '<?= site_url("return_invoice/save") ?>',
            type: 'POST',
            data: {
                customer_id: customer_id,
                invoice_date: invoice_date,
                invoice_id: invoice_id,
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
        window.location.href = '<?= site_url("return_invoice/edit/") ?>' + invoice_id;
    });

    $('#load_invoice_btn').click(function(){
        var customer_id = $('#customer_select').val();
        if(customer_id == ''){
            alert('Please select a customer first.');
            return;
        }

        $.ajax({
            url: '<?= site_url("return_invoice/get_customer_invoices") ?>',
            type: 'POST',
            data: {customer_id: customer_id},
            dataType: 'json',
            success: function(invoices){
                var html = '';
                if(invoices.length > 0){
                    $.each(invoices, function(i, inv){
                        html += '<tr>';
                        html += '<td>'+inv.invoice_no+'</td>';
                        html += '<td>'+parseFloat(inv.total_amount).toFixed(2)+'</td>';
                        html += '<td><button type="button" class="select_invoice" data-id="'+inv.id+'" data-no="'+inv.invoice_no+'" data-amount="'+inv.total_amount+'">Select Item</button></td>';
                        html += '</tr>';
                    });
                } else {
                    html = '<tr><td colspan="4">No invoices found for this customer.</td></tr>';
                }
                $('#invoice_list').html(html);
                $('#invoice_modal').show();
            }
        });
    });

    // $(document).on('click', '.select_invoice', function(){
    //     var invoice_id = $(this).data('id');
    //     var invoice_no = $(this).data('no');
    //     var total_amount = $(this).data('amount');

    //     // Fill selected invoice info
    //     $('#selected_invoice_id').val(invoice_id);
    //     $('#selected_invoice_no').val(invoice_no);
    //     $('#selected_invoice_amount').val(total_amount);

    //     // Load invoice items using AJAX
    //     $.ajax({
    //         url: '<?= site_url("return_invoice/get_invoice_items_by_invoice_id") ?>',  // You will build this function in controller
    //         type: 'POST',
    //         data: {invoice_id: invoice_id},
    //         dataType: 'json',
    //         success: function(response){
    //             // Clear table first
    //             $('#invoice_table tbody').empty();

    //             // Append returned items into return form's item table
    //             $.each(response.items, function(i, item){
    //                 var row = '<tr>';
    //                 row += '<td>'+item.item_name+'</td>';
    //                 row += '<td>'+item.description+'</td>';
    //                 row += '<td>'+item.unit+'</td>';
    //                 row += '<td>'+parseFloat(item.price).toFixed(2)+'</td>';
    //                 row += '<td>'+item.qty+'</td>';
    //                 row += '<td>'+parseFloat(item.amount).toFixed(2)+'</td>';
    //                 row += '</tr>';
    //                 $('#invoice_table tbody').append(row);
    //             });

    //             // Close popup
    //             $('#invoice_popup').hide();
    //         }
    //     });
    // });

    $(document).on('click', '.select_invoice', function(){
        var invoice_id = $(this).data('id');

        $.ajax({
        url: '<?= site_url("return_invoice/get_invoice_items_by_invoice_id") ?>',
        type: 'POST',
        data: {invoice_id: invoice_id},
        dataType: 'json',
        success: function(response){
            if(response.status === 'success'){
                var items = response.items;

                $('#invoice_id').val(invoice_id);
                
                // Clear existing rows first
                $('#invoice_table tbody tr').each(function(){
                    var row = $(this);
                    row.find('.item_select').val('');
                    row.find('.description, .unit, .price, .qty, .amount').val('');
                });

                // Now populate rows
                for(var i = 0; i < items.length; i++){
                    var item = items[i];
                    var row = $('#invoice_table tbody tr').eq(i);

                    row.find('.item_select').val(item.item_id);
                    row.find('.description').val(item.description);
                    row.find('.unit').val(item.unit);
                    row.find('.price').val(item.price);
                    row.find('.qty').val(item.qty);
                    row.find('.amount').val(item.amount);
                }

                calculate_totals();

                $('#invoice_modal').modal('hide');

            } else {
                alert(response.message);
            }
        }
        });
    });



    

});
</script>

</body>
</html>
