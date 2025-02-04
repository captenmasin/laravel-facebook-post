<?php

namespace Captenmasin\LaravelFacebookPost\Traits;

trait Helper
{
    protected string $pageId;
    protected string $accessToken;

    /**
     * Validate if the given ID follows Facebook's expected format.
     */
    public function isValidPostId(string $id): bool
    {
        return preg_match('/^\d+_\d+$/', $id) === 1;
    }

    /**
     * Validate if a given value is a non-empty string.
     */
    public function isValidString($value): bool
    {
        return is_string($value) && trim($value) !== '';
    }

    /**
     * Generate a standardized success response.
     */
    public function successResponse(string $message, ?string $postId = null): array
    {
        $response = [
            'status'      => 'success',
            'status_code' => 200,
            'message'     => $message,
        ];

        if (!empty($postId)) {
            $response['post_id'] = $postId;
        }

        return $response;
    }

    /**
     * Generate a standardized failure response.
     */
    public function failureResponse(int $code, string $message): array
    {
        return [
            'status'      => 'fail',
            'status_code' => intval($code),
            'message'     => $message,
        ];
    }
}
