<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\TransactionService;

class HomeController
{
    public function __construct(
        private TemplateEngine $templateEngine,
        private TransactionService $transactionService
    ) {
    }

    public function home(): void
    {
        $page = (int)($_GET['p'] ?? 1);

        $onPage = 2;

        $offset = ($page - 1) * $onPage;

        $searchTerm = $_GET['s'] ?? null;

        [$transactions, $transactionCount] = $this->transactionService->getUserTransactions($onPage, $offset);

        $pagesTotal = ceil($transactionCount / $onPage);

        $pages = $pagesTotal ? range(1, $pagesTotal) : [];

        $pagelinks = array_map(
            fn ($pageNum) => http_build_query([
                'p' => $pageNum,
                's' => $searchTerm
            ]),
            $pages
        );

        echo $this->templateEngine->render("/index.php", [
            'title' => 'Home page',
            'transactions' => $transactions,
            'currentPage' => $page,
            'previousPageQuery' => http_build_query([
                'p' => $page - 1,
                's' => $searchTerm
            ]),
            'pagesTotal' => $pagesTotal,
            'nextPageQuery' => http_build_query([
                'p' => $page + 1,
                's' => $searchTerm
            ]),
            'pageLinks' => $pagelinks,
            'searchTerm' => $searchTerm,
        ]);
    }
}