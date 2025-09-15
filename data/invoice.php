<table id="pur_table" class="table table-striped table-bordered" style="width:100%">
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
          <button 
  class="btn btn-sm btn-info btn-updates" 
  data-id="<?= $row['ID'] ?>"
  data-per="<?= $row['per_no'] ?>"
  data-branch="<?= $row['br_no'] ?>"
  data-date="<?= $row['Date'] ?>"
  data-item="<?= $row['item_no'] ?>"
  data-cost="<?= $row['cost'] ?>"
  data-qty="<?= $row['quantity'] ?>"
  data-discount="<?= $row['discount'] ?>"
  data-toggle="modal" 
  data-target="#purchaseModal"
>Update</button>

            <button class="btn btn-sm btn-danger btn-delete" data-id="<?=$row['ID']?>">Delete</button>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>