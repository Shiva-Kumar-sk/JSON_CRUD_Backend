<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function item()
    {
        $filePath = storage_path('item.json');

        if (!File::exists($filePath)) {

            File::put($filePath, json_encode([]));
        }


        $content = File::get(storage_path('item.json'));
        $item = json_decode($content);
        return response(["status" => true, "data" => $item, "message" => "all data get successfully"], 200);
    }

    public function createItem(Request $request)
    {
        $validator = validator::make($request->all(), [
            "name" => "required",
            "description" => "required"

        ]);

        if ($validator->fails()) {
            return response(['status' => false, "data" => null, "message" => "validation error", "error" => $validator->errors()], 400);
        }

        $content = File::get(storage_path('item.json'));
        $item = json_decode($content);
        $arrayLen = count($item);
        
        if ($arrayLen > 0) {
            $idColumn = array_column($item, 'id');
            $highestId = max($idColumn);


            $newItem = [
                "id" => $highestId + 1,
                "name" => $request->name,
                "description" => $request->description
            ];
        } else{
            $newItem = [
                "id" => 1,
                "name" => $request->name,
                "description" => $request->description
            ];

        }

        $item[] = $newItem;
        $data = json_encode($item);
        File::put(storage_path('item.json'), $data);
        return response(["status" => true, "data" => $newItem, "message" => "data inserted"], 201);
    }


    public function updateItem(Request $request)
    {
        $validator = validator::make($request->all(), [
            "id" => "required",
            "name" => "required",
            "description" => "required"

        ]);

        if ($validator->fails()) {
            return response(['status' => false, "data" => null, "message" => "validation error", "error" => $validator->errors()], 400);
        }

        $content = File::get(storage_path('item.json'));
        $item = json_decode($content, 2);

        $index = array_search($request->id, array_column($item, 'id'));


        if ($index !== false) {
            $item[$index]['name'] = $request->name;
            $item[$index]['description'] = $request->description;

            $data = json_encode($item);
            File::put(storage_path('item.json'), $data);

            $updatedData = [
                "id" => $request->id,
                "name" => $request->name,
                "description" => $request->description

            ];

            return response(["status" => true, "data" => $updatedData, "message" => "item update successfully"], 200);
        } else {

            return response(["status" => false, "data" => null, "message" => "No task found"], 404);
        }
    }

    public function deleteItem(Request $request)
    {

        $validator = validator::make($request->all(), [
            "id" => "required"
        ]);

        if ($validator->fails()) {
            return response(['status' => false, "data" => null, "message" => "validation error", "error" => $validator->errors()], 400);
        }

        $content = File::get(storage_path('item.json'));
        $item = json_decode($content, 2);

        $index = array_search($request->id, array_column($item, 'id'));
        if ($index !== false) {
            $itemForDelete = $item[$index];
            unset($item[$index]);
            $item = array_values($item);

            $data = json_encode($item);
            File::put(storage_path('item.json'), $data);

            return response(["status" => true, "data" => $itemForDelete,  "message" => "item delete successfully"], 200);
        } else {
            return response(["status" => false, "data" => null, "message" => "No task found"], 404);
        }
    }
}
