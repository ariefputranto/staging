<?php if(isset($active_class) && $active_class == "home") { ?>

<?php } else {?>


<div class="container">
<div class="row">
<div class="col-lg-12">
<div class="inner-hed arbreadcumb">
<p style="text-align:left"> <a href="<?php echo base_url();?>"> Home </a> 
	<?php if(isset($title)) { ?>
	&nbsp; <i class="fa fa-angle-right"></i> &nbsp; <?php echo $title;?>  
	<?php } ?>
</p>
</div>
</div>
</div>
</div>
<?php
$search_banner = "background: rgba(0, 0, 0, 0) url('".base_url()."assets/images/banner.jpg') repeat scroll center center / cover ;";
if(!empty($site_settings->search_banner)) 
$search_banner = "background: rgba(0, 0, 0, 0) url('".base_url().$site_theme."/assets/system_design/images/".$site_settings->search_banner."') repeat scroll center center / cover ;";
?>
<section class="banner inner-banner" style="<?php echo $search_banner;?>">
<div class="container">
<div class="row">
  <?php $this->load->view($site_theme.'/'.'site/common/search-form');?>
</div>
</div>
</section>
<?php } ?>