<?php

namespace App\Logger;

use Monolog\Attribute\AsMonologProcessor;
use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;
use Symfony\Component\HttpFoundation\Exception\SessionNotFoundException;
use Symfony\Component\HttpFoundation\RequestStack;

#[AsMonologProcessor]
class SessionRequestProcessor
{
    public function __construct(
        private RequestStack $requestStack
    ) {
    }

    public function __invoke(LogRecord $record): LogRecord
    {
        // Build a stable per-request correlation id and attach it to every log record.
        // This ensures ALL log calls carry the same unique id, even without an active session.
        $prefix = 'deelog_';

        try {
            $session = $this->requestStack->getSession();
            if ($session->isStarted()) {
                $sid = substr($session->getId(), 0, 8) ?: '????????';
                $prefix = $sid;
            }
        } catch (SessionNotFoundException $e) {
            // keep anonymous prefix when no session available
        }

        $request = $this->requestStack->getCurrentRequest();
        $requestId = null;
        if (null !== $request) {
            $requestId = $request->attributes->get('_log_request_id');
            if (!$requestId) {
                // 16 hex chars (64 bits) random id is enough for uniqueness and compactness
                try {
                    $requestId = bin2hex(random_bytes(8));
                } catch (\Exception) {
                    // Fallback if random_bytes is unavailable for any reason
                    $requestId = substr(sha1(uniqid((string) mt_rand(), true)), 0, 16);
                }
                $request->attributes->set('_log_request_id', $requestId);
            }
        } else {
            // No Request object (e.g., CLI early logs) – still generate a process-level id
            static $cliRequestId = null;
            if (!$cliRequestId) {
                try {
                    $cliRequestId = bin2hex(random_bytes(8));
                } catch (\Exception) {
                    $cliRequestId = substr(sha1(uniqid((string) mt_rand(), true)), 0, 16);
                }
            }
            $requestId = $cliRequestId;
        }

        $record->extra['request_id'] = $prefix.'-'.$requestId;

        return $record;
    }
}
