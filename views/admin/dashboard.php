  <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
 
      <div class="admin-body">   
	  <div class="col-lg-12"><h1><?php if(isset($this->phrases["welcome"])) echo $this->phrases["welcome"]; else echo "Welcome";?>, <?php if(isset($this->phrases["admin"])) echo $this->phrases["admin"]; else echo "Admin";?></h1></div>
	  <?php echo $this->session->flashdata('message');?> 
        <div class="av">
          <ul>
            <li class="green"><a href="<?php echo base_url();?>admin/viewBookings"> <i class="fa fa-video-camera"></i> </a> </li>
            <li class="blue"><a href="<?php echo base_url();?>auth/users"> <i class="fa fa-users"></i> </a> </li>
            <li class="pink"><a href="<?php echo base_url();?>settings/locations/list"> <i class="fa fa-map-marker"></i> </a> </li>
            <li class="orang"><a href="<?php echo base_url();?>auth/change_password"> <i class="fa fa-edit"></i> </a> </li>
            <li class="dark-orange"><a href="<?php echo base_url();?>settings/siteSettings"><i class="fa fa-cogs"></i></a> </li>
			<li class="gray"><a href="<?php echo base_url();?>admin/siteBackup"> <i class="fa fa-download"></i></a> </li>

          </ul>
        </div>

		<?php $this->load->view('admin/calendar');?>

      </div>
    </div>
  </div>
</section>
