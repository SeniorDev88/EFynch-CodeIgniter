<link href="<?php echo base_url(); ?>js/fancybox/jquery.fancybox.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url(); ?>js/fancybox/jquery.fancybox.pack.js" type="text/javascript"></script>

<?php 
if (!empty($write_message) ){
?>
<script>
  $(document).ready(function() {
    $("a#write_message").fancybox({
      maxWidth    : 800,
      maxHeight   : 600,
      fitToView   : true,
      width       : '70%',
      height      : '70%',
      autoSize    : true,
      closeClick  : false,
      openEffect  : 'none',
      closeEffect : 'none'
    });
    $("a#write_message").trigger("click");
  }); 
</script>
<?php    
}
?>
<script>
setInterval(function(){

    checknewmessage();
}, 10000);

function scrollmessage(){
    $('#mesagelist').stop().animate({
      scrollTop: $('#mesagelist')[0].scrollHeight
    }, 800);
}

$(document).ready(function(){
    scrollmessage();

    $("#message").on("keyup",function(eve){
        if(eve.which == 13) {
            sendmessage();
        }
    });
});

function upFile(file1) {
    $(".msg-img-upload").hide();
    var fileExtension = ['jpeg', 'jpg', 'png', 'gif'];
    if ($.inArray($(file1).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
        alert("Permitted to upload  JPG/GIF/PNG image only");
        $("#fileup").val('');
        $(".msg-img-upload").show();
        return false;
    }
    var jobkey = $("#jobkey").val();
    var userkey = $("#userkey").val();
    var username = '<?php echo addslashes($this->session->userdata("userFirstname"))." ".addslashes($this->session->userdata("userLastname")); ?>';
    var imgp = '<?php echo $this->session->userdata("userImg"); ?>';
    var tm = '<?php echo date("m/d/Y") ?>';

    var file = file1.files[0];
    var formData = new FormData();
    formData.append('formData', file);
    formData.append('jobkey', jobkey);
    formData.append('userkey', userkey);
    formData.append('act', 'upload');

    /*var msg = $("#message").val();
        var html = '<div class="message-details temp clearfix message-right">'+
                            '<span class="profile-img-sm radius50"><img src="'+imgp+'"></span>'+
                            '<div class="message-content"><span data-target=".bs-example-modal-lg" data-toggle="modal" class="msg-img-upd"><img src="'+imgsrc+'"></span> <p>'+tm+'</p></div>'+
                        '</div>';
        $("#mesagelist").append(html); */

    $.ajax({
        type: "POST",
        url: "<?php echo base_url('sendmessage'); ?>",
        contentType: false,
        processData: false,
        dataType: "json",
        data: formData,
        success: function (data) {
            if( data.success ){
                checknewmessage();
                $("#fileup").val('');
            }
            $(".msg-img-upload").show();
        }
    });
}

function sendmessage(){
    var jobkey = $("#jobkey").val();
    var userkey = $("#userkey").val();
    var username = '<?php echo addslashes($this->session->userdata("userFirstname"))." ".addslashes($this->session->userdata("userLastname")); ?>';
    var imgp = '<?php echo $this->session->userdata("userImg"); ?>';
    var tm = '<?php echo date("m/d/Y") ?>';
    if($("#message").val() != ""){
        var msg = $("#message").val();
        var html = '<div class="message-details temp clearfix message-right">'+
                            '<span class="profile-img-sm radius50"><img src="'+imgp+'"></span>'+
                            '<div class="message-content">'+msg+' <p class="msg-date">'+tm+'</p></div>'+
                        '</div>';
        $("#mesagelist").append(html);
        $.post('<?php echo base_url('sendmessage'); ?>',{

               jobkey      : jobkey,
               userkey    : userkey,
               message    : msg,
               act : 'message'
            },function (data){
                if( data.success ){
                   // $("#lastkey").val(data.messagekey);
                   checknewmessage();
                }
            },"json");
        $("#message").val('');
    }

}

var xhr = new XMLHttpRequest();

function checknewmessage(){
    xhr.abort();
    var jobkey = $("#jobkey").val();
    var userkey = $("#userkey").val();
    var lastkey = $("#lastkey").val();
    xhr = $.post('<?php echo base_url('checknewmessage'); ?>',{
       jobkey      : jobkey,
       userkey    : userkey,
       lastkey    : lastkey
    },function (data){
        if( data.success ){
            if(data.isnew){
                $("#lastkey").val(data.lastkey);
                $(".temp").remove();
                var html = '';
                $.each(data.messages, function(i, item) {

                    html = '<div class="message-details '+item.msgclass+' clearfix">'+
                            '<span class="profile-img-sm radius50"><img src="'+item.profileimageurl+'"></span>'+
                            '<div class="message-content">';
                            if(item.hasdoc==1){
                                html += '<span data-target=".bs-example-modal-lg" onclick="attchsrc(\''+item.doc+'\')" data-toggle="modal" class="msg-img-upd"><img src="'+item.doc+'"></span>';
                            }else{
                                html += item.message;
                            }


                            html += ' <p class="msg-date">'+item.posteddate+'</p></div>'+
                        '</div>';
                });
                $("#mesagelist").append(html);
               // scrollmessage();


            }
        }
    },"json");

}

function getmessage(userkey){
    var jobkey = $("#jobkey").val();
    $("#userkey").val(userkey);
    $(".msgact").removeClass('active');
    $("#usermsg_"+userkey).addClass('active');

    //var lastkey = $("#lastkey").val();
    $.post('<?php echo base_url('getmessage'); ?>',{
       jobkey      : jobkey,
       userkey    : userkey
    },function (data){
        if( data.success ){

                $("#lastkey").val(data.lastkey);
                var html = '';
                $.each(data.messages, function(i, item) {

                    html += '<div class="message-details '+item.msgclass+' clearfix">'+
                            '<span class="profile-img-sm radius50"><img src="'+item.profileimageurl+'"></span>'+
                            '<div class="message-content">';
                            if(item.hasdoc==1){
                                html += '<span data-target=".bs-example-modal-lg" onclick="attchsrc(\''+item.doc+'\')" data-toggle="modal" class="msg-img-upd"><img src="'+item.doc+'"></span>';
                            }else{
                                html += item.message;
                            }


                            html += ' <p class="msg-date">'+item.posteddate+'</p></div>'+
                        '</div>';
                });
                $("#mesagelist").html(html);


        }
    },"json");

}

function attchsrc(imgs){
    $("#imagepath").attr('src',imgs);
}

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

	$(document).ready(function(){
    messageHeight();
  });
  $(window).resize(function(){
    messageHeight();
  });
  function messageHeight(){
		var dashHeight = $('.dashmenu').height();
    var headerHeight = $('.main-header').height();
    var winheight = $(window).height();
		if ($(window).width() > 1200) {
   		$('.newbg').css('top',dashHeight + headerHeight);
      $('.newbg').css('height',winheight - (dashHeight + headerHeight));
		}
		else {
		}
  }


</script>
<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title" id="myModalLabel">Image Preview</h4>
    </div>
    <div class="modal-body imgprew">
      <img id="imagepath" src=""/>
    </div>
  </div>
</div>
</div>

    <section class="right-side divheight">
        <?php 
        if (!empty($write_message) ){
        ?>
        <a class="fancybox.iframe" href="<?=$write_message?>" style="display: none;" id="write_message">video</a>
        <?php 
        }
        ?>
    	<div class="container-right pr0 pl0">
                        <?php
      $this->load->view('headers/dashboard_menu');
?>
        	<div class="col-sm-12 col-md-9 pl0 pr0">

                <div class="col-xs-12 pl30 pr30 p10-xs">
                	<div class="col-sm-12 p0 mb10"><h1>Messages</h1></div>  <div class="clear"></div>

                    <?php if(!empty($users)){
                        foreach ($users as $us){


                        ?>
                        <a onclick="getmessage('<?php echo $us['userkey']; ?>')" id="usermsg_<?php echo $us['userkey']; ?>" class="row-middle-wrap row-message-inbox msgact clearfix <?php if($us['userkey'] == $userkey){ ?> active<?php } ?> ">
                            <div class="profile-img-md radius50"><img src="<?php echo  $us['userimage']; ?>"></div>
                            <div class="col-xs-9">
                              <p class="msg-sender"><?php echo $us['firstname']." ".$us['lastname']; ?></p>
                              <p class="msg-last-message">
                                <?php echo ( strlen( $us['lastmessage'] ) > 100 ) ? substr(nl2br(stripslashes($us['lastmessage'])),0,100)."..." : nl2br(stripslashes($us['lastmessage'])); ?>

                            </p>
                            </div>
                        </a>
                    <?php
                        }
                    }else{
                        ?>
                        No conversations.
                        <?php
                    } ?>
                </div>
            </div>
            <div class="col-sm-3 pl0 pr0">
              <div class="mesg-show"><i></i></div>
            	<div class="newbg divheight pb10">
                	<div class="ash-bar"><p>MESSAGES</p></div>
                    <div id="mesagelist" class="mesg-content">
                    <?php
                    $lastkey = "";
                    if(!empty($messages)){
                        $cnt=1;
                        foreach($messages as $mes){
                            $lastkey  = $mes['messagekey'];

                        ?>
                        <div class="message-details clearfix <?php if($mes['completionnote'] == '1'){ ?>message-complete<? } ?> <?php if($mes['iscurrentuser'] == '1'){ ?>message-right<? }else{ ?>message-left<?php } ?>">
                            <?php if($mes['completionnote'] == '1' and $cnt == 1){ ?>
                            <span class="completion-note"><i class="glyphicon glyphicon-ok mr5"></i>Completion Note</span>
                            <div class="clear"></div>
                            <?
                                    $cnt++;
                                }
                            ?>
                            <span class="profile-img-sm radius50"><img src="<?php echo $mes['profileimageurl']; ?>"></span>
                            <div class="message-content">

                                <?php if($mes['hasdoc']){
                                    ?>
                                    <span class="msg-img-upd" data-toggle="modal" onclick="attchsrc('<?php echo $mes['doc']; ?>')" data-target=".bs-example-modal-lg">
                                        <img src="<?php echo $mes['doc']; ?>"/>
                                    </span>
                                <?php
                                }else{
                                    echo nl2br($mes['message']);
                                } ?>

                                <p class="msg-date"><?php echo $mes['posteddate']; ?></p></div>
                        </div>

                    <?php
                        }
                    } ?>
                </div>

                   <input type="hidden" name="lastkey" id="lastkey" value="<?php echo $lastkey; ?>">
                   <input type="hidden" name="jobkey" id="jobkey" value="<?php echo $jobkey; ?>">
                   <input type="hidden" name="userkey" id="userkey" value="<?php echo $userkey; ?>">
                    <div class="fixed-msg-details clearfix ">
                        <div class="fixed-message"><textarea name="message"  id="message"></textarea>
                          <div class="msg-img-upload" data-toggle="tooltip" data-placement="top" title="Send Image">
                            <input type="file" id="fileup" onchange="upFile(this)"/>
                          </div>
                          <input onclick="sendmessage()" type="button" value="Submit">
                        </div>
                    </div>

                </div>
            </div>

        <div class="clear"></div>
        </div>

    </section>

<!--Wrapper Starts from left menu-->
</div>
<script type="text/javascript">
  $(document).ready(function(){
    $(".mesg-show").click(function(){
      $(".newbg").toggleClass("newbgclik");
    })
  });
</script>
