<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
use App\Models\Genre;
use App\Models\GenreBook;
use App\Models\BorrowedBook;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Book::factory(10)->create();
        
        BorrowedBook::factory(10)->create();

        $genres = ["Fiction", "Non-Fiction", "Horror", "Fantasy", "Tutorial", "Sci-fi", "Phylosiphy", "Biograpghy", "Kids"];

        foreach ($genres as $genre){
            Genre::factory()->create([
                'name' => $genre
            ]);
        }
        
        GenreBook::factory(20)->create();
        
    }
}
