<?php

declare(strict_types=1);
/**
 * This file is part of DTM-PHP.
 *
 * @license  https://github.com/dtm-php/dtm-client/blob/master/LICENSE
 */
namespace DtmClient;

use DtmClient\Api\ApiInterface;
use DtmClient\Api\RequestBranch;
use DtmClient\Constants\TransType;
use DtmClient\Exception\FailureException;
use GuzzleHttp\Psr7\Response;

class Msg extends AbstractTransaction
{
    protected  $barrier;

    public function __construct(ApiInterface $api, Barrier $barrier)
    {
        $this->api = $api;
        $this->barrier = $barrier;
    }

    public function add(string $action, $payload)
    {
        TransContext::addStep(['action' => $action]);
        TransContext::addPayload($payload);
    }

    public function prepare(string $queryPrepared)
    {
        TransContext::setQueryPrepared($queryPrepared);
        return $this->api->prepare(TransContext::toArray());
    }

    public function submit()
    {
        return $this->api->submit(TransContext::toArray());
    }

    public function doAndSubmit(string $queryPrepared, callable $businessCall)
    {
        $this->barrier->barrierFrom(TransType::MSG, TransContext::getGid(), '00', 'msg');
        $this->prepare($queryPrepared);
        try {
            $businessCall();
            $this->submit();
        } catch (FailureException $failureException) {
            $this->api->abort();
            throw $failureException;
        } catch (\Exception $exception) {
            $this->queryPrepared($queryPrepared);
            throw $exception;
        }
    }

    protected function queryPrepared(string $queryPrepared)
    {
        // If busicall return an error other than failure, we will query the result
        $requestBranch = new RequestBranch();
        $requestBranch->method = 'GET';
        $requestBranch->branchId = TransContext::getBranchId();
        $requestBranch->op = TransContext::getOp();
        $requestBranch->url = $queryPrepared;
        /** @var Response $response */
        $response = $this->api->transRequestBranch($requestBranch);

        // if local transaction is fail, then abort transaction
        $this->api->abort(TransContext::toArray());
    }
}
