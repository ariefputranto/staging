  <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
		  <div class="inner-elements">
 
			<?php echo $this->session->flashdata('message');?>
				<?php $attributes = array('name'=>'add_edit_phrase','id'=>'add_edit_phrase');
                           echo form_open("settings/editPhrases/".$language_id,$attributes) ?>
                    
                           <?php if(isset($phrases) && count($phrases)>0) {
                              $i=1;
                              foreach($phrases as $p)
                              { ?>
							  <div class="col-lg-6">       <div class="form-group">
                           <label> <?php echo $p->text ?></label>
                           <input type="text" name="<?php if(isset($p->id)) echo $p->id;?>"  value="<?php if(isset($p->existing_text)) echo $p->existing_text;?>"/></div></div>

                           <?php } } else echo "No Data Available.";?>

              

                    <div class="form-group">	  <div class="col-lg-6">
						<button type="submit" class="btn btn-success"><?php echo "Update";?></button></div>
  </div>
                        <?php echo form_close(); ?>

			</div>

		  </div>
      </div>
    </div>
  </div>
</section>

