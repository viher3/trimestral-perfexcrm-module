<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Trimestral extends AdminController
{
    const EXPORT_PATH = '/var/www/clientes.reaccionestudio.com/modules/trimestral/';

    const UPLOADS_PATH = '/var/www/clientes.reaccionestudio.com/uploads';

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
        $this->load->model('invoices_model');
    }

    public function index()
    {
        $this->load->view('index');
    }

    public function download()
    {
        // get data
        $expenses = $this->trimestral_model->getExpenses();
        $invoices = $this->trimestral_model->getInvoices();

        // Init folder
        $id = (new DateTime())->format('Ymdhis');
        $pathId = self::EXPORT_PATH . 'exports/' . $id;
        mkdir($pathId, 0777, true);

        // Expenses
        foreach($expenses as $expense)
        {
            $this->proccessExpense($expense['date'], $pathId, 'expenses', $expense['id']);
        }

        // Invoices
        foreach($invoices as $invoice)
        {
            $this->generatePdfInvoice($invoice, $pathId);
        }

        // TODO: compress folder
        die('--fin--');
    }

    /**
     * Generate invoice as PDF
     * @param $id
     */
    private function generatePdfInvoice($invoice, $pathId)
    {
        $invoiceId = $invoice['id'];

        $invoice        = $this->invoices_model->get($invoiceId);
        $invoice        = hooks()->apply_filters('before_admin_view_invoice_pdf', $invoice);
        $invoice_number = format_invoice_number($invoice->id);

        try {
            $pdf = invoice_pdf($invoice);
        } catch (Exception $e) {
            $message = $e->getMessage();
            echo $message;
            if (strpos($message, 'Unable to get the size of the image') !== false) {
                show_pdf_unable_to_get_image_size_error();
            }
            die;
        }

        $pdfFilename = mb_strtoupper(slug_it($invoice_number)) . '.pdf';
        $pdfData  = $pdf->Output($pdfFilename, 'S');

        // Save PDF
        $dateObj = DateTime::createFromFormat('Y-m-d', $invoice->date);

        // folder
        $folderTypeName = 'Ingresos';
        $monthName = $dateObj->format('m') . '. ' . self::MONTHS[$dateObj->format('n')];
        $folder = $pathId . '/' . $dateObj->format('Y') . '/' . $monthName . '/' . $folderTypeName;
        $fullPdfFilepath = $folder . '/' . $pdfFilename;

        try{

            if( ! file_exists($folder)){
                mkdir($folder, 0777, true);
            }

            file_put_contents($fullPdfFilepath, $pdfData);
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    /**
     * Proccess an expense
     */
    private function proccessExpense($date, $pathId, $type, $id)
    {
        $dateObj = DateTime::createFromFormat('Y-m-d', $date);

        // folder
        $folderTypeName = 'Gastos';
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