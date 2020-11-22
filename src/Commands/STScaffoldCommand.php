<?php

namespace ycamposde\ycscaffold\Commands;

use Illuminate\Console\Command;
use ycamposde\ycscaffold\DetectsApplicationNamespace;
use Illuminate\Filesystem\Filesystem;
use ycamposde\ycscaffold\Makes\MakeAudit;
use ycamposde\ycscaffold\Makes\MakeContract;
use ycamposde\ycscaffold\Makes\MakeController;
use ycamposde\ycscaffold\Makes\MakeControllerApi;
use ycamposde\ycscaffold\Makes\MakeCriteria;
use ycamposde\ycscaffold\Makes\MakeCriteriaInterface;
use ycamposde\ycscaffold\Makes\MakeEloquent;
use ycamposde\ycscaffold\Makes\MakeModel;
use ycamposde\ycscaffold\Makes\MakeRepository;
use ycamposde\ycscaffold\Makes\MakeRequest;
use ycamposde\ycscaffold\Makes\MakerTrait;
use ycamposde\ycscaffold\Makes\MakeService;
use ycamposde\ycscaffold\Makes\MakeUuids;

class STScaffoldCommand extends Command
{
  use DetectsApplicationNamespace, MakerTrait;
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'make:scaffold {name}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Command description';

  private $composer;

  private $meta;

  /**
   * Create a new command instance.
   *
   * @param Filesystem $files
   */
  public function __construct(Filesystem $files)
  {
    parent::__construct();

    $this->files = $files;
    $this->composer = app()['composer'];
  }

  /**
   * Execute the console command.
   *
   * @return void
   */
  public function handle()
  {
    $header = "scaffolding: {$this->getObjName("Name")}";
    $footer = str_pad('', strlen($header), '-');

    $dump = str_pad('>DUMP AUTOLOAD<', strlen($header), ' ', STR_PAD_BOTH);
    $this->line("\n----------- $header -----------\n");
    $this->makeMeta();
    //$this->makeMigration();
    //$this->makeSeed();
    $this->makeModel();
    //$this->makeController();
    $this->makeRequest();
    $this->makeControllerApi();
    $this->makeService();
    $this->makeRepository();
    $this->makeContract();
    $this->makeEloquent();
    $this->makeAudit();
    $this->makeUuid();
    $this->makeCriteria();
    $this->makeCriteriaInterface();
    // $this->makeLocalization(); //ToDo - implement in future version
    //$this->makeViews();
    //$this->makeViewLayout();
    $this->line("\n----------- $footer -----------");
    $this->comment("----------- $dump -----------");
    $this->composer->dumpAutoloads();

  }

  /**
   * Generate the desired migration.
   *
   * @return void
   */
  protected function makeMeta()
  {
    // ToDo - Verificar utilidade...
    $this->meta['action'] = 'create';
    $this->meta['var_name'] = $this->getObjName("name");
    $this->meta['table'] = $this->getObjName("names");//obsoleto
    //$this->meta['ui'] = $this->option('ui');
    $this->meta['namespace'] = $this->getAppNamespace();
    $this->meta['Model'] = $this->getObjName('Name');
    $this->meta['Models'] = $this->getObjName('Names');
    $this->meta['model'] = $this->getObjName('name');
    $this->meta['models'] = $this->getObjName('names');
    $this->meta['ModelMigration'] = "Create{$this->meta['Models']}Table";
    //$this->meta['schema'] = $this->option('schema');
    //$this->meta['prefix'] = ($prefix = $this->option('prefix')) ? "$prefix." : "";
  }

  /**
   * Make a Controller with default actions
   *
   * @return void
   */
  private function makeController()
  {
    new MakeController($this, $this->files);
  }

  private function makeModel()
  {
    new MakeModel($this, $this->files);
  }

  /**
   * Make a Controller with default actions
   *
   * @return void
   */
  private function makeControllerApi()
  {
    new MakeControllerApi($this, $this->files);
  }

  private function makeRequest()
  {
    new MakeRequest($this, $this->files);
  }

  private function makeService()
  {
    new MakeService($this, $this->files);
  }

  private function makeRepository() {
    new MakeRepository($this, $this->files);
  }

  private function makeContract() {
    new MakeContract($this, $this->files);
  }

  private function makeEloquent() {
    new MakeEloquent($this, $this->files);
  }

  private function makeAudit() {
    new MakeAudit($this, $this->files);
  }

  private function makeUuid() {
    new MakeUuids($this, $this->files);
  }

  private function makeCriteria() {
    new MakeCriteria($this, $this->files);
  }

  private function makeCriteriaInterface() {
    new MakeCriteriaInterface($this, $this->files);
  }

  /**
   * Get access to $meta array
   *
   * @return array
   */
  public function getMeta()
  {
    return $this->meta;
  }

  /**
   * Generate names
   *
   * @param string $config
   * @return mixed
   * @throws \Exception
   */
  public function getObjName($config = 'Name')
  {
    $names = [];
    $args_name = $this->argument('name');

    // Name[0] = Tweet
    $names['Name'] = ucfirst($args_name); //str_singular(ucfirst($args_name));
    // Name[1] = Tweets
    $names['Names'] = ucfirst($args_name) . 's'; //str_plural(ucfirst($args_name));
    // Name[2] = tweets
    $names['names'] = strtolower(preg_replace('/(?<!^)([A-Z])/', '_$1', $args_name)) . 's'; // str_plural(strtolower(preg_replace('/(?<!^)([A-Z])/', '_$1', $args_name)));
    // Name[3] = tweet
    $names['name'] = strtolower(preg_replace('/(?<!^)([A-Z])/', '_$1', $args_name)); //str_singular(strtolower(preg_replace('/(?<!^)([A-Z])/', '_$1', $args_name)));
    if (!isset($names[$config]))
    {
      throw new \Exception("Position name is not found");
    };

    return $names[$config];
  }

}
