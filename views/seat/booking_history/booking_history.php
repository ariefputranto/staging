<section class="apps">
<div class="container">
<div class="row">
<div class="col-lg-12">
<?php echo $this->session->flashdata('message');?>
<div class="cont dynamic-pages">
<table id="example" class="cell-border example" cellspacing="0" width="100%">
<thead>
<tr>
                          <th>#</th>
                          <th><?php echo getPhrase('Ticket Number');?> (<?php echo getPhrase('Status');?>)</th>
                          <th><?php echo getPhrase('Pick Date');?></th>
                          <th><?php echo getPhrase('Pick Point');?></th>
                          <th><?php echo getPhrase('Drop Point');?></th>
                          <th><?php echo getPhrase('Cost of Journey');?></th>
                          <th><?php echo getPhrase('Date of Booking');?></th>
                          <th><?php echo getPhrase('Number of Seats');?></th>
                          <th><?php echo getPhrase('Shuttle Number');?></th>
                          <th><?php echo getPhrase('Ride Status');?></th>
                          <th><?php echo getPhrase('Rating for Ride');?></th>
                          
                        </tr>
                  </thead>
                  <tfoot>
                    <tr>
                          <th>#</th>
                          <th><?php echo getPhrase('Ticket Number');?> (<?php echo getPhrase('Status');?>)</th>
                          <th><?php echo getPhrase('Pick Date');?></th>
                          <th><?php echo getPhrase('Pick Point');?></th>
                          <th><?php echo getPhrase('Drop Point');?></th>
                          <th><?php echo getPhrase('Cost of Journey');?></th>
                          <th><?php echo getPhrase('Date of Booking');?></th>
                          <th><?php echo getPhrase('Number of Seats');?></th>
                          <th><?php echo getPhrase('Shuttle Number');?></th>
                          <th><?php echo getPhrase('Ride Status');?></th>
                          <th><?php echo getPhrase('Rating for Ride');?></th>

						  
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
                          <td><?php echo $row->booking_ref;?> (<?php echo $row->booking_status;?>)</td>
                          <td><?php echo $row->pick_date;?></td>
                          <td><?php echo $row->pick_point;?></td>
                          <td><?php echo $row->drop_point;?></td>
                          <td><?php echo $row->cost_of_journey;?></td>
                          <td><?php echo $row->date_of_booking;?></td>
                          <td><?php echo $row->seat_reserve;?></td>
                          <td><?php echo $row->shuttle_no;?></td>
                          <td><?php echo humanize($row->ride_status);?></td>

						  <td>
								<?php

                  $valid = 0;
                  $today = date('Y-m-d');
                  $cur_time = date('H.i');
                  $cur_time_val = floatval($cur_time); 

                  $pick_time = explode(':', $row->pick_time);

                  $pick_time_formatted = $pick_time[0].'.'.$pick_time[1];

                  $pick_time_val = floatval($pick_time_formatted) - 1; //Cancel time allowed 1 Hr before if on same day.


  								if(strtotime($row->pick_date) >= strtotime($today))
  								{
                      if(strtotime($row->pick_date) == strtotime($today)) {

                          if($cur_time_val <= $pick_time_val)
                            $valid = 1;
                          else
                            $valid = 0;

                      } else {

                          $valid = 1;
                      }

  								} else {

                      $valid = 0;
                  }

                  if($valid == 1) {
                    //echo '<a class="cancel" href="'.base_url().'bookingseat/cancel_ticket/'.$row->booking_ref.'"><i class="flaticon-close"></i> Cancel</a> ';
                  }

								?>
								<?php if($row->ride_status == 'dropped_off') { ?>
								<aside id="rate_<?php echo $row->booking_id.'_'.$row->travel_location_cost_id.'_'.$row->shuttle_no;?>" class="rating1" <?php if($row->user_rating_value > 0) echo 'data-score='.$row->user_rating_value;?>></aside>
								<?php } ?>
              			</td>
						  
                        </tr>

						<?php } } else echo "<tr><td colspan='5'>No Records Available.</td></tr>"; ?>

                  </tbody>
                    </table>
 </div>
      </div>

    </div>
  </div>
</section>


<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery.min.js"></script>
<link href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/jquery.raty.css" rel="stylesheet" media="screen">
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery.raty.js"></script>
<script>
   $('aside.rating1').raty({
   
      path: '<?php echo base_url().$site_theme.'/';?>assets/system_design/raty_images',
      score: function() {
        return $(this).attr('data-score');
      },
      cancel  : true,
      click: function(score, evt) {

         if(score > 0) {

            var id_parts = $(this).attr('id').split('_');

            booking_id              = id_parts[1];
            travel_location_cost_id = id_parts[2];
            shuttle_no              = id_parts[3];

             $.ajax({
               type: "post",
               async: false,
               url: "<?php echo base_url();?>/bookingseat/rateRiding",
               data: { booking_id:booking_id, score:score, travel_location_cost_id:travel_location_cost_id, shuttle_no:shuttle_no, "<?php echo $this->security->get_csrf_token_name();?>":"<?php echo $this->security->get_csrf_hash();?>"},
               success: function(data) {
                 if(data > 0)
                  alert("Thanks for rating.");
               },
               error: function(){
                 alert('Ajax Error');
               }      
           }); 
         }
      }

   });

</script>