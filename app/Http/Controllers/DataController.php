<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Fetch data from the database, assuming a table named 'data_table'
        $data = DB::table('data_table')->orderBy('Data', 'asc')->get();

        // Create a new collection to rank the data
        $rankedData = $data->map(function ($item, $key) use ($data) {
            $item->Peringkat = $data->count() - $key;
            return $item;
        });

        // Sort data by rank
        $rankedData = $rankedData->sortBy('Peringkat');

        // Get the user's input for the split percentage (default to 50%)
        $splitPercentage = $request->input('split_percentage', 50);

        // Calculate the index to split the data based on the percentage
        $splitIndex = ceil($rankedData->count() * ($splitPercentage / 100));

        // Split the data into two parts dynamically based on user input
        $p = $rankedData->slice(0, $splitIndex);
        $q = $rankedData->slice($splitIndex);

        // Pass the split data to the view
        return view('data.index', ['p' => $p, 'q' => $q, 'splitPercentage' => $splitPercentage]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function showForm()
    {
        // Show the form to accept user input
        return view('data.form');
    }
    public function processInput(Request $request)
    {
        // Validate the input
        $request->validate([
            'data' => 'required|string',
            'split_percentage' => 'required|integer|min:1|max:99'
        ]);

        // Get the data and split percentage from user input
        $inputData = preg_split('/\r\n|\r|\n/', $request->input('data')); // Split the string by newline characters
        $splitPercentage = $request->input('split_percentage');

        // Clean and format the data
        $formattedData = collect($inputData)->map(function ($item, $key) {
            return [
                'No' => $key + 1,
                'Data' => (int) trim($item), // Convert to integer and trim spaces
            ];
        });

        // Sort the data by the 'Data' value
        $rankedData = $formattedData->sortBy('Data')->values();

        // Assign rankings
        $rankedData = $rankedData->map(function ($item, $key) use ($rankedData) {
            $item['Peringkat'] = $rankedData->count() - $key;
            return $item;
        });

        // Calculate the split index dynamically based on percentage
        $splitIndex = ceil($rankedData->count() * ($splitPercentage / 100));

        // Split the data into two parts
        $p = $rankedData->slice(0, $splitIndex);
        $q = $rankedData->slice($splitIndex);

        // Pass the split data to the view
        return view('data.index', ['p' => $p, 'q' => $q, 'splitPercentage' => $splitPercentage]);
    }

}
