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
			<?php $welcme_txt = (!empty($this->phrases["welcome to"])) ? $this->phrases["welcome to"] : "Welcome To"; ?>
          <h3><?php echo $welcme_txt." ".$this->config->item('site_settings')->site_title;?></h3>
            <h5> <?php echo "Contact Inquiry Details"; ?></h5>
            <p><?php echo "Name"; ?> <strong><?php if(isset($name)) echo $name;?></strong></p>
            <p><?php echo "Email"; ?> <strong><?php if(isset($email)) echo $email;?></strong></p>
            <?php if(!empty($msg)) { ?>
            <p><?php echo "Message"; ?> <strong><?php if(isset($msg)) echo $msg;?></strong></p>
           <?php } ?>
           <p><?php echo "Terms and Conditions"; ?> <strong><?php if(isset($terms_and_conditions)) echo humanize($terms_and_conditions);?></strong></p>
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
