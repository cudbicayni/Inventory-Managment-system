<?php 

class Codes{
	public $db,$result;
	public function setConnect(){
		try{
			$this->db=new mysqli("localhost","root","","invent");
		}catch(Exception $ex){
			die($ex->getMessage());
		}
	}// end of connection method
	public function setSQL($sql){
		try{
			$this->setConnect();
			$r=$this->db->query($sql);
			if($rw=$r->fetch_array(MYSQLI_NUM))
				echo $rw[0];
			else
				echo "failed";
			//echo $r==1?"operation is done":"failed";
			$this->db->close();
		}catch(Exception $ex){
			die($ex->getMessage());
		}
	}// end of setSQL
	public function viewTable($sql){
		$this->setConnect();
		$this->result=$this->db->query($sql);
		$fields=$this->result->fetch_fields();
		?>
		  <div class="row" style="margin-left: 2%;">
        <div class="col-11">
            <div class="card">
                <div class="card-body">
								<h4 class="header-title mt-0 mb-1">Diiwangeli Macamiil</h4>
		<table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                        <button type="button" class="btn btn-primary width-md a" data-bs-toggle="modal" data-bs-target="#centermodal">Add New</button>
                
                        <thead>
                            <tr>
                                <?php foreach ($fields as $field): ?>
                                    <th><?php echo htmlspecialchars($field->name); ?></th>
                                <?php endforeach; ?>
                                <th>Action</th>
                            </tr>
                        </thead>
												<tbody>
												<?php foreach ($this->result as $key => $row): ?>
                                <tr>
																<?php foreach ($row as $key => $value): ?>
                                        <td><?php echo htmlspecialchars($value); ?></td>
                                    <?php endforeach; ?>
                                    <td>
                                        <input class="inp" type="hidden" name="id" value="">
                                        <button type="button" class="btn btn-dark btn-rounded">Update</button>
                                        <button type="button" class="btn btn-danger btn-rounded">Delete</button>
                                    </td>
                                </tr>
																<?php endforeach ?>
                        </tbody>
                    </table>
										</div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
			
			
		<?php		
	}
	// end of viewTable method
	public function fillCombo($sql, $selectedValue = "", $placeholder = "") {
    $this->setConnect();
    $res = $this->db->query($sql);

    if (!empty($placeholder)) {
        echo "<option value=''>{$placeholder}</option>";
    }

    while ($r = $res->fetch_array(MYSQLI_NUM)) {
        $selected = ($r[0] == $selectedValue) ? "selected" : "";
        echo "<option value='{$r[0]}' {$selected}>{$r[1]}</option>";
    }
}

}// end of class
?>