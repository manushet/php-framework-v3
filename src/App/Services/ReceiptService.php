<?php

namespace App\Services;

use App\config\Paths;
use Framework\Database;
use Framework\Exceptions\ValidationException;

class ReceiptService
{
    public function __construct(private Database $db)
    {
    }

    public function validateFile(?array $file): void
    {
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            throw new ValidationException([
                'receipt' => ['Failed to upload file']
            ]);
        }

        $maxFileSizeMB = 3 * 1024 * 1024;

        if ($file['size'] > $maxFileSizeMB) {
            throw new ValidationException([
                'receipt' => ['File upload is too large']
            ]);
        }

        $originalFileName = $file['name'];

        if (!preg_match('/^[A-Za-z0-9\s\._-]+$/', $originalFileName)) {
            throw new ValidationException([
                'receipt' => ['Invalid file name']
            ]);
        }

        $clientMimeType = $file['type'];

        $allowedMimeTypes = ['image/jpeg', 'image/png', 'application/pdf'];

        if (!in_array($clientMimeType, $allowedMimeTypes)) {
            throw new ValidationException([
                'receipt' => ['Invalid file type']
            ]);
        }
    }

    public function uploadFile(array $file, int $transactionId): void
    {
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);

        $newFilename = bin2hex(random_bytes(16)) . '.' . $fileExtension;

        $uploadPath = Paths::STORAGE_UPLOADS . '/' . $newFilename;

        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            throw new ValidationException([
                'receipt' => ['Failed to upload file']
            ]);
        }

        $this->db->query(
            "INSERT INTO receipts(transaction_id, original_filename, storage_filename, media_type) VALUES(:tid, :ofile, :sfile, :ftype)",
            [
                'tid' => $transactionId,
                'ofile' => $file['name'],
                'sfile' => $newFilename,
                'ftype' => $file['type']
            ]
        );
    }

    public function getReceipt(int $id): object|false
    {
        $receipt = $this->db->query(
            "SELECT * FROM receipts WHERE id=:id;",
            ['id' => $id]
        )->findOne();

        return $receipt;
    }

    public function readReceipt(object $receipt)
    {
        $filePath = Paths::STORAGE_UPLOADS . '/' . $receipt->storage_filename;

        if (!file_exists($filePath)) {
            redirectTo('/');
        }

        header("Content-Disposition: inline;filename:{$receipt->original_filename}");
        header("Content-Type: {$receipt->media_type}");

        readfile($filePath);
    }

    public function deleteReceipt($receipt): void
    {
        $filePath = Paths::STORAGE_UPLOADS . '/' . $receipt->storage_filename;

        unlink($filePath);

        $this->db->query(
            "DELETE FROM receipts WHERE id=:id;",
            [
                'id' => $receipt->id
            ]
        );
    }
}