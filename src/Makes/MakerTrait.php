<?php namespace ycamposde\ycscaffold\Makes;

use Illuminate\Contracts\Filesystem\Filesystem;
use ycamposde\ycscaffold\Commands\STScaffoldCommand;

trait MakerTrait {
  protected $files;
  protected $scaffoldCommandM;

  public function __construct(STScaffoldCommand $scaffoldCommand, Filesystem $files)
  {
    $this->files = $files;
    $this->scaffoldCommandM = $scaffoldCommand;

    $this->generateNames($this->scaffoldCommandM);
  }

  protected function getArrayRecursive(array $array, $parent = '') {
    $data = [];
    foreach ($array as $key => $value) {
      if(gettype($value) == 'array') {
        array_merge(
            $data,
            $this->getArrayRecursive($value, "$parent")
        );
        continue;
      }
      $data["$parent.$key"] = $value;
    }

    return $data;
  }

  protected function getFilesRecursive($path) {
    $files = [];
    $scan = array_diff(scandir($path), ['.', '..']);
    foreach ($scan as $file) {
      $file = realpath("$path$file");
      if(is_dir($file)) {
        $files = array_merge
        (
            $files,
            $this->getFilesRecursive($file.DIRECTORY_SEPARATOR)
        );
        continue;
      }
      $files[] = $file;
    }
    return $files;
  }

  /**
   * Get stub path.
   * @return string
   * @internal param $file_name
   * @internal param string $path
   */
  protected function getStubPath()
  {
    return substr(__DIR__,0, -5) . 'Stubs/';
  }

  /**
   * Get fields stubs.
   *
   * @param $ui
   * @param $type
   * @return array fields
   */
  protected function getStubFields($ui, $type)
  {
    $stubsFieldsPath= $this->getStubPath()."views/$ui/fields/$type/";
    if($this->existsDirectory($stubsFieldsPath))
    {
      $this->scaffoldCommandM->error('Stub not found');
      return;
    }

    $stubsFieldsFiles = $this->getFilesRecursive($stubsFieldsPath);
    $stubs = [];
    foreach ($stubsFieldsFiles as $file)
    {
      $stubs[str_replace($stubsFieldsPath, '', $file)] = $this->getFile($file);
    }
    return $stubs;
  }

  /**
   * Get views stubs.
   *
   * @param $ui
   * @return array views
   */
  protected function getStubViews($ui)
  {
    $viewsPath = $this->getStubPath()."views/$ui/pages/";
    $files = $this->getFilesRecursive($viewsPath);
    $viewFiles = [];
    foreach ($files as $file)
    {
      $viewFiles[str_replace($viewsPath, '', $file)] = $file;
    }
    return $viewFiles;
  }

  protected function getDestinationViews($model)
  {
    return "./resources/views/$model/";
  }

  /**
   * Build file replacing metas in template.
   *
   * @param array $metas
   * @param string &$template
   * @return mixed|string
   */
  protected function buildStub(array $metas, &$template)
  {
    foreach($metas as $k => $v)
    {
      $template = str_replace("{{". $k ."}}", $v, $template);
    }
    return $template;
  }

  /**
   * Get the path to where we should store the controller.
   *
   * @param $file_name
   * @param string $path
   * @return string
   */
  protected function getPath($file_name, $path = 'controller')
  {
    if($path == "controller") {
      return "./app/Http/Controllers/$file_name.php";
    }
    else if ($path == 'controller-api') {
      return "./app/Http/Controllers/Api/$file_name.php";
    }
    elseif($path == "model") {
      return "./app/Models/$file_name.php";
    }
    elseif($path == "service") {
      return "./app/Services/$file_name.php";
    }
    elseif($path == "repository") {
      return "./app/Repositories/$file_name.php";
    }
    elseif($path == "contract") {
      return "./app/Repositories/Contracts/$file_name.php";
    }
    elseif($path == "eloquent") {
      return "./app/Repositories/Eloquent/$file_name.php";
    }
    elseif($path == "criteria") {
      return "./app/Repositories/Criteria/$file_name.php";
    }
    elseif($path == "criteria-interface") {
      return "./app/Repositories/Criteria/$file_name.php";
    }
    elseif($path == "audit") {
      return "./app/Traits/$file_name.php";
    }
    elseif($path == "uuid") {
      return "./app/Traits/$file_name.php";
    }
    elseif($path == "request") {
      return "./app/Http/Requests/Store$file_name.php";
    }
    elseif($path == "seed") {
      return "./database/seeds/$file_name.php";
    }
    elseif($path == "view-index") {
      return "./resources/views/$file_name/index.blade.php";
    }
    elseif($path == "view-edit") {
      return "./resources/views/$file_name/edit.blade.php";
    }
    elseif($path == "view-show") {
      return "./resources/views/$file_name/show.blade.php";
    }
    elseif($path == "view-create") {
      return "./resources/views/$file_name/create.blade.php";
    }
    elseif($path == "localization") {
      return "./resources/lang/$file_name.php";
    }
    elseif($path == "route-web") {
      return './routes/web.php';
    }
    elseif($path == "route-api") {
      return './routes/api.php';
    }

    return;
  }

  protected function getFile($file) {
    return $this->files->get($file);
  }

  protected function existsDirectory($path) {
    return !$this->files->isDirectory($path);
  }

  /**
   * Build the directory for the class if necessary.
   *
   * @param  string  $path
   * @return string
   */
  protected function makeDirectory($path)
  {
    if (!$this->files->isDirectory(dirname($path))) {
      $this->files->makeDirectory(dirname($path), 0777, true, true);
    }
  }

}
