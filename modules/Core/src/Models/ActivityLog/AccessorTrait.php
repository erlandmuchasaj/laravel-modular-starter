<?php

namespace Modules\Core\Models\ActivityLog;

use Illuminate\Support\Facades\Lang;
use Jenssegers\Agent\Agent;

trait AccessorTrait
{
    /**
     * The USer agent
     *
     * @var Agent | null
     */
    protected Agent | null $agent = null;

    public function getLogMessageAttribute(): string
    {
        $message = $this->getTranslationMessage(
            $this->description,
            $this->subject,
            $this->causer,
            $this->causer_type
        );

        return (is_string($message)) ? $message : $this->description;
    }

    public function getModelAttribute()
    {
        $model = $this->getClassNameAndEvent(
            $this->description,
            $this->subject
        );

        return (is_array($model)) ? $model['model'] : '';
    }

    public function getEventAttribute(): array|string
    {
        $event = $this->getClassNameAndEvent(
            $this->description,
            $this->subject
        );

        return (is_array($event)) ? $event['event'] : '';
    }

    public function getSubjectNameAttribute(): ?string
    {
        if ($this->subject_type !== null && Lang::has("core::activity_log.models.{$this->subject_type}")) {
            return (string) __("core::activity_log.models.{$this->subject_type}");
        }

        return null;
    }

    public function getDeviceAttribute(): ?string
    {
        return $this->getUserAgent()?->device();
    }

    public function getPlatformAttribute(): ?string
    {
        return $this->getUserAgent()?->platform();
    }

    public function getBrowserAttribute(): ?string
    {
        return $this->getUserAgent()?->browser();
    }

    private function getUserAgent(): ?Agent
    {
        if ($this->user_agent === null) {
            return null;
        }

        if (! $this->agent) {
            $this->agent = new Agent();
            $this->agent->setUserAgent($this->user_agent);
        }

        return $this->agent;
    }
}
