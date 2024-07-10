<?php

namespace Pterodactyl\Models;

use Illuminate\Support\Collection;

class Permission extends Model
{
    /**
     * The resource name for this model when it is transformed into an
     * API representation using fractal.
     */
    public const RESOURCE_NAME = 'subuser_permission';

    /**
     * Constants defining different permissions available.
     */
    public const ACTION_WEBSOCKET_CONNECT = 'websocket.connect';
    public const ACTION_CONTROL_CONSOLE = 'control.console';
    public const ACTION_CONTROL_START = 'control.start';
    public const ACTION_CONTROL_STOP = 'control.stop';
    public const ACTION_CONTROL_RESTART = 'control.restart';

    public const ACTION_DATABASE_READ = 'database.read';
    public const ACTION_DATABASE_CREATE = 'database.create';
    public const ACTION_DATABASE_UPDATE = 'database.update';
    public const ACTION_DATABASE_DELETE = 'database.delete';
    public const ACTION_DATABASE_VIEW_PASSWORD = 'database.view_password';

    public const ACTION_SCHEDULE_READ = 'schedule.read';
    public const ACTION_SCHEDULE_CREATE = 'schedule.create';
    public const ACTION_SCHEDULE_UPDATE = 'schedule.update';
    public const ACTION_SCHEDULE_DELETE = 'schedule.delete';

    public const ACTION_USER_READ = 'user.read';
    public const ACTION_USER_CREATE = 'user.create';
    public const ACTION_USER_UPDATE = 'user.update';
    public const ACTION_USER_DELETE = 'user.delete';

    public const ACTION_BACKUP_READ = 'backup.read';
    public const ACTION_BACKUP_CREATE = 'backup.create';
    public const ACTION_BACKUP_DELETE = 'backup.delete';
    public const ACTION_BACKUP_DOWNLOAD = 'backup.download';
    public const ACTION_BACKUP_RESTORE = 'backup.restore';

    public const ACTION_ALLOCATION_READ = 'allocation.read';
    public const ACTION_ALLOCATION_CREATE = 'allocation.create';
    public const ACTION_ALLOCATION_UPDATE = 'allocation.update';
    public const ACTION_ALLOCATION_DELETE = 'allocation.delete';

    public const ACTION_FILE_READ = 'file.read';
    public const ACTION_FILE_READ_CONTENT = 'file.read-content';
    public const ACTION_FILE_CREATE = 'file.create';
    public const ACTION_FILE_UPDATE = 'file.update';
    public const ACTION_FILE_DELETE = 'file.delete';
    public const ACTION_FILE_ARCHIVE = 'file.archive';
    public const ACTION_FILE_SFTP = 'file.sftp';

    public const ACTION_STARTUP_READ = 'startup.read';
    public const ACTION_STARTUP_UPDATE = 'startup.update';
    public const ACTION_STARTUP_DOCKER_IMAGE = 'startup.docker-image';

    public const ACTION_SETTINGS_RENAME = 'settings.rename';
    public const ACTION_SETTINGS_REINSTALL = 'settings.reinstall';

    public const ACTION_ACTIVITY_READ = 'activity.read';

    /**
     * Should timestamps be used on this model.
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     */
    protected $table = 'permissions';

    /**
     * Fields that are not mass assignable.
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Cast values to correct type.
     */
    protected $casts = [
        'subuser_id' => 'integer',
    ];

    public static array $validationRules = [
        'subuser_id' => 'required|numeric|min:1',
        'permission' => 'required|string',
    ];

    /**
     * All the permissions available on the system. You should use self::permissions()
     * to retrieve them, and not directly access this array as it is subject to change.
     *
     * @see \Pterodactyl\Models\Permission::permissions()
     */
    protected static array $permissions = [
        'websocket' => [
            'description' => 'Permite al usuario conectarse al websocket del servidor, dándoles acceso para ver la salida de la consola y las estadísticas del servidor en tiempo real.',
            'keys' => [
                'connect' => 'Permite al usuario conectarse a la instancia de websocket de un servidor para transmitir la consola.',
            ],
        ],

        'control' => [
            'description' => 'Permisos que controlan la capacidad de un usuario para controlar el estado de un servidor o enviar comandos.',
            'keys' => [
                'console' => 'Permite al usuario enviar comandos al servidor a través de la consola.',
                'start' => 'Permite al usuario encender el servidor si está apagado.',
                'stop' => 'Permite al usuario apagar el servidor si está encendido.',
                'restart' => 'Permite al usuario reiniciar el servidor. Esto les permite encender el servidor si está apagado, pero no poner el servidor en un estado completamente apagado.',
            ],
        ],

        'user' => [
            'description' => 'Permisos que permiten a un usuario gestionar otros subusuarios en un servidor. Nunca podrán editar su propia cuenta ni asignar permisos que ellos mismos no tengan.',
            'keys' => [
                'create' => 'Permite al usuario crear nuevos subusuarios para el servidor.',
                'read' => 'Permite al usuario ver subusuarios y sus permisos para el servidor.',
                'update' => 'Permite al usuario modificar otros subusuarios.',
                'delete' => 'Permite al usuario eliminar un subusuario del servidor.',
            ],
        ],

        'file' => [
            'description' => 'Permisos que controlan la capacidad de un usuario para modificar el sistema de archivos de este servidor.',
            'keys' => [
                'create' => 'Permite al usuario crear archivos y carpetas adicionales a través del Panel o carga directa.',
                'read' => 'Permite al usuario ver el contenido de un directorio, pero no ver el contenido o descargar archivos.',
                'read-content' => 'Permite al usuario ver el contenido de un archivo dado. Esto también permitirá al usuario descargar archivos.',
                'update' => 'Permite al usuario actualizar el contenido de un archivo o directorio existente.',
                'delete' => 'Permite al usuario eliminar archivos o directorios.',
                'archive' => 'Permite al usuario archivar el contenido de un directorio, así como descomprimir archivos existentes en el sistema.',
                'sftp' => 'Permite al usuario conectarse a SFTP y gestionar archivos del servidor utilizando los otros permisos de archivos asignados.',
            ],
        ],

        'backup' => [
            'description' => 'Permisos que controlan la capacidad de un usuario para generar y gestionar copias de seguridad del servidor.',
            'keys' => [
                'create' => 'Permite al usuario crear nuevas copias de seguridad para este servidor.',
                'read' => 'Permite al usuario ver todas las copias de seguridad que existen para este servidor.',
                'delete' => 'Permite al usuario eliminar copias de seguridad del sistema.',
                'download' => 'Permite al usuario descargar una copia de seguridad del servidor. Peligro: esto Permite al usuario acceder a todos los archivos del servidor en la copia de seguridad.',
                'restore' => 'Permite al usuario restaurar una copia de seguridad para el servidor. Peligro: esto permite al usuario eliminar todos los archivos del servidor en el proceso.',
            ],
        ],

        'allocation' => [
            'description' => 'Permisos que controlan la capacidad de un usuario para modificar las asignaciones de puertos para este servidor.',
            'keys' => [
                'read' => 'Permite al usuario ver todas las asignaciones actualmente asignadas a este servidor. Los usuarios con cualquier nivel de acceso a este servidor siempre pueden ver la asignación principal.',
                'create' => 'Permite al usuario asignar asignaciones adicionales al servidor.',
                'update' => 'Permite al usuario cambiar la asignación principal del servidor y adjuntar notas a cada asignación.',
                'delete' => 'Permite al usuario eliminar una asignación del servidor.',
            ],
        ],

        'startup' => [
            'description' => 'Permisos que controlan la capacidad de un usuario para ver los parámetros de inicio de este servidor.',
            'keys' => [
                'read' => 'Permite al usuario ver las variables de inicio de un servidor.',
                'update' => 'Permite al usuario modificar las variables de inicio del servidor.',
                'docker-image' => 'Permite al usuario modificar la imagen de Docker utilizada al ejecutar el servidor.',
            ],
        ],

        'database' => [
            'description' => 'Permisos que controlan el acceso de un usuario a la gestión de bases de datos para este servidor.',
            'keys' => [
                'create' => 'Permite al usuario crear una nueva base de datos para este servidor.',
                'read' => 'Permite al usuario ver la base de datos asociada con este servidor.',
                'update' => 'Permite al usuario cambiar la contraseña de una instancia de base de datos. Si el usuario no tiene el permiso de ver contraseña, no verá la contraseña actualizada.',
                'delete' => 'Permite al usuario eliminar una instancia de base de datos de este servidor.',
                'view_password' => 'Permite al usuario ver la contraseña asociada a una instancia de base de datos para este servidor.',
            ],
        ],

        'schedule' => [
            'description' => 'Permisos que controlan el acceso de un usuario a la gestión de horarios para este servidor.',
            'keys' => [
                'create' => 'Permite al usuario crear nuevos horarios para este servidor.',
                'read' => 'Permite al usuario ver horarios y las tareas asociadas con ellos para este servidor.',
                'update' => 'Permite al usuario actualizar horarios y programar tareas para este servidor.',
                'delete' => 'Permite al usuario eliminar horarios para este servidor.',
            ],
        ],

        'settings' => [
            'description' => 'Permisos que controlan el acceso de un usuario a la configuración de este servidor.',
            'keys' => [
                'rename' => 'Permite al usuario renombrar este servidor y cambiar su descripción.',
                'reinstall' => 'Permite al usuario desencadenar una reinstalación de este servidor.',
            ],
        ],

        'activity' => [
            'description' => 'Permisos que controlan el acceso de un usuario a los registros de actividad del servidor.',
            'keys' => [
                'read' => 'Permite al usuario ver los registros de actividad del servidor.',
            ],
        ],
    ];

    /**
     * Returns all the permissions available on the system for a user to
     * have when controlling a server.
     */
    public static function permissions(): Collection
    {
        return Collection::make(self::$permissions);
    }
}
