
    <div class="login-right pattern-tools">
        <div class="col-xs-12"><a class="backtohome" href="<?php echo base_url(); ?>"></a></div>
        <div class="mt50 mt10-xs">
          <h1 class="login-h1">Home Improvement.<br class="hidden-xs"/> On your phone and on your side.</h1>
          <p class="login-p">EFynch.com is a Home Improvement Platform which helps to connect homeowners and contractors when projects and work is needed. We strive to do this efficiently as possible, making most services automated while protecting your privacy. There are no sales calls or high pressure tactics. Contractors can forego the strenuous and often expensive sales process, sharing the savings with homeowners. </p>
        </div>
        <div class="row mt20 mb20">
        	<div class="col-xs-12 tc">
            <a href="<?php echo base_url('primaryrole'); ?>" class="btn-normal btn-orange radius30p">Sign Up</a>
            <a href="<?php echo base_url('login'); ?>" class="btn-normal radius30p">Login</a>
          </div>
        </div>
        <div class="row">
        	<div class="col-sm-12 col-md-6"><div class="splash-bg"><img src="images/bg-splash.png" /></div></div>
            <div class="col-sm-12 col-md-6 hidden-xs"><a href="#" class="icon-store icon-istore"></a>
              <!--<a href="#" class="icon-store icon-astore"></a>-->
            </div>
        </div>
    </div>
    <div class="splashfoot">
        <div class="col-xs-12 clearfix clicksplash p10">
          <p>Download the App Now!</p>
          <i class="revealsplash"></i>
        </div>
        <div class="clear"></div>
        <div class="splash-reveal clearfix">
          <a href="#" class="icon-store icon-istore"></a>
          <!--<a href="#" class="icon-store icon-astore"></a>-->
        </div>
    </div>
<!--starts from header-->
</div>
<script type="text/javascript">
  $(document).ready(function(){
    $('.splash-reveal').hide();
    $('.clicksplash').click(function(){
      $('.revealsplash').toggleClass('fliparow');
      $('.splash-reveal').slideToggle();
    })
  });
</script>
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-96444822-1', 'auto');
ga('send', 'pageview');

</script>

</body>
</html>
