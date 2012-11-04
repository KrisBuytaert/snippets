# [Remember: No empty lines between comments and class definition]
class our-hornetq {


  class {'hornetq':
    hornetqconfigdir => '/usr/local/hornetq/config/';
  }

  $graphitehost = hiera('graphite')
  file { '/etc/nrpe.d/graphite.cfg':
    content =>  template('up-hornetq/graphite.cfg.erb'),
    group   => '0',
    mode    => '0644',
    owner   => '0',
  }


  @@nagios_service { "check_hornetq_${::hostname}":
    check_command       => 'check_nrpe_command!check_hornetq',
    service_description => 'check_hornetq_length',
    host_name           => $::fqdn,
    target              => "${icinga::params::targetdir}/services/puppet-service-${::fqdn}.cfg",

  }

  @@jmxtrans::graphite {"MessageCountMonitor-${::fqdn}":
    jmxhost      => hiera('hornetqserver'),
    jmxport      => "5446",
    objtype      => 'org.hornetq:type=Queue,*',
    attributes   => '"MessageCount","MessagesAdded","ConsrCount"',
    resultalias  => "hornetq",
    typenames    => "name",
    graphitehost => hiera('graphite'),
    graphiteport => "2003",
  }


}
