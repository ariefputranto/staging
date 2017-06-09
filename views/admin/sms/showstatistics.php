  <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
      <div class="inner-elements">
        <div class="col-md-12">
         <div class="panel">
            <div class="panel-heading ele-hea"> <?php if(isset($this->phrases["field values for"])) echo $this->phrases["field values for"]; else echo "Field values for";?> <?php echo (count($fields) > 0) ? $fields[0]->gateway_title : '';?> <i class="fa fa-plus"></i> </div>
            <div class="panel-body paddig">
			  <?php  
			  $attributes = array('name' => 'tokenform', 'id' => 'tokenform','method' => 'post');
			  echo form_open( '',$attributes);
			  ?>
			  <div class="inner-pages-forms">
               <div class="col-md-12">               
               <?php
			   if(count($fields) > 0) {
			   foreach( $fields as $row) { ?>
				   <div class="form-group">
				   <label> <?php echo $row->field_name;?> </label><?php echo (isset($row->gateway_field_value)) ? $row->gateway_field_value : '';?>			   
				   </div>  
			   <?php } }
				else {
					echo 'No fields for this gateway. Click <a href="'.base_url().'admin/addsmsfields/'.$gid.'">here</a> to add';
				}
			   ?>
				<div class="form-group">
				<label>Balance</label><?php echo $balance;?>
				</div>
				<?php
				//print_r($otherdetails);
			   if(isset($otherdetails)) {
				   ?>
				   <div class="form-group">
					<label>Other Details</label>
				</div>
				   <?php
				   if($gateway == 'Plivo') {
					   foreach( $otherdetails as $key => $val) { ?>
						   <div class="form-group">
						   <label> <?php echo $key;?> </label><?php echo $val;?>			   
						   </div>  
					   <?php } 
				   }
			   }				
			   ?>				
               </div>
               <div class="buttos">
               <button type="button" class="btn btn-info" onclick="window.location='<?php echo base_url();?>admin/sms_settings'"><i class="fa fa-arrow-left"></i> <?php if(isset($this->phrases["back"])) echo $this->phrases["back"]; else echo "Back";?></button>
               </div>               
              </div>
			 
			  <?php echo form_close();?>			  
            </div>
          </div>                               
            
        </div>      
         
         
      </div>
    </div>
  </div>
</section>
