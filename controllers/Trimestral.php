<?php

defined('BASEPATH') or exit('No direct script access allowed');

require(FCPATH . 'modules/trimestral/services/DownloadService.php');

/**
 * Class Trimestral
 */
class Trimestral extends AdminController
{
    const EXPORT_PATH = FCPATH . 'temp/modules/trimestral/';

    const UPLOADS_PATH = FCPATH . 'uploads';

    /**
     * Trimestral constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('trimestral_model');
        $this->load->model('invoices_model');
        $this->load->helper('download');
    }

    public function index()
    {
        $this->load->view('index');
    }

    /**
     * @throws Exception
     */
    public function download()
    {
        try{
            $downloadService = new DownloadService(
                $this->trimestral_model,
                $this->invoices_model,
                $this->input->post('init_date'),
                $this->input->post('end_date')
            );
            $downloadService->execute();
            $downloadService->forceDownload($downloadService->fileId());
        }catch(Exception $e){
            throw $e;
        }
    }
}
