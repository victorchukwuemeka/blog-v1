<?php

use App\Models\Category;

use function Pest\Laravel\get;

it('shows a category', function () {
    $category = Category::factory()->create();

    get(route('categories.show', $category))
        ->assertOk()
        ->assertViewIs('categories.show')
        ->assertViewHas('category', $category);
});

it('throws a 404 if the category does not exist', function () {
    get(route('categories.show', 'non-existent-category'))
        ->assertNotFound();
});
