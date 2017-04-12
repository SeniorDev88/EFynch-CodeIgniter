
    <section class="right-side divheight">
    	<div class="container-right pr0 pl0">
<?php
      $this->load->view('headers/dashboard_menu');
?>
<script src="<?php echo base_url(); ?>js/jquery-migrate-1.0.0.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>js/jqprint.js" type="text/javascript"></script>
<script>

function paymentdetails(contractkey){
  $('#myModal').modal('hide');
  $.post('<?php echo base_url('paymentdetails'); ?>',{
     contractkey    : contractkey
  },function (datares){
    
    $.each(datares, function (key, val) {
     
      if($("#id_"+key).length > 0) {
        $("#id_"+key).html(val);
      }
      $('#myModal').modal('show');
    });
    
  },"json");
}

function printdiv(){
  /*var divContents = $("#printdiv").html();
  var printWindow = window.open('', '', 'height=600,width=800');
  printWindow.document.write('<html><head><title>Receipts</title>');
  $("link[rel=stylesheet]").each(function() {
      var href = $(this).attr("href");
      if (href) {
          var media = $(this).attr("media") || "all";
          printWindow.document.write("<link type='text/css' rel='stylesheet' href='" + href + "' media='" + media + "'>")
      }
  });
  printWindow.document.write('</head><body ><div class="pr_receipt">');
  printWindow.document.write(divContents);
  printWindow.document.write('</div></body></html>');
  printWindow.document.close();
  setTimeout(function(){ printWindow.print(); }, 1000); */
  $("#printdiv").jqprint();
}
</script>

<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header myheader">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel" style="color:#f79224;">Receipt Details</h4>
      </div>
      <div class="modal-body receipt" id="printdiv">
        <h2>Job Info</h2>
        <div class="row pl15 pr15">
          <div class="col-xs-12 mb15">
            <label>Job Name</label>
            <p id="id_jobname"></p>
          </div>
        </div>
        <div class="row  pl15 pr15">
          <div class="col-xs-12 col-sm-6 mb15">
            <label>Job Completion Date</label>
            <p id="id_jobcompleteddate">04/12/2016</p>
          </div>
          <div class="col-xs-12 col-sm-6 mb15">
            <label>Bid Amount</label>
            <p id="id_amount">$100.00</p>
          </div>
        </div>
        <h2>Contractor Info</h2>
        <div class="row  pl15 pr15">
          <div class="col-xs-12 col-sm-6 mb15">
            <label>Contractor Name</label>
            <p id="id_contractorname">Fernando Perera</p>
          </div>
          <div class="col-xs-12 col-sm-6 mb15">
            <label>Phone</label>
            <p id="id_contractorphone">432-345-3456</p>
          </div>
          <div class="col-xs-12 col-sm-6 mb15">
            <label>Address</label>
            <p id="id_contractoraddress">123 Cold street, suite 300 <br> MD 21117</p>
          </div>
        </div>
        <h2>Billing Info</h2>
        <div class="row  pl15 pr15">
          <div class="col-xs-12 col-sm-6 mb15">
            <label>Card Details</label>
            <p id="id_carddetails">VISA : xxx14</p>
          </div>
          <div class="col-xs-12 col-sm-4 mb15">
            <label>Payment Date</label>
            <p id="id_paymentdate">04/12/2016</p>
          </div>
        </div>
        <div class="row  pl15 pr15">
          <div class="col-xs-12 col-sm-6 mb15">
            <label>Service Fee</label>
            <p id="id_servicefee">$12.00</p>
          </div>
          <div class="col-xs-12 col-sm-6 mb15">
            <label>Released Amount</label>
            <p id="id_paymentamount">$112.00</p>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="printdiv()"><span class="glyphicon glyphicon-print mr10"></span>Print</button>
      </div>
    </div>
  </div>
</div>

                <div class="col-xs-12">

                    <div class="col-sm-12 mt20">
                        <div class="btn-group btn-group-justified">
                        <a class="a-tabs-justify btn btn-default <?php if($type == "received"){ ?>active<?php } ?>" href="<?php echo base_url('accountbalance') ?>" ><?php if($this->session->userdata('usertype') == 'homeowner'){ ?>Debited<?php }else{ ?>Received<?php } ?> </a> <a href="<?php echo base_url('accountbalance/pending') ?>" class="a-tabs-justify btn btn-default <?php if($type == "pending"){ ?>active<?php } ?>" >Pending </a>
                        </div>
                    </div>

                	<div class="col-sm-12 mb10"><h1>Account Balance</h1></div>  <div class="clear"></div>

                    <div class="col-sm-12">
                        <?php if(!empty($payments)){
                                foreach($payments as $not){
                                    ?>
                                    <div class="row-middle-wrap row-receipt clearfix pr5-xs">
                                      <div class="col-xs-12 col-sm-7 col-md-6 col-lg-4">
                                        <div class="profile-img-md radius50"><img src="<?php echo $not['imgurl']; ?>"></div>
                                        <div class="FL">
                                        <h2><?php echo $not['firstname']." ".$not['lastname']; ?> </h2>
                                        <p><?php echo $not['jobname']; ?> </p>
                                        <h2>$<?php echo number_format($not['amount'], 2, '.', ''); ?> <?php /* ?><span>(Job No: 34567) </span><?php */ ?></h2>
                                        </div>
                                      </div>
                                      <?php if($type == "received" and $this->session->userdata('usertype') == 'homeowner'){ ?>
                                      <div class="col-xs-12 col-sm-5 col-md-4 col-lg-2 tc-xs FR FN-xs">
                                        <a class="view-receipt radius30p" data-toggle="modal" data-target="#myModal" onclick="paymentdetails('<?php echo $not['contractkey']; ?>')">View Receipt</a>
                                      </div>
                                      <?php } ?>
                                    </div>
                            <?php
                                }
                        }else{
                            ?>
                <div class="col-xs-12 mt10s"><p class="alert alert-danger mt10">No Account Balance Found.</p></div>
                        <?

                        } ?>


                    </div>


                </div>



        <div class="clear"></div>
        </div>
    <div class="braintree_logo">
      <p>Payments are Secure and Powered By Braintree</p>
      <img src="<?php echo base_url(); ?>img/braintreelogo.jpg"/>
    </div>
    </section>

<!--Wrapper Starts from left menu-->
</div>
