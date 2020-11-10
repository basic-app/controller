<?php
/**
 * @author Basic App Dev Team <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Controller;

trait ControllerTrait
{

    protected $viewsNamespace;

    protected function getViewsNamespace() : ?string
    {
        return $this->viewsNamespace;
    }

    protected function render(string $view, array $params = []) : string
    {
        $namespace = $this->getViewsNamespace();

        return view($namespace ?  $namespace . "\\" . $view : $view, $params);
    }

    public function redirect(string $url)
    {
        return redirect()->withCookies()->to($url);
    }

    public function redirectBack(string $defaultUri = null, array $params = [])
    {
        $uri = $this->request->getGet('backUrl');

        if (!$uri)
        {
            $uri = $defaultUri;
        }

        helper(['url']);

        if (!$uri)
        {
            $url = base_url();
        }
        else
        {
            $url = site_url($uri);
        }

        return $this->redirect($url);
    }

}