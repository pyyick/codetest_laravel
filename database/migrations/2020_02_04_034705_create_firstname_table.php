<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\firstName;

class CreateFirstnameTable extends Migration
{
    /**
     * Run the migrations.
     * Preliminary exercise: Creation of the database and import of data
     * @return void
     */
    public function up()
    {
        Schema::create('ref_firstnames', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('label', 255);
            $table->tinyInteger('type')->default(0);
            $table->tinyInteger('gender')->default(0);
            $table->string('origin', 255)->nullable();
        });
        //Get CSV and insert
        $csv = Storage::get("firstnames.csv");
        $rows= explode(PHP_EOL, $csv);
        //Skip first row
        $first_row = TRUE;
        Log::info("Import Started");
        DB::enableQueryLog();
        $count = 0;
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
            $firstName = new firstName();
            $firstName->label = mb_convert_encoding($record[0], "UTF-8");
            switch ($record[1]){
                case "M":
                    $firstName->type = 1;
                    $firstName->gender = 1;
                    break;
                case "F":
                    $firstName->type = 2;
                    $firstName->gender = 2;
                    break;
                case "M,F":
                    $firstName->type = 0;
                    $firstName->gender = 1;
                    break;
                case "F,M":
                    $firstName->type = 0;
                    $firstName->gender = 2;
                    break;
            }
            $firstName->origin = $record[2];
            $firstName->save();
            Log::info($this->getQuery(DB::getQueryLog(), $count));
            $count++;
        }
        Log::info("Import Ended");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('firstname');
    }

    private function getQuery($sql, $count = 0){
        $query = str_replace(array('?'), array('\'%s\''), $sql[$count]["query"]);
        $query = vsprintf($query, $sql[$count]["bindings"]);
        return $query;
    }
}
