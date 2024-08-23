<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JsonData;

class JsonDataController extends Controller
{
    public function index()
    {
        // $data = JsonData::all();

        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'Data retrieved successfully',
        //     'data' => $data
        // ], 200);

            // Fetch all records from the json_datas table
            $jsonDataRecords = JsonData::all();

            // Decode the JSON data for each record
            $formattedData = $jsonDataRecords->map(function($record) {
                $record->data = json_decode($record->data, true); // Decode JSON to an associative array
                return $record;
            });

            // Return the response with a well-structured format
            return response()->json([
                'status' => 'success',
                'message' => 'Data retrieved successfully',
                'data' => $formattedData
            ], 200);
    }

    public function store(Request $request)
    {
        // Check if the file is uploaded
        if ($request->hasFile('uploaded_file')) {
            $file = $request->file('uploaded_file');

            // Ensure the uploaded file is a JSON file
            if ($file->getClientOriginalExtension() !== 'json') {
                return response()->json(['error' => 'Only JSON files are allowed'], 400);
            }

            // Get the contents of the JSON file
            $jsonData = file_get_contents($file->getRealPath());

            // Decode the JSON data into a PHP array
            $dataArray = json_decode($jsonData, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Invalid JSON data'], 400);
            }

            // Store the decoded data in the database as JSON
            $jsonDataRecord = JsonData::create(['data' => json_encode($dataArray)]);
            return response()->json($jsonDataRecord, 201);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }
}
