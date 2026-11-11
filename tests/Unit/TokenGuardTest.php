<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Auth\TokenGuard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class TokenGuardTest extends TestCase
{
    public static function invalidTokens(): array
    {
        return [
            'true' => [true], 'false' => [false], 'integer' => [1],
            'zero' => [0], 'float' => [1.5], 'array' => [['token']],
            'empty array' => [[]], 'null' => [null], 'empty string' => [''],
        ];
    }

    #[DataProvider('invalidTokens')]
    public function test_invalid_credentials_never_reach_the_user_lookup($token): void
    {
        $provider = $this->createMock(UserProvider::class);
        $provider->expects($this->never())->method('retrieveByCredentials');
        $guard = new TokenGuard($provider, Request::create('/api/user', 'POST', ['api_token' => $token]));

        $this->assertNull($guard->user());
        $this->assertFalse($guard->validate(['api_token' => $token]));
    }

    public function test_valid_string_token_still_authenticates(): void
    {
        $user = new User;
        $provider = $this->createMock(UserProvider::class);
        $provider->expects($this->exactly(2))->method('retrieveByCredentials')
            ->with(['api_token' => 'valid-token'])->willReturn($user);
        $guard = new TokenGuard($provider, Request::create('/api/user', 'GET', ['api_token' => 'valid-token']));

        $this->assertSame($user, $guard->user());
        $this->assertTrue($guard->validate(['api_token' => 'valid-token']));
    }
}
