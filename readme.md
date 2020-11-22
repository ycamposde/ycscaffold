# Laravel 8.x Scaffold Generator CRUD - MVC
## Usage

### Step 1: Install Through Composer

```
composer require ycamposde/ycscaffold
```
##### add:
```
"required": {
    ...
    ...
    "webpatser/laravel-uuid": "^2.0"
}

```
### Step 2: Add the Service Provider

Open `config/app.php` and, to your **providers** array at the bottom, add:

```
ycamposde\ycscaffold\GeneratorsServiceProvider::class,
```

### Step 3: Run Artisan!

You're all set. Run `php artisan` from the console, and you'll see the new commands `make:scaffold`.

## Examples

Use this command to generator scaffolding of **Test** in your project:
```
php artisan make:scaffold Test
```

This command will generate:

```
app/Models/Test.php
app/Http/Controllers/Api/TestController.php
app/Http/Request/TestStore.php
app/Repositories/Test.php
app/Services/Test.php
```


##Collaborators
 [Yerson Campos](https://github.com/ycamposde "Yerson Campos")
