class repos { 
 yumrepo { 
    "foreman":
 				   descr => "Foreman stable repository",
				   baseurl => "http://yum.theforeman.org/stable",
    				gpgcheck => "0",
    				enabled => "1";

    "foreman-testing":
    				enabled => '0',
    				gpgcheck => '0',
    				descr => 'Foreman testing repository',
    				baseurl => 'http://yum.theforeman.org/test';

    "epel":
               descr => "Epel-5",
               baseurl => "http://mirror.eurid.eu/epel/5/i386/",
               enabled => 1,
               gpgcheck => 0;

    "kbs-el5-rb187":
               descr => "kbs-el5-rb187",
               enabled=>1,
               baseurl=> "http://centos.karan.org/el5/ruby187/i386",
               gpgcheck=>1,
               gpgkey=> "http://centos.karan.org/RPM-GPG-KEY-karan.org.txt";

    "rpmforge":
        			descr => "RPMforge.net - dag",
        			baseurl => absent,
        			mirrorlist => "http://apt.sw.be/redhat/el5/en/mirrors-rpmforge",
        			enabled => "1",
        			gpgkey => "file:///etc/pki/rpm-gpg/RPM-GPG-KEY-rpmforge-dag",
        			gpgcheck => "0",
        			priority => 1; 

    "blackup-noarch":
           		descr => "Blackop-Noarch",
            	baseurl => "http://blackopsoft.com/el5/RPMS/noarch/",
            	enabled => 1;

    "percona":
 		         descr       => "Percona",
            	enabled     =>1,
            	baseurl     => "http://repo.percona.com/centos/$releasever/os/$basearch/",
            	gpgcheck    => 0;

    "ourdelta":
            	descr       => "Ourdelta",
            	enabled     => 1,
            	gpgcheck    => 0,
            	baseurl     => "http://master.ourdelta.org/yum/CentOS-MySQL50/5Server/$basearch";

    "puppetlabs":
            	descr       => "Puppetlabs",
            	baseurl     => "http://yum.puppetlabs.com/",
            	enabled     => 1,
            	gpgcheck    => 0;

    "jpackage-generic":
               baseurl => "http://mirrors.dotsrc.org/jpackage/5.0/generic/free/",
             	descr      => "JPackage-generic",
            	gpgcheck    => 0,
            	enabled     => 1;

    "jpackage":
               baseurl => "http://mirrors.dotsrc.org/jpackage/5.0/redhat-el-5.0/free/",
               descr   => "Jpackage-el5",
               gpgcheck => 0,
               enabled         => 1;

 	 "clusterlabs-ng":
	    			descr => "Clusterlabs Next",
	    			baseurl => "http://www.clusterlabs.org/rpm-next/epel-5/",	
            	enabled => 1,
            	gpgcheck => 0;

 	 "clusterlabs":
            	descr => "Clusterlabs",
            	baseurl => "http://www.clusterlabs.org/rpm/epel-5/",
            	enabled => 1,
            	gpgcheck => 0;

        
	
	"gleich":
		descr => "Glei.ch Ruby pacakges",
		baseurl => "http://yum.glei.ch/el5/",
            	enabled => 1,
            	gpgcheck => 0;

	"bravenet":
		descr => "BraveNet",
		baseurl => "http://download.elff.bravenet.com/5/",
            	enabled => 1,
            	gpgcheck => 0;
 }
}

