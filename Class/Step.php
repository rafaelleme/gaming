<?php

class Step
{
    public function __construct()
    {
        new Session();
    }

    public function getCurrent()
    {
        if (empty(Session::get('currentStep'))) {
            self::setDefault();
        }

        $session = Session::get('step');

        return $session[Session::get('currentStep')];
    }

    public function getMessage(): ?string
    {
        $current = $this->getCurrent();

        $item = $this->getItem($current['categories']);

        $this->setItemSession($item);

        if (empty($item)) {
            self::next();
            return $this->setReloadPage();
        }

        if (!empty($item['finished'])) {
            Session::set('finished', 1);
            self::next();
            self::next();
            Session::reset();
            return $this->getMessageToString('Acertei de novo!');
        }

        return $this->getMessageToString($current['message'], $item['name']);
    }

    public function getItem(array $item)
    {
        if ($item['answer'] === null) {

            $res = null;

            if (!empty($item['categories'])) {
                foreach ($item['categories'] as $category) {

                    $res = $this->getItem($category);

                    if ($res !== null) {
                        return $res;
                    }
                }
            }

            return $item;
        }

        if ($item['answer'] === true) {

            if (empty($item['items'])) {
                $item['finished'] = true;
                return $item;
            }

            return $this->getItem($item['items']);
        }

        if ($item['answer'] === false) {

            if (empty($item['categories']) || $item['final'] === true) {
                return null;
            }

            return $this->getItem($item['categories']);
        }

        return null;
    }

    public function getMessageToString(string $message, string $item = '')
    {
        if ($item) {
            return sprintf($message, $item);
        }
        return $message;
    }

    public function setReloadPage(): string
    {
        return '<script>window.location.reload();</script>';
    }

    public function setItemSession(array $item = null): void
    {
        if ($item === null) {
            return;
        }

        Session::set('currentItem', $item);
    }

    public static function next(): void
    {
        $next = Session::get('currentStep') + 1;

        if (array_key_exists($next, Session::get('step'))) {
            Session::set('currentStep', $next);
            return;
        }

        self::setDefault();
    }

    public static function setDefault(): void
    {
        Session::set('currentStep', 1);
    }

    public static function updateSession(): void
    {
        if (Session::Get('currentStep') === 1 && !empty($_POST['step'])) {
            Session::set('finished', 0);
            Session::reset();
            self::next();
        }

        if (Session::get('currentStep') === 2 && !empty($_POST['answer'])) {
            $answer = $_POST['answer'] === 'true';
            self::setChangesStep2($answer);
        }

        if (Session::get('currentStep') === 3 && (!empty($_POST['dish']) && !empty($_POST['category']))) {
            $category = self::makeCategory($_POST);

            $item = Session::get('currentItem');

            array_push($item['categories'], $category);

            self::setChangesStep3($item);

            self::next();
        }
    }

    public static function makeCategory(array $data): array
    {
        return [
            'parent' => '',
            'name' => $data['category'],
            'answer' => null,
            'items' => [
                'parent' => $data['category'],
                'name' => $data['dish'],
                'answer' => null
            ]
        ];
    }

    public static function setChangesStep2(bool $answer): void
    {
        $local = Session::get('step');

        $current = $local[Session::get('currentStep')];

        $item = Session::get('currentItem');

        $currentStr = json_encode($current, true);

        $itemStr = json_encode($item, true);

        $item['answer'] = $answer;

        $finalItemStr = json_encode($item, true);

        $finalCurrentStr = str_replace($itemStr, $finalItemStr, $currentStr);

        $finalCurrent = json_decode($finalCurrentStr,true);

        Session::setItem($finalCurrent);
    }

    public static function setChangesStep3(array $data): void
    {
        $local = Session::get('step');

        $step2 = $local[2];

        $item = Session::get('currentItem');

        $item['answer'] = false;

        $step2Str = json_encode($step2, true);

        $itemStr = json_encode($item, true);

        $finalItemStr = json_encode($data, true);

        $finalStep2Str = str_replace($itemStr, $finalItemStr, $step2Str);

        $finalStep2 = json_decode($finalStep2Str,true);

        Session::setItem($finalStep2, 2);
    }
}