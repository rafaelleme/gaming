<?php

class Session
{
    public function __construct()
    {
        if (!empty($_SESSION['step']) && Session::get('finished') == 0) {
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

    public static function setStepChanges(array $item, bool $answer)
    {
        $session = self::get('step');

        $current = $session[self::get('currentStep')];

        $currentStr = json_encode($current, true);

        $itemStr = json_encode($item, true);

        $item['answer'] = $answer;

        $finalItemStr = json_encode($item, true);

        $finalCurrentStr = str_replace($itemStr, $finalItemStr, $currentStr);

        $finalCurrent = json_decode($finalCurrentStr,true);

        self::setNewStep($finalCurrent);
    }

    public static function setNewStep(array $arr)
    {
        $_SESSION['step'][self::get('currentStep')] = $arr;
    }
}