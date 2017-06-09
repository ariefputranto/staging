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
                           <label><?php if(isset($this->phrases["title"])) echo $this->phrases["title"]; else echo "Title";?></label>
                          <input type="text" name="page_title" id="page_title" value="<?php echo set_value('page_title', (isset($page_rec->page_title)) ? $page_rec->page_title : '');?>" required/>
							<?php echo form_error('page_title'); ?>
                        </div>
						
						<div class="form-group">
                           <label><?php if(isset($this->phrases["page_content"])) echo $this->phrases["page_content"]; else echo "Content";?></label>
                          <?php
						  if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
							{
								$val = $this->input->post( 'page_content' );
							}
							elseif( isset($page_rec) &&  count($page_rec) > 0)
							{
								$val = $page_rec->page_content;
							}
							else
							{
								$val = '';
							}
						  ?>
						  <textarea name="page_content" id="editor1" class="ckeditor" required><?php echo $val;?></textarea>
							<?php echo form_error('page_content'); ?>
                        </div>
						
						<?php /*?>
						<div class="form-group">
                           <label><?php if(isset($this->phrases["display as link"])) echo $this->phrases["display as link"]; else echo "Display as link?";?></label>                          
						   <?php			   
							if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
							{
								$val = $this->input->post( 'page_link_display' );
							}
							elseif( isset($page_rec) &&  count($page_rec) > 0)
							{
								$val = $page_rec->page_link_display;
							}
							else
							{
								$val = '';
							}
							$element = array();					
							$element['Yes'] = 'Yes';
							$element['No'] = 'No';						
							echo form_dropdown('page_link_display', $element, $val);
							?>
							<?php echo form_error('page_link_display'); ?>					
                        </div>
						<?php */?>
						<input type="hidden" name="page_link_display" value="Yes">
						
						<div class="form-group">
                           <label><?php if(isset($this->phrases["status"])) echo $this->phrases["status"]; else echo "Status";?></label>                          
						   <?php			   
							if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
							{
								$val = $this->input->post( 'page_status' );
							}
							elseif( isset($page_rec) &&  count($page_rec) > 0)
							{
								$val = $page_rec->page_status;
							}
							else
							{
								$val = '';
							}
							$element = array();					
							$element['Active'] = 'Active';
							$element['In-Active'] = 'In-Active';						
							echo form_dropdown('page_status', $element, $val);
							?>
							<?php echo form_error('page_status'); ?>					
                        </div>

                           <?php 
                              if(isset($page_rec->page_id) ) {?>
                           <input type="hidden" name="update_rec_id" value="<?php if(isset($page_rec->page_id)) echo $page_rec->page_id;?>"/>
                           <?php } else {
							   ?>
							   <input type="hidden" name="update_rec_id" value="0"/>
							   <?php
						   } ?>

                        <div class="form-group">	  
							<button type="submit" class="btn btn-success" name="submitbutt"><?php if(isset($lang_rec->id)) echo (isset($this->phrases["update"])) ? $this->phrases["update"] : "Update"; else echo (isset($this->phrases["add"])) ? $this->phrases["add"] : "Add";?></button>
							<a onclick="window.location.href = '<?php echo base_url().'settings/pages';?>';" class="btn btn-info" title="<?php if(isset($this->phrases["cancel"])) echo $this->phrases["cancel"]; else echo "Cancel";?>"> <?php if(isset($this->phrases["cancel"])) echo $this->phrases["cancel"]; else echo "Cancel";?></a>
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
                language_name: {
                          required: true      
                      }
                  },

				messages: {
					language_name: {
							  required: "<?php if(isset($this->phrases["please enter language name"])) echo $this->phrases["please enter language name"]; else echo "Please enter Language Name";?>."
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
