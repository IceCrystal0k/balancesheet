<?php
namespace App\Enums;

abstract class ClientType extends BasicEnum
{
    const Any = 0;
    const Provider = 1;
    const Beneficiary = 2;
}