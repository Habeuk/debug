# debug

Help to debug code.

## installation

```
composer require habeuk/debug
```

### Author

<div>
<img alt="Logo habeuk" src="https://habeuk.com/sites/default/files/styles/medium/public/2023-08/logo-habeuk.png" height="40px">
<strong> Provide by <a href="https://habeuk.com/" target="_blank"> habeuk.com </a> </strong>
</div>

## Usage

You have to setup smtp parameters if you want to send logs by mail

DebugLogger::$smtpSettings['host'] = '';
DebugLogger::$smtpSettings['sender'] = "";
DebugLogger::$smtpSettings['sender_name'] = '';
DebugLogger::$smtpSettings['port'] = 587;
DebugLogger::$smtpSettings['user_name'] = "";
DebugLogger::$smtpSettings['password'] = "";
DebugLogger::$smtpSettings['recipients'] = ['email1', 'email2']
DebugLogger::$logDir = "log path"; // The path where you want to save the log files