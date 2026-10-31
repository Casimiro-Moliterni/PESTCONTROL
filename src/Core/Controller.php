<?php
declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    public function __construct(protected Context $ctx)
    {
    }

    protected function render(string $template, array $data = [], string $layout = 'layout', int $status = 200): Response
    {
        $this->ctx->view->share('flash', $this->ctx->session->pullFlash());
        return new Response($this->ctx->view->render($template, $data, $layout), $status);
    }

    protected function redirect(string $path, ?string $type = null, ?string $message = null): Response
    {
        if ($type !== null && $message !== null) {
            $this->ctx->session->flash($type, $message);
        }
        return Response::redirect($path);
    }

    protected function aziendaId(): int
    {
        return $this->ctx->auth->aziendaId();
    }

    protected function stati(string $name): array
    {
        return $this->ctx->config['stati'][$name];
    }

    /** Regola "in:..." costruita dalle chiavi di una lista di stati configurata. */
    protected function inRule(string $statiName): string
    {
        return 'in:' . implode(',', array_keys($this->stati($statiName)));
    }

    protected function requirePermission(string $modulo, string $azione): void
    {
        if (!$this->ctx->auth->can($modulo, $azione)) {
            throw new HttpException(403);
        }
    }

    protected function notFound(): never
    {
        throw new HttpException(404);
    }
}
