<?php

namespace App\Enum;

enum OperationType: string
{
    case ORDER_SALE = 'ORDER_SALE';
    case REFILL_PRODUCT = 'REFILL_PRODUCT';
    case INCOMING_SHIPMENT = 'INCOMING_SHIPMENT';
    case MANUAL_ADJUSTMENT = 'MANUAL_ADJUSTMENT';
}
