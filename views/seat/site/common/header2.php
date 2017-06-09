<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	
	<meta name="keywords" content="<?php if(isset($this->config->item('seoSettings')->meta_keywords)) echo $this->config->item('seoSettings')->meta_keywords; elseif(isset($site_settings->site_title)) echo $site_settings->site_title; ?>" />

	<meta name="description" content="<?php if(isset($this->config->item('seoSettings')->meta_description)) echo $this->config->item('seoSettings')->meta_description; elseif(isset($site_settings->site_title)) echo $site_settings->site_title; ?>" />

	<title><?php if(isset($title)) echo $title; if(isset($site_settings->site_title)) echo " - ".$site_settings->site_title;?></title>
	
	<link rel="shortcut icon" href="<?php echo base_url().$site_theme.'/';?>assets/system_design/images/fevicon.png" type="image/x-icon"/>
	<meta name="theme-color" content="#fb6f6f">
<!-- Windows Phone -->
<meta name="msapplication-navbutton-color" content="#fb6f6f">
<!-- iOS Safari -->
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <!-- Bootstrap -->
    <link href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/font-awesome.css" rel="stylesheet">
	<link href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/style.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/flaticon.css"> 
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/fonts.css"> 
    <!--bootstrap Slider Range-->
	<link href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/bootstrap-slider.css" rel="stylesheet">
	<link href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/jquery.dataTables.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]--> 
  </head>
  <body> 
  
<div class="container">
<div class="row">
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-center-xs">
<div class="shuttle"><i class="fa  fa-tachometer"></i> <?php if(isset($site_settings->site_title)) echo $site_settings->site_title; else echo 'Shuttle Booking System'; ?> </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-center-xs">
<div class="social">
<?php
if(isset($site_settings->facebook) && $site_settings->facebook != '') {
?>
<a href="<?php echo $site_settings->facebook;?>" target="_blank"><i class="fa fa-facebook"></i> </a>
<?php } ?>

<?php
if(isset($site_settings->twitter) && $site_settings->twitter != '') {
?>
<a href="<?php echo $site_settings->twitter;?>" target="_blank"><i class="fa fa-twitter"></i></a>
<?php } ?>

<?php
if(isset($site_settings->linkedin) && $site_settings->linkedin != '') {
?>
<a href="<?php echo $site_settings->linkedin;?>" target="_blank"><i class="fa fa-linkedin"></i></a>
<?php } ?>

<?php
if(isset($site_settings->google_plus) && $site_settings->google_plus != '') {
?>
<a href="<?php echo $site_settings->google_plus;?>" target="_blank"><i class="fa fa-google-plus"></i></a>
<?php } ?>

<?php
if(isset($site_settings->pinterest) && $site_settings->pinterest != '') {
?>
<a href="<?php echo $site_settings->pinterest;?>" target="_blank"><i class="fa fa-pinterest"></i></a>
<?php } ?>

<?php
if(isset($site_settings->instagram) && $site_settings->instagram != '') {
?>
<a href="<?php echo $site_settings->instagram;?>" target="_blank"><i class="fa fa-instagram"></i></a>
<?php } ?>

</div>
</div>
</div>
</div>
 
<section class="header">
<div class="container">
<div class="row">
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
<h1 class="logo"><a href="<?php echo basE_url();?>"><img src="<?php echo base_url().$site_theme.'/';?>assets/system_design/images/<?php if($site_settings->site_logo != "" && file_exists($site_theme.'/'.'assets/system_design/images/'.$site_settings->site_logo)) echo $site_settings->site_logo; else echo "logo.png";?>"> </a></h1>
</div>

<?php if(!$this->ion_auth->logged_in()) { ?>
<div class="col-lg-5 col-md-5 col-sm-6 col-xs-12">
<nav class="navbar navbar-default top-menu">
 
    <!-- Brand and toggle get grouped for better mobile display -->
 

    <!-- Collect the nav links, forms, and other content for toggling -->
 
      <ul class="nav navbar-nav">
        
		<li class="active"><a href="<?php echo basE_url();?>auth/login"><i class="flaticon-tool"></i> Login </a></li>
        <li><a href="<?php echo base_url();?>auth/create_user"><i class="flaticon-social"></i> Sign Up</a></li>
		
	  <?php $session_deails = $this->session->userdata('journey_booking_details'); if(!empty($session_deails)) { ?>
	  <li id="dropdownMenu2"  class="dropdown">
	  <!--<a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="<?php echo base_url()?>bookingseat/index"><span class="caret"></span> Cancel </a>-->
	  <ul class="dropdown-menu pc" aria-labelledby="dropdownMenu2">
		<li> <a href="<?php echo base_url();?>bookingseat/search_ticket"><i class="flaticon-paper"></i> Print / SMS Ticket  </a></li>
		<!--<li> <a href="<?php echo base_url();?>bookingseat/cancel_ticket"><i class="flaticon-close"></i> Cancel Ticket </a></li>-->
	  </ul>
	  </li>
	  <?php } else {
		  ?>
		  <li> <a href="<?php echo base_url();?>bookingseat/search_ticket"><i class="flaticon-paper"></i> Print / SMS Ticket  </a></li>
		<!--<li> <a href="<?php echo base_url();?>bookingseat/cancel_ticket"><i class="flaticon-close"></i> Cancel Ticket </a></li>-->
		  <?php
	  }?>
	  
		
		<?php $session_deails = $this->session->userdata('journey_booking_details'); if(!empty($session_deails)) { ?>
		<li> <a href="<?php echo URL_BOOKINGSEAT_CLEARSELECTION;?>"><i class="flaticon-close"></i> <?php echo getPhrase('Cancel Selections');?> </a></li>
		<?php } ?>
		
      </ul>
      
	  
	   
 
	
 
</nav>
</div>
<?php } ?>
<?php if(!$this->ion_auth->logged_in()) { 
	   ?>
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 pull-right">
<div class="customer-care">
<i class="flaticon-technology"></i>
<h6>(24/7 Customer Support)</h6>
<h1><?php echo isset($site_settings->call_center) ? $site_settings->call_center : '';?></h1>
</div>
</div>
<?php } ?>
<?php if($this->ion_auth->logged_in()) { 
	   ?>
<div class="col-lg-6 col-md-7 col-sm-9 col-xs-12 pull-right">	


 
	<div class="af-lo">
       <div class="customer-care pull-right cc"> 
	   <div class="dropdown af-us">
  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
      <i class="flaticon-social"></i>  <h6>	<?php echo getPhrase('Hi!');?> <a href="<?php echo base_url()?>bookingseat/my-bookings"><?php echo (isset(getUserRec()->username)) ? getUserRec()->username : getPhrase('User');?></a> <span class="caret"></span> </h6>  
    
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
	<?php $session_deails = $this->session->userdata('journey_booking_details'); if(!empty($session_deails)) { ?>
	<li> <a href="<?php echo base_url();?>bookingseat/clearselection"><i class="flaticon-paper"></i> Cancel Selections  </a></li>
	<?php } ?>
	
	<li> <a href="<?php echo base_url();?>bookingseat/search_ticket"><i class="flaticon-paper"></i> Print / SMS Ticket  </a></li>
	<!--<li> <a href="<?php echo base_url();?>bookingseat/cancel_ticket"><i class="flaticon-close"></i> Cancel Ticket </a></li>-->
    <li role="separator" class="divider"></li>
    <?php 
	$link_title = 'Booking History';
	$url = base_url().'bookingseat/booking_history';
	if($this->ion_auth->is_admin())
	$url = base_url().'admin/viewBookings/todayz';
	if($this->ion_auth->is_executive())
	$url = base_url().'executive/viewBookings/todayz';
	if($this->ion_auth->is_driver())
	{
	$url = base_url().'driver/index';
	$link_title = 'Dashboard';
	}
	?>
	<li><a href="<?php echo $url;?>"><i class="flaticon-paper"></i>  <?php echo $link_title;?> </a></li>
  <?php $url = base_url().'bookingseat/profile';
  if($this->ion_auth->is_admin())
	  $url = base_url().'admin/profile';
  if($this->ion_auth->is_executive())
	  $url = base_url().'executive/profile';
  ?>
  <li><a href="<?php echo $url;?>"><i class="flaticon-social"></i>  Profile </a> </li>
	
	<?php $url = base_url().'auth/change_password';
	if($this->ion_auth->is_admin())
	$url = base_url().'auth/change_password';
	if($this->ion_auth->is_executive())
	$url = base_url().'auth/change_password';
	?>
	<li><a href="<?php echo $url;?>"><i class="fa fa-key"></i>  Change Password </a> </li>
    <li><a href="<?php echo base_url();?>auth/logout"><i class="fa fa-power-off"></i>  Sign Out </a> </li>
  </ul>
</div>
</div>
<div class="customer-care pull-right">
<i class="flaticon-technology"></i>
<h6>(24/7 Customer Support)</h6>
<h1><?php echo isset($site_settings->call_center) ? $site_settings->call_center : '';?></h1>
</div>
</div>
</div>
<?php } ?>
</div>
</section>  

<div class="container">
<div class="row">
<div class="col-lg-12">
<nav class="navbar navbar-default menu">
 
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
 
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="<?php if(isset($active_class) && $active_class == "home") echo "active";?>"><a href="<?php echo base_url();?>">Home           </a></li>
        <?php foreach($this->dynamicpages as $ind => $page) { 
			if($page->page_title == 'About Us')
			{
		?>
			<li><a href="<?php echo base_url();?>pages/index/<?php echo $page->page_id;?>">About Us</a></li>
			<?php }} ?>
        <li class="<?php if(isset($active_class) && $active_class == "faqs") echo "active";?>"><a href="<?php echo base_url();?>welcome/faqs">FAQs </a></li>
        <li class="<?php if(isset($active_class) && $active_class == "offers") echo "active";?>"><a href="<?php echo base_url();?>offers">Offers  <img src="<?php echo base_url().$site_theme.'/';?>assets/system_design/images/new.gif"></a></li>
       
        
        <?php if(!empty($this->dynamicpages)) { ?>
		<li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Pages          <span class="caret"></span></a>
          <ul class="dropdown-menu dp">
            <?php foreach($this->dynamicpages as $page) { 
			if(!in_array($page->page_title, array('About Us', 'Contact Us', 'Terms & Conditions')))
			{
			?>
			<li><a href="<?php echo base_url();?>pages/index/<?php echo $page->page_id;?>"><?php echo $page->page_title;?></a></li>
			<?php }
			} ?>
          </ul>
        </li>
		<?php } ?>
         <li class="<?php if(isset($active_class) && $active_class == "contact_us") echo "active";?>"><a href="<?php echo base_url();?>contact">Contact Us</a></li>
      </ul>
      
      
    </div><!-- /.navbar-collapse -->
 
</nav>
</div>
</div>
</div>
<?php //echo '<pre>'; print_r($this->dynamicpages);?>