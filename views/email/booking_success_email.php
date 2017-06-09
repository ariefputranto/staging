<?php $site_theme = 'seat';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title><?php echo "Welcome - ".$this->config->item('site_settings')->site_title;?></title>
      <style>
         body , html { margin:0; padding:0; height:100%; font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:13px; }
         .mailer{ 
         box-shadow: 0 0 5px #ccc;
         float: left;
         height: auto;
         max-width: 800px;
         }
         .content { float:left; width:100%; line-height:45px; padding:15px; }
         .hedder{ width:100%; float:left; }
         .new-tb{  
         margin: 0 auto;
         max-width: 700px;}
         .new-tb td, th {
         border: 0px solid #c2c2c2 !important;
         text-align: left;   padding:0px 7px}
         .new-tb table, tr {
         border: 0px solid #c2c2c2 !important;
         }
         .thed{
         background-color:#fff; color: #000;
         }
         .thed1{
         border-bottom:1px solid #a60000; border-top:1px solid #a60000;
         }
         .bm{
         border-bottom:1px solid #dc0101 !important
         }
         .bm1{
         border:1px solid #dc0101 !important
         }
         .tb-ri{
         text-align: right !important;
         }
         .idtd{max-width:250px; background-color:#a60000; padding:6px; margin:6px; border-radius:5px; color:#fff;  }
         .inv{ color:#a60000; border-bottom:1px solid #a60000;}
         .idtd2 {
         background-color: #a60000;
         color: #fff;
         padding: 6px;
         }
         .idtd1{max-width:250px;  padding:0px 6px; margin:6px; 	border-bottom:1px solid #a60000; border-top:1px solid #a60000; }
         .padd-tb{ padding:1px !important }
         .ve1{   float: left;
         margin: 0 5px;
         width: 76px; }
         .ve12{  float: left;
         margin: 0 5px;
         width: 94.5%;}
         .ve13{float: left;
         margin: 0 5px;
         width: 46.2%;}
         .pmf{float: left;
         margin: 0 5px;
         width:30%;} 
      </style>
   </head>
   <body>

      <div class="mailer">
         <div class="hedder">         
		 <?php
		 $site_logo = (isset($this->config->item('site_settings')->site_logo)) ? $this->config->item('site_settings')->site_logo : '';
		 if(file_exists($site_theme.'/'.'assets/system_design/images/'.$site_logo)) { ?>
         <img src="<?php echo base_url().$site_theme.'/';?>assets/system_design/images/<?php echo $site_logo;?>"  />
         <?php } elseif(file_exists('uploads/email_logo/email-logo.png')) {
			 ?>
			<img src="<?php echo base_url();?>uploads/email_logo/email-logo.png"  />
			 <?php
			} ?>
         </div>
         <div class="content">
            <div class="new-tb">
               <!-- Middle Content-->
               <div class="container-fluid content-bg">
                  <div class="spacer"></div>
                  <div class="container emailer-wi">
			<?php $welcme_txt = (isset($this->phrases["welcome to"])) ? $this->phrases["welcome to"] : "Welcome To"; ?>
          <h3><?php echo $welcme_txt." ".$this->config->item('site_settings')->site_title;?></h3>
		  
		  <?php if(isset($booking_status) && $booking_status == 'Confirmed') { ?>
			<h4><?php echo getPhrase('Congratulations'); ?> : <strong>Your Booking Has Been Confirmed.</strong></h4>
			<?php } ?>
			
			
            <h5> <?php echo (isset($this->phrases["booking details"])) ? $this->phrases["booking details"] : "Booking Details"; ?></h5>
            <p><?php echo (isset($this->phrases["your booking reference is"])) ? $this->phrases["your booking reference is"] : "Your Booking Reference is"; ?> <strong><?php if(isset($booking_ref)) echo $booking_ref;?></strong></p>
			
            <p><?php echo (isset($this->phrases["journey cost"])) ? $this->phrases["journey cost"] : "Journey Cost"; ?> <strong><?php if(isset($cost_of_journey)) echo $this->config->item('site_settings')->currency_symbol. number_format($cost_of_journey,2);?></strong></p>
			
			<?php if(isset($seats)) { ?>
			<p><?php echo getPhrase('Seats'); ?> <strong><?php if(isset($seats)) echo $seats;?></strong></p>
			<?php } ?>
			
			<?php if(isset($payment_type) && strtolower($payment_type) == 'finpayapi' && $booking_status == 'Pending') { ?>
			<p><?php echo getPhrase('Note'); ?> <strong>You just make a booking using Finpay payment code you need to pay the amount to confirm the seat.</strong></p>
			<?php } ?>
			
			<?php if(isset($payment_type) && strtolower($payment_type) == 'finpayapi' && $booking_status == 'Cancelled') { ?>
			<p><?php echo getPhrase('Note'); ?> <strong>Your booking has been cancelled as you have not paid the amount.</strong></p>
			<?php } ?>
            <p></p>
         </div>
         <div class="spacer"></div>
      </div>
      </div>
      <!-- Middle Content-->
     <!-- Footer-->
               <div class="panel-footer padding">
                  <div class="container-fluid copy">
                     <div class="container padding">
                        <div class="col-md-5"><?php echo $this->config->item('site_settings')->rights_reserved_content;?></div>
                     </div>
                  </div>
               </div>
               <!-- Footer--> 
            </div>
         </div>
      </div>
   </body>
</html>
