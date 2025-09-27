<?php
namespace src;

class ArticleController extends Controller {
    public function show(string $slug, int $id) {
        echo "<h1>Article Slug: {$slug}, ID: {$id}</h1>";
        // You could then load the article from a database here, using the slug and ID.
    }

    public function __toString(): string {
        return 'ArticleController';
    }
}
