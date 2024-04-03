<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\ReceiptService;
use App\Services\{TransactionService};

class ReceiptController
{
    public function __construct(
        private TemplateEngine $templateEngine,
        private TransactionService $transactionService,
        private ReceiptService $receiptService
    ) {
    }

    public function uploadView(array $params)
    {
        $transaction = $this->transactionService->getUserTransaction((int)$params['transaction']);

        if (!$transaction) {
            redirectTo("/");
        }

        echo $this->templateEngine->render("receipts/create.php");
    }

    public function upload(array $params)
    {
        $transaction = $this->transactionService->getUserTransaction((int)$params['transaction']);

        if (!$transaction) {
            redirectTo("/");
        }

        $receiptFile = $_FILES['receipt'] ?? null;

        $this->receiptService->validateFile($receiptFile);

        $this->receiptService->uploadFile($receiptFile, (int)$transaction->id);

        redirectTo("/");
    }

    public function download(array $params)
    {
        $transaction = $this->transactionService->getUserTransaction((int)$params['transaction']);

        if (!$transaction) {
            redirectTo('/');
        }

        $receipt = $this->receiptService->getReceipt((int)$params['receipt']);

        if (!$receipt) {
            redirectTo('/');
        }

        if ((int)$receipt->transaction_id !== (int)$params['transaction']) {
            redirectTo('/');
        }

        $this->receiptService->readReceipt($receipt);
    }

    public function delete(array $params)
    {
        $transaction = $this->transactionService->getUserTransaction((int)$params['transaction']);

        if (!$transaction) {
            redirectTo('/');
        }

        $receipt = $this->receiptService->getReceipt((int)$params['receipt']);

        if (!$receipt) {
            redirectTo('/');
        }

        if ((int)$receipt->transaction_id !== (int)$params['transaction']) {
            redirectTo('/');
        }

        $this->receiptService->deleteReceipt($receipt);

        redirectTo('/');
    }
}