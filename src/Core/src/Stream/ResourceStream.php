<?php

namespace AsyncAws\Core\Stream;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Convert a resource into a Stream.
 *
 * @internal
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
final class ResourceStream implements Stream
{
    /**
     * @var resource
     */
    private $content;

    private $chunkSize;

    private function __construct($content, int $chunkSize = 64 * 1024)
    {
        $this->content = $content;
        $this->chunkSize = $chunkSize;
    }

    public static function create($content, int $chunkSize = 64 * 1024): ResourceStream
    {
        if ($content instanceof self) {
            return $content;
        }
        if (\is_resource($content)) {
            if (!stream_get_meta_data($content)['seekable']) {
                throw new InvalidArgument(sprintf('The give body is not seekable.'));
            }

            return new self($content, $chunkSize);
        }

        throw new InvalidArgument(sprintf('Expect content to be a "resource". "%s" given.', \is_object($content) ? \get_class($content) : \gettype($content)));
    }

    public function length(): ?int
    {
        return fstat($this->content)['size'] ?? null;
    }

    public function stringify(): string
    {
        if (-1 === fseek($this->content, 0)) {
            throw new InvalidArgument('Unable to seek the content.');
        }

        return \stream_get_contents($this->content);
    }

    public function getIterator(): \Traversable
    {
        if (-1 === fseek($this->content, 0)) {
            throw new InvalidArgument('Unable to seek the content.');
        }

        while (!\feof($this->content)) {
            yield \fread($this->content, $this->chunkSize);
        }
    }
}