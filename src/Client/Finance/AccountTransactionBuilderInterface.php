<?php

namespace LauLamanApps\IzettleApi\Client\Finance;

use LauLamanApps\IzettleApi\API\Finance\AccountTransaction;

interface AccountTransactionBuilderInterface
{
    /**
     * @return AccountTransaction[]
     */
    public function buildFromJson(string $json);
}
