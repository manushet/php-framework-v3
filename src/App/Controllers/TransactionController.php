<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\ValidatorService;
use App\Services\TransactionService;

class TransactionController
{
    public function __construct(
        private TemplateEngine $templateEngine,
        private ValidatorService $validatorService,
        private TransactionService $transactionService
    ) {
    }

    public function createView()
    {
        echo $this->templateEngine->render("transactions/create.php", [
            'title' => 'Create Transaction'
        ]);
    }

    public function create(): void
    {
        $this->validatorService->validateTransaction($_POST);

        $transaction = [
            "uid" => $_SESSION['user'],
            "description" => $_POST['description'],
            "amount" => $_POST['amount'],
            "date" => "{$_POST['date']} 00:00:00",
        ];

        $this->transactionService->createTransaction($transaction);

        redirectTo('/');
    }

    public function editView(array $params)
    {
        $transaction = $this->transactionService->getUserTransaction((int)$params['transaction']);

        if (!$transaction) {
            redirectTo('/');
        }

        echo $this->templateEngine->render("transactions/edit.php", [
            'title' => 'Create Transaction',
            'transaction' => $transaction,
        ]);
    }

    public function edit(array $params)
    {
        $transaction = $this->transactionService->getUserTransaction((int)$params['transaction']);

        if (!$transaction) {
            redirectTo('/');
        }

        $this->validatorService->validateTransaction($_POST);

        $transaction = [
            "id" => (int)$params['transaction'],
            "uid" => $_SESSION['user'],
            "description" => $_POST['description'],
            "amount" => $_POST['amount'],
            "date" => "{$_POST['date']} 00:00:00",
        ];

        $this->transactionService->updateTransaction($transaction);

        redirectTo('/');
    }

    public function delete(array $params)
    {
        $transaction = $this->transactionService->getUserTransaction((int)$params['transaction']);

        if (!$transaction) {
            redirectTo('/');
        }

        $this->transactionService->deleteTransaction((int)$params['transaction']);

        redirectTo('/');
    }
}