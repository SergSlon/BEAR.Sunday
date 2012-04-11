<?php
/**
 * Exception handler
 */
namespace BEAR\Framework\scripts\excection_handler;

use BEAR\Resource\Exception\BadRequest;
use BEAR\Resource\Exception\MethodNotAllowed;
use BEAR\Resource\Exception\InvalidParameter;
use BEAR\Resource\Exception\InvalidScheme;
use BEAR\Resource\Exception\ResourceNotFound;
use Ray\Di\Exception\InvalidBinding;
use BEAR\Framework\Resource\Page\Error;
use BEAR\Framework\Web\HttpFoundation as Output;

set_exception_handler(function(\Exception $e) {
    $mode = isset($_ENV['BEAR_OUTPUT_MODE']) ? $_ENV['BEAR_OUTPUT_MODE'] : 'prod';
    $expectionId = 'e' . substr(md5((string)$e), 0, 5);
    try {
        $response = new Error;
        throw $e;
    } catch (NotFound $e) {
        $response->code = 404;
        $response->body = 'The requested URI was not found on this service.';
        goto NOT_FOUND;
    } catch (BadRequest $e) {
        $response->code = 400;
        $response->body = 'You sent a request that this service cound not understand.';
        goto METHOD_NOT_ALLOWED;
    } catch (InvalidParameter $e) {
        $response->code = 400;
        $response->body = 'You sent a request that query is not valid.';
        goto BAD_REQUEST;
    } catch (InvalidScheme $e) {
        $response->code = 400;
        $response->body = 'You sent a request that scheme is not valid.';
        goto BAD_REQUEST;
    } catch (MethodNotAllowed $e) {
        $response->code = 405;
        $response->body = 'The requested method is not allowed for this URI.';
        goto METHOD_NOT_ALLOWED;
    } catch (ResourceNotFound $e) {
        $response->code = 404;
        $response->body = 'The requested URI was not found on this service.';
        goto NOT_FOUND;
    } catch (InvalidBinding $e) {
        goto INVALID_BINDING;
    } catch (\Exception $e) {
        goto SERVER_ERROR;
    }

OK:
    (new Output)->setResource($response)->prepare()->output();
    exit(0);

INVALID_BINDING:
SERVER_ERROR:
    $response->code = 500;
    $response->body = "Internal error occured ({$expectionId})";
NOT_FOUND:
BAD_REQUEST:
METHOD_NOT_ALLOWED:
    $response->headers['X-EXCEPTION-CLASS'] = get_class($e);
    $response->headers['X-EXCEPTION-MESSAGE'] = str_replace("\n", ' ', $e->getMessage());
    $response->headers['X-EXCEPTION-CODE'] = $e->getCode();
    $response->headers['X-EXCEPTION-FILE-LINE'] = $e->getFile() . ':' . $e->getLine();
    $previous =  $e->getPrevious() ? (get_class($e->getPrevious()) .': ' . str_replace("\n", ' ', $e->getPrevious()->getMessage())) : '-';
    $response->headers['X-EXCEPTION-PREVIOUS'] =  $previous;
    $response->headers['X-EXCEPTION-ID'] = $expectionId;
    (new Output)->setResource($response)->setException($e, $expectionId)->prepare(false)->output();
    exit(1);
});