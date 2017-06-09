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
                          <th><?php if(isset($this->phrases["image"])) echo $this->phrases["image"]; else echo "Image";?></th>
                          <th><?php if(isset($this->phrases["name"])) echo $this->phrases["name"]; else echo "Name";?></th>
                          <th><?php if(isset($this->phrases["category"])) echo $this->phrases["category"]; else echo "Category";?></th>
                          <th><?php if(isset($this->phrases["fuel type"])) echo $this->phrases["fuel type"]; else echo "Fuel Type";?></th>
						  <th><?php if(isset($this->phrases["total vehicles"])) echo $this->phrases["total vehicles"]; else echo "Total Vehicles";?></th>
						  <th><?php if(isset($this->phrases["confirmed bookings"])) echo $this->phrases["confirmed bookings"]; else echo "Confirmed Bookings";?></th>
						  <th><?php if(isset($this->phrases["cancelled bookings"])) echo $this->phrases["cancelled bookings"]; else echo "Cancelled Bookings";?></th>
						  <th><?php if(isset($this->phrases["pending bookings"])) echo $this->phrases["pending bookings"]; else echo "Pending Bookings";?></th>
						  <th><?php if(isset($this->phrases["total bookings"])) echo $this->phrases["total bookings"]; else echo "Total Bookings";?></th>

                        </tr>
                  </thead>
                  <tfoot>
                    <tr>
                          <th>#</th>
                          <th><?php if(isset($this->phrases["image"])) echo $this->phrases["image"]; else echo "Image";?></th>
                          <th><?php if(isset($this->phrases["name"])) echo $this->phrases["name"]; else echo "Name";?></th>
                          <th><?php if(isset($this->phrases["category"])) echo $this->phrases["category"]; else echo "Category";?></th>
                          <th><?php if(isset($this->phrases["fuel type"])) echo $this->phrases["fuel type"]; else echo "Fuel Type";?></th>
						  <th><?php if(isset($this->phrases["total vehicles"])) echo $this->phrases["total vehicles"]; else echo "Total Vehicles";?></th>
						  <th><?php if(isset($this->phrases["confirmed bookings"])) echo $this->phrases["confirmed bookings"]; else echo "Confirmed Bookings";?></th>
						  <th><?php if(isset($this->phrases["cancelled bookings"])) echo $this->phrases["cancelled bookings"]; else echo "Cancelled Bookings";?></th>
						  <th><?php if(isset($this->phrases["pending bookings"])) echo $this->phrases["pending bookings"]; else echo "Pending Bookings";?></th>
						  <th><?php if(isset($this->phrases["total bookings"])) echo $this->phrases["total bookings"]; else echo "Total Bookings";?></th>

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
                          <td><?php echo $row->name."<br/><small>".$row->model."</small>";?></td>
                          <td><?php echo $row->category;?></td>
                          <td><?php echo ucwords($row->fuel_type);?></td>
                          <td><?php echo $row->total_vehicles;?></td>
                          <td><?php echo $row->confirmed_bookings;?></td>
                          <td><?php echo $row->cancelled_bookings;?></td>
                          <td><?php echo $row->pending_bookings;?></td>
                          <td><?php echo $row->total_bookings;?></td>

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
