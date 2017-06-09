<?php

	/** User basic details **/
	$registered_name_default 	= "";
	$phone_default 				= "";
	$phone_code_default			=	"";
	$email_default 				= "";

	/** Set User basic details, If logged in **/
	if ($this->ion_auth->logged_in() || $this->ion_auth->is_client()) {
		$registered_name_default 	= $this->session->userdata('username');
		$phone_default 				= $this->session->userdata('phone');
		$phone_code_default 		= $this->session->userdata('phone_code');
		$email_default 				= $this->session->userdata('email');
	}


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
         <img src="<?php echo base_url().$site_theme.'/';?>assets/system_design/images/caricon.png"/>         
         </div>
         

          <div class="formlist top">
		<div class="col-lg-12"> 		  
<div class="rate"><?php if(isset($this->phrases["total"])) echo $this->phrases["total"]; else echo "Total";?> <?php echo 
			$site_settings->currency_symbol.
			$record['cost_of_journey'];?> </div>
			</div>
		   <?php
				$attributes = "id='passenger_details_form' 
							   name='passenger_details_form' 
							   class=''
							   ";
				echo form_open('booking/passengerDetails', $attributes);
			?>

 

			<div class="col-lg-6"> 
			 <label><?php if(isset($this->phrases["name"])) echo $this->phrases["name"]; else echo "Name";?>*</label>
			 <input type="text" name="registered_name" id="registered_name" 
			 value="<?php echo 
			 set_value('registered_name', (isset($record['registered_name'])) ? $record['registered_name'] : $registered_name_default);?>">
			 <?php echo form_error('registered_name');?>
            </div>

			<div class="col-lg-6"> 
			 <label><?php if(isset($this->phrases["email"])) echo $this->phrases["email"]; else echo "Email";?>*</label>
			 <input type="text" name="email" id="email" 
			 value="<?php echo 
			 set_value('email', (isset($record['email'])) ? $record['email'] : $email_default);?>">
			 <?php echo form_error('email');?>
        </div>

				<div class="col-lg-6"> 
			 <label><?php if(isset($this->phrases["mobile number"])) echo $this->phrases["mobile number"]; else echo "Mobile Number";?>*</label><br>
			 <input type="text" name="phone_code" class="code" id="phone_code" value="<?php echo set_value('phone_code', (isset($record['phone_code'])) ? $record['phone_code'] : $phone_code_default);?>" placeholder="<?php if(isset($this->phrases["code"])) echo $this->phrases["code"]; else echo "Code";?>">
			 <?php echo form_error('phone_code');?>
			 <input type="text" name="phone" id="phone" class="code1"
			 value="<?php echo 
			 set_value('phone', (isset($record['phone'])) ? $record['phone'] : $phone_default);?>" placeholder="<?php if(isset($this->phrases["number"])) echo $this->phrases["number"]; else echo "Number";?>">
			 <?php echo form_error('phone');?>
        </div>

				<div class="col-lg-6"> 
			 <label><?php if(isset($this->phrases["additional info / notes.(your message)"])) 
			 echo $this->phrases["additional info / notes.(your message)"]; else echo "Additional Info / Notes.(your message)";?></label>
			 <input type="text" name="additional_info" id="additional_info" 
			 value="<?php echo 
				 set_value('additional_info', (isset($record['additional_info'])) ? 
				 $record['additional_info'] : '');
				 ?>">
          </div>           
 
			<div class="col-lg-6">  
			 <label><?php if(isset($this->phrases["pick-up address* (please enter full address)"])) 
			 echo $this->phrases["pick-up address* (please enter full address)"]; else echo "Pick-up Address* (Please enter full address)";?></label>
			 <textarea placeholder="  <?php if(isset($this->phrases["APPLICABLE IF PICKUP IS AIRPORT ONLY"])) 
           echo $this->phrases["APPLICABLE IF PICKUP IS AIRPORT ONLY"]; 
           else echo "APPLICABLE IF PICKUP IS AIRPORT ONLY";?>  " name="complete_pickup_address" id="complete_pickup_address"><?php echo 
				 set_value('complete_pickup_address', (isset($record['complete_pickup_address'])) ? 
				 $record['complete_pickup_address'] : '');
				 ?></textarea>
				  <?php echo form_error('complete_pickup_address');?>
          </div>

				<div class="col-lg-6"> 
			 <label><?php if(isset($this->phrases["drop-off address"])) 
			 echo $this->phrases["drop-off address"]; else echo "Drop-off Address";?>*</label>
			 <textarea   name="complete_destination_address" id="complete_destination_address"><?php echo 
				 set_value('complete_destination_address', (isset($record['complete_destination_address'])) ? 
				 $record['complete_destination_address'] : '');
				 ?></textarea>
				 <?php echo form_error('complete_destination_address');?>
       </div>
 
			<div class="col-lg-6"> 
			 <label> <?php if(isset($this->phrases["arriving from airport"])) 
			 echo $this->phrases["arriving from airport"]; else echo "Arriving from airport";?>?</label>
			<?php
			$options = array('No' => 'No', 'Yes' => 'Yes');
			$arrivingfrom_airport = (isset($record['arrivingfrom_airport'])) ? $record['arrivingfrom_airport'] : 'No';			
			echo form_dropdown('arrivingfrom_airport', $options, $arrivingfrom_airport, 'id="arrivingfrom_airport" onchange="javascript:changediv();"' );
			?>			
          </div>
		  
		  <div id="flightdetails_div">
         	<div class="col-lg-6"> 
			 <label> <?php if(isset($this->phrases["flight number"])) 
			 echo $this->phrases["flight number"]; else echo "Flight Number";?></label>
			<input type="text" name="flight_num" id="flight_num" 
			 value="<?php echo 
			 set_value('flight_num', (isset($record['flight_num'])) ? $record['flight_num'] : '');?>">
          </div>

		 	<div class="col-lg-6"> 
			 <label><?php if(isset($this->phrases["terminal number"])) 
			 echo $this->phrases["terminal number"]; else echo "Terminal Number";?></label>
			<input type="text" name="terminal_num" id="terminal_num" 
			 value="<?php echo 
			 set_value('terminal_num', (isset($record['terminal_num'])) ? $record['terminal_num'] : '');?>">
          </div>

        	<div class="col-lg-6"> 
			 <label><?php if(isset($this->phrases["arriving from (please enter full address)"])) 
			 echo $this->phrases["arriving from (please enter full address)"]; 
			 else echo "Arriving From (Please enter full address)";?></label>
			 <input type="text" name="arriving_from" id="arriving_from" 
			 value="<?php echo 
			 set_value('arriving_from', (isset($record['arriving_from'])) ? $record['arriving_from'] : '');?>">
       </div>

          	<div class="col-lg-6"> 
				<label><?php if(isset($this->phrases["meet & greet"])) echo $this->phrases["meet & greet"]; else echo "Meet & Greet";?> 
				<?php 
					echo "(".$site_settings->currency_symbol.
					$site_settings->cost_for_meet_greet.")";
				?>
				</label>
				<span class="input-group-addon pop tick">  
				<input type="checkbox" name="meet_greet_chkbx" id="meet_greet_chkbx"
				 onclick="set_meet_greet();" aria-label="..." 
				 <?php if(isset($record['meet_greet']) && $record['meet_greet'] == "Yes") echo "checked"; ?>>
					<?php if(isset($this->phrases["tick for Yes, leave blank for No"])) echo $this->phrases["tick for Yes, leave blank for No"]; else echo "Tick for Yes, leave blank for No";?>
				</span>
			</div>
			</div>

			<input type="hidden" name="meet_greet" id="meet_greet" />
			<input type="hidden" name="cost_for_meet_greet" id="cost_for_meet_greet" value="<?php echo $site_settings->cost_for_meet_greet;?>"/>

      

       <div class="col-lg-12"> 
          <a href="<?php echo base_url();?>booking/vehicleSelection">
           <div class="btn btn-danger next_btn pre_btn">
			   <i class="fa fa-arrow-circle-o-left"></i> <?php if(isset($this->phrases["previous step"])) echo $this->phrases["previous step"]; else echo "Previous Step";?> 
			   </div> </a> 
			    <button type="submit" class="btn btn-danger next_btn pull-right">
			 <i class="fa fa-arrow-circle-o-right"></i> <?php if(isset($this->phrases["book now"])) echo $this->phrases["book now"]; else echo "Book Now";?> 
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



<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery.min.js">
</script> 
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery.validate.js" >
</script>
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/additional-methods.js" >
</script>

<script>
   (function($,W,D)
   {
      var JQUERY4U = {};

      JQUERY4U.UTIL =
      {
          setupFormValidation: function()
          {

			//Additional Methods			
                 $.validator.addMethod("phoneNumber", function(uid, element) {
                     return (this.optional(element) || uid.match(/^([0-9 +-]*)$/));
                 }, "<?php if(isset($this->phrases["please enter valid number"])) echo $this->phrases["please enter valid number"]; else echo "Please enter valid number";?>.");

			/* Form validation rules */

              $("#passenger_details_form").validate({
                  rules: {
					registered_name: {
							  required: true
						  },
					email: {
							  required: true, 
							  email: true
						  },
					phone: {
							  required: true, 
							  phoneNumber: true
						  },
					complete_pickup_address: {
							  required: true
						  },
					complete_destination_address: {
							  required: true
						  }
                  },

				messages: {
					registered_name: {
							  required: "<?php if(isset($this->phrases["please enter name"])) echo $this->phrases["please enter name"]; else echo "Please enter Name";?>."
						  },
					email: {
							  required: "<?php if(isset($this->phrases["please enter email"])) echo $this->phrases["please enter email"]; else echo "Please enter Email";?>."
						  },
					phone: {
							  required: "<?php if(isset($this->phrases["please enter phone"])) echo $this->phrases["please enter phone"]; else echo "Please enter Phone";?>."
						  },
					complete_pickup_address: {
							  required: "<?php if(isset($this->phrases["please enter complete pick-up address"])) echo $this->phrases["please enter complete pick-up address"]; else echo "Please enter complete Pick-up Address";?>."
						  },
					complete_destination_address: {
							  required: "<?php if(isset($this->phrases["please enter drop-off address"])) echo $this->phrases["please enter drop-off address"]; else echo "Please enter Drop-off Address";?>."
						  }
				},

                  submitHandler: function(form) {
                      form.submit();
                  }
              });

          }
      }

      //when the dom has loaded setup form validation rules
      $(D).ready(function($) {
          JQUERY4U.UTIL.setupFormValidation();
      });

   })(jQuery, window, document);



	/* On Document Ready */
   $(document).ready(function() {

		set_meet_greet();

	});


	/* Set Meet & Greet */
	function set_meet_greet()
	{

		if($('#meet_greet_chkbx').is(':checked')) {

			$('#meet_greet').val('Yes');
			$('#cost_for_meet_greet').val('<?php echo $site_settings->cost_for_meet_greet;?>');

		} else {

			$('#meet_greet').val('No');
			$('#cost_for_meet_greet').val('0');
		}

	}


function changediv()
{
	var type = $('#arrivingfrom_airport').val();
	if(type == 'No')
	{
		$('#flightdetails_div').hide();
	}
	else
	{
		$('#flightdetails_div').show();
	}
}
changediv();
		  </script>
