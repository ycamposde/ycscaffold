<?php namespace ycamposde\ycscaffold\Makes;

use ycamposde\ycscaffold\DetectsApplicationNamespace;
use Illuminate\Filesystem\Filesystem;
use ycamposde\ycscaffold\Commands\STScaffoldCommand;

class MakeRouteApi {
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
    $name = $this->scaffoldCommandObj->getObjName('Name') . '';
    $path = $this->getPath($name, 'route-api');
    if ($this->files->exists($path))
    {
      return $this->scaffoldCommandObj->comment("x Request: $name");
    }
    $this->makeDirectory($path);
    $this->files->put($path, $this->compileRequestStub());
    $this->scaffoldCommandObj->info('+ route api ');
  }

  /**
   * Compile the controller stub.
   *
   * @return string
   */
  protected function compileRequestStub()
  {
    $stub = $this->files->get(substr(__DIR__,0, -5) . 'Stubs/api.stub');
    $this->buildStub($this->scaffoldCommandObj->getMeta(), $stub);
    // $this->replaceValidator($stub);
    return $stub;
  }

}
