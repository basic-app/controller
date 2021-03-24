<?php
/**
 * @author Basic App Dev Team <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Controller;

use BasicApp\Action\ActionControllerTrait;

abstract class BaseController extends \CodeIgniter\Controller
{

    use ActionTrait;

    use ControllerTrait;

}