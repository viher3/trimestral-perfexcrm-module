<?php

require(FCPATH . 'modules/trimestral/services/InvoiceProcessor.php');
require(FCPATH . 'modules/trimestral/services/ExpenseProcessor.php');
require(FCPATH . 'modules/trimestral/services/FolderZipper.php');
require(FCPATH . 'modules/trimestral/services/FolderDeletion.php');

/**
 * Class DownloadService
 */
class DownloadService
{
    private $trimestral_model;

    private $initDate;

    private $endDate;

    private $invoices_model;

    private $fileId;

    private $fullFilePath;

    /**
     * DownloadService constructor.
     * @param $trimestralModel
     * @param $invoicesModel
     * @param $initDate
     * @param $endDate
     */
    public function __construct($trimestralModel, $invoicesModel, $initDate, $endDate)
    {
        $this->trimestral_model = $trimestralModel;
        $this->invoices_model = $invoicesModel;
        $this->initDate = $initDate;
        $this->endDate = $endDate;
        $this->fileId = null;
        $this->fullFilePath = null;
    }

    /**
     * @throws Exception
     */
    public function execute()
    {
        try {
            // get data
            $expenses = $this->trimestral_model->getExpenses();
            $invoices = $this->trimestral_model->getInvoices();

            // Init folder
            $id = (new DateTime())->format('Ymdhis');
            $pathId = $this->generatePathId($id);
            $this->fileId = $id;
            $this->fullFilePath = $pathId . '.zip';
            mkdir($pathId, 0777, true);

            $expenseProcessor = new ExpenseProcessor();

            // Expenses
            foreach ($expenses as $expense) {
                $expenseProcessor->execute($expense['date'], $pathId, 'expenses', $expense['id']);
            }

            $invoicesProcessor = new InvoiceProcessor($this->invoices_model);

            // Invoices
            foreach ($invoices as $invoice) {
                $invoicesProcessor->execute($invoice, $pathId);
            }

            // Zip folder
            $zipFilename = $id . '.zip';
            $zipper = new FolderZipper($pathId);
            $zipper->compress($zipFilename);

            // Remove temp folder
            FolderDeletion::remove($pathId);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $fileId
     * @throws Exception
     */
    public function forceDownload($fileId)
    {
        $filepath = $this->generatePathId($fileId);
        $filepath = str_replace('exports/', '', $filepath) . '.zip';

        if(!file_exists($filepath)){
            throw new Exception(sprintf('File "%s" does not exists.', $filepath));
        }

        $fileContent = file_get_contents($filepath);
        force_download('trimestral_export.zip', $fileContent, 'application/zip');
    }

    /**
     * @return null
     */
    public function fullFilePath()
    {
        return $this->fullFilePath;
    }

    /**
     * @return null
     */
    public function fileId()
    {
        return $this->fileId;
    }

    /**
     * @param $id
     * @return string
     */
    public function generatePathId($id)
    {
        return Trimestral::EXPORT_PATH . 'exports/' . $id;
    }
}
