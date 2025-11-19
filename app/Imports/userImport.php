<?php
namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Illuminate\Support\Facades\Hash;

class UserImport implements ToModel
{
    public function model(array $row)
    {
        // Skip header row
        if ($row[0] === 'Name' || $row[0] === 'name') {
            return null;
        }

           // --- Validate email format ---
           $email = trim($row[1]);
           if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
               \Log::warning("Skipped row: Invalid email format => {$email}");
               return null;
           }
   
           // --- Skip if email already exists ---
           if (User::where('email', $email)->exists()) {
               \Log::info("Skipped row: Duplicate email => {$email}");
               return null;
           }

           $mobile = trim($row[3]);
           if(!preg_match('/^\d{10}$/', $mobile)) {
            \Log::warning("Skipped row: Invalid email format => {$mobile}");
            return null;
           }

        $dob = null;
        
        if (!empty($row[4])) {
            try {
                // If date looks like "21-05-2000"
                $dob = Carbon::createFromFormat('d-m-Y', $row[4])->format('Y-m-d');
            } catch (\Exception $e) {
                // If Excel stores date as a number (like 36998)
                if (is_numeric($row[4])) {
                    $dob = ExcelDate::excelToDateTimeObject($row[4])->format('Y-m-d');
                }
            }
           
        }  else {
            \Log::warning("Skipped row: Invalid DOB => {$row[4]}");
            return null;
        }

        $pasword = trim($row[2]);

        if (strlen($password) < 6) {
            \Log::warning("Skipped row: Invalid password => {password}");
            return null;
        }

        return new User([
            'name' => $row[0],
            'email' => $row[1],
            'password' =>Hash::make($row[2]),
            'mobile' => $row[3],
            'dob' => $dob,
            'gender' => $row[5] ?? 'Not Specified',
            'status' => $row[6] ?? 'active',
        ]);
    }
}
