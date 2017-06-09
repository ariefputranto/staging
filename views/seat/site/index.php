<?php
$search_banner = "background: rgba(0, 0, 0, 0) url('../images/banner.jpg') repeat scroll center center / cover ;";
if(!empty($site_settings->search_banner)) 
$search_banner = "background: rgba(0, 0, 0, 0) url('".$site_theme."/assets/system_design/images/".$site_settings->search_banner."') repeat scroll center center / cover ;";
?>
<section class="banner" style="<?php echo $search_banner;?>">
<div class="container">
<div class="row">
<div class="col-lg-12">
<h1>
<?php 
if(!empty($site_settings->homepage_title)) 
echo $site_settings->homepage_title;
else
echo 'Book your next journey with us';	
?></h1>
<h3><?php 
if(!empty($site_settings->homepage_subtitle)) 
echo $site_settings->homepage_subtitle;
else
echo 'Yes! You’ll love it';	
?></h3>
</div>
<?php $this->load->view($site_theme.'/'.'site/common/search-form');?>
</div>
</div>
</section>
<?php $offer = $this->db->query('SELECT * FROM digi_offers WHERE DATE(NOW()) BETWEEN DATE(NOW()) AND expiry_date ORDER BY RAND() LIMIT 1')->result();
if(!empty($offer))
{
?>
<section class="add">
    <div class="container">
        <div class="row">
            <div class="col-lg-3"></div>
            <div class="col-lg-6">
                <div class="coupon-box">
                    <span>Coupon code for the day : </span><?php echo $offer[0]->title;?>
                </div>
            </div>
            <div class="col-lg-3"></div>
        </div>
    </div>
</section>
<?php } ?>
<div class="container">
<div class="row">
<div class="col-lg-12">
<div class="hed">
<h2>Why Us</h2>
<p><?php if(isset($site_settings->whyus)) echo $site_settings->whyus;?></p>
</div>
</div>
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
<div class="why">
<i class="flaticon-transport"></i>
<?php $tls = $this->base_model->get_travel_locations();?>
<h4><?php echo $this->base_model->num_rows;?></h4>
<p>Routes</p>
</div>
</div>

<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
<div class="why">
<i class="flaticon-people"></i>
<h4>24\7</h4>
<p>Support</p>
</div>
</div>

<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
<div class="why">
<i class="flaticon-security"></i>
<h4>100%</h4>
<p>Safe & security</p>
</div>
</div>

<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
<div class="why">
<i class="flaticon-vehicle"></i>
<?php $tlcs = $this->base_model->get_location_costs();?>
<h4><?php echo $this->base_model->num_rows;?></h4>
<p>Rides</p>
</div>
</div>
</div>
</div>

<section class="howitwork">
<div class="container">
<div class="row">
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"><!--vid-->
 <div class="video">
 <?php 
 $image = base_url().$site_theme.'/'.'assets/system_design/images/video.png';
 if(isset($this->config->item('site_settings')->homepage_banner)) 
	 $image = base_url() . 'seat/assets/system_design/images/'.$this->config->item('site_settings')->homepage_banner; ?>
 <img src="<?php echo $image;?>"> 
  </div></div>
 <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
 
<div class="hed">
<h2>About Our Company</h2>
<p><?php if(isset($this->config->item('site_settings')->about_company)) echo $this->config->item('site_settings')->about_company; elseif(isset($site_settings->about_company)) echo $site_settings->about_company; ?></p>
</div>
   
 
</div>
</div>  
</div> 
</section>

<?php $testimonials = $this->base_model->fetch_records_from('testimonials', array('status' => 'Active'), '*', 'created', 'DESC', 3);
if(!empty($testimonials))
{
?>
<div class="container">
<div class="row">
<div class="col-lg-12">
<div class="hed">
<h2>Testimonials</h2>
<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem </p>
</div>
</div>

<?php foreach($testimonials as $testimonial) { ?>
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
<div class="testimonial">
<img src="<?php echo base_url()?>uploads/testimonials/<?php echo $testimonial->image?>">
<h5><?php echo strtoupper($testimonial->name);?></h5>
<p><?php echo $testimonial->designation;?></p>
<blockquote>
<i class="flaticon-typography"></i>
<?php echo $testimonial->comments;?>
 <i class="flaticon-typography">
</i>
 </blockquote>
</div>
</div>
<?php } ?>

</div>
</div>
<?php } ?>


<section class="call-ac">
<div class="container">
<div class="row">
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 clearfix"> 
<?php 
$support_ticket_banner = base_url().$site_theme.'/assets/system_design/images/cu.jpg';
if(!empty($site_settings->support_ticket_banner)) 
	$support_ticket_banner = base_url().$site_theme.'/assets/system_design/images/'.$site_settings->support_ticket_banner; ?>
<img src="<?php echo $support_ticket_banner;?>" class="cu"  > </div>
<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 col-lg-offset-1 clearfix"> 
<h2>Looking to get some Help ?</h2>
<p><?php if(!empty($site_settings->support_ticket)) echo $site_settings->support_ticket; ?></p>
</div>
<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 clearfix"> 
<div class="customer-care">
<i class="flaticon-technology"></i>
<h6>(24/7 Customer Support)</h6>
<h1><?php echo isset($site_settings->call_center) ? $site_settings->call_center : '';?></h1>
 <a class="btn btn-default" href="<?php echo base_url();?>contact">Contact Us</a>
</div></div>

</div>
</div>
</section>


<section class="contact">
 
 
 
<div class="container"><div class="row"><div class="col-lg-12">
<div class="address">

<h1 class="logo"><!--<img src="<?php echo base_url().$site_theme.'/';?>assets/system_design/images/logo.png">-->
<?php if(!empty($site_settings->site_title)) echo $site_settings->site_title; ?>
</h1>
<p><?php if(!empty($site_settings->about_company)) echo $site_settings->about_company; ?></p>

<h5>We are Social Now!</h5>

<?php if(!empty($site_settings->facebook)) { ?>
<a href="<?php echo $site_settings->facebook;?>" target="_blank"><i class="fa fa-facebook"></i> </a>
<?php } ?>

<?php if(!empty($site_settings->twitter)) { ?>
<a href="<?php echo $site_settings->twitter;?>" target="_blank"><i class="fa fa-twitter"></i> </a>
<?php } ?>

<?php if(!empty($site_settings->google_plus)) { ?>
<a href="<?php echo $site_settings->google_plus;?>" target="_blank"><i class="fa fa-google-plus"></i> </a>
<?php } ?>

<?php if(!empty($site_settings->pinterest)) { ?>
<a href="<?php echo $site_settings->pinterest;?>" target="_blank"><i class="fa fa-pinterest"></i> </a>
<?php } ?>

<?php
if(isset($site_settings->instagram) && $site_settings->instagram != '') {
?>
<a href="<?php echo $site_settings->instagram;?>" target="_blank"><i class="fa fa-instagram"></i></a>
<?php } ?>

</div></div></div></div>


</section>