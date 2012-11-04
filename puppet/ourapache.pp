class our_apache {
  class {
    'apache':;
    'apache::mod::rewrite':;
    'php':;
    'php::apc':;
    'php::logging': logfile => '/var/log/php.log';
  }
  Class['php'] -> Class['php::apc']

  include ::collectd::plugin::apache

  logrotate::file { 'php-error':
    log        => $php::logging::logfile,
    options    => [
      'daily',
      'rotate 7',
      'delaycompress',
      'compress',
      'notifempty',
      'create',
    ],
  }
  firewall{'080 http':
    dport  => '80',
    proto  => 'tcp',
    action => 'accept',
  }
}
