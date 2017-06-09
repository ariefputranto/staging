<!--<div class="container">
<div class="row">
<div class="col-lg-12">
<div class="inner-hed">
<h3><?php echo getPhrase('Booking Success');?></h3>
<p> <a href="#"> Home </a> &nbsp; <i class="fa fa-angle-right"></i> &nbsp; <?php echo getPhrase('Booking Success');?>  </p>
</div>
</div>
</div>
</div> -->
<div class="container">
<div class="row">
<div class="col-lg-10 col-lg-offset-1">
<div class="login other-form">
<ul>
<li>
 
<div class="form-feilds">

<p><?php 
$template = $this->base_model->fetch_records_from('templates', array('template_id' => 12)); //Booking Success Page Finpay API
if(!empty($template))
{
	echo $template[0]->template_header;
	echo str_replace('__PAYMENT_CODE__', $payment_code, $template[0]->template_content);
	echo $template[0]->template_footer;
}
else
{
echo '<p>Your Payment Code : '.$payment_code.'</p>';
if(isset($this->phrases["Thanks for your booking. Your order is now being processed.
You will receive a confirmation text, call or email shortly.
Your Car is only booked once you have received confirmation. If you don't hear 
from us please call our main number"])) echo $this->phrases["Thanks for your booking. Your order is now being processed.
You will receive a confirmation text, call or email shortly.
Your Car is only booked once you have received confirmation. If you don't hear 
from us please call our main number"]; else echo "Thanks for your booking. Your order is now being processed.
You will receive a confirmation text, call or email shortly.
Your Car is only booked once you have received confirmation. If you don't hear 
from us please call our main number";?> <?php if(isset($site_settings->phone)) echo $site_settings->phone;?>. <?php if(isset($this->phrases["thank you"])) echo $this->phrases["thank you"]; else echo "Thank you";?>!
<?php } ?>
</p>
 
</div>

 

<div class="clearfix"></div>
</li>
 
</ul>
</div>
</div>
</div>
</div>