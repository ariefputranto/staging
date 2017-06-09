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
                          <th><?php if(isset($this->phrases["username"])) echo $this->phrases["username"]; else echo "Username";?></th>
                          <th><?php if(isset($this->phrases["booking reference"])) echo $this->phrases["booking reference"]; else echo "Booking Reference";?></th>
                          <th><?php if(isset($this->phrases["vehicle"])) echo $this->phrases["vehicle"]; else echo "Vehicle";?></th>
                          <th><?php if(isset($this->phrases["cost of journey"])) echo $this->phrases["cost of journey"]; else echo "Cost of Journey";?></th>
                          <th><?php if(isset($this->phrases["payment type"])) echo $this->phrases["payment type"]; else echo "Payment Type";?></th>
                          <th><?php if(isset($this->phrases["payer name"])) echo $this->phrases["payer name"]; else echo "Payer Name";?></th>
                          <th><?php if(isset($this->phrases["payer email"])) echo $this->phrases["payer email"]; else echo "Payer Email";?></th>
                          <th><?php if(isset($this->phrases["transaction id"])) echo $this->phrases["transaction id"]; else echo "Transaction ID";?></th>
                          <th><?php if(isset($this->phrases["booking date"])) echo $this->phrases["booking date"]; else echo "Booking Date";?></th>
                          <th><?php if(isset($this->phrases["booking status"])) echo $this->phrases["booking status"]; else echo "Booking Status";?></th>

                        </tr>
                  </thead>
                  <tfoot>
                    <tr>
                          <th>#</th>
                          <th><?php if(isset($this->phrases["username"])) echo $this->phrases["username"]; else echo "Username";?></th>
                          <th><?php if(isset($this->phrases["booking reference"])) echo $this->phrases["booking reference"]; else echo "Booking Reference";?></th>
                          <th><?php if(isset($this->phrases["vehicle"])) echo $this->phrases["vehicle"]; else echo "Vehicle";?></th>
                          <th><?php if(isset($this->phrases["cost of journey"])) echo $this->phrases["cost of journey"]; else echo "Cost of Journey";?></th>
                          <th><?php if(isset($this->phrases["payment type"])) echo $this->phrases["payment type"]; else echo "Payment Type";?></th>
                          <th><?php if(isset($this->phrases["payer name"])) echo $this->phrases["payer name"]; else echo "Payer Name";?></th>
                          <th><?php if(isset($this->phrases["payer email"])) echo $this->phrases["payer email"]; else echo "Payer Email";?></th>
                          <th><?php if(isset($this->phrases["transaction id"])) echo $this->phrases["transaction id"]; else echo "Transaction ID";?></th>
                          <th><?php if(isset($this->phrases["booking date"])) echo $this->phrases["booking date"]; else echo "Booking Date";?></th>
                          <th><?php if(isset($this->phrases["booking status"])) echo $this->phrases["booking status"]; else echo "Booking Status";?></th>

                        </tr>
                  </tfoot>
                   <tbody>

						<?php

							if(count($records) > 0) {

							  $i=1;
							  foreach($records as $row) {

						   ?>

						<tr>

							<td><?php echo $i++; ?></td>
							<td><?php echo $row->registered_name;?></td>
							<td><?php echo $row->booking_ref;?></td>
							<td><?php echo $row->vehicle_name."<br/><small>".$row->model."</small>";?></td>
							<td>
							<?php 
							$cost_of_journey = ($row->basic_fare + $row->service_charge + $row->insurance_amount) - $row->discount_amount;
							echo $this->config->item('site_settings')->currency_symbol.$row->cost_of_journey;?></td>
							<td><?php echo $row->payment_type;?></td>
							<?php if($row->payment_type == "cash") { ?>
							<td><?php echo $row->registered_name;?></td>
							<td><?php echo $row->email;?></td>
							<td><?php echo "N/A";?></td>
							<?php } else { ?>
							<td><?php echo $row->payer_name;?></td>
							<td><?php echo $row->payer_email;?></td>
							<td><?php echo $row->transaction_id;?></td>
							<?php } ?>
							<td><?php echo date('d M Y',strtotime($row->date_of_booking));?></td>
							 <td><?php echo (isset($this->phrases[$row->booking_status])) ? $this->phrases[$row->booking_status] : $row->booking_status;?></td>

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
