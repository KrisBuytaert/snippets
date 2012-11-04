class icinga::collect {
  if $icinga::server {
    # Set defaults for collected resources.
    Nagios_host <<| |>>              { notify => Service[$icinga::service_server] }
    Nagios_service <<| |>>           { notify => Service[$icinga::service_server] }
    .....
  }


  if $icinga::client {
    @@nagios_host { $::fqdn:
      ensure             => present,
      alias              => $::hostname,
      address            => $::ipaddress,
      max_check_attempts => $icinga::max_check_attempts,
      check_command      => 'check-host-alive',
      use                => 'linux-server',
      hostgroups         => 'linux-servers',
      action_url         => '/pnp4nagios/graph?host=$HOSTNAME$',
      target             => "${icinga::targetdir}/hosts/puppet-host-${::fqdn}.cfg",
    }


    @@nagios_service { "check_ping_${::hostname}":
      check_command       => 'check_ping!100.0,20%!500.0,60%',
      service_description => 'Ping',
      action_url          => '/pnp4nagios/graph?host=$HOSTNAME$&srv=$SERVICEDESC$',
    }

    .....
  }

}

