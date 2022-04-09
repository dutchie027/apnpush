<?php

namespace Apnpush\Tests\Payload;

use Apnpush\Payload\Alert;
use PHPUnit\Framework\TestCase;

class AlertTest extends TestCase
{
    public function testSetTitle()
    {
        $alert = Alert::create()->setTitle('title');

        self::assertEquals('title', $alert->getTitle());
    }

    public function testSetSubtitle()
    {
        $alert = Alert::create()->setSubtitle('subtitle');

        self::assertEquals('subtitle', $alert->getSubtitle());
    }

    public function testSetBody()
    {
        $alert = Alert::create()->setBody('body');

        self::assertEquals('body', $alert->getBody());
    }

    public function testSetTitleLocKey()
    {
        $alert = Alert::create()->setTitleLocKey('title-loc-key');

        self::assertEquals('title-loc-key', $alert->getTitleLocKey());
    }

    public function testSetTitleLocArgs()
    {
        $alert = Alert::create()->setTitleLocArgs(['title1', 'title2']);

        self::assertEquals(['title1', 'title2'], $alert->getTitleLocArgs());
    }

    public function testSetActionLocKey()
    {
        $alert = Alert::create()->setActionLocKey('action-loc-key');

        self::assertEquals('action-loc-key', $alert->getActionLocKey());
    }

    public function testSetLocKey()
    {
        $alert = Alert::create()->setLocKey('loc-key');

        self::assertEquals('loc-key', $alert->getLocKey());
    }

    public function testSetLocArgs()
    {
        $alert = Alert::create()->setLocArgs(['loc-arg1', 'loc-arg2']);

        self::assertEquals(['loc-arg1', 'loc-arg2'], $alert->getLocArgs());
    }

    public function testSetLaunchImage()
    {
        $alert = Alert::create()->setLaunchImage('launch-image');

        self::assertEquals('launch-image', $alert->getLaunchImage());
    }

    public function testAlertConvertingToJson()
    {
        $alert = Alert::create()
            ->setTitle('title')
            ->setSubtitle('subtitle')
            ->setBody('body')
            ->setTitleLocKey('title-loc-key')
            ->setTitleLocArgs(['loc-arg'])
            ->setActionLocKey('action-loc-key')
            ->setLocKey('loc-key')
            ->setLocArgs(['loc-arg'])
            ->setLaunchImage('launch-image');

        self::assertJsonStringEqualsJsonString(
            '{"title":"title","subtitle":"subtitle","body":"body","title-loc-key":"title-loc-key",' .
            '"title-loc-args":["loc-arg"],"action-loc-key":"action-loc-key","loc-key":"loc-key",' .
            '"loc-args":["loc-arg"],"launch-image":"launch-image"}',
            $alert->toJson()
        );
    }
}
