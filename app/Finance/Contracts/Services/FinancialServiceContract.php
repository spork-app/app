<?php
namespace App\Finance\Contracts\Services;

use App\Core\Models\FeatureList;
use Carbon\Carbon;

interface FinancialServiceContract
{
    public function getTransactions(FeatureList $accessToken, Carbon $startDate, Carbon $endDate): array;
    public function getAccounts(FeatureList $accessToken): array;
}
