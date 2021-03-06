  <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
  <div class="inner-elements">
        <div class="col-md-12">
		<?php echo $this->session->flashdata('message');?>
		<a onclick="window.location.href = '<?php echo base_url().'faqs/addeditfaq';?>';" class="btn btn-success" title="Add New"><?php if(isset($this->phrases["add new"])) echo $this->phrases["add new"]; else echo "Add New";?> <i class="fa fa-plus"></i></a>

		<br/><br/>
		<table id="example" class="cell-border example" cellspacing="0" width="100%">
               <thead>
                  <tr>
                     <th>#</th>
                     <th><?php if(isset($this->phrases["title"])) echo $this->phrases["title"]; else echo "Title";?></th>
					 <th><?php if(isset($this->phrases["created"])) echo $this->phrases["created"]; else echo "Created";?></th>
					 <th><?php if(isset($this->phrases["status"])) echo $this->phrases["status"]; else echo "Status";?></th>
                     <th><?php if(isset($this->phrases["action"])) echo $this->phrases["action"]; else echo "Action";?></th>
                  </tr>
               </thead>
               <tfoot>
                  <tr>
                     <th>#</th>
                     <th><?php if(isset($this->phrases["title name"])) echo $this->phrases["title"]; else echo "Title";?></th>
                     <th><?php if(isset($this->phrases["created"])) echo $this->phrases["created"]; else echo "Created";?></th>
					 <th><?php if(isset($this->phrases["status"])) echo $this->phrases["status"]; else echo "Status";?></th>
					 <th><?php if(isset($this->phrases["action"])) echo $this->phrases["action"]; else echo "Action";?></th>
                  </tr>
               </tfoot>
               <tbody>
                  <?php if(isset($pages) && count($pages)>0) { 						
                     $i=1;
                     foreach($pages as $page)
                     {

                     ?>
                  <tr>
                     <td><?php echo $i; $i++; ?></td>
                     <td><?php echo ucfirst($page->faq_title); ?></td>
					 <td><?php echo date('d M,Y', strtotime($page->faq_created)); ?></td>
					 <td><?php echo $page->faq_status; ?></td>
                     <td>                        
                        <?php 

							$edit_txt = (isset($this->phrases["edit faq"])) ? $this->phrases["edit faq"] : "Edit FAQ";

							echo anchor('faqs/addeditfaq/'.$page->faq_id,'<i class="fa fa-edit"> '.$edit_txt.'</i>','class="btn btn-info act-btn"'); ?> &nbsp;

                        <a class="btn btn-danger act-btn" data-toggle="modal" data-target="#delRecModal" onclick="deleteMessage(<?php echo $page->faq_id;?>)"><i class="fa fa-trash"></i></a>  	
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


<!-- modal fade -->
<div class="modal fade" id="delRecModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php if(isset($this->phrases["close"])) echo $this->phrases["close"]; else echo "Close";?></span></button>
            <h4 class="modal-title" id="myModalLabel"><?php if(isset($this->phrases["delete record"])) echo $this->phrases["delete record"]; else echo "Delete Record";?></h4>
         </div>
         <div class="modal-body">
           <?php if(isset($this->phrases["are you sure to delete the record"])) echo $this->phrases["are you sure to delete the record"]; else echo "Are you sure to delete the Record";?>?
         </div>
         <div class="modal-footer">
            <a type="button" class="btn btn-default modal-btn" id="delete_no" href=""><?php if(isset($this->phrases["yes"])) echo $this->phrases["yes"]; else echo "Yes";?></a>
            <a type="button" class="btn btn-default modal-btn" data-dismiss="modal"><?php if(isset($this->phrases["no"])) echo $this->phrases["no"]; else echo "No";?></a>
         </div>
      </div>
   </div>
</div>



<script>
/****** Delete Message ******/
   function deleteMessage(x){

	var str = "<?php echo base_url();?>faqs/index/delete/"+x;
	document.getElementById("delete_no").setAttribute("href", str);

   }
   
</script>
