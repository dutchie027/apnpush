<?php

namespace Apnpush\Payload;

/**
 * Class Alert
 *
 * @see http://bit.ly/payload-key-reference
 */
class Alert implements \JsonSerializable
{
    public const ALERT_TITLE_KEY = 'title';
    public const ALERT_SUBTITLE = 'subtitle';
    public const ALERT_BODY_KEY = 'body';
    public const ALERT_TITLE_LOC_KEY = 'title-loc-key';
    public const ALERT_TITLE_LOC_ARGS_KEY = 'title-loc-args';
    public const ALERT_ACTION_LOC_KEY = 'action-loc-key';
    public const ALERT_LOC_KEY = 'loc-key';
    public const ALERT_LOC_ARGS_KEY = 'loc-args';
    public const ALERT_LAUNCH_IMAGE_KEY = 'launch-image';

    /**
     * A short string describing the purpose of the notification.
     *
     * @var string
     */
    private $title;

    /**
     * A subtitle.
     *
     * @var string
     */
    private $subtitle;

    /**
     * The text of the alert message.
     *
     * @var string
     */
    private $body;

    /**
     * The key to a title string in the Localizable.strings file for the current localization.
     *
     * @var string|null
     */
    private $titleLocKey;

    /**
     * Variable string values to appear in place of the format specifiers in title-loc-key.
     *
     * @var string[]|null
     */
    private $titleLocArgs;

    /**
     * If a string is specified, the iOS system displays an alert that includes the Close and View buttons.
     *
     * @var string|null
     */
    private $actionLocKey;

    /**
     * A key to an alert-message string in a Localizable.strings file for the current localization.
     *
     * @var string
     */
    private $locKey;

    /**
     * Variable string values to appear in place of the format specifiers in loc-key.
     *
     * @var string[]
     */
    private $locArgs;

    /**
     * The filename of an image file in the app bundle, with or without the filename extension.
     *
     * @var string
     */
    private $launchImage;

    protected function __construct()
    {
    }

    public static function create(): Alert
    {
        return new self();
    }

    /**
     * Set Alert title.
     */
    public function setTitle(string $value): Alert
    {
        $this->title = $value;

        return $this;
    }

    /**
     * Get Alert title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set Alert title.
     */
    public function setSubtitle(string $value): Alert
    {
        $this->subtitle = $value;

        return $this;
    }

    /**
     * Get Alert subtitle.
     *
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set Alert body.
     */
    public function setBody(string $value): Alert
    {
        $this->body = $value;

        return $this;
    }

    /**
     * Get Alert body.
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set title-loc-key.
     */
    public function setTitleLocKey(string $value = null): Alert
    {
        $this->titleLocKey = $value;

        return $this;
    }

    /**
     * Get title-loc-key.
     *
     * @return string|null
     */
    public function getTitleLocKey()
    {
        return $this->titleLocKey;
    }

    /**
     * Set title-loc-args.
     *
     * @param array<string>|null $value
     */
    public function setTitleLocArgs(array $value = null): Alert
    {
        $this->titleLocArgs = $value;

        return $this;
    }

    /**
     * Get title-loc-args.
     *
     * @return string[]|null
     */
    public function getTitleLocArgs()
    {
        return $this->titleLocArgs;
    }

    /**
     * Set action-loc-key.
     */
    public function setActionLocKey(string $value = null): Alert
    {
        $this->actionLocKey = $value;

        return $this;
    }

    /**
     * Get action-loc-key.
     *
     * @return string|null
     */
    public function getActionLocKey()
    {
        return $this->actionLocKey;
    }

    /**
     * Set loc-key.
     */
    public function setLocKey(string $value): Alert
    {
        $this->locKey = $value;

        return $this;
    }

    /**
     * Get loc-key.
     *
     * @return string
     */
    public function getLocKey()
    {
        return $this->locKey;
    }

    /**
     * Set loc-args.
     *
     * @param array<string> $value
     */
    public function setLocArgs(array $value): Alert
    {
        $this->locArgs = $value;

        return $this;
    }

    /**
     * Get loc-args.
     *
     * @return string[]
     */
    public function getLocArgs()
    {
        return $this->locArgs;
    }

    /**
     * Set launch-image.
     *
     * @return $this
     */
    public function setLaunchImage(string $value)
    {
        $this->launchImage = $value;

        return $this;
    }

    /**
     * Get launch-image.
     *
     * @return string
     */
    public function getLaunchImage()
    {
        return $this->launchImage;
    }

    /**
     * Convert Alert to JSON.
     */
    public function toJson(): string
    {
        return json_encode($this, JSON_UNESCAPED_UNICODE) ?: '';
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return array<mixed>
     *
     * @see   http://php.net/manual/en/jsonserializable.jsonserialize.php
     */
    public function jsonSerialize()
    {
        $alert = [];

        if (is_string($this->title)) {
            $alert[self::ALERT_TITLE_KEY] = $this->title;
        }

        if (is_string($this->subtitle)) {
            $alert[self::ALERT_SUBTITLE] = $this->subtitle;
        }

        if (is_string($this->body)) {
            $alert[self::ALERT_BODY_KEY] = $this->body;
        }

        if (is_string($this->titleLocKey)) {
            $alert[self::ALERT_TITLE_LOC_KEY] = $this->titleLocKey;
        }

        if (is_array($this->titleLocArgs)) {
            $alert[self::ALERT_TITLE_LOC_ARGS_KEY] = $this->titleLocArgs;
        }

        if (is_string($this->actionLocKey)) {
            $alert[self::ALERT_ACTION_LOC_KEY] = $this->actionLocKey;
        }

        if (is_string($this->locKey)) {
            $alert[self::ALERT_LOC_KEY] = $this->locKey;
        }

        if (is_array($this->locArgs)) {
            $alert[self::ALERT_LOC_ARGS_KEY] = $this->locArgs;
        }

        if (is_string($this->launchImage)) {
            $alert[self::ALERT_LAUNCH_IMAGE_KEY] = $this->launchImage;
        }

        return $alert;
    }
}
