<?php namespace ycamposde\ycscaffold\Makes;

use ycamposde\ycscaffold\DetectsApplicationNamespace;
use Illuminate\Filesystem\Filesystem;
use ycamposde\ycscaffold\Commands\STScaffoldCommand;

class MakeCriteriaInterface {
  use DetectsApplicationNamespace, MakerTrait;

  protected $scaffoldCommandObj;

  function __construct(STScaffoldCommand $scaffoldCommand, Filesystem $files)
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
    $name = 'CriteriaInterface';
    $path = $this->getPath($name, 'criteria-interface');
    if ($this->files->exists($path))
    {
      return $this->scaffoldCommandObj->comment("x $name");
    }
    $this->makeDirectory($path);
    $this->files->put($path, $this->compileServiceStub());
    $this->scaffoldCommandObj->info('+ Criteria Interface');
  }

  /**
   * Compile the controller stub.
   *
   * @return string
   */
  protected function compileServiceStub()
  {
    $stub = $this->files->get(substr(__DIR__,0, -5) . 'Stubs/criteria-interface.stub');
    $this->buildStub($this->scaffoldCommandObj->getMeta(), $stub);
    // $this->replaceValidator($stub);
    return $stub;
  }

}
