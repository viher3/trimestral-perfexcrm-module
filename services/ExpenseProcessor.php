<?php

require_once(FCPATH . 'modules/trimestral/language/DateLanguage.php');

/**
 * Class ExpenseProcessor
 */
class ExpenseProcessor
{
    /**
     * Proccess an expense
     */
    public function execute($date, $pathId, $type, $id)
    {
        $dateObj = DateTime::createFromFormat('Y-m-d', $date);

        // folder
        $folderTypeName = _l('trimestral_expensesFolderName');
        $months = DateLanguage::monthsArray();
        $monthName = $dateObj->format('m') . '. ' . $months[$dateObj->format('n')];
        $folder = $pathId . '/' . $dateObj->format('Y') . '/' . $monthName . '/' . $folderTypeName;

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        $uploadFolder = Trimestral::UPLOADS_PATH . '/' . $type . '/' . $id;

        if (!file_exists($uploadFolder)) return;

        $files = scandir($uploadFolder);

        foreach ($files as $file) {
            if (in_array($file, ['.', '..', 'index.html'])) continue;

            $filePath = $uploadFolder . '/' . $file;

            try {
                $destiny = $folder . '/' . $file;
                copy($filePath, $destiny);
            } catch (Exception $e) {
                throw $e;
            }
        }
    }
}
