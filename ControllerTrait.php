<?php
/**
 * @author Basic App Dev Team <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Controller;

use CodeIgniter\Security\Exceptions\SecurityException;
use CodeIgniter\Exceptions\PageNotFoundException;

trait ControllerTrait
{

    protected $viewsNamespace;

    protected $viewsPath;

    protected function render(string $view, array $params = []) : string
    {
        $view = $this->viewsPath ? $this->viewsPath . '/' . $view : $view;

        $view = $this->viewsNamespace ? $this->viewsNamespace . "\\" . $view : $view;

        return view($view, $params);
    }    

    public function redirectBack(string $defaultUrl = null)
    {
        $backUrl = $this->request->getGet('backUrl');

        if ($backUrl)
        {
            return redirect()->withCookies()->to($backUrl);
        }

        if ($defaultUrl)
        {
            return $this->redirect()->withCookies()->to($defaultUrl);
        }
    
        return redirect()->withCookies()->back();
    }

    public function throwSecurityException(?string $message = null)
    {
        throw new SecurityException($message ?? 'Forbidden', 403);
    }

    public function throwPageNotFoundException(?string $message = null)
    {
        throw PageNotFoundException::forPageNotFound($message);
    }

}