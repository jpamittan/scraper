<?php

namespace App\Jobs;

use App\Http\Service\ScrapeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ScrapeQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $listname;
    protected $searchKeys;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $data, object $listname, array $searchKeys)
    {
        $this->data = $data;
        $this->listname = $listname;
        $this->searchKeys = $searchKeys;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new ScrapeService())->google(
            $this->data,
            $this->listname,
            $this->searchKeys
        );
    }
}
