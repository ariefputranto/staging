<section class="work_section tp">
  <div class="container-fluid">
  <div class="col-lg-2 col-md-2 col-sm-12 padding-lr">
    <div id='cssmenu'>
      <ul>
        <li class="<?php if(isset($active_menu) && $active_menu == "dashboard") echo " active";?>"><a href="<?php echo base_url();?>admin"><span> <i class="fa fa-dashboard"></i><?php if(isset($this->phrases["dashboard"])) echo $this->phrases["dashboard"]; else echo "Dashboard";?></span></a> </li>

        <li class="has-sub<?php if(isset($active_menu) && $active_menu == "view_bookings") echo " active";?>"><a href="#"><span> <i class="fa  fa-video-camera"></i>  <?php if(isset($this->phrases["view bookings"])) echo $this->phrases["view bookings"]; else echo "View Bookings";?></span></a>
          <ul class="bb">
            <li><a href="<?php echo base_url();?>admin/viewBookings/todayz"><span><?php if(isset($this->phrases["today's"])) echo $this->phrases["today's"]; else echo "Today's";?></span></a></li>
            <li><a href="<?php echo base_url();?>admin/viewBookings/all"><span><?php if(isset($this->phrases["all"])) echo $this->phrases["all"]; else echo "All";?></span></a></li>
            <li><a href="<?php echo base_url();?>admin/viewBookings/Cancelled"><span><?php if(isset($this->phrases["cancelled"])) echo $this->phrases["cancelled"]; else echo "Cancelled";?></span></a></li>
          </ul>
        </li>
        
		<li class="<?php if(isset($active_menu) && $active_menu == "admin_profile") echo " active";?>"><a href="<?php echo base_url();?>admin/profile"><span> <i class="fa fa-user"></i>  <?php if(isset($this->phrases["profile"])) echo $this->phrases["profile"]; else echo "Profile";?> </span></a></li>
        <li class="<?php if(isset($active_menu) && $active_menu == "change_password") echo " active";?>"><a href="<?php echo base_url();?>auth/change_password"><span> <i class="fa fa-edit"></i> <?php if(isset($this->phrases["change password"])) echo $this->phrases["change password"]; else echo "Change Password";?></span></a></li>
        <li><a href="<?php echo base_url();?>auth/logout"><span> <i class="fa fa-power-off"></i> <?php if(isset($this->phrases["logout"])) echo $this->phrases["logout"]; else echo "Logout";?></span></a></li>
 
      </ul>
    </div>
  </div>

  <!-- Breadcrumb -->
  <?php if(isset($heading) || isset($sub_heading)) { ?>
  <div class="col-md-10 padding-0">
	<div class="brade">
			<a href="<?php echo base_url();?>admin"><?php if(isset($this->phrases["home"])) echo $this->phrases["home"]; else echo "Home";?></a> 
			<?php if(isset($heading)) echo " >> ".$heading;?>
			<?php if(isset($sub_heading)) echo " >> ".$sub_heading;?>
			<?php if(isset($overallVehicles)) echo " (".$overallVehicles.")";?>
			<?php if(isset($language_name)) echo " >> ".ucwords($language_name);?>
		</div>
	</div>
   <?php } ?>
