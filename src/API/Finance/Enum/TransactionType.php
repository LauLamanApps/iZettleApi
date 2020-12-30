<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\API\Finance\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static TransactionType CARD_PAYMENT()
 * @method static TransactionType CARD_REFUND()
 * @method static TransactionType BANK_ACCOUNT_VERIFICATION()
 * @method static TransactionType PAYOUT()
 * @method static TransactionType FAILED_PAYOUT()
 * @method static TransactionType CASH_BACK()
 * @method static TransactionType CASH_BACK_PAYOUT()
 * @method static TransactionType VOUCHER_ACTIVATION()
 * @method static TransactionType EMONEY_TRANSFER()
 * @method static TransactionType TELL_FRIEND()
 * @method static TransactionType FROZEN_FUNDS()
 * @method static TransactionType FEE_DISCOUNT_REVOCATION()
 * @method static TransactionType CARD_PAYMENT_FEE()
 * @method static TransactionType CARD_PAYMENT_FEE_REFUND()
 * @method static TransactionType ADVANCE()
 * @method static TransactionType ADVANCE_FEE()
 * @method static TransactionType ADVANCE_DOWN_PAYMENT()
 * @method static TransactionType ADVANCE_FEE_DOWN_PAYMENT()
 * @method static TransactionType SUBSCRIPTION_CHARGE()
 * @method static TransactionType INVOICE_PAYMENT()
 * @method static TransactionType INVOICE_PAYMENT_FEE()
 * @method static TransactionType ADJUSTMENT()
 */
final class TransactionType extends Enum
{
    public const CARD_PAYMENT = 'CARD_PAYMENT';
    public const CARD_REFUND = 'CARD_REFUND';
    public const BANK_ACCOUNT_VERIFICATION = 'BANK_ACCOUNT_VERIFICATION';
    public const PAYOUT = 'PAYOUT';
    public const FAILED_PAYOUT = 'FAILED_PAYOUT';
    public const CASH_BACK = 'CASHBACK';
    public const CASH_BACK_PAYOUT = 'CASHBACK_PAYOUT';
    public const VOUCHER_ACTIVATION = 'VOUCHER_ACTIVATION';
    public const EMONEY_TRANSFER = 'EMONEY_TRANSFER';
    public const TELL_FRIEND = 'TELL_FRIEND';
    public const FROZEN_FUNDS = 'FROZEN_FUNDS';
    public const FEE_DISCOUNT_REVOCATION = 'FEE_DISCOUNT_REVOCATION';
    public const CARD_PAYMENT_FEE = 'CARD_PAYMENT_FEE';
    public const CARD_PAYMENT_FEE_REFUND = 'CARD_PAYMENT_FEE_REFUND';
    public const ADVANCE = 'ADVANCE';
    public const ADVANCE_FEE = 'ADVANCE_FEE';
    public const ADVANCE_DOWN_PAYMENT = 'ADVANCE_DOWNPAYMENT';
    public const ADVANCE_FEE_DOWN_PAYMENT = 'ADVANCE_FEE_DOWNPAYMENT';
    public const SUBSCRIPTION_CHARGE = 'SUBSCRIPTION_CHARGE';
    public const INVOICE_PAYMENT = 'INVOICE_PAYMENT';
    public const INVOICE_PAYMENT_FEE = 'INVOICE_PAYMENT_FEE';
    public const ADJUSTMENT = 'ADJUSTMENT';
}
