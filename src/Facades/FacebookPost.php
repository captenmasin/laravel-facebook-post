<?php

namespace Captenmasin\LaravelFacebookPost\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array getPosts()
 * @method static array createPost(string $message)
 * @method static array createPostWithLink(string $url, ?string $message = null)
 * @method static array createPostWithPhoto(string $url, ?string $message = null)
 * @method static array updatePost(string $postId, string $message)
 * @method static array deletePost(string $postId)
 */
class FacebookPost extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'facebook-post-service';
    }
}
