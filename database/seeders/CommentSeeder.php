<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run() : void
    {
        Comment::factory(50)
            ->recycle(User::all())
            ->recycle(Post::all())
            ->create([
                'content' => <<<'MARKDOWN'
**bold** *italic* ~~strike~~
[link](https://example.com) ![img](https://via.placeholder.com/50)

> quote

- item
  - subitem
1. one

---

`inline`
```js
console.log("hi");
```
MARKDOWN
            ]);
    }
}
