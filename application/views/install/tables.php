<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container">

    <ol class="breadcrumb">
        <li><i class="fa fa-share-alt"></i> MySQL Connection</li>
        <li><i class="fa fa-database"></i> Database Creation</li>
        <li class="active"><i class="fa fa-table"></i> Table Creation</li>
        <li><i class="fa fa-cog"></i> Site Settings</li>
        <li><i class="fa fa-flag-checkered"></i> Finish</li>
    </ol>

    <div class="panel panel-primary">

        <div class="panel-heading">

            <h3 class="panel-title">Table Creation</h3>

        </div>

        <div class="panel-body">

            <p>Your database <code><?= $_COOKIE['db_name']; ?></code> has been successfully created.</p>
            <p>Please click the "Next" button below to generate the tables for your Forums.</p>
            <br>

            <?php
            if (validation_errors())
            {
                echo '<div class="alert alert-danger" role="alert">' . validation_errors() .'</div>';
            }

            echo $this->messageci->display();

            ?>

            <?php echo form_open(); ?>

            <input type="hidden" id="db_name_cookie" name="db_name_cookie" value="<?= $_COOKIE['db_name'] ?>">

            <div class="form-group">

                <input type="submit" class="btn btn-primary" value="Next">

            </div>

        </div>

    </div>

</div>