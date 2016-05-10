<?php

namespace App\Email;

use Infuse\Queue;
use Infuse\Queue\Message;
use Infuse\View;

class Mailer
{
    const QUEUE_NAME = 'emails';

    /**
     * @staticvar array
     */
    private static $drivers = [
        'mandrill' => 'App\Email\Driver\MandrillDriver',
        'nop' => 'App\Email\Driver\NullDriver',
        'smtp' => 'App\Email\Driver\SwiftDriver',
    ];

    /**
     * @var array
     */
    private $settings;

    /**
     * @var App\Email\Driver\DriverInterface
     */
    private $driver;

    /**
     * @param array $settings
     */
    public function __construct(array $settings)
    {
        // deprecated
        if (!isset($settings['driver']) && isset($settings['type'])) {
            $settings['driver'] = self::$drivers[$settings['type']];
        }

        $driverClass = $settings['driver'];
        $this->driver = new $driverClass($settings);
        $this->settings = $settings;
    }

    public function getDriver()
    {
        return $this->driver;
    }

    public function getQueue()
    {
        return new Queue(self::QUEUE_NAME);
    }

    /**
     * Queues an email.
     *
     * @param string $template name of template
     * @param array  $message
     *
     * @return \Infuse\Queue\Message
     */
    public function queueEmail($template, array $message)
    {
        $message = $this->compressMessage($message);

        $body = [
            't' => $template,
            'm' => $message,
        ];
        $body = json_encode($body);

        return $this->getQueue()->enqueue($body);
    }

    /**
     * Handles an email message coming off the queue.
     *
     * @param Message $message
     */
    public function processEmail(Message $message)
    {
        // uncompress the message variables
        $body = json_decode($message->getBody());
        $variables = $this->uncompressMessage($body->m);

        $this->send($variables, $body->t, $variables);
    }

    /**
     * @deprecated
     */
    public function sendEmail($template, array $message)
    {
        // render the body from the template
        // NOTE: this method does not have template variables
        // they are embedded in $message
        if ($template) {
            $message = $this->messageFromTemplate($message, $template, $message);
        }

        // set missing from information
        if (!isset($message['from_email'])) {
            $message['from_email'] = array_value($this->settings, 'from_email');
        }

        if (!isset($message['from_name'])) {
            $message['from_name'] = array_value($this->settings, 'from_name');
        }

        return $this->driver->send($message);
    }

    /**
     * Sends an email.
     *
     * @param array        $message
     * @param string|false $template     optional template name
     * @param array        $templateVars optional template variables
     *
     * @return array
     */
    public function send(array $message, $template = false, array $templateVars = [])
    {
        // render the body from the template
        if ($template) {
            $message = $this->messageFromTemplate($message, $template, $templateVars);
        }

        // set missing from information
        if (!isset($message['from_email'])) {
            $message['from_email'] = array_value($this->settings, 'from_email');
        }

        if (!isset($message['from_name'])) {
            $message['from_name'] = array_value($this->settings, 'from_name');
        }

        return $this->driver->send($message);
    }

    /**
     * Compresses message variables.
     *
     * @param array $message
     *
     * @return string compressed and encoded variables
     */
    public function compressMessage(array $message)
    {
        return base64_encode(gzcompress(json_encode($message), 9));
    }

    /**
     * Uncompresses a message.
     *
     * @param string $compressed
     *
     * @return array
     */
    public function uncompressMessage($compressed)
    {
        return json_decode(gzuncompress(base64_decode($compressed)), true);
    }

    /**
     * Adds in HTML and text values to a message from the template (if not already set).
     *
     * @param array  $message
     * @param string $template
     * @param array  $templateVars
     *
     * @return array
     */
    private function messageFromTemplate(array $message, $template, array $templateVars)
    {
        if (!isset($message['html'])) {
            $htmlTemplate = 'emails/'.$template;
            $htmlView = new View($htmlTemplate, $templateVars);
            $message['html'] = $htmlView->render();
        }

        if (!isset($message['text'])) {
            $textTemplate = 'emails/text/'.$template;
            $textView = new View($textTemplate, $templateVars);
            $message['text'] = $textView->render();
        }

        return $message;
    }
}
