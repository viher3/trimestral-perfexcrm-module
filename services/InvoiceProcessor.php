<?php

require_once(FCPATH . 'modules/trimestral/language/DateLanguage.php');

/**
 * Class InvoiceProcessor
 */
class InvoiceProcessor
{
    private $invoices_model;

    /**
     * InvoiceProcessor constructor.
     */
    public function __construct($invoicesModel)
    {
        $this->invoices_model = $invoicesModel;
    }

    /**
     * Generate invoice as PDF
     * @param $id
     */
    public function execute($invoice, $pathId)
    {
        $invoiceId = $invoice['id'];

        $invoice = $this->invoices_model->get($invoiceId);
        $invoice = hooks()->apply_filters('before_admin_view_invoice_pdf', $invoice);
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
        $pdfData = $pdf->Output($pdfFilename, 'S');

        // Save PDF
        $dateObj = DateTime::createFromFormat('Y-m-d', $invoice->date);

        // folder
        $folderTypeName = _l('invoicesFolderName');
        $months = DateLanguage::monthsArray();
        $monthName = $dateObj->format('m') . '. ' . $months[$dateObj->format('n')];
        $folder = $pathId . '/' . $dateObj->format('Y') . '/' . $monthName . '/' . $folderTypeName;
        $fullPdfFilepath = $folder . '/' . $pdfFilename;

        try {

            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }

            file_put_contents($fullPdfFilepath, $pdfData);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
