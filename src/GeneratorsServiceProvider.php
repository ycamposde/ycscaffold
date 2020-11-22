<?php namespace ycamposde\ycscaffold;

use Illuminate\Support\ServiceProvider;

class GeneratorsServiceProvider extends ServiceProvider {

  /**
   * Bootstrap the application services.
   *
   * @return void
   */
  public function boot() {
    //
  }

  /**
   * Register the application services.
   *
   * @return void
   */
  public function register() {
    $this->registerScaffoldGenerator();
  }

  /**
   * Register the make:scaffold generator.
   *
   * @return void
   */
  private function registerScaffoldGenerator() {
    $this->app->singleton('command.ycamposde.scaffold', function ($app) {
      return $app['ycamposde\ycscaffold\Commands\STScaffoldCommand'];
    });
    $this->commands('command.ycamposde.scaffold');
  }

}
