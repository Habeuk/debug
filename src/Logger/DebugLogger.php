<?php

namespace Stephane888\Debug\Logger;

use Exception;
use Monolog\Formatter\HtmlFormatter;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\WebProcessor;
use PHPMailer\PHPMailer\PHPMailer;
use Psr\Log\LogLevel;
use Stephane888\Debug\Logger\PHPMailerHandler;

class DebugLogger implements DebugLoggerInterface {
  private $logger;
  
  /**
   * The values for PHPMailer to send email via SMTP
   * [
   * 'host' => '', // mail.example.com
   * 'sender' => '', // john@example.com
   * 'sender_name' => '', // Loggin Habeuk
   * 'port' => '' // 587
   * 'user_name => '' // johndoe
   * 'password => '' // *******
   * 'recipients => [] // [ 'john@example.com', 'doe@example.com' ]
   * ]
   *
   * @var array
   */
  public static $smtpSettings = [
    'host' => '',
    'sender' => '',
    'sender_name' => '',
    'port' => '',
    'user_name' => '',
    'password' => '',
    'recipients' => []
  ];
  
  /**
   * The log directory path // /var/www/my-app/logs
   */
  public static $logDir = '';
  
  public function __construct(string $channel) {
    $this->logger = new Logger($channel);
  }
  
  /**
   *
   * @inheritdoc
   */
  public function info($message, array $contenData = [], $fileName = "info"): void {
    $this->logger->pushHandler(new RotatingFileHandler($this->getFileDir() . "/$fileName.log", 7));
    $this->logger->info($message, $contenData);
  }
  
  /**
   *
   * @inheritdoc
   */
  public function notice($message, array $contenData = [], $fileName = "notice"): void {
    $this->logger->pushHandler(new RotatingFileHandler($this->getFileDir() . "/$fileName.log", 7));
    $this->logger->notice($message, $contenData);
  }
  
  /**
   *
   * @inheritdoc
   */
  public function debug($message, array $contenData = [], $fileName = "debug"): void {
    $this->logger->pushHandler(new RotatingFileHandler($this->getFileDir() . "/$fileName.log", 7));
    $this->logger->notice($message, $contenData);
  }
  
  /**
   *
   * @inheritdoc
   */
  public function warning($message, array $contenData = [], $fileName = "warning"): void {
   
    
    $smtpSettings = DebugLogger::$smtpSettings;
    
    $phpmailer = new PHPMailer();
    $phpmailer->isSMTP();
    $phpmailer->Host = $smtpSettings['host'];
    $phpmailer->SMTPAuth = true;
    $phpmailer->Port = $smtpSettings['port'];
    $phpmailer->Username = $smtpSettings['user_name'];
    $phpmailer->Password = $smtpSettings['password'];
    
    $phpmailer->setFrom($smtpSettings['sender'], 'Logging Server');
    foreach ($smtpSettings['recipients'] as $recipient) {
      $phpmailer->addAddress($recipient);
    }
    
    $this->logger->pushProcessor(new IntrospectionProcessor());
    $this->logger->pushProcessor(new MemoryUsageProcessor());
    $this->logger->pushProcessor(new WebProcessor());
    
    $handler = new PHPMailerHandler($phpmailer);
    $handler->setFormatter(new HtmlFormatter());
    
    $this->logger->pushHandler($handler);
    $this->logger->pushHandler(new StreamHandler($this->getFileDir() . "$fileName.log", LogLevel::ERROR));
    $this->logger->warning($message, $contenData);
  }
  
  /**
   *
   * @inheritdoc
   */
  public function error($message, array $contenData = [], $fileName = "error"): void {
   
    
    $smtpSettings = DebugLogger::$smtpSettings;
    
    $phpmailer = new PHPMailer();
    $phpmailer->isSMTP();
    $phpmailer->Host = $smtpSettings['host'];
    $phpmailer->SMTPAuth = true;
    $phpmailer->Port = $smtpSettings['port'];
    $phpmailer->Username = $smtpSettings['user_name'];
    $phpmailer->Password = $smtpSettings['password'];
    
    $phpmailer->setFrom($smtpSettings['sender'], 'Logging Server');
    foreach ($smtpSettings['recipients'] as $recipient) {
      $phpmailer->addAddress($recipient);
    }
    
    $this->logger->pushProcessor(new IntrospectionProcessor());
    $this->logger->pushProcessor(new MemoryUsageProcessor());
    $this->logger->pushProcessor(new WebProcessor());
    
    $handler = new PHPMailerHandler($phpmailer);
    $handler->setFormatter(new HtmlFormatter());
    
    $this->logger->pushHandler($handler);
    $this->logger->pushHandler(new StreamHandler($this->getFileDir() . "$fileName.log", LogLevel::ERROR));
    $this->logger->error($message, $contenData);
  }
  
  /**
   *
   * @inheritdoc
   */
  public function critical($message, array $contenData = [], $fileName = "critical"): void {
   
    
    $smtpSettings = DebugLogger::$smtpSettings;
    
    $phpmailer = new PHPMailer();
    $phpmailer->isSMTP();
    $phpmailer->Host = $smtpSettings['host'];
    $phpmailer->SMTPAuth = true;
    $phpmailer->Port = $smtpSettings['port'];
    $phpmailer->Username = $smtpSettings['user_name'];
    $phpmailer->Password = $smtpSettings['password'];
    
    $phpmailer->setFrom($smtpSettings['sender'], 'Logging Server');
    foreach ($smtpSettings['recipients'] as $recipient) {
      $phpmailer->addAddress($recipient);
    }
    
    $this->logger->pushProcessor(new IntrospectionProcessor());
    $this->logger->pushProcessor(new MemoryUsageProcessor());
    $this->logger->pushProcessor(new WebProcessor());
    
    $handler = new PHPMailerHandler($phpmailer);
    $handler->setFormatter(new HtmlFormatter());
    
    $this->logger->pushHandler($handler);
    $this->logger->pushHandler(new StreamHandler($this->getFileDir() . "$fileName.log", LogLevel::ERROR));
    $this->logger->critical($message, $contenData);
  }
  
  /**
   *
   * @inheritdoc
   */
  public function alert($message, array $contenData = [], $fileName = "alert"): void {
   
    
    $smtpSettings = DebugLogger::$smtpSettings;
    
    $phpmailer = new PHPMailer();
    $phpmailer->isSMTP();
    $phpmailer->Host = $smtpSettings['host'];
    $phpmailer->SMTPAuth = true;
    $phpmailer->Port = $smtpSettings['port'];
    $phpmailer->Username = $smtpSettings['user_name'];
    $phpmailer->Password = $smtpSettings['password'];
    
    $phpmailer->setFrom($smtpSettings['sender'], 'Logging Server');
    foreach ($smtpSettings['recipients'] as $recipient) {
      $phpmailer->addAddress($recipient);
    }
    
    $this->logger->pushProcessor(new IntrospectionProcessor());
    $this->logger->pushProcessor(new MemoryUsageProcessor());
    $this->logger->pushProcessor(new WebProcessor());
    
    $handler = new PHPMailerHandler($phpmailer);
    $handler->setFormatter(new HtmlFormatter());
    
    $this->logger->pushHandler($handler);
    $this->logger->pushHandler(new StreamHandler($this->getFileDir() . "$fileName.log", LogLevel::ERROR));
    $this->logger->alert($message, $contenData);
  }
  
  /**
   *
   * @inheritdoc
   */
  public function emergency($message, array $contenData = [], $fileName = "emergency"): void {
   
    
    $smtpSettings = DebugLogger::$smtpSettings;
    
    $phpmailer = new PHPMailer();
    $phpmailer->isSMTP();
    $phpmailer->Host = $smtpSettings['host'];
    $phpmailer->SMTPAuth = true;
    $phpmailer->Port = $smtpSettings['port'];
    $phpmailer->Username = $smtpSettings['user_name'];
    $phpmailer->Password = $smtpSettings['password'];
    
    $phpmailer->setFrom($smtpSettings['sender'], 'Logging Server');
    foreach ($smtpSettings['recipients'] as $recipient) {
      $phpmailer->addAddress($recipient);
    }
    $this->logger->pushProcessor(new IntrospectionProcessor());
    $this->logger->pushProcessor(new MemoryUsageProcessor());
    $this->logger->pushProcessor(new WebProcessor());
    
    $handler = new PHPMailerHandler($phpmailer);
    $handler->setFormatter(new HtmlFormatter());
    
    $this->logger->pushHandler($handler);
    $this->logger->pushHandler(new StreamHandler($this->getFileDir() . "$fileName.log", LogLevel::ERROR));
    $this->logger->emergency($message, $contenData);
  }
  
  /**
   * Return the log directory.
   */
  protected function getFileDir(): string {
    if (DebugLogger::$logDir != '') {
      return DebugLogger::$logDir;
    }
    if (defined('FULLROOT_WBU')) {
      DebugLogger::$logDir = FULLROOT_WBU;
      return DebugLogger::$logDir;
    }
    return '/logs';
  }
  
}
