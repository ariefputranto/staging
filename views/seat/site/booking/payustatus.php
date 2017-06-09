<section class="apps line">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="inner_con thank">
          <div class="car_list">
<center> <img src="<?php echo base_url().$site_theme.'/';?>assets/system_design/images/sheck.png"></center>

<p class="bs"><?php if(isset($this->phrases["here are your booking status"])) echo $this->phrases["here are your booking status"]; else echo "Here are your booking status";?></p>
<p><?php echo $details;?></p> 
<p><?php if(isset($this->phrases["Thanks for your booking. Your order is now being processed.
You will receive a confirmation text, call or email shortly.
Your Car is only booked once you have received confirmation. If you don't hear 
from us please call our main number"])) echo $this->phrases["Thanks for your booking. Your order is now being processed.
You will receive a confirmation text, call or email shortly.
Your Car is only booked once you have received confirmation. If you don't hear 
from us please call our main number"]; else echo "Thanks for your booking. Your order is now being processed.
You will receive a confirmation text, call or email shortly.
Your Car is only booked once you have received confirmation. If you don't hear 
from us please call our main number";?> <?php if(isset($site_settings->phone)) echo $site_settings->phone;?>. <?php if(isset($this->phrases["thank you"])) echo $this->phrases["thank you"]; else echo "Thank you";?>!</p>
              <center><a href="<?php echo base_url();?>"><div class="btn btn-danger next_btn pre_btn"> <i class="fa fa-arrow-circle-o-right"></i> <?php if(isset($this->phrases["go back"])) echo $this->phrases["go back"]; else echo "Go Back";?> </div> </a></center>
          <div class="clearfix"></div>
         </div>
        </div>
      </div>
    </div>
  </div>
</section>
