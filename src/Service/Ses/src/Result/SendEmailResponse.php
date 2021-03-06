<?php

namespace AsyncAws\Ses\Result;

use AsyncAws\Core\Result;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class SendEmailResponse extends Result
{
    private $MessageId;

    /**
     * A unique identifier for the message that is generated when the message is accepted.
     */
    public function getMessageId(): ?string
    {
        $this->initialize();

        return $this->MessageId;
    }

    protected function populateResult(ResponseInterface $response, HttpClientInterface $httpClient): void
    {
        $data = $response->toArray(false);

        $this->MessageId = isset($data['MessageId']) ? (string) $data['MessageId'] : null;
    }
}
