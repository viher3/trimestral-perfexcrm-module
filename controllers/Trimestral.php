<?php

defined('BASEPATH') or exit('No direct script access allowed');

require(FCPATH . 'modules/trimestral/services/DownloadService.php');

class Trimestral extends AdminController
{
    const EXPORT_PATH = FCPATH . 'temp/modules/trimestral/';

    const UPLOADS_PATH = FCPATH . 'uploads';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('trimestral_model');
        $this->load->model('invoices_model');
    }

    public function index()
    {
        $this->load->view('index');
    }

    public function download()
    {
        $downloadService = new DownloadService(
            $this->trimestral_model,
            $this->invoices_model,
            $this->input->post('init_date'),
            $this->input->post('end_date')
        );
        $downloadService->execute();

        // TODO: compress folder
        die('--fin--');
    }


}
