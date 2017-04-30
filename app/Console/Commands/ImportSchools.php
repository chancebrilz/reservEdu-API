<?php

namespace App\Console\Commands;

use App\School;
use Illuminate\Console\Command;

class ImportSchools extends Command {

    protected $signature = 'import:schools {filename}';

    protected $description = 'Import school data from a csv file.';

    protected $geocodeURL = "http://maps.googleapis.com/maps/api/geocode/json?address=";

    public function __construct() {
        parent::__construct();
    }

    public function handle() {

        ini_set('auto_detect_line_endings',TRUE);

        $file = $this->argument('filename');

        $bar = $this->output->createProgressBar();

        if(file_exists($file) && is_readable($file)) {

            $header = null;
            $db_data = [];
            $handle = fopen($file, 'r');
            $rows = 0;
            $i = 0;

            while (($row = fgetcsv($handle, 0, ",")) !== FALSE) {
                if($header == null) {
                    $header = $row;
                } else {
                    $data = array_combine($header, $row);
                    $db_data[$i] = [
                        'nces_id' => $data["NCES School ID"],
                        'name' => $data["School Name"],
                        'district' => $data["District"],
                        'address' => $data["Street Address"] . " " . $data["City"] . ", " . $data["State"] . " " . $data["ZIP"],
                        'phone' => $data["Phone"],
                        'email' => (isset($data["Email"]) ? $data["Email"] : $data["NCES School ID"]."@ed.gov"),
                        'code' => str_random(10),
                        'activated' => false
                    ];

                    $address = json_decode(file_get_contents($this->geocodeUrl($db_data[$i]['address']), false), true)['results'];

                    if(isset($address[0])) {
                        $db_data[$i]['lat'] = $address[0]['geometry']['location']['lat'];
                        $db_data[$i]['lng'] = $address[0]['geometry']['location']['lng'];
                    } else {
                        $db_data[$i]['lat'] = 0;
                        $db_data[$i]['lng'] = 0;
                    }

                    $i++;
                }
                $bar->advance();
            }

            fclose($handle);

            School::insert($db_data);

            $bar->finish();

            $this->info("\n\n" . count($db_data) . " schools have been imported!");

        } else {
            $this->error($file . " does not exist!");
        }

    }

    public function geocodeUrl($address) {
        return ($this->geocodeURL . urlencode($address));
    }

}
