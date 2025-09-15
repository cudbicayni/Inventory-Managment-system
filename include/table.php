<table id="example" class="display table table-bordered table-hover" style="width:100%">
            <thead class="table-dark">
                <tr>
                <?php foreach ($fields as $key => $value): ?>
                      <th><?php echo $value->name; ?></th>
                  <?php endforeach; ?>
                      <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($res as $row): ?>
                <tr>
                <?php foreach ($row as $key => $value): ?>
                    <td><?php echo $value; ?></td>
                <?php endforeach; ?>
                    <td>
                        <input class="inp" type="hidden" name="id" value="<?php echo $row['sos_no']; ?>">
                        <button class="btn btn-sm btn-warning me-1 b1" data-bs-toggle="modal" data-bs-target="#extraLargeModal" data-id="<?php echo $row['sos_no']; ?>">show</button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>