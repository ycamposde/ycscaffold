<?php namespace ycamposde\ycscaffold\Makes;

use ycamposde\ycscaffold\DetectsApplicationNamespace;
use Illuminate\Filesystem\Filesystem;
use ycamposde\ycscaffold\Commands\STScaffoldCommand;

class MakeControllerApi {
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
    $name = $this->scaffoldCommandObj->getObjName('Name') . 'Controller';
    $path = $this->getPath($name, 'controller-api');
    if ($this->files->exists($path))
    {
      return $this->scaffoldCommandObj->comment("x $name");
    }
    $this->makeDirectory($path);
    $this->files->put($path, $this->compileControllerStub());
    $this->scaffoldCommandObj->info('+ Controller API');
  }

  /**
   * Compile the controller stub.
   *
   * @return string
   */
  protected function compileControllerStub()
  {
    $stub = $this->files->get(substr(__DIR__,0, -5) . 'Stubs/controller-api.stub');
    $this->buildStub($this->scaffoldCommandObj->getMeta(), $stub);
    // $this->replaceValidator($stub);
    return $stub;
  }

}
