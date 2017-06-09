  <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
		  <div class="inner-elements">
			<div class="col-md-6">
			<?php echo $this->session->flashdata('message');?>
				<?php 
				//print_r($page_rec);
				if(count($page_rec) > 0)
				$page_rec = $page_rec[0];
				$attributes = array('name'=>'add_language_form','id'=>'add_language_form');
                           echo form_open('',$attributes) ?>
                        <div class="form-group">
                           <label><?php if(isset($this->phrases["question"])) echo $this->phrases["question"]; else echo "Question";?>&nbsp;<font color="red">*</font></label>
                          <input type="text" name="faq_title" id="faq_title" value="<?php echo set_value('faq_title', (isset($page_rec->faq_title)) ? $page_rec->faq_title : '');?>" required/>
							<?php echo form_error('faq_title'); ?>
                        </div>
						
						<div class="form-group">
                           <label><?php if(isset($this->phrases["faq_content"])) echo $this->phrases["faq_content"]; else echo "Content";?>&nbsp;<font color="red">*</font></label>
                          <?php
						  if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
							{
								$val = $this->input->post( 'faq_content' );
							}
							elseif( isset($page_rec) &&  count($page_rec) > 0)
							{
								$val = $page_rec->faq_content;
							}
							else
							{
								$val = '';
							}
						  ?>
						  <textarea name="faq_content" id="editor1" class="ckeditor" required><?php echo $val;?></textarea>
							<?php echo form_error('faq_content'); ?>
                        </div>			
						
						
						<div class="form-group">
                           <label><?php if(isset($this->phrases["status"])) echo $this->phrases["status"]; else echo "Status";?>&nbsp;<font color="red">*</font></label>                          
						   <?php			   
							if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
							{
								$val = $this->input->post( 'faq_status' );
							}
							elseif( isset($page_rec) &&  count($page_rec) > 0)
							{
								$val = $page_rec->faq_status;
							}
							else
							{
								$val = '';
							}
							$element = array();					
							$element['Active'] = 'Active';
							$element['In-Active'] = 'In-Active';						
							echo form_dropdown('faq_status', $element, $val);
							?>
							<?php echo form_error('faq_status'); ?>					
                        </div>

                           <?php 
                              if(isset($page_rec->faq_id) ) {?>
                           <input type="hidden" name="update_rec_id" value="<?php if(isset($page_rec->faq_id)) echo $page_rec->faq_id;?>"/>
                           <?php } else {
							   ?>
							   <input type="hidden" name="update_rec_id" value="0"/>
							   <?php
						   } ?>

                        <div class="form-group">	  
							<button type="submit" class="btn btn-success" name="submitbutt"><?php if(isset($lang_rec->id)) echo (isset($this->phrases["update"])) ? $this->phrases["update"] : "Update"; else echo (isset($this->phrases["add"])) ? $this->phrases["add"] : "Add";?></button>
							<a onclick="window.location.href = '<?php echo base_url().'faqs/index';?>';" class="btn btn-info" title="<?php if(isset($this->phrases["cancel"])) echo $this->phrases["cancel"]; else echo "Cancel";?>"> <?php if(isset($this->phrases["cancel"])) echo $this->phrases["cancel"]; else echo "Cancel";?></a>
						</div>
                        
                        <?php echo form_close(); ?>
			</div>

		  </div>
      </div>
    </div>
  </div>
</section>

<script type= "text/javascript" src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery.min.js" ></script>
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery.validate.js" ></script>
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/additional-methods.js" ></script>
<script>
	(function($,W,D)
   {
      var JQUERY4U = {};

      JQUERY4U.UTIL =
      {
          setupFormValidation: function()
          {

			/* Form validation rules */
              $("#add_language_form").validate({
                  rules: {
                faq_title: {
                          required: true      
                      },
				faq_content: {
                          required: true      
                      }
                  },

				messages: {
					faq_title: {
							  required: "<?php if(isset($this->phrases["please enter faq title"])) echo $this->phrases["please enter faq title"]; else echo "Please enter FAQ title";?>."
						  },
					faq_content: {
							  required: "<?php if(isset($this->phrases["please enter faq content"])) echo $this->phrases["please enter faq content"]; else echo "Please enter FAQ content";?>."
						  }
				},

                  submitHandler: function(form) {
                      form.submit();
                  }
              });

          }
      }

      //when the dom has loaded setup form validation rules
      $(D).ready(function($) {
          JQUERY4U.UTIL.setupFormValidation();
      });
   
   })(jQuery, window, document);
   

   </script>
