<?php
/**
 * @author Basic App Dev Team <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Controller;

trait RedirectBackTrait
{

    public function redirectBack(string $defaultUrl = null)
    {
        $url = $defaultUrl;

        // @todo get returnUrl from GET

        if (!$url)
        {
            $url = base_url();
        }

        return redirect()->withCookies()->to($url);
    }

}