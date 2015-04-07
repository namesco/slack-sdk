<?php namespace ThreadMeUp\Slack;

use Guzzle\Http\Message\Response as GuzzleResponse;

class Response
{
    protected $rawResponse;
    protected $response;
    protected $errorTypes = array(
        'account_inactive' => 'Authentication token is for a deleted user or team',
        'channel_not_found' => 'Value passed for channel was invalid',
        'invalid_auth' => 'Invalid authentication token',
        'is_archived' => 'Channel has been archived',
        'msg_too_long' => 'Message text is too long',
        'no_text' => 'No message text provided',
        'not_authed' => 'No authentication token provided',
        'not_in_channel' => 'Cannot post user messages to a channel they are not in',
        'rate_limited' => 'Application has posted too many messages',
    );
    protected $error = null;

    public function __construct(GuzzleResponse $response)
    {
        $this->rawResponse = $response;
        $this->response = json_decode(json_encode($this->rawResponse->json()));
    }

    public function statusCode()
    {
        return $this->rawResponse->getStatusCode();
    }

    public function isOkay()
    {
        $isOkay = filter_var($this->response->ok, FILTER_VALIDATE_BOOLEAN);
        if (!$isOkay) $this->error = $this->errorTypes[$this->response->error];

        return $isOkay;
    }

    public function getError()
    {
        return $this->error;
    }
}
