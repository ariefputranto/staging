  <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
  <div class="inner-elements">
        <div class="col-md-12">
		<?php echo $this->session->flashdata('message');?>

  <table id="example" class="cell-border example datatable" cellspacing="0" width="100%">
                   <thead>
                     <tr>
                          <th>#</th>
                          <th><?php if(isset($this->phrases["booking ref"])) echo $this->phrases["booking ref"]; else echo "Booking Ref";?></th>
                          <th><?php if(isset($this->phrases["name"])) echo $this->phrases["name"]; else echo "Name";?></th>
                          <th><?php if(isset($this->phrases["email"])) echo $this->phrases["email"]; else echo "Email";?></th>
                          <th><?php if(isset($this->phrases["phone"])) echo $this->phrases["phone"]; else echo "Phone";?></th>
                          <th><?php if(isset($this->phrases["pick point"])) echo $this->phrases["pick point"]; else echo "Pick Point";?></th>
                          <th><?php if(isset($this->phrases["drop point"])) echo $this->phrases["drop point"]; else echo "Drop Point";?></th>
                          <th><?php if(isset($this->phrases["pick time"])) echo $this->phrases["pick time"]; else echo "Pick Time";?></th>
                          <th><?php if(isset($this->phrases["vehicle selected"])) echo $this->phrases["vehicle selected"]; else echo "Vehicle Selected";?></th>
                          <th><?php if(isset($this->phrases["booking made"])) echo $this->phrases["booking made"]; else echo "Booking Made";?></th>
                          <th><?php if(isset($this->phrases["status"])) echo $this->phrases["status"]; else echo "Status";?></th>
                          <?php if(isset($param) && $param == "Cancelled") { ?>
                          <th><?php if(isset($this->phrases["cancelled"])) echo $this->phrases["cancelled"]; else echo "Cancelled";?></th>
                          <?php } ?>
                          <th><?php if(isset($this->phrases["action"])) echo $this->phrases["action"]; else echo "Action";?></th>
                        </tr>
                  </thead>
                  <tfoot>
                    <tr>
                          <th>#</th>
                          <th><?php if(isset($this->phrases["booking ref"])) echo $this->phrases["booking ref"]; else echo "Booking Ref";?></th>
                          <th><?php if(isset($this->phrases["name"])) echo $this->phrases["name"]; else echo "Name";?></th>
                          <th><?php if(isset($this->phrases["email"])) echo $this->phrases["email"]; else echo "Email";?></th>
                          <th><?php if(isset($this->phrases["phone"])) echo $this->phrases["phone"]; else echo "Phone";?></th>
                          <th><?php if(isset($this->phrases["pick point"])) echo $this->phrases["pick point"]; else echo "Pick Point";?></th>
                          <th><?php if(isset($this->phrases["drop point"])) echo $this->phrases["drop point"]; else echo "Drop Point";?></th>
                          <th><?php if(isset($this->phrases["pick time"])) echo $this->phrases["pick time"]; else echo "Pick Time";?></th>
                          <th><?php if(isset($this->phrases["vehicle selected"])) echo $this->phrases["vehicle selected"]; else echo "Vehicle Selected";?></th>
                          <th><?php if(isset($this->phrases["booking made"])) echo $this->phrases["booking made"]; else echo "Booking Made";?></th>
                          <th><?php if(isset($this->phrases["status"])) echo $this->phrases["status"]; else echo "Status";?></th>
                          <?php if(isset($param) && $param == "Cancelled") { ?>
                          <th><?php if(isset($this->phrases["cancelled"])) echo $this->phrases["cancelled"]; else echo "Cancelled";?></th>
                          <?php } ?>
                          <th><?php if(isset($this->phrases["action"])) echo $this->phrases["action"]; else echo "Action";?></th>
                        </tr>
                  </tfoot>
                      <tbody>

						<?php
//neatPrint($bookings);
							if(count($bookings) > 0) {

							  $i=1;
							  foreach($bookings as $row) {

								if($row->read_status == '0')
									$class = 'class="unread-app"';
								else
									$class = '';

						   ?>

						<tr <?php echo $class;?>>
                          <td><?php echo $i++;?></td>
                          <td><?php echo $row->booking_ref;?></td>
                          <td><?php echo $row->registered_name;?></td>
                          <td><?php echo $row->email;?></td>
                          <td><?php echo $row->phone;?></td>
                          <td><?php echo $row->pick_point;?></td>
                          <td><?php echo $row->drop_point;?></td>
                          <td><?php echo $row->pick_time;?></td>
                          <td><?php echo $row->shuttle_no;?></td>
                          <td><?php echo explode(',', timespan($row->bookdate, time()))[0];?> <?php if(isset($this->phrases["ago"])) echo $this->phrases["ago"]; else echo "ago";?></td>
                           <td><?php 
						   if($row->payment_type == 'finpayapi')
						   {
							   if($row->booking_status == 'Pending')
									echo 'Payment not yet done';
								elseif($row->booking_status == 'Cancelled' && $row->comments != '')
									echo $row->comments;
								else
									echo $row->booking_status;
						   }
						   else
						   {
							echo $row->booking_status;
						   }
						   ?></td>
                          <?php if(isset($param) && $param == "Cancelled") { ?>
							<td><?php echo explode(',', timespan($row->cancelled_on, time()))[0];?> <?php if(isset($this->phrases["ago"])) echo $this->phrases["ago"]; else echo "ago";?></td>
                          <?php } ?>


                          <td>
							<a <?php if($row->read_status == '0') { ?>onclick="updateReadStatus(<?php echo $row->id;?>); window.location.href = '<?php echo base_url().'admin/viewBookings/details/'.$row->id;?>';" <?php } else { ?> onclick="window.location.href = '<?php echo base_url().'admin/viewBookings/details/'.$row->id;?>';" <?php }?> class="btn btn-info act-btn" title="<?php if(isset($this->phrases["view details"])) echo $this->phrases["view details"]; else echo "View Details";?>"><i class="fa fa-eye"></i> </a>

							<?php //if($row->booking_status == "Pending") 
							{						
								?>

								<a data-toggle="modal" data-target="#confirmModal"  onclick="confirmMessage(<?php echo $row->id;?>)" class="btn btn-success act-btn" title="<?php if(isset($this->phrases["confirm"])) echo $this->phrases["confirm"]; else echo "Confirm";?>"><i class="fa fa-check"></i> </a>

								

							<?php } ?>
							
							<a data-toggle="modal" data-target="#cancelModal"  onclick="cancelMessage(<?php echo $row->id;?>)" class="btn btn-warning act-btn" title="<?php if(isset($this->phrases["cancel"])) echo $this->phrases["cancel"]; else echo "Cancel";?>"><i class="fa fa-close"></i> </a>

							<?php if($this->ion_auth->is_admin()) { ?>
							<a data-toggle="modal" data-target="#delRecModal"  onclick="deleteMessage(<?php echo $row->id;?>)" class="btn btn-danger act-btn" title="<?php if(isset($this->phrases["delete"])) echo $this->phrases["delete"]; else echo "Delete";?>"><i class="fa fa-trash"></i> </a>
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
            <h4 class="modal-title" id="myModalLabel"><?php if(isset($this->phrases["delete booking"])) echo $this->phrases["delete booking"]; else echo "Delete Booking";?></h4>
         </div>
         <div class="modal-body">
            <?php if(isset($this->phrases["are you sure to delete the booking"])) echo $this->phrases["are you sure to delete the booking"]; else echo "Are you sure to delete the Booking";?>?
         </div>
         <div class="modal-footer">
            <a type="button" class="btn btn-default modal-btn" id="delete_no" href=""><?php if(isset($this->phrases["yes"])) echo $this->phrases["yes"]; else echo "Yes";?></a>
            <a type="button" class="btn btn-default modal-btn" data-dismiss="modal"><?php if(isset($this->phrases["no"])) echo $this->phrases["no"]; else echo "No";?></a>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php if(isset($this->phrases["close"])) echo $this->phrases["close"]; else echo "Close";?></span></button>
            <h4 class="modal-title" id="myModalLabel"><?php if(isset($this->phrases["confirm booking"])) echo $this->phrases["confirm booking"]; else echo "Confirm Booking";?></h4>
         </div>
         <div class="modal-body">
            <?php if(isset($this->phrases["are you sure to confirm the booking"])) echo $this->phrases["are you sure to confirm the booking"]; else echo "Are you sure to confirm the Booking";?>?
         </div>
         <div class="modal-footer">
            <a type="button" class="btn btn-default modal-btn" id="cnf_no" href=""><?php if(isset($this->phrases["yes"])) echo $this->phrases["yes"]; else echo "Yes";?></a>
            <a type="button" class="btn btn-default modal-btn" data-dismiss="modal"><?php if(isset($this->phrases["no"])) echo $this->phrases["no"]; else echo "No";?></a>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php if(isset($this->phrases["close"])) echo $this->phrases["close"]; else echo "Close";?></span></button>
            <h4 class="modal-title" id="myModalLabel"><?php if(isset($this->phrases["cancel booking"])) echo $this->phrases["cancel booking"]; else echo "Cancel Booking";?></h4>
         </div>
         <div class="modal-body">
            <?php if(isset($this->phrases["are you sure to cancel the booking"])) echo $this->phrases["are you sure to cancel the booking"]; else echo "Are you sure to cancel the Booking";?>?
         </div>
         <div class="modal-footer">
            <a type="button" class="btn btn-default modal-btn" id="cnl_no" href=""><?php if(isset($this->phrases["yes"])) echo $this->phrases["yes"]; else echo "Yes";?></a>
            <a type="button" class="btn btn-default modal-btn" data-dismiss="modal"><?php if(isset($this->phrases["no"])) echo $this->phrases["no"]; else echo "No";?></a>
         </div>
      </div>
   </div>
</div>

<script>
/****** Delete Message ******/
   function deleteMessage(x){

	var str = "<?php echo base_url();?>admin/viewBookings/delete/"+x;
	document.getElementById("delete_no").setAttribute("href", str);

   }
   
/****** Confirm Message ******/
   function confirmMessage(x){

	var str = "<?php echo base_url();?>admin/viewBookings/Confirmed/"+x;
	document.getElementById("cnf_no").setAttribute("href", str);

   }
   
/****** Cancel Message ******/
   function cancelMessage(x){

	var str = "<?php echo base_url();?>admin/viewBookings/Cancelled/"+x;
	document.getElementById("cnl_no").setAttribute("href", str);

   }

  /*** Update Read Status ***/
  function updateReadStatus(id)
  {
	if(id > 0){

		$.ajax({
			  type: "post",
			  url: "<?php echo base_url();?>admin/updateReadStatus",
			  async: false,
			  data: { id:id, <?php echo $this->security->get_csrf_token_name();?>:"<?php echo $this->security->get_csrf_hash();?>"},
			  success: function(data) {

			  },
			  error: function(){
				alert('Ajax Error');
			  }		  
			}); 

	} 
  }
   
</script>
