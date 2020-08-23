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

        $item = $this->getFinalMessage($current['categories']);

        if (empty($item)) {
            $this->next();
            return sprintf('Qual prato você pensou?');
        }

        if (isset($_POST['answer'])) {
            $answer = $_POST['answer'] == 'true' ? true : false;
            Session::setStepChanges($item, $answer);
        }

        if (!empty($item['finished'])) {
            Session::set('finished', 1);
            return 'Acertei de novo';
        }

        return $this->getMessageToString($current['message'], $item['name']);
    }

    public function getFinalMessage(array $item)
    {
        if ($item['answer'] === null) {

            $res = null;

            if (!empty($item['categories'])) {
                foreach ($item['categories'] as $category) {

                    $res = $this->getFinalMessage($category);

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

            return $this->getFinalMessage($item['items']);
        }

        if ($item['answer'] === false) {

            if (empty($item['categories']) || $item['final'] === true) {
                return null;
            }

            return $this->getFinalMessage($item['categories']);
        }
    }

    public function getMessageToString(string $message, string $item)
    {
        return sprintf($message, $item);
    }

    public function next(): void
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
}