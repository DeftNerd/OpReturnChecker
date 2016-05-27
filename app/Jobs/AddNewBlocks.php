<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Blocks;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;

class AddNewBlocks extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {


        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', 'http://btc.blockr.io/api/v1/coin/info');
        $last_block = json_decode($res->getBody())->data->last_block->nb;

        $start_timestamp = Carbon::now()->subDays(30)->timestamp;
        $res = $client->request('GET', 'http://btc.blockr.io/api/v1/block/info/first?after=' . $start_timestamp);
        $start_block = json_decode($res->getBody())->data->nb;

        $last_processed = Blocks::max('height') ?: $start_block;

        echo "Starting Block:       " . $start_block . PHP_EOL;
        echo "Last processed block: " . $last_processed . PHP_EOL;
        echo "Last mined block:     " . $last_block . PHP_EOL;
        echo "Blocks to process:    " . $last_block - $last_processed . PHP_EOL;

        while ($last_processed <= $last_block) {
          $check = "";
          // FIXME This next range thing doesn't take into account the fact that $last_processed + 10 might be higher than $last_block
          foreach (range($last_processed + 1, $last_processed + 10) as $number) {
            $check .= $number . ",";
          }
          $res = $client->request('GET', 'http://btc.blockr.io/api/v1/block/info/' . $check);
          $blocks = json_decode($res->getBody())->data;
          foreach($blocks as $block) {
            $new = new Blocks;
            $new->height = $block->nb;
            $new->hash = $block->hash;
            $new->version = $block->version;
            $new->nb_txs = $block->nb_txs;
            $new->merkleroot = $block->merkleroot;
            $new->fee = $block->fee * 100000000;
            $new->vout_sum = $block->vout_sum * 100000000;
            $new->size = $block->size;
            $new->difficulty = $block->difficulty;
            $new->days_destroyed = $block->days_destroyed;
            $new->time_utc = \Carbon\Carbon::parse($block->time_utc);
            $new->save();
            echo "Saved block #" . $block->nb . PHP_EOL;
            $last_processed++;
          }

        }
    }
}
