<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div>
                            <h4 style="margin:0">Descargar gastos e ingresos</h4>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />

                        <p>Selecciona un rango de fechas para descargar todas las facturas y gastos registrados organizados por años y meses.</p>
                        <br />

                        <div class="input-group">
                            <label for="init_date">Fecha de inicio</label>
                            <input name="init_date" id="init_date" type="date" class="form-control input-sm" />
                        </div>
                        <br />
                        <div class="input-group">
                            <label for="end_date">Fecha de fin</label>
                            <input name="end_date" id="end_date" type="date" class="form-control input-sm" />
                        </div>

                        <br />
                        <p align="center">
                            <a class="btn btn-info pull-left display-block mright5" href="<?= admin_url('trimestral/download') ?>">
                                <i class="fa fa-download"></i>&nbsp;&nbsp;Descargar archivos
                            </a>
                        </p>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php init_tail(); ?>
