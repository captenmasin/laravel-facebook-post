<?php

namespace Captenmasin\LaravelFacebookPost\Services;

use Illuminate\Support\Facades\Http;
use Captenmasin\LaravelFacebookPost\Traits\Helper;

class FacebookPostService
{
    use Helper;

    private const API_VERSION = 'v19.0';
    private const BASE_URL = "https://graph.facebook.com/" . self::API_VERSION;

    protected string $pageId;
    protected string $accessToken;

    public function __construct(string $pageId, string $accessToken)
    {
        $this->pageId = $pageId;
        $this->accessToken = $accessToken;
    }

    /**
     * Retrieve posts from the Facebook page feed.
     */
    public function getPosts(): array
    {
        $url = self::BASE_URL . "/{$this->pageId}/feed";
        return $this->sendRequest('GET', $url);
    }

    public static function create(
        ?string $text = null,
        ?string $url = null,
        ?string $media = null,
    ): array
    {
        if (!empty($url)) {
            return self::createPostWithLink($url, $text ?? null);
        }

        if (!empty($media)) {
            return self::createPostWithPhoto($media, $text ?? null);
        }

        if (!empty($text)) {
            return self::createPost($text);
        }

        return self::failureResponse(422, 'Invalid post data. Must contain at least a message, link, or photo.');
    }

    /**
     * Publish a new text post.
     */
    public function createPost(string $message): array
    {
        if (blank($message)) {
            return $this->failureResponse(422, 'Message is required.');
        }

        $url = self::BASE_URL . "/{$this->pageId}/feed";
        return $this->sendRequest('POST', $url, ['message' => $message]);
    }

    /**
     * Publish a post with a link.
     */
    public function createPostWithLink(string $url, ?string $message = null): array
    {
        if (blank($url)) {
            return $this->failureResponse(422, 'URL is required.');
        }

        $endpoint = self::BASE_URL . "/{$this->pageId}/feed";
        return $this->sendRequest('POST', $endpoint, [
            'message' => $message,
            'link'    => $url,
        ]);
    }

    /**
     * Publish a post with a photo.
     */
    public function createPostWithPhoto(string $photoUrl, ?string $message = null): array
    {
        if (blank($photoUrl)) {
            return $this->failureResponse(422, 'Photo URL is required.');
        }

        $endpoint = self::BASE_URL . "/{$this->pageId}/photos";
        return $this->sendRequest('POST', $endpoint, [
            'url'     => $photoUrl,
            'message' => $message,
        ]);
    }

    /**
     * Update an existing Facebook post.
     */
    public function updatePost(string|int $postId, string $message): array
    {
        if (blank($postId)) {
            return $this->failureResponse(422, 'Post ID is required.');
        }

        if (blank($message)) {
            return $this->failureResponse(422, 'Message is required.');
        }

        if (!$this->isValidPostId($postId)) {
            return $this->failureResponse(422, 'Invalid Post ID.');
        }

        $endpoint = self::BASE_URL . "/{$postId}";
        return $this->sendRequest('POST', $endpoint, ['message' => $message]);
    }

    /**
     * Delete a Facebook post.
     */
    public function deletePost(string $postId): array
    {
        if (blank($postId)) {
            return $this->failureResponse(422, 'Post ID is required.');
        }

        if (!$this->isValidPostId($postId)) {
            return $this->failureResponse(422, 'Invalid Post ID.');
        }

        $endpoint = self::BASE_URL . "/{$postId}";
        return $this->sendRequest('DELETE', $endpoint);
    }

    /**
     * Send an HTTP request to Facebook API.
     */
    private function sendRequest(string $method, string $url, array $data = []): array
    {
        $data['access_token'] = $this->accessToken;

        $response = match ($method) {
            'GET'    => Http::get($url, $data),
            'POST'   => Http::asForm()->post($url, $data),
            'DELETE' => Http::delete($url, $data),
            default  => throw new \InvalidArgumentException("Unsupported HTTP method: {$method}"),
        };

        return $response->successful()
            ? $this->successResponse('Request successful', $response->json())
            : $this->failureResponse($response->status(), $response->body());
    }

    /**
     * Validate if the given Post ID is correct.
     */
    private function isValidPostId(string $postId): bool
    {
        return preg_match('/^\d+_\d+$/', $postId); // Facebook post IDs are usually in this format
    }
}
