<?php

declare(strict_types=1);
/**
 * This file is part of DTM-PHP.
 *
 * @license  https://github.com/dtm-php/dtm-client/blob/master/LICENSE
 */
namespace DtmClient;

use DtmClient\Util\Str;
use Hyperf\Context\Context;

/**
 * All properties in this class are read-only.
 * All properties data will be stored in the coroutine context.
 */
class TransContext extends Context
{
    use TransOption;

    private static  $gid;

    private static  $transType;

    private static  $dtm;

    private static  $customData;

    /**
     * Use in MSG/SAGA.
     */
    private static  $steps;

    /**
     * Use in MSG/SAGA.
     * @var string[]
     */
    private static  $payloads;

    private static  $binPayLoads;

    /**
     * Use in XA/TCC.
     */
    private static  $branchId;

    /**
     * Use in XA/TCC.
     */
    private static  $subBranchId;

    /**
     * Use in XA/TCC.
     */
    private static  $op;

    /**
     * Use in MSG.
     */
    private static  $queryPrepared;

    public static function toArray()
    {
        $data = self::getContainer();
        $array = [];
        foreach ($data as $key => $value) {
            if (str_starts_with($key, TransContext::class . '.')) {
                $array[Str::snake(str_replace(TransContext::class . '.', '', $key))] = $value;
            }
        }
        return $array;
    }

    public static function init(string $gid, string $transType, string $branchId)
    {
        static::setGid($gid);
        static::setTransType($transType);
        static::setBranchId($branchId);
    }

    public static function getGid()
    {
        return static::get(static::class . '.gid', '');
    }

    public static function setGid(string $gid)
    {
        static::set(static::class . '.gid', $gid);
    }

    public static function getTransType()
    {
        return static::get(static::class . '.transType');
    }

    public static function setTransType(string $transType)
    {
        static::set(static::class . '.transType', $transType);
    }

    public static function getDtm()
    {
        return static::get(static::class . '.dtm');
    }

    public static function setDtm(string $dtm)
    {
        static::set(static::class . '.dtm', $dtm);
    }

    public static function getCustomData()
    {
        return static::get(static::class . '.customData');
    }

    public static function setCustomData(string $customData)
    {
        static::set(static::class . '.customData', $customData);
    }

    public static function getSteps()
    {
        return static::get(static::class . '.steps') ?? [];
    }

    public static function setSteps(array $steps)
    {
        static::set(static::class . '.steps', $steps);
    }

    public static function addStep(array $step)
    {
        static::setSteps(array_merge(static::getSteps(), [$step]));
    }

    public static function getPayloads()
    {
        return static::get(static::class . '.payloads') ?? [];
    }

    public static function setPayloads(array $payloads)
    {
        static::set(static::class . '.payloads', $payloads);
    }

    public static function addPayload(array $payload)
    {
        static::setPayloads(array_merge(static::getPayloads(), $payload));
    }

    public static function getBinPayLoads()
    {
        return static::get(static::class . '.binPayLoads') ?? [];
    }

    public static function setBinPayLoads(array $binPayLoads)
    {
        static::set(static::class . '.binPayLoads', $binPayLoads);
    }

    public static function addBinPayload(array $binPayLoad)
    {
        static::setBinPayLoads(array_merge(static::getBinPayLoads(), $binPayLoad));
    }

    public static function getBranchId()
    {
        return static::get(static::class . '.branchId', '');
    }

    public static function setBranchId(string $branchId)
    {
        static::set(static::class . '.branchId', $branchId);
    }

    public static function getSubBranchId()
    {
        return static::get(static::class . '.subBranchId', 0);
    }

    public static function setSubBranchId(int $subBranchId)
    {
        static::set(static::class . '.subBranchId', $subBranchId);
    }

    public static function getOp()
    {
        return static::get(static::class . '.op');
    }

    public static function setOp(string $op)
    {
        static::set(static::class . '.op', $op);
    }

    public static function getQueryPrepared()
    {
        return static::get(static::class . '.queryPrepared');
    }

    public static function setQueryPrepared(string $queryPrepared)
    {
        static::set(static::class . '.queryPrepared', $queryPrepared);
    }
}
