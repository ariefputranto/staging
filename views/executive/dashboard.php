  <div class="col-lg-10 col-md-10 col-sm-12 padding-lr">
    <div class="body-content">
 
      <div class="admin-body">   
	  <div class="col-lg-12"><h1><?php if(isset($this->phrases["welcome"])) echo $this->phrases["welcome"]; else echo "Welcome";?>, <?php if(isset($this->phrases["executive"])) echo $this->phrases["executive"]; else echo "Executive";?></h1></div>
	  <?php echo $this->session->flashdata('message');?> 
        <div class="av">
          <ul>
            <li class="green"><a href="<?php echo base_url();?>executive/viewBookings"> <i class="fa fa-video-camera"></i> </a> </li>
            
			<li class="gray"><a href="<?php echo base_url();?>executive/profile"> <i class="fa fa-user"></i></a> </li>
            
            <li class="orang"><a href="<?php echo base_url();?>auth/change_password"> <i class="fa fa-edit"></i> </a> </li>
            
			

          </ul>
        </div>

		<?php $this->load->view('admin/calendar');?>

      </div>
    </div>
  </div>
</section>
