<?php

namespace Yayheni\Speechgen;

/**
 * Class for connection with api https://speechgen.io/en/node/api/
 *
 * @link https://speechgen.io/en/node/api/
 */
class SpeechgenAPI
{
    /**
     * Url for option has a maximum limit of 2000 characters. And also a maximum of 2 voice changes. If the limits are
     * exceeded, you will get an error (status = -1).
     */
    const URL_SPEECHGEN = 'https://speechgen.io/index.php?r=api/text';

    protected $token;
    protected $email;
    protected $voice;
    protected $text;
    protected $format;
    protected $speed;
    protected $pitch;
    protected $emotion;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function getAudio($text, $voice)
    {
        $this->text = $text;
        $this->voice = $voice;

        return $this->getResponse();
    }

    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    public function setSpeed($speed)
    {
        $this->speed = $speed;

        return $this;
    }

    public function setPitch($pitch)
    {
        $this->pitch = $pitch;

        return $this;
    }

    public function setEmotion($emotion)
    {
        $this->emotion = $emotion;

        return $this;
    }

    public function getResponse()
    {
        $requestHeaders = [
            'token' => $this->token,
            'email' => $this->email,
            'voice' => $this->voice,
            'text' => $this->text,
        ];
        $addedParameters = [
            'format' => $this->format ?? null,
            'speed' => $this->speed ?? null,
            'pitch' => $this->pitch ?? null,
            'emotion' => $this->emotion ?? null,
        ];
        $filterAddedParam = array_filter($addedParameters, function ($value) {
            return !($value === null);
        });

        $requestOptions = array_merge($requestHeaders, $filterAddedParam);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, self::URL_SPEECHGEN);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($requestOptions));

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}
