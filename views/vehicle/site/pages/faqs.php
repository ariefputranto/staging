<section class="apps">
  <div class="container">
	  <?php echo $this->session->flashdata('message');?>
    <div class="row">
     
    <div class="col-md-12">
	
<div class="cont dynamic-pages">
<div class="breacrumb">
      	<h1>FAQ'S</h1>
      	<small>Home     <i class="fa fa-angle-right"></i> FAQ'S</small>
      </div>
	
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
  <?php 
  $i = 0;
  foreach($faqs as $faq) { ?>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingOne-<?php echo $faq->faq_id?>">
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne-<?php echo $faq->faq_id?>" aria-expanded="true" aria-controls="collapseOne-<?php echo $faq->faq_id?>"><?php echo $faq->faq_title;?></a>
      </h4>
    </div>
    <div id="collapseOne-<?php echo $faq->faq_id?>" class="panel-collapse collapse<?php if($i == 0) {?> in<?php } ?>" role="tabpanel" aria-labelledby="headingOne-<?php echo $faq->faq_id?>">
      <div class="panel-body"><?php echo $faq->faq_content;?></div>
    </div>
  </div>
  <?php 
  $i++;
  } ?>
  
  
</div>
 
    </div>
    
    
    
    </div>  
      
    </div>
  </div>
</section>
