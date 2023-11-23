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
use Stephane888\Debug\ExceptionDebug;

class DebugLogger implements DebugLoggerInterface {
  private $logger;
  
  /**
   *
   * @var \PHPMailer\PHPMailer\PHPMailer
   */
  protected $phpmailer = null;
  
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
    $phpmailer = $this->initSenderMail($message);
    
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
    $phpmailer = $this->initSenderMail($message);
    
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
    $phpmailer = $this->initSenderMail($message);
    
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
  public function alert($messageError, $subject, array $contenData = [], $fileName = "alert"): void {
    $phpmailer = $this->initSenderMail($subject);
    $this->logger->pushProcessor(new IntrospectionProcessor());
    $this->logger->pushProcessor(new MemoryUsageProcessor());
    $this->logger->pushProcessor(new WebProcessor());
    $handler = new PHPMailerHandler($phpmailer);
    $handler->setFormatter(new HtmlFormatter());
    $this->logger->pushHandler($handler);
    $this->logger->pushHandler(new StreamHandler($this->getFileDir() . "$fileName.log", LogLevel::ERROR));
    if (!is_array($messageError)) {
      $messageError = [
        $messageError
      ];
    }
    $this->logger->alert($messageError, $contenData);
  }
  
  /**
   *
   * @inheritdoc
   */
  public function emergency($message, array $contenData = [], $fileName = "emergency"): void {
    $phpmailer = $this->initSenderMail($message);
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
   * Permet d'envoyer un mail.
   *
   * @return boolean
   */
  public function sendMail() {
    /**
     *
     * @var \PHPMailer\PHPMailer\PHPMailer $phpmailer
     */
    $phpmailer = $this->initSenderMail();
    $phpmailer->Body = "Une erreur s'est produite";
    if (!$phpmailer->send()) {
      throw ExceptionDebug::exception($phpmailer->ErrorInfo);
    }
  }
  
  /**
   * initialise l'envoit de mail.
   *
   * @param string $subject
   * @return \PHPMailer\PHPMailer\PHPMailer
   */
  protected function initSenderMail($subject = 'Erreur sur un application') {
    if (!$this->phpmailer) {
      $smtpSettings = DebugLogger::$smtpSettings;
      if (empty($smtpSettings['host']) || empty($smtpSettings['user_name']) || empty($smtpSettings['password']) || empty($smtpSettings['recipients']) || empty($smtpSettings['sender']))
        throw ExceptionDebug::exception("Configuration d'envoie de mail incomplet");
      $phpmailer = new PHPMailer();
      $phpmailer->isSMTP();
      $phpmailer->Host = $smtpSettings['host'];
      $phpmailer->SMTPAuth = true;
      $phpmailer->Port = $smtpSettings['port'];
      $phpmailer->Username = $smtpSettings['user_name'];
      $phpmailer->Password = $smtpSettings['password'];
      $phpmailer->Subject = $subject;
      
      $phpmailer->setFrom($smtpSettings['sender'], 'Logging Server');
      foreach ($smtpSettings['recipients'] as $recipient) {
        $phpmailer->addAddress($recipient);
      }
      $this->phpmailer = $phpmailer;
    }
    return $this->phpmailer;
  }
  
  /**
   * Return the log directory.
   */
  protected function getFileDir(): string {
    if (DebugLogger::$logDir != '') {
      return DebugLogger::$logDir . '/';
    }
    if (defined('FULLROOT_WBU')) {
      DebugLogger::$logDir = FULLROOT_WBU;
      return DebugLogger::$logDir . '/';
    }
    return '/logs';
  }
  
}
