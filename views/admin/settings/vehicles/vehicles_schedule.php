  <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
		<div class="inner-elements">
        <div class="col-md-12">
		<?php echo $this->session->flashdata('message');?>
		<a onclick="window.location.href = '<?php echo base_url().'settings/change_vehicle';?>';" class="btn btn-success" title="Add New"><?php if(isset($this->phrases["change vehicle"])) echo $this->phrases["change vehicle"]; else echo "Change Vehicle";?> <i class="fa fa-plus"></i></a>&nbsp;|&nbsp;
		
		<a onclick="window.location.href = '<?php echo base_url().'managedriver/change_driver';?>';" class="btn btn-success" title="Add New"><?php if(isset($this->phrases["change driver"])) echo $this->phrases["change driver"]; else echo "Change Driver";?> <i class="fa fa-plus"></i></a>

		<br/><br/>
		<table id="example" class="cell-border example" cellspacing="0" width="100%">
                   <thead>
                     <tr>
                          <th>#</th>
                          <th><?php if(isset($this->phrases["shuttle no"])) echo $this->phrases["shuttle no"]; else echo "Shuttle no";?></th>
                          <th><?php if(isset($this->phrases["Route"])) echo $this->phrases["Route"]; else echo "Route";?></th>
                          <th><?php if(isset($this->phrases["category"])) echo $this->phrases["category"]; else echo "Category";?></th>
                          <th><?php if(isset($this->phrases["passenger capacity"])) echo $this->phrases["passenger capacity"]; else echo "Passenger Capacity";?></th>
                          <th><?php if(isset($this->phrases["start date"])) echo $this->phrases["start date"]; else echo "Start Date";?></th>
                          <th><?php if(isset($this->phrases["end date"])) echo $this->phrases["end date"]; else echo "end date";?></th>
                          <th><?php if(isset($this->phrases["driver"])) echo $this->phrases["driver"]; else echo "Driver";?></th>
                          <!--<th><?php if(isset($this->phrases["total vehicles"])) echo $this->phrases["total vehicles"]; else echo "Total Vehicles";?></th>
                          <th><?php if(isset($this->phrases["status"])) echo $this->phrases["status"]; else echo "Status";?></th>-->
                          <th><?php if(isset($this->phrases["action"])) echo $this->phrases["action"]; else echo "Action";?></th>
                        </tr>
                  </thead>
                  
                      <tbody>

						<?php

							if(count($records) > 0) {

							  $i=1;
							  foreach($records as $row) {

						   ?>

						<tr>
						  <td><?php echo $i++;?></td>
						  <td><?php echo $row->shuttle_no;?></td>
                          <td><?php echo $row->startloc."<code> To </code>".$row->endloc;?></td>
                          <td><?php echo $row->name . ' ('.$row->category.')';?></td>
                          <td><?php echo $row->passenger_capacity;?></td>
                          <td><?php echo $row->special_start;?></td>
                          <td><?php echo $row->special_end;?></td>
                          <td><?php echo $row->username;?></td>
                          <!--<td><?php echo $row->total_vehicles;?></td>
                          <td><?php echo (isset($this->phrases[$row->status])) ? $this->phrases[$row->status] : $row->status;?></td>-->

                          <td>							
							<a data-toggle="modal" data-target="#delRecModal"  onclick="deleteMessage(<?php echo $row->id;?>)" class="btn btn-danger act-btn" title="<?php if(isset($this->phrases["delete"])) echo $this->phrases["delete"]; else echo "Delete";?>"><i class="fa fa-trash"></i> </a>
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
            <h4 class="modal-title" id="myModalLabel"><?php if(isset($this->phrases["delete record"])) echo $this->phrases["delete record"]; else echo "Delete Record";?></h4>
         </div>
         <div class="modal-body">
            <?php if(isset($this->phrases["are you sure to delete the record"])) echo $this->phrases["are you sure to delete the record"]; else echo "Are you sure to delete the Record";?>?
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

	var str = "<?php echo base_url();?>settings/vehicles_schedule/delete/"+x;
	document.getElementById("delete_no").setAttribute("href", str);

   }
   
</script>
