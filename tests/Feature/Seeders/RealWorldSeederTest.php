<?php

namespace Tests\Feature\Seeders;

use App\Models\Article;
use App\Models\User;
use Database\Seeders\RealWorldSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class RealWorldSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_realworld_seeder_populates_reference_data(): void
    {
        Artisan::call('db:seed', ['--class' => RealWorldSeeder::class]);

        $this->assertDatabaseHas('users', ['username' => 'jake']);
        $this->assertDatabaseHas('articles', ['slug' => 'how-to-train-your-dragon']);
        $this->assertDatabaseHas('tags', ['name' => 'dragons']);

        $jake = User::where('username', 'jake')->first();
        $article = Article::where('slug', 'how-to-train-your-dragon')->first();

        $this->assertNotNull($jake);
        $this->assertNotNull($article);

        $this->assertTrue($jake->following()->where('username', 'jane')->exists());
        $this->assertTrue($article->tags()->where('name', 'dragons')->exists());
        $this->assertTrue($article->favoritedBy()->where('username', 'jane')->exists());
        $this->assertTrue($article->comments()->exists());
    }
}
