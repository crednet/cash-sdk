<?php

namespace CredPal\CPCash\Http\Middleware;

use Closure;
use CredPal\CPCash\Exceptions\CPCashException;
use CredPal\CPCash\Exceptions\NotFoundException;
use CredPal\CPCash\Traits\WalletAccount;

class CheckWalletAccount
{
    use WalletAccount;

    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     * @throws CPCashException
     * @throws NotFoundException
     */
    public function handle($request, Closure $next)
    {
        $this->isWalletConditionPassed(request('user_id'));

        return $next($request);
    }
}
