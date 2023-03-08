<?php
 
namespace Kooriv\MessageBroker\Commands;

use Kooriv\MessageBroker\Contract\AMQP;
use Illuminate\Console\Command;
 
class Consume extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consume';
 
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume messages';
 
    /**
     * Execute the console command.
     *
     * @param  \App\Support\DripEmailer  $drip
     * @return mixed
     */
    public function handle()
    {
        /** @var \Kooriv\MessageBroker\Contract\AMQP $amqp */
        $amqp = app(AMQP::class);
        $amqp->consumer()->handle();
    }
}