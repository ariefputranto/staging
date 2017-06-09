  <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
		  <div class="inner-elements">
			<div class="col-md-6">
			<?php echo $this->session->flashdata('message');?>
				<?php 
                  $attributes = array('name' => 'seo_settings_form', 'id' => 'seo_settings_form');
                  echo form_open_multipart('settings/seoSettings',$attributes) ;?> 

               <div class="form-group">                    
                  <label><?php if(isset($this->phrases["seo keywords"])) echo $this->phrases["seo keywords"]; else echo "SEO Keywords";?></label>
                  <textarea name="meta_keywords"><?php echo set_value('meta_keywords', 
                     (isset($seo_settings->meta_keywords)) ? 
                     $seo_settings->meta_keywords : '');?></textarea>  

               </div>

               <div class="form-group">                    
                  <label><?php if(isset($this->phrases["meta description"])) echo $this->phrases["meta description"]; else echo "Meta Description";?></label>
                  <textarea name="meta_description"><?php echo set_value('meta_description', 
                     (isset($seo_settings->meta_description)) ? 
                     $seo_settings->meta_description : '');?></textarea>

               </div>

               <div class="form-group">                    
                  <label><?php if(isset($this->phrases["google analytics"])) echo $this->phrases["google analytics"]; else echo "Google Analytics";?></label>
                  <textarea name="google_analytics"><?php echo set_value('google_analytics', 
                     (isset($seo_settings->google_analytics)) ? 
                     $seo_settings->google_analytics : '');?></textarea>

               </div>
               
               <input type="hidden" value="<?php  if(isset($seo_settings->id))
                  echo $seo_settings->id;
                  ?>"  name="update_record_id" />


               <input type="submit" value="Update" name="submit" class="btn btn-success" />
               <?php echo form_close();?>

			</div>

		  </div>
      </div>
    </div>
  </div>
</section>
