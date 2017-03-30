<?php

namespace App\Console\Commands;

use App\School;
use Illuminate\Console\Command;

class ImportSchools extends Command {

    protected $signature = 'import:schools {filename}';

    protected $description = 'Import school data from a csv file.';

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

            while (($row = fgetcsv($handle, 0, ",")) !== FALSE) {
                if($header == null) {
                    $header = $row;
                } else {
                    $data = array_combine($header, $row);
                    $db_data[] = [
                        'nces_id' => $data["NCES School ID"],
                        'name' => $data["School Name"],
                        'district' => $data["District"],
                        'address' => $data["Street Address"] . " " . $data["City"] . ", " . $data["State"] . " " . $data["ZIP"],
                        'phone' => $data["Phone"],
                        'email' => (isset($data["Email"]) ? $data["Email"] : $data["NCES School ID"]."@ed.gov"),
                        'code' => str_random(10),
                        'activated' => false
                    ];
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

}
