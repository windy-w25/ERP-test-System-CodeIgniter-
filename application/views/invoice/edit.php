<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<h2>Edit Invoice</h2>
<div id="invoice_print">


<label>Customer Name</label>
<select id="customer_select">
    <option value="">Select Customer</option>
    <?php foreach($customers as $cust): ?>
        <option value="<?= $cust->id ?>" <?= ($cust->id == $invoice->customer_id) ? 'selected' : '' ?>>
            <?= $cust->name ?>
        </option>
    <?php endforeach; ?>
</select>

<label style="margin-left: 30%">Invoice No</label>
<input type="text" value="<?= $invoice->invoice_no ?>" readonly>

<br><br>
<label>Address</label><br>
<textarea  id="customer_address" rows="3" cols="30" readonly></textarea>

<label style="margin-left: 30%">Date</label>
<input type="date" id="invoice_date" value="<?= $invoice->invoice_date ?>">

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
        <?php foreach($invoice_items as $item): ?>
        <tr>
            <td>
                <select class="item_select">
                    <option value="">Select Item</option>
                    <?php foreach($items as $itm): ?>
                        <option value="<?= $itm->id ?>" <?= ($itm->id == $item->item_id) ? 'selected' : '' ?>>
                            <?= $itm->name ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td><input type="text" class="description" value="<?= $item->description ?>" readonly></td>
            <td><input type="text" class="unit" value="<?= $item->unit ?>" readonly></td>
            <td><input type="number" style="text-align: right;" class="price" value="<?= $item->price ?>" readonly></td>
            <td><input type="number" style="text-align: right;" class="qty" min="0" value="<?= $item->qty ?>"></td>
            <td><input type="number" style="text-align: right;" class="amount" value="<?= $item->amount ?>" readonly></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<br>
<label>Total Qty:</label> <span id="total_qty"><?= $invoice->total_qty ?></span><br>
<label>Total Amount:</label> <span id="total_amount"><?= number_format($invoice->total_amount, 2) ?></span>
<br><br>
</div>
<button id="update_invoice">Update Invoice</button>
<button id="print_invoice">Print Invoice</button>

<script>
$(document).ready(function(){

    var initial_customer_id = $('#customer_select').val();
    if(initial_customer_id != ''){
        load_customer_address(initial_customer_id);
    }

    $('#customer_select').change(function(){
        var customer_id = $(this).val();
        if(customer_id != ''){
            load_customer_address(customer_id);
        } else {
            $('#customer_address').val('');
        }
    });

    function load_customer_address(customer_id){
        $.ajax({
            url: '<?= site_url("invoice/get_customer_address") ?>',
            type: 'POST',
            data: {customer_id: customer_id},
            dataType: 'json',
            success: function(data){
                $('#customer_address').val(data.address);
            }
        });
    }

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

    $('#update_invoice').click(function(){
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
            url: '<?= site_url("invoice/update_invoice/".$invoice->id) ?>',
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
                    window.location.href = '<?= site_url("invoice") ?>';  // Go back to invoice list
                }
            }
        });

    });

    $('#print_invoice').click(function(){

        $('#invoice_print').find(' textarea').each(function(){
            var $el = $(this);
            if($el.is('textarea')){
                $el.text($el.val());
            }
        });

        var printContents = document.getElementById('invoice_print').outerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload(); 
    });



});
</script>

</body>
</html>
