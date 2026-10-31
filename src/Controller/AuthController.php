<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\Controller;
use App\Core\Response;

final class AuthController extends Controller
{
    public function loginForm(): Response
    {
        if ($this->ctx->auth->check()) {
            return $this->redirect('/app');
        }
        return $this->render('auth/login', ['email' => ''], 'layout_pubblico');
    }

    public function login(): Response
    {
        $input = $this->ctx->request->only(['email', 'password']);
        if ($this->ctx->auth->attempt($input['email'], $input['password'])) {
            $this->ctx->audit->record('login', 'utente', $this->ctx->auth->id());
            return $this->redirect('/app');
        }
        $this->ctx->session->flash('error', 'Credenziali non valide.');
        return $this->render('auth/login', ['email' => $input['email']], 'layout_pubblico', 401);
    }

    public function logout(): Response
    {
        $this->ctx->auth->logout();
        return $this->redirect('/app/login');
    }
}
