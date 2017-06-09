<?php

	/** User basic details **/
	$name_default 		= "";
	$phone_default 		= "";
	$email_default 		= "";

	/** Set User basic details, If logged in **/
	if ($this->ion_auth->logged_in() || $this->ion_auth->is_client()) {

		$name_default 	= $this->session->userdata('username');
		$phone_default 	= $this->session->userdata('phone');
		$email_default 	= $this->session->userdata('email');

	}

  ?>

<section class="apps line">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="inner_con ic">
           <a href="<?php echo base_url();?>"> <div class="roundOne innround"><img src="<?php echo base_url().$site_theme.'/';?>assets/system_design/images/caricon.png"/></div></a>
       

		<?php echo $this->session->flashdata('message');?>
          <div class="formlist top im">

			 <div class="panel-heading lgn-hed req-qt"> <?php if(isset($this->phrases["request quote"])) echo $this->phrases["request quote"]; else echo "Request Quote";?> </div>

		   <?php
				$attributes = "id='quote_request_form' 
							   name='quote_request_form' 
							   class=''
							   ";
				echo form_open('request-quote', $attributes);
			?>

 

			<div class="col-lg-6"> 
			 <input type="text" name="name" id="name" 
			 value="<?php echo set_value('name', $name_default);?>" 
			 placeholder="<?php if(isset($this->phrases["name"])) 
			 echo $this->phrases["name"]; else echo "Name";?>*">
			 <?php echo form_error('name');?>
          </div>

		<div class="col-lg-6"> 
			 <input type="text" name="email" id="email" 
			 value="<?php echo  set_value('email', $email_default);?>" 
			 placeholder="<?php if(isset($this->phrases["email"])) 
			 echo $this->phrases["email"]; else echo "Email";?>*">
			 <?php echo form_error('email');?>
        </div>

		<div class="col-lg-6"> 
			 <input type="text" name="phone" id="phone" 
			 value="<?php echo set_value('phone', $phone_default);?>" 
			 placeholder="<?php if(isset($this->phrases["mobile number"])) 
			 echo $this->phrases["mobile number"]; else echo "Mobile Number";?>*">
			 <?php echo form_error('phone');?>
       </div>

		<div class="col-lg-6"> 
			 <input type="text" name="additional_info" id="additional_info" 
			 value="<?php echo set_value('additional_info');?>" 
			 placeholder="<?php if(isset($this->phrases["additional info / notes.(your message)"])) 
			 echo $this->phrases["additional info / notes.(your message)"]; else echo "Additional Info / Notes.(your message)";?>*">
       </div>
 
			<div class="col-lg-6"> 
				<input name="pick_date" id="pick_date" type="text" 
				class="calen span2 dp" readonly kl_virtual_keyboard_secure_input="on"  
				placeholder="<?php if(isset($this->phrases["pick-up date"])) 
				echo $this->phrases["pick-up date"]; else echo "Pick-up Date";?>*">
					<?php echo form_error('pick_date'); ?>
		</div>

			<div class="col-lg-6"> 
				<input name="pick_time" id="pick_time" type="text" 
				class="tme tp" placeholder="<?php if(isset($this->phrases["pick-up time"])) 
				echo $this->phrases["pick-up time"]; else echo "Pick-up Time";?>*">
					<?php echo form_error('pick_time'); ?>
			</div>

		<div class="col-lg-6"> 
			 <textarea name="pick_point" id="pick_point" 
			 placeholder="<?php if(isset($this->phrases["pick-up address* (please enter full address)"])) 
			 echo $this->phrases["pick-up address* (please enter full address)"]; else echo "Pick-up Address* (Please enter full address)";?>"><?php echo 
				 set_value('pick_point');
				 ?></textarea>
				  <?php echo form_error('pick_point');?>
         </div>

		<div class="col-lg-6">  
			 <textarea name="drop_point" id="drop_point" 
			 placeholder="<?php if(isset($this->phrases["drop-off address"])) 
			 echo $this->phrases["drop-off address"]; else echo "Drop-off Address";?>*"><?php echo 
				 set_value('drop_point'); ?></textarea>
				 <?php echo form_error('drop_point');?>
         </div>
		 <!--
<div class="col-lg-6">  
         <font><?php if(isset($this->phrases["APPLICABLE IF PICKUP IS AIRPORT ONLY"])) 
           echo $this->phrases["APPLICABLE IF PICKUP IS AIRPORT ONLY"]; 
           else echo "APPLICABLE IF PICKUP IS AIRPORT ONLY";?> </font> 
        </div>-->

         <div class="col-lg-6"> 
			<input type="text" name="flight_num" id="flight_num" 
			 value="<?php echo set_value('flight_num');?>" 
			 placeholder="<?php if(isset($this->phrases["flight number"])) 
			 echo $this->phrases["flight number"]; else echo "Flight Number";?>">
        </div>

		<div class="col-lg-6"> 
			<input type="text" name="terminal_num" id="terminal_num" 
			 value="<?php echo set_value('terminal_num');?>" 
			 placeholder="<?php if(isset($this->phrases["terminal number"])) 
			 echo $this->phrases["terminal number"]; else echo "Terminal Number";?>">
           </div>

       <div class="col-lg-6"> 
			 <input type="text" name="arriving_from" id="arriving_from" 
			 value="<?php echo set_value('arriving_from');?>" 
			 placeholder="<?php if(isset($this->phrases["arriving from (please enter full address)"])) 
			 echo $this->phrases["arriving from (please enter full address)"]; 
			 else echo "Arriving From (Please enter full address)";?>">
           </div>


 

             <div class="col-lg-6"> 
           <button type="submit" class="btn btn-danger next_btn pull-right">
			   <i class="fa  fa-file-text"></i> 
			   <?php if(isset($this->phrases["get quote"])) 
			   echo $this->phrases["get quote"]; else echo "Get Quote";?>
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
                 }, "<?php if(isset($this->phrases["please enter valid number"])) 
                 echo $this->phrases["please enter valid number"]; 
                 else echo "Please enter valid number";?>.");

			/* Form validation rules */

              $("#quote_request_form").validate({
                  rules: {
					name: {
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
					additional_info: {
							  required: true
						  },
					pick_date: {
							  required: true
						  },
					pick_time: {
							  required: true
						  },
					pick_point: {
							  required: true
						  },
					drop_point: {
							  required: true
						  }
                  },

				messages: {
					name: {
							  required: "<?php if(isset($this->phrases["please enter name"])) 
							  echo $this->phrases["please enter name"]; 
							  else echo "Please enter Name";?>."
						  },
					email: {
							  required: "<?php if(isset($this->phrases["please enter email"])) 
							  echo $this->phrases["please enter email"];
							  else echo "Please enter Email";?>."
						  },
					phone: {
							  required: "<?php if(isset($this->phrases["please enter phone"])) 
							  echo $this->phrases["please enter phone"];
							  else echo "Please enter Phone";?>."
						  },
					additional_info: {
							  required: "<?php if(isset($this->phrases["please enter some message / info"])) 
							  echo $this->phrases["please enter some message / info"];
							  else echo "Please enter some Message / Info";?>."
						  },
					pick_date: {
							  required: "<?php if(isset($this->phrases["please enter pick-up date"])) 
							  echo $this->phrases["please enter pick-up date"];
							  else echo "Please enter Pick-up Date";?>."
						  },
					pick_time: {
							  required: "<?php if(isset($this->phrases["please enter pick-up time"])) 
							  echo $this->phrases["please enter pick-up time"];
							  else echo "Please enter Pick-up Time";?>."
						  },
					pick_point: {
							  required: "<?php if(isset($this->phrases["please enter complete pick-up address"])) 
							  echo $this->phrases["please enter complete pick-up address"];
							  else echo "Please enter complete Pick-up Address";?>."
						  },
					drop_point: {
							  required: "<?php if(isset($this->phrases["please enter drop-off address"])) 
							  echo $this->phrases["please enter drop-off address"];
							  else echo "Please enter Drop-off Address";?>."
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


</script>
