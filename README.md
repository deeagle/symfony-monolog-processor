# Symfony-Monolog-Processor

Simple Symfony demo that shows how to plug a custom Monolog processor into your application logs.
When you hit the sample route, the controller logs a couple of messages; the processor enriches
each record with request/session details so you can see additional context in your log files.

## What’s inside

- Symfony application skeleton
- Custom Monolog processor: `src/Logger/SessionRequestProcessor.php`
- Monolog wiring: `config/packages/monolog.yaml and config/services.yaml`
- Demo controller/route: `/lucky/number` (`src/Controller/LuckyController.php`)
- Twig template for rendering the lucky number (`templates/lucky/number.html.twig`)

## Requirements

- PHP 8.2+ and Composer
- Optional: Symfony CLI (convenience)

## Getting started

1) Install dependencies:
   `composer install`

2) Start environment:
   `make dev`

3) Open the demo route:
   [http://127.0.0.1:8000/lucky/number](http://127.0.0.1:8000/lucky/number)

4) Check the logs
   Check `var/log/dev.log` while you browse the route. You should see messages from LuckyController
   along with extra context added by the SessionRequestProcessor.

## Notes

- If you change logging settings, look at `config/packages/monolog.yaml`.
- The processor is registered in `config/services.yaml` and is automatically applied to log records.
- The console log is fine, but you won't watch the extra information there!
  **You must dive the log-files!**

## Example logs

- Two different calls, to see the different IDs (e.g. useful for filtering within ELK)

```bash
[2025-11-28T10:57:21.101069+00:00] request.INFO: Matched route "app_lucky_number". {"route":"app_lucky_number","route_parameters":{"_route":"app_lucky_number","_controller":"App\\Controller\\LuckyController::number"},"request_uri":"http://localhost:8000/lucky/number","method":"GET"} {"request_id":"deelog_-6843088609f722e8","uid":"946235b"}
[2025-11-28T10:57:21.102669+00:00] security.DEBUG: Checking for authenticator support. {"firewall_name":"main","authenticators":0} {"request_id":"deelog_-6843088609f722e8","uid":"946235b"}
[2025-11-28T10:57:21.102930+00:00] app.INFO: Attention! Watch the log-files to see the additional information [] {"request_id":"deelog_-6843088609f722e8","uid":"946235b"}
[2025-11-28T10:57:21.103220+00:00] app.DEBUG: LuckyController::number: enter [] {"request_id":"deelog_-6843088609f722e8","uid":"946235b"}
[2025-11-28T10:57:21.103253+00:00] app.DEBUG: LuckyController::number: got number 83 [] {"request_id":"deelog_-6843088609f722e8","uid":"946235b"}
[2025-11-28T10:57:21.113246+00:00] request.INFO: Matched route "app_lucky_number". {"route":"app_lucky_number","route_parameters":{"_route":"app_lucky_number","_controller":"App\\Controller\\LuckyController::number"},"request_uri":"http://localhost:8000/lucky/number","method":"GET"} {"request_id":"deelog_-29635794ebd1033d","uid":"fb26066"}
[2025-11-28T10:57:21.114017+00:00] security.DEBUG: Checking for authenticator support. {"firewall_name":"main","authenticators":0} {"request_id":"deelog_-29635794ebd1033d","uid":"fb26066"}
[2025-11-28T10:57:21.114199+00:00] app.INFO: Attention! Watch the log-files to see the additional information [] {"request_id":"deelog_-29635794ebd1033d","uid":"fb26066"}
[2025-11-28T10:57:21.114558+00:00] app.DEBUG: LuckyController::number: enter [] {"request_id":"deelog_-29635794ebd1033d","uid":"fb26066"}
[2025-11-28T10:57:21.114608+00:00] app.DEBUG: LuckyController::number: got number 64 [] {"request_id":"deelog_-29635794ebd1033d","uid":"fb26066"}
```

## Weblinks

- [https://github.com/Seldaek/monolog/tree/main/src/Monolog/Processor]
- [https://symfony.com/doc/current/logging/processors.html]
- [https://github.com/symfony/symfony/discussions/50803]
