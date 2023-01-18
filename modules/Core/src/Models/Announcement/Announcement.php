<?php

namespace Modules\Core\Models\Announcement;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Modules\Core\Database\Factories\AnnouncementFactory;
use Modules\User\Models\User\User;
use ReflectionClass;
use ReflectionException;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Modules\Core\Models\Announcement\Announcement
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $area when area is null it means is for both
 * @property string $type
 * @property string $message
 * @property bool $enabled
 * @property Carbon|null $starts_at
 * @property Carbon|null $ends_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read string $parsed_body
 * @property-read User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement enabled()
 * @method static \Modules\Core\Database\Factories\AnnouncementFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement forArea(string $area)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement inTimeFrame()
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement query()
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereStartsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereUserId($value)
 *
 * @mixin Eloquent
 */
class Announcement extends Model
{
    use LogsActivity
        , HasFactory
        , ScopesTrait
        , MethodTrait
        , RelationsTrait
        , MutatorTrait
        , AccessorTrait;

    public const TYPE_FRONTEND = 'frontend';

    public const TYPE_BACKEND = 'backend';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'announcements';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'area',
        'type',
        'message',
        'enabled',
        'starts_at',
        'ends_at',
    ];

    /**
     * @var string[]
     */
    protected $dates = [
        'starts_at',
        'ends_at',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'enabled' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['parsed_body'];

    protected static bool $logFillable = true;

    /**
     * Logging only the changed attributes
     *
     * @var bool
     */
    protected static bool $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    /**
     * @param  Activity  $activity
     * @param  string  $eventName
     */
    public function tapActivity(Activity $activity, string $eventName)
    {
        try {
            $reflect = new ReflectionClass($this);
            $class_name = Str::lower($reflect->getShortName());
            $activity->description = "activity.$class_name.{$eventName}";
        } catch (ReflectionException $e) {
            $activity->description = $eventName;
        }
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory<Announcement>
     */
    protected static function newFactory(): Factory
    {
        return AnnouncementFactory::new();
    }
}
