<?php


namespace iMi\LaravelTransSid;

use Illuminate\Routing\UrlGenerator;

class UrlGeneratorService extends UrlGenerator
{
    public function addSid($url) {
        if (strpos($url, \Config::get('session.cookie')) !== false) {
            return $url;
        }
        $sep = (strpos($url, '?') !== false) ? '&' : '?';
        $url .= $sep . \Config::get('session.cookie') . '=' . \Session::getId();
        return $url;

    }
    public function to($path, $extra = array(), $secure = null)
    {
        if (isset($extra['NO_ADD_SID'])) {
            unset($extra['NO_ADD_SID']);
            return parent::to($path, $extra, $secure);
        }

        $url = parent::to($path, $extra, $secure);
        $url = $this->addSid($url);
        return $url;
    }

    protected function toRoute($route, $parameters, $absolute)
    {
        if (isset($parameters['NO_ADD_SID'])) {
            unset($parameters['NO_ADD_SID']);
            return parent::toRoute($route, $parameters, $absolute);
        }

        $url = parent::toRoute($route, $parameters, $absolute);
        $url = $this->addSid($url);
        return $url;
    }


    public function previous($fallback = false)
    {
        $url = parent::previous($fallback);
        $url = parent::previous();
        $url = $this->addSid($url);
        return $url;
    }


}
