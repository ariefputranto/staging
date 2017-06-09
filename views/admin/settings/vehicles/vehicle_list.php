  <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
		<div class="inner-elements">
        <div class="col-md-12">
		<?php echo $this->session->flashdata('message');?>
		<a onclick="window.location.href = '<?php echo base_url().'settings/vehicles/add';?>';" class="btn btn-success" title="Add New"><?php if(isset($this->phrases["add new"])) echo $this->phrases["add new"]; else echo "Add New";?> <i class="fa fa-plus"></i></a>

		<br/><br/>
		<table id="example" class="cell-border example" cellspacing="0" width="100%">
                   <thead>
                     <tr>
                          <th>#</th>
                          <th><?php if(isset($this->phrases["image"])) echo $this->phrases["image"]; else echo "Image";?></th>
                          <th><?php if(isset($this->phrases["name"])) echo $this->phrases["name"]; else echo "Name";?></th>
                          <th><?php if(isset($this->phrases["category"])) echo $this->phrases["category"]; else echo "Category";?></th>
                          <th><?php if(isset($this->phrases["passenger capacity"])) echo $this->phrases["passenger capacity"]; else echo "Passenger Capacity";?></th>
                          <th><?php if(isset($this->phrases["large luggage capacity"])) echo $this->phrases["large luggage capacity"]; else echo "Large Luggage Capacity";?></th>
                          <th><?php if(isset($this->phrases["small luggage capacity"])) echo $this->phrases["small luggage capacity"]; else echo "Small Luggage Capacity";?></th>
                          <th><?php if(isset($this->phrases["vehicle id"])) echo $this->phrases["vehicle id"]; else echo "Vehicle Id";?></th>
                          <th><?php if(isset($this->phrases["total vehicles"])) echo $this->phrases["total vehicles"]; else echo "Total Vehicles";?></th>
                          <th><?php if(isset($this->phrases["status"])) echo $this->phrases["status"]; else echo "Status";?></th>
                          <th><?php if(isset($this->phrases["action"])) echo $this->phrases["action"]; else echo "Action";?></th>
                        </tr>
                  </thead>
                  <tfoot>
                    <tr>
                          <th>#</th>
                          <th><?php if(isset($this->phrases["image"])) echo $this->phrases["image"]; else echo "Image";?></th>
                          <th><?php if(isset($this->phrases["name"])) echo $this->phrases["name"]; else echo "Name";?></th>
                          <th><?php if(isset($this->phrases["category"])) echo $this->phrases["category"]; else echo "Category";?></th>
                          <th><?php if(isset($this->phrases["passenger capacity"])) echo $this->phrases["passenger capacity"]; else echo "Passenger Capacity";?></th>
                          <th><?php if(isset($this->phrases["large luggage capacity"])) echo $this->phrases["large luggage capacity"]; else echo "Large Luggage Capacity";?></th>
                          <th><?php if(isset($this->phrases["small luggage capacity"])) echo $this->phrases["small luggage capacity"]; else echo "Small Luggage Capacity";?></th>
                          <th><?php if(isset($this->phrases["vehicle id"])) echo $this->phrases["vehicle id"]; else echo "Vehicle Id";?></th>
                          <th><?php if(isset($this->phrases["total vehicles"])) echo $this->phrases["total vehicles"]; else echo "Total Vehicles";?></th>
                          <th><?php if(isset($this->phrases["status"])) echo $this->phrases["status"]; else echo "Status";?></th>
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
						  <td><img src="<?php echo base_url();?>uploads/vehicle_images/<?php if($row->image != "" && file_exists('uploads/vehicle_images/'.$row->image)) echo $row->image; else echo "default-car.jpg";?>" width="60"></td>
                          <td><?php echo $row->name;
						  if ( $row->number_plate != '')
							  echo ' ('.$row->number_plate.')';
						  echo "<br/><small>".$row->model."</small>";
						  ?></td>
                          <td><?php echo $row->category;?></td>
                          <td><?php echo $row->passenger_capacity;?></td>
                          <td><?php echo $row->large_luggage_capacity;?></td>
                          <td><?php echo $row->small_luggage_capacity;?></td>
                          <td><?php echo $row->id;?></td>
                          <td><?php echo $row->total_vehicles;?></td>
                          <td><?php echo (isset($this->phrases[$row->status])) ? $this->phrases[$row->status] : $row->status;?></td>

                          <td>
							<a onclick="window.location.href = '<?php echo base_url().'settings/vehicles/edit/'.$row->id;?>';" class="btn btn-success act-btn" title="<?php if(isset($this->phrases["edit"])) echo $this->phrases["edit"]; else echo "Edit";?>"><i class="fa fa-edit"></i> </a>
							
							<!--<a onclick="window.location.href = '<?php echo base_url().'settings/change_vehicle/'.$row->id;?>';" class="btn btn-warning act-btn" title="<?php if(isset($this->phrases["Change Vehicle"])) echo $this->phrases["Change Vehicle"]; else echo "Change Vehicle";?>"><i class="fa fa-cog"></i> </a>-->

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

	var str = "<?php echo base_url();?>settings/vehicles/delete/"+x;
	document.getElementById("delete_no").setAttribute("href", str);

   }
   
</script>
