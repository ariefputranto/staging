
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
       
     
   <div class="modal-body clearfix">
       <div class="coupon_section">		
                <p class="firs-hed" id="title_txt"> </p>			
				 <div id="msg"><font color="red">Select code to copy</font></div>
				<div class="coupon_box">
				
				<span class="cupon-code cco" id="code_txt"></span>
								<button class="cupon-code cco1" id="cpy_code" onclick="copyToClipboard('#code_txt');">Copy Code</button>
			                    </div>				

			</div>
      <div align="center" id="expiry_date_txt"> </div>
				<div class="fancy_detail_section">
			 <span class="description_section" id="desc_txt"> </span>						
 			</div>
			
			      </div>
      
    </div>
  </div>
</div>
<div class="container">
<div class="row">
<div class="col-lg-10 col-lg-offset-1">
<div class="coupnLst off-li clearfix">
          <ul class="clearfix">

          <?php if(isset($offers) && count($offers) > 0) { 

                   foreach ($offers as $row) {

                    $img_txt = "Great Offer";
                    if($row->offer_type == "percentage")
                      $img_txt = $row->offer_type_val."%";
                    elseif($row->offer_type == "amount")
                      $img_txt = $site_settings->currency_symbol.$row->offer_type_val;
             ?>

            <li>
           <div class="flat-par"> <span> FLAT </br> <?php echo $img_txt;?> OFF</span></div>
            <div class="rC">
              <div class="contentCoupn">
			       <h1><?php echo $row->title;?></h1>
                <p class="more"><?php echo $row->description;?> </p>
              </div>
              <div class="contentBtn">
                <div class="btn couponButton">  <span><a href="#" data-toggle="modal" data-target="#myModal" onclick="getCodeModal('<?php echo $row->title;?>','<?php echo $row->code;?>','<?php echo $row->description;?>','<?php echo date('M d, Y', strtotime($row->expiry_date));?>');" > GET CODE</a></span> </div>
              </div>
              <div class="listOffer cl">
              <ul>
               <li><i class="flaticon-clock104"></i> Ending on: <?php echo date('M d, Y', strtotime($row->expiry_date));?> </li>
               </ul>
              </div>
            </div>

         </li>
        <?php } } else echo "Coming Soon...";?>

          </ul>
         
        </div></div>
</div>
</div>
 

 <script>
   function getCodeModal(title, code, desc, exp_date)
   {
      if(!title || !code || !desc)
        return;

      $('#title_txt').text(title);
      $('#code_txt').text(code);
      $('#desc_txt').text(desc);
      $('#expiry_date_txt').text("Offer Ended On: "+exp_date);

   }

   function copyToClipboard(element) {
      $(element).select();
      document.execCommand("copy");
	  $('#msg').html('<font color="green">Code Copied to clip board</font>');
    }

 </script>