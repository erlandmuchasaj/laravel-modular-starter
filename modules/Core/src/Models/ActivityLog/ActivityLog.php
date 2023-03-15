<?php

namespace Modules\Core\Models\ActivityLog;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Modules\User\Models\User\User;
use Spatie\Activitylog\Contracts\Activity as ActivityContract;
use Spatie\Activitylog\Models\Activity;

/**
 * Modules\Core\Models\ActivityLog\ActivityLog
 *
 * @property int                $id
 * @property string|null        $log_name
 * @property string             $description
 * @property string|null        $subject_type
 * @property int|null           $subject_id
 * @property string|null        $event
 * @property string|null        $causer_type
 * @property int|null           $causer_id
 * @property Collection|null    $properties
 * @property string|null        $client_ip
 * @property array|null         $client_ips
 * @property string|null        $user_agent
 * @property int|null           $impersonator_id
 * @property string|null        $batch_uuid
 * @property Carbon|null        $created_at
 * @property Carbon|null        $updated_at
 * @property string|null        $event_name
 * @property string|null        $device
 * @property string|null        $platform
 * @property string|null        $browser
 * @property Model|null         $subject
 * @property User|null          $causer
 * @property-read Collection $changes
 * @property-read string $log_message
 * @property-read mixed $model
 * @property-read string|null $subject_name
 * @property-read User|null $user
 *
 * @method static Builder|Activity causedBy(\Illuminate\Database\Eloquent\Model $causer)
 * @method static Builder|Activity forBatch(string $batchUuid)
 * @method static Builder|Activity forEvent(string $event)
 * @method static Builder|Activity forSubject(\Illuminate\Database\Eloquent\Model $subject)
 * @method static Builder|Activity hasBatch()
 * @method static Builder|Activity inLog(...$logNames)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereBatchUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereCauserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereCauserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereLogName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereProperties($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereSubjectType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class ActivityLog extends Activity implements ActivityContract
{
    use HasFactory,
        ScopesTrait,
        MethodTrait,
        RelationsTrait,
        MutatorTrait,
        AccessorTrait;

    public $guarded = ['id'];

    public static string $morph_key = 'activity_log';

    protected $casts = [
        'properties' => 'collection',
        'client_ips' => 'array',
    ];

    protected $appends = [
        // 'log_message',
        // 'event',
        // 'model',
        // 'subject_name',
        'device',
        'platform',
        'browser',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function (ActivityLog $model) {
            $model->client_ip = request()->ip();
            $model->client_ips = request()->ips();
            $model->user_agent = request()->userAgent();
            // $model->is_api = request()->is('api/*');
        });
    }
}
