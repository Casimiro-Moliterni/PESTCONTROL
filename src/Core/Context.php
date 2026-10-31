<?php
declare(strict_types=1);

namespace App\Core;

/** Contenitore dei servizi condivisi, passato ai controller. */
final class Context
{
    public readonly Request $request;
    public readonly Session $session;
    public readonly Csrf $csrf;
    public readonly Database $db;
    public readonly Auth $auth;
    public readonly AuditLog $audit;
    public readonly View $view;

    public function __construct(public readonly array $config)
    {
        $this->request = new Request();
        $this->session = new Session();
        $this->session->start();
        $this->csrf  = new Csrf($this->session);
        $this->db    = new Database($config['db']);
        $this->auth  = new Auth($this->db, $this->session);
        $this->audit = new AuditLog($this->db, $this->auth, $this->request);
        $this->view  = new View(ROOT . '/templates');
        $this->view->share('auth', $this->auth);
        $this->view->share('csrf', $this->csrf);
        $this->view->share('stati', $config['stati']);
        $this->view->share('appName', $config['app']['name']);
        $this->view->share('errors', []);
    }
}
