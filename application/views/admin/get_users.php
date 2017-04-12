<option value="all">All</option>
<?php foreach ($cities as $v) {?>
    <option value="<?php echo $v['userid'];?>"><?php echo $v['firstname'].' '.$v['lastname'];?></option>
<?php }?>