<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div>
                            <h4 style="margin:0"><?= _l('title') ?></h4>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />

                        <p><?= _l('description') ?></p>
                        <br />

                        <?php echo form_open_multipart(admin_url('trimestral/download')) ?>
                            <div class="input-group">
                                <label for="init_date"><?= _l('init_date') ?></label>
                                <input name="init_date" id="init_date" type="date" class="form-control input-sm" required />
                            </div>
                            <br />
                            <div class="input-group">
                                <label for="end_date"><?= _l('end_date') ?></label>
                                <input name="end_date" id="end_date" type="date" class="form-control input-sm" required />
                            </div>

                            <br />
                            <p align="center">
                                <button type="submit" class="btn btn-info pull-left display-block mright5">
                                    <i class="fa fa-download"></i>&nbsp;&nbsp;<?= _l('download') ?>
                                </button>
                            </p>
                        <?php echo form_close(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
