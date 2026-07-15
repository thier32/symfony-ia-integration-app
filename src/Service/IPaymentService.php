<?php

namespace App\Service;

use App\Dto\Transaction\TransactionCreateDto;
use App\Dto\Transaction\TransactionDto;

interface IPaymentService extends IBaseService
{

    /**
     * @param TransactionCreateDto $transactionCreateDto
     * @return TransactionDto
     */
    public function payment(TransactionCreateDto $transactionCreateDto):TransactionDto;
}
