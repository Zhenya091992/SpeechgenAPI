<?php

namespace tests;

use Dotenv\Dotenv;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Yayheniy\Speechgen\SpeechgenAPI;

class SpeechgenAPITest extends TestCase
{
    protected string $token;

    protected string $email;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $env = Dotenv::createImmutable(__DIR__ . '/../');
        $env->load();

        $this->token = $_ENV['SPEECHGEN_TOKEN'];    //enter your token
        $this->email = $_ENV['SPEECHGEN_EMAIL'];    //enter your email

        parent::__construct($name, $data, $dataName);
    }

    public function testSpeechgenAPI ()
    {
        $instance = new SpeechgenAPI($this->token, $this->email);
        $request = $instance->getAudio('play', 'Matthew');
        Assert::assertJson($request);
        $data = json_decode($request, 1);

        /**
         * current voiceover status. Available from 3 values:
         *  0  - process
         *  1  - completed successfully
         *  -1 - error
         */
        Assert::assertEquals(1, $data['status']);

        Assert::assertEquals('', $data['error']);
    }
}
