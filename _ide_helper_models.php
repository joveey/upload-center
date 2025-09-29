<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Division newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Division newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Division query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Division whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Division whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Division whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Division whereUpdatedAt($value)
 */
	class Division extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $mapping_index_id
 * @property string $excel_column_index
 * @property string $table_column_name
 * @property string $data_type
 * @property bool $is_required
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\MappingIndex $mappingIndex
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MappingColumn newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MappingColumn newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MappingColumn query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MappingColumn whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MappingColumn whereDataType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MappingColumn whereExcelColumnIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MappingColumn whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MappingColumn whereIsRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MappingColumn whereMappingIndexId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MappingColumn whereTableColumnName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MappingColumn whereUpdatedAt($value)
 */
	class MappingColumn extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $code
 * @property string $description
 * @property string $table_name
 * @property int $header_row
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $division_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MappingColumn> $columns
 * @property-read int|null $columns_count
 * @property-read \App\Models\Division|null $division
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MappingIndex newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MappingIndex newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MappingIndex query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MappingIndex whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MappingIndex whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MappingIndex whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MappingIndex whereDivisionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MappingIndex whereHeaderRow($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MappingIndex whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MappingIndex whereTableName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MappingIndex whereUpdatedAt($value)
 */
	class MappingIndex extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $division_id
 * @property-read \App\Models\Division|null $division
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDivisionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 */
	class User extends \Eloquent {}
}

