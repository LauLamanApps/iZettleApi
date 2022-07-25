<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Finance;

use DateTime;
use LauLamanApps\IzettleApi\API\Finance\AccountTransaction;
use LauLamanApps\IzettleApi\API\Finance\Enum\TransactionType;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;

final class AccountTransactionBuilder implements AccountTransactionBuilderInterface
{
    /**
     * @return AccountTransaction[]
     */
    public function buildFromJson(string $json)
    {
        $data = json_decode($json, true);

        return $this->buildFromArray($data['data']);
    }

    /**
     * @return AccountTransaction[]
     */
    private function buildFromArray(array $data): array
    {
        $array = [];

        foreach ($data as $accountTransaction) {
            $array[] = $this->build($accountTransaction);
        }

        return $array;
    }

    private function build(array $accountTransaction): AccountTransaction
    {
        return new AccountTransaction(
            new DateTime($accountTransaction['timestamp']),
            $accountTransaction['amount'],
            TransactionType::from($accountTransaction['originatorTransactionType']),
            Uuid::fromString($accountTransaction['originatingTransactionUuid'])
        );
    }
}
