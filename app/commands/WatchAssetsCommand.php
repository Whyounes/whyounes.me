<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class WatchAssetsCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'assets:watch';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Watch assets and build on modify.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$watcher = $this->laravel['watcher'];
		$path = app_path().'/assets';
		$listener = $watcher->watch($path);
		$console = $this;
		$listener->onModify(function($resource) {
			$this->info("{$resource->getPath()} has been modified." . PHP_EOL);
			$this->comment("Rebuilding assets...");
			try {
				$this->call('basset:build', array('--production' => 'true'));
				$this->info(PHP_EOL . "Build complete.");
			} catch (Exception $e) {
				$this->error($e->getMessage());
				$this->error("Build failed!");
			}
			$this->comment(PHP_EOL . "Watching assets...");
		});
		$this->comment(PHP_EOL . "Watching assets...");
		$watcher->startWatch();
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			// array('example', InputArgument::REQUIRED, 'An example argument.'),
			);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			// array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
			);
	}

}