<?php
  $db = new mysqli("localhost", "root", "", "invent");
$sql = "SELECT item_no AS ID, c.cat_name AS category, item_name AS Items, Price, balance
        FROM items i
        JOIN categories c ON c.cat_no = i.cat_no
        ";

$res = $db->query($sql);
$fields = $res->fetch_fields();
?>
<table id="myTable" class="table table-striped table-bordered" style="width:100%">
    <thead>
      <tr>
        <?php foreach ($fields as $field): ?>
          <th><?=htmlspecialchars($field->name)?></th>
        <?php endforeach; ?>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($res as $row): ?>
        <tr>
          <?php foreach ($row as $value): ?>
            <td><?=htmlspecialchars($value)?></td>
          <?php endforeach; ?>
          <td>
             <input type="hidden" class="ID" value="<?php echo $row['ID']; ?>">
<button 
  class="btn btn-sm btn-info btn-update" 
  data-id="<?=$row['ID']?>" 
  data-toggle="modal" 
  data-target="#exampleModal">Update</button>

            <button class="btn btn-sm btn-danger btn-delete" data-id="<?=$row['ID']?>">Delete</button>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>