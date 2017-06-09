<!DOCTYPE html>
<html lang="en">
<!--[if IE 7]><html lang="en" class="ie7"><![endif]-->
<!--[if IE 8]><html lang="en" class="ie8"><![endif]-->
<!--[if IE 9]><html lang="en" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><html lang="en"><![endif]-->
<!--[if !IE]><html lang="en"><![endif]-->
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="author" content="">

<meta name="keywords" content="<?php if(isset($this->config->item('seoSettings')->meta_keywords)) echo $this->config->item('seoSettings')->meta_keywords; elseif(isset($site_settings->site_title)) echo $site_settings->site_title; ?>" />

<meta name="description" content="<?php if(isset($this->config->item('seoSettings')->meta_description)) echo $this->config->item('seoSettings')->meta_description; elseif(isset($site_settings->site_title)) echo $site_settings->site_title; ?>" />


<title> <?php if(isset($title)) echo $title; if(isset($site_settings->site_title)) echo " - ".$site_settings->site_title; ?></title>
<!--style start-->
<link href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/bootstrap.css" rel="stylesheet">
<!--<link href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/style.css" rel="stylesheet">-->
<link href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/style_vehicle.css" rel="stylesheet">

<link href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/font-awesome.css" rel="stylesheet">

<link href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/side-menu.css" rel="stylesheet">
<link href="<?php echo base_url().$site_theme.'/';?>/assets/system_design/css/chosen.min.css" rel="stylesheet" media="screen">

<?php if(isset($css_type) && in_array("datatable",$css_type)) { ?>
<link href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/jquery.dataTables.css" rel="stylesheet">
<?php } ?>

<?php if(isset($css_type) && in_array("datepicker",$css_type)) { ?>
<link href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/datepicker.css" rel="stylesheet">
<link href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/BeatPicker.min.css" rel="stylesheet">
<?php } ?>

<?php if(isset($css_type) && in_array("calendar",$css_type)) { ?>
<link href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/responsive-calendar.css" rel="stylesheet">
<?php } ?>

<?php if(isset($this->config->item('seoSettings')->google_analytics)) echo $this->config->item('seoSettings')->google_analytics; ?>

</head>

<script>

	function changeLanguage(lang)
	{
	
		if(lang > 0) {
		
			window.location = "<?php echo base_url();?>settings/changeLanguage/"+lang;
		
		}
	
	}

</script>

<section class="top_wrapper">
 
  <div class="header admin-header">
    <div class="navbar-header">
      <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <div class="navbar-brand lg">
        <div class="logo"> <a href="<?php echo base_url();?>driver"><img height="50" src="<?php echo base_url().$site_theme.'/';?>assets/system_design/images/<?php if($site_settings->site_logo != "" && file_exists($site_theme.'/'.'assets/system_design/images/'.$site_settings->site_logo)) echo $site_settings->site_logo; else echo "logo.png";?>"></a> </div>
        <!--./logo--></div>
    </div>
 
    <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
  
  
  <div class="col-lg-1"></div>
  
 
  
  <div class="col-lg-4 pull-right">
  <div class="dropdown ad">
        <ul class="message_ul">
          <!-- <li><a title="<?php if(isset($this->phrases["today's bookings"])) echo $this->phrases["today's bookings"]; else echo "Today's Bookings";?>" href="<?php echo base_url();?>admin/viewBookings/todayz" class="round_div" ><i class="fa fa-envelope"></i><span class="badge bg-success"><?php echo $this->config->item('count_of_todayz_bookings');?></span></a>
          </li> -->

        </ul>
  </div>

   <ul class="navbar-right login user_proile">
        <li><a href="#"><img height="38" src="<?php echo base_url();?>uploads/driver_profile_pic/<?php if($this->session->userdata('photo') != "" && file_exists('uploads/driver_profile_pic/'.$this->session->userdata('photo'))) echo $this->session->userdata('photo'); else echo "noimage.jpg";?>"> <?php echo $this->session->userdata('username')?></a>
          <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
            <li><a tabindex="-1" href="<?php echo base_url();?>driver/profile"> <i class="fa fa-user"></i> <?php if(isset($this->phrases["my profile"])) echo $this->phrases["my profile"]; else echo "My Profile";?></a></li>
            <li><a tabindex="-1" href="<?php echo base_url();?>auth/change_password"> <i class="fa fa-newspaper-o"></i> <?php if(isset($this->phrases["change password"])) echo $this->phrases["change password"]; else echo "Change Password";?></a></li>
			<li><a href="<?php echo base_url();?>auth/logout" class="" title="<?php if(isset($this->phrases["logout"])) echo $this->phrases["logout"]; else echo "Logout";?>"><i class="fa fa-power-off"></i> <?php if(isset($this->phrases["logout"])) echo $this->phrases["logout"]; else echo "Logout";?></a></li>
          </ul>
        </li>
     
        
      </ul>   
  </div>
 
     
    </nav>
  </div>
</section>
