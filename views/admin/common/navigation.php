<section class="work_section tp">
  <div class="container-fluid">
  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12 padding-lr">
    
  <nav class="collapse navbar-collapse bs-navbar-collapse padding-0" role="navigation">
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

        <li class="has-sub<?php if(isset($active_menu) && $active_menu == "vehicle_settings") echo " active";?>"><a href="#"><span> <i class="fa fa-cab"></i> <?php if(isset($this->phrases["vehicle settings"])) echo $this->phrases["vehicle settings"]; else echo "Vehicle Settings";?></span></a>
          <ul class="bb">
            <li><a href="<?php echo base_url();?>settings/vehicles/list"><span><?php if(isset($this->phrases["list vehicles"])) echo $this->phrases["list vehicles"]; else echo "List Vehicles";?></span></a></li>
			
			<li><a href="<?php echo base_url();?>settings/change_vehicle"><span><?php if(isset($this->phrases["Change Vehicle"])) echo $this->phrases["Change Vehicle"]; else echo "Change Vehicle";?></span></a></li>
			
			<li><a href="<?php echo base_url();?>settings/vehicles_schedule"><span><?php if(isset($this->phrases["Vehicle Schedules"])) echo $this->phrases["Vehicle Schedules"]; else echo "Vehicle Schedules";?></span></a></li>
			
			<li><a href="<?php echo base_url();?>settings/vehicles/add"><span><?php if(isset($this->phrases["add vehicle"])) echo $this->phrases["add vehicle"]; else echo "Add Vehicle";?></span></a></li>
			
			<li><a href="<?php echo base_url();?>settings/vehicleCategories/list"><span><?php if(isset($this->phrases["vehicle categories"])) echo $this->phrases["vehicle categories"]; else echo "Vehicle Categories";?></span></a></li>
			<li><a href="<?php echo base_url();?>settings/vehicleFeatures/list"><span><?php if(isset($this->phrases["vehicle features"])) echo $this->phrases["vehicle features"]; else echo "Vehicle Features";?></span></a></li>
			
			

          </ul>
        </li>

		<li class="has-sub<?php if(isset($active_menu) && $active_menu == "locations") echo " active";?>"><a href="#"><span> <i class="fa fa-map-marker"></i> <?php if(isset($this->phrases["location settings"])) echo $this->phrases["location settings"]; else echo "Location Settings";?></span></a>
          <ul class="bb">
            <li><a href="<?php echo base_url();?>settings/locations/list"><span><?php if(isset($this->phrases["locations"])) echo $this->phrases["locations"]; else echo "Locations";?></span></a></li>
			<li><a href="<?php echo base_url();?>settings/travelLocations/list"><span><?php if(isset($this->phrases["travel locations"])) echo $this->phrases["travel locations"]; else echo "Travel Locations";?></span></a></li>
			
			<li><a href="<?php echo base_url();?>settings/travelLocationCosts/list"><span><?php if(isset($this->phrases["travel locations cost"])) echo $this->phrases["travel locations cost"]; else echo "Travel Locations Cost";?></span></a></li>
			
			<li><a href="<?php echo base_url();?>settings/price_variations/list"><span><?php if(isset($this->phrases["price variations"])) echo $this->phrases["price variations"]; else echo "Price Variations";?></span></a></li>

          </ul>
        </li>

        <li class="has-sub<?php if(isset($active_menu) && $active_menu == "users") echo " active";?>"><a href="#"><span> <i class="fa fa-users"></i> <?php if(isset($this->phrases["users"])) echo $this->phrases["users"]; else echo "Users";?></span></a>
          <ul class="bb">
            <li><a href="<?php echo base_url();?>auth/users"><span><?php if(isset($this->phrases["list users"])) echo $this->phrases["list users"]; else echo "List Users";?></span></a></li>
            <li><a href="<?php echo base_url();?>auth/create_user"><span><?php if(isset($this->phrases["add user"])) echo $this->phrases["add user"]; else echo "Add User";?></span></a></li>

          </ul>
        </li>


        <li class="<?php if(isset($active_menu) && $active_menu == "site_settings") echo " active";?>"><a href="<?php echo base_url();?>settings/siteSettings"><span> <i class="fa fa-cogs"></i>  <?php if(isset($this->phrases["site settings"])) echo $this->phrases["site settings"]; else echo "Site Settings";?> </span></a></li>
		
		<li class="<?php if(isset($active_menu) && $active_menu == "pages") echo " active";?>"><a href="<?php echo base_url();?>settings/pages"><span> <i class="fa fa-file"></i>  <?php if(isset($this->phrases["dynamic pages"])) echo $this->phrases["dynamic pages"]; else echo "Dynamic Pages";?> </span></a></li>
		
		<li class="<?php if(isset($active_menu) && $active_menu == "faqs") echo " active";?>"><a href="<?php echo base_url();?>faqs/index"><span> <i class="fa fa-question-circle"></i>  <?php if(isset($this->phrases["faqs"])) echo $this->phrases["faqs"]; else echo "FAQs";?> </span></a></li>

        <li class="<?php if(isset($active_menu) && $active_menu == "email_settings") echo " active";?>"><a href="<?php echo base_url();?>settings/emailSettings"><span> <i class="fa fa-envelope"></i>  <?php if(isset($this->phrases["email settings"])) echo $this->phrases["email settings"]; else echo "Email Settings";?> </span></a></li>

        <li class="<?php if(isset($active_menu) && $active_menu == "seo_settings") echo " active";?>"><a href="<?php echo base_url();?>settings/seoSettings"><span> <i class="fa fa-bar-chart"></i>  <?php if(isset($this->phrases["seo settings"])) echo $this->phrases["seo settings"]; else echo "SEO Settings";?> </span></a></li>

         <li class="has-sub<?php if(isset($active_menu) && $active_menu == "offers") echo " active";?>"><a href="#"><span> <i class="fa fa-dropbox"></i> <?php if(!empty($this->phrases["offers"])) echo $this->phrases["offers"]; else echo "Offers";?></span></a>
          <ul class="bb">
            <li><a href="<?php echo base_url();?>admin/offers"><span><?php if(isset($this->phrases["list"])) echo $this->phrases["list"]; else echo "List";?></span></a></li>
            <li><a href="<?php echo base_url();?>admin/offers/create"><span><?php if(isset($this->phrases["create"])) echo $this->phrases["create"]; else echo "Create";?></span></a></li>

          </ul>
        </li>
		
		<li class="has-sub<?php if(isset($active_menu) && $active_menu == "driver") echo " active";?>"><a href="#"><span> <i class="fa fa-list"></i> <?php if(isset($this->phrases["Driver Management"])) echo $this->phrases["Driver Management"]; else echo "Driver Management";?></span></a>
          <ul class="bb">
            <li><a href="<?php echo base_url();?>managedriver/index"><span><?php if(isset($this->phrases["List Drivers"])) echo $this->phrases["List Drivers"]; else echo "List Drivers";?></span></a></li>
            <li><a href="<?php echo base_url();?>managedriver/create_account"><span><?php if(isset($this->phrases["Add Driver"])) echo $this->phrases["Add Driver"]; else echo "Add Driver";?></span></a></li>
			<li><a href="<?php echo base_url();?>managedriver/change_driver"><span><?php if(isset($this->phrases["Changes Driver"])) echo $this->phrases["Changes Driver"]; else echo "Changes Driver";?></span></a></li>

          </ul>
        </li>

        <li class="<?php if(isset($active_menu) && $active_menu == "cancellation_policy") echo " active";?>"><a href="<?php echo base_url();?>settings/cancellationPolicySettings"><span> <i class="fa fa-close"></i>  <?php if(isset($this->phrases["cancellation policy"])) echo $this->phrases["cancellation policy"]; else echo "Cancellation Policy";?> </span></a></li>

        <li class="has-sub<?php if(isset($active_menu) && $active_menu == "languages") echo " active";?>"><a href="#"><span> <i class="fa fa-globe"></i> <?php if(isset($this->phrases["language settings"])) echo $this->phrases["language settings"]; else echo "Language Settings";?></span></a>
          <ul class="bb">
            <li><a href="<?php echo base_url();?>settings/languages"><span><?php if(isset($this->phrases["list languages"])) echo $this->phrases["list languages"]; else echo "List Languages";?></span></a></li>
            <li><a href="<?php echo base_url();?>settings/add_edit_Lang"><span><?php if(isset($this->phrases["add language"])) echo $this->phrases["add language"]; else echo "Add Language";?></span></a></li>
            <li><a href="<?php echo base_url();?>settings/add_edit_Phrase"><span><?php if(isset($this->phrases["add phrase"])) echo $this->phrases["add phrase"]; else echo "Add Phrase";?></span></a></li>
            <li><a href="<?php echo base_url();?>settings/addPhrasesByExcel"><span><?php if(isset($this->phrases["add phrase by excel"])) echo $this->phrases["add phrase by excel"]; else echo "Add Phrases By Excel";?></span></a></li>

          </ul>
        </li>


		<li class="has-sub<?php if(isset($active_menu) && $active_menu == "testimonials") echo " active";?>"><a href="#"><span> <i class="fa fa-list"></i> <?php if(isset($this->phrases["testimonials"])) echo $this->phrases["testimonials"]; else echo "Testimonials";?></span></a>
          <ul class="bb">
            <li><a href="<?php echo base_url();?>admin/testimonials"><span><?php if(isset($this->phrases["testimonials"])) echo $this->phrases["testimonials"]; else echo "Testimonials";?></span></a></li>
            <li><a href="<?php echo base_url();?>admin/testimonials/add"><span><?php if(isset($this->phrases["add testimonial"])) echo $this->phrases["add testimonial"]; else echo "Add testimonial";?></span></a></li>
          </ul>
        </li>
		
		<li class="has-sub<?php if(isset($active_menu) && $active_menu == "templates") echo " active";?>"><a href="#"><span> <i class="fa fa-list"></i> <?php if(isset($this->phrases["email templates"])) echo $this->phrases["email templates"]; else echo "Email Templates";?></span></a>
          <ul class="bb">
            <li><a href="<?php echo base_url();?>admin/templates"><span><?php if(isset($this->phrases["templates"])) echo $this->phrases["templates"]; else echo "Templates";?></span></a></li>
            <li><a href="<?php echo base_url();?>admin/templates/add"><span><?php if(isset($this->phrases["add template"])) echo $this->phrases["add template"]; else echo "Add template";?></span></a></li>
          </ul>
        </li>
		
		<li class="has-sub<?php if(isset($active_menu) && $active_menu == "smstemplates") echo " active";?>"><a href="#"><span> <i class="fa fa-list"></i> <?php if(isset($this->phrases["sms templates"])) echo $this->phrases["sms templates"]; else echo "SMS Templates";?></span></a>
          <ul class="bb">
            <li><a href="<?php echo base_url();?>admin/smstemplates"><span><?php if(isset($this->phrases["templates"])) echo $this->phrases["templates"]; else echo "Templates";?></span></a></li>
            <li><a href="<?php echo base_url();?>admin/smstemplates/add"><span><?php if(isset($this->phrases["add template"])) echo $this->phrases["add template"]; else echo "Add template";?></span></a></li>
          </ul>
        </li>
		
        <li class="has-sub<?php if(isset($active_menu) && $active_menu == "reports") echo " active";?>"><a href="#"><span> <i class="fa fa-list"></i> <?php if(isset($this->phrases["reports"])) echo $this->phrases["reports"]; else echo "Reports";?></span></a>
          <ul class="bb">
            <li><a href="<?php echo base_url();?>reports/overallVehicles"><span><?php if(isset($this->phrases["overall vehicles"])) echo $this->phrases["overall vehicles"]; else echo "Overall Vehicles";?></span></a></li>
            <li><a href="<?php echo base_url();?>reports/payments"><span><?php if(isset($this->phrases["payments"])) echo $this->phrases["payments"]; else echo "Payments";?></span></a></li>

          </ul>
        </li>
		
		
		
		<li class="<?php if(isset($active_menu) && $active_menu == "sms") echo " active";?>"><a href="<?php echo base_url();?>admin/sms_settings"><span> <i class="fa  fa-whatsapp"></i>  <?php if(isset($this->phrases["sms"])) echo $this->phrases["sms"]; else echo "SMS";?> </span></a></li>
		
		<li class="<?php if(isset($active_menu) && $active_menu == "paymentgateway") echo " active";?>"><a href="<?php echo base_url();?>admin/paymentsettings"><span> <i class="fa fa-cc-mastercard"></i>  <?php if(isset($this->phrases["payment gateways"])) echo $this->phrases["payment gateways"]; else echo "Payment Gateways";?> </span></a></li>
		
		<li class="has-sub<?php if(isset($active_menu) && $active_menu == "banks") echo " active";?>"><a href="#"><span> <i class="fa fa-list"></i> <?php if(isset($this->phrases["banks"])) echo $this->phrases["banks"]; else echo "Banks";?></span></a>
          <ul class="bb">
            <li><a href="<?php echo base_url();?>admin/banks"><span><?php if(isset($this->phrases["banks"])) echo $this->phrases["banks"]; else echo "Banks";?></span></a></li>
            <li><a href="<?php echo base_url();?>admin/banks/add"><span><?php if(isset($this->phrases["add bank"])) echo $this->phrases["add bank"]; else echo "Add bank";?></span></a></li>
          </ul>
        </li>



        <li class="<?php if(isset($active_menu) && $active_menu == "site_backup") echo " active";?>"><a href="<?php echo base_url();?>admin/siteBackup"><span> <i class="fa fa-folder-open"></i>  <?php if(isset($this->phrases["site backup"])) echo $this->phrases["site backup"]; else echo "Site Backup";?> </span></a></li>
		<li class="<?php if(isset($active_menu) && $active_menu == "admin_profile") echo " active";?>"><a href="<?php echo base_url();?>admin/profile"><span> <i class="fa fa-user"></i>  <?php if(isset($this->phrases["profile"])) echo $this->phrases["profile"]; else echo "Profile";?> </span></a></li>
        <li class="<?php if(isset($active_menu) && $active_menu == "change_password") echo " active";?>"><a href="<?php echo base_url();?>auth/change_password"><span> <i class="fa fa-edit"></i> <?php if(isset($this->phrases["change password"])) echo $this->phrases["change password"]; else echo "Change Password";?></span></a></li>
        <li><a href="<?php echo base_url();?>auth/logout"><span> <i class="fa fa-power-off"></i> <?php if(isset($this->phrases["logout"])) echo $this->phrases["logout"]; else echo "Logout";?></span></a></li>
 
      </ul>
    </div></nav>
  </div>

  <!-- Breadcrumb -->
  <?php if(isset($heading) || isset($sub_heading)) { ?>
  <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-0">
	<div class="brade">
			<a href="<?php echo base_url();?>admin"><?php if(isset($this->phrases["home"])) echo $this->phrases["home"]; else echo "Home";?></a> 
			<?php if(isset($heading)) echo " >> ".$heading;?>
			<?php if(isset($sub_heading)) echo " >> ".$sub_heading;?>
			<?php if(isset($overallVehicles)) echo " (".$overallVehicles.")";?>
			<?php if(isset($language_name)) echo " >> ".ucwords($language_name);?>
		</div>
	</div>
   <?php } ?>
