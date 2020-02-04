<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\FirstName;

class FileController extends Controller
{
    //
    public function submit(Request $request){
        //Validate
        if ($request->file->getClientOriginalExtension() !== "csv"){
            return response()->json(["error" => "Wrong file format"], 400);
        }
        //Upload file
        $fileName = "a-treaty-" . time() . '.' . $request->file->getClientOriginalExtension();
        $request->file->move(storage_path("app"), $fileName);
        //Process file
        $csv = Storage::get($fileName);
        $rows= explode(PHP_EOL, $csv);
        //Skip first row
        $first_row = TRUE;
        $output = [];
        //Init output file
        $newFilename = "a-treaty.csv";
        $firstColumn = [
            "id",
            "firstname",
            "lastname",
            "new_firstname",
            "new_lastname",
            "name"
        ];
        Storage::disk('local')->put($newFilename, implode(',', $firstColumn));
        foreach ($rows as $row)
        {
            if ($first_row){
                $first_row = FALSE;
                continue;
            }
            $record = str_getcsv($row, ";");
            //Check null
            if (empty($record[0]))
                continue;
            //Check column
            if (count($record) !== 3)
                return response()->json(["error" => "Wrong file format"], 400);
            //Assign first 3 rows
            $output["id"] = $record[0];
            $firstName = mb_convert_encoding($record[1], "UTF-8");
            $lastName = mb_convert_encoding($record[2], "UTF-8");
            $output["firstname"] = $firstName;
            $output["lastname"] = $lastName;
            //Find first name
            $civilite = "";
            $newFirstName = mb_convert_encoding($record[1], "UTF-8");
            $newLastName = mb_convert_encoding($record[2], "UTF-8");
            $firstNameResult = FirstName::where("label", "=", $firstName);
            if ($firstNameResult->count() > 0){
                $result = $firstNameResult->first();
                $civilite = $this->nameHandler($result);
                $newFirstName = "$civilite $newFirstName";
            } else {
                //Find last name
                $lastNameResult = FirstName::where("label", "=", $lastName);
                if ($lastNameResult->count() > 0){
                    $result = $lastNameResult->first();
                    $civilite = $this->nameHandler($result);
                    $newFirstName = "$civilite $lastName";
                    $newLastName = $firstName;
                }
            }
            //Compose the result
            $name = "$newFirstName $newLastName";
            $output["new_firstname"] = $newFirstName;
            $output["new_lastname"] = $newLastName;
            $output["name"] = $name;
            Storage::disk('local')->append($newFilename, implode(',', $output));
            //Reset value
            $output = [];
        }
        //return
        return response()->download(storage_path("app/") . $newFilename, $newFilename, [
            "Content-Type" => "text/csv",
        ])->deleteFileAfterSend();
    }

    private function nameHandler($result){
        switch ($result["gender"]){
            case 1:
                return "Mr.";
            case 2:
                return "Ms.";
        }
        return false;
    }
}
