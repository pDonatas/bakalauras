<?php

declare(strict_types=1);

namespace App\Services\RabbitMQ\Packer;

use Illuminate\Queue\InvalidPayloadException;

class PayloadPacker
{
    public function pack(mixed $job, ?string $queue, ?string $data = ''): string
    {
        $payload = json_encode($this->createPayloadArray($job, $queue, $data));

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new InvalidPayloadException(json_last_error_msg());
        }

        return $payload;
    }

    /**
     * Create a payload array from the given job and data.
     *
     * @return array<string, mixed>
     */
    protected function createPayloadArray(mixed $job, string $queue, ?string $data = ''): array
    {
        return is_object($job)
            ? $this->createObjectPayload($job)
            : $this->createStringPayload($job, $data);
    }

    /**
     * Create a payload for an object-based queue handler.
     *
     * @return array<string, mixed>
     */
    protected function createObjectPayload(mixed $job): array
    {
        return [
            'displayName' => $this->getDisplayName($job),
            'job'         => 'Illuminate\Queue\CallQueuedHandler@call',
            'maxTries'    => $job->tries ?? null,
            'timeout'     => $job->timeout ?? null,
            'data'        => [
                'commandName' => get_class($job),
                'command'     => serialize(clone $job),
            ],
        ];
    }

    /**
     * Get the display name for the given job.
     */
    protected function getDisplayName(mixed $job): string
    {
        return method_exists($job, 'displayName')
            ? $job->displayName() : get_class($job);
    }

    /**
     * Create a typical, string based queue payload array.
     *
     * @return array<string, mixed>
     */
    protected function createStringPayload(?string $job, mixed $data): array
    {
        return [
            'displayName' => is_string($job) ? explode('@', $job)[0] : null,
            'job'         => $job,
            'maxTries'    => null,
            'timeout'     => null,
            'data'        => $data,
        ];
    }

    public function unpack(string $raw, string $queue): mixed
    {
        return json_decode($raw, true);
    }
}
