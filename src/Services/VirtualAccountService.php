<?php

namespace CredPal\CPCash\Services;

use CredPal\CPCash\Contracts\VirtualAccount;
use CredPal\CPCash\Exceptions\CPCashException;
use CredPal\CPCash\Exceptions\NotFoundException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use CredPal\CPCash\Exceptions\InternalServerException;
use Symfony\Component\HttpFoundation\Response;

class VirtualAccountService implements VirtualAccount
{}
