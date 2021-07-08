<?php
/**
 * @author Basic App Dev Team <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Controller;

use Exception;
use Closure;
use CodeIgniter\Exceptions\PageNotFoundException;
use Webmozart\Assert\Assert;
use BasicApp\Action\ActionInterface;

trait ActionsTrait
{

    protected $actions = [];

    protected $defaultActions = [];

    protected $allowedActions;

    protected $_actions = [];

    protected function remapAction($method, ...$params)
    {
        $action = $this->getAction($method);

        if (!$this->beforeAction($method, $error))
        {
            $this->throwSecurityException($error ?? lang('Access denied.'));
        }

        return $action->execute(...$params);
    }

    protected function getActionOrFail($method, $error = null) : ActionInterface
    {
        if (array_key_exists($method, $this->_actions))
        {
            return $this->_actions[$method];
        }

        throw PageNotFoundException::forPageNotFound($error ?? lang('Page not found.'));
    }

    protected function getAction(string $method, array $params = []) : ActionInterface
    {
        $actions = array_merge($this->defaultActions, $this->actions);
        
        if (!array_key_exists($method, $actions) || !$actions[$method])
        {
            throw PageNotFoundException::forPageNotFound(lang('Page not found.'));
        }

        if (!$this->isActionAllowed($method, $error))
        {
            throw PageNotFoundException::forPageNotFound($error ?? lang('Page not found.'));
        }

        if (is_array($actions[$method]))
        {
            Assert::arrayHasKey('class', $actions[$method]);

            $class = $actions[$method]['class'];

            unset($actions[$method]['class']);

            $params = array_merge($actions[$method], $params);

            $this->_actions[$method] = call_user_func_array([$this, 'createAction'], [$class, $params]);
        }
        else
        {
            $this->_actions[$method] = $this->createAction($actions[$method], $params);
        }

        /*
        if (!$this->beforeAction($method, $error))
        {
            $this->throwSecurityException($error ?? lang('Access denied.'));
        }
        */
        
        return $this->_actions[$method];
    }

    protected function createAction(string $actionClass, array $params = [])
    {
        $action = new $actionClass($this, $params);

        $action->setLogger($this->logger);

        $action->setRequest($this->request);

        $action->setResponse($this->response);

        $action->initialize();

        return $action;
    }

    protected function isActionAllowed(string $method, &$error = null) : bool
    {
        if ($this->allowedActions !== null)
        {
            if (array_search($method, $this->allowedActions) !== false)
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

    protected function beforeAction(string $action, &$error = null) : bool
    {
        return true;
    }

}