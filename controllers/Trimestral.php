<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Trimestral extends AdminController
{
    const EXPORT_PATH = '/var/www/vhosts/reaccionestudio.com/clientes.reaccionestudio.com/modules/trimestral/';

    const UPLOADS_PATH = '/var/www/vhosts/reaccionestudio.com/clientes.reaccionestudio.com/uploads';

    const MONTHS = [
        1 => 'Enero',
        2 => 'Febrero',
        3 => 'Marzo',
        4 => 'Abril',
        5 => 'Mayo',
        6 => 'Junio',
        7 => 'Julio',
        8 => 'Agosto',
        9 => 'Septiembre',
        10 => 'Octubre',
        11 => 'Noviembre',
        12 => 'Diciembre'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('trimestral_model');
    }

    public function index()
    {
        $this->load->view('index');
    }

    public function download()
    {
        $invoiceData = file_get_contents('https://clientes.reaccionestudio.com/admin/invoices/pdf/41');
        file_put_contents(self::EXPORT_PATH . '/test.pdf', $invoiceData);

        die('000');

        // get data
        $expenses = $this->trimestral_model->getExpenses();
        $invoices = $this->trimestral_model->getInvoices();

        // Init folder
        $id = (new DateTime())->format('Ymdhis');
        $pathId = self::EXPORT_PATH . 'exports/' . $id;
        mkdir($pathId, 0777, true);

        foreach($expenses as $expense)
        {
            $this->proccess($expense['date'], $pathId, 'expenses', $expense['id']);
        }

        /* TODO: las facturas se generan en PDF de forma dinámica, no se guardan en la carpeta uploads ...
        foreach($invoices as $invoice)
        {
            $this->proccess($invoice['date'], $pathId, 'invoices', $invoice['id']);
        }
        */

        die('--fin--');
    }

    private function proccess($date, $pathId, $type, $id)
    {
        $dateObj = DateTime::createFromFormat('Y-m-d', $date);

        // folder
        $folderTypeName = ($type == 'expenses') ? 'Gastos' : 'Ingresos';
        $monthName = $dateObj->format('m') . '. ' . self::MONTHS[$dateObj->format('n')];
        $folder = $pathId . '/' . $dateObj->format('Y') . '/' . $monthName . '/' . $folderTypeName;

        if( ! file_exists($folder)){
            mkdir($folder, 0777, true);
        }

        $uploadFolder = self::UPLOADS_PATH . '/' . $type . '/' . $id;

        if( ! file_exists($uploadFolder)) return;

        $files = scandir($uploadFolder);

        foreach($files as $file)
        {
            if(in_array($file, ['.', '..', 'index.html'])) continue;

            $filePath = $uploadFolder . '/' . $file;

            try
            {
                $destiny = $folder . '/' . $file;
                copy($filePath, $destiny);
            }
            catch(Exception $e)
            {
                echo $e->getMessage();
            }
        }
    }
}