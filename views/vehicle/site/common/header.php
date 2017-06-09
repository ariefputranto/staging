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
<!-- Bootstrap -->
<link href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/dn-style.css" rel="stylesheet">
<link href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/font-awesome.css" rel="stylesheet">
<link href="<?php echo base_url().$site_theme.'/';?>/assets/system_design/css/chosen.min.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url().$site_theme.'/';?>/assets/system_design/css/open-sans.css" rel="stylesheet">

<?php if(isset($css_type) && in_array("datatable",$css_type)) { ?>
<link href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/jquery.dataTables.css" rel="stylesheet">
<?php } ?>

<?php if(isset($css_type) && in_array("datepicker",$css_type)) { ?>
<link href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/datepicker.css" rel="stylesheet">
<?php } ?>

<?php if(isset($css_type) && in_array("timepicker",$css_type)) { ?>
<link href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/timepicki.css" rel="stylesheet">
<?php } ?>

<?php if(isset($css_type) && in_array("bxslider",$css_type)) { ?>
<link href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/bx-slider.css" rel="stylesheet">
<?php } ?>

 
<?php if(isset($this->config->item('seoSettings')->google_analytics)) echo $this->config->item('seoSettings')->google_analytics; ?>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
 
<body class="bg">

	<div class="top-section">
	<div class="container">
	<div class="row"> 

	<div class="col-lg-4">
	<div class="social"> 
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
	
	</div> 	<a class="left"><i class="fa fa-bars"></i></a></div>
	<div class="col-lg-4"> <a href="<?php echo base_url();?>"> <img class="logo" src="<?php echo base_url().$site_theme.'/';?>assets/system_design/images/<?php if($site_settings->site_logo != "" && file_exists($site_theme.'/'.'assets/system_design/images/'.$site_settings->site_logo)) echo $site_settings->site_logo; else echo "logo.png";?>"> </a> </div>
	<div class="col-lg-4">
	<div class="right1">
		<?php
			if(!$this->ion_auth->logged_in()) {
				if(isset($active_class) && $active_class == "create_account") {  } else { 
			?>
			
			
			<?php } if(isset($active_class) && $active_class == "login") {  } else { ?>
			<a class="alogin" href="<?php echo base_url();?>auth/login" >  <i class="fa fa-sign-in"></i> <?php if(isset($this->phrases["login"])) echo $this->phrases["login"]; else echo "Login";?></a>
			<a class="areg" href="<?php echo base_url();?>auth/create_user"> <i class="fa fa-user-plus"></i> <?php if(isset($this->phrases["sign up"])) echo $this->phrases["sign up"]; else echo "Sign Up";?></a>
			
		 <?php } } else { ?>
		  <div class="dropdown">
    <button class="btn btn-primary dropdown-toggle afteruser" type="button" data-toggle="dropdown"><a class="usr-top"> <?php if(isset($this->phrases["hi"])) echo $this->phrases["hi"]; else echo "Hi";?> <?php echo ucwords($this->session->userdata('username')).", ";?></a>
    <span class="caret"></span></button>
    <ul class="dropdown-menu">
      <?php if($this->ion_auth->is_admin()) { ?>
	  <li><a class="" href="<?php echo base_url();?>admin" > <?php if(isset($this->phrases["dashboard"])) echo $this->phrases["dashboard"]; else echo "Dashboard";?></a></li>
	  <?php } else { ?>
      <li><a class="" href="<?php echo base_url();?>auth/edit_user/<?php echo $this->session->userdata('user_id');?>" > <?php if(isset($this->phrases["account"])) echo $this->phrases["account"]; else echo "Account";?></a></li>
      <li><a class="" href="<?php echo base_url();?>auth/change_password" > <?php if(isset($this->phrases["change password"])) echo $this->phrases["change password"]; else echo "Change Password";?></a></li>
	  <li><a class="" href="<?php echo base_url();?>client/myBookings" > <?php if(isset($this->phrases["booking history"])) echo $this->phrases["booking history"]; else echo "Booking History";?></a></li> 
	  <?php } ?>
	  <li><a class="" href="<?php echo base_url();?>auth/logout" > <?php if(isset($this->phrases["logout"])) echo $this->phrases["logout"]; else echo "Logout";?></a></li>
    </ul>
  </div>
<?php } ?>

			
  </div>
 <a class="right"><i class="fa fa-bars"></i></a> 
	</div>	

	</div>
	</div>
 </div>
 
 
 <div class="leftpanel"> 
 <div class="leftButtons">
 <select name="vehicle_category" id="vehicle_category" onchange="javascript:getVehicles('onchg')">
 <?php 
 if(count($this->vehicle_typeshome) > 0) {
	 foreach($this->vehicle_typeshome as $vtype) {
	 ?>
	 <option value="<?php echo $vtype->id;?>"><?php echo $vtype->category;?></option>
	 <?php }
 }?>
 </select>
 <?php $start_locationshome = $this->base_model->getLocations("start");?>
 <select id="location" name="location" onchange="javascript:getVehicles('onchg')">
 <?php 
 if(count($start_locationshome) > 0) {
	 foreach($start_locationshome as $vtype) {
	 ?>
	 <option value="<?php echo $vtype->id;?>"><?php echo $vtype->location;?></option>
	 <?php }
 }?>
 </select>
 <input type="hidden" name="result_no" id="result_no" value="0">
</div>
<script>
function getVehicles(cas)
{
	var result_no = $('#result_no').val();
	$.ajax({
		  type: "post",
		  url: "<?php echo base_url();?>pages/getvehicles",
		  async: false,
		  data: { 
					category : $('#vehicle_category').val(),
					start_id : $('#location').val(),
					result_no : result_no,
					cas : cas,
					<?php echo $this->security->get_csrf_token_name();?>:
					"<?php echo $this->security->get_csrf_hash();?>"
				},
		  cache: false, 
		  success: function(data) {
			var t = $.parseJSON( data )
			if(t.records == 0)
			{
				$('#viewmore').hide();
			}
			if(t.totalvehicles < t.result_no * 5)
						$('#viewmore').hide();
			if(t.cas == 'onchg')
			{
				$('#cars_list').html(t.html);
			}
			else
			{					
			$('#cars_list').append(t.html);
			document.getElementById("result_no").value = Number(result_no)+5;			
			}
			
		  },
		  error: function(){
			alert('Ajax Error');
		  }
		});
}
</script>

 <ul id="cars_list"></ul>
 <ul id="more_data">
 </ul><button type="button" id="viewmore" class="btn btn-default viermore" >vier more</button> 
 </div>
 
 
 <div class="rightpanel">
 <h2><?php if(isset($this->phrases["Support Center"])) echo $this->phrases["Support Center"]; else echo "Support Center";?></h2>
  <ul>
 <?php
 if(isset($site_settings->call_center)) echo '<li>' . $site_settings->call_center . '</li>';
 ?> 
 <?php
 if(isset($site_settings->email_support)) echo '<li>' . $site_settings->email_support . '</li>';
 ?>
 <?php
 
 if(isset($site_settings->faq_page)) 
 {
	 //echo '<pre>';
 //print_r($this->phrases);
	 ?><li>
	 <img src="<?php echo base_url().$site_theme.'/';?>assets/system_design/images/support3.png">
	 <h3><a href="<?php echo base_url();?>welcome/faqs"><?php if(isset($this->phrases["Search FAQ's"])) echo $this->phrases["Search FAQ's"]; else echo "Search FAQ’s";?></a></h3>
	 </li>
	 <?php
 }
 ?>
 <?php
 if(isset($site_settings->support_ticket)) echo '<li>' . $site_settings->support_ticket . '</li>';
 ?>
 <?php
 if(isset($site_settings->live_chat))
	 echo '<li>' . $site_settings->live_chat . '</li>';
 ?>
 </ul>
 </div>
 
