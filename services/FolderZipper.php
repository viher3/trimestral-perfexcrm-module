<?php

/**
 * Class FolderZipper
 */
class FolderZipper
{
    /**
     * FolderZipper constructor.
     */
    public function __construct($folderPath)
    {
        $this->folderPath = $folderPath;
    }

    /**
     * @param $filename
     * @throws Exception
     */
    public function compress($filename)
    {
        $zip = new ZipArchive();
        $zipFilepath = Trimestral::EXPORT_PATH . $filename;

        if ($zip->open($zipFilepath, ZipArchive::CREATE) !== TRUE) {
            throw new Exception("cannot open <$filename>\n");
        }

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->folderPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            // Skip directories (they would be added automatically)
            if (!$file->isDir()) {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($this->folderPath) + 1);

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();
    }
}
