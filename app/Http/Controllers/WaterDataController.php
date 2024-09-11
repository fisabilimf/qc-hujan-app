<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class WaterDataController extends Controller
{
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
        return view('qcdebit', ['p' => $p, 'q' => $q, 'splitPercentage' => $splitPercentage]);
    }
}
