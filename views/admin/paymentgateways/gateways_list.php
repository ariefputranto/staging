  <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
  <div class="inner-elements">
        <div class="col-md-12">
		<?php echo $this->session->flashdata('message');?>

  <table id="example" class="cell-border example" cellspacing="0" width="100%">
                   <thead>
                     <tr>
                          <th>#</th>
                          <th><?php if(isset($this->phrases["name"])) echo $this->phrases["name"]; else echo "Name";?></th>
                          <th><?php if(isset($this->phrases["is default"])) echo $this->phrases["is default"]; else echo "Is Default";?></th>
                          <th><?php if(isset($this->phrases["action"])) echo $this->phrases["action"]; else echo "Action";?></th>
                        </tr>
                  </thead>
                  <tfoot>
                    <tr>
                          <th>#</th>
                          <th><?php if(isset($this->phrases["name"])) echo $this->phrases["name"]; else echo "Name";?></th>
                          <th><?php if(isset($this->phrases["is default"])) echo $this->phrases["is default"]; else echo "Is Default";?></th>
                          <th><?php if(isset($this->phrases["action"])) echo $this->phrases["action"]; else echo "Action";?></th>
                        </tr>
                  </tfoot>
                      <tbody>

						<?php

							if(count($gateways) > 0) {
							  $i=1;
							  foreach($gateways as $row) {								
						   ?>
						<tr>
                          <td><?php echo $i++;?></td>
                          <td><?php echo $row->gateway_title;?></td>
                          <td><?php echo $row->is_default;?></td>
                          <td>
							<?php if($row->gateway_title == 'Cash') {
								echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';								
							} else { ?>
							<a class="btn btn-warning" type="button" href="<?php echo site_url(); ?>/admin/addfieldvalues/<?php echo $row->gateway_id?>/paymentgateway" ><i class="fa fa-edit"></i></a>
						   <?php } ?>
						   
						   <?php if($row->is_default == 1) {
							  echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
						   } else { ?>
						   <a class="btn btn-default" type="button" href="<?php echo site_url(); ?>/admin/makedefaultgateway/<?php echo $row->gateway_id?>"  title="Make Default"><i class="fa fa-anchor"></i></a>
						   <?php } ?>
						   
						   <?php if($row->gateway_status == 'active') {
							   ?>
							   <a class="btn btn-default" type="button" href="<?php echo site_url(); ?>/admin/deactivategateway/<?php echo $row->gateway_id?>"  title="Deactivate"><i class="fa fa-remove"></i></a>
							   <?php
						   } else {
							   ?>
							   <a class="btn btn-default" type="button" href="<?php echo site_url(); ?>/admin/activategateway/<?php echo $row->gateway_id?>"  title="Activate"><i class="fa fa-check"></i></a>
							   <?php
						   }?>

						</td>
                        </tr>

						<?php } } ?>

                  </tbody>
                    </table>
        </div>
         
         
         
      </div>
      </div>
    </div>
  </div>
</section>
