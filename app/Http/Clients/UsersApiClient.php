<?php

namespace App\Http\Clients;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class UsersApiClient
{
    public function __invoke(): Response
    {
        return Http::baseUrl(config('services.users_api.url'))
            ->withToken(config('services.users_api.token'))
            ->acceptJson()
            ->connectTimeout(3)
            ->timeout(10)
            ->get('/api/users');
    }
}
