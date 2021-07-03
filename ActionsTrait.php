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
            if (is_array($actions[$method]))
            {
                return call_user_func_array([$this, 'runAction'], $actions[$method]);
            }
            else
            {
                return $this->runAction($actions[$method]);
            }
        }

        throw PageNotFoundException::forPageNotFound(lang('Page not found.'));        
    }

    protected function runAction(string $actionClass, string $method = null, ...$params)
    {
        if (!$this->isActionAllowed($method, $error))
        {
            throw PageNotFoundException::forPageNotFound($error ?? lang('Page not found.'));
        }

        $action = new $actionClass($this, $params);

        $return = $action->run($method, ...$params);

        if ($return instanceof Closure)
        {
            Assert::notEmpty($return->bindTo($this, $this), lang('Bind failed.'));

            return $return($method, ...$params);
        }

        return $return;
    }

    protected function isActionAllowed(string $action, &$error = null) : bool
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