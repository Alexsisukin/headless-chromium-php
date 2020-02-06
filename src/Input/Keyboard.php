<?php

namespace HeadlessChromium\Input;

use HeadlessChromium\Communication\Message;
use HeadlessChromium\Page;

class Keyboard
{

    /**
     * @var Page
     */
    protected $page;

    const TYPE_DOWN     = 'keyDown';
    const TYPE_UP       = 'keyUp';
    const TYPE_RAW_DOWN = 'rawKeyDown';
    const TYPE_CHAR     = 'char';


    /**
     * @param Page $page
     */
    public function __construct(Page $page)
    {
        $this->page = $page;
    }

    public function write(string $text)
    {
        $symbols = mb_str_split($text);
        foreach ($symbols as $symbol) {
            $this->input(self::TYPE_DOWN, $symbol);
            $this->input(self::TYPE_UP, $symbol);
        }
    }

    /**
     * Tapping the keyboard
     *
     * @param string $type Type
     * @param string $text Text
     *
     * @return $this
     * @throws \HeadlessChromium\Exception\CommunicationException
     * @throws \HeadlessChromium\Exception\NoResponseAvailable
     */
    protected function input(string $type, string $text)
    {
        $this->page->assertNotClosed();

        $this->page->getSession()->sendMessageSync(new Message('Input.dispatchKeyEvent', [
            'type' => $type,
            'text' => $text,
        ]));

        return $this;
    }
}
