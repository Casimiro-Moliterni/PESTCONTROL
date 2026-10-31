<?php
declare(strict_types=1);

namespace App\Core;

/** Front controller: crea il contesto, smista la richiesta, gestisce gli errori. */
final class App
{
    public function __construct(private array $config)
    {
    }

    public function run(): void
    {
        $ctx = new Context($this->config);
        $router = new Router();
        (require ROOT . '/config/routes.php')($router);

        try {
            $response = $router->dispatch($ctx);
        } catch (HttpException $e) {
            $response = $this->errorResponse($ctx, $e);
        } catch (\Throwable $e) {
            error_log((string) $e);
            $response = $this->errorResponse($ctx, new HttpException(500));
        }
        $response->send();
    }

    private function errorResponse(Context $ctx, HttpException $e): Response
    {
        if ($e->status === 401) {
            return Response::redirect('/app/login');
        }
        $messages = [
            403 => 'Non hai i permessi per questa operazione.',
            404 => 'Pagina non trovata.',
            419 => 'Sessione scaduta, ricarica la pagina e riprova.',
            500 => 'Errore interno. Riprova più tardi.',
        ];
        $ctx->view->share('flash', []);
        $html = $ctx->view->render(
            'errori/errore',
            ['codice' => $e->status, 'messaggio' => $messages[$e->status] ?? 'Errore.'],
            $ctx->auth->check() ? 'layout' : 'layout_pubblico'
        );
        return new Response($html, $e->status);
    }
}
