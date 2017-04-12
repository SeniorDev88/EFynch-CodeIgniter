
<script>
$(document).ready(function(){
    $('.targeted').hide();
    var wbLeft_id='';
    $(".showed").click(function(){
      wbLeft_id = $(this).attr('id').split('_');
      var myID = $("#tgt_"+wbLeft_id[1]);
      $(".targeted").slideUp();
      if($(myID).css("display") == "none"){
        $(myID).slideDown('slow');
      }
      else{
        $(myID).slideUp('slow');
      }
    });
});
</script>

  <section class="service-section">
    <div class="inside-banner"><p>How can EFynch help you today?</p></div>
    <div class="container">
      <div class="col-xs-12">
        <h3 class="info-subhead">EFynch and EFynch.com is here to help you with all you home improvement needs. We assist you in the location of pros and other help around the home including the following services:</h3>
      </div>
      <div class="row">
        <div class="col-xs-12">
          <div class="col-xs-12 col-sm-4 pl5 pr5"><p class="job-list-fl">Appliances</p></div>
          <div class="col-xs-12 col-sm-4 pl5 pr5"><p class="job-list-fl">Electrical</p></div>
          <div class="col-xs-12 col-sm-4 pl5 pr5"><p class="job-list-fl">Flooring</p></div>
          <div class="col-xs-12 col-sm-4 pl5 pr5"><p class="job-list-fl">Handy Man</p></div>
          <div class="col-xs-12 col-sm-4 pl5 pr5"><p class="job-list-fl">HVAC</p></div>
          <div class="col-xs-12 col-sm-4 pl5 pr5"><p class="job-list-fl">Lawn and Garden</p></div>
          <div class="col-xs-12 col-sm-4 pl5 pr5"><p class="job-list-fl">Moving</p></div>
          <div class="col-xs-12 col-sm-4 pl5 pr5"><p class="job-list-fl">Painting</p></div>
          <div class="col-xs-12 col-sm-4 pl5 pr5"><p class="job-list-fl">Roofing / Gutters</p></div>
          <div class="col-xs-12 col-sm-4 pl5 pr5"><p class="job-list-fl">Windows and Siding</p></div>
          <div class="col-xs-12 col-sm-4 pl5 pr5"><p class="job-list-fl">Other</p></div>
        </div>
      </div>
      <div class="row mt20">
        <div class="col-xs-12">
          <h3 class="info-subhead">Currently, the EFynch software and platform only service guests within the State of Maryland.</h3>
        </div>
        <div class="col-xs-12 col-sm-8 mb20">
            <div class="tip_wrap">
              <div class="col-xs-12 tipbar showed" id="show_1">
                <div class="col-xs-11 p0"><a class="tip_icons FL"></a><p class="tip_heading">What is EFynch?</p></div>
                <div class="col-xs-1 p0"><a class="tip_arrow"></a></div>
              </div>

              <div class="clear"></div>

              <div class="tipcnt pt10 targeted" id="tgt_1">
                <p class="text-content">EFynch is a platform which connects people to contractors. We assist in the sharing of information between everyone including the collection of bids, communications, and even provide a secure platform for payments. Our goal is to focus on making this transaction easy and reducing the stress of sales.</p>
              </div>
            </div>


            <div class="tip_wrap">
              <div class="col-xs-12 tipbar showed" id="show_2">
                <div class="col-xs-11 p0"><a class="tip_icons FL"></a><p class="tip_heading">Where is EFynch located?</p></div>
                <div class="col-xs-1 p0"><a class="tip_arrow"></a></div>
              </div>

              <div class="clear"></div>

              <div class="tipcnt pt10 targeted" id="tgt_2">
                <p class="text-content">We are located in Baltimore (City), Maryland!</p>
              </div>
            </div>

            <div class="tip_wrap">
              <div class="col-xs-12 tipbar showed" id="show_3">
                   <div class="col-xs-11 p0"><a class="tip_icons FL"></a><p class="tip_heading">Who can join EFynch?</p></div>
                   <div class="col-xs-1 p0"><a class="tip_arrow"></a></div>
              </div>

              <div class="clear"></div>

              <div class="tipcnt pt10 targeted" id="tgt_3">
                  <p class="text-content">Homeowners, contractors, service providers, laborers, and hungry college kids looking for extra money and willing to get their hands dirty.</p>
              </div>
            </div>

            <div class="tip_wrap">
              <div class="col-xs-12 tipbar showed" id="show_4">
                   <div class="col-xs-11 p0"><a class="tip_icons FL"></a><p class="tip_heading">What jobs can be completed using EFynch?</p></div>
                   <div class="col-xs-1 p0"><a class="tip_arrow"></a></div>
              </div>

              <div class="clear"></div>

              <div class="tipcnt pt10 targeted" id="tgt_4">
                    <p class="text-content">Any job related to the home: Lifting, carrying, plumbing, electrical, handyman, painting, designing, mowing, planting, watering, hammering, drilling, holding, or anything else you can think of. As long as the job is on your home, legal, and in the State of Maryland we are here.</p>
              </div>
            </div>

            <div class="tip_wrap">
              <div class="col-xs-12 tipbar showed" id="show_5">
                   <div class="col-xs-11 p0"><a class="tip_icons FL"></a><p class="tip_heading">What won’t we do?</p></div>
                   <div class="col-xs-1 p0"><a class="tip_arrow"></a></div>
              </div>

              <div class="clear"></div>

              <div class="tipcnt pt10 targeted" id="tgt_5">
                    <ul class="ml15">
                      <li class="text-content">Sell your personal information</li>
                      <li class="text-content">Show you “reviews” which are paid for</li>
                      <li class="text-content">Charge you to see information and ratings</li>
                      <li class="text-content">Charge you to post a bid</li>
                      <li class="text-content">Charge you to bid on a bid</li>
                      <li class="text-content">Charge a contractor to make a sales call to a homeowner</li>
                      <li class="text-content">Charge a homeowner to join and provide information which we will then (in turn) sell to a 3rd
party contractor who will put you on their sales call list.</li>
                    </ul>
                    <p class="text-content">Finally, we will never never never try to sell you services. This includes (but not limited to) piano tuning, dental cleaning, pet sitting, Obstetrics and Gynecology- We have some great members that are service providers- but in our experience, a service helping you find a toilet installer should probably be separate from the group helping you with a cavity :)</p>
              </div>
            </div>

            <div class="tip_wrap">
              <div class="col-xs-12 tipbar showed" id="show_6">
                   <div class="col-xs-11 p0"><a class="tip_icons FL"></a><p class="tip_heading">Speaking of charges- how much does it cost?</p></div>
                   <div class="col-xs-1 p0"><a class="tip_arrow"></a></div>
              </div>

              <div class="clear"></div>

              <div class="tipcnt pt10 targeted" id="tgt_6">
                    <p class="text-content">EFynch is completely free for homeowners and service pros to join. “It costs nothing to look” as one of our fellow e-commerce companies likes to say (credit to match.com).</p>

                    <p class="text-content">It also costs you nothing to post a project, view our tips and tricks, or follow us on Instagram and receive daily inspirations on why our homes are so awesome.</p>
              </div>
            </div>

            <div class="tip_wrap">
              <div class="col-xs-12 tipbar showed" id="show_7">
                   <div class="col-xs-11 p0"><a class="tip_icons FL"></a><p class="tip_heading">So how does EFynch make money?</p></div>
                   <div class="col-xs-1 p0"><a class="tip_arrow"></a></div>
              </div>

              <div class="clear"></div>

              <div class="tipcnt pt10 targeted" id="tgt_7">
                    <p class="text-content">We want to be 100% perfectly clear on this. WE DO NOT CHARGE HOMEOWNERS- we are here to help.</p>

                    <p class="text-content">EFynch’s business model is a platform, the same used by many software companies you already use like eBay and Uber. When you post an add, contractors can bid on these. Right now it is free for them to bid. The only time we collect money is when a project is signed, we charge the contractor a small fee (sometimes as low as 3%) for using our software. Other companies may charge as much as 20%- so we feel our fee is extremely modest especially considering how much we want to help you. The truth is we’re having fun doing this!</p>
              </div>
            </div>

            <div class="tip_wrap">
              <div class="col-xs-12 tipbar showed" id="show_8">
                   <div class="col-xs-11 p0"><a class="tip_icons FL"></a><p class="tip_heading">How is the EFynch payment portal secured?</p></div>
                   <div class="col-xs-1 p0"><a class="tip_arrow"></a></div>
              </div>

              <div class="clear"></div>

              <div class="tipcnt pt10 targeted" id="tgt_8">
                    <p class="text-content">We use Braintree, by Paypal. EFynch does not store any of your bank account information on our servers.</p>
              </div>
            </div>

            <div class="tip_wrap">
              <div class="col-xs-12 tipbar showed" id="show_9">
                   <div class="col-xs-11 p0"><a class="tip_icons FL"></a><p class="tip_heading">Why is EFynch so awesome?</p></div>
                   <div class="col-xs-1 p0"><a class="tip_arrow"></a></div>
              </div>

              <div class="clear"></div>

              <div class="tipcnt pt10 targeted" id="tgt_9">
                    <p class="text-content">Because like you, we are homeowners as well. It is tough sometimes and there are a lot of mixed messages being spread. Misinformation and high pressure sales are something we’ve all experienced and we think this model for business is obsolete! Competition and accurate information is our goal- efficiency is an added benefit. We hope you agree and welcome your feedback.</p>
              </div>
            </div>

            <div class="tip_wrap">
              <div class="col-xs-12 tipbar showed" id="show_10">
                   <div class="col-xs-11 p0"><a class="tip_icons FL"></a><p class="tip_heading">How did the EFynch bird get so darn cute?</p></div>
                   <div class="col-xs-1 p0"><a class="tip_arrow"></a></div>
              </div>

              <div class="clear"></div>

              <div class="tipcnt pt10 targeted" id="tgt_10">
                    <p class="text-content">Great genetics, proper grooming and an organic diet.</p>
              </div>
            </div>

            <div class="tip_wrap">
              <div class="col-xs-12 tipbar showed" id="show_11">
                   <div class="col-xs-11 p0"><a class="tip_icons FL"></a><p class="tip_heading">It is rumored your founder, Teris Pantazes, is the strongest man on earth, is this true?</p></div>
                   <div class="col-xs-1 p0"><a class="tip_arrow"></a></div>
              </div>

              <div class="clear"></div>

              <div class="tipcnt pt10 targeted" id="tgt_11">
                    <p class="text-content">We could only verify this with his 2 daughters, they said it is true!</p>
              </div>
            </div>
    </div>
    <div class="col-xs-12 col-sm-4 mt50">
      <img src="img/tip_phone_demo.png">
    </div>
      </div>
    </div>
  </section>

