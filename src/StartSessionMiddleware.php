<?php

namespace iMi\LaravelTransSid;

class StartSessionMiddleware extends \Illuminate\Session\Middleware\StartSession
{

    const LOCKED_FIELD = 'locked_to';

    /**
     * Store IP and Agent in order to lock the session to a specfic user
     * (against over-taking via URL sharing)
     *
     * @param $session
     * @param $request
     */
    protected function lockToUser($session, $request)
    {
        $session->put(self::LOCKED_FIELD, [
            'ip'    => $request->getClientIp(),
            'agent' => md5($request->server('HTTP_USER_AGENT'))
        ]);
    }

    /**
     * Check if IP or Agent changed
     *
     * @param $session
     * @param $request
     * @return bool
     */
    protected function validate($session, $request)
    {
        $locked = $session->get(self::LOCKED_FIELD);
        return !($locked['ip'] != $request->getClientIp()
            || $locked['agent'] != md5($request->server('HTTP_USER_AGENT')));
    }

    /**
     * Overwritten from parent class.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Session\Session|mixed
     */
    public function getSession(\Illuminate\Http\Request $request)
    {
        $session = parent::getSession($request);

        if ($request->has($session->getName())) {
            $session->setId($request->input($session->getName()));

            if (!$session->has(self::LOCKED_FIELD)) {
                $this->lockToUser($session, $request);
            } else {
                // validate session against store IP and user agent hash
                if (!$this->validate($session, $request)) {
                    $session->setId(null); // refresh ID
                    $session->start();
                    $this->lockToUser($session, $request);
                }
            }
        }

        return $session;
    }
}
