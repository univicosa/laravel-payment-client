<?php

namespace Payments\Client\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class ReturnController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param Request $request
     * @return mixed
     */
    public function receive(Request $request)
    {
        $token = (new Parser())->parse($request->get('token'));
        $signer = new Sha256();
        if ($token->isExpired() || !$token->verify($signer, config('payment.system'))) {
            abort(403);
        }
        if (!$token->hasClaim('payments')) {
            abort(422);
        }

        $class = '\\'.ltrim(config('payment.controller'), '\\');
        $controller = resolve($class);
        $action = config('payment.action');

        return $controller->$action(json_decode($token->getClaim('payments')));
    }
}