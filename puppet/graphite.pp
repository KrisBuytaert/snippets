node /graphite[0-9]+/ {

  include graphite
  include graphite::demo
  include jmxtrans


  .... 

  Jmxtrans::Graphite <<| |>>  { notify => Service['jmxtrans'] }


}

