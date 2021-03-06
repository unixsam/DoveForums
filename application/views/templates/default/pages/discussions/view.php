<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    {breadcrumbs}

<div class="well well-sm">

    <strong>{discussion_name}</strong>

</div>

<div class="btn-toolbar pagination-toolbar" role="toolbar">

    <!-- Check the user is logged in -->
    <?php if ($this->ion_auth->logged_in() === TRUE) { ?>

    <div class="btn-group pull-right">

        {reply_button}

    </div>

    <div class="btn-group pull-right">

        {new_discussion_button}

    </div>

    <?php } ?>

</div>

<div class="panel panel-danger" id="{comment_id}">

    <div class="panel-heading">

        <div class="media-left">{avatar}</div>

        <div class="media-body">

            <p>{created_by}<br><span class="label label-success">{points} Xp</span></p>

        </div>

    </div>

    <div class="panel-body">

        <div class="media">

            <div class="media-body">

                <div class="row">

                    <div class="col-md-6">

                        <p class="text-muted">{created_date}</p>

                    </div>

                    <div class="col-md-6">

                        <p class="pull-right"><strong>{comment_id_link}</strong></p>

                    </div>

                </div>

                <hr />

                <p>{body}</p>

            </div>

        </div>

    </div>

    <div class="panel-footer">

        <div class="btn-toolbar">

            <div class="btn-group">

                {pm_button}
                {thumbs_up_button}

            </div>

            <!-- Check the user is logged in -->
            <?php if ($this->ion_auth->logged_in() === TRUE) { ?>

            <div class="btn-group pull-right">

                {delete_discussion_button}
                {edit_discussion_button}
                {report_button}

            </div>

            <?php } ?>

        </div>

    </div>

</div>

<?php if($has_comments) { ?>

    {comments}

    <div class="panel panel-default" id="{comment_id}">

        <div class="panel-heading">

            <div class="media-left">{avatar}</div>

            <div class="media-body">

                <p>{created_by}<br><span class="label label-success">{points} Xp</span></p>

            </div>

        </div>

        <div class="panel-body">

            <div class="media">

                <div class="media-body">

                    <div class="row">

                        <div class="col-md-6">

                            <p class="text-muted">{created_date}</p>

                        </div>

                        <div class="col-md-6">

                            <p class="pull-right"><strong>{comment_id_link}</strong></p>

                        </div>

                    </div>

                    <hr />

                    <p>{body}</p>

                </div>

            </div>

        </div>

        <div class="panel-footer">

            <div class="btn-toolbar">

                <div class="btn-group">

                    {pm_button}
                    {thumbs_up_button}

                </div>

                <!-- Check the user is logged in -->
                <?php if ($this->ion_auth->logged_in() === TRUE ) { ?>

                <div class="btn-group pull-right">

                    {delete_comment_button}
                    {edit_comment_button}
                    {report_button}

                </div>

                <?php } ?>

            </div>

        </div>

    </div>

    {/comments}

<?php } else { ?>

    <div class="alert alert-info"><p><?=lang('txt_no_comments');?></p></div>

<?php } ?>

<div class="btn-toolbar pagination-toolbar" role="toolbar">

    <!-- Check the user is logged in -->
    <?php if ($this->ion_auth->logged_in() === TRUE ) { ?>

        <div class="btn-group pull-right">

            {reply_button}

        </div>

        <div class="btn-group pull-right">

            {new_discussion_button}

        </div>

    <?php } ?>

</div>

<div class="text-center">

    {pagination} <br />
    <p class="text-muted small"><?=lang('txt_displaying');?> {result_start} <?=lang('txt_to');?> {result_end} <?=lang('txt_of');?> {total}</p>

</div>

<!-- Check if the user is logged in -->
<?php if($this->ion_auth->logged_in() === TRUE) { ?>

    <div class="panel panel-warning" id="quick_reply">

        <div class="panel-heading">

            <p class="panel-title"><?=lang('txt_quick_reply');?></p>

        </div>

        <div class="panel-body">

            <div id="new_comment">

                {form_open}

                <div class="form-group <?php if(form_error('comment')){echo 'has-error';} ?>">

                    {comment_field}
                    {comment_error}

                </div>

                <div class="form-group">

                    {discussion_id_field_hidden}
                    {category_id_field_hidden}
                    {post_comment_button}

                </div>

                {form_close}

            </div>

        </div>

    </div>

<?php } ?>