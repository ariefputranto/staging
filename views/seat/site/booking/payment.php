<?php

	if(count($this->session->userdata('journey_booking_details')) > 0) {
		$record = $this->session->userdata('journey_booking_details');
	}

  ?>
 

<section class="apps line">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="inner_con ic">
         <div class="roundOne innround">
         <img src="<?php echo base_url().$site_theme.'/';?>assets/system_design/images/caricon.png"/>      </div>
          

          <div class="formlist top pay">
		   <div class="rate"> <?php if(isset($this->phrases["total"])) echo $this->phrases["total"]; else echo "Total";?>: 
			<?php 
				$cost_of_journey = '';
				if(isset($record['cost_of_journey']) && isset($record['cost_for_meet_greet']))
					$cost_of_journey = (($record['cost_of_journey'])+($record['cost_for_meet_greet']));
				echo $site_settings->currency_symbol.$cost_of_journey;
			?> </div>

		   <?php
				$attributes = "id='payment_form' 
							   name='payment_form' 
							   class=''
							   ";
				echo form_open('booking/payment', $attributes);
			?>

 
			<strong><?php if(isset($this->phrases["you can choose any Payment method and proceed for booking"])) echo $this->phrases["you can choose any Payment method and proceed for booking"]; else echo "You can choose any Payment method and proceed for Booking";?></strong>

     
			<?php foreach($gateways as $key => $gateway) { ?>
				<div class="col-lg-6">  
					<span class="input-group-addon pop gm tick">
						 <label class="radio payRad">
						 <input type="radio" name="payment_type" id="payment_type" value="<?php echo $gateway->gateway_id;?>" checked aria-label="...">
						 <span class="outer"><span class="inner"></span></span></label>
					<img src="<?php echo base_url().$site_theme.'/';?>assets/system_design/images/<?php echo strtolower($gateway->gateway_title);?>.png" alt="<?php echo $gateway->gateway_title;?>" title="<?php echo $gateway->gateway_title;?>"> <?php //echo $gateway->gateway_title;?> </span>
				</div>
			<?php } ?>
			<!--<li> 
				<span class="input-group-addon pop gm tick">
				<input type="radio" name="payment_type" id="payment_type" value="paypal" aria-label="..."><img src="<?php echo base_url().$site_theme.'/';?>assets/system_design/images/pay1.png">  PayPal </span>
			</li>
		<!--	<li> 
				<span class="input-group-addon pop gm tick">
				<input type="radio" name="payment_type" id="payment_type" value="payu" aria-label="..."><img src="<?php echo base_url().$site_theme.'/';?>assets/system_design/images/pay1.png">  PayU </span>
			</li> -->
		 

  

 
          		<div class="col-lg-7">  <a href="<?php echo base_url();?>booking/viewDetails">
           <div class="btn btn-danger next_btn pre_btn">
			 <i class="fa fa-arrow-circle-o-left"></i> <?php if(isset($this->phrases["previous step"])) echo $this->phrases["previous step"]; else echo "Previous Step";?> 
			   </div> </a> </div>
           <div class="col-lg-5"> 
           <button type="submit" class="btn btn-danger next_btn  pull-right">
					 <i class="fa fa-arrow-circle-o-right"></i> <?php if(isset($this->phrases["done"])) echo $this->phrases["done"]; else echo "Done";?>
			   </button> 
			   </div>
           </form>
          <div class="clearfix"></div>
         </div>
        </div>
      </div>
    </div>
  </div>
</section>
