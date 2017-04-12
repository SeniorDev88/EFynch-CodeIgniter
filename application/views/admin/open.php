<!-- Content area -->

<div class="content">



    <!-- CKEditor default -->

    <div class="panel panel-flat">

        <div class="panel-heading">

            <div>

                <h5 class="panel-title">Answers From Different People</h5>

            </div>
        </div>







        <div class="panel-body">

            <?php if(count($content)>0) {?>
            <table class="table table-bordered table-hover datatable-highlight">

                <thead>

                <tr>

                    <th>Answers</th>

                </tr>

                </thead>

                <tbody>

                <?php foreach ($content as $val) {?>

                    <tr>

                        <td><?php echo ucfirst($val['answer'])?></td>

                        <!--<td><?php /*echo ucfirst(date('M d, Y H:i',strtotime($val['created'])))*/?></td>-->
                    </tr>

                <?php } ?>

                </tbody>

            </table>
            <?php }else{?>
                <h2>No record found</h2>
            <?php }?>

        </div>

    </div>