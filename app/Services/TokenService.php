<?php

namespace App\Services;

use App\Models\Authorization;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TokenService
{
    /**
     * @param  HasherContract  $hasher
     */
    public function __construct(private readonly HasherContract $hasher)
    {
    }

    /**
     * @param  string  $email
     * @param  string  $password
     * @return string
     */
    public function issueToken(string $email, string $password): string
    {
        $organization = $this->getOrganization($email);

        $this->validatePassword($password, $organization);

        $token = $organization->createToken('API');

        return $token->plainTextToken;
    }

    /**
     * @param  string  $email
     * @return Authorization
     */
    private function getOrganization(string $email): Authorization
    {
        return Authorization::query()->firstWhere('email', $email)
            ?? throw new NotFoundHttpException('Organization not found.');
    }

    /**
     * @param  string  $password
     * @param  Authorization  $organization
     * @return void
     */
    private function validatePassword(string $password, Authorization $organization): void
    {
        if (!$this->hasher->check($password, $organization->getAuthPassword())) {
            throw new BadRequestHttpException('Incorrect password.');
        }
    }
}
