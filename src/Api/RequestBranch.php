<?php

declare(strict_types=1);
/**
 * This file is part of DTM-PHP.
 *
 * @license  https://github.com/dtm-php/dtm-client/blob/master/LICENSE
 */
namespace DtmClient\Api;

use Google\Protobuf\GPBEmpty;
use Google\Protobuf\Internal\Message;

class RequestBranch
{
    public  $method;

    public  $body = [];

    public  $branchId;

    public  $op;

    public  $url;

    public  $branchHeaders = [];

    public  $grpcArgument;

    public  $grpcMetadata = [];

    public  $grpcDeserialize = [GPBEmpty::class, 'decode'];

    public  $grpcOptions = [];

    public  $jsonRpcServiceName = '';

    public  $jsonRpcServiceParams = [];
}
