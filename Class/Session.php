<?php

class Session
{
    public function __construct()
    {
        if (!empty($_SESSION['step'])) {
            return;
        }

        $_SESSION['finished'] = 0;
        $_SESSION['step'] = [
            1 => [
                'index' => 1,
                'message' => 'Pense em um prato que gosta',
            ],
            2 => [
                'index' => 2,
                'message' => 'O prato que você pensou é %s ?',
                'categories' => [
                    'parent' => '',
                    'name' => 'bolo de chocolate',
                    'answer' => null,
                    'final' => true,
                    'categories' => [
                        [
                            'parent' => '',
                            'name' => 'massa',
                            'answer' => null,
                            'items' => [
                                'parent' => 'massa',
                                'name' => 'lasanha',
                                'answer' => null,
                            ],
                        ],
                    ],
                ],
            ],
            3 => [
                'index' => 3,
                'message' => 'Qual prato você pensou?',
            ],
        ];
    }

    public static function set(string $pos, $value): void
    {
        $_SESSION[$pos] = $value;
    }

    public static function get(string $pos)
    {
        if (!empty($_SESSION[$pos])) {
            return $_SESSION[$pos];
        }

        return null;
    }

    public static function setItem(array $arr, $step = null)
    {
        if ($step === null) {
            $step = self::get('currentStep');
        }

        $_SESSION['step'][$step] = $arr;
    }

    public static function reset()
    {
        $session = $_SESSION['step'][2];

        $sessionStr = json_encode($session, true);

        $sessionStr = str_replace('"answer":false','"answer":null', $sessionStr);

        $sessionStr = str_replace('"answer":true','"answer":null', $sessionStr);

        $sessionFinal = json_decode($sessionStr, true);

        $_SESSION['step'][2] = $sessionFinal;
    }
}