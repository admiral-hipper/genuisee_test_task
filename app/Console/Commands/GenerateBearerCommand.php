<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateBearerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:apikey';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user = User::factory(['name' => 'GraphQL User'])->create();

        $plain_text_key = Str::random(40);
        $user->tokens()->create([
            'name' => 'Fixed Token',
            'token' => hash('sha256', $plain_text_key),
            'abilities' => ['*'],
        ]);
        echo "API KEY - {$plain_text_key}\n";

        return Command::SUCCESS;
    }
}
