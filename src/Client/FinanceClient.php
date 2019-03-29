<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client;

use DateTime;
use LauLamanApps\IzettleApi\API\Finance\AccountTransaction;
use LauLamanApps\IzettleApi\API\Finance\Enum\AccountTypeGroup;
use LauLamanApps\IzettleApi\API\Finance\PayoutInfo;
use LauLamanApps\IzettleApi\Client\Filter\Finance\AccountTransactionsFilter;
use LauLamanApps\IzettleApi\Client\Filter\Finance\BalanceInfoFilter;
use LauLamanApps\IzettleApi\Client\Filter\Finance\PayoutInfoFilter;
use LauLamanApps\IzettleApi\Client\Finance\AccountTransactionBuilderInterface;
use LauLamanApps\IzettleApi\Client\Finance\AccountTransactionParser;
use LauLamanApps\IzettleApi\Client\Finance\PayoutInfoBuilderInterface;
use LauLamanApps\IzettleApi\Client\Finance\PayoutInfoParser;
use LauLamanApps\IzettleApi\IzettleClientInterface;
use Money\Currency;
use Money\Money;
use Ramsey\Uuid\UuidInterface;

final class FinanceClient
{
    const BASE_URL = 'https://finance.izettle.com/organizations/%s';

    const GET_ACCOUNT_TRANSACTIONS = self::BASE_URL . '/accounts/%s/transactions';
    const GET_ACCOUNT_BALANCE = self::BASE_URL . '/accounts/%s/balance';

    const GET_PAYOUT_INFO = self::BASE_URL . '/payout-info';

    /**
     * @var IzettleClientInterface
     */
    private $client;

    /**
     * @var string
     */
    private $organizationUuid;

    /**
     * @var AccountTransactionBuilderInterface
     */
    private $accountTransactionBuilder;

    /**
     * @var PayoutInfoBuilderInterface
     */
    private $payoutInfoBuilder;

    public function __construct(
        IzettleClientInterface $client,
        ?UuidInterface $organizationUuid = null,
        AccountTransactionBuilderInterface $accountTransactionBuilder,
        PayoutInfoBuilderInterface $payoutInfoBuilder
    ) {
        $this->client = $client;
        $this->organizationUuid = $organizationUuid ? (string) $organizationUuid : 'self';
        $this->accountTransactionBuilder = $accountTransactionBuilder;
        $this->payoutInfoBuilder = $payoutInfoBuilder;
    }

    /**
     * @return AccountTransaction[]
     */
    public function getAccountTransactions(
        AccountTypeGroup $accountTypeGroup,
        AccountTransactionsFilter $filter
    ): array {
        $url = sprintf(self::GET_ACCOUNT_TRANSACTIONS, $this->organizationUuid, $accountTypeGroup->getValue());


        $json = $this->client->getJson(
            $this->client->get(
                $url,
                $filter
            )
        );

        return $this->accountTransactionBuilder->buildFromJson($json);
    }

    public function getBalanceInfo(BalanceInfoFilter $filter): Money
    {
        $url = sprintf(self::GET_ACCOUNT_BALANCE, $this->organizationUuid, $filter->getAccountTypeGroup()->getValue());
        $response = $this->client->get($url, $filter);
        $data = json_decode($this->client->getJson($response), true)['data'];
        $currency = new Currency($data['currencyId']);

        return new Money($data['totalBalance'], $currency);
    }

    public function getPayoutInfo(?PayoutInfoFilter $filter = null): PayoutInfo
    {
        $url = sprintf(self::GET_PAYOUT_INFO, $this->organizationUuid);
        $json = $this->client->getJson(
            $this->client->get(
                $url,
                $filter
            )
        );

        return $this->payoutInfoBuilder->buildFromJson($json);
    }
}
