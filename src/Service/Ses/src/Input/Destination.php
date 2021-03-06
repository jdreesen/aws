<?php

namespace AsyncAws\Ses\Input;

class Destination
{
    /**
     * An array that contains the email addresses of the "To" recipients for the email.
     *
     * @var string[]
     */
    private $ToAddresses;

    /**
     * An array that contains the email addresses of the "CC" (carbon copy) recipients for the email.
     *
     * @var string[]
     */
    private $CcAddresses;

    /**
     * An array that contains the email addresses of the "BCC" (blind carbon copy) recipients for the email.
     *
     * @var string[]
     */
    private $BccAddresses;

    /**
     * @param array{
     *   ToAddresses?: string[],
     *   CcAddresses?: string[],
     *   BccAddresses?: string[],
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->ToAddresses = $input['ToAddresses'] ?? [];
        $this->CcAddresses = $input['CcAddresses'] ?? [];
        $this->BccAddresses = $input['BccAddresses'] ?? [];
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getBccAddresses(): array
    {
        return $this->BccAddresses;
    }

    /**
     * @return string[]
     */
    public function getCcAddresses(): array
    {
        return $this->CcAddresses;
    }

    /**
     * @return string[]
     */
    public function getToAddresses(): array
    {
        return $this->ToAddresses;
    }

    /**
     * @param string[] $value
     */
    public function setBccAddresses(array $value): self
    {
        $this->BccAddresses = $value;

        return $this;
    }

    /**
     * @param string[] $value
     */
    public function setCcAddresses(array $value): self
    {
        $this->CcAddresses = $value;

        return $this;
    }

    /**
     * @param string[] $value
     */
    public function setToAddresses(array $value): self
    {
        $this->ToAddresses = $value;

        return $this;
    }

    public function validate(): void
    {
        // There are no required properties
    }
}
