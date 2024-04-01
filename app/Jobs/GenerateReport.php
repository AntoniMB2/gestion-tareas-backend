<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use App\Models\Task;
use Barryvdh\DomPDF\Facade as PDF;
class GenerateReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function handle()
    {
        {
            $tasks = Task::whereBetween('completed_at', [new Carbon($this->startDate), new Carbon($this->endDate)])->get();
    
            // Genera el PDF
            $pdf = PDF::loadView('report', ['tasks' => $tasks]);
            $pdf->save(storage_path('app/public/reports/report.pdf'));
        }
    }

}