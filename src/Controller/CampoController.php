<?php
declare(strict_types=1);
namespace App\Controller;
use App\Core\Controller;use App\Core\Response;use App\Repository\InterventoRepository;
final class CampoController extends Controller
{
 public function index(): Response{$interventi=(new InterventoRepository($this->ctx->db,$this->aziendaId()))->search(['operatore_id'=>$this->ctx->auth->id()]);return $this->render('campo/index',compact('interventi'));}
}
