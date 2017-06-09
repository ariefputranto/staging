<?php
$search_banner = "background: rgba(0, 0, 0, 0) url('../images/banner.jpg') repeat scroll center center / cover ;";
if(!empty($site_settings->search_banner)) 
$search_banner = "background: rgba(0, 0, 0, 0) url('".$site_theme."/assets/system_design/images/".$site_settings->search_banner."') repeat scroll center center / cover ;";
?>

<section class="banner inner-banner" style="<?php echo $search_banner;?>">
<div class="container">
<div class="row">
<?php $this->load->view($site_theme . '/site/common/search-form');?>
</div>
</div>
</section>