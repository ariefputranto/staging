  <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
  <div class="inner-elements">
        <div class="col-md-12">
		<?php echo $this->session->flashdata('message');?>

		<table id="example" class="cell-border example" cellspacing="0" width="100%">
                   <thead>
                     <tr>
                          <th>#</th>
                          <th><?php if(isset($this->phrases["table name"])) echo $this->phrases["table name"]; else echo "Table Name";?></th>
                          <th><?php if(isset($this->phrases["action"])) echo $this->phrases["action"]; else echo "Action";?></th>
                        </tr>
                  </thead>
                  <tfoot>
                    <tr>
                          <th>#</th>
                          <th><?php if(isset($this->phrases["table name"])) echo $this->phrases["table name"]; else echo "Table Name";?></th>
                          <th><?php if(isset($this->phrases["action"])) echo $this->phrases["action"]; else echo "Action";?></th>
                        </tr>
                  </tfoot>
                      <tbody>

						<?php

							if(count($records) > 0) {

							  $i=1;
							  foreach($records as $row) {

						   ?>

						<tr>
						  <td><?php echo $i++;?></td>
                          <td><?php echo ucwords(humanize($row));?></td>

                          <td>
							<a onclick="window.location.href = '<?php echo base_url().'export/exportExcel/'.$row;?>';" class="btn btn-success act-btn" title="<?php if(isset($this->phrases["table backup"])) echo $this->phrases["table backup"]; else echo "Table Backup";?>"><i class="fa fa-download"></i> <?php if(isset($this->phrases["backup"])) echo $this->phrases["backup"]; else echo "Backup";?></a>

							<?php if($row != "users") { ?>
								<a data-toggle="modal" data-target="#delRecModal"  onclick="deleteMessage('<?php echo $row;?>')" class="btn btn-danger act-btn" title="<?php if(isset($this->phrases["empty the data in table"])) echo $this->phrases["empty the data in table"]; else echo "Empty the Data in Table";?>"><i class="fa fa-trash"></i> <?php if(isset($this->phrases["empty"])) echo $this->phrases["empty"]; else echo "Empty";?></a>
							<?php } ?>
							</td>
                        </tr>

						<?php } } ?>

                  </tbody>
                    </table>
        </div>
         
         
         
      </div>
      </div>
    </div>
  </div>
</section>


<!-- modal fade -->
<div class="modal fade" id="delRecModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php if(isset($this->phrases["close"])) echo $this->phrases["close"]; else echo "Close";?></span></button>
            <h4 class="modal-title" id="myModalLabel"><?php if(isset($this->phrases["empty the dable data"])) echo $this->phrases["empty the dable data"]; else echo "Empty The Table Data";?></h4>
         </div>
         <div class="modal-body">
            <?php if(isset($this->phrases["are you sure to empty the data in table"])) echo $this->phrases["are you sure to empty the data in table"]; else echo "Are you sure to empty the data in Table";?>?
         </div>
         <div class="modal-footer">
            <a type="button" class="btn btn-default modal-btn" id="delete_no" href=""><?php if(isset($this->phrases["yes"])) echo $this->phrases["yes"]; else echo "Yes";?></a>
            <a type="button" class="btn btn-default modal-btn" data-dismiss="modal"><?php if(isset($this->phrases["no"])) echo $this->phrases["no"]; else echo "No";?></a>
         </div>
      </div>
   </div>
</div>

<script>
/****** Delete Message ******/
   function deleteMessage(x){

	var str = "<?php echo base_url();?>admin/emptyTheTableData/"+x;
	document.getElementById("delete_no").setAttribute("href", str);

   }
   
</script>
