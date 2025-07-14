<?php

use App\Models\User;
use App\Livewire\CommentForm;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

it('validates the comment and dispatches an event for the parent component', function () {
    $user = User::factory()->create();

    actingAs($user);

    livewire(CommentForm::class)
        ->set('commentContent', 'Lorem ipsum dolor sit amet.')
        ->call('submit')
        ->assertDispatched('comment.submitted')
        ->assertSet('commentContent', '');
});

it("doesn't allow guests to comment", function () {
    livewire(CommentForm::class)
        ->set('commentContent', 'Lorem ipsum dolor sit amet.')
        ->call('submit')
        ->assertStatus(401);
});
