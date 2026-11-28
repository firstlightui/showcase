<?php

namespace App\Support\Chat;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * The Chat recipe's server, running inside Showcase. The Chat plugin talks
 * HTTP to HOST; requests to that host never leave the phone: they are
 * handed to chat-server's own routes in this app. A real app points
 * CHAT_SERVER_URL at its backend instead and needs none of this.
 */
class InAppChatServer
{
    public const HOST = 'chat.showcase.invalid';

    /** Before chat-server registers its routes: from a service provider's register(). */
    public static function configure(): void
    {
        config([
            'firstlight-chat.server.url' => 'http://'.self::HOST,
            'chat.routes.middleware' => ['api', AuthenticateDemoPerson::class],
            'chat.attachments.disk' => 'local',
        ]);
    }

    public static function install(): void
    {
        Http::fake([self::HOST.'/*' => fn (ClientRequest $request) => self::handle($request)]);
        // Requests to every other host are sent as usual. Don't keep a record of these.
        Factory::macro('stopRecordingShowcaseChat', function () {
            $this->recording = false;
        });
        Http::stopRecordingShowcaseChat();
    }

    private static function handle(ClientRequest $request)
    {
        $server = ['HTTP_ACCEPT' => 'application/json', 'HTTP_AUTHORIZATION' => $request->header('Authorization')[0] ?? ''];
        foreach (['Firstlight-Chat-Version', 'Content-Type'] as $header) {
            $value = $request->header($header)[0] ?? null;
            if ($value !== null) {
                $server[$header === 'Content-Type' ? 'CONTENT_TYPE' : 'HTTP_'.strtoupper(str_replace('-', '_', $header))] = $value;
            }
        }

        $parameters = [];
        $files = [];
        $content = null;
        if ($request->isMultipart()) {
            unset($server['CONTENT_TYPE']);
            foreach ($request->data() as $part) {
                if (isset($part['filename'])) {
                    $tmp = tempnam(sys_get_temp_dir(), 'chat-upload');
                    $contents = $part['contents'];
                    file_put_contents($tmp, is_resource($contents) ? stream_get_contents($contents, -1, 0) : (string) $contents);
                    $files[$part['name']] = new UploadedFile($tmp, $part['filename'], null, null, true);
                } else {
                    $parameters[$part['name']] = $part['contents'];
                }
            }
        } else {
            $content = $request->body();
        }

        $kernel = app(Kernel::class);
        $previous = app()->bound('request') ? app('request') : null;
        $inner = Request::create($request->url(), $request->method(), $parameters, [], $files, $server, $content);
        $response = $kernel->handle($inner);
        $kernel->terminate($inner, $response);
        if ($previous !== null) {
            app()->instance('request', $previous);
        }

        $body = match (true) {
            $response instanceof BinaryFileResponse => file_get_contents($response->getFile()->getPathname()),
            $response instanceof StreamedResponse => (function () use ($response) {
                ob_start();
                $response->sendContent();

                return ob_get_clean();
            })(),
            default => $response->getContent(),
        };

        return Http::response($body, $response->getStatusCode(), $response->headers->all());
    }
}
