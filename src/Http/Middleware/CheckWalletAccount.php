<?php

namespace CredPal\CPCash\Http\Middleware;

use Closure;
use CredPal\CPCash\Exceptions\CPCashException;
use CredPal\CPCash\Exceptions\NotFoundException;
use CredPal\CPCash\Traits\HasWalletAccount;
use Illuminate\Http\Request;

class CheckWalletAccount
{
    use HasWalletAccount;

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws CPCashException
     * @throws NotFoundException
     */
    public function handle(Request $request, Closure $next)
    {
        $this->isWalletConditionPassed($request->get('user_id'));

        return $next($request);
    }
}
