<?php

namespace HeadlessChromium\Test;

use HeadlessChromium\Browser\ProcessAwareBrowser;
use HeadlessChromium\BrowserFactory;

class KeyEventTest extends BaseTestCase
{

    /**
     * @var ProcessAwareBrowser
     */
    public static $browser;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        $factory = new BrowserFactory('chromium-browser');
        self::$browser = $factory->createBrowser(['headless' => false]);
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        self::$browser->close();
    }

    private function openSitePage($file)
    {
        $page = self::$browser->createPage();
        $page->navigate($this->sitePath($file))->waitForNavigation();

        return $page;
    }

    /**
     * @throws \HeadlessChromium\Exception\CommunicationException
     * @throws \HeadlessChromium\Exception\NoResponseAvailable
     */
    public function testClickLink()
    {
        // initial navigation
        $page = $this->openSitePage('input.html');
        $input = 'body > input[type=text]';
        $textarea = 'body > textarea';

        $page->evaluate('document.querySelector("' . $input . '").focus()');
        $text = 'text';
        $page->keyboard()->write($text);
        $value = $page->evaluate('document.querySelector("' . $input . '").value')
            ->getReturnValue();

        $this->assertEquals($text, $value);
        $page->evaluate('document.querySelector("' . $textarea . '").focus()');
        $page->keyboard()->write($text);
        $value = $page->evaluate('document.querySelector("' . $textarea . '").value')
            ->getReturnValue();
        $this->assertEquals($text, $value);
    }
}
