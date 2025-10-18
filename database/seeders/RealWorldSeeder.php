<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class RealWorldSeeder extends Seeder
{
    public function run(): void
    {
        $users = $this->seedUsers();
        [$dragons, $training, $react] = $this->seedTags();

        $article = Article::factory()->create([
            'author_id' => $users['jake']->id,
            'title' => 'How to train your dragon',
            'slug' => 'how-to-train-your-dragon',
            'description' => 'Ever wonder how?',
            'body' => 'You have to believe in dragons. This guide shows you how.',
        ]);

        $article->tags()->sync([$dragons->id, $training->id]);
        $article->favoritedBy()->attach($users['jane']);

        Comment::factory()->create([
            'article_id' => $article->id,
            'author_id' => $users['jane']->id,
            'body' => 'Dragons are amazing! Thanks for sharing.',
        ]);

        $secondArticle = Article::factory()->create([
            'author_id' => $users['jane']->id,
            'title' => 'How to build react apps',
            'slug' => 'how-to-build-react-apps',
            'description' => 'React is a great framework.',
            'body' => 'We explore component-driven development with React.',
        ]);

        $secondArticle->tags()->sync([$react->id]);

        $users['jake']->following()->attach($users['jane']);
        $users['jane']->following()->attach($users['john']);
    }

    /**
     * @return array<string, \App\Models\User>
     */
    private function seedUsers(): array
    {
        return [
            'jake' => User::factory()->create([
                'username' => 'jake',
                'email' => 'jake@realworld.dev',
                'bio' => 'I work at statefarm',
                'image' => 'https://i.pravatar.cc/150?img=1',
                'password' => 'demo1234',
            ]),
            'jane' => User::factory()->create([
                'username' => 'jane',
                'email' => 'jane@realworld.dev',
                'bio' => 'Fullstack developer and writer',
                'image' => 'https://i.pravatar.cc/150?img=2',
                'password' => 'demo1234',
            ]),
            'john' => User::factory()->create([
                'username' => 'john',
                'email' => 'john@realworld.dev',
                'bio' => 'Loves testing APIs',
                'image' => 'https://i.pravatar.cc/150?img=3',
                'password' => 'demo1234',
            ]),
        ];
    }

    /**
     * @return array<int, \App\Models\Tag>
     */
    private function seedTags(): array
    {
        return [
            Tag::firstOrCreate(['name' => 'dragons']),
            Tag::firstOrCreate(['name' => 'training']),
            Tag::firstOrCreate(['name' => 'react']),
        ];
    }
}
