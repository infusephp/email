<?php

namespace Infuse\Email\Driver;

use Infuse\Utility;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class SwiftDriver implements DriverInterface
{
    /**
     * @var Swift_Mailer
     */
    private $swift;

    public function __construct(array $settings, $transport = null)
    {
        if (!array_key_exists('username', $settings) && !array_key_exists('password', $settings)) {
            $transport = Swift_SmtpTransport::newInstance($settings['host'], $settings['port']);
        }
        if (!$transport) {
            $transport = Swift_SmtpTransport::newInstance($settings['host'], $settings['port'])
                ->setUsername($settings['username'])
                ->setPassword($settings['password']);
        }

        $this->swift = Swift_Mailer::newInstance($transport);
    }

    /**
     * Returns the Swift Mailer instance.
     *
     * @return Swift_Mailer
     */
    public function getSwift()
    {
        return $this->swift;
    }

    public function send(array $message)
    {
        // build recipients
        $to = [];
        $bcc = [];
        $toIncoming = (array) array_value($message, 'to');
        foreach ($toIncoming as $item) {
            $type = array_value($item, 'type');
            if ($type == 'bcc') {
                $bcc[$item['email']] = $item['name'];
            } else {
                $to[$item['email']] = $item['name'];
            }
        }

        if (count($to) === 0) {
            return [];
        }

        $fromEmail = array_value($message, 'from_email');
        $fromName = array_value($message, 'from_name');

        $swiftMessage = Swift_Message::newInstance()
            ->setFrom([$fromEmail => $fromName])
            ->setTo($to)
            ->setBcc($bcc)
            ->setSubject($message['subject'])
            ->setBody($message['html'], 'text/html');

        if (isset($message['text'])) {
            $swiftMessage->addPart($message['text'], 'text/plain');
        }

        if (isset($message['headers']) && is_array($message['headers'])) {
            $headers = $swiftMessage->getHeaders();

            foreach ($message['headers'] as $k => $v) {
                $headers->addTextHeader($k, $v);
            }
        }

        $sent = $this->swift->send($swiftMessage);

        $result = [];
        foreach ($message['to'] as $item) {
            $result[] = [
                '_id' => Utility::guid(false),
                'email' => $item['email'],
                'status' => ($sent) ? 'sent' : 'rejected',
            ];
        }

        return $result;
    }
}
