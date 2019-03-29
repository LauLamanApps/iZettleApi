<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Filter\Finance;

use DateTime;
use LauLamanApps\IzettleApi\API\Finance\Enum\AccountTypeGroup;
use LauLamanApps\IzettleApi\Client\Filter\FilterInterface;

final class BalanceInfoFilter implements FilterInterface
{
    /**
     * @var AccountTypeGroup
     */
    private $accountTypeGroup;

    /**
     * @var DateTime
     */
    private $at;

    public function __construct(AccountTypeGroup $accountTypeGroup, ?DateTime $at = null)
    {
        $this->at = $at;
        $this->accountTypeGroup = $accountTypeGroup;
    }

    public function getAccountTypeGroup(): AccountTypeGroup
    {
        return $this->accountTypeGroup;
    }

    public function getParameters(): array
    {
        if (!$this->at) {
            return [];
        }

        return [
            'at' => $this->at->format('Y-m-d')
        ];
    }
}
