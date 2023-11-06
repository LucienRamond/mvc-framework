<?php declare(strict_types = 1);

namespace Lram\Foundation;
use Lram\Foundation\Router\Router;
use Lram\Foundation\Exceptions\HttpException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Illuminate\Database\Capsule\Manager as Capsule;

class App {
    protected Router $router;

    public function __construct(){
        $this->initDotenv();
        if(Config::get('app.env') === 'production'){
            $this->initProductionExceptionHandler();
        }
        $this->initSession();
        $this->initDatabase();
        $this->router = new Router(require ROOT.'/app/routes.php');
    }

    protected function initDotenv(): void{
        $dotenv = \Dotenv\Dotenv::createImmutable(ROOT);
        $dotenv->safeLoad();
    }

    protected function initProductionExceptionHandler(): void
    {
        set_exception_handler(
            fn () => HttpException::render(500, 'Huston, on a un problème !')
        );
    }

    protected function initSession(): void
    {
        Session::init();
        Session::add('_token', Session::get('_token') ?? $this->generateCsrfToken());
    }

    protected function generateCsrfToken(): string
    {
        $length = Config::get('hashing.csrf_token_length');
        $token = bin2hex(random_bytes($length));
        return $token;
    }

    public function render(): void
    {
        $this->router->getInstance();
        Session::resetFlash();
    }

    public function getGenerator(): UrlGenerator
    {
        return $this->router->GetGenerator();
    }

    protected function initDatabase(): void
    {
        date_default_timezone_set(Config::get('app.timezone'));
        $capsule = new Capsule();
        $capsule->addConnection([
            'driver' => Config::get('database.driver'),
            'host' => Config::get('database.host'),
            'database' => Config::get('database.name'),
            'username' => Config::get('database.username'),
            'password' => Config::get('database.password')
        ]);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}