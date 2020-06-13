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
    }

    /**
     * @throws Exception
     */
    public function execute()
    {
        try{
            // get data
            $expenses = $this->trimestral_model->getExpenses();
            $invoices = $this->trimestral_model->getInvoices();

            // Init folder
            $id = (new DateTime())->format('Ymdhis');
            $pathId = Trimestral::EXPORT_PATH . 'exports/' . $id;
            mkdir($pathId, 0777, true);

            $expenseProcessor = new ExpenseProcessor();

            // Expenses
            foreach($expenses as $expense)
            {
                $expenseProcessor->execute($expense['date'], $pathId, 'expenses', $expense['id']);
            }

            $invoicesProcessor = new InvoiceProcessor($this->invoices_model);

            // Invoices
            foreach($invoices as $invoice)
            {
                $invoicesProcessor->execute($invoice, $pathId);
            }

            // Zip folder
            $zipper = new FolderZipper($pathId);
            $zipper->compress($id . '.zip');

            // Remove temp folder
            FolderDeletion::remove($pathId);

        }catch(Exception $e){
            throw $e;
        }
    }
}
