<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\GenreBook;
use Illuminate\Http\Request;
use App\Http\Resources\BookResource;
use App\Http\Resources\BookSearchResource;
use App\Http\Requests\BookRequest;
use Illuminate\Support\Facades\DB;


class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::with('genre')->paginate(10);;
        
        return BookResource::collection($books);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookRequest $request)
    {
        $data= $request->validated();

        try{
            $bookpost = Book::create([
            'title' => $request->name,
            'author' => $request->author,
            'available' => true,
            ]);

            foreach ($request->genres as $genre){
                GenreBook::create([
                    'book_id' => $bookpost->id,
                    'genre_id' => $genre['genre_id'],
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json([
            'message' => 'Resource created successfully',
            'data' => new BookResource($bookpost),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function search(string $id)
    {
        $book = Book::find($id);

        return new BookSearchResource($book); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookRequest $request, string $id)
    {
        $data = $request->validated();

        DB::beginTransaction();

        try {
            $book = Book::findOrFail($id);

            $book->update([
                'title' => $data['title'],
                'author' => $data['author'],
                'available' => $data['available'] ?? false
            ]);

            // Remove duplicates from the genre IDs
            $uniqueGenres = collect($data['genres'])->pluck('genre_id')->unique();

            // Sync the genres without duplicates
            $book->genre()->sync($uniqueGenres);

            DB::commit();

            return response()->json([
                'message' => 'Resource updated successfully',
                'data' => new BookResource($book),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
{
    $book = Book::find($id);

    if (!$book) {
        return response()->json(['message' => 'Book not found'], 404);
    }

    try {
        // Attempt to delete the book and its genres
        $book->delete();
        GenreBook::where('book_id', $id)->delete();
        return response()->json(['message' => 'Book deleted successfully']);
    } catch (\Exception $e) {
        return response()->json(['message' => 'An error occurred while deleting the book', 'error' => $e->getMessage()], 500);
    }
}

}
