<?php

declare(strict_types=1);

use Laranex\BetterLaravel\Str;

it('formats route file names as pluralised kebab-case', function (string $input, string $expected) {
    expect(Str::route($input))->toBe($expected);
})->with([
    ['blog', 'blogs'],
    ['BlogPost', 'blog-posts'],
    ['blog_post', 'blog_posts'],
    ['categories', 'categories'],
]);

it('formats directories in lower case', function () {
    expect(Str::directory('V1'))->toBe('v1')
        ->and(Str::directory(''))->toBe('');
});

it('extracts the real name of a class file', function () {
    expect(Str::realName('CreateArticleFeature.php', '/Feature.php/'))->toBe('Create Article')
        ->and(Str::realName('CreateArticle'))->toBe('Create Article')
        ->and(Str::realName(''))->toBe('');
});

it('formats feature names', function (string $input, string $expected) {
    expect(Str::feature($input))->toBe($expected);
})->with([
    ['createPost', 'CreatePostFeature'],
    ['CreatePostFeature', 'CreatePostFeature'],
    ['CreatePostFeature.php', 'CreatePostFeature'],
    ['Create Post Feature', 'CreatePostFeature'],
    ['create', 'CreateFeature'],
    ['Blog/createPost', 'Blog'.DIRECTORY_SEPARATOR.'CreatePostFeature'],
]);

it('formats job names', function (string $input, string $expected) {
    expect(Str::job($input))->toBe($expected);
})->with([
    ['createPost', 'CreatePostJob'],
    ['CreatePostJob', 'CreatePostJob'],
    ['CreatePostJob.php', 'CreatePostJob'],
    ['Create Post Job', 'CreatePostJob'],
]);

it('formats operation names', function (string $input, string $expected) {
    expect(Str::operation($input))->toBe($expected);
})->with([
    ['notifySubscribers', 'NotifySubscribersOperation'],
    ['NotifySubscribersOperation', 'NotifySubscribersOperation'],
    ['NotifySubscribersOperation.php', 'NotifySubscribersOperation'],
]);

it('formats controller names', function (string $input, string $expected) {
    expect(Str::controller($input))->toBe($expected);
})->with([
    ['blog', 'BlogController'],
    ['BlogController', 'BlogController'],
    ['BlogController.php', 'BlogController'],
    ['blog_post', 'BlogPostController'],
]);

it('formats request names', function (string $input, string $expected) {
    expect(Str::request($input))->toBe($expected);
})->with([
    ['storePost', 'StorePostRequest'],
    ['StorePostRequest', 'StorePostRequest'],
    ['StorePostRequest.php', 'StorePostRequest'],
]);

it('formats policy names', function () {
    expect(Str::policy('post'))->toBe('PostPolicy')
        ->and(Str::policy('PostPolicy.php'))->toBe('PostPolicy');
});

it('formats module names with a Module suffix exactly once', function (string $input, string $expected) {
    expect(Str::module($input))->toBe($expected);
})->with([
    ['blog', 'BlogModule'],
    ['Blog', 'BlogModule'],
    ['BlogModule', 'BlogModule'],
    ['blog_module', 'BlogModule'],
    ['user profile', 'UserProfileModule'],
]);

it('formats domain and model names as studly case', function () {
    expect(Str::domain('blog_post'))->toBe('BlogPost')
        ->and(Str::domain('Blog'))->toBe('Blog')
        ->and(Str::model('blog post'))->toBe('BlogPost');
});

it('still exposes the Laravel string helpers', function () {
    expect(Str::studly('blog_post'))->toBe('BlogPost')
        ->and(Str::kebab('BlogPost'))->toBe('blog-post')
        ->and(Str::plural('post'))->toBe('posts');
});
