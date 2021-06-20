<?php
/**
 * @author Basic App Dev Team <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Controller;

use Closure;
use CodeIgniter\Exceptions\PageNotFoundException;
use BasicApp\Action\ActionInterface;

trait ActionsTrait
{

    protected $actions = [];

    protected $defaultActions = [];

    protected $allowedActions;

    protected function getActions() : array
    {
        return array_merge($this->defaultActions, $this->actions);
    }

    protected function remapAction($method, ...$params)
    {
        $actions = $this->getActions();

        if (array_key_exists($method, $actions) && $actions[$method] && $this->isActionAllowed($method))
        {
            if (is_array($actions[$method]))
            {
                $action = call_user_func_array([$this, 'createAction'], $actions[$method]);
            }
            else
            {
                $action = $this->createAction($actions[$method]);
            }

            return $action->execute($method, $params);
        }

        throw PageNotFoundException::forPageNotFound();        
    }

    protected function createAction(string $actionClass, array $params = []) : ActionInterface
    {
        $action = new $actionClass($this, $params);

        return $action;
    }

    protected function isActionAllowed(string $action) : bool
    {
        if ($this->allowedActions !== null)
        {
            if (array_search($action, $this->allowedActions) !== false)
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

}