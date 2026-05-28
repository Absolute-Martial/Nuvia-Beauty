<?php

namespace App\Domains\Beauty\Jobs;

use App\Domains\Beauty\Services\PerfectCorpTaskService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreatePerfectCorpAnalysisTask implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public int $taskId,
    ) {
    }

    public function handle(PerfectCorpTaskService $tasks): void
    {
        $tasks->processCreateTask($this->taskId);
    }
}
