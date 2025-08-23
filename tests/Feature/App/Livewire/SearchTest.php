<?php

use App\Livewire\Search;

use function Pest\Livewire\livewire;

it('shows a message when no posts or links are found', function () {
    livewire(Search::class)
        ->set('query', 'test')
        ->assertSee('No posts found for "test".', false)
        ->assertSee('No links found for "test".', false);
});
