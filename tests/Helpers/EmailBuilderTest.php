<?php

namespace Coresender\Tests\Helpers;

use PHPUnit\Framework\TestCase;
use Coresender\Helpers\EmailBuilder;

class EmailBuilderTest extends TestCase
{
    /** @test */
    public function it_should_set_from(): void
    {
        $emailBuilder = new EmailBuilder();
        $emailBuilder->setFrom('jean.luc@example.com', 'Jean-Luc Picard');

        $email = $emailBuilder->getEmail();

        $this->assertEquals(['from' => ['email' => 'jean.luc@example.com', 'name' => 'Jean-Luc Picard']], $email);
    }

    /** @test */
    public function it_should_add_to_recipient(): void
    {
        $emailBuilder = new EmailBuilder();
        $emailBuilder->addToRecipient('geordi@example.com', 'Geordi La Forge');

        $email = $emailBuilder->getEmail();

        $this->assertEquals(['to' => [['email' => 'geordi@example.com', 'name' => 'Geordi La Forge']]], $email);
    }

    /** @test */
    public function it_should_set_subject(): void
    {
        $emailBuilder = new EmailBuilder();
        $emailBuilder->setSubject('I need engines');

        $email = $emailBuilder->getEmail();

        $this->assertEquals(['subject' => 'I need engines'], $email);
    }

    /** @test */
    public function it_should_set_body_html(): void
    {
        $emailBuilder = new EmailBuilder();
        $emailBuilder->setBodyHtml('<p>Geordi, I need engines, <strong>now!</strong></p>');

        $email = $emailBuilder->getEmail();

        $this->assertEquals(['body' => ['html' => '<p>Geordi, I need engines, <strong>now!</strong></p>']], $email);
    }

    /** @test */
    public function it_should_set_body_text(): void
    {
        $emailBuilder = new EmailBuilder();
        $emailBuilder->setBodyText('Geordi, I need engines, now!');

        $email = $emailBuilder->getEmail();

        $this->assertEquals(['body' => ['text' => 'Geordi, I need engines, now!']], $email);
    }

    /** @test */
    public function it_should_set_custom_id(): void
    {
        $emailBuilder = new EmailBuilder();
        $emailBuilder->setCustomId('abcd-1234');

        $email = $emailBuilder->getEmail();

        $this->assertEquals(['custom_id' => 'abcd-1234'], $email);
    }

    /** @test */
    public function it_should_set_custom_id_uniqueness(): void
    {
        $emailBuilder = new EmailBuilder();
        $emailBuilder->setCustomIdUnique(true);

        $email = $emailBuilder->getEmail();

        $this->assertTrue($email['custom_id_unique']);
    }

    /** @test */
    public function it_should_set_track_opens(): void
    {
        $emailBuilder = new EmailBuilder();
        $emailBuilder->setTrackOpens(true);

        $email = $emailBuilder->getEmail();

        $this->assertTrue($email['track_opens']);
    }

    /** @test */
    public function it_should_set_track_clicks(): void
    {
        $emailBuilder = new EmailBuilder();
        $emailBuilder->setTrackClicks(true);

        $email = $emailBuilder->getEmail();

        $this->assertTrue($email['track_clicks']);
    }

    /** @test */
    public function it_should_set_list_id(): void
    {
        $emailBuilder = new EmailBuilder();
        $emailBuilder->setListId('crew-orders');

        $email = $emailBuilder->getEmail();

        $this->assertEquals(['list_id' => 'crew-orders'], $email);
    }

    /** @test */
    public function it_should_set_list_unsubscribe(): void
    {
        $emailBuilder = new EmailBuilder();
        $emailBuilder->setListUnsubscribe('https://example.com/unsubscribe/abcd-1234');

        $email = $emailBuilder->getEmail();

        $this->assertEquals(['list_unsubscribe' => 'https://example.com/unsubscribe/abcd-1234'], $email);
    }
}
