<?php

namespace Modules\Core\Models\Announcement;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Modules\Core\Database\Factories\AnnouncementFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

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
     * @var boolean
     */
    protected static bool $logOnlyDirty = true;


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

//    /**
//     * @param Activity $activity
//     * @param string $eventName
//     */
//    public function tapActivity(Activity $activity, string $eventName)
//    {
//        try {
//            $reflect = new ReflectionClass($this);
//            $class_name = Str::lower($reflect->getShortName());
//            $activity->description = "$class_name.{$eventName}";
//        } catch (ReflectionException $e) {
//            $activity->description = $eventName;
//        }
//    }

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return AnnouncementFactory::new();
    }
}
