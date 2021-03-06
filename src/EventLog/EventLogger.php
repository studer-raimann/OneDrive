<?php
namespace srag\Plugins\OneDrive\EventLog;

/**
 * Class EventLogRepository
 * @author Theodor Truffer <tt@studer-raimann.ch>
 */
class EventLogger
{
    public static function logUploadStarted(
        int $user_id,
        int $obj_id,
        string $file_path,
        string $renamed_from = ''
    ) {
        self::log(
            $user_id,
            $obj_id,
            EventType::uploadStarted(),
            $file_path,
            ObjectType::file(),
            $renamed_from ? ['renamed_from' => $renamed_from] : []
        );
    }
    
    public static function logUploadComplete(
        int $user_id,
        int $obj_id,
        string $file_path
    ) {
        self::log(
            $user_id,
            $obj_id,
            EventType::uploadComplete(),
            $file_path,
            ObjectType::file(),
            []
        );
    }
    
    public static function logUploadAborted(
        int $user_id,
        int $obj_id,
        string $file_path
    ) {
        self::log(
            $user_id,
            $obj_id,
            EventType::uploadAborted(),
            $file_path,
            ObjectType::file(),
            []
        );
    }

    public static function logUploadFailed(
        int $user_id,
        int $obj_id,
        string $file_path,
        string $message = ''
    ) {
        self::log(
            $user_id,
            $obj_id,
            EventType::uploadFailed(),
            $file_path,
            ObjectType::file(),
            $message ? ['error_msg' => $message] : []
        );
    }

    public static function logObjectDeleted(
        int $user_id,
        int $obj_id,
        string $object_path,
        ObjectType $object_type
    ) {
        self::log(
            $user_id,
            $obj_id,
            EventType::objectDeleted(),
            $object_path,
            $object_type,
            []
        );
    }
    
    public static function logObjectRenamed(
        int $user_id,
        int $obj_id,
        string $object_path_old,
        string $object_name_new,
        ObjectType $object_type
    ) {
        self::log(
            $user_id,
            $obj_id,
            EventType::objectRenamed(),
            $object_path_old,
            $object_type,
            ['new_name' => $object_name_new]
        );
    }
    
    protected static function log(
        int $user_id,
        int $obj_id,
        EventType $event_type,
        string $object_path,
        ObjectType $object_type,
        array $additional_data
    ) {
        $entry = new EventLogEntryAR();
        $entry->setObjId($obj_id);
        $entry->setTimestamp(date('Y-m-d H:i:s', time()));
        $entry->setEventType($event_type);
        $entry->setUserId($user_id);
        $entry->setPath($object_path);
        $entry->setObjectType($object_type);
        $entry->setAdditionalData($additional_data);
        $entry->create();
    }
}
