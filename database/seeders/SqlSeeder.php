<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SqlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks to prevent ordering issues during import
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // List of sql files to run in order
        $sqlFiles = [
            'departement.sql',
            'classroom.sql',
            'subject.sql',
            'student.sql',
        ];

        foreach ($sqlFiles as $fileName) {
            $path = base_path('sql/' . $fileName);
            if (File::exists($path)) {
                $this->command->info("Seeding from SQL file: {$fileName}");
                $sql = File::get($path);
                
                // Execute the SQL statements
                DB::unprepared($sql);
            } else {
                $this->command->error("SQL file not found: {$fileName}");
            }
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
