<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Zcode;
use App\Models\Settlement;

class csvParser extends Model
{
    use HasFactory;

    public static function parseCsv()
    {

        $files = scandir(public_path('csv'));
        foreach ($files as $file) {
            if (!is_dir($file)) {
                // Reading file
                $file = fopen(public_path('csv/' . $file), "r");
                $importData_arr = array(); // Read through the file and store the contents as an array
                $i = 0;
                //Read the contents of the uploaded file 
                while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
                    $num = count($filedata);
                    // Skip first row (Remove below comment if you want to skip the first row)
                    if ($i == 0) {
                        $i++;
                        continue;
                    }
                    for ($c = 0; $c < $num; $c++) {
                        $importData_arr[$i][] = $filedata[$c];
                    }
                    $i++;
                }

                fclose($file); //Close after reading

                $zcodes = [];
                $settlements = [];
                foreach ($importData_arr as $importData) {
                    // dd($importData);
                    //extract data for zCodes table
                    if (!empty(filter_var($importData[0], FILTER_SANITIZE_NUMBER_INT))) {
                        $temp_codes = [
                            'zip_code'     => filter_var($importData[0], FILTER_SANITIZE_NUMBER_INT),
                            'locality'     => mb_strtoupper(htmlspecialchars(utf8_encode($importData[5]))),
                            'state'        => mb_strtoupper(htmlspecialchars(utf8_encode($importData[4]))),
                            'state_code'   => $importData[7],
                            'zip_code_key' => !empty($importData[9]) ? filter_var($importData[9], FILTER_SANITIZE_NUMBER_INT) : null,
                            'municipality' => mb_strtoupper(htmlspecialchars(utf8_encode($importData[3]))),
                            'municipality_code' => $importData[11],
                        ];

                        $temp_settlements = [
                            'zip_code'     => filter_var($importData[0], FILTER_SANITIZE_NUMBER_INT),
                            'id_settlement'     => filter_var($importData[12], FILTER_SANITIZE_NUMBER_INT),
                            'settlement'        => mb_strtoupper(htmlspecialchars(utf8_encode($importData[1]))),
                            'zone_type'         => mb_strtoupper(htmlspecialchars(utf8_encode($importData[13]))),
                            'settlement_type' =>   mb_strtoupper(htmlspecialchars(utf8_encode($importData[2]))),
                        ];

                        $zcodes[$importData[0]] = $temp_codes;
                        array_push($settlements, $temp_settlements);
                    }
                }

                Zcode::insert($zcodes);
                Settlement::insert($settlements);
            }
        }
    }
}
