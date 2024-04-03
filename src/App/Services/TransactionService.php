<?php

namespace App\Services;

use Framework\Database;

class TransactionService
{
    public function __construct(private Database $db)
    {
    }

    public function createTransaction($transaction): void
    {
        $this->db->query(
            "INSERT INTO transactions(user_id, description, amount, date) VALUES(:uid, :description, :amount, :date)",
            $transaction
        );
    }

    public function getUserTransactions(int $onPage, int $offset): array
    {
        $searchTerm = addcslashes($_GET['s'] ?? '', '%_');

        $params = [
            'uid' => $_SESSION['user'],
            'search' => "%{$searchTerm}%"
        ];

        $transactions = $this->db->query(
            "SELECT *, TO_CHAR(date::DATE, 'dd Month, yyyy') as formatted_date FROM transactions WHERE user_id=:uid AND description LIKE :search ORDER BY date DESC LIMIT {$onPage} OFFSET {$offset};",
            $params
        )->findAll();

        $transactions = array_map(
            function (object $transaction) {
                $transaction->receipts = $this->db->query("SELECT * FROM receipts WHERE transaction_id=:tid;", [
                    'tid' => $transaction->id
                ])->findAll();

                return $transaction;
            },
            $transactions
        );

        $transactionCount = $this->db->query("SELECT COUNT(*) FROM transactions WHERE user_id=:uid AND description LIKE :search;", $params)->count();

        return [$transactions, $transactionCount];
    }

    public function getUserTransaction(int $id): object|false
    {
        $transaction = $this->db->query(
            "SELECT *, TO_CHAR(date::DATE, 'yyyy-mm-dd') as formatted_date FROM transactions WHERE id=:id AND user_id=:uid;",
            [
                'id' => $id,
                'uid' => $_SESSION['user']
            ]
        )->findOne();

        return $transaction;
    }

    public function updateTransaction($transaction): void
    {
        $this->db->query(
            "UPDATE transactions SET description=:description, amount=:amount, date=:date WHERE id=:id AND user_id=:uid;",
            $transaction
        );
    }

    public function deleteTransaction($transaction): void
    {
        $this->db->query(
            "DELETE FROM transactions WHERE id=:id AND user_id=:uid;",
            [
                'id' => $transaction,
                'uid' => $_SESSION['user']
            ]
        );
    }
}