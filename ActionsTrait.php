<?php
/**
 * @author Basic App Dev Team <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Controller;

use Closure;
use CodeIgniter\Exceptions\PageNotFoundException;
use Webmozart\Assert\Assert;
use BasicApp\Action\ActionInterface;

trait ActionsTrait
{

    protected $actions = [];

    protected $defaultActions = [];

    protected $allowedActions;

    protected function remapAction($method, ...$params)
    {
        $actions = array_merge($this->defaultActions, $this->actions);

        if (array_key_exists($method, $actions) && $actions[$method])
        {
            if (!$this->isActionAllowed($method, $error))
            {
                throw PageNotFoundException::forPageNotFound($error ?? lang('Page not found.'));
            }

            if (is_array($actions[$method]))
            {
                $action = call_user_func_array([$this, 'createAction'], $actions[$method]);
            }
            else
            {
                $action = $this->createAction($actions[$method]);
            }

            $action->initialize($method);

            if (!$this->beforeAction($action, $error))
            {
                $this->throwSecurityException($error ?? lang('Access denied.'));
            }

            return $action->execute(...$params);
        }

        throw PageNotFoundException::forPageNotFound(lang('Page not found.'));        
    }

    protected function createAction(string $actionClass, array $params = [])
    {
        $action = new $actionClass($this, $params);

        $action->setLogger($this->logger);

        $action->setRequest($this->request);

        $action->setResponse($this->response);

        return $action;
    }

    protected function isActionAllowed(string $actionClass, &$error = null) : bool
    {
        if ($this->allowedActions !== null)
        {
            if (array_search($actionClass, $this->allowedActions) !== false)
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        return true;
    }

    protected function beforeAction(ActionInterface $action, &$error = null) : bool
    {
        return true;
    }

}