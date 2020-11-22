<?php namespace ycamposde\ycscaffold\Makes;

use ycamposde\ycscaffold\DetectsApplicationNamespace;
use Illuminate\Filesystem\Filesystem;
use ycamposde\ycscaffold\Commands\;

class MakeEloquent {
  use DetectsApplicationNamespace, MakerTrait;

  protected $scaffoldCommandObj;

  function __construct( $scaffoldCommand, Filesystem $files)
  {
    $this->files = $files;
    $this->scaffoldCommandObj = $scaffoldCommand;

    $this->start();
  }

  /**
   * Start make controller.
   *
   * @return void
   */
  private function start()
  {
    $name = 'Repository';
    $path = $this->getPath($name, 'eloquent');
    if ($this->files->exists($path))
    {
      return $this->scaffoldCommandObj->comment("x Eloquent $name");
    }
    $this->makeDirectory($path);
    $this->files->put($path, $this->compileServiceStub());
    $this->scaffoldCommandObj->info('+ Eloquent Repository');
  }

  /**
   * Compile the controller stub.
   *
   * @return string
   */
  protected function compileServiceStub()
  {
    $stub = $this->files->get(substr(__DIR__,0, -5) . 'Stubs/eloquent-repository.stub');
    $this->buildStub($this->scaffoldCommandObj->getMeta(), $stub);
    // $this->replaceValidator($stub);
    return $stub;
  }

}
