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
          <h3><?php echo (isset($this->phrases["welcome to"])) ? $this->phrases["welcome to"] : "Welcome To"." ".$this->config->item('site_settings')->site_title;?></h3>

            <h5> <?php if(isset($this->phrases["quote request details"])) echo $this->phrases["quote request details"]; else echo "Quote Request Details";?></h5>

            <p><strong><?php if(isset($this->phrases["name"])) echo $this->phrases["name"]; else echo "Name";?> </strong><?php if(isset($name)) echo $name;?></p>
            <p><strong><?php if(isset($this->phrases["email"])) echo $this->phrases["email"]; else echo "Email";?> </strong><?php if(isset($email)) echo $email;?></p>
            <p><strong><?php if(isset($this->phrases["phone"])) echo $this->phrases["phone"]; else echo "Phone";?> </strong><?php if(isset($phone)) echo $phone;?></p>
            <p><strong><?php if(isset($this->phrases["additional info"])) echo $this->phrases["additional info"]; else echo "Additional Info.";?> </strong><?php if(isset($additional_info)) echo $additional_info;?></p>
            <p><strong><?php if(isset($this->phrases["pick-up date"])) echo $this->phrases["pick-up date"]; else echo "Pick-up Date";?> </strong><?php if(isset($pick_date)) echo $pick_date;?></p>
            <p><strong><?php if(isset($this->phrases["pick-up time"])) echo $this->phrases["pick-up time"]; else echo "Pick-up Time";?> </strong><?php if(isset($pick_time)) echo $pick_time;?></p>
            <p><strong><?php if(isset($this->phrases["pick-up address"])) echo $this->phrases["pick-up address"]; else echo "Pick-up Address";?> </strong><?php if(isset($pick_point)) echo $pick_point;?></p>
            <p><strong><?php if(isset($this->phrases["drop-off address"])) echo $this->phrases["drop-off address"]; else echo "Drop-off Address";?> </strong><?php if(isset($drop_point)) echo $drop_point;?></p>

            <?php if(isset($flight_num) && $flight_num != "") { ?>
            <p><strong><?php if(isset($this->phrases["flight number"])) echo $this->phrases["flight number"]; else echo "Flight Number";?> </strong><?php echo $flight_num;?></p>
            <?php } if(isset($terminal_num) && $terminal_num != "") { ?>
            <p><strong><?php if(isset($this->phrases["terminal number"])) echo $this->phrases["terminal number"]; else echo "Terminal Number";?> </strong><?php echo $terminal_num;?></p>
            <?php } if(isset($arriving_from) && $arriving_from != "") { ?>
            <p><strong><?php if(isset($this->phrases["arriving from"])) echo $this->phrases["arriving from"]; else echo "Arriving From";?> </strong><?php echo $arriving_from;?></p>
            <?php } ?>

            <p></p>
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
      <script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/bootstrap.min.js"></script>
   </body>
</html>
