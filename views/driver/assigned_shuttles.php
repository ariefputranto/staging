  <div class="col-lg-10 col-md-10 col-sm-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
  <div class="inner-elements">
        <div class="col-md-12">
		<?php echo $this->session->flashdata('message');?>

    <?php if(isset($param) && ($param == "by_pick_date" || ((bool)strtotime($param)))) { ?>

        <?php echo form_open('driver/assigned_shuttles_by_date');?>
        <div class="col-md-9">
            <div class="col-md-2">
                <label>Select Date</label>
            </div>
            <div class="col-md-3">
                <input type="text" id="date_key" name="date_key" <?php if(((bool)strtotime($param))) echo 'value="'.$param.'"'; ?> >
            </div>

           <input type="submit" value="Search" class="btn btn-info">

          </div>
          </form>

    <?php } ?>

		<?php if($param != "by_pick_date") { ?>

		<table id="example" class="cell-border" cellspacing="0" width="100%">
                   <thead>
                     <tr>
                          <th>#</th>
                          <th><?php if(!empty($this->phrases["shuttle number"])) echo $this->phrases["shuttle number"]; else echo "Shuttle Number";?></th>
                          <th><?php if(!empty($this->phrases["name"])) echo $this->phrases["name"]; else echo "Name";?></th>
                           <th><?php if(!empty($this->phrases["pick date"])) echo $this->phrases["pick date"]; else echo "Pick Date";?></th>
                          <th><?php if(!empty($this->phrases["pickup point"])) echo $this->phrases["pickup point"]; else echo "Pickup Point";?></th>
                          <th><?php if(!empty($this->phrases["dropoff point"])) echo $this->phrases["dropoff point"]; else echo "Drop off Point";?></th>
                          <th><?php if(!empty($this->phrases["action"])) echo $this->phrases["action"]; else echo "Action";?></th>
                        </tr>
                  </thead>
                  <tfoot>
                    <tr>
                          <th>#</th>
                          <th><?php if(!empty($this->phrases["shuttle number"])) echo $this->phrases["shuttle number"]; else echo "Shuttle Number";?></th>
                          <th><?php if(!empty($this->phrases["name"])) echo $this->phrases["name"]; else echo "Name";?></th>
                          <th><?php if(!empty($this->phrases["pick date"])) echo $this->phrases["pick date"]; else echo "Pick Date";?></th>
                          <th><?php if(!empty($this->phrases["pickup point"])) echo $this->phrases["pickup point"]; else echo "Pickup Point";?></th>
                          <th><?php if(!empty($this->phrases["dropoff point"])) echo $this->phrases["dropoff point"]; else echo "Drop off Point";?></th>
                          <th><?php if(!empty($this->phrases["action"])) echo $this->phrases["action"]; else echo "Action";?></th>
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
                          <td><?php echo $row->shuttle_no;?></td>
                          <td><?php echo $row->name;?></td>
                          <td><?php echo $row->pick_date;?></td>
                          <td><?php echo $row->pick_point_name." (".$row->start_time.")";?></td>
                          <td><?php echo $row->drop_point_name." (".$row->destination_time.")";?></td>

                          <td>
              							<a onclick="window.location.href = '<?php echo base_url().'driver/view_passenger/'.$row->shuttle_no.'/'.$row->tlc_id.'/'.$row->pick_date;?>';" class="btn btn-success act-btn" title="<?php if(!empty($this->phrases["view passenger"])) echo $this->phrases["view passenger"]; else echo "View Passenger";?>"><i class="fa fa-eye"></i> </a>

              						</td>
                        </tr>

						<?php } } else 
						{
						//echo "<tr><td colspan='7'>No Records Available.</td></tr>";
							
						}
							 ?>

                  </tbody>
                    </table>
          <?php } ?>
        </div>


      </div>
      </div>
    </div>
  </div>
</section>
