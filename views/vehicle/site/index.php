<section class="apps">
  <div class="container">
	  <?php echo $this->session->flashdata('message');?>
    <div class="row">
     
    <div class="col-md-12">
	
	
    <div class="cont">
	<div class="roundOne"><img src="<?php echo base_url().$site_theme.'/';?>assets/system_design/images/caricon.png"/></div>
	<div class="roundTwo"><img src="<?php echo base_url().$site_theme.'/';?>assets/system_design/images/man.png"/></div>
   <h1> <?php if(isset($site_settigns->site_title)) echo $site_settigns->site_title; else echo "Point <span>2</span> Point Transfers";?> </h1>
   <p>AppKit is a landing page for your mobile application with a lot of benefits. The template supports three OS: iOS, Android and Windows Phone. There are various mobile devices, including tablets, provided for each OS.  </p>
   <div class="col-md-6 pl"> 
    <a href="<?php echo base_url();?>request-quote">
    <div class="btn btn-success reques"> <i class="fa fa-file-text"></i> <?php if(isset($this->phrases["get quote"])) echo $this->phrases["get quote"]; else echo "Get Quote";?></div>
    </a>
    </div>
    <div class="col-md-6 pr">  
        <a href="<?php echo base_url();?>booking">
        <div class="btn btn-danger book"><i class="fa fa-send"></i> <?php if(isset($this->phrases["book now"])) echo $this->phrases["book now"]; else echo "Book Now";?></div>
        </a>
    </div>
 
    </div>
    
    
    
    </div>  
      
    </div>
  </div>
</section>
