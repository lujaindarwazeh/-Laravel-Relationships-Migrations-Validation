<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\Log;


use Illuminate\Console\Command;
use App\Models\Student;
use Illuminate\Support\Facades\Mail;
use App\Mail\LateEnterNotification;
use Carbon\Carbon;
use App\Mail\LateEnterMail;

class checkstudententertime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:late-entry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if students entered more than 1 hour late and send email';


    /**
     * Execute the console command.
     *
     * @return int
     */

public function handle()
{
    
    $today = now()->toDateString(); 
   

    $expectedTime = Carbon::createFromFormat('Y-m-d H:i:s', $today . ' 08:00:00');
   

    $lateStudents = [];

    $students = Student::whereNotNull('enter_date')->get();
  

    foreach ($students as $student) {
        $enterDateTime = Carbon::parse($student->enter_date); 
       

        if ($enterDateTime->toDateString() === $today) {
            if ($enterDateTime->greaterThan($expectedTime->copy()->addHour())) {
                $lateStudents[] = $student;
                Log::info("Student {$student->email} is late, added to late list.");
            } else {
                Log::info("Student {$student->email} is on time.");
            }
        } else {
            Log::info("Student {$student->email} enter_date is not today.");
        }
    }

    if (!empty($lateStudents)) {
        Mail::to("lujain.darwazeh123@gmail.com")->send(new LateEnterMail($lateStudents));
        $this->info("📧 One email sent with all late students.");
    } else {
      
        $this->line("✅ No late students today.");
    }

   
    return Command::SUCCESS;
}



}
