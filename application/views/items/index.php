<h2 style="color: #4CAF50;">Item List</h2>
<a href="<?php echo site_url('item/create'); ?>" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Add New Item</a>
<table border="1" cellpadding="5" style="width: 50%; margin-top: 20px; border-collapse: collapse;">
    <thead>
        <tr style="background-color: #4CAF50; color: white;">
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Description</th>
            <th>Unit</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
            <td><?= $item->id ?></td>
            <td><?= $item->name ?></td>
            <td><?= $item->price ?></td>
            <td><?= $item->description ?></td>
            <td><?= $item->unit_name ?></td> 
            <td>
                <a href="<?= site_url('item/edit/' . $item->id) ?>" style="color: #4CAF50; text-decoration: none;">Edit</a> | 
                <a href="<?= site_url('item/delete/' . $item->id) ?>" style="color: #FF0000; text-decoration: none;" onclick="return confirm('Delete this item?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
