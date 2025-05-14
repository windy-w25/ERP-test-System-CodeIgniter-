<h2 style="color: #4CAF50; margin-left: 50px;">Edit Item</h2>
<form method="post" style="max-width: 500px; margin-left: 50px; padding: 20px; background-color: #f9f9f9; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
    <label for="name" style="color: #333;">Name:</label><br>
    <input type="text" name="name" value="<?= set_value('name', $item->name) ?>" style="width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ddd; border-radius: 4px;"><br>

    <label for="price" style="color: #333;">Price:</label><br>
    <input type="text" name="price" value="<?= set_value('price', $item->price) ?>" style="width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ddd; border-radius: 4px;"><br>

    <label for="description" style="color: #333;">Description:</label><br>
    <textarea name="description" style="width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ddd; border-radius: 4px; height: 100px;"><?= set_value('description', $item->description) ?></textarea><br>

    <label for="unit_id" style="color: #333;">Unit:</label>
    <select name="unit_id" style="width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ddd; border-radius: 4px;" required>
        <option value="">Select Unit</option>
        <?php foreach ($units as $unit): ?>
            <option value="<?= $unit->id ?>" <?= set_select('unit_id', $unit->id) ?>>
                <?= $unit->name ?>
            </option>
        <?php endforeach; ?>
    </select>
    
    <button type="submit" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
        Update
    </button>
</form>
