<section class="apps line">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="inner_con ic">
          <a href="<?php echo base_url();?>"> <div class="roundOne innround"><img src="<?php echo base_url().$site_theme.'/';?>assets/system_design/images/caricon.png"/></div></a>

		<?php echo $this->session->flashdata('message');?>
          <div class="formlist top">

			 <div class="panel-heading lgn-hed"> <?php if(isset($this->phrases["cancellation policy"])) echo $this->phrases["cancellation policy"]; else echo "Cancellation Policy";?> </div>
<img src="<?php echo base_url().$site_theme.'/';?>assets/system_design/images/bmw1.png" class="cancel">
<p class="cancl-pol">   <?php if(isset($this->phrases["hi, the cancellation process is as follows"])) echo $this->phrases["hi, the cancellation process is as follows"]; else echo "Hi, The Cancellation process is as follows";?>, </p>

<p class="cancl-pol "><?php if(isset($this->phrases["if you cancel your booking before 8 hours to the pick-up time, you will get"])) echo $this->phrases["if you cancel your booking before 8 hours to the pick-up time, you will get"]; else echo "If you cancel your booking before 8 hours to the Pick-up time, you will get";?> 
<?php if(isset($cancellation_policy_rec->eight_hrs_before)) echo $cancellation_policy_rec->eight_hrs_before;?>
% <?php if(isset($this->phrases["refund"])) echo $this->phrases["refund"]; else echo "refund";?>.</p>
 

<p class="cancl-pol "><?php if(isset($this->phrases["if you cancel your booking before 5 hours to the pick-up time, you will get"])) echo $this->phrases["if you cancel your booking before 5 hours to the pick-up time, you will get"]; else echo "If you cancel your booking before 5 hours to the Pick-up time, you will get";?>  
<?php if(isset($cancellation_policy_rec->five_hrs_before)) echo $cancellation_policy_rec->five_hrs_before;?>
% <?php if(isset($this->phrases["refund"])) echo $this->phrases["refund"]; else echo "refund";?>.</p>
 

<p class="cancl-pol "><?php if(isset($this->phrases["if you cancel your booking before 3 hours to the pick-up time, you will get"])) echo $this->phrases["if you cancel your booking before 3 hours to the pick-up time, you will get"]; else echo "If you cancel your booking before 3 hours to the Pick-up time, you will get";?> 
<?php if(isset($cancellation_policy_rec->three_hrs_before)) echo $cancellation_policy_rec->three_hrs_before;?>
% <?php if(isset($this->phrases["refund"])) echo $this->phrases["refund"]; else echo "refund";?>.</p>

<div class="socilIcon"> 
<h3><?php if(isset($this->phrases["social links"])) echo $this->phrases["social links"]; else echo "Social Links";?></h3>

<?php
	if(isset($site_settings->facebook) && $site_settings->facebook != '') {
	?>
	<a href="<?php echo $site_settings->facebook;?>" target="_blank"><i class="fa fa-facebook"></i></a>
	<?php } ?>
	
	<?php
	if(isset($site_settings->twitter) && $site_settings->twitter != '') {
	?>
	<a href="<?php echo $site_settings->twitter;?>" target="_blank"><i class="fa fa-twitter"></i></a>
	<?php } ?>
	
	<?php
	if(isset($site_settings->google_plus) && $site_settings->google_plus != '') {
	?>
	<a href="<?php echo $site_settings->google_plus;?>" target="_blank"><i class="fa fa-google"></i></a>
	<?php } ?>
	
	<?php
	if(isset($site_settings->pinterest) && $site_settings->pinterest != '') {
	?>
	<a href="<?php echo $site_settings->pinterest;?>" target="_blank"><i class="fa fa-pinterest"></i></a>
	<?php } ?>

</div>
 
         </div>
        </div>
      </div>
    </div>
  </div>
</section>
