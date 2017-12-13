<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\API\Finance\Enum;

use Werkspot\Enum\AbstractEnum;

/**
 * @method static TransactionType cardPayment()
 * @method bool isCardPayment()
 * @method static TransactionType cardRefund()
 * @method bool isCardRefund()
 * @method static TransactionType bankAccountVerification()
 * @method bool isBankAccountVerification()
 * @method static TransactionType payout()
 * @method bool isPayout()
 * @method static TransactionType failedPayout()
 * @method bool isFailedPayout()
 * @method static TransactionType cashBack()
 * @method bool isCashBack()
 * @method static TransactionType cashBackPayout()
 * @method bool isCashBackPayout()
 * @method static TransactionType voucherActivation()
 * @method bool isVoucherActivation()
 * @method static TransactionType emoneyTransfer()
 * @method bool isEmoneyTransfer()
 * @method static TransactionType tellFriend()
 * @method bool isTellFriend()
 * @method static TransactionType frozenFunds()
 * @method bool isFrozenFunds()
 * @method static TransactionType feeDiscountRevocation()
 * @method bool isFeeDiscountRevocation()
 * @method static TransactionType cardPaymentFee()
 * @method bool isCardPaymentFee()
 * @method static TransactionType cardPaymentFeeRefund()
 * @method bool isCardPaymentFeeRefund()
 * @method static TransactionType advance()
 * @method bool isAdvance()
 * @method static TransactionType advanceFee()
 * @method bool isAdvanceFee()
 * @method static TransactionType advanceDownPayment()
 * @method bool isAdvanceDownPayment()
 * @method static TransactionType advanceFeeDownPayment()
 * @method bool isAdvanceFeeDownPayment()
 * @method static TransactionType subscriptionCharge()
 * @method bool isSubscriptionCharge()
 * @method static TransactionType invoicePayment()
 * @method bool isInvoicePayment()
 * @method static TransactionType invoicePaymentFee()
 * @method bool isInvoicePaymentFee()
 */
final class TransactionType extends AbstractEnum
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
