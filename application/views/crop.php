<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $title?></title>
        
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap.min.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap-theme.min.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/custom.css" />
        <script src="<?php echo base_url(); ?>js/jquery-1.11.0.min.js"></script>
        <script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
        <script src="<?php echo base_url();?>js/jquery.validate.js" type="text/javascript"></script>
	
	<!-- Jcrop Scripts / Styles-->
	<script src="<?php echo base_url(); ?>js/jcrop/js/jquery.Jcrop.min.js"></script>
	<script src="<?php echo base_url(); ?>js/jcrop/js/jquery.color.js"></script>
	<link rel="stylesheet" href="<?php echo base_url(); ?>js/jcrop/css/jquery.Jcrop.min.css" />
	
    </head>    
    <body>
	
    <style>	
	.prelod {
    background-attachment: scroll;
    background-clip: border-box;
    background-color: rgba(0, 0, 0, 0);
    background-image: url("<?php echo base_url(); ?>images/twppre.gif");
    background-origin: padding-box;
    background-position: 0 0;
    background-repeat: no-repeat;
    background-size: auto auto;
    display: block;
    height: 35px;
    width: 35px;
}
.cntrbtns{
	background: #fff none repeat scroll 0 0;
    border-top: 2px solid #e0e0e0;
    bottom: 0;
    left: 0;
    padding-top: 15px;
    position: fixed;
    width: 100%;
    z-index:999;
}
</style>
	
	<script>
	    function closeColorBox(){
		parent.$.fn.colorbox.close();
	    }
</script>	    
		<?php if($isshow == "show" || $imgfor != "estore"){?>  
		<script>			
	    jQuery(function($){
		 var jcrop_api;

		$('#target').Jcrop({
		    boxWidth: 375,
		    aspectRatio: <?php echo $imageSizes["width"];?>/<?php echo $imageSizes["height"];?>,
		    minSize: [ <?php echo $imageSizes["width"];?>, <?php echo $imageSizes["height"];?> ],
		    allowSelect: false,
		    setSelect: [ 0, 0, <?php echo $imageSizes["width"];?>, <?php echo $imageSizes["height"];?> ],
		    onChange:   attachCoords,
		    onSelect:   attachCoords,

		},function(){
		  jcrop_api = this;
		});
	    });
		</script>			    
	    <?php } ?>
		<script>	
	    function attachCoords(c){
	      $('#x1').val(c.x);
	      $('#y1').val(c.y);
	      $('#x2').val(c.x2);
	      $('#y2').val(c.y2);
	      $('#w').val(c.w);
	      $('#h').val(c.h);
	    };
		
		$(document).ready(function(){
		$("#preloaddiv").html('');
			$("#firstsub").click(function(){
				//alert('asad');
				$("#preloaddiv").html('<div class="preInnr">Please wait while we upload…<span class="prelod cuspreNW"></span></div>');
			});
		});
	    
	</script>
	
	<div class="UPerror"><?php echo $error;?></div>
	
	<?php if($view == 1){ ?>
	<?php echo form_open_multipart('');?>
	    <input type="hidden" name="fileupload" />
         <input type="hidden" name="imtype" value="<?php echo $type;?>"/>
	  <div class="panel panel-default"><button type="button" class="close cus-clse" data-dismiss="modal" aria-hidden="true" onClick="closeColorBox();"></button>
	      <div class="panel-heading">
		<h4>Upload a document</h4>
         
	      </div>
	      <div id="preloaddiv"></div>
	      <div class="panel-body">
		<div class="form-group">
		
		  <label for="exampleInputFile">Please upload a document or JPG/GIF/PNG image with minimum dimensions of <?php echo $imageSizes["width"];?>x<?php echo $imageSizes["height"];?>px (Width/Height)  with max file size 6MB.</label>		
		
		  <input type="file" id="exampleInputFile" name="myfile" />
		</div>
		
		<center><button class="btn btn-primary" type="submit" id="firstsub">Upload</button> <button class="btn btn-default" type="button" onClick="closeColorBox();">Cancel</button></center>
	      </div>
	  </div>
	</form>
	<?php } ?>
	
	<?php if($view == 2){ ?>
	
	    <form method="post">
		<input type="hidden" name="thumbnail" value="1" />	
		<input type="hidden" id="x1" name="x1" value="0"/>
		<input type="hidden" id="y1" name="y1" value="0"/>
		<input type="hidden" id="x2" name="x2" value="0"/>
		<input type="hidden" id="y2" name="y2" value="0"/>
		<input type="hidden" id="w" name="w" value="0"/>
		<input type="hidden" id="h" name="h" value="0"/>
		<input type="hidden" name="imagekey" value="<?php echo $imagekey; ?>" />
		
		<div class="panel panel-default">
		    <div class="panel-heading">
		      <h4>Upload an image and crop</h4>
		    </div>
		    
		    <div class="panel-body">
			  <img src="<?php echo $imageLink; ?>" id="target" alt="Crop" />
		    </div>
		    
		<center class="cntrbtns">
		<?php if($imgfor == "estore"){ ?>
		  <button class="btn btn-primary mr5" name="skip" value="1" type="submit" id="firstsub">Skip Crop</button>
		<?php } ?>		
		<button class="btn btn-primary" name="crop" value="1" type="submit"><span class=" glyphicon glyphicon-retweet"></span> Crop</button> 
	    <button class="btn btn-default" type="button" onClick="closeColorBox();"><span class="  glyphicon glyphicon-remove-circle"></span> Cancel</button>
		</center>
		    <br/>
		</div>
	    </form>
	
	<?php } ?>
	
	<?php if($view == 4){ ?>	
	    <form method="post">	
		<input type="hidden" name="imagekey" value="<?php echo $imagekey; ?>" />		
		<div class="panel panel-default">
		    <div class="panel-heading">
		      <h4>Rotate and Crop image</h4>
		    </div>
		    
		    <div class="panel-body">
			  <img src="<?php echo $imageLink; ?>" style="max-width:100%"/>
		    </div>
		    
		<center class="cntrbtns">
		<input type="hidden" name="left" value="1" />
		<button class="btn btn-primary" name="Rotate" value="Rotate" type="submit"> <span class="glyphicon glyphicon-repeat"></span> Rotate</button>
		<button class="btn btn-primary" name="Skiprotate" value="Crop" type="submit"> <span class=" glyphicon glyphicon-retweet"></span> Crop</button>
		<button class="btn btn-primary mr5" name="skip" value="1" type="submit" id="firstsub"><span class=" glyphicon glyphicon-upload"></span> Save</button>	
		</center>
		    <br/>
		</div>
	    </form>
	
	<?php } ?>	
	
	<?php if($view == 3){ ?>
	    	<script>
		    parent.profileImage('<?php echo $imagekey;?>','<?php echo $imagePath;?>','<?php echo $filetype;?>');
		    parent.$.fn.colorbox.close(); 
		</script>
	<?php } ?>
	
    </body>
</html>    