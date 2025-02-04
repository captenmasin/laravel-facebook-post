# Laravel Facebook Page Post
Create, update, delete and get posts from a facebook page in laravel.

## Requirements

- PHP >=8.3
- Laravel >= 11

## Installation
```
composer require captenmasin/laravel-facebook-post
```
## Configuration
You can publish the configuration file `config/facebook.php` optionally by using the following command:
``` 
php artisan vendor:publish --provider="Captenmasin\LaravelFacebookPost\FacebookPostServiceProvider" --tag="config"
```

Configure `.env` file
```
FACEBOOK_PAGE_ID=
FACEBOOK_ACCESS_TOKEN=
```

## Usage

### Get All posts
``` 
use Captenmasin\LaravelFacebookPost\Facades\FacebookPost;

$posts = FacebookPost::getPosts();
```

### Create Basic Text post
``` 
use Captenmasin\LaravelFacebookPost\Facades\FacebookPost;

$post = FacebookPost::createPost('Hello world');
```

### Create post with photo
``` 
use Captenmasin\LaravelFacebookPost\Facades\FacebookPost;

$text = 'Hello world'; //optional
$imageUrl = 'https://example.com/image.jpg';

$response = FacebookPost::createPostWithPhoto($imageUrl, $text);
```

### Create post with link
``` 
use Captenmasin\LaravelFacebookPost\Facades\FacebookPost;

$text = 'Hello world'; //optional
$url = 'https://example.com';

$response = FacebookPost::createPostWithLink($url, $text);
```

### Update  post
``` 
use Captenmasin\LaravelFacebookPost\Facades\FacebookPost;

$message = 'Foo bar';
$postId = '1234567890_987654321'; // Your post id

$response = FacebookPost::updatePost($postId, $message);
```

### Delete  post
``` 
use Captenmasin\LaravelFacebookPost\Facades\FacebookPost;

$postId = '1234567890_987654321'; // Your post id
$response = FacebookPost::deletePost($postId);
```

## Example Responses

### Success
``` 
[
  "status" => "success"
  "status_code" => 200
  "message" => "Post created successfully"
  "post_id" => "1234567890_987654321"
]
```

### Failure
``` 
[
  "status" => "fail"
  "status_code" => 422
  "message" => "Message is required"
]
```

## Links

### [Facebook Page API](https://developers.facebook.com/docs/pages-api)
### [Facebook Access Token](https://developers.facebook.com/docs/facebook-login/guides/access-tokens/get-long-lived)

## License
The MIT License (MIT). Please see [License File](LICENSE) for more information.
